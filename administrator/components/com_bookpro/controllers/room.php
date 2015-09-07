<?php
    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');


    class BookProControllerRoom extends AController
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('room');
            $this->_controllerName = CONTROLLER_ROOM;
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
                    JRequest::setVar('view', 'rooms');
            }

            parent::display();
        }

        /**
        * Open editing form page
        */
        function editing()
        {
            parent::editing('room');
        }

        /**
        * Cancel edit operation. Check in subject and redirect to subjects list. 
        */
        function cancel()
        {
            parent::cancel('Subject editing canceled');
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
            ARequest::redirectList(CONTROLLER_ROOM);
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
            ARequest::redirectList(CONTROLLER_ROOM);
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

            if($post['child_price'])
            {
                $post['child_price'] = implode(",", $post['child_price']);
            }
            
            if($post['adult_price'])
            {
                $post['adult_price'] = implode(",", $post['adult_price']);
            }            

            if($post['child']==0){
                $post['child_price']='';
            }

            $post['id'] = ARequest::getCid();

            $post['desc']=JRequest::getVar( 'desc', '', 'post', 'string', JREQUEST_ALLOWHTML );

            $post['cancel_policy'] = JRequest::getVar('cancel_policy', '', 'post', 'string', JREQUEST_ALLOWRAW);

            $id = $this->_model->store($post);

            if ($id !== false) {
                //if(!ARequest::getCid()){
                  //  $this->saverate($post['price_weekday'], $post['price_weekend'], $id);
                //}                
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }
            if ($apply) {
                ARequest::redirectEdit(CONTROLLER_ROOM, $id);
            } else {
                ARequest::redirectList(CONTROLLER_ROOM);
            }        
        }

        function saverate($dayrate, $endrate, $room_id)
        {             
            $startdate = new JDate();                    
            $starttoend = 365;   

            for($i=0; $i < $starttoend; $i++)
            {     
                $data = array(); 
                $data['date']=null;  
                $dateend   = date('w',strtotime($startdate.$i.' day')); 
                $date      = date('Y-m-d',strtotime($startdate.$i.' day')); 

                $data['room_id']   = $room_id;  
                $date1 = new JDate($date);     
                $data['date']      = $date1-> toSql();

                if($dateend == 0 || $dateend == 6){
                    $data['rate']      = $endrate; 
                }else{
                    $data['rate']      = $dayrate;                  
                }    

                $model = new BookproModelRoomRate();
                $id = $model->store($data);      
            }   
            return '';
        }


    }

?>