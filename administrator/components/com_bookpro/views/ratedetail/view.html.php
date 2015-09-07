<?php

defined('_JEXEC') or die;
AImporter::model('flightrate');
class BookproViewRatedetail extends BookproJViewLegacy
{
	

	/**
	 * (non-PHPdoc)
	 * @see JViewLegacy::display()
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$flight_id = $input->get('flight_id',0);
		$date = $input->get('date',null);
		$model = new BookProModelFlightRate();
		$this->rates = $model->getRateFlight($flight_id, $date);
		
		parent::display($tpl);
	}

	
}