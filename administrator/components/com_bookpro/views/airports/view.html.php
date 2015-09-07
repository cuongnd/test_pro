<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
AImporter::model('countries');
require_once JPATH_COMPONENT.'/helpers/airport.php';
//import needed JoomLIB helpers
AImporter::helper('route','bookpro', 'request','image');
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_airport_list_');
}

class BookProViewAirports extends BookproJViewLegacy
{
    /**
     * Array containing browse table filters properties.
     * 
     * @var array
     */
    var $lists;
    
    /**
     * Array containig browse table subjects items to display.
     *  
     * @var array
     */
    var $items;
    
    /**
     * Standard Joomla! browse tables pagination object.
     * 
     * @var JPagination
     */
    var $pagination;
    
       
    /**
     * Sign if table is used to popup selecting customers.
     * 
     * @var boolean
     */
    var $selectable;
    
    /**
     * Standard Joomla! object to working with component parameters.
     * 
     * @var $params JParameter
     */
    var $params;

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     */
    function display($tpl = null)
    {
       $this->state		= $this->get('State');
        $this->state->set('airport_filter_bus','');
		$this->items		= $this->get('Items');
		//echo $this->get('DBO')->getQuery()->dump();
		$this->pagination	= $this->get('Pagination');
		foreach ($this->items as &$item)
		{
			$this->ordering[$item->parent_id][] = $item->id;
		}
        $this->addToolbar();
        
        parent::display($tpl);
    }
    protected function addToolbar()
    {
    	JToolBarHelper::title(JText::_('Destination Manager'), 'airport');
    	JToolbarHelper::addNew('airport.add');
    	JToolbarHelper::editList('airport.edit');
    	JToolbarHelper::divider();
    	JToolbarHelper::publish('airports.publish', 'Publish', true);
    	JToolbarHelper::unpublish('airports.unpublish', 'UnPublish', true);
    	JToolbarHelper::divider();
    	JToolbarHelper::deleteList('', 'airports.delete');
    }
}
?>