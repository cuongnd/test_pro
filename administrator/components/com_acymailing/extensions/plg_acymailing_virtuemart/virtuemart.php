<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.2.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class plgAcymailingVirtuemart extends JPlugin
{
	var $version = false;
	var $lang = '';

	function plgAcymailingVirtuemart(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('acymailing', 'virtuemart');
			$this->params = new JParameter( $plugin->params );
		}

		$file = ACYMAILING_ROOT.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'version.php';
		if(!file_exists($file)) return false;
		include_once($file);
		$vmversion = new vmVersion();
		if(empty($vmversion->RELEASE)){
			$this->version = vmVersion::$RELEASE;
			$params = JComponentHelper::getParams('com_languages');
			$this->lang = strtolower(str_replace('-','_',$params->get('site', 'en-GB')));
		}else{
			$this->version = $vmversion->RELEASE;
		}

		jimport('joomla.user.helper');

	}

	 function acymailing_getPluginType() {

	 	if(!$this->version) return;

	 	$onePlugin = new stdClass();
	 	$onePlugin->name = JText::_('Virtuemart');
	 	$onePlugin->function = 'acymailingtagvirtuemart_show';
	 	$onePlugin->help = 'plugin-virtuemart';

	 	return $onePlugin;
	 }

	 function acymailingtagvirtuemart_show(){
		$app = JFactory::getApplication();

		$contentType = array();
		$contentType[] = JHTML::_('select.option', "title",JText::_('TITLE_ONLY'));
		$contentType[] = JHTML::_('select.option', "intro",JText::_('INTRO_ONLY'));
		$contentType[] = JHTML::_('select.option', "full",JText::_('FULL_TEXT'));

		$pageInfo = new stdClass();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->elements = new stdClass();

		$paramBase = ACYMAILING_COMPONENT.'.tagvmproduct';
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',(version_compare($this->version,'2.0.0','<' ) ? 'a.product_id' : 'a.virtuemart_product_id'),'cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'desc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$pageInfo->lang = $app->getUserStateFromRequest( $paramBase.".lang", 'lang','','string' );
		$pageInfo->contenttype = $app->getUserStateFromRequest( $paramBase.".contenttype", 'contenttype','full','string' );

		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );

		$db = JFactory::getDBO();

		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search).'%\'';
			if(version_compare($this->version,'2.0.0','<' )){
				$filters[] = "a.product_id LIKE $searchVal OR a.product_s_desc LIKE $searchVal OR a.product_name LIKE $searchVal OR a.product_sku LIKE $searchVal";
			}else{
				$filters[] = "a.virtuemart_product_id LIKE $searchVal OR b.product_s_desc LIKE $searchVal OR b.product_name LIKE $searchVal OR a.product_sku LIKE $searchVal";
			}
		}

		$whereQuery = '';
		if(!empty($filters)){
			$whereQuery = ' WHERE ('.implode(') AND (',$filters).')';
		}

		if(version_compare($this->version,'2.0.0','<' )){
			$query = 'SELECT SQL_CALC_FOUND_ROWS a.product_id,a.product_s_desc,a.product_sku,a.product_name FROM #__vm_product as a';
		}else{
			$query = 'SELECT SQL_CALC_FOUND_ROWS a.virtuemart_product_id as \'product_id\' ,b.product_s_desc,a.product_sku,b.product_name FROM #__virtuemart_products as a';
			$query .= ' LEFT JOIN #__virtuemart_products_'.$this->lang.' as b ON a.virtuemart_product_id = b.virtuemart_product_id';
		}

		if(!empty($whereQuery)) $query.= $whereQuery;
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}

		$db->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $db->loadObjectList();

		if(!empty($pageInfo->search)){
			$rows = acymailing_search($pageInfo->search,$rows);
		}

		$db->setQuery('SELECT FOUND_ROWS()');
		$pageInfo->elements->total = $db->loadResult();
		$pageInfo->elements->page = count($rows);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		$tabs = acymailing_get('helper.acytabs');
		echo $tabs->startPane( 'vmproduct_tab');
		echo $tabs->startPanel( JText::_( 'ACY_PRODUCTS' ), 'vm_product');

	?>
		<br style="font-size:1px"/>
			<script language="javascript" type="text/javascript">
		<!--
			var selectedContents = new Array();
			function applyContent(contentid,rowClass){
				if(selectedContents[contentid]){
					window.document.getElementById('content'+contentid).className = rowClass;
					delete selectedContents[contentid];
				}else{
					window.document.getElementById('content'+contentid).className = 'selectedrow';
					selectedContents[contentid] = 'content';
				}

				updateTag();
			}

			function updateTag(){
				var tag = '';
				var otherinfo = '';
				for(var i=0; i < document.adminForm.contenttype.length; i++){
					 if (document.adminForm.contenttype[i].checked){ selectedtype = document.adminForm.contenttype[i].value; otherinfo += '|type:'+document.adminForm.contenttype[i].value; }
				}

				if(window.document.getElementById('jflang')  && window.document.getElementById('jflang').value != ''){
					otherinfo += '|lang:';
					otherinfo += window.document.getElementById('jflang').value;
				}

				for(var i in selectedContents){
					if(selectedContents[i] == 'content'){
						tag = tag + '{vmproduct:'+i+otherinfo+'}<br/>';
					}
				}
				setTag(tag);
			}
		//-->
		</script>
		<table width="100%" class="adminform">
			<tr>
				<td>
					<?php echo JText::_('DISPLAY');?>
				</td>
				<td colspan="2">
				<?php echo JHTML::_('acyselect.radiolist', $contentType, 'contenttype' , 'size="1" onclick="updateTag()"', 'value', 'text', $pageInfo->contenttype); ?>
				</td>
				<td>
					<?php $jflanguages = acymailing_get('type.jflanguages');
						$jflanguages->onclick = 'onclick="updateTag()"';
						echo $jflanguages->display('lang',$pageInfo->lang); ?>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td width="100%">
					<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
					<input type="text" name="search" id="acymailingsearch" value="<?php echo $pageInfo->search;?>" class="text_area" onchange="document.adminForm.submit();" />
					<button class="btn" onclick="this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
					<button class="btn" onclick="document.getElementById('acymailingsearch').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
				</td>
			</tr>
		</table>

		<table class="adminlist table table-striped table-hover" cellpadding="1" width="100%">
			<thead>
				<tr>
					<th class="title"></th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', JText::_( 'FIELD_TITLE'), (version_compare($this->version,'2.0.0','<' ) ? 'a.product_name' : 'b.product_name'), $pageInfo->filter->order->dir,$pageInfo->filter->order->value ); ?>
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', JText::_( 'ACY_DESCRIPTION'), (version_compare($this->version,'2.0.0','<' ) ? 'a.product_s_desc' : 'b.product_s_desc'), $pageInfo->filter->order->dir,$pageInfo->filter->order->value ); ?>
					</th>
					<th class="title titleid">
						<?php echo JHTML::_('grid.sort',   JText::_( 'ACY_ID' ), (version_compare($this->version,'2.0.0','<' ) ? 'a.product_id' : 'a.virtuemart_product_id'), $pageInfo->filter->order->dir, $pageInfo->filter->order->value ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4">
						<?php echo $pagination->getListFooter(); ?>
						<?php echo $pagination->getResultsCounter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
					$k = 0;
					for($i = 0,$a = count($rows);$i<$a;$i++){
						$row =& $rows[$i];
				?>
					<tr id="content<?php echo $row->product_id?>" class="<?php echo "row$k"; ?>" onclick="applyContent(<?php echo $row->product_id.",'row$k'"?>);" style="cursor:pointer;">
						<td class="acytdcheckbox"></td>
						<td>
						<?php
							echo acymailing_tooltip('SKU : '.$row->product_sku,$row->product_name,'',$row->product_name);
						?>
						</td>
						<td>
						<?php
							echo strip_tags($row->product_s_desc,'<br><p>');
						?>
						</td>
						<td align="center">
							<?php echo $row->product_id; ?>
						</td>
					</tr>
				<?php
						$k = 1-$k;
					}
				?>
			</tbody>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $pageInfo->filter->order->value; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $pageInfo->filter->order->dir; ?>" />
	<?php

	echo $tabs->endPanel();
	echo $tabs->startPanel( JText::_( 'TAG_CATEGORIES' ), 'vm_auto');

	$type = JRequest::getString('type');

	if(version_compare($this->version,'2.0.0','<' )){
		$db->setQuery('SELECT a.*,b.* FROM `#__vm_category` as a LEFT JOIN `#__vm_category_xref` as b ON a.category_id = b.category_child_id ORDER BY `list_order`');
	}else{
		$db->setQuery('SELECT a.*,b.*,c.*,a.virtuemart_category_id as category_id FROM `#__virtuemart_categories` as a LEFT JOIN `#__virtuemart_category_categories` as b ON a.virtuemart_category_id = b.category_child_id LEFT JOIN `#__virtuemart_categories_'.$this->lang.'` as c ON a.virtuemart_category_id = c.virtuemart_category_id ORDER BY a.`ordering`');
	}

	$categories = $db->loadObjectList('category_id');

	$this->cats = array();
	foreach($categories as $oneCat){
		$this->cats[$oneCat->category_parent_id][] = $oneCat;
	}

		$ordering = array();
		if(version_compare($this->version,'2.0.0','<' )){
			$ordering[] = JHTML::_('select.option', "|order:product_id,DESC",JText::_('ACY_ID'));
			$ordering[] = JHTML::_('select.option', "|order:cdate,DESC",JText::_('CREATED_DATE'));
			$ordering[] = JHTML::_('select.option', "|order:mdate,DESC",JText::_('MODIFIED_DATE'));
			$ordering[] = JHTML::_('select.option', "|order:product_name,ASC",JText::_('FIELD_TITLE'));
			$ordering[] = JHTML::_('select.option', "|order:product_price,ASC",'Price');
		}else{
			$ordering[] = JHTML::_('select.option', "|order:virtuemart_product_id,DESC",JText::_('ACY_ID'));
			$ordering[] = JHTML::_('select.option', "|order:created_on,DESC",JText::_('CREATED_DATE'));
			$ordering[] = JHTML::_('select.option', "|order:modified_on,DESC",JText::_('MODIFIED_DATE'));
			$ordering[] = JHTML::_('select.option', "|order:product_price,ASC",'Price');
		}

	?>
		<br style="font-size:1px"/>
	<script language="javascript" type="text/javascript">
		<!--
			var selectedCat = new Array();
			function applyAutoProduct(catid,rowClass){
				if(selectedCat[catid]){
					window.document.getElementById('product_cat'+catid).className = rowClass;
					delete selectedCat[catid];
				}else{
					window.document.getElementById('product_cat'+catid).className = 'selectedrow';
					selectedCat[catid] = 'product';
				}

				updateTagAuto();
			}

			function updateTagAuto(){
				tag = '{autovmproduct:';

				for(var icat in selectedCat){
					if(selectedCat[icat] == 'product'){
						tag += icat+'-';
					}
				}

				for(var i=0; i < document.adminForm.contenttypeauto.length; i++){
					 if (document.adminForm.contenttypeauto[i].checked){ tag += '|type:'+document.adminForm.contenttypeauto[i].value; }
				}

				if(document.adminForm.manufacturer && document.adminForm.manufacturer.value && document.adminForm.manufacturer.value!=0){ tag += '|manu:'+document.adminForm.manufacturer.value; }

				if(document.adminForm.min_article && document.adminForm.min_article.value && document.adminForm.min_article.value!=0){ tag += '|min:'+document.adminForm.min_article.value; }
				if(document.adminForm.max_article.value && document.adminForm.max_article.value!=0){ tag += '|max:'+document.adminForm.max_article.value; }
				if(document.adminForm.contentorder.value){ tag += document.adminForm.contentorder.value; }
				if(document.adminForm.contentfilter && document.adminForm.contentfilter.value){ tag += document.adminForm.contentfilter.value; }
				if(window.document.getElementById('jflangvm')  && window.document.getElementById('jflangvm').value != ''){
					tag += '|lang:';
					tag += window.document.getElementById('jflangvm').value;
				}
				if(document.adminForm.cols.value>1){ tag += '|cols:'+document.adminForm.cols.value; }

				tag += '}';

				setTag(tag);
			}
		//-->
	</script>
	<table width="100%" class="adminform">
		<tr>
			<td>
				<?php echo JText::_('DISPLAY');?>
			</td>
			<td colspan="2">
			<?php echo JHTML::_('acyselect.radiolist', $contentType, 'contenttypeauto' , 'size="1" onclick="updateTagAuto();"', 'value', 'text', 'full'); ?>
			</td>
			<td>
				<?php $jflanguages = acymailing_get('type.jflanguages');
				if(!empty($jflanguages->values)){
					$jflanguages->id = 'jflangvm'; $jflanguages->onclick = 'onchange="updateTagAuto();"'; echo $jflanguages->display('language');
				}?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo JText::_('MAX_ARTICLE'); ?>
			 </td>
			 <td>
			 	<input name="max_article" type="text" style="width:50px" value="20" onchange="updateTagAuto();"/>
			</td>
			<td>
				<?php echo JText::_('ACY_ORDER'); ?>
			 </td>
			 <td>
			 	<?php echo JHTML::_('select.genericlist', $ordering, 'contentorder' , 'size="1" style="width:150px" onchange="updateTagAuto();"'); ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo 'Manufacturer'; ?>
			 </td>
			 <td>
			 	<?php
			 	if(version_compare($this->version,'2.0.0','<' )){
			 		$db->setQuery('SELECT mf_name, manufacturer_id FROM #__vm_manufacturer ORDER BY mf_name ASC');
			 	}else{
			 		$db->setQuery('SELECT mf_name, a.virtuemart_manufacturer_id as manufacturer_id FROM #__virtuemart_manufacturers as a LEFT JOIN #__virtuemart_manufacturers_'.$this->lang.' as b ON a.virtuemart_manufacturer_id = b.virtuemart_manufacturer_id ORDER BY mf_name ASC');
			 	}

			 	$manufacturers = $db->loadAssocList();
			 	$firstVal = array('mf_name' => ' - - - ','manufacturer_id'=>0);
			 	array_unshift($manufacturers,$firstVal);
			 	echo JHTML::_('select.genericlist', $manufacturers, 'manufacturer' , 'size="1" style="width:150px" onchange="updateTagAuto();"','manufacturer_id','mf_name'); ?>
			</td>
			<td>
				<?php echo JText::_('FIELD_COLUMNS'); ?>
			 </td>
			 <td>
			 	<select name="cols" style="width:150px" onchange="updateTagAuto();" size="1">
			 		<?php for($o = 1;$o<11;$o++) echo '<option value="'.$o.'">'.$o.'</option>'; ?>
			 	</select>
			</td>
		</tr>
		<?php if($type == 'autonews') { ?>
		<tr>
			<td>
			<?php 	echo JText::_('MIN_ARTICLE'); ?>
			 </td>
			 <td>
			 <input name="min_article" type="text" style="width:50px" value="1" onchange="updateTagAuto();"/>
			 </td>
			<td>
			<?php echo JText::_('ACY_FILTER'); ?>
			 </td>
			 <td>
			 	<?php $filter = acymailing_get('type.contentfilter'); $filter->onclick = 'updateTagAuto();'; echo $filter->display('contentfilter','|filter:created'); ?>
			</td>
		</tr>
		<?php } ?>
	</table>
	<table class="adminlist table table-striped table-hover" cellpadding="1" width="100%">
	<?php $k=0; echo $this->displayChildren(0,$k); ?>
	</table>
	<?php

	echo $tabs->endPanel();
	echo $tabs->startPanel(JText::_('ACY_COUPON'),'vm_coupon');
	$value= array();
 	$value[] = JHTML::_('select.option', 'percent',JText::_('COUPON_PERCENT'));
 	$value[] = JHTML::_('select.option', 'total',JText::_('COUPON_TOTAL'));
 	$percent_total = JHTML::_('acyselect.radiolist', $value, 'coupon_percent' , 'onclick="updateTagCoupon();"', 'value', 'text', 'percent');

 	$value= array();
 	$value[] = JHTML::_('select.option', 'permanent',JText::_('COUPON_PERMANENT'));
 	$value[] = JHTML::_('select.option', 'gift',JText::_('COUPON_GIFT'));
 	$permanent = JHTML::_('acyselect.radiolist', $value, 'coupon_permanent' , 'onclick="updateTagCoupon();"', 'value', 'text', 'gift');

	?>
	<script language="javascript" type="text/javascript">
	<!--
		function updateTagCoupon(){
			tagname = '';
			for(var i=0; i < document.adminForm.coupon_percent.length; i++){
				 if (document.adminForm.coupon_percent[i].checked){ tagname += document.adminForm.coupon_percent[i].value+'|'; }
			}
			for(var i=0; i < document.adminForm.coupon_permanent.length; i++){
				 if (document.adminForm.coupon_permanent[i].checked){ tagname += document.adminForm.coupon_permanent[i].value+'|'; }
			}
			tagname += document.adminForm.coupon_value.value+'|';
			tagname += document.adminForm.coupon_name.value;

			setTag('{vmcoupon:'+tagname+'}');
		}
	//-->
	</script>
	<table class="adminlist table table-striped table-hover" cellpadding="1">
	<tr><td><?php echo JText::_('COUPON_NAME'); ?></td><td><input id="coupon_name" onchange="updateTagCoupon();" type="text" style="width:160px" value="[name][key][value]"></td><td><?php echo $permanent; ?></td></tr>
	<tr><td><?php echo JText::_('COUPON_VALUE'); ?></td><td><input id="coupon_value" onchange="updateTagCoupon();" type="text" style="width:50px" value=""></td><td><?php echo $percent_total; ?></td></tr>
	</table>

	<?php

	echo $tabs->endPanel();
	echo $tabs->startPanel(JText::_('RECEIVER_INFORMATION'),'vm_userinfo');

	echo '<br style="font-size:1px"/>';
	echo '<table class="adminlist table table-striped table-hover" cellpadding="1">';

	if(version_compare($this->version,'2.0.0','<' )){
		$fields = acymailing_getColumns('#__vm_user_info');
	}else{
		$fields = acymailing_getColumns('#__virtuemart_userinfos');
	}

	$k = 0;
	foreach($fields as $fieldname => $oneField){
		if(preg_match('#_(on|id|by)$#i',$fieldname)) continue;
		$type = '';
		if(strpos(strtolower($oneField),'date') !== false) $type = '|type:date';
		echo '<tr style="cursor:pointer" class="row'.$k.'" onclick="setTag(\'{vmfield:'.$fieldname.$type.'}\');insertTag();" ><td class="acytdcheckbox"></td><td>'.$fieldname.'</td></tr>';
		$k = 1-$k;
	}
	echo '</table>';

	echo $tabs->endPanel();
	echo $tabs->endPane();

	 }

	 function displayChildren($parentid,&$k,$level = 0){
	 	if(empty($this->cats[$parentid])) return;

	 	foreach($this->cats[$parentid] as $oneCat){
	 		$k = 1 - $k;
	 		echo '<tr id="product_cat'.$oneCat->category_id.'" class="row'.$k.'" onclick="applyAutoProduct('.$oneCat->category_id.',\'row'.$k.'\');" style="cursor:pointer;"><td class="acytdcheckbox"></td><td>';
			echo str_repeat('- - ',$level).$oneCat->category_name.'</td></tr>';
	 		$this->displayChildren($oneCat->category_id,$k,$level+1);
		}
	 }

	 function acymailing_replacetags(&$email,$send = true){
	 	if(!$this->version) return;

	 	$this->_replaceAuto($email);
	 	$this->_replaceProducts($email);
 	}

	function _replaceAuto(&$email){
		$this->acymailing_generateautonews($email);

		if(!empty($this->tags)){
			$email->body = str_replace(array_keys($this->tags),$this->tags,$email->body);
			if(!empty($email->altbody)) $email->altbody = str_replace(array_keys($this->tags),$this->tags,$email->altbody);
		}
	}

	function acymailing_replaceusertags(&$email,&$user,$send = true){
		if(!$this->version) return;
		$this->_replaceCoupon($email,$user,$send);
		$this->_replaceFields($email,$user,$send);
	}

	function _replaceFields(&$email,&$user,$send){
		$match = '#{vmfield:(.*)}#Ui';
		$variables = array('subject','body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			if(empty($results[$var][0])) unset($results[$var]);
		}

		if(!$found) return;

		$pluginsHelper = acymailing_get('helper.acyplugins');
		$db= JFactory::getDBO();

		if(version_compare($this->version,'2.0.0','<')){
			$myquery = "SELECT * FROM #__vm_user_info WHERE user_email = ".$db->Quote($user->email);
		}else{
			$myquery = "SELECT * FROM #__virtuemart_userinfos WHERE virtuemart_user_id = ".intval($user->userid)." AND virtuemart_user_id > 0";
		}

		$db->setQuery($myquery);
		$vmuser = $db->loadObject();

		$tags = array();
		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				if(isset($tags[$oneTag])) continue;

				$arguments = explode('|',strip_tags($allresults[1][$i]));
				$field = $arguments[0];
				unset($arguments[0]);
				$mytag = new stdClass();
				$mytag->default = $this->params->get('default_'.$field,'');
				if(!empty($arguments)){
					foreach($arguments as $onearg){
						$args = explode(':',$onearg);
						if(isset($args[1])){
							$mytag->$args[0] = $args[1];
						}else{
							$mytag->$args[0] = 1;
						}
					}
				}

				$tags[$oneTag] = (isset($vmuser->$field) && strlen($vmuser->$field) > 0) ? $vmuser->$field : $mytag->default;

				$pluginsHelper->formatString($tags[$oneTag],$mytag);
			}
		}

		foreach($results as $var => $allresults){
			$email->$var = str_replace(array_keys($tags),$tags,$email->$var);
		}
	}


	function _replaceCoupon(&$email,&$user,$send){

		if(empty($user->subid) || !$send) return;

		$match = '#{vmcoupon:(.*)}#Ui';
		$variables = array('subject','body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			if(empty($results[$var][0])) unset($results[$var]);
		}

		if(!$found) return;

		$tags = array();
		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				if(isset($tags[$oneTag])) continue;

				$tags[$oneTag] = $this->generateCoupon($allresults,$i,$user);
			}
		}

		foreach(array_keys($results) as $var){
			$email->$var = str_replace(array_keys($tags),$tags,$email->$var);
		}
	}

	function generateCoupon(&$allresults,$i,&$user){
		list($percent,$gift,$value,$name) = explode('|',$allresults[1][$i]);
		$db = JFactory::getDBO();
		$key = JUserHelper::genrandompassword(5);
		$value = str_replace(',','.',$value);
		$name = str_replace(array('[name]','[subid]','[email]','[key]','[value]'),array($user->name,$user->subid,$user->email,$key,$value),$name);

		if(version_compare($this->version,'2.0.0','<' )){
			$db->setQuery('INSERT INTO #__vm_coupons (`coupon_code`,`percent_or_total`,`coupon_type`,`coupon_value`) VALUES ('.$db->Quote($name).','.$db->Quote($percent).','.$db->Quote($gift).','.$db->Quote($value).')');
		}else{
			$db->setQuery('INSERT INTO #__virtuemart_coupons (`coupon_code`,`percent_or_total`,`coupon_type`,`coupon_value`,`coupon_start_date`) VALUES ('.$db->Quote($name).','.$db->Quote($percent).','.$db->Quote($gift).','.$db->Quote($value).','.$db->Quote(date('Y-m-d')).')');
		}
		$db->query();

		return $name;
	}

	function acymailing_generateautonews(&$email){
		if(!$this->version) return;

		$return = new stdClass();
		$return->status = true;
		$return->message = '';

		$time = time();
		$match = '#{autovmproduct:(.*)}#Ui';
		$variables = array('body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			if(empty($results[$var][0])) unset($results[$var]);
		}

		if(!$found) return $return;

		$this->tags = array();
		$db = JFactory::getDBO();

		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				if(isset($this->tags[$oneTag])) continue;

				$arguments = explode('|',strip_tags($allresults[1][$i]));
				$allcats = explode('-',$arguments[0]);
				$parameter = new stdClass();
				for($i=1;$i<count($arguments);$i++){
					$args = explode(':',$arguments[$i]);
					$arg0 = trim($args[0]);
					if(isset($args[1])){
						$parameter->$arg0 = $args[1];
					}else{
						$parameter->$arg0 = true;
					}
				}
				$selectedArea = array();
				foreach($allcats as $oneCat){
					if(empty($oneCat)) continue;
					$selectedArea[] = (int) $oneCat;
				}

				if(version_compare($this->version,'2.0.0','<' )){
					$query = 'SELECT DISTINCT b.`product_id` FROM `#__vm_product_category_xref` as a LEFT JOIN `#__vm_product` as b ON a.product_id = b.product_id';
				}else{
					$query = 'SELECT DISTINCT b.`virtuemart_product_id` FROM `#__virtuemart_product_categories` as a LEFT JOIN `#__virtuemart_products` as b ON a.virtuemart_product_id = b.virtuemart_product_id';
				}
				$where = array();
				if(!empty($parameter->manu)){
					if(version_compare($this->version,'2.0.0','<' )){
						$query .= ' LEFT JOIN #__vm_product_mf_xref as c on c.product_id = a.product_id';
						$where[] = "c.manufacturer_id = ".intval($parameter->manu);
					}else{
						$query .= ' LEFT JOIN #__virtuemart_product_manufacturers as c on c.virtuemart_product_id = a.virtuemart_product_id';
						$where[] = "c.virtuemart_manufacturer_id = ".intval($parameter->manu);
					}
				}
				$orderBy = '';
				if(!empty($parameter->order)){
					$ordering = explode(',',$parameter->order);
					$orderBy = ' ORDER BY b.`'.acymailing_secureField($ordering[0]).'` '.acymailing_secureField($ordering[1]);
					if($ordering[0] == 'product_price'){
						if(version_compare($this->version,'2.0.0','<' )){
							$query .= ' LEFT JOIN #__vm_product_price as d on d.product_id = a.product_id';
							$orderBy = ' ORDER BY d.`'.acymailing_secureField($ordering[0]).'` '.acymailing_secureField($ordering[1]);
						}else{
							$query .= ' LEFT JOIN #__virtuemart_product_prices as d on d.virtuemart_product_id = a.virtuemart_product_id';
							$orderBy = ' ORDER BY d.`'.acymailing_secureField($ordering[0]).'` '.acymailing_secureField($ordering[1]);
						}
					}
				}

				if($this->params->get('stock',0) == '1') $where[] = 'b.product_in_stock > 0';

				if(!empty($selectedArea)){
					if(version_compare($this->version,'2.0.0','<' )){
						$where[] = 'a.category_id IN ('.implode(',',$selectedArea).')';
					}else{
						$where[] = 'a.virtuemart_category_id IN ('.implode(',',$selectedArea).')';
					}
				}

				if(!empty($parameter->featured)) $where[] = version_compare($this->version,'2.0.0','<' ) ? "b.product_special='Y'" : "b.product_special=1";
				if(version_compare($this->version,'2.0.0','<' )){
					if(!empty($parameter->discounted)) $where[] = "b.product_discount_id > 0";
				}

				$where[] = version_compare($this->version,'2.0.0','<' ) ? "b.`product_publish` = 'Y'" : "b.`published` = 1";
				if(!empty($parameter->filter) AND !empty($email->params['lastgenerateddate'])){
					$condition = version_compare($this->version,'2.0.0','<' ) ? 'b.`cdate` >\''.$email->params['lastgenerateddate'].'\'' : "b.`created_on` > '".date( 'Y-m-d H:i:s',$email->params['lastgenerateddate'] - date('Z'))."'";
					if($parameter->filter == 'modify'){
						$condition .= version_compare($this->version,'2.0.0','<' ) ? ' OR b.`mdate` >\''.$email->params['lastgenerateddate'].'\'' : "OR b.`modified_on` > '".date( 'Y-m-d H:i:s',$email->params['lastgenerateddate'] - date('Z'))."'";
					}

					$where[] = $condition;
				}

				$query .= ' WHERE ('.implode(') AND (',$where).')';
				if(!empty($orderBy)){
					$query .= $orderBy;
				}
				if(!empty($parameter->max)) $query .= ' LIMIT '.(int) $parameter->max;

				$db->setQuery($query);
				$allArticles = acymailing_loadResultArray($db);

				if(!empty($parameter->min) AND count($allArticles)< $parameter->min){
					$return->status = false;
					$return->message = 'Not enough products for the tag '.$oneTag.' : '.count($allArticles).' / '.$parameter->min;
				}

				$stringTag = '';
				if(!empty($allArticles)){
					if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'autovmproduct.php')){
						ob_start();
						require(ACYMAILING_MEDIA.'plugins'.DS.'autovmproduct.php');
						$stringTag = ob_get_clean();
					}else{
						$arrayElements = array();
						foreach($allArticles as $oneArticleId){
							$args = array();
							$args[] = 'vmproduct:'.$oneArticleId;
							if(!empty($parameter->type)) $args[] = 'type:'.$parameter->type;
							if(!empty($parameter->lang)) $args[] = 'lang:'.$parameter->lang;
							if(!empty($parameter->language)) $args[] = 'language:'.$parameter->language;
							if(isset($parameter->noprice)) $args[] = 'noprice';
							if(!empty($parameter->itemid)) $args[] = 'itemid:'.$parameter->itemid;
							if(!empty($parameter->shoppergroup)) $args[] = 'shoppergroup:'.$parameter->shoppergroup;
							$arrayElements[] = '{'.implode('|',$args).'}';
						}
						$acypluginsHelper = acymailing_get('helper.acyplugins');
						$stringTag = $acypluginsHelper->getFormattedResult($arrayElements,$parameter);
					}
				}

				$this->tags[$oneTag] = $stringTag;
			}
		}

		return $return;
	}

 	function _replaceProducts(&$email){

		$match = '#{vmproduct:(.*)}#Ui';
		$variables = array('body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			if(empty($results[$var][0])) unset($results[$var]);
		}

		if(!$found) return;

		$mailerHelper = acymailing_get('helper.mailer');

		if(version_compare($this->version,'2.0.0','>=' )){
			include_once(ACYMAILING_ROOT.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
			include_once(ACYMAILING_ROOT.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'models'.DS.'product.php');
		}


		$resultshtml = array();
		$resultstext = array();
		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				if(isset($resultshtml[$oneTag])) continue;

				$resultshtml[$oneTag] = $this->_replaceProduct($allresults,$i);
				$resultstext[$oneTag] = $mailerHelper->textVersion($resultshtml[$oneTag]);
			}
		}

		$email->body = str_replace(array_keys($resultshtml),$resultshtml,$email->body);
		$email->altbody = str_replace(array_keys($resultstext),$resultstext,$email->altbody);
	 }

	 function _replaceProduct(&$allresults,$i){
		$arguments = explode('|',strip_tags($allresults[1][$i]));
		$tag = new stdClass();
		$tag->id = (int) $arguments[0];
		$tag->lang = $this->lang;
		$tag->shoppergroup = intval($this->params->get('shopper_group_id'));
		$tag->itemid = intval($this->params->get('itemid'));
		for($i=1,$a=count($arguments);$i<$a;$i++){
			$args = explode(':',$arguments[$i]);
			$arg0 = trim($args[0]);
			if(isset($args[1])){
				$tag->$arg0 = $args[1];
			}else{
				$tag->$arg0 = true;
			}
		}

		if(version_compare($this->version,'2.0.0','<' )){
			$result = $this->_getProduct1($tag);
		}else{
			$result = $this->_getProduct2($tag);
		}

		if(isset($tag->pict)){
			$pictureHelper = acymailing_get('helper.acypict');
			$pictureHelper->maxHeight = empty($tag->maxheight) ? $this->params->get('maxheight',150) : $tag->maxheight;
			$pictureHelper->maxWidth = empty($tag->maxwidth) ? $this->params->get('maxwidth',150) : $tag->maxwidth;
			if($tag->pict == '0'){
				$result = $pictureHelper->removePictures($result);
			}elseif($tag->pict == 'resized'){
				if($pictureHelper->available()){
					$result = $pictureHelper->resizePictures($result);
				}elseif($app->isAdmin()){
					$app->enqueueMessage($pictureHelper->error,'notice');
				}
			}
		}

		return $result;
	 }

	 function _getProduct2($tag){
	 	if(!defined('VMLANG') && !empty($tag->language)) define('VMLANG',str_replace('-','_',trim($tag->language)));
	 	$vmProductModel = new VirtueMartModelProduct();
		$product = $vmProductModel->getProduct($tag->id,true,true,false);
		$vmProductModel->addImages($product);


		$description = (empty($tag->type) || $tag->type == 'full') ? $product->product_desc : $product->product_s_desc;
		$link = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id;
		if(!empty($tag->lang)) $link.= '&lang='.$tag->lang;
		if(!empty($tag->itemid)) $link .= '&Itemid='.$tag->itemid;
		$link = acymailing_frontendLink($link);

		$price1 = $product->product_price;
		if(!empty($product->product_override_price) && $product->override){
			$price2 = $product->product_override_price;
		}

		if($this->params->get('vat',1)){
			if(!empty($product->prices['basePriceWithTax'])) $price1 = $product->prices['basePriceWithTax'];
			if(!empty($product->prices['salesPrice']) && $product->prices['basePriceWithTax'] != $product->prices['salesPrice']) $price2 = $product->prices['salesPrice'];
		}

		$currencyHelper = CurrencyDisplay::getInstance($product->product_currency);
		$price = $currencyHelper->priceDisplay($price1,$product->product_currency);
		if(!empty($price2)) $price2 = $currencyHelper->priceDisplay($price2,$product->product_currency);

		$finalPrice = empty($price2) ? $price : '<strike>'.$price.'</strike> '.$price2;

		if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'tagvmproduct.php')){
			ob_start();
			require(ACYMAILING_MEDIA.'plugins'.DS.'tagvmproduct.php');
			return ob_get_clean();
		}

		$result = '';
		$astyle = '';
		if(empty($tag->type) || $tag->type != 'title'){
			$result .= '<div class="acymailing_content">';
			$astyle = 'style="text-decoration:none;" name="product-'.$product->virtuemart_product_id.'"';
		}

		$result .= '<a '.$astyle.' target="_blank" href="'.$link.'">';
		if(empty($tag->type) || $tag->type != 'title') $result .= '<h2 class="acymailing_title">';
		$result .= $product->product_name;
		if(empty($tag->noprice)) $result.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$finalPrice;
		if(empty($tag->type) || $tag->type != 'title') $result .= '</h2>';
		$result .= '</a>';

		if(empty($tag->type) || $tag->type != 'title'){
			if(!empty($product->images)){
				$mainPict = reset($product->images);
				if(!empty($mainPict->file_url_thumb)){
					$result .= '<a target="_blank" style="text-decoration:none;border:0px" href="'.$link.'" ><img style="float:left;margin:5px;border:0px" alt="'.$product->product_name.'" src="'.$mainPict->file_url_thumb.'" /></a>';
				}
			}

			$result .= $description;

		}
		if(empty($tag->type) || $tag->type != 'title') $result .= '</div>';

		return $result;

	 }

	 function _getProduct1($tag){

	 	$time = time();


		$query = 'SELECT d.*, c.*, b.*, a.* FROM '.acymailing_table('vm_product',false).' as a ';
		$query .= ' LEFT JOIN '.acymailing_table('vm_product_price',false).' as b on a.product_id = b.product_id';
		if(!empty($tag->shoppergroup)) $query .= ' AND b.shopper_group_id = '.$tag->shoppergroup;
		$query .= ' LEFT JOIN '.acymailing_table('vm_tax_rate',false).' as c on a.product_tax_id = c.tax_rate_id ';
		$query .= ' LEFT JOIN '.acymailing_table('vm_product_discount',false).' as d on a.`product_discount_id` = d.`discount_id` AND d.`start_date` < '.$time.' AND (d.`end_date` = 0 OR d.`end_date` > '.$time.') ';
		$query .= ' WHERE a.product_id = '.$tag->id.' LIMIT 1';

		$db = JFactory::getDBO();
		$db->setQuery($query);
		$product = $db->loadObject();

		if(empty($product)){
			$app = JFactory::getApplication();
			if($app->isAdmin()){
				$app->enqueueMessage('The product "'.$tag->id.'" could not be loaded','notice');
			}
			return '';
		}

		if(!empty($tag->lang)){
			$langid = (int) substr($tag->lang,strpos($tag->lang,',')+1);
			if(!empty($langid)){
				$query = "SELECT reference_field, value FROM `#__jf_content` WHERE `published` = 1 AND `reference_table` = 'vm_product' AND `language_id` = $langid AND `reference_id` = ".$tag->id;
				$db->setQuery($query);
				$translations = $db->loadObjectList();
				if(!empty($translations)){
					foreach($translations as $oneTranslation){
						if(!empty($oneTranslation->value)){
							$translatedfield =  $oneTranslation->reference_field;
							$product->$translatedfield = $oneTranslation->value;
						}
					}
				}
			}
		}

		if($this->params->get('vat',1) AND !empty($product->tax_rate)) $product->product_price = $product->product_price * (1 + $product->tax_rate);

		$description = (empty($tag->type) || $tag->type == 'full') ? $product->product_desc : $product->product_s_desc;
		$link = 'index.php?option=com_virtuemart&page=shop.product_details&product_id='.$product->product_id;
		if(!empty($tag->lang)) $link.= '&lang='.substr($tag->lang, 0,strpos($tag->lang,','));
		if(!empty($tag->itemid)) $link .= '&Itemid='.$tag->itemid;
		$link = acymailing_frontendLink($link);

		if(!empty($product->amount)){
			$price2 = empty($product->is_percent) ? $product->product_price - $product->amount : $product->product_price - ($product->amount * $product->product_price / 100);
		}

		switch($product->product_currency) {
			case 'USD': $product->product_currency='$';break;
			case 'EUR': $product->product_currency='€';break;
			case 'GBP': $product->product_currency='£';break;
			case 'JPY': $product->product_currency='¥';break;
			case 'AUD': $product->product_currency='AUD $';break;
			case 'CAD': $product->product_currency='CAD $';break;
			case 'HKD': $product->product_currency='HKD $';break;
			case 'NZD': $product->product_currency='NZD $';break;
			case 'SGD': $product->product_currency='SGD $';break;
			case 'RUB': $product->product_currency='руб.';break;
			case 'ZAR': $product->product_currency='R';break;
		}

		if($this->params->get('priceformat','english') == 'french'){
			$price = number_format($product->product_price, 2, ',', ' ').' '.$product->product_currency;
			if(!empty($price2)) $price2 = number_format($price2, 2, ',', ' ').' '.$product->product_currency;
		}else{
			$price = $product->product_currency.number_format($product->product_price, 2, '.', '');
			if(!empty($price2)) $price2 = $product->product_currency.number_format($price2, 2, '.', '');
		}

		$finalPrice = empty($price2) ? $price : '<strike>'.$price.'</strike> '.$price2;

		if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'tagvmproduct.php')){
			ob_start();
			require(ACYMAILING_MEDIA.'plugins'.DS.'tagvmproduct.php');
			return ob_get_clean();
		}

		$result = '';
		$astyle = '';
		if(empty($tag->type) || $tag->type != 'title'){
			$result .= '<div class="acymailing_content">';
			$astyle = 'style="text-decoration:none;" name="product-'.$product->product_id.'"';
		}

		$result .= '<a '.$astyle.' target="_blank" href="'.$link.'">';
		if(empty($tag->type) || $tag->type != 'title') $result .= '<h2 class="acymailing_title">';
		$result .= $product->product_name;
		if(empty($tag->noprice)) $result.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$finalPrice;
		if(empty($tag->type) || $tag->type != 'title') $result .= '</h2>';
		$result .= '</a>';

		if(empty($tag->type) || $tag->type != 'title'){
			if(!empty($product->product_thumb_image)){
				$img = $product->product_thumb_image;
				if(file_exists(ACYMAILING_ROOT.'components'.DS.'com_virtuemart'.DS.'shop_image'.DS.'product'.DS.'resized'.DS.substr($img,0,strrpos($img,'.')).'_90x90'.substr($img,strrpos($img,'.')))){
					$img = 'resized/'.substr($img,0,strrpos($img,'.')).'_90x90'.substr($img,strrpos($img,'.'));
				}
				$picturePath = (strpos($img,'http') === 0) ? $img : ACYMAILING_LIVE.'components/com_virtuemart/shop_image/product/'.$img;
				$result .= '<a target="_blank" style="text-decoration:none;border:0px" href="'.$link.'" ><img style="float:left;margin:5px;border:0px" alt="'.$product->product_name.'" src="'.$picturePath.'" /></a>'.$description;
			}else{
				$result .= $description;
			}
		}
		if(empty($tag->type) || $tag->type != 'title') $result .= '</div>';

		return $result;

	}

	function onAcyDisplayFilters(&$type,$context="massactions"){

		if($this->params->get('displayfilter_'.$context,true) == false) return;
		if(!$this->version) return;

		$db = JFactory::getDBO();
		if(version_compare($this->version,'2.0.0','<')){
			$db->setQuery("SELECT `product_id` as value, CONCAT(`product_id`,' : ',`product_sku`,' ( ',`product_name`,' ) ') as text FROM #__vm_product ORDER BY `product_id` ASC");
		}else{
			$db->setQuery("SELECT a.`virtuemart_product_id` as value, CONCAT(a.`virtuemart_product_id`,' : ',`product_sku`,' ( ',`product_name`,' ) ') as text FROM #__virtuemart_products as a LEFT JOIN #__virtuemart_products_".$this->lang." as b ON a.virtuemart_product_id = b.virtuemart_product_id ORDER BY a.`virtuemart_product_id` ASC");
		}

		$allProducts = $db->loadObjectList();
		if(!empty($allProducts)){
			$selectOne = new stdClass();
			$selectOne->value = 0;
			$selectOne->text = JText::_('ACY_ONE_PRODUCT');
			array_unshift($allProducts,$selectOne);
		}

		$vmbuy = array();
		$vmbuy[] = JHTML::_('select.option', '0', JText::_('ACY_DIDNOTBOUGHT') );
		$vmbuy[] = JHTML::_('select.option', '1', JText::_('ACY_BOUGHT') );


		$vmgroupsparams = acymailing_get('type.operatorsin');
		$vmgroupsparams->js = 'onchange="countresults(__num__)"';
		$operators = acymailing_get('type.operators');
		$operators->extra = 'onchange="countresults(__num__)"';
		if(version_compare($this->version,'2.0.0','<')){
			$db->setQuery('SELECT `shopper_group_id` as value, `shopper_group_name` as text FROM `#__vm_shopper_group` ORDER BY `shopper_group_name` ASC');
		}else{
			$db->setQuery('SELECT `virtuemart_shoppergroup_id` as value, `shopper_group_name` as text FROM `#__virtuemart_shoppergroups` ORDER BY `shopper_group_name` ASC');
		}

		$vmgroups = $db->loadObjectList();

		if(version_compare($this->version,'2.0.0','<')){
			$fields = acymailing_getColumns('#__vm_user_info');
		}else{
			$fields = acymailing_getColumns('#__virtuemart_userinfos');
		}
		$vmfield = array();
		foreach($fields as $oneField => $fieldType){
			$vmfield[] = JHTML::_('select.option',$oneField,$oneField);
		}

		if(version_compare($this->version,'2.0.0','<')){
			$db->setQuery('SELECT order_status_code as code, order_status_name as name FROM `#__vm_order_status` ORDER BY `list_order` ASC');
		}else{
			$db->setQuery('SELECT order_status_code as code, order_status_name as name FROM `#__virtuemart_orderstates` ORDER BY `ordering` ASC');
		}

		$allStatus = $db->loadObjectList();
		if(!empty($allStatus)){
			$firstStatus = new stdClass();
			$firstStatus->name = JText::_('ALL_STATUS');
			$firstStatus->code = '';
			array_unshift($allStatus,$firstStatus);
		}

		if(version_compare($this->version,'2.0.0','<')){
			$db->setQuery("SELECT `payment_method_name` as name, `payment_method_id` as id FROM `#__vm_payment_method` WHERE `payment_enabled` = 'Y' ORDER BY `list_order` ASC");
		}else{
			$db->setQuery("SELECT `payment_name` as name, a.`virtuemart_paymentmethod_id` as id FROM `#__virtuemart_paymentmethods` as a LEFT JOIN `#__virtuemart_paymentmethods_".$this->lang."` as b ON a.virtuemart_paymentmethod_id = b.virtuemart_paymentmethod_id WHERE `published` = 1 ORDER BY `ordering` ASC");
		}

		$allPayments = $db->loadObjectList();
		if(!empty($allPayments)){
			$firstPayment = new stdClass();
			$firstPayment->name = JText::_('ACY_ALL');
			$firstPayment->id = '';
			array_unshift($allPayments,$firstPayment);
		}

		$return = '';
		if(!empty($allStatus)){
			$return .= '<div id="filter__num__vmallorders">'.$vmgroupsparams->display("filter[__num__][vmallorders][type]").' ';
			$return .= JHTML::_('select.genericlist',   $allStatus, "filter[__num__][vmallorders][status]", 'class="inputbox" onchange="countresults(__num__)" size="1"', 'code', 'name').' ';
			$return .= JHTML::_('select.genericlist',   $allPayments, "filter[__num__][vmallorders][payment]", 'class="inputbox" onchange="countresults(__num__)" size="1"', 'id', 'name');
			$return .= '<br/> <input name="filter[__num__][vmallorders][cdateinf]" onchange="countresults(__num__)" /> < '.JText::_('CREATED_DATE').' < <input name="filter[__num__][vmallorders][cdatesup]" onchange="countresults(__num__)" />';
			$return .= '<br/> <input name="filter[__num__][vmallorders][mdateinf]" onchange="countresults(__num__)" /> < '.JText::_('MODIFIED_DATE').' < <input name="filter[__num__][vmallorders][mdatesup]" onchange="countresults(__num__)" />';
			$return .= '</div>';
			$type['vmallorders'] = JText::_('Virtuemart').' : '.JText::_('ACY_ORDERS');
		}

		if(!empty($allProducts)){
			$return .= '<div id="filter__num__vmorder">'.JHTML::_('select.genericlist', $vmbuy, "filter[__num__][vmorder][type]", 'class="inputbox" onchange="countresults(__num__)" size="1"', 'value', 'text',1).' ';
			$return .= JHTML::_('select.genericlist',   $allProducts, "filter[__num__][vmorder][product]", 'class="inputbox" style="max-width:200px" onchange="countresults(__num__)" size="1"', 'value', 'text').' '.JHTML::_('select.genericlist',   $allStatus, "filter[__num__][vmorder][status]", 'class="inputbox" onchange="countresults(__num__)" size="1"', 'code', 'name');
			$return .= '<br/> <input name="filter[__num__][vmorder][creationdateinf]" onchange="countresults(__num__)" /> < '.JText::_('CREATED_DATE').' < <input name="filter[__num__][vmorder][creationdatesup]" onchange="countresults(__num__)" />';
			$return .= '</div>';
			$type['vmorder'] = JText::_('Virtuemart').' : '.JText::_('ACY_PRODUCTS');
		}

		if(!empty($vmgroups)){
			$return .= '<div id="filter__num__vmgroups">'.$vmgroupsparams->display("filter[__num__][vmgroups][type]").' ';
			$return .= JHTML::_('select.genericlist', $vmgroups, "filter[__num__][vmgroups][group]", 'class="inputbox" size="1" onchange="countresults(__num__)"', 'value', 'text');
			$return .= '</div>';
			$type['vmgroups'] = JText::_('Virtuemart').' : '.JText::_('SHOPPER_GROUP');
		}

		if(!empty($vmfield)){
			$return .= '<div id="filter__num__vmfield">'.JHTML::_('select.genericlist',   $vmfield, "filter[__num__][vmfield][map]", 'class="inputbox" size="1" onchange="countresults(__num__)"', 'value', 'text');
			$return .= ' '.$operators->display("filter[__num__][vmfield][operator]").' <input class="inputbox" type="text" name="filter[__num__][vmfield][value]" onchange="countresults(__num__)" style="width:200px" value="" />';
			$return .= '</div>';
			$type['vmfield'] = JText::_('Virtuemart').' : '.JText::_('FIELD');
		}

		return $return;
	}

	function onAcyProcessFilterCount_vmfield(&$query,$filter,$num){
		$this->onAcyProcessFilter_vmfield($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	}

	function onAcyProcessFilter_vmfield(&$query,$filter,$num){
		if(version_compare($this->version,'2.0.0','<')){
			$myquery = "SELECT DISTINCT a.user_email FROM #__vm_user_info as a WHERE ".$query->convertQuery('a',$filter['map'],$filter['operator'],$filter['value']);
		}else{
			$myquery = "SELECT DISTINCT a.virtuemart_user_id FROM #__virtuemart_userinfos as a WHERE ".$query->convertQuery('a',$filter['map'],$filter['operator'],$filter['value']);
		}
		$query->db->setQuery($myquery);
		$allEmails  = acymailing_loadResultArray($query->db);
		if(empty($allEmails)) $allEmails[] = '-1';
		if(version_compare($this->version,'2.0.0','<')){
			$query->where[] = "sub.email IN ('".implode("','",$allEmails)."')";
		}else{
			$query->where[] = "sub.userid IN ('".implode("','",$allEmails)."')";
		}
	}

	function onAcyProcessFilterCount_vmallorders(&$query,$filter,$num){
		$this->onAcyProcessFilter_vmallorders($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	}

	function onAcyProcessFilter_vmallorders(&$query,$filter,$num){
		$db = JFactory::getDBO();
		if(version_compare($this->version,'2.0.0','<')){
	 		$lj = "`#__vm_orders` as vmallorders$num ON vmallorders$num.`user_id` = sub.`userid`";
		}else{
			$lj1 = "`#__virtuemart_order_userinfos` as vmuserinfos_$num ON vmuserinfos_$num.`email` = sub.`email`";
			$query->leftjoin['vmuserinfos_'.$num] = $lj1;
			$lj = "`#__virtuemart_orders` as vmallorders$num ON vmallorders$num.`virtuemart_order_id` = vmuserinfos_$num.`virtuemart_order_id`";
		}
	 	if(!empty($filter['status'])) $lj .= " AND vmallorders$num.`order_status` = ".$db->Quote($filter['status']);
	 	if(!empty($filter['cdateinf'])){
	 		$filter['cdateinf'] = acymailing_replaceDate($filter['cdateinf']);
	 		if(!is_numeric($filter['cdateinf'])) $filter['cdateinf'] = strtotime($filter['cdateinf']);
	 		if(version_compare($this->version,'2.0.0','<')){
		 		$lj .= " AND vmallorders$num.`cdate` > ".$db->Quote($filter['cdateinf']);
	 		}else{
	 			if(is_numeric($filter['cdateinf'])) $filter['cdateinf'] = strftime('%Y-%m-%d %H:%M:%S',$filter['cdateinf']);
		 		$lj .= " AND vmallorders$num.`created_on` > ".$db->Quote($filter['cdateinf']);
	 		}
	 	}
	 	if(!empty($filter['cdatesup'])){
	 		$filter['cdatesup'] = acymailing_replaceDate($filter['cdatesup']);
	 		if(!is_numeric($filter['cdatesup'])) $filter['cdatesup'] = strtotime($filter['cdatesup']);
	 		if(version_compare($this->version,'2.0.0','<')){
	 			$lj .= " AND vmallorders$num.`cdate` < ".$db->Quote($filter['cdatesup']);
	 		}else{
	 			if(is_numeric($filter['cdatesup'])) $filter['cdatesup'] = strftime('%Y-%m-%d %H:%M:%S',$filter['cdatesup']);
		 		$lj .= " AND vmallorders$num.`created_on` < ".$db->Quote($filter['cdateinf']);
	 		}
	 	}
	 	if(!empty($filter['mdateinf'])){
	 		$filter['mdateinf'] = acymailing_replaceDate($filter['mdateinf']);
	 		if(!is_numeric($filter['mdateinf'])) $filter['mdateinf'] = strtotime($filter['mdateinf']);
	 		if(version_compare($this->version,'2.0.0','<')){
	 			$lj .= " AND vmallorders$num.`mdate` > ".$db->Quote($filter['mdateinf']);
 			}else{
	 			if(is_numeric($filter['mdateinf'])) $filter['mdateinf'] = strftime('%Y-%m-%d %H:%M:%S',$filter['mdateinf']);
		 		$lj .= " AND vmallorders$num.`modified_on` > ".$db->Quote($filter['mdateinf']);
	 		}
	 	}
	 	if(!empty($filter['mdatesup'])){
	 		$filter['mdatesup'] = acymailing_replaceDate($filter['mdatesup']);
	 		if(!is_numeric($filter['mdatesup'])) $filter['mdatesup'] = strtotime($filter['mdatesup']);
	 		if(version_compare($this->version,'2.0.0','<')){
	 			$lj .= " AND vmallorders$num.`mdate` < ".$db->Quote($filter['mdatesup']);
	 		}else{
	 			if(is_numeric($filter['mdatesup'])) $filter['mdatesup'] = strftime('%Y-%m-%d %H:%M:%S',$filter['mdatesup']);
		 		$lj .= " AND vmallorders$num.`modified_on` < ".$db->Quote($filter['mdatesup']);
	 		}
	 	}

	 	if(!empty($filter['payment']) && version_compare($this->version,'2.0.0','>=')){
	 		$lj .= " AND vmallorders$num.`virtuemart_paymentmethod_id` = ".$db->Quote($filter['payment']);
	 	}

	 	$query->leftjoin['vmallorders_'.$num] = $lj;

		$operator = ($filter['type'] == 'IN') ? 'IS NOT NULL' : 'IS NULL';

	 	if(!empty($filter['payment']) && version_compare($this->version,'2.0.0','<')){
	 		$plj = "`#__vm_order_payment` as vmpay$num ON vmallorders$num.`order_id` = vmpay$num.`order_id` AND `payment_method_id` = ".intval($filter['payment']);
 			$query->leftjoin['vmpay_'.$num] = $plj;
 			$query->where[] = "vmpay$num.`order_id` ".$operator;
 			return;
	 	}else{
	 		if(version_compare($this->version,'2.0.0','<')){
	 			$query->where[] = "vmallorders$num.`user_id` ".$operator;
	 		}else{
	 			$query->where[] = "vmallorders$num.`virtuemart_order_id` ".$operator;
	 		}
	 	}

	}

	function onAcyProcessFilterCount_vmgroups(&$query,$filter,$num){
		$this->onAcyProcessFilter_vmgroups($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	}

	function onAcyProcessFilter_vmgroups(&$query,$filter,$num){
		if(version_compare($this->version,'2.0.0','<')){
			$myquery = 'SELECT DISTINCT b.`user_email` FROM `#__vm_shopper_vendor_xref` as a LEFT JOIN `#__vm_user_info` as b on a.`user_id` = b.`user_id` WHERE a.`shopper_group_id` ';
			$myquery .= ($filter['type'] == 'IN') ? '= ' : "!= ";
			$myquery .= (int) $filter['group'];

			$query->db->setQuery($myquery);
			$allEmails  = acymailing_loadResultArray($query->db);
			if(empty($allEmails)) $allEmails[] = 'none';
			$query->where[] = "sub.email IN ('".implode("','",$allEmails)."')";
		}else{
			$query->leftjoin['vmshoppergroup_'.$num] = "#__virtuemart_vmuser_shoppergroups as vmshoppergroup_$num ON sub.userid = vmshoppergroup_$num.virtuemart_user_id AND vmshoppergroup_$num.virtuemart_shoppergroup_id = ".intval($filter['group']);
			$query->where[] = "vmshoppergroup_$num.virtuemart_user_id ".(($filter['type'] == 'IN') ? '> 0' : "IS NULL");
		}
	}

	function onAcyProcessFilterCount_vmorder(&$query,$filter,$num){
		$this->onAcyProcessFilter_vmorder($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	}

	function onAcyProcessFilter_vmorder(&$query,$filter,$num){

		$datesVar = array('creationdatesup','creationdateinf');
		foreach($datesVar as $oneDate){
			if(empty($filter[$oneDate])) continue;
			$filter[$oneDate] = acymailing_replaceDate($filter[$oneDate]);
			if(!is_numeric($filter[$oneDate])) $filter[$oneDate] = strtotime($filter[$oneDate]);
			if(version_compare($this->version,'2.0.0','>=') && is_numeric($filter[$oneDate])) $filter[$oneDate] = strftime('%Y-%m-%d %H:%M:%S',$filter[$oneDate]);
		}

		if(version_compare($this->version,'2.0.0','<')){
			$orderFilter = empty($filter['status']) ? '' : " AND a.order_status = ".$query->db->Quote($filter['status']);
			$myquery = "SELECT DISTINCT b.user_email FROM #__vm_order_item as a LEFT JOIN #__vm_user_info as b on a.user_info_id = b.user_info_id WHERE b.user_id < 1".$orderFilter;
			$myqueryid = "SELECT DISTINCT b.user_id FROM #__vm_order_item as a LEFT JOIN #__vm_orders as b on a.order_id = b.order_id WHERE b.user_id > 0".$orderFilter;

			if(!empty($filter['product']) AND is_numeric($filter['product'])){
				$myquery .= " AND a.product_id = ".(int) $filter['product'];
				$myqueryid .= " AND a.product_id = ".(int) $filter['product'];
			}

			if(!empty($filter['creationdateinf'])) $myquery .= ' AND a.cdate > '.$filter['creationdateinf'];
			if(!empty($filter['creationdatesup'])) $myquery .= ' AND a.cdate < '.$filter['creationdatesup'];
			if(!empty($filter['creationdateinf'])) $myqueryid .= ' AND a.cdate > '.$filter['creationdateinf'];
			if(!empty($filter['creationdatesup'])) $myqueryid .= ' AND a.cdate < '.$filter['creationdatesup'];

			$query->db->setQuery($myquery);
			$allEmails  = acymailing_loadResultArray($query->db);
			$query->db->setQuery($myqueryid);
			$allIds  = acymailing_loadResultArray($query->db);
			if(empty($allEmails)) $allEmails[] = 'none';
			if(empty($allIds)) $allIds[] = '-1';
			if(empty($filter['type'])){
				$query->where[] = "sub.email NOT IN ('".implode("','",$allEmails)."') AND sub.userid NOT IN ('".implode("','",$allIds)."')";
			}else{
				$query->where[] = "sub.email IN ('".implode("','",$allEmails)."') OR sub.userid IN ('".implode("','",$allIds)."')";
			}
		}else{
			$join1 = "#__virtuemart_order_userinfos as vmorderuserinfos_$num ON vmorderuserinfos_$num.email = sub.email";
			$query->leftjoin['vmorderuserinfos_'.$num] = $join1;
			$lj = "#__virtuemart_order_items as vmorderitem_$num ON vmorderitem_$num.virtuemart_order_id = vmorderuserinfos_$num.virtuemart_order_id ";
			if(!empty($filter['status'])) $lj .= " AND vmorderitem_$num.order_status = ".$query->db->Quote($filter['status']);
			if(!empty($filter['product'])) $lj .= " AND vmorderitem_$num.virtuemart_product_id = ".(int) $filter['product'];
			if(!empty($filter['creationdateinf'])) $lj .= " AND vmorderitem_$num.created_on > ".$query->db->Quote($filter['creationdateinf']);
			if(!empty($filter['creationdatesup'])) $lj .= " AND vmorderitem_$num.created_on < ".$query->db->Quote($filter['creationdatesup']);
			$query->leftjoin['vmorderitem_'.$num] = $lj;
			$query->where[] = "vmorderitem_$num.virtuemart_order_id ".(empty($filter['type']) ? 'IS NULL' : "IS NOT NULL");
		}
	}

}//endclass
