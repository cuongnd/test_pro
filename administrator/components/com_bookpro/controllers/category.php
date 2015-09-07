<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: category.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerCategory extends AController
{
    
    
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->_model = $this->getModel('category');
        $this->_controllerName = CONTROLLER_CATEGORY;
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
        JRequest::setVar('view', 'categories');
        parent::display();
    }

    /**
     * Open editing form page
     */
    function editing()
    {
        parent::editing('category');
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
                $mainframe->enqueueMessage(JText::_('Tour save failed'), 'error');
            }
        }
        ARequest::redirectList(CONTROLLER_CATEGORY);
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
        ARequest::redirectList(CONTROLLER_CATEGORY);
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
        
        $post['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $id = $this->_model->store($post);
        
        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }
        if ($apply) {
            ARequest::redirectEdit(CONTROLLER_CATEGORY, $id);
        } else {
            ARequest::redirectList(CONTROLLER_CATEGORY);
        }
    
    }
	

  }

?>