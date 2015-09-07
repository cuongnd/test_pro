<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');
    AImporter::model('roomrates', 'roomrate', 'roomratelog','room');

    class BookProControllerRoomRate extends AController
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('roomrate');
            $this->_controllerName = CONTROLLER_ROOM_RATE;
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
                    JRequest::setVar('view', 'roomrate');
            }

            parent::display();
        }

        /**
        * Open editing form page
        */
        function editing()
        {
            parent::editing('roomrate');
        }

        /**
        * Cancel edit operation. Check in subject and redirect to subjects list. 
        */
        function cancel()
        {
            $mainframe = &JFactory::getApplication();  
            $mainframe->enqueueMessage(JText::_('Subject editing canceled'));
            $mainframe->redirect('index.php?option=com_bookpro&view=hotels');
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
            ARequest::redirectList(CONTROLLER_ROOM_RATE);
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
            ARequest::redirectList(CONTROLLER_ROOM_RATE);
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

            $post = JRequest::get('post');

            $post['id'] = ARequest::getCid();   


            $startdate = new JDate($post['startdate']);        
            $enddate   = new JDate($post['enddate']);

            $starttoend =  $startdate->diff($enddate)->days;   
            for($i=0; $i <= $starttoend; $i++)
            {     
                $data = array(); 
                $data['date']=null;  
                $dateend   = date('w',strtotime($startdate.$i.' day')); 
                $date      = date('Y-m-d',strtotime($startdate.$i.' day')); 

                $data['room_id']   = $post['room_id'];  
                $data['number']    = $post['number'];  
                $date1 = new JDate($date);     
                $data['date']      = $date1-> toSql();

                if($dateend == 0 || $dateend == 6){
                    $data['rate']      = $post['endrate']; 
                }else{
                    $data['rate']      = $post['dayrate'];                  
                }    

                $data['id'] = $this->getIdBydate($date, $data['room_id']);   

                $model = new BookproModelRoomRate();
                $id = $model->store($data);   
            }

            if ($id !== false) {
                $this->saveRoomratelog($post); 
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }
            if ($apply) {
                ARequest::redirectEdit(CONTROLLER_ROOM_RATE, '');
            } else {
                ARequest::redirectList(CONTROLLER_ROOM_RATE);
            }

        }

        function saveRoomratelog($data)
        {
            if($data['room_id']){
               $modelRoom = new BookProModelRoom(); 
               $modelRoom->setId($data['room_id']);
               $room = $modelRoom->getObject() ;
               $data['hotel_id'] = $room->hotel_id;
            }
            $model = new BookProModelRoomratelog();
            $id = $model->store($data);
            return $id;
        }
        function getIdBydate($date, $room_id)
        {
            $id='';
            $lists  = array('date'=>$date);            
            $modelRoomrates = new BookproModelRoomRates();          
            $modelRoomrates->init($lists);             
            $roomrates      = $modelRoomrates->getData();      
            if(count($roomrates)>0) {                                             
                for($j=0;$j<count($roomrates);$j++)
                {    
                    if($room_id == $roomrates[$j]->room_id)
                        $id = $roomrates[$j]->id;                       
                }
            }
            return $id;     
        }

    }

?>