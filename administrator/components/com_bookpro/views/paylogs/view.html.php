<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
AImporter::model( 'paylogs','order', 'customer');
//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request', 'currency');
AHtml::importIcons();
if (!defined('SESSION_PREFIX')) {
    define('SESSION_PREFIX', 'bookpro_paylog_list_');
}

class BookProViewPayLogs extends BookproJViewLegacy {

    var $lists;
    var $items;
    var $pagination;
    var $selectable;
    var $params;

    function display($tpl = null) {
        $mainframe = &JFactory::getApplication();

        $model = new BookProModelPayLogs();
        $this->lists = array();

        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['title'] = ARequest::getUserStateFromRequest('title', '', 'string');
        //$this->lists['country_id'] = ARequest::getUserStateFromRequest('country_id', null, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'lft', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'ASC', 'word');

        $model->init($this->lists);
        $this->pagination = &$model->getPagination();
        $this->items = &$model->getData();
        $this->selectable = JRequest::getCmd('task') == 'element';
        $this->turnOnOrdering = ($this->lists['order'] == 'ordering');


//        $modelPaylogs = new BookProModelPayLogs();
//        $datas = $modelPaylogs->getData();
//        $obj = &$modelPaylogs->getObject();
        
        
//        $order_id = ARequest::getUserStateFromRequest('order_id', '', 'int');
//        if ($order_id) {
//            $modelOrder = new BookProModelOrder();
//            $modelOrder->setId($order_id);
//            $this->order = $modelOrder->getObject();
//            //var_dump($this->order);
//        }

        $this->order_id = JFactory::getApplication()->getUserStateFromRequest('order_id', 'order_id', 0);
        $model = new BookProModelOrder();
        $model->setId($this->order_id);
        $this->order = $model->getObject();
//        var_dump($this->order);
        parent::display($tpl);
    }

}

?>