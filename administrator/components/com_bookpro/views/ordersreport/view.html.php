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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
// import needed Joomla! libraries
jimport ( 'joomla.application.component.view' );
// import needed JoomLIB helpers
AImporter::helper ( 'bookpro', 'route', 'request', 'orderstatus', 'ordertype', 'paystatus' );
// import needed models
AImporter::model ( 'customer', 'orders', 'orderinfo' );
// import custom icons
AHtml::importIcons ();
// defines constants

if (! defined ( 'SESSION_PREFIX' )) {
	if (IS_ADMIN) {
		define ( 'SESSION_PREFIX', 'bookpro_order_list_' );
	}
}
class BookProViewOrdersreport extends BookproJViewLegacy {

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
	 * @param string $tpl
	 *        	name of used template
	 */
	function display($tpl = null) {
		$mainframe = &JFactory::getApplication ();
		/* @var $mainframe JApplication */
		$document = &JFactory::getDocument ();
		/* @var $document JDocument */
		$document->setTitle ( 'Orders Management' );
		$layout = JRequest::getVar ( 'layout' );
		$tpl = JRequest::getVar ( 'tpl' );
		$this->setLayout ( $layout );
		$model = new BookProModelOrdersreport ();

		if ($layout == 'passenger') {

			$jlang = JFactory::getLanguage ();
			$jlang->load ( 'com_bookpro', JPATH_SITE, 'en-GB', true );
			$jlang->load ( 'com_bookpro', JPATH_SITE, $jlang->getDefault (), true );

			$cids = ARequest::getCids ();
			$cids = $cids ? $cids : array ();
			$this->passengers = $model->getPassenger ( $cids );
			$this->passengers = $model->listaddoneBypassenger ( $this->passengers );
			$this->passengers = $model->assign_roomselected ( $this->passengers );
			$this->passengers = $model->assign_triptransferprice ( $this->passengers );
			$this->passengers = json_decode ( json_encode ( $this->passengers ), FALSE );
			$this->tour = $model->getTourByOrderId ( $cids [0] );
			parent::display ( $tpl );
			return false;
		}

		$this->lists = array ();
		$this->lists ['limit'] = ARequest::getUserStateFromRequest ( 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
		$this->lists ['limitstart'] = ARequest::getUserStateFromRequest ( 'limitstart', 0, 'int' );
		$this->lists ['order'] = ARequest::getUserStateFromRequest ( 'filter_order', 'departdate', 'cmd' );
		$this->lists ['order_Dir'] = ARequest::getUserStateFromRequest ( 'filter_order_Dir', 'ASC', 'word' );
		$this->lists ['day'] = ARequest::getUserStateFromRequest ( 'filter_day', '', 'string' );
		$this->lists ['departdate'] = ARequest::getUserStateFromRequest ( 'filter_departdate', '', 'string' );

		$this->lists ['type'] = ARequest::getUserStateFromRequest ( 'type', '', 'string' );
		$this->lists ['order_status'] = ARequest::getUserStateFromRequest ( 'order_status', 'CONFIRMED', 'string' );
		$this->lists ['pay_status'] = ARequest::getUserStateFromRequest ( 'pay_status', 'SUCCESS', 'string' );
		$this->lists ['tour_type'] = ARequest::getUserStateFromRequest ( 'tour_type', null, 'string' );
		$this->lists ['tour_id'] = ARequest::getUserStateFromRequest ( 'tour_id', 0, 'int' );
		// $this->lists['total']= ARequest::getUserStateFromRequest('total', null , 'int');

		$model->init ( $this->lists );
		$this->items = &$model->getData ();
		// echo "<pre>";
		// print_r($this->items);
		// echo JFactory::getDbo()->replacePrefix($model->buildQuery());
		$this->pagination = &$model->getPagination ();
		AImporter::model ( 'tours' );
		$model_tours = new BookProModelTours ();
		$tours = $model_tours->getData ();
		$tours = AHtml::getFilterSelect ( 'tour_id', 'Tour', $tours, $this->lists ['tour_id'], true, '', 'id', 'title' );
		$this->assign ( 'tours', $tours );
		$this->assign ( 'orderstatus', $this->getOrderStatusSelect ( $this->lists ['order_status'] ) );
		$this->assignRef ( 'paystatus', $this->getPayStatusSelect ( $this->lists ['pay_status'] ) );
		$this->assignRef ( 'tour_type', $this->getOrdertour_type ( $this->lists ['tour_type'] ) );

		parent::display ( $tpl );
	}
	function getOrderStatusSelect($select) {
		OrderStatus::init ();
		return AHtml::getFilterSelect ( 'order_status', JText::_ ( 'Order Status' ), OrderStatus::$map, $select, true, '', 'value', 'text' );
	}
	function getOrdertour_type($select) {
		$tourtype = array (
				'nonedaytrip' => Jtext::_ ( 'COM_BOOKPRO_NONEDAYTRIP' ),
				'nonedaytripprivate' => Jtext::_ ( 'COM_BOOKPRO_NONEDAYTRIPPRIVATE' ),
				'nonedaytripshared' => Jtext::_ ( 'COM_BOOKPRO_NONEDAYTRIPSHARED' ),
				'daytrip' => Jtext::_ ( 'COM_BOOKPRO_DAYTRIP' ),
				'shared' => Jtext::_ ( 'COM_BOOKPRO_SHARED' ),
				'private' => Jtext::_ ( 'COM_BOOKPRO_PRIVATE' )
		);
		return AHtml::getFilterSelect ( 'tour_type', JText::_ ( 'Order Tour Type' ), $tourtype, $select, true, '', 'value', 'text' );
	}
	function CountPassengerByOrderId($order_id) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'COUNT(*)' );
		$query->from ( '#__bookpro_passenger AS pass' );
		$query->where ( 'pass.order_id=' . $order_id );
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	function getOrderTypeSelect($select) {
		OrderType::init ();
		return AHtml::getFilterSelect ( 'type', JText::_ ( 'Order Type' ), OrderType::$map, $select, true, '', 'value', 'text' );
	}
	function getPayStatusSelect($select) {
		PayStatus::init ();
		return AHtml::getFilterSelect ( 'pay_status', 'Pay status', PayStatus::$map, $select, true, '', 'value', 'text' );
	}
}