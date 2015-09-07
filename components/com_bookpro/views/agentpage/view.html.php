<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
AImporter::model('airports','customer');

class BookproViewAgentPage extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$document = & JFactory::getDocument();
		$model = new BookProModelCustomer();
		$model->setIdByUserId();
		$customer = &$model->getObject();
		if ($customer) {
			$customerUser = new JUser($customer->user);
			$this->assignRef('customer', $customer);
			$this->assignRef('user', $customerUser);
			$this->assignRef('params', $params);
		}
		
		$document->setTitle($user->name);
		$this->assignRef('cities', $this->getCitySelectBox($customer->city,$customer->country_id));
		
		$orderModel=new BookProModelOrders();
		$lists=array('orders-created_by'=>$customer->id,'order'=>'created');
		$orderModel->init($lists);
		$this->orders=$orderModel->getFullObject();
		
		
		parent::display($tpl);
		

	}
	
	
	function getCitySelectBox($select,$country_id)
	{
		$model = new BookProModelAirports();
		$lists=array('order'=>'id','order_Dir' => 'ASC','country_id'=>$country_id);
		$model->init($lists);
		$fullList = $model->getData();
		return AHtmlFrontEnd::getFilterSelect('city', 'Select City', $fullList, $select, false, '', 'id', 'title');
	}
}

?>
