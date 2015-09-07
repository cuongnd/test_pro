<?php 
class BookproModelFlightroute extends JModelAdmin{
	public function getForm($data = array(), $loadData = true)   //lấy form trong file xml(models/forms/themdefault.xml)
	{															//Lấy form vào
		$app = JFactory::getApplication();
		$form = $this->loadForm('com_bookpro.flightroute', 'flightroute',
				array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		
		return $form;
	}
	
	protected function loadFormData() {//load dá»¯ liá»‡u lÃªn form
		$data = JFactory::getApplication()->getUserState ( 'com_bookpro.edit.flightroute.data', array () );
			
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
	
		return $data;
	}
	
 
	
	
}