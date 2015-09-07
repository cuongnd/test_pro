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
AImporter::model('airports');
//import needed assets
//import custom icons
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_transports_list_');
}

class BookProViewTransports extends BookproJViewLegacy
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
        
        $document->setTitle(JText::_('Transport list'));
        
        $model = new BookProModelTransports();
        
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['from'] = ARequest::getUserStateFromRequest('from', null, 'int');
        $this->lists['to'] = ARequest::getUserStateFromRequest('to', 0, 'int');
		
        $model->init($this->lists);
        
        $this->pagination = &$model->getPagination();
        $this->items = &$model->getData();
        $this->params = &JComponentHelper::getParams(OPTION);
        
        $this->selectable = JRequest::getCmd('task') == 'element';
        $airportFrombox=$this->getDestinationSelectBox($this->lists['from'],'from');
        $airportTobox=$this->getDestinationSelectBox($this->lists['to'],'to');
        $this->assignRef("dfrom",$airportFrombox);
        $this->assignRef("dto",$airportTobox);
        parent::display($tpl);
       
    }
  function getDestinationSelectBox($select, $field = 'from')
    {
        $model = BookProHelper::getAirportModel();
        $lists = array('limit' => null , 'limitstart' => null , 'state' => null , 'access' => null , 'order' => 'ordering' , 'order_Dir' => 'ASC' , 'search' => null , 'parent' => null , 'template' => null);
        $model->init($lists);
        $fullList = $model->getFullList();
        return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_DESTINATION'), $fullList, $select, false, '', 'value', 'text');
    }
  }

?>