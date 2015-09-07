<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
AImporter::model('airports','customer');

class BookproViewMyPage extends JViewLegacy
{

	function display($tpl = null)
	{
		return;
        $user=JFactory::getUser();
        if(!$user->id)
        {
            $app=JFactory::getApplication();
            $app->Redirect('index.php?option=com_bookpro&view=login');
            return;
        }

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
		//$this->assignRef('cities', $this->getCitySelectBox($customer->city,$customer->country_id));
		
		
		
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
