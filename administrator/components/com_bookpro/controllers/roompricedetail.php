<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');
AImporter::model('roompricedetail');

class BookProControllerRoomPriceDetail extends AController
{
	var $_model;
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('roompricedetail');
		$this->_controllerName = CONTROLLER_ROOM_PRICE_DETAIL;
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
				JRequest::setVar('view', 'roompricedetail');
		}

		parent::display();
	}

	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel()
	{
		$mainframe = &JFactory::getApplication();
		$mainframe->enqueueMessage(JText::_('Subject editing canceled'));
		$mainframe->redirect('index.php?option=com_bookpro&controller=tourpackage&tour_id='.JRequest::getVar('tour_id'));
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
			
		$model = new BookProModelRoomPriceDetail();
		$ids = $model->store($post);
		if ($ids !== false) {
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}

		if($apply){
			$mainframe->redirect('index.php?option=com_bookpro&view=roompricedetail');
		}else{
			$mainframe->redirect('index.php?option=com_bookpro&controller=tourpackage&tour_id='.JRequest::getVar('tour_id'));
		}
	}
}

?>