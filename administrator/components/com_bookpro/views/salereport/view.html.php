<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 56 2012-07-21 07:53:28Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 //import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'route','request','date');
//import needed models
AImporter::model('customer','orders');
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_sales_list_');
}

class BookProViewSalereport extends BookproJViewLegacy
{
    /**
     * Array containig browse table reservations items to display.
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
        $document->setTitle('Orders Management');
        $model = new BookProModelOrders();
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['from']= ARequest::getUserStateFromRequest('filter_from', '', 'string');
        $this->lists['to']= ARequest::getUserStateFromRequest('filter_to', '', 'string');        $this->lists['tour_id']= ARequest::getUserStateFromRequest('tour_id', '', array());        
        $model->init($this->lists);
        $this->items = &$model->getData();
        $this->pagination = &$model->getPagination();
        $this->setLayout(JRequest::getCmd('layout','default'));
        
        $document=JFactory::getDocument();
        $document->addScript('https://www.google.com/jsapi');
        
        parent::display($tpl);
    }
   
}