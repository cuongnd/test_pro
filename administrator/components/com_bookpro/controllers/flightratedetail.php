<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');
    AImporter::model('flightrates', 'flightrate', 'flightratelog');

    class BookProControllerFlightRateDetail extends AController
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('flightratedetail');
            $this->_controllerName = CONTROLLER_FLIGHT_RATE_DETAIL;
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
                    JRequest::setVar('view', 'flightratedetail');
            }

            parent::display();
        }

        /**
        * Open editing form page
        */
        function editing()
        {
            parent::editing('flightratedetail');
        }

        /**
        * Cancel edit operation. Check in subject and redirect to subjects list. 
        */
        function cancel()
        {           
            $mainframe = &JFactory::getApplication();  
            $mainframe->enqueueMessage(JText::_('Subject editing canceled'));
            $mainframe->redirect('index.php?option=com_bookpro&view=flights');
        }

        /**
        * Save items ordering 
        */
        function saveorder()
        {
            JRequest::checkToken() or jexit('Invalid Token');

            $cids = ARequest::getCids();
            $order = ARequest::getIntArray('order');
            if (ARequest::controlCids($cids, 'save order')) {
                $mainframe = &JFactory::getApplication();
                if ($this->_model->saveorder($cids, $order)) {
                    $mainframe->enqueueMessage(JText::_('Successfully saved order'), 'message');
                } else {
                    $mainframe->enqueueMessage(JText::_('Order save failed'), 'error');
                }
            }
            ARequest::redirectList(CONTROLLER_ROOM_RATE_DETAIL);
        }

        /**
        * Move item up in ordered list
        */
        function orderup()
        {
            $this->setOrder(- 1);
        }

        /**
        * Move item down in ordered list
        */
        function orderdown()
        {
            $this->setOrder(1);
        }

        /**
        * Set item order
        * 
        * @param int $direct move direction
        */
        function setOrder($direct)
        {                            
            JRequest::checkToken() or jexit('Invalid Token');
            $cid = ARequest::getCid();
            $mainframe = &JFactory::getApplication();
            if ($this->_model->move($cid, $direct)) {
                $mainframe->enqueueMessage(JText::_('Successfully moved item'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Item move failed'), 'error');
            }
            ARequest::redirectList(CONTROLLER_ROOM_RATE_DETAIL);
        }


        /**
        * Save subject and state on edit page.
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

            $id         = JRequest::getVar('id');
            $rate       = JRequest::getVar('rate');
            $flight_id    = JRequest::getVar('flight_id');
            $date       = JRequest::getVar('date');
            $number       = JRequest::getVar('number');
            $ids='';

            if(count($rate)>0){
                for($i=0; $i<count($rate);$i++)
                {   
                    $data = array();
                    $data['id']     = $id[$i];                                    
                    $data['rate']   = $rate[$i];
                    $data['flight_id'] = $room_id[$i]; 
                    $data['date']   = $date[$i];
                    $data['number'] = $number[$i];
                    
                    if($data['id']){      
                        $model = new BookProModelRoomRateDetail();
                        $ids = $model->store($data);       
                    }                      
                    
                    if((!$data['id'] && $data['rate']) || (!$data['id'] && $data['number']))
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
            $mainframe = &JFactory::getApplication();
            if($apply){
                $mainframe->redirect('index.php?option=com_bookpro&view=flightratedetail');    
            }else{
                $mainframe->redirect('index.php?option=com_bookpro&view=flightrate');
            }


        }             
    }

?>