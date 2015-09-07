<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::joomlaJS();

//import needed models
AImporter::model("hotel", 'airports', 'faqs', 'activities', 'itineraries', 'airport', 'galleries');
//import needed JoomLIB helpers
AImporter::helper('tour');
AImporter::model('packagetypes', 'tourpackages');
AImporter::helper('currency');

class BookProViewTour extends JViewLegacy {

    var $document;

    function display($tpl = null) {

        /*
          print_r('<PRE>');
          var_dump($tours); die; */

        $dispatcher = JDispatcher::getInstance();
        $this->document = JFactory::getDocument();
        $this->config = AFactory::getConfig();
        $this->_prepareDocument();

        $dispatcher = JDispatcher::getInstance();
        $this->event = new stdClass();
        JPluginHelper::importPlugin('bookpro');

        $results = $dispatcher->trigger('onBookproProductAfterTitle', array($this->tour));
        $this->event->afterDisplayTitle = $results[0];

        $faqs = TourHelper::getFaqsByTourId($this->tour->id);
        $this->assign('faqs', $faqs);
        AImporter::model('tour');
        $model_tour = new BookProModelTour();
        $this->tour = $model_tour->getFullTourObject(JRequest::getVar('id'));
		$reviews = $this->getListReviews();
		$this->assignRef('reviews', $reviews);
        $this->assign('imagesAct', $this->getImagesAct($this->tour->id));
        $stype = $this->tour->daytrip ? 'daytrip' : $this->tour->stype;
        $listtourpackagerates = $this->{'listtourpackagerates_' . $stype}();
        $this->assign('listtourpackagerates', $listtourpackagerates);

        parent::display($tpl);
    }
    function getListReviews(){
    	AImporter::model('reviews','customer','country');
    	$model = new BookProModelReviews();
    	$lists = array('review-state'=>1,'obj_id'=>$this->tour->id);
    	$model->init($lists);
    	$items = $model->getData();
    	
    	return $items;
    }

    protected function _prepareDocument() {

        $this->document->setTitle($this->tour->title);
        $this->document->setDescription($this->tour->metadesc);
        $this->document->setMetaData('keywords', $this->tour->metakey);
    }

    function listtourpackagerates_private() {
        
    }

    function listtourpackagerates_shared() {
        
    }

    function listtourpackagerates_daytrip() {
        $listtourpackagerates=array();
        $now = JFactory::getDate(JRequest::getVar('checkin'));
        $checkin = JFactory::getDate($now->format('Y-m-d'));
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $tour_id = JRequest::getVar('id');
        if ($tour_id) {
            AImporter::model('tour');
            $model_tour = new BookProModelTour();
            $tour = $model_tour->getFullTourObject($tour_id);
            $this->tour = $tour;
        }

        $query->select('packagerate.*,tour.capacity AS tour_capacity,tour.stype AS stype,tour_package.min_person AS min_person,tour.id AS tour_id,packagetype.id AS packagetype_id,tour_package.id AS tour_package_id
  
   ,packagetype.title AS packagetype_title
        ');
        $query->from('#__bookpro_packagerate as packagerate');
        $query->leftJoin('#__bookpro_tour_package  AS tour_package ON tour_package.id=packagerate.tourpackage_id');
        $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=tour_package.packagetype_id');
        $query->leftJoin('#__bookpro_tour AS tour ON tour.id=tour_package.tour_id');
        $query->where('packagerate.date =' . $db->quote($checkin->toSql()));
        $query->where('tour_package.tour_id=' . $this->tour->id);
        //$query->group('packagerate.date');
        $db->setQuery($query);
        //echo $db->replacePrefix($query);
        $listtourpackagerates[] = $db->loadObjectList('id');
       // echo "<pre>";
        //print_r($listtourpackagerates[0]);
       // exit();
        $query = $db->getQuery(true);
        $query->select('
            packagerate.*,tour.stype AS stype,tour.id AS tour_id 
        ');
        $query->from('#__bookpro_packageratedaytripjoingroup as packagerate');
        $query->leftJoin('#__bookpro_tour AS tour ON tour.id=packagerate.tour_id');
        $query->where('packagerate.date =' . $db->quote($checkin->toSql()));
        $query->where('packagerate.tour_id=' . $this->tour->id);
        $query->group('packagerate.date');
        $db->setQuery($query);
        //echo $db->replacePrefix($query);
        $listtourpackagerates[] = $db->loadObject();


        return $listtourpackagerates;
    }

    protected function getImagesAct($tour_id) {
        $images = '';
        $actModel = new BookProModelActivities();
        $acts = $actModel->getActivityByTour($tour_id);

        if ($acts) {
            foreach ($acts as $key => $value) {
                $images .= '<img src="' . JURI::base() . '/' . $value->image . '" style="margin-right:2px;"/>';
            }
        }
        return $images;
    }

}
