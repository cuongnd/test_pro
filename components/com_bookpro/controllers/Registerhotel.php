<?php

    /**
    * @package     Bookpro
    * @author         Nguyen Dinh Cuong
    * @link         http://ibookingonline.com
    * @copyright     Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version     $Id: hotel.php 82 2012-08-16 15:07:10Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller','hotel');
    AImporter::model('hotel');

    class BookProControllerRegisterhotel extends AController
    {     
        var $_model;

        function __construct($config = array())
        {             
            parent::__construct($config);
            $this->_model = $this->getModel('hotel');
            $this->_controllerName = CONTROLLER_REGISTER_HOTEL;  
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
                case 'feature':
                    $this->state($this->getTask());
                    break;
                case 'unfeature':
                    $this->state($this->getTask());
                    break;
                case 'trash':
                    $this->state($this->getTask());
                    break;
                default:
                    JRequest::setVar('view', 'registerhotels');
            }               
            parent::display();
        }

        function save()
        {
            JRequest::checkToken() or jexit('Invalid Token');

            $mainframe = &JFactory::getApplication();

            $post = JRequest::get('post');       

            $post['id'] = ARequest::getCid();

            //$post['userid'] = HotelHelper::getCustomerIdByUserLogin();    
            //var_dump($post['userid']); die;

            $post['desc']=JRequest::getVar( 'desc', '', 'post', 'string', JREQUEST_ALLOWHTML );
            $post['term_conditions'] = JRequest::getVar('term_conditions', '', 'post', 'string', JREQUEST_ALLOWRAW);

            $post['cancel_policy'] = JRequest::getVar('cancel_policy', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $post['facility']=JRequest::getVar( 'facility', '', 'post', 'string', JREQUEST_ALLOWHTML );      

            $id = $this->_model->store($post);          

            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }                                  

            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=registerhotels&Itemid='.JRequest::getVar('Itemid'));
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

            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=registerhotels&Itemid='.JRequest::getVar('Itemid')); 
        }

    }

?>