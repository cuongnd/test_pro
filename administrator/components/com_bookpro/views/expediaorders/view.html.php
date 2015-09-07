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
AImporter::helper('bookpro', 'route','request','orderstatus','ordertype','paystatus');
//import needed models
AImporter::model('customer', 'orders','orderinfo');
//import custom icons
AHtml::importIcons();
//defines constants

if (! defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_order_list_');
	}
}
class BookProViewExpediaOrders extends BookproJViewLegacy
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
        $model = new BookProModelExpediaOrders();
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['from']= ARequest::getUserStateFromRequest('filter_from', '', 'string');
        $this->lists['to']= ARequest::getUserStateFromRequest('filter_to', '', 'string');
        $this->lists['type']= ARequest::getUserStateFromRequest('type', '', 'string');
        $this->lists['order_status']= ARequest::getUserStateFromRequest('order_status', null , 'string');
        $this->lists['pay_status']= ARequest::getUserStateFromRequest('order_status', null , 'string');
        //$this->lists['total']= ARequest::getUserStateFromRequest('total', null , 'int');
        $model->init($this->lists);
        $this->items = &$model->getData();
        $this->pagination = &$model->getPagination();
        $this->assign('orderstatus',$this->getOrderStatusSelect($this->lists['order_status']));
        $this->assignRef('paystatus', $this->getPayStatusSelect($this->lists['pay_status']));
        parent::display($tpl);
    }
	function getOrderStatusSelect($select){
		OrderStatus::init();
		return AHtml::getFilterSelect('order_status', JText::_('Order Status'), OrderStatus::$map, $select, true, '', 'value', 'text');
	}
	function getOrderTypeSelect($select){
		OrderType::init();
		return AHtml::getFilterSelect('type', JText::_('Order Type'), OrderType::$map, $select, true, '', 'value', 'text');
	}
	function getPayStatusSelect($select){
		PayStatus::init();
		return AHtml::getFilterSelect('pay_status', 'Pay status', PayStatus::$map, $select, true, '', 'value', 'value');
	}
}