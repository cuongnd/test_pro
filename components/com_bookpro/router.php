<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: router.php 53 2012-07-17 14:42:54Z quannv $
 **/

defined('_JEXEC') or die;

jimport('joomla.application.categories');

function BookproBuildRoute(&$query)
{
		$segments = array();
		if($query['controller']=='tour' && $query['view']=='tour'){
				unset($query['controller']);
				$segments[] = $query['view'];
				unset($query['view']);
				if (isset($query['id'])) {
				$db = JFactory::getDBO();
				$sql ="SELECT * ".
						" FROM #__bookpro_tour".
						" WHERE id =" . $query['id'];
				$db->setQuery($sql);
				$tour = $db->loadObject();
				$title=JFilterOutput::stringURLSafe($tour->title).'.html';
				$segments[]=$query['id'].':'.$title;
				unset($query['id']);
			}
		}
		return $segments;
}


function BookproParseRoute($segments)
{
		$vars = array();
		if(count($segments)==2) {
			$vars['controller']='tour';
			$vars['view']=$segments[0];
			$ids=explode(':',$segments[1]);
			$vars['id']=(int)$ids[0];
		} 
		return $vars;
		
}

