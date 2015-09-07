<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('tour','itineraries');
AImporter::helper('tourhelper');
AImporter::joomlaJS();

class BookProViewItineraryPdf extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null)
	{
		$this->config=AFactory::getConfig();
		
		$tour_id=JRequest::getVar('tour_id');
		if (! class_exists('BookProModelItineraries')) {
			AImporter::model('itineraries');
		}
		$result=array();
		$model = new BookProModelItineraries();
		$lists=array('order'=>'ordering','tour_id'=>$tour_id);
		$model->init($lists);
		$items = $model->getData();
		$this->assign('items',$items);
		$tmodel=new BookProModelTour();
		$tmodel->setId($tour_id);
		$this->tour=$tmodel->getObject();
		
		
		$document=JFactory::getDocument();
		$document->setName('mypdf');
		
		parent::display($tpl);
	}
	protected function _prepareDocument(){
		

	}
	
}
