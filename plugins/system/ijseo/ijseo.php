<?php   
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgSystemIjseo extends JPlugin{    
   
   	function plgSystemIjseo(& $subject, $config){
		parent::__construct($subject, $config);
   	}
	
	function getParams(){
		$database = JFactory::getDBO();
		$sql = "select `params` from #__ijseo_config";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result);	
		return $params;
	}
	
	function onAfterDispatch(){		
		$seo_title = "";
		$seo_keywords = "";
		$seo_description = "";
		
		$app = JFactory::getApplication();
		$domain = JURI::base();
		$db = JFactory::getDBO();
		
		if($app->isAdmin()){			
			return;
		}
		
		$result = $this->getMetaValues();		
		if(isset($result) && is_array($result) && count($result) > 0){
			$seo_title = $result["metatile"];
			$seo_keywords = $result["metakey"];
			$seo_description = $result["metadesc"];
		}
		$document = JFactory::getDocument();
		
		if(trim($seo_title) != ""){
			$document->setTitle($seo_title);
            $document->setMetaData("title", $seo_title);
		}	
		if(trim($seo_keywords) != ""){
			$params = $this->getParams();
			if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
				$metakey = trim($seo_title);			
				$delimiters = str_split(trim($params->delimiters));			
				$metakey = str_replace($delimiters, ",", $metakey);
				$document->setMetaData("keywords", trim($metakey));
			}
			else{
				$document->setMetaData("keywords", trim($seo_keywords));
			}	
		}	
		if(trim($seo_description) != "") {
			$document->setDescription($seo_description);
		}
		return true;
	}
	
	function getMetaValues(){
		$db = JFactory::getDBO();
		$option = JRequest::getVar('option', '');
		$view = JRequest::getVar('view', '');
		$task = JRequest::getVar('task', '');
		$layout = JRequest::getVar('layout', '');
		$show = JRequest::getVar('show', '');
		$itemid = JRequest::getVar('Itemid', 0);
		$menutype = "";
		$menuName = "";
		$return = "";
		
		if(isset($this->show) && ($show == "" || empty($show)) && $view != 'shop.product_details' && (($view!="itemlist" && $layout!='category') || ($view=="itemlist" && $layout=='category'))){
			$query = " select menutype, id, title, link ".
					 " from #__menu ".
					 " where id = ".intval($itemid);					 				    
			$db->setQuery($query);
			$res = $db->loadAssoc();
			$menutype = $res["menutype"];
			$menuName = $db->Quote($res["title"]);
		}		
		//get meta values for articles, menus or any component		
		$is_menu = $this->isMenu($option, $task, $view);
		
		if(trim($is_menu) == "" && ($view == "article" || $view == "featured")){//for articles
			$return = $this->getArticlesMeta();
		}
		elseif(trim($is_menu) == ""){ // for another components
			$return = $this->getComponentsMeta($option, $task, $view);
		}
		else{//for menus
			$return = $this->getMenusMeta($menutype);
		}
		return $return;
	}
	
	function isMenu($option, $task, $view){
		$id = JRequest::getInt("id");
		$layout = JRequest::getVar("layout", "");
		$view = JRequest::getVar("view", "");
		
		$where = " 1=1 ";
		
		if ($option == "com_easyblog" && ($view == 'categories' || $view == 'entry') && $id > 0) {
			return '';
		}
		
		switch($option){
			case "com_news_portal" : {
				$where .= " and menutype not in ('categories', 'sections', 'news-portal-content') ";
				break;
			}
			case 'com_digistore' : {
				$where .= " and menutype not in ('digicats') ";
				break;
			}
			case 'com_magazine' : {
				$where .= " and menutype not in ('magazine-content', 'magazines') ";
				break;
			}
		}
		
		if(trim($option) != ""){
			$where .= " and link like '%option=".$option."%'";
		}
		if(trim($task) != ""){
			$where .= " and link like '%task=".$task."%'";
		}
		if(trim($view) != ""){
			$where .= " and link like '%view=".$view."%'";
		}
		if($id != ""){
			$where .= " and link like '%id=".$id."%'";
		}
		if($layout != ""){
			$where .= " and link like '%layout=".$layout."%'";
		}
		
		
		if($where != " 1=1 "){
			$sql = "select `menutype` from #__menu where ".$where." and `published`=1";
			$db = JFactory::getDBO();
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadColumn();
			$result = @$result["0"];
			if($result == NULL){
				return "";
			}
			else{
				return trim($result);
			}
		}
	 }
	 
	function getComponentsMeta($option, $task, $view){
		$db = JFactory::getDBO();
		$return_array = array("metatile"=>"", "metakey"=>"", "metadesc"=>"");		
		switch($option){
			case "com_mtree" : {			
				if(trim($task) != "" && trim($task) == "listcats"){
					$id = JRequest::getVar("cat_id", 0);
					$sql = "select mc.metakey, mc.metadesc, sm.titletag from #__mt_cats mc left join #__ijseo_metags sm on mc.cat_id=sm.id where sm.mtype='mt_cat' and mc.cat_id=".intval($id);					
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if(isset($result) && is_array($result) && count($result) > 0){
						$return_array["metatile"] = trim($result["0"]["titletag"]);
						$return_array["metakey"] = trim($result["0"]["metakey"]);
						$return_array["metadesc"] = trim($result["0"]["metadesc"]);
					}
				}
				elseif(trim($task) != "" && trim($task) == "viewlink"){
					$id = JRequest::getVar("link_id", 0);
					$sql = "select ml.metakey, ml.metadesc, sm.titletag from #__mt_links ml left join #__ijseo_metags sm on ml.link_id=sm.id where sm.mtype='mt_list' and ml.link_id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if(isset($result) && is_array($result) && count($result) > 0){
						$return_array["metatile"] = trim($result["0"]["titletag"]);
						$return_array["metakey"] = trim($result["0"]["metakey"]);
						$return_array["metadesc"] = trim($result["0"]["metadesc"]);
					}
				}
				break;
			}
			case "com_zoo" : {
				if(trim($task) != "" && trim($task) == "item"){
					$id = JRequest::getVar("item_id", 0);
					$sql = "select it.params from #__zoo_item it where it.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
										
					if(isset($result) && is_array($result) && count($result) > 0){
						$params = json_decode($result["0"]["params"], true);	
						$return_array["metatile"] = trim($params["metadata.title"]);
						$return_array["metakey"] = trim($params["metadata.keywords"]);
						$return_array["metadesc"] = trim($params["metadata.description"]);
					}
				}
				elseif(trim($task) != "" && trim($task) == "category"){
					$id = JRequest::getVar("item_id", 0);
					$sql = "select it.params from #__zoo_category it where it.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
										
					if(isset($result) && is_array($result) && count($result) > 0){
						$params = json_decode($result["0"]["params"], true);	
						$return_array["metatile"] = trim($params["metadata.title"]);
						$return_array["metakey"] = trim($params["metadata.keywords"]);
						$return_array["metadesc"] = trim($params["metadata.description"]);
					}
				}
				break;
			}
			case "com_k2" : {
				$layout = JRequest::getVar('layout');
				$id = JRequest::getInt("id");
				$seo_params = $this->getComponentParams();
				$view = JRequest::getVar("view");
				
				if ($layout == "item" || $layout == "category" || $view == "item" || $view == "category" || $view == "itemlist") {
					if ($layout == "item" || $view == "item") {
						$sql = "SELECT c.`params`, m.titletag FROM #__k2_categories c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-item' WHERE c.`id`=".intval($id);
						$db->setQuery($sql);
						$obj = $db->loadObject();
						
						$params = trim($obj->params);
						$params = json_decode($params, true);
						
						$return_array["metatile"] = trim($obj->titletag);
						$return_array["metakey"] = trim($params["catMetaKey"]);
						$return_array["metadesc"] = trim($params["catMetaDesc"]);
					}
					elseif ($layout == "category" || $view == "itemlist") {
						$sql = "SELECT c.`metadesc`, c.`metakey`, m.`titletag` FROM #__k2_items c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-cat' WHERE c.`id`=".intval($id);
						$db->setQuery($sql);
						$obj = $db->loadObject();
						
						$return_array["metatile"] = trim($obj->titletag);
						$return_array["metakey"] = trim($obj->metakey);
						$return_array["metadesc"] = trim($obj->metadesc);
					}
					
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
				break;
			}
			case "com_easyblog" : {
				$id = JRequest::getInt("id");
				$seo_params = $this->getComponentParams();
				$view = JRequest::getVar("view");
				
				if ($view == "entry" && $id > 0) {
					$sql = "SELECT c.`description`, c.`keywords`, m.`titletag` FROM #__easyblog_meta c LEFT JOIN #__ijseo_metags m on m.`id`=c.`content_id` and m.`mtype` = 'easyblog-item' WHERE c.`type` = 'post' and c.`content_id`=".intval($id);
				}
				elseif ($view == "categories" && $id > 0) {
					$sql = "SELECT c.`description`, c.`keywords`, m.`titletag` FROM #__easyblog_meta c LEFT JOIN #__ijseo_metags m on m.`id`=c.`content_id` and m.`mtype` = 'easyblog-cat' WHERE c.`type` = 'category' and c.`content_id`=".intval($id);
				}
				
				if(($view == "entry" || $view == "categories") && ($id > 0)){
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim($obj->titletag);
					$return_array["metakey"] = trim($obj->keywords);
					$return_array["metadesc"] = trim($obj->description);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
				
				break;
			}
			case "com_kunena" : {
				$view = JRequest::getVar('view');
				$id = JRequest::getInt("catid");
				$seo_params = $this->getComponentParams();
				if ($view == 'listcat' || $view == 'showcat' || $view == "category") {
					$sql = "SELECT * FROM #__ijseo_metags WHERE mtype = 'kunena-cat' AND `id`=".intval($id);
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim($obj->titletag);
					$return_array["metakey"] = trim($obj->metakey);
					$return_array["metadesc"] = trim($obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
				break;
			}

		}
		return $return_array;
	}
	
	function getMenusMeta($menutype){
		$option = JRequest::getVar('option', '', 'get', 'string');
		$view = JRequest::getVar('view', '', 'get', 'string');		
		$itemid = JRequest::getVar('Itemid', 0);
		$params = $this->getComponentParams();		
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";
		//get meta values
		$sql = "select `params` from #__menu where id=".intval($itemid);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		
		if(isset($result) && trim($result) != "" && trim($result) != "{}"){
			$result = json_decode(trim($result), true);
			$metakey = isset($result["menu-meta_keywords"]) ? trim($result["menu-meta_keywords"]) : "";
			$metadesc = isset($result["menu-meta_description"]) ? trim($result["menu-meta_description"]) : "";
			$metatile = isset($result["page_title"]) ? trim($result["page_title"]) : "";			
			if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){				
				$metakey = $metatile;			
				$delimiters = str_split(trim($params->delimiters));			
				$metakey = str_replace($delimiters, ",", $metakey);
			}
		}
		$return_array["metatile"] = trim($metatile);
		$return_array["metakey"] = trim($metakey);
		$return_array["metadesc"] = trim($metadesc);
		
		// Special "hack" for Kunena, if a menu item doesn't exist for e specific category
		if ($option == 'com_kunena') {
			$seo_params = $this->getComponentParams();
			$id = JRequest::getInt('catid');
			
			
			// If a specific Menu Link exists for a category, then leave this alone
			// else return the meta data for that category from SEO
			if (($id) && ($view == 'listcat' || $view == 'showcat')) {
				$sql = "SELECT COUNT(id) FROM `#__menu` WHERE 
						   `link` = 'index.php?option=com_kunena&view=showcat&catid={$id}'
						   OR `link` = 'index.php?option=com_kunena&view=listcat&catid={$id}' ";
				$db->setQuery($sql);
				$specific_menu_item_exists = $db->loadColumn();
				$specific_menu_item_exists = @$specific_menu_item_exists["0"];
				
				if ($specific_menu_item_exists === 0) {
					$sql = "SELECT * FROM #__ijseo_metags WHERE mtype = 'kunena-cat' AND id='{$id}' ";
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim($obj->titletag);
					$return_array["metakey"] = trim($obj->metakey);
					$return_array["metadesc"] = trim($obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}				
				}
			}
		}
		
		// Special "hack" for K2, if a menu item doesn't exist for e specific category / item
		if ($option == 'com_k2') {
			$seo_params = $this->getComponentParams();
			$id = JRequest::getInt('id');
			$view = JRequest::getVar('view');
			$layout = JRequest::getVar('layout');
			$itemid = JRequest::getInt('Itemid');
			
			// if it's an item
			if (($id) && ($view == 'item')) {
				// Check to see if it has a specific item id
				$sql = "SELECT COUNT(id) FROM #__menu 
						   WHERE `link` = 'index.php?option=com_k2&view=item&layout=item&id={$id}' ";
				$db->setQuery($sql);
				$itemid_exists = $db->loadColumn();
				$itemid_exists = @$itemid_exists["0"];
				
				if (!$itemid_exists) {
					// if there isn't any specific item id for the element
					// get the metadata from SEO
					$sql = "SELECT * FROM #__ijseo_metags 
							   WHERE `mtype` = 'k2-item' AND `id` = '{$id}' 
							   LIMIT 1";
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim($obj->titletag);
					$return_array["metakey"] = trim($obj->metakey);
					$return_array["metadesc"] = trim($obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
			// if it's a category
			} elseif (($id) && ($view == 'itemlist')) {
				// Check to see if it has a specific item id
				$sql = "SELECT COUNT(id) FROM #__menu 
						   WHERE `link` = 'index.php?option=com_k2&view=itemlist&layout=category&task=category&id={$id}' ";
				$db->setQuery($sql);
				$itemid_exists = $db->loadColumn();
				$itemid_exists = @$itemid_exists["0"];
				
				if (!$itemid_exists) {
					// if there isn't any specific item id for the element
					// get the metadata from SEO
					$sql = "SELECT * FROM #__ijseo_metags 
							   WHERE `mtype` = 'k2-cat' AND `id` = '{$id}' 
							   LIMIT 1";
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim($obj->titletag);
					$return_array["metakey"] = trim($obj->metakey);
					$return_array["metadesc"] = trim($obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}				
			}
		}
				
		return $return_array;
	}
	
	function getArticlesMeta(){
		$params = $this->getComponentParams();
		$id = JRequest::getVar("id", "");
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";
		//get meta values
		$sql = "select c.`attribs`, c.`metakey`, c.`metadesc`, m.`titletag` from #__content c left join #__ijseo_metags m on m.id=c.id and m.`mtype`='article' where c.`id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		if(isset($result) && is_array($result) && count($result) > 0){
			$metatile = trim($result["0"]["titletag"]);
			$metakey = trim($result["0"]["metakey"]);
			$metadesc = trim($result["0"]["metadesc"]);
			$attribs = json_decode($result["0"]["attribs"]);
		}
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			$metakey = $metatile;			
			$delimiters = str_split(trim($params->delimiters));			
			$metakey = str_replace($delimiters, ",", $metakey);
		}
		
		$return_array["metatile"] = trim($metatile);
		$return_array["metakey"] = trim($metakey);
		$return_array["metadesc"] = trim($metadesc);		
		return $return_array;
	}
	
	function MenuParams($menu_id){
		$db = JFactory::getDBO();
		$sql = "select `params` from #__menu where `id`=".intval($menu_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result, true);
		return $params;
	}
	
	function getComponentParams(){
		$db = JFactory::getDBO();
		$sql = "select `params` from #__ijseo_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result);
		return $params;
	}
	
	function onContentPrepare(){
		$db = JFactory::getDBO();		
		$params = $this->getComponentParams();
		
		$check_grank=intval($params->ijseo_check_grank);

		if($params->ijseo_gposition){			
			$jnow	=  JFactory::getDate();
			$date	=  $jnow->toSQL();
			$current_date_for_ping = $jnow->toUnix();
			
			$sql = "select max(`checkdate`) from #__ijseo_keys limit 0,1";
			$db->setQuery($sql);
			$db->query();
			$last_ping_date = $db->loadColumn();
			$last_ping_date = @$last_ping_date["0"];
			
			if($current_date_for_ping <= (strtotime($last_ping_date)+30)){
				// no ping, time less then 30 seconds from last ping
			}
			else{
				$Q = " select * from #__ijseo_keys as a where ". 
						 $check_grank."<=(SELECT DATEDIFF('".$date."',a.checkdate))".
						 " OR a.checkdate='0000-00-00 00:00:00' order by checkdate limit 0,20 ";
				$db->setQuery($Q);
				if(!$db->query()){
					echo $db->getErrorMsg();
				}	
				$keys = $db->loadAssocList();
				
				foreach ($keys as $key){
					$this->getKeyRank(trim($key['title']), $key['rank'], $date, $params->ijseo_keysource);
				}
			}
		}
	}
	
	function getKeyRank($key, $oldrank, $date, $ijseo_keysource){ 
		$database =& JFactory::getDBO();
		$params = $this->getComponentParams();
		
		if(!isset($params->ijseo_check_ext) || $params->ijseo_check_ext == ""){
			$params->ijseo_check_ext="com";
		}	
		if(!isset($params->check_nr)){
			$params->check_nr = "10";
		}	
		// exact word or phrase
		$request = "http://www.google.".$params->ijseo_check_ext."/search?q=".urlencode($key)."&num=".$params->check_nr."&start=0";
		$data = $this->getPageData($request);
		$sitehost = $_SERVER['HTTP_HOST'];
		$sitehost1 = $_SERVER['HTTP_HOST'];
		if (strpos($sitehost, 'www')  === false){
			$sitehost = "www.$sitehost";
		}
		else{
			$sitehost1=substr($sitehost,4);
		}	
		$position = 0;
		
		preg_match_all("/(<cite>)(.*)(<\/cite>)/Ui", $data, $result);
		$val=array();
		for($i=0; $i<count($result["0"]); $i++){			
			array_push($val, $result["0"][$i]);
		}
	
		for($i=0; $i<count($val); $i++){
			$val[$i] = strip_tags($val[$i]);
			$pos = strpos($val[$i],"/");
			if($pos !== false){
				$val[$i]=substr($val[$i],0,$pos);
			}	
		}				
		
		$newVal=array();
		$newVal["0"] = @$val["0"];
		
		for($i=0;$i<count($val);$i++){
			$ok=true;
			for($j=0;$j<count($newVal);$j++)
				if($val[$i]==$newVal[$j]){
					$ok=false;
					break;
				}
			if($ok==true)
				array_push($newVal,$val[$i]);
		}
		$val=$newVal;
		for($index=0; $index<count($val); $index++){
			$find = strip_tags($val[$index]);
			$tag=strpos($find,$sitehost);
			$tag1=strpos($find,$sitehost1);
			/// keyword position
			$position=$index+1;
			if($tag!==false){
				// if the site is found on google for this keyword, update the new key rank  			
				$this->updateRank($oldrank, $position, $date, $key, $ijseo_keysource);
				return $position;
			}
			//fix the problem if the site name doesn't have www.on google
			else if($tag1!==false){
				// if the site is found on google for this keyword, update the new key rank  			
				$this->updateRank($oldrank, $position, $date, $key, $ijseo_keysource);
				return $position;
			}
		}
		$this->updateRank($oldrank, 0, $date, $key, $ijseo_keysource);
		return  0;
	}
	
	function getPageData($url) {
		if(function_exists('curl_init')) {
			$ch = curl_init($url); // initialize curl with given url
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // add useragent
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
			if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // max. seconds to execute
			curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
			return @curl_exec($ch);
		}
		else {
			return @file_get_contents($url);
		}
	}  
  
	function updateRank($oldrank, $newrank, $currentDate, $key, $ijseo_keysource){
		$key = trim($key);
		$db =& JFactory::getDBO();
		$change = 0;
		$mode = -1;
		if($newrank > 0){
			$change = abs($newrank - $oldrank);
		}	
		if($newrank > $oldrank && $oldrank > 0){
			$mode = 0;
		}	
		elseif(($oldrank >0  && $newrank < $oldrank) || ($oldrank == 0 && $newrank >0)){
			$mode = 1;
		}
		
		$sql = "update #__ijseo_keys set rank = ".$newrank." , rchange =  ".$change.", mode = ".$mode." , checkdate = '".$currentDate."' where title = '".mysql_escape_string($key)."' ";
		$db->setQuery($sql);
		if(!$db->query()){
			return $db->getErrorMsg();
		}
	}
	
}

?>