<?php
return;
    // No direct access to this file
    defined('_JEXEC') or die('Restricted access');
    // import Joomla view library
    jimport('joomla.application.component.view');
    AImporter::joomlaJS();

    //import needed models
    AImporter::model("hotel", 'airports', 'faqs','activities','itineraries','airport');
    //import needed JoomLIB helpers
    AImporter::helper('tour');

    class bookproViewtour_k12301 extends JViewLegacy {

        var $document;

        function display($tpl = null) {
            return;
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

            $faqModel = new BookProModelFaqs();
            $faqs = $faqModel->getItems();
            $this->assign('faqs', $faqs);
            
            $this->assign('imagesAct', $this->getImagesAct($this->tour->id));

            parent::display($tpl);
        }

        protected function _prepareDocument() {      

            $this->document->setTitle($this->tour->title);
            $this->document->setDescription($this->tour->metadesc);
            $this->document->setMetaData('keywords', $this->tour->metakey);
        }

        protected function getImagesAct($tour_id)
        {
            $images = '';             
            $actModel   = new BookProModelActivities();
            $acts       = $actModel->getActivityByTour($tour_id);
  
            if($acts){
                foreach($acts as $key =>$value){
                   $images .= '<img src="'.JURI::base().'/'.$value->image.'" style="margin-right:2px;"/>'; 
                }
            }      
           return $images;
        }

    }
