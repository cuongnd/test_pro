<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 66 2012-07-31 23:46:01Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerExpediaOrder extends AController
{


	var $_model;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('expediaorder');
		$this->_controllerName = CONTROLLER_EXPEDIAORDER;
	}

	/**
	 * Display default view - Airport list
	 */
	function display()
	{

		
		switch ($this->getTask()) {
			case 'publish':
			case 'sendemail':
				$this->sendemail();
				break;
			case 'unpublish':
			case 'bookings':
				JRequest::setVar('view', 'bookings');
				break;
			case 'detail':
				JRequest::setVar('view', 'expediaorder');
				break;
			case 'trash':
				$this->state($this->getTask());
				break;
			default:
				JRequest::setVar('view', 'expediaorders');

		}

		parent::display();
	}

	/**
	 * Open editing form page
	 */
	function editing()
	{
		parent::editing('order');
	}

	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel()
	{
		parent::cancel('Subject editing canceled');
	}

	/**
	 * Save items ordering
	 */
	function saveorder()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$cids = ARequest::getCids();
		$order = ARequest::getIntArray('order');
		if (ARequest::controlCids($cids, 'save order')) {
			$mainframe = &JFactory::getApplication();
			if ($this->_model->saveorder($cids, $order)) {
				$mainframe->enqueueMessage(JText::_('Successfully saved order'), 'message');
			} else {
				$mainframe->enqueueMessage(JText::_('Order save failed'), 'error');
			}
		}
		ARequest::redirectList(CONTROLLER_ORDER);
	}
	function batchupdate(){

		JRequest::checkToken() or jexit('Invalid Token');
		$mainframe = &JFactory::getApplication();
		$order_id = JRequest::getInt('order_id');
			
		if (! class_exists('BookProModelOrderInfo')) {
			AImporter::model('orderinfo');
		}
		$modelInfo= new BookProModelOrderInfo();
		
		$depart=JRequest::getVar('depart',array());
		$orderinfo_id=JRequest::getVar('info_id',array());
		$obj_id=JRequest::getVar('obj_id',array());
		$start=JRequest::getVar('start',array());
		$adult=JRequest::getVar('adult',array());
		$children=JRequest::getVar('child',array());
		$package=JRequest::getVar('package',array());
		$location=JRequest::getVar('location',array());
		$purpose=JRequest::getVar('purpose',array());
		$qty=JRequest::getVar('qty',array());
		for ($i = 0; $i < count($orderinfo_id); $i++) {
			$tdate=$start[$i];
			if(count($depart)>0){
				$tdate = JFactory::getDate($start[$i].' '.$depart[$i])->toSql();
			}else {
				$tdate = $tdate= JFactory::getDate($start[$i])->toSql();
			}
			$data=array('id'=>$orderinfo_id[$i],
					'obj_id'=>$obj_id[$i],
					'adult'=>$adult[$i],
					'child'=>$children[$i],
					'package'=>$package[$i],
					'location'=>$location[$i],
					'purpose'=>$purpose[$i],
					'start'=>$tdate);
			$modelInfo->store($data);
		}
		$this->updateOrder($order_id);
		$this->setRedirect(JURI::base().'index.php?option=com_bookpro&controller=order&task=detail&cid[]='.$order_id);

	}
	private function updateOrder($order_id){
		$order=JTable::getInstance('orders','table');
		$order->load($order_id);
		if (! class_exists('BookProModelOrderInfos')) {
			AImporter::model('orderinfos');
		}
		$modelInfo= new BookProModelOrderinfos();
		$lists=array('order_id'=>$order_id);
		$modelInfo->init($lists);
		$datas=$modelInfo->getData();

		$total=0;
		switch ($order->type) {
			case 'TOUR':

				if (! class_exists('BookProModelTourPackagece')) {
					AImporter::model('tourpackage');
				}
				foreach ($datas as $row) {
					$modelpackprice = new BookProModelTourPackage();
					$modelpackprice->setId($row->obj_id);
					$price = $modelpackprice->getObject();
					$total+=$row->adult*$price->price+$row->child*$price->child_price;
				}
				$order->total=$total;
				break;
			case 'TRANSPORT':
				if (! class_exists('BookProModelTransport')) {
					AImporter::model('transport');
				}
				foreach ($datas as $row) {
					$modelTransport = new BookProModelTransport();
					$modelTransport->setId($row->obj_id);
					$trans = $modelTransport->getObject();
					$total+=$row->adult*$trans->price;
				}
				$order->total=$total;
				break;

			default:
				;
				break;
		}
		
		$order->notes=JRequest::getString('notes');
		$order->order_status=JRequest::getString('order_status');
		$order->pay_status=JRequest::getString('pay_status');
		
		$order->store();
	}
	function saveorderinfo(){
			
		JRequest::checkToken() or jexit('Invalid Token');
		$mainframe = &JFactory::getApplication();
		$post = JRequest::get('post');
		$post['id'] = ARequest::getCid();
			
		if (! class_exists('BookProModelOrderInfo')) {
			AImporter::model('orderinfo');
		}
		$modelInfo= new BookProModelOrderInfo();
		$id=$modelInfo->store($post);
			
			
		if (! class_exists('BookProModelPackagePrice')) {
			AImporter::model('packageprice');
		}
		$modelpackprice = new BookProModelPackagePrice();
		$modelpackprice->setId(JRequest::getInt('price_id'));
		$price = $modelpackprice->getObject();
			

			
			
		$order_id=JRequest::getVar('order_id');
		$order=array('id'=>$order_id,'total'=>$total);
		$this->_model->store($order);
			
			
		if ($id !== false) {
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
			
		$this->setRedirect(JURI::base().'index.php?option=com_bookpro&controller=order&task=detail&cid[]='.$order_id);
	}

	/**
	 * Move item up in ordered list
	 */
	function orderup()
	{
		$this->setOrder(- 1);
	}

	/**
	 * Move item down in ordered list
	 */
	function orderdown()
	{
		$this->setOrder(1);
	}

	/**
	 * Set item order
	 *
	 * @param int $direct move direction
	 */
	function setOrder($direct)
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = ARequest::getCid();
		$mainframe = &JFactory::getApplication();
		if ($this->_model->move($cid, $direct)) {
			$mainframe->enqueueMessage(JText::_('Successfully moved item'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Item move failed'), 'error');
		}
		ARequest::redirectList(CONTROLLER_ORDER);
	}


	/**
	 * Save subject and state on edit page.
	 */
	function apply()
	{
		$this->save(true);
	}


	/**
	 * Save subject.
	 *
	 * @param boolean $apply true state on edit page, false return to browse list
	 */
	function sendemail(){
			

			
		AImporter::model('customer','order','application');
		$body_customer = JFile::read(JPATH_COMPONENT_BACK_END.DS.'templates'.DS.'emailconfirm.html');
		$amount=JRequest::getVar('amount');
		$order_id=JRequest::getVar('order_id');
		$orderModel=new BookProModelOrder();
		$applicationModel=new BookProModelApplication();
		$customerModel= new BookProModelCustomer();
		$orderModel->setId($order_id);
		$order=$orderModel->getObject();
		$customerModel->setId($order->user_id);
		$customer=$customerModel->getObject();
		$app=$applicationModel->getObjectByCode($order->type);
		AImporter::helper('email');
		$body_customer= EmailHelper::fillCustomer($body_customer, $customer);
		$body_customer=EmailHelper::fillOrder($body_customer,$order);
		$payment_link=JURI::root().'index.php?option=com_bookpro&task=paymentredirect&controller=payment&order_id='.$order->id;
		$body_customer = str_replace('{payment_link}',$payment_link, $body_customer);
		$order->order_status="CONFIRMED";
		$order->store();
		BookProHelper::sendMail($this->app->email_send_from, $app->email_send_from_name, $customer->email, $app->email_customer_subject, $body_customer,true);
		$this->setRedirect(JURI::root().'/administrator/index.php?option=com_bookpro&view=orders');
		return;

	}
	function save($apply = false)
	{
		JRequest::checkToken() or jexit('Invalid Token');


		$mainframe = &JFactory::getApplication();

		$post = JRequest::get('post');


		$post['id'] = ARequest::getCid();

		$post['notes'] = JRequest::getVar('notes', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$id = $this->_model->store($post);

		// notification
		$jinput = JFactory::getApplication()->input;

		if($id) {
			if($jinput->getBool('notify_customer',false)){
				AImporter::helper('email');
				$mailer=new EmailHelper();
				$mailer->changeOrderStatus($id);
			}
		}

		if ($id !== false) {
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		if ($apply) {
			ARequest::redirectEdit(CONTROLLER_ORDER, $id);
		} else {
			ARequest::redirectList(CONTROLLER_ORDER);
		}

	}


}

?>