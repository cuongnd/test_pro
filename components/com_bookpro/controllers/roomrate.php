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

 
        function save()
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
          
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=roomrate&hotel_id='.JRequest::getVar('hotel_id').'&Itemid='.JRequest::getVar('Itemid'));
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

        function trash()
        {   
            JRequest::checkToken() or jexit('Invalid Token');
            $mainframe = &JFactory::getApplication();
            $cid = JRequest::getVar('cid');
            if( $this->_model->trash($cid))
            {
                $mainframe->enqueueMessage(JText::_('Successfully Deleted'), 'message');

            }else{
                $mainframe->enqueueMessage(JText::_('Delete failed'), 'error');
            }

            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=roomrate&hotel_id='.JRequest::getVar('hotel_id').'&Itemid='.JRequest::getVar('Itemid')); 
        }
    }

?>