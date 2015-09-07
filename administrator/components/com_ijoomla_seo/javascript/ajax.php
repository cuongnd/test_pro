<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "administra")),0,-1));

//if (!isset($_SERVER["HTTP_REFERER"])) exit("Direct access not allowed.");
$mosConfig_absolute_path =substr(JPATH_BASE, 0, strpos(JPATH_BASE, "/administra")); 

define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
include_once( JPATH_BASE .DS. "configuration.php" );
include_once( JPATH_BASE .DS. "libraries" .DS. "joomla" .DS. "object" .DS. "object.php" );
include_once( JPATH_BASE .DS. "libraries" .DS. "joomla" .DS. "database" .DS. "database.php" );

$config = new JConfig();
$options = array ("host" => $config->host,"user" => $config->user,"password" => $config->password,"database" => $config->db,"prefix" => $config->dbprefix);
$database = JFactory::getDBO();

$tasks = JRequest::getVar("tasks", "");

switch($tasks){
	case "change_sticky" : {		
		changeSticky();
		break;
	}
	case "stats" :{
		calculateStats();
		break;
	}
	case "get_Grank" :{
		getKeyRank();
		break;
	}
	case "change" :{
		change();
		break;
	}
	case "changeMenuItems" :{
		changeMenuItems();
		break;
	}
}

function changeMenuItems(){
	global $database;
	$menu_type = JRequest::getVar("menu_type", "");
	$sql = "select id, title from #__menu where menutype='".$menu_type."'";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadAssocList();
	$return = "";
	$return .= '<select name="loc_id">';
	if(isset($result) && count($result) > 0){
		foreach($result as $key=>$value){
			$return .= '<option value="'.$value["id"].'">'.$value["title"].'</option>';
		}
	}
	$return .= '</select>';
	echo $return;
}

function changeSticky(){
	global $database;
	
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$param = $database->loadColumn();
	$param = @$param["0"];
	$params = json_decode($param);

	$sid = intval(JRequest::getVar("sid"));
	$onoff = intval(JRequest::getVar("onoff"));
	if($params->ijseo_keysource == "0"){
		$sql = " update #__ijseo_keys set `sticky` = ".$onoff." where id = ".$sid;
		$database->setQuery($sql);		
		if(!$database->query()){
			 echo $database->getErrorMsg();
		}
	}
	else{
		$sql = " update #__ijseo_titlekeys set `sticky` = ".$onoff." where id = ".$sid;
		$database->setQuery($sql);		
		if(!$database->query()){
			 echo $database->getErrorMsg();
		}
	}		
}

function change(){
	$params = getComponentParams();
	$key = JRequest::getVar("key");
	$key = str_replace("*and*", "&", trim($key));
	$val = intval(JRequest::getVar("val"));
	$mode = intval(JRequest::getVar("mode"));
	global $database;
	
	if($val == 0){
		$mode = -1;
	}
	if($params->ijseo_keysource == "1"){
		$sql = "update #__ijseo_titlekeys set rchange = ".$val.", mode = ".$mode." where title = '".addslashes(trim($key))."'";	
		$database->setQuery($sql);
		if(!$database->query()){
			return $database->getErrorMsg();
		}
	}
	else{
		$sql = "update #__ijseo_keys set rchange = ".$val.", mode = ".$mode." where title = '".addslashes(trim($key))."'";	
		$database->setQuery($sql);
		if(!$database->query()){
			return $database->getErrorMsg();
		}
	}	
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
	else{ 
		return @file_get_contents($url);
	}
}

function updateRank($oldrank, $newrank, $currentDate, $key){				
	$change = 0;
	$mode = -1;
	global $database;
	$params = getComponentParams();
	
	if($newrank > 0){
		$change = abs($newrank - $oldrank);
	}	
	if($newrank > $oldrank && $oldrank > 0){
		$mode = 0;
	}	
	elseif(($oldrank >0  && $newrank < $oldrank) || ($oldrank == 0 && $newrank >0)){
		$mode = 1;
	}	
	
	if($params->ijseo_keysource == "1"){
		$sql = "update #__ijseo_titlekeys set rank = ".$newrank." , rchange =  ".$change.", mode = ".$mode." , checkdate = '".$currentDate."' where title = '".addslashes(trim($key))."'";
	}
	else{
		$sql = "update #__ijseo_keys set rank = ".$newrank." , rchange =  ".$change.", mode = ".$mode." , checkdate = '".$currentDate."' where title = '".addslashes(trim($key))."'";
	}	
	$database->setQuery($sql);
		
	if(!$database->query()){
		return $database->getErrorMsg();
	}	
}

function getComponentParams(){
	global $database;
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$param = $database->loadColumn();
	$param = @$param["0"];
	$params = json_decode($param);
	return $params;
}

function getKeyRank(){
	$key = JRequest::getVar("key", "");	
	$key = str_replace("*and*", "&", $key);
	$oldrank = JRequest::getVar("oldrank");
	
	global $database;
		
	$params = getComponentParams();

	// exact word or phrase
	if(!isset($params->ijseo_check_ext)){
		$params->ijseo_check_ext = "com";
	}	
	if(!isset($params->check_nr)){
		$params->check_nr = "10";
	}
	$request = "http://www.google.".$params->ijseo_check_ext."/search?q=".urlencode($key)."&num=".$params->check_nr."&start=0";
  	$data = getPageData($request);
	
  	$sitehost = $_SERVER['HTTP_HOST'];
	$sitehost1 = $_SERVER['HTTP_HOST'];
  	if (strpos($sitehost, 'www')  === false) {
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
		$val[$i]=strip_tags($val[$i]);
		$pos=strpos($val[$i],"/");
		if($pos!==false) $val[$i]=substr($val[$i],0,$pos);
	}
	
	$newVal=array();
	if(isset($val["0"])){
		$newVal["0"] = $val["0"];
	}
	
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
	$currentDate = "";
	for($index=0;$index<count($val);$index++){
		$find = strip_tags($val[$index]);
		$tag=strpos($find,$sitehost);
		$tag1=strpos($find,$sitehost1);
		/// keyword position
  		$position=$index+1;
		$currentDate = date("Y-m-d G:i:s");	
		if($tag!==false){
  			// if the site is found on google for this keyword, update the new key rank  			
			if ($oldrank != $position){
				updateRank($oldrank, $position, $currentDate, $key);
			}	
			echo $position;
			return; 						
		}
		else if($tag1!==false){
  			// if the site is found on google for this keyword, update the new key rank  		
			updateRank($oldrank, $position, $currentDate, $key);				
  			echo $position;
			return;
  		}			
	}
	updateRank($oldrank, 0, $currentDate, $key);		
	echo 0;
}

function calculateStats(){
	$selmenu = JRequest::getVar("selmenu");
	$selsubmenu = JRequest::getVar("selsubmenu");
	
	switch($selmenu){
		case "article" : {
			calculateArticleStats();
			break;
		}
		case "menuitems" : {
			calculateMenuItemsStats($selsubmenu);
			break;
		}
		case "mtree" : {
			calculateMtreeStats($selsubmenu);
			break;
		}
		case "zoo" : {
			calculateZooStats($selsubmenu);
			break;
		}
		case "ktwo" : {
			calculateKtwoStats($selsubmenu);
			break;
		}			
		case "kunena" : {
			calculateKunenaStats($selsubmenu);
			break;
		}
		case "easyblog" : {
			calculateEasyblogStats($selsubmenu);
			break;
		}
	}
}

function calculateZooStats($selsubmenu){	
	global $database;
	
	$sql = "select count(*) from #__extensions where `name`='com_zoo' and `element`='com_zoo' and `type`='component'";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	if(intval($result) == 0){
		echo '-';
		return true;
	}
	
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	
	$params = json_decode($result);
	
	$stats = JRequest::getVar("stats", "0");		
	$sql = "";
	$result = "";
	
	$submenu = "0";
	if($selsubmenu == "zoo_items"){
		$submenu = "1";
	}
	elseif($selsubmenu == "zoo_cats"){
		$submenu = "2";
	}	
	
	if($selsubmenu != "" && $selsubmenu == "zoo_items"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}	
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&filter=sticky&value=1&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__zoo_item` WHERE 1=1 and params like '%\"metadata.title\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=zoo&filter=atype&value=1&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__zoo_item` WHERE 1=1 and params like '%\"metadata.keywords\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=zoo&filter=atype&value=2&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM `#__zoo_item` WHERE 1=1 and params like '%\"metadata.description\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=zoo&filter=atype&value=3&types=zoo&zoo='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	elseif($selsubmenu != "" && $selsubmenu == "zoo_cats"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyszoo&filter=sticky&value=1&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__zoo_category` WHERE 1=1 and params like '%\"metadata.title\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=zoo&filter=atype&value=1&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__zoo_category` WHERE 1=1 and params like '%\"metadata.keywords\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=zoo&filter=atype&value=2&types=zoo&zoo='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM `#__zoo_category` WHERE 1=1 and params like '%\"metadata.description\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=zoo&filter=atype&value=3&types=zoo&zoo='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	else{
		echo "-";	
	}
}

function calculateKtwoStats($selsubmenu){	
	global $database;
	
	$sql = "select count(*) from #__extensions where `name`='com_k2' and `element`='com_k2' and `type`='component'";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	if(intval($result) == 0){
		echo '-';
		return true;
	}
	
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	$params = json_decode($result);
	
	$stats = JRequest::getVar("stats", "0");		
	$sql = "";
	$result = "";
	
	$submenu = "0";
	if($selsubmenu == "k2-item"){
		$submenu = "1";
	}
	elseif($selsubmenu == "k2-cat"){
		$submenu = "2";
	}	
	
	if($selsubmenu != "" && $selsubmenu == "k2-item"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k 
							join #__ijseo_keys_id ki 
							on ki.keyword = k.title 
							where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.type = '".$selsubmenu."' 
							and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki 
							on ki.keyword = k.title 
							where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki 
							where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.`type`='".$selsubmenu."' and k.sticky=1";
			}	
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&filter=sticky&value=1&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=ktwo&filter=atype&value=1&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__k2_items` AS mt WHERE 1=1 and mt.metakey = ''";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=ktwo&filter=atype&value=2&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM `#__k2_items` AS mt WHERE 1=1 and mt.metadesc = ''";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=ktwo&filter=atype&value=3&types=ktwo&ktwo='.$submenu.'">';
		}

		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	elseif($selsubmenu != "" && $selsubmenu == "k2-cat"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&filter=sticky&value=1&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=ktwo&filter=atype&value=1&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__k2_categories` AS mt WHERE 1=1 and mt.`params` like '%\"catMetaKey\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=ktwo&filter=atype&value=2&types=ktwo&ktwo='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM `#__k2_categories` AS mt WHERE 1=1 and mt.`params` like '%\"catMetaDesc\":\"\"%'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=ktwo&filter=atype&value=3&types=ktwo&ktwo='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	else{
		echo "-";	
	}
}
function calculateEasyblogStats($selsubmenu){	
	global $database;
	
	$sql = "select count(*) from #__extensions where `name`='com_easyblog' and `element`='com_easyblog' and `type`='component'";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	if(intval($result) == 0){
		echo '-';
		return true;
	}
	
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	$params = json_decode($result);
	
	$stats = JRequest::getVar("stats", "0");
	$sql = "";
	$result = "";
	
	$submenu = "0";
	if($selsubmenu == "easyblog-item"){
		$submenu = "1";
	}
	elseif($selsubmenu == "easyblog-cat"){
		$submenu = "2";
	}	
	
	if($selsubmenu != "" && $selsubmenu == "easyblog-item"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k 
							join #__ijseo_keys_id ki 
							on ki.keyword = k.title 
							where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.type = '".$selsubmenu."' 
							and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki 
							on ki.keyword = k.title 
							where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki 
							where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.`type`='".$selsubmenu."' and k.sticky=1";
			}	
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&filter=sticky&value=1&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=easyblog&filter=atype&value=1&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__easyblog_meta` AS mt WHERE 1=1 and mt.keywords = '' and mt.`type`='post' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=easyblog&filter=atype&value=2&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "7"){
			$sql = "SELECT count(*) FROM `#__easyblog_meta` AS mt WHERE 1=1 and mt.description = '' and mt.`type`='post' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=easyblog&filter=atype&value=3&types=easyblog&easyblog='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	elseif($selsubmenu != "" && $selsubmenu == "easyblog-cat"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&keyup_doun=true">';
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyseasyblog&filter=sticky&value=1&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=easyblog&filter=atype&value=1&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__easyblog_meta` AS mt WHERE 1=1 and mt.keywords = '' and mt.`type`='category' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=easyblog&filter=atype&value=2&types=easyblog&easyblog='.$submenu.'">';
		}
		elseif($stats == "7"){
			$sql = "SELECT count(*) FROM `#__easyblog_meta` AS mt WHERE 1=1 and mt.description = '' and mt.`type`='category' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=easyblog&filter=atype&value=3&types=easyblog&easyblog='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	else{
		echo "-";	
	}
}

function calculateKunenaStats($selsubmenu){	
	global $database;
	
	$sql = "select count(*) from #__extensions where `name`='com_kunena' and `element`='com_kunena' and `type`='component'";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	if(intval($result) == 0){
		echo '-';
		return true;
	}
	
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	$params = json_decode($result);
	
	$stats = JRequest::getVar("stats", "0");		
	$sql = "";
	$result = "";
	
	$submenu = "0";
	if($selsubmenu == "kunena-item"){
		$submenu = "1";
	}
	elseif($selsubmenu == "kunena-cat"){
		$submenu = "2";
	}	
	
	if($selsubmenu != "" && $selsubmenu == "kunena-item"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k 
							join #__ijseo_keys_id ki 
							on ki.keyword = k.title 
							where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.type = '".$selsubmenu."' 
							and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki 
							on ki.keyword = k.title 
							where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki 
							where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k 
							where k.`type`='".$selsubmenu."' and k.sticky=1";
			}	
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&filter=sticky&value=1&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=kunena&filter=atype&value=1&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.metakey = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=kunena&filter=atype&value=2&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.metadesc = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=kunena&filter=atype&value=3&types=kunena&kunena='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	elseif($selsubmenu != "" && $selsubmenu == "kunena-cat"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysktwo&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keyskunena&filter=sticky&value=1&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM `#__ijseo_metags` AS mt WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=kunena&filter=atype&value=1&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM #__ijseo_metags mt WHERE 1=1 and mt.metakey = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=kunena&filter=atype&value=2&types=kunena&kunena='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM #__ijseo_metags mt WHERE mt.metadesc = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=kunena&filter=atype&value=3&types=kunena&kunena='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	else{
		echo "-";	
	}
}

function calculateMtreeStats($selsubmenu){	
	global $database;
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	$params = json_decode($result);
	
	$stats = JRequest::getVar("stats", "0");		
	$sql = "";
	$result = "";
	
	$submenu = "0";
	if($selsubmenu == "mt_list"){
		$submenu = "1";
	}
	elseif($selsubmenu == "mt_cat"){
		$submenu = "2";
	}	
	
	if($selsubmenu != "" && $selsubmenu == "mt_list"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}	
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&filter=sticky&value=1&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM #__mt_links l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.link_id WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=1&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM #__mt_links l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.link_id WHERE 1=1 and l.metakey = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=2&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM #__mt_links l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.link_id WHERE 1=1 and l.metadesc = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=3&types=mtree&mtree='.$submenu.'">';
		}
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo "-";
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	elseif($selsubmenu != "" && $selsubmenu == "mt_cat"){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
			}			
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&filter=sticky&value=1&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "5"){
			$sql = "SELECT count(*) FROM #__mt_cats l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.cat_id WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=1&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "SELECT count(*) FROM #__mt_cats l WHERE 1=1 and l.metakey = '' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=2&types=mtree&mtree='.$submenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "SELECT count(*) FROM #__mt_cats l WHERE l.metadesc = '' ";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=3&types=mtree&mtree='.$submenu.'">';
		}
		
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo "-";
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	else{
		echo "-";	
	}
}

function calculateArticleStats(){
	global $database;
	
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	$params = json_decode($result);	
	$stats = JRequest::getVar("stats", "0");		
	$sql = "";
	$result = "";	
	
	if($stats == "1"){
		if($params->ijseo_keysource == "0"){
			$sql = "SELECT COUNT(*) FROM 
					(
						SELECT *
						FROM #__ijseo_keys k
						JOIN #__ijseo_keys_id ki ON ki.keyword = k.title
						WHERE ki.type = 'article'
						AND k.mode =1
						AND k.rchange <>0
						GROUP BY title
					) AS d";

		}
		else{
			$sql = "select count(*) from #__ijseo_titlekeys k where k.type = 'article' and k.mode = 1 and k.rchange <> 0";
		}
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysarticles&keyup_doun=true">';			
	}
	elseif($stats == "2"){
		if($params->ijseo_keysource == "0"){
			$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = 'article' and k.mode = 0 and k.rchange <> 0";
		}
		else{
			$sql = "select count(*) from #__ijseo_titlekeys k where k.type = 'article' and k.mode = 0 and k.rchange <> 0";
		}
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysarticles&keyup_doun=down">';				
	}
	elseif($stats == "3"){
		if($params->ijseo_keysource == "0"){
			$sql = "
				SELECT COUNT(*) FROM 
				(
					SELECT DISTINCT *
					FROM #__ijseo_keys k
					LEFT JOIN #__ijseo_keys_id ki on ki.keyword = k.title
					WHERE 
						ki.type = 'article' AND k.mode = '-1' AND k.rchange = 0 AND k.rank <> 0
						AND ki.type_id IN 
						(
							SELECT id FROM #__content 
							WHERE state=1 AND (publish_down > '2011-10-28 12:04:16' OR publish_down='0000-00-00 00:00:00') 
						)
					GROUP BY k.title
					ORDER BY CASE k.rank WHEN 0 THEN 9999 ELSE k.rank END
				) AS d";
		}
		else{
			$sql = "select count(*) from #__ijseo_titlekeys k where k.type = 'article' and k.mode = -1 and k.rchange = 0 AND k.rank <> 0";
        }
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysarticles&keyup_doun=nochange">';
	}
	elseif($stats == "4"){
		if($params->ijseo_keysource == "0"){	
			$sql = "SELECT COUNT(DISTINCT(k.title)) FROM #__ijseo_keys k, #__ijseo_keys_id ki 
					WHERE ki.`type`='article' and k.sticky=1 and k.title=ki.keyword";
		}
		else{
			$sql = "select count(*) from #__ijseo_titlekeys where sticky=1 and type='article'";
		}
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysarticles&filter=sticky&value=1">';
	}
	elseif($stats == "5"){	
		$sql = "SELECT COUNT(*)
				FROM (
				  SELECT c.id, c.title, c.metakey, c.metadesc, c.attribs, mt.titletag
				  FROM #__content c
				  LEFT JOIN `#__ijseo_metags` AS mt ON c.id = mt.id
				  AND mt.mtype = 'article'
				  WHERE mt.titletag = ''
				  AND c.state IN (0, 1)
				) AS w ";
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=articles&filter=atype&value=1">';
	}
	elseif($stats == "6"){		
		$sql = "select count(*) from #__content where metakey like '' AND state IN (0, 1)";
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=articles&filter=atype&value=2">';
	}
	elseif($stats == "7"){		
		$sql = "select count(*) from #__content where metadesc like '' AND state IN (0, 1)";
		$result .= '<a href="index.php?option=com_ijoomla_seo&controller=articles&filter=atype&value=3">';
	}
	
	if($sql != ""){
		$database->setQuery($sql);		
		if(!$database->query()){
			 echo $database->getErrorMsg();
		}
		$temp = $database->loadColumn();
		$temp = @$temp["0"];
		echo $result.$temp.'</a>';
	}
}

function calculateMenuItemsStats($selsubmenu){	
	global $database;
	$sql = "select params from #__ijseo_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadColumn();
	$result = @$result["0"];
	
	$params = json_decode($result);
	
	$stats = JRequest::getVar("stats", "0");		
	$sql = "";
	$result = "";	
	
	if($selsubmenu != ""){
		if($stats == "1"){
			if($params->ijseo_keysource == "0"){
                $sql = "SELECT COUNT(*) FROM (
                                SELECT *
                                FROM #__ijseo_keys k
                                JOIN #__ijseo_keys_id ki ON ki.keyword = k.title
                                WHERE ki.type = '{$selsubmenu}'
                                AND k.mode =1
                                AND k.rchange <>0
                                GROUP BY k.title               
                            ) AS c";
			}
			else{
				$sql = "SELECT COUNT(*) FROM (
                                SELECT * FROM #__ijseo_titlekeys k 
                                WHERE k.type = '".$selsubmenu."' 
                                AND k.mode = 1 AND k.rchange <> 0 GROUP BY k.title
                            ) AS c";
			}						
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmenus&keyup_doun=true&menu_types='.$selsubmenu.'">';			
		}
		elseif($stats == "2"){
			if($params->ijseo_keysource == "0"){
				$sql = "
                    SELECT COUNT(DISTINCT(title)) FROM #__ijseo_keys k 
                    JOIN #__ijseo_keys_id ki ON ki.keyword = k.title 
                    WHERE ki.type = '".$selsubmenu."' AND k.mode = 0 AND k.rchange <> 0
                ";
			}
			else{
				$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmenus&keyup_doun=down&menu_types='.$selsubmenu.'">';				
		}
		elseif($stats == "3"){		
			if($params->ijseo_keysource == "0") {
                $sql = "SELECT COUNT(DISTINCT(title))
                            FROM #__ijseo_keys k
                            LEFT JOIN #__ijseo_keys_id ki ON ki.keyword = k.title
                            WHERE ki.type = '{$selsubmenu}'
                            AND k.mode = -1
                            AND k.rchange =0";
			} else {
                $sql = "SELECT COUNT( DISTINCT (title) )
                            FROM #__ijseo_keys k
                            LEFT JOIN #__ijseo_keys_id ki ON ki.keyword = k.title
                            WHERE ki.type = '{$selsubmenu}'
                            AND k.mode = -1
                            AND k.rchange =0";                
			}
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmenus&menu_types='.$selsubmenu.'&keyup_doun=nochange">';
		}
		elseif($stats == "4"){		
			if($params->ijseo_keysource == "0"){
				$sql = "select COUNT( DISTINCT (title) ) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword GROUP BY k.title";
			}
			else{
				$sql = "select COUNT( DISTINCT (title) ) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1 GROUP BY k.title";
			}	
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmenus&filter=sticky&value=1&menu_types='.$selsubmenu.'">';
		}
		elseif($stats == "5"){		
			$sql = "select count(*) from #__menu where (params like '%\"page_title\":\"\"%' or params not like '%page_title%') and `menutype`='".$selsubmenu."'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=menus&filter=atype&value=1&menu_types='.$selsubmenu.'">';
		}
		elseif($stats == "6"){		
			$sql = "select count(*) from #__menu where (params like '%\"menu-meta_keywords\":\"\"%' or params not like '%menu-meta_keywords%') and `menutype`='".$selsubmenu."'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=menus&filter=atype&value=2&menu_types='.$selsubmenu.'">';
		}
		elseif($stats == "7"){		
			$sql = "select count(*) from #__menu where (params like '%\"menu-meta_description\":\"\"%' or params not like '%menu-meta_description%') and `menutype`='".$selsubmenu."'";
			$result .= '<a href="index.php?option=com_ijoomla_seo&controller=menus&filter=atype&value=3&menu_types='.$selsubmenu.'">';
		}
        
		if($sql != ""){
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
			$temp = $database->loadColumn();
			$temp = @$temp["0"];
			echo $result.$temp.'</a>';
		}
	}
	else{
		echo "-";	
	}
}

?>