<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	models/admconfig.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 jimport('joomla.application.component.model');
 
class JblanceModelAdmconfig extends JModelLegacy {
	function __construct(){
		parent :: __construct();
		//$user	=& JFactory::getUser();
	}
	
	function getConfig(){
	
		$row =& JTable::getInstance('config', 'Table');
		$row->load(1);
	
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($row->params);
		$params = $registry->toObject();
	
		$return[0] = $row;
		$return[1] = $params;
		return $return;
	}
	
	public function getShowUserGroup(){
	
		// Initialize variables
		$app =& JFactory::getApplication();
		$db	 =& JFactory::getDBO();
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		// Get the total number of records for pagination
		$query	= 'SELECT COUNT(*) FROM #__jblance_usergroup';
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query	= "SELECT ug.*, (SELECT COUNT(*) FROM #__jblance_user u WHERE u.ug_id=ug.id) usercount FROM #__jblance_usergroup ug ".
				  "ORDER BY ordering";
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows	= $db->loadObjectList();
	
		$return[0] = $rows;
		$return[1] = $pageNav;
		return $return;
	}
	
	//7.Salary Type - edit
	function getEditUserGroup(){
		$app  	=& JFactory::getApplication();
		$db		=& JFactory::getDBO();
		$row 	=& JTable::getInstance('usergroup', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));

		$isNew = (empty($cid))? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		$fields = $this->getFields();
	
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($row->params);
		$params = $registry->toArray();
	
		$return[0] = $row;
		$return[1] = $fields;
		$return[2] = $params;
	
		return $return;
	}
	
	//2.Membership Plans - show
	function getShowPlan(){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		$ug_id	 	= $app->getUserStateFromRequest('com_jblance_filter_plan_ug_id', 'ug_id', '', 'int');
		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
		$lists['ug_id'] = $select->getSelectUserGroups('ug_id', $ug_id, 'COM_JBLANCE_SELECT_USERGROUP', '', 'onchange="document.adminForm.submit();"');
	
		$where = array();
		if($ug_id != '') 	 $where[] = 'p.ug_id ='.$db->quote($ug_id);
		$where = (count($where) ? ' WHERE ('.implode( ') AND (', $where ) . ')' : '');
	
		$query = "SELECT COUNT(*) FROM #__jblance_plan";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query = "	SELECT p.*, COUNT(s.id) as subscr, ug.name groupName FROM #__jblance_plan p
					LEFT JOIN #__jblance_plan_subscr AS s ON s.plan_id = p.id
					LEFT JOIN `#__jblance_usergroup` AS ug ON p.ug_id = ug.id
					$where
					GROUP BY p.id
					ORDER BY p.ordering ASC";
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();
	
		$return[0] = $rows;
		$return[1] = $pageNav;
		$return[2] = $lists;
		return $return;
	}
	
	//2.Membership Plans - edit
	function getEditPlan(){
		$app  	= JFactory::getApplication();
		$row 	=& JTable::getInstance('plan', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid))? true : false;
		if(!$isNew)
			$row->load($cid[0]);
		
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($row->params);
		$params = $registry->toArray();
	
		$return[0] = $row;
		$return[1] = $params;
	
		return $return;
	}
	
	//7a.Pay Modes - show
	function getShowPaymode(){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
		$post   = JRequest::get('post');
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		$query = "SELECT COUNT(*) FROM #__jblance_paymode";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query = "SELECT * FROM #__jblance_paymode ".
				 "ORDER BY ordering";
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();
	
		$return[0] = $rows;
		$return[1] = $pageNav;
	
		return $return;
	}
	
	//7a.Pay Modes - edit
	function getEditPaymode(){
		$app  	=& JFactory::getApplication();
		$db		=& JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
	
		$paymode =& JTable::getInstance('paymode', 'Table');
		$paymode->load($cid[0]);
		
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($paymode->params);
		$params = $registry->toObject();
		
		$gwcode = $paymode->gwcode;
		// get the JForm object
		jimport('joomla.form.form');
		$pathToGatewayXML = JPATH_COMPONENT_SITE."/gateways/forms/$gwcode.xml";
		if(file_exists($pathToGatewayXML)){
			$form =& JForm::getInstance($gwcode, $pathToGatewayXML, array('control' => 'params', 'load_data' => true));
			$form->bind($params);
		}
		else
			$form = null;
	
		$return[0] = $paymode;
		$return[1] = $params;
		$return[2] = $form;
		return $return;
	}
	
	//7.Custom Field - show
	function getShowCustomField(){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
		$post   = JRequest::get( 'post' );
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
		$filter_field_type = $app->getUserStateFromRequest('com_jblance.filter_cust_field_type', 'filter_field_type', 'profile', 'string');
	
		$where = '';
		if(!empty($filter_field_type))
			$where = " WHERE field_for = ".$db->quote($filter_field_type);
	
		$lists['field_type'] = $this->getSelectFieldtype('filter_field_type', $filter_field_type, 0, 'onchange="document.adminForm.submit();"');
	
		$query = "SELECT COUNT(*) FROM #__jblance_custom_field $where";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
	
		$query = "SELECT * FROM #__jblance_custom_field
		$where
		ORDER BY ordering";
		$db->setQuery($query/*, $pageNav->limitstart, $pageNav->limit*/);
		$rows = $db->loadObjectList();
	
		$parents = $children = array();
		foreach($rows as $ct){
			if($ct->parent == 0)
				$parents[] = $ct;
			else
				$children[] = $ct;
		}
		$ordered = '';
		
		if(count($parents)){
			foreach($parents as $pt){
				$ordered[] = $pt;
				foreach($children as $ct){
					if($ct->parent == $pt->id){
						$ordered[]= $ct;
					}
				}
			}
			$rows = $ordered;
		}
		
		$return[0] = $rows;
		$return[1] = $pageNav;
		$return[2] = $lists;
		$return[3] = $filter_field_type;
		
		return $return;
	}
	
	//7.Custom Field - edit
	function getEditCustomField(){
		$app 	=& JFactory::getApplication();
		$db		=& JFactory::getDBO();
		$row 	=& JTable::getInstance('custom', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid)) ? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		$filter_field_type = $app->getUserStateFromRequest('com_jblance.filter_cust_field_type', 'field_for', 'profile', 'string');
		$lists['field_type'] = $this->getSelectFieldtype('field_for', $filter_field_type, 'profile', 'onchange="document.adminForm.submit();"');
		if($filter_field_type)
			$where = " field_for = ".$db->quote($filter_field_type);
	
		//make selection custom group
		$query = 'SELECT id AS value, field_title AS text FROM #__jblance_custom_field WHERE parent=0 AND'. $where.' ORDER BY ordering';
		$db->setQuery($query);
		$users = $db->loadObjectList();
	
		$types = array();
		foreach($users as $item){
			$types[] = JHTML::_('select.option', $item->value, JText::_($item->text));
		}
		$groups = JHTML::_('select.genericlist', $types, 'parent', 'class="inputbox required" size="8"', 'value', 'text', $row->parent);
	
		$return[0] = $row;
		$return[1] = $groups;
		$return[2] = $lists;
		return $return;
	}
	
	//Email Templates
	function getEmailTemplate(){
		$app  	 =& JFactory::getApplication();
		$db 	 = & JFactory :: getDBO();
		$tempFor = $app->input->get('tempfor', 'subscr-pending', 'string');
	
		$query = "SELECT * FROM #__jblance_emailtemplate WHERE templatefor = ".$db->Quote($tempFor);
		$db->setQuery($query);
		$template = $db->loadObject();
	
		return $template;
	}
	
	//13.Category - show
	function getShowCategory(){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
		$post   = JRequest::get('post');
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		$query = "SELECT COUNT(*) FROM #__jblance_category a";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query = 'SELECT * FROM #__jblance_category WHERE parent=0 ORDER BY ordering';
		$db->setQuery($query);
		$categs = $db->loadObjectList();
	
		// subcategories view as tree
		$tree = array();
	
		foreach($categs as $v) {
			$indent = '';
			$tree[] = $v;
			$tree = $select->getSubcategories($v->id, $indent, $tree, 1);
		}
		$rows = array_slice($tree, $pageNav->limitstart, $pageNav->limit);
	
		$return[0] = $rows;
		$return[1] = $pageNav;
		return $return;
	}
	
	//13.Category - edit
	function getEditCategory(){
		$app  	=& JFactory::getApplication();
		$db		=& JFactory::getDBO();
		$row 	=& JTable::getInstance('category', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid)) ? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		return $row;
	}
	
	function getShowBudget(){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
		$post   = JRequest::get('post');
		
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
		
		$query = "SELECT COUNT(*) FROM #__jblance_budget b";
		$db->setQuery($query);
		$total = $db->loadResult();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		
		$query = 'SELECT * FROM #__jblance_budget ORDER BY ordering';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$return[0] = $rows;
		$return[1] = $pageNav;
		return $return;
	}
	
	function getEditBudget(){
		$app  	=& JFactory::getApplication();
		$db		=& JFactory::getDBO();
		$row 	=& JTable::getInstance('budget', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid)) ? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		return $row;
	}
	
	/* Misc Functions */
	
	public function &getFields(){
		// Initialize variables
		$app	=& JFactory::getApplication();
		$db		=& JFactory::getDBO();
	
		$query	= "SELECT * FROM #__jblance_custom_field ".
				  "WHERE field_for=".$db->quote('profile')." ".
				  "ORDER BY ordering";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
	
		$parents = $children = array();
		foreach($rows as $ct){
			if($ct->parent == 0)
				$parents[] = $ct;
			else
				$children[] = $ct;
		}
		$ordered = '';
	
		if(count($parents)){
			foreach($parents as $pt){
				$ordered[] = $pt;
				foreach($children as $ct){
					if($ct->parent == $pt->id){
						$ordered[]= $ct;
					}
				}
			}
			$rows = $ordered;
		}
	
		return $rows;
	}
	
	//7.getSelectDuration
	function getSelectDuration($var, $default, $disabled, $event){
		$option = '';
		if($disabled == 1)
			$option = 'disabled';
	
		$types[] = JHTML::_('select.option', 'days', JText::_('COM_JBLANCE_DAYS'));
		$types[] = JHTML::_('select.option', 'weeks', JText::_('COM_JBLANCE_WEEKS'));
		$types[] = JHTML::_('select.option', 'months', JText::_('COM_JBLANCE_MONTHS'));
		$types[] = JHTML::_('select.option', 'years', JText::_('COM_JBLANCE_YEARS'));
	
		$lists = JHTML::_('select.genericlist', $types, $var, "class=\"inputbox\" size=\"1\" $option $event", 'value', 'text', $default);
		return $lists;
	}
	
	//20.getSelectFieldtype
	function getSelectFieldtype($var, $default, $disabled, $event){
		$option = '';
		if($disabled == 1)
			$option = 'disabled';
	
		$types[] = JHTML::_('select.option', 'profile', JText::_('COM_JBLANCE_PROFILE'));
		$types[] = JHTML::_('select.option', 'project', JText::_('COM_JBLANCE_PROJECT'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, "class='inputbox' size='1' $option $event", 'value', 'text', $default);
		return $lists;
	}
	
	function getSelectTheme($var, $default){
		$types[] = JHTML::_('select.option', 'styleGR.css', JText::_('COM_JBLANCE_GREY'));
		/* $types[] = JHTML::_('select.option', 'styleFB.css', JText::_('COM_JBLANCE_FACEBOOK_BLUE'));
		$types[] = JHTML::_('select.option', 'styleJS.css', JText::_('COM_JBLANCE_JOMSOCIAL_GREEN'));
		$types[] = JHTML::_('select.option', 'styleBO.css', JText::_('COM_JBLANCE_BLACK_ORANGE'));
		$types[] = JHTML::_('select.option', 'styleOR.css', JText::_('COM_JBLANCE_ORANGE'));
		$types[] = JHTML::_('select.option', 'styleCS1.css', JText::_('COM_JBLANCE_CUSTOM1'));
		$types[] = JHTML::_('select.option', 'styleCS2.css', JText::_('COM_JBLANCE_CUSTOM2')); */
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1"', 'value', 'text', $default);
	
		return $lists;
	}
	
	function getselectDateFormat($var, $default){
		$types[] = JHTML::_('select.option', 'd-m-Y', JText::_('dd-mm-yyyy'));
		$types[] = JHTML::_('select.option', 'm-d-Y', JText::_('mm-dd-yyyy'));
		$types[] = JHTML::_('select.option', 'Y-m-d', JText::_('yyyy-mm-dd'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1"', 'value', 'text', $default);
	
		return $lists;
	}
	
}