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
AImporter::helper('bookpro', 'controller', 'parameter', 'request');

class BookProControllerPassenger extends AController
{
    
    /**
     * Main model
     * 
     * @var BookProModelCustomer
     */
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        if (! class_exists('BookProModelPassenger')) {
            AImporter::model('passenger');
        }
        $this->_model = new BookProModelPassenger();
        $this->_controllerName = CONTROLLER_PASSENGER;
    }

    /**
     * Display default view - Passengers list	
     */
    function display()
    {
        switch ($this->getTask()) {
            case 'trash':
            	$this->state($this->getTask());
            	break;
            case 'restore':
                $this->state($this->getTask());
                break;
            default:
                JRequest::setVar('view', 'passengers');
                break;
        }
        parent::display();
    }

    /**
     * Display browse Passengers page into element window.
     */
    function element()
    {
        $this->display();
    }

    /**
     * Open editing form page.
     */
    function editing()
    {
        parent::editing('passenger');
    }

    /**
     * Cancel edit operation. Check in customer and redirect to customers list. 
     */
    function cancel()
    {
        parent::cancel('Customer editing canceled');
    }
    
    function apply()
    {
    	$this->save(true);
    }

    /**
     * Save customer.
     * 
     * @param boolean $apply true state on edit page, false return to browse list
     */
    function save($apply = false)
    {
     JRequest::checkToken() or jexit('Invalid Token');
        
        
        $mainframe = &JFactory::getApplication();
        
        $post = JRequest::get('post');
        
        $post['id'] = ARequest::getCid();
        
        $post['desc'] = JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $id = $this->_model->store($post);
        
        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }
        if ($apply) {
            ARequest::redirectEdit(CONTROLLER_PASSENGER, $id);
        } else {
            ARequest::redirectList(CONTROLLER_PASSENGER);
        }
    }
   

}

?>