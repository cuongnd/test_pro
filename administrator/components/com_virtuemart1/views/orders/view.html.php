<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Load the view framework
if(!class_exists('VmView')) require(JPATH_VM_ADMINISTRATOR.'/helpers/vmview.php');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewOrders extends VmView {

	function display($tpl = null) {

		//Load helpers

		$this->loadHelper('currencydisplay');

		$this->loadHelper('html');

		if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.DS.'vmpsplugin.php');

		$orderStatusModel=VmModel::getModel('orderstatus');
		$orderStates = $orderStatusModel->getOrderStatusList();

		$this->SetViewTitle( 'ORDER');

		$model = VmModel::getModel();

		$curTask = JRequest::getWord('task');
		if ($curTask == 'edit') {
			VmConfig::loadJLang('com_virtuemart_shoppers',TRUE); 

			// Load addl models
			$userFieldsModel = VmModel::getModel('userfields');
			$productModel = VmModel::getModel('product');


			// Get the data
			$virtuemart_order_id = JRequest::getInt('virtuemart_order_id');
			$order = $model->getOrder($virtuemart_order_id);

			$this->orderID = $order['details']['BT']->virtuemart_order_id;
			$orderbt = $order['details']['BT'];
			$orderst = (array_key_exists('ST', $order['details'])) ? $order['details']['ST'] : $orderbt;
			$orderbt ->invoiceNumber = $model->getInvoiceNumber($orderbt->virtuemart_order_id);
			$this->currency = CurrencyDisplay::getInstance('',$order['details']['BT']->virtuemart_vendor_id);

			$_userFields = $userFieldsModel->getUserFields(
					 'account'
					, array('captcha' => true, 'delimiters' => true) // Ignore these types
					, array('delimiter_userinfo','user_is_vendor' ,'username','password', 'password2', 'agreed', 'address_type') // Skips
			);

			$this->userfields = $userFieldsModel->getUserFieldsFilled(
					 $_userFields
					,$orderbt
					,'BT_'
			);

			$_userFields = $userFieldsModel->getUserFields(
					 'shipment'
					, array() // Default switches
					, array('delimiter_userinfo', 'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
			);

			$this->shipmentfields = $userFieldsModel->getUserFieldsFilled(
					 $_userFields
					,$orderst
					,'ST_'
			);

			// Create an array to allow orderlinestatuses to be translated
			// We'll probably want to put this somewhere in ShopFunctions...
			$_orderStatusList = array();
			foreach ($orderStates as $orderState) {
				//$_orderStatusList[$orderState->virtuemart_orderstate_id] = $orderState->order_status_name;
				//When I use update, I have to use this?
				$_orderStatusList[$orderState->order_status_code] = JText::_($orderState->order_status_name);
			}

			$_itemStatusUpdateFields = array();
			$_itemAttributesUpdateFields = array();
			foreach($order['items'] as $_item) {
				$_itemStatusUpdateFields[$_item->virtuemart_order_item_id] = JHTML::_('select.genericlist', $orderStates, "item_id[".$_item->virtuemart_order_item_id."][order_status]", 'class="selectItemStatusCode input-medium"', 'order_status_code', 'order_status_name', $_item->order_status, 'order_item_status'.$_item->virtuemart_order_item_id,true);

			}

			if(!isset($_orderStatusList[$orderbt->order_status])){
				if(empty($orderbt->order_status)){
					$orderbt->order_status = 'unknown';
				}
				$_orderStatusList[$orderbt->order_status] = JText::_('COM_VIRTUEMART_UNKNOWN_ORDER_STATUS');
			}

			/* Assign the data */
			$this->orderdetails = $order;
			$this->orderstatuslist = $_orderStatusList;
			$this->itemstatusupdatefields = $_itemStatusUpdateFields;
			$this->itemattributesupdatefields = $_itemAttributesUpdateFields;
			$this->orderbt = $orderbt;
			$this->orderst = $orderst;
			$this->virtuemart_shipmentmethod_id = $orderbt->virtuemart_shipmentmethod_id;

			/* Data for the Edit Status form popup */
			$this->currentOrderStat = $order['details']['BT']->order_status;
			// used to update all item status in one time
			$this->orderStatusSelect = JHTML::_('select.genericlist', $orderStates, 'order_status', '', 'order_status_code', 'order_status_name', $this->currentOrderStat, 'order_items_status',true);

			/* Toolbar */
			JToolBarHelper::custom( 'prevItem', 'backward','','COM_VIRTUEMART_ITEM_PREVIOUS',false);
			JToolBarHelper::custom( 'nextItem', 'forward','','COM_VIRTUEMART_ITEM_NEXT',false);
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'cancel', 'cancel','back','COM_VIRTUEMART_CLOSE',false,false);
		}
		else if ($curTask == 'editOrderItem') {
			$this->loadHelper('calculationh');

			$this->orderstatuses = $orderStates;
			$this->virtuemart_order_id = JRequest::getString('orderId', '');
			$this->virtuemart_order_item_id = JRequest::getVar('orderLineId', '');

			$this->orderItem = $model->getOrderLineDetails($this->virtuemart_order_id, $this->virtuemart_order_item_id);

		}
		else {
			$this->addStandardDefaultViewLists($model,'created_on');
			$orderStatusModel =VmModel::getModel('orderstatus');
			$orderstates = JRequest::getWord('order_status_code','');
			$this->lists['state_list'] = $orderStatusModel->renderOSList($orderstates,'order_status_code',FALSE,' onchange="this.form.submit();" ');
			$orderslist = $model->getOrdersList();

			$this->orderstatuses = $orderStates;
			foreach ($orderStates as $orderState) {
				//$_orderStatusList[$orderState->virtuemart_orderstate_id] = $orderState->order_status_name;
				//When I use update, I have to use this?
				$_orderStatusList[$orderState->order_status_code] = JText::_($orderState->order_status_name);
			}
			$this->orderStatusList = $_orderStatusList ;
			$this->loadHelper('currencydisplay');
			$vendor = Permissions::getInstance()->isSuperVendor();
			if ( $vendor > 1 ) $this->setLayout('vendor');
			/* Apply currency This must be done per order since it's vendor specific */
			$_currencies = array(); // Save the currency data during this loop for performance reasons

			if ($orderslist) {

			    foreach ($orderslist as $virtuemart_order_id => $order) {

				    if(!empty($order->order_currency)){
					    $currency = $order->order_currency;
				    } else if($order->virtuemart_vendor_id){
					    if(!class_exists('VirtueMartModelVendor')) require(JPATH_VM_ADMINISTRATOR.'/models'.DS.'vendor.php');
					    $currObj = VirtueMartModelVendor::getVendorCurrency($order->virtuemart_vendor_id);
				        $currency = $currObj->virtuemart_currency_id;
				   }
				    //This is really interesting for multi-X, but I avoid to support it now already, lets stay it in the code
				    if (!array_key_exists('curr'.$currency, $_currencies)) {

					    $_currencies['curr'.$currency] = CurrencyDisplay::getInstance($currency,$order->virtuemart_vendor_id);
				    }

				    $order->order_total = $_currencies['curr'.$currency]->priceDisplay($order->order_total);
				    $order->invoiceNumber = $model->getInvoiceNumber($order->virtuemart_order_id);
			    }

			}

			/*
			 * UpdateStatus removed from the toolbar; don't understand how this was intented to work but
			 * the order ID's aren't properly passed. Might be readded later; the controller needs to handle
			 * the arguments.
			 */

			 /* Toolbar */
			JToolBarHelper::save('updatestatus', JText::_('COM_VIRTUEMART_UPDATE_STATUS'));

			JToolBarHelper::deleteList();

			/* Assign the data */
			$this->orderslist = $orderslist ;

			$this->pagination = $model->getPagination();

		}

		shopFunctions::checkSafePath();

		parent::display($tpl);
	}

}

