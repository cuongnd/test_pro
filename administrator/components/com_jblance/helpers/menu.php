<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	helpers/menu.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Helper class for generating menus (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class menuHelper {

	public function getJBMenuItems(){
		
		$db	=   JFactory::getDBO();
		$menus	=   array();

		// For menu access
		$user		= JFactory::getUser();
				
		$query	= 'SELECT a.id, a.link, a.title as name, a.parent_id, a.type, false as script FROM #__menu AS a '.
				  'LEFT JOIN #__menu AS b ON b.id=a.parent_id AND b.published=1 '.
				  'WHERE a.published=1 '.
				  'AND a.menutype='.$db->quote('joombri');

		if($user->id == 0){
			$query	.= ' AND a.access=0 ';
		}
		
		$ordering_field = 'lft';
		$query	.= ' ORDER BY a.'.$db->quoteName($ordering_field);
				
		$db->setQuery($query);//echo $query;
		
		$result	= $db->loadObjectList();
		
		//avoid multiple count execution
		$parentColumn	= 'parent_id';
		$menus			= array();
		
		foreach($result as $i => $row){
			//get top main links on toolbar

			//add Itemid if not our components
			//if(strpos($row->link, 'com_jblance') == false){
				$row->link .="&Itemid=".$row->id;
			//}
                        
			if($row->$parentColumn == 1){
				$obj				= new stdClass();
				$obj->item			= $row;
				$obj->item->script	= false;
				$obj->childs		= null;

				$menus[$row->id]	= $obj;
			}
		}
		
		// Retrieve child menus from the original result.
		// Since we reduce the number of sql queries, we need to use php to split the menu's out
		// accordingly.
		foreach($result as $i => $row){
			if($row->$parentColumn != 1 && isset($menus[$row->$parentColumn])){
				if(!is_array($menus[$row->$parentColumn]->childs)){
					$menus[$row->$parentColumn]->childs = array();
				}
				$menus[$row->$parentColumn]->childs[] = $row;
			}
		}
		return $menus;
	}
	
	function processJBMenuItems($menus){
		$user = JFactory::getUser();
		foreach($menus as $keyi=>$menu){
			if(!empty($menu->childs)){
				$count = count($menu->childs);
				$flag = 0;
				foreach($menu->childs as $keyj=>$child){
					$uri = JFactory::getURI($child->link);
					$layout = $uri->getVar('layout');
					$denied = JblanceHelper::deniedLayouts($user->id);
					if(in_array($layout, $denied)){
						unset($menu->childs[$keyj]);
						$flag++;
					}
				}
				//remove the parent menu item if all the subitems are denied. This is helpful in case of "Free Mode" condition
				if($count == $flag){
					unset($menus[$keyi]);
				}
			}
		}
		return $menus;
	}
	
	
	
	
 	function getActiveLink(){
		$url		= 'index.php?';
		$segments	=& $_GET;
		$option = $view = $layout = '';
		$q = array();
		
		if(isset($_GET['option']))
			$q[] = 'option='.$_GET['option'];
		
		if(isset($_GET['view']))
			$q[] = 'view='.$_GET['view'];
		
		if(isset($_GET['layout']))
			$q[] = 'layout='.$_GET['layout'];
		
		$query = implode($q, '&');
		
		/* $final = 'index.php?option='.$_GET['option'].'&view='.$_GET['view'].'&layout='.$_GET['layout']; echo $final;
		$i			= 1;
		foreach($segments as $key => $value){	
			// Do not check against Itemid, format and userid as they may be different.
			if( $key == 'option' || $key == 'view' || $key == 'layout'){
				$url	.= $i > 1 ? '&' : '';				
				$url	.= $key . '=' . $value;
				$i++;
			}					
		} */
		$url = 'index.php?'.$query;
		
		return $url;
	}
	
 	function getActiveId( $link ){
		$db		= JFactory::getDBO();
		
		$query	= 'SELECT `id`,parent_id FROM #__menu WHERE menutype ='.$db->Quote('joombri').' '.
				  'AND published=1 AND link LIKE '.$db->Quote('%'.$link.'%');
		$db->setQuery( $query );//echo $query;
		$result	= $db->loadObject();
		
		if( !$result ){
			return 0;
		}
		$parent_id = 'parent_id';
		
		return ($result->parent_id == 0 || (!false && $result->parent_id == 1) ) ? $result->id : $result->parent_id;
		//return $result->parent_id == 0 ? $result->id : $result->parent_id;
	}

}

?>