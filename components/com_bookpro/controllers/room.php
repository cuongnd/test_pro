<?php
    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');

    AImporter::model('room', 'roomrate');    

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
        * Save subject.
        * 
        * @param boolean $apply true state on edit page, false return to browse list
        */
        function save()
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
			//save default rate
            if ($id !== false) {
                if(!ARequest::getCid()){
                    //$this->saverate($post['price_weekday'], $post['price_weekend'], $id);
                }               
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }

            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=rooms&hotel_id='.JRequest::getVar('hotel_id').'&cid[]='.$id.'&Itemid='.JRequest::getVar('Itemid'));           
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

            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=rooms&hotel_id='.JRequest::getVar('hotel_id').'&Itemid='.JRequest::getVar('Itemid')); 
        }


    }

?>