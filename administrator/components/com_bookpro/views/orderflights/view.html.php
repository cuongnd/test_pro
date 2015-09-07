<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 56 2012-07-21 07:53:28Z quannv $
 * */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'route', 'request', 'orderstatus', 'ordertype', 'paystatus');
//import needed models
AImporter::model('customer', 'orders', 'orderinfo','order');
//import custom icons
AHtml::importIcons();
//defines constants

if (!defined('SESSION_PREFIX')) {
    if (IS_ADMIN) {
        define('SESSION_PREFIX', 'bookpro_order_list_');
    }
}

class BookProViewOrderFlights extends BookproJViewLegacy {

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
    function display($tpl = null) {
        $this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

        parent::display($tpl);
    }
    function getOrderStatusSelect($select) {
        OrderStatus::init();
        return AHtml::getFilterSelect('order_status', JText::_('Order Status'), OrderStatus::$map, $select, true, '', 'value', 'text');
    }

    function getOrderTypeSelect($select) {
        OrderType::init();
        return AHtml::getFilterSelect('type', JText::_('Order Type'), OrderType::$map, $select, true, '', 'value', 'text');
    }

    function getPayStatusSelect($select) {
        PayStatus::init();
        return AHtml::getFilterSelect('pay_status', 'Pay status', PayStatus::$map, $select, true, '', 'value', 'text');
    }

}