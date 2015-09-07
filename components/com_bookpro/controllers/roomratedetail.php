<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');
    AImporter::model('roomrates', 'roomrate', 'roomratelog','roomratedetail');

    class BookProControllerRoomRateDetail extends AController
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('roomratedetail');
            $this->_controllerName = CONTROLLER_ROOM_RATE_DETAIL;
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
                default:                   
                    JRequest::setVar('view', 'roomratedetail');
                    JRequest::setVar('hotel_id', JRequest::getVar('hotel_id'));
                    JRequest::setVar('Itemid', JRequest::getVar('Itemid'));
            }           
            parent::display();
        }



        /**
        * Save subject.
        * 
        * @param boolean $apply true state on edit page, false return to browse list
        */
        function save()
        {
            JRequest::checkToken() or jexit('Invalid Token');

            $mainframe = &JFactory::getApplication();

            $id         = JRequest::getVar('id');
            $rate       = JRequest::getVar('rate');
            $room_id    = JRequest::getVar('room_id');
            $date       = JRequest::getVar('date');
            $ids='';

            if(count($rate)>0){
                for($i=0; $i<count($rate);$i++)
                {   
                    $data = array();
                    $data['id'] = $id[$i];                                    
                    $data['rate'] = $rate[$i];
                    $data['room_id'] = $room_id[$i]; 
                    $data['date'] = $date[$i];
                    if($data['id']){      
                        $model = new BookProModelRoomRateDetail();
                        $ids = $model->store($data);       
                    }                      

                    if((!$data['id'] && $data['rate']))
                    {  
                        $model = new BookProModelRoomRateDetail();
                        $ids = $model->store($data);                        
                    }                        

                }                  
            }
            if ($ids !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }          
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=roomratedetail&hotel_id='.JRequest::getVar('hotel_id').'&Itemid='.JRequest::getVar('Itemid'));
               
        } 
        
        function search()
        {
            $mainframe = &JFactory::getApplication();             
            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=roomratedetail&hotel_id='.JRequest::getVar('hotel_id').'&from='.JRequest::getVar('from').'&to='.JRequest::getVar('to').'&Itemid='.JRequest::getVar('Itemid'));
        }            
    }

?>