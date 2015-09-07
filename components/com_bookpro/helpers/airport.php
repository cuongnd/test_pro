<?php



/**

 * @package 	Bookpro

 * @author 		Nguyen Dinh Cuong

 * @link 		http://ibookingonline.com

 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong

 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $

 **/



defined('_JEXEC') or die('Restricted access');



class AirportHelper

{

static function countAirlineByDest($dest_id){
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('count(id)');
	$query->from('#__bookpro_airportairline');
	if ($dest_id){
		$query->where('dest_id='.$dest_id);
	}else{
		return 0;
	}
	$db->setQuery($query);
	return $db->loadResult();
	
	
	
}
function Parent( &$row )
	{
		$db =JFactory::getDBO();

		// If a not a new item, lets set the menu item id
		if ( $row->id ) {
			$id = ' WHERE a.id != '.(int) $row->id;
		} else {
			$id = null;
		}

		if (!$row->parent_id) {
			$row->parent_id = 0;
		}

		$query = 'SELECT a.id as value, a.title as text,a.parent_id as parent_id' .
				' FROM #__bookpro_dest a' .
				$id .
				' ORDER BY parent_id, ordering';
		$db->setQuery( $query );
		$destitems = $db->loadObjectList();
		// establish the hierarchy of the menu
		$children = array();

		if ( $destitems )
		{
			// first pass - collect children
			foreach ( $destitems as $v )
			{
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list=AirportHelper::TreeRecurse(0, '', array(), $children, 9999, 0, 0);
		// assemble menu items to the array
		$destitems 	= array();
		$destitems[] 	= JHTML::_('select.option',  '0', JText::_( 'Top' ) );

		foreach ( $list as $item ) {
			$destitems[] = JHTML::_('select.option',  $item->value, '&nbsp;&nbsp;&nbsp;'. $item->treename );
		}

		$output = JHTML::_('select.genericlist',   $destitems, 'parent_id', 'class="inputbox" size="10"', 'value', 'text', $row->parent_id );

		return $output;
	}
    function getFullList(){
        AImporter::model('airports');
        $model = new BookProModelAirports();
        $dests = $model->getItems();
        AImporter::model('tours');
        if (!empty($dests)) {
            foreach ($dests as $dest){
                $model = new BookProModelTours();
                $lists = array('state'=>1,'dest_id'=>$dest->id);
                $model->init($lists);
                $total = $model->getTotal();
                $dest->totalTour = $total;
            }
        }
        return $dests;

    }

    public static function treerecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->value;

				if ($type)
				{
					$pre = '<sup>|_</sup>&#160;';
					$spacer = '.&#160;&#160;&#160;&#160;&#160;&#160;';
				}
				else
				{
					$pre = '- ';
					$spacer = '&#160;&#160;';
				}

				if ($v->parent_id == 0)
				{
					$txt = $v->text;
				}
				else
				{
					$txt = $pre . $v->text;
				}
				$pt = $v->parent_id;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count(@$children[$id]);
				$list = AirportHelper::TreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
			}
		}
		return $list;
	}
}



?>

