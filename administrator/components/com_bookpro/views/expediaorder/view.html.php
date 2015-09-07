 '
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 62 2012-07-29 01:18:34Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed models
AImporter::model('customer','order','passengers','orders','orderinfos');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','paystatus','ordertype','orderstatus');
//import needed assets


class BookProViewExpediaOrder extends BookproJViewLegacy
{

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
		$model = new BookProModelExpediaOrder();
		$model->setId(ARequest::getCid());
		$order = &$model->getObject();

		$cModel=new BookProModelCustomer();
		$cModel->setId($order->user_id);
		$customer=$cModel->getObject();


		if ($this->getLayout() == 'form') {
			$this->_displayForm($tpl, $order);
			return;
		}
		$document->setTitle(BookProHelper::formatName($customer));
		$params = JComponentHelper::getParams(OPTION);
		/* @var $params JParameter */

		$infomodel=new BookProModelOrderinfos();
		$param=array('order_id'=>$order->id);
		$infomodel->init($param);
		$orderinfo=$infomodel->getData();

		$this->assignRef('customer', $customer);
		$this->assignRef('payment', $payment);
		$this->assignRef('order', $order);
		$this->assignRef('orderinfo', $orderinfo);

		parent::display($tpl);
		return;


	}


	function _displayForm($tpl, $order)
	{
		$document = &JFactory::getDocument();
		/* @var $document JDocument */

		$error = JRequest::getInt('error');
		$data = JRequest::get('post');
		if ($error) {
			$order->bind($data);

		}

		if (! $order->id && ! $error) {
			$order->init();
		}
		JFilterOutput::objectHTMLSafe($order);
			
		$params = JComponentHelper::getParams(OPTION);
		/* @var $params JParameter */
		$this->assignRef('obj', $order);
		$this->assignRef('params', $params);
		$this->assignRef('paystatus', $this->getPayStatusSelect($order->pay_status));
		$this->assignRef('orderstatus', $this->getOrderStatusSelect($order->order_status));
		$this->assignRef('ordertype', $this->getOrderTypeSelect($order->type));
		
		parent::display($tpl);
	}
	function getCountrySelectBox($select, $field = 'country_id', $autoSubmit = false)
	{
		AImporter::model('countries');
		$model=new BookProModelCountries();
		$fullList = $model->getItems();
		return AHtml::getFilterSelect($field,JText::_('COM_BOOKPRO_SELECT_COUNTRY') , $fullList, $select, $autoSubmit, '', 'id', 'country_name');
	}
	function getPayStatusSelect($select){
		PayStatus::init();
		return AHtml::getFilterSelect('pay_status', 'Pay status', PayStatus::$map, $select, false, '', 'value', 'value');
	}
	function getOrderTypeSelect($select){
		OrderType::init();
		return AHtml::getFilterSelect('type', 'Order Type', OrderType::$map, $select, false, '', 'value', 'value');
	}
	function getOrderStatusSelect($select){
		OrderStatus::init();
		return AHtml::getFilterSelect('order_status', 'Order Status', OrderStatus::$map, $select, false, '', 'value', 'value');
	}

	function getCustomerSelectBox($select)
	{
		AImporter::model('customers');
		$model = new BookProModelCustomers();
		$lists = array('limit' => null , 'limitstart' => null  );
		$model->init($lists);
		$fullList = $model->getData();
		return AHtml::getFilterSelect('user_id', 'Select Customer', $fullList, $select, false, 'disabled="disabled"', 'id', 'firstname');
	}
	function createTimeSelectBox($name,$selected){
		$start = "00:00";
		$end = "23:30";
		$option=array();
		$tStart = strtotime($start);
		$tEnd = strtotime($end);
		$tNow = $tStart;
		while($tNow <= $tEnd){
			$option[]=JHTML::_('select.option',date("H:i",$tNow),date("H:i",$tNow));
			$tNow = strtotime('+15 minutes',$tNow);
		}
		return JHtml::_('select.genericlist',$option,$name,'style="float:none !important;"','value','text',$selected);
	}

}

?>