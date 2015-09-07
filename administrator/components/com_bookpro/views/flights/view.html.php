<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 47 2012-07-13 09:43:14Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request');
//import needed assets
//import custom icons
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_flight_list_');
	} elseif (IS_SITE) {
		define('SESSION_PREFIX', 'bookpro_site_flight_list_');
	}
}

class BookProViewFlights extends BookproJViewLegacy
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
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
                
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $document->setTitle(JText::_('Flight list'));
        
        $model = new BookProModelFlights();
        
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['desfrom'] = ARequest::getUserStateFromRequest('desfrom', '', 'string');
        $this->lists['desto'] = ARequest::getUserStateFromRequest('desto', '', 'string');
        $this->lists['airline_id'] = ARequest::getUserStateFromRequest('airline_id', '', 'string');
		
        $model->init($this->lists);
        
        $this->pagination = &$model->getPagination();
        $this->items = &$model->getData();
        $this->params = &JComponentHelper::getParams(OPTION);
        
        $this->selectable = JRequest::getCmd('task') == 'element';
        $airportFrombox=$this->getAirportSelectBox($this->lists['desfrom'],'desfrom');
        $airportTobox=$this->getAirportSelectBox($this->lists['desto'],'desto');
        $airlines=$this->getAirlineSelectBox($this->lists['airline_id']);
        $this->assignRef("airportfrom",$airportFrombox);
        $this->assignRef("airportto",$airportTobox);
        $this->assignRef("airline",$airlines);
        $this->addToolbar();
        parent::display($tpl);
        
       
    }
   function getAirportSelectBox($select, $field = 'desfrom', $autoSubmit = false)
    {
    	AImporter::model('airports');
    	$model = new BookProModelAirports();
    	
    	
    	$state=$model->getState();
    	$state->set('list.start',0);
    	$state->set('list.limit', 0);
    	$state->set('list.state', 1);
    	$state->set('list.province', 1);
    	$state->set('filter.air', 1);
    	$state->set('filter.parent_id', 1);
    	$fullList = $model->getItems();
        
       
        return AHtml::getFilterSelect($field, 'Select Airport', $fullList, $select, $autoSubmit, '', 'id', 'title');
    }
    function getAirlineSelectBox($select, $field = 'airline_id', $autoSubmit = false){
    	AImporter::model('airlines');
    	$model = new BookProModelAirlines();
    	
    	$state = $model->getState();
    	$state->set('list.limit', null);
    	$state->set('list.limitstart',null);
    	$state->set('filter.state',1);
    	$state->set('list.lft','title');
    	 
         
         $fullList = $model->getItems();
        return AHtml::getFilterSelect($field, 'Select Airline', $fullList, $select, $autoSubmit, '', 'id', 'title');
    }
    protected function addToolbar()
    {
    	JToolBarHelper::title(JText::_('Flight Manager'), 'flight');
    	JToolbarHelper::addNew('flight.add');
    	JToolbarHelper::editList('flight.edit');
    	JToolbarHelper::divider();
    	JToolbarHelper::publish('flights.publish', 'Publish', true);
    	JToolbarHelper::unpublish('flights.unpublish', 'UnPublish', true);
    	JToolbarHelper::divider();
    	JToolbarHelper::deleteList('', 'flights.delete');
    	$import 	= JPluginHelper::importPlugin( strtolower('bookpro'), 'flight_stats' );
    	if ($import){
    		
    		JToolbarHelper::addNew('flight.addstats','Add stats');
    	}
    }
}

?>