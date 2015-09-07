<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id$
    **/

    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');
    AImporter::model('coupon', 'coupons');  


    class BookProControllerCoupon extends AController
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('coupon');
            $this->_controllerName = CONTROLLER_COUPON;
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
                    break;
                case 'trash':
                    $this->state($this->getTask());
                    break;
                default:
            }
            JRequest::setVar('view', 'coupons');
            parent::display();
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


            $post['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);

            $id = $this->_model->store($post);

            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=coupon&cid[]='.$id.'&Itemid='.JRequest::getVar('Itemid'));  
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

            $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=coupons&Itemid='.JRequest::getVar('Itemid')); 
        }       
    }

?>