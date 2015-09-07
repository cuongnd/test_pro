<?php

defined('_JEXEC') or die;
AImporter::model('tour');
class BookproViewCloseTour extends BookproJViewLegacy
{
    protected $tour;
	public function display($tpl = null)
	{  
        $tour_id = JRequest::getVar('tour_id');
        $model   = new BookProModelTour();
        $model->setId($tour_id);
        $this->tour = $model->getObject();
        
		parent::display($tpl);
	}               
}