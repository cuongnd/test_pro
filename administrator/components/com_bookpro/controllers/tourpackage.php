<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerTourPackage extends AController
{
    
    
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->_model = $this->getModel('tourpackage');
        $this->_controllerName = CONTROLLER_TOURPACKAGE;
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
        JRequest::setVar('view', 'tourpackages');
        parent::display();
    }

    /**
     * Open editing form page
     */
    function editing()
    {
        parent::editing('tourpackage');
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
        ARequest::redirectList(CONTROLLER_TOURPACKAGE);
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
        ARequest::redirectList(CONTROLLER_TOURPACKAGE);
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
        $post['roomtypes'] = JRequest::getVar('roomtypes',null,array());
        $post['desc'] = JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $id = $this->_model->store($post);
        
        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }
        if ($apply) {
            ARequest::redirectEdit(CONTROLLER_TOURPACKAGE, $id);
        } else {
            ARequest::redirectList(CONTROLLER_TOURPACKAGE);
        }
    
    }
	      function CheckTourShared()
     {
        $stype = '';
        $id = JRequest::getVar('tour_id');
        AImporter::model('tour');
        $model = new BookProModelTour();
        $model->setId($id);
        $tour = $model->getObject();
        if($tour){
            $stype = trim($tour->stype); 
        }
        echo $stype;
        die;
     }    


  }

?>