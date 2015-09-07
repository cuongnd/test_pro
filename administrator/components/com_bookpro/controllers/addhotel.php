<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::model('packagehotel');
    AImporter::helper('request', 'controller','tour');


    class BookProControllerAddHotel extends AController
    {          
        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('addhotel');
            $this->_controllerName = CONTROLLER_ADDHOTEL;
        }

        /**
        * Display default view - Airport list	
        */
        function display()
        {                    
            switch ($this->getTask()) {
                case 'publish':
                    $this->state($this->getTask());
                    break;
                case 'unpublish':
                    $this->state($this->getTask());
                    break;
                case 'trash':
                    $this->state($this->getTask());
                    break;
            }
            JRequest::setVar('view', 'addhotel');
            parent::display();
        }

        /**
        * Cancel edit operation. Check in subject and redirect to subjects list. 
        */
        function cancel()
        {
            $mainframe = &JFactory::getApplication();
            $mainframe->enqueueMessage(JText::_('Subject editing canceled'), 'message'); 
            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=itineraries&tour_id='.JRequest::getVar('tour_id'));
            // parent::cancel('Subject editing canceled');
        }

        /**
        * Save items ordering 
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
        function save($apply = false)
        {
            JRequest::checkToken() or jexit('Invalid Token');      
            $mainframe = &JFactory::getApplication();              
            $post = JRequest::get('post');
            $packagetype_ids =  $post['packagetype_ids'];

            //delete
            if($packagetype_ids){
                for($i=0; $i<count($packagetype_ids); $i++)
                {
                    $packageHotels = array();
                    $packageHotel_ids = TourHelper::getArrayIdPackageHotelsByTou_idAndItinerary_idAndPackagetype_id($post['tour_id'], $post['itinerary_id'], $packagetype_ids[$i]);        
                    if($packageHotel_ids){
                        $modeld = new BookProModelPackageHotel();
                        $id = $modeld->trash($packageHotel_ids);       
                    }
                }
            }

            if($packagetype_ids){
                for($i=0; $i<count($packagetype_ids); $i++)
                {
                    $hotelname              = "hotel_id".$i;
                    $data                   = array();       
                    $data['itinerary_id']   = $post['itinerary_id'];
                    $data['packagetype_id'] = $packagetype_ids[$i];
                    $hotes                  = $post[$hotelname];
                    if($hotes ){
                        foreach($hotes as $key => $hote)
                        {
                            $data['hotel_id']       = $hote;      
                            $model = new BookProModelPackageHotel();
                            $id = $model->store($data);          
                        }
                    }  
                }
            }                                       

            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }           
            if($apply){
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=addhotel&itinerary_id='.JRequest::getVar('itinerary_id').'&tour_id='.JRequest::getVar('tour_id'));
            }else{                                                                     
                $mainframe->redirect('index.php?option=com_bookpro&view=itineraries&tour_id='.JRequest::getVar('tour_id'));    
            }   
        }


    }

?>