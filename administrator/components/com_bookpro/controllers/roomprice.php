<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');
AImporter::model('roompricelog','tourpackage','roomprices','roomprice');


class BookProControllerRoomPrice extends AController
{
	var $_model;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('roomprice');
		$this->_controllerName = CONTROLLER_ROOM_PRICE;
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
				JRequest::setVar('view', 'roomprice');
		}
		parent::display();
	}

	/**
	 * Open editing form page
	 */
	function editing()
	{
		parent::editing('roomprice');
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
		ARequest::redirectList(CONTROLLER_ROOM_PRICE);
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
		ARequest::redirectList(CONTROLLER_ROOM_PRICE);
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

		$model = new BookProModelRoomPrice();
		$id = $model->store($post);
		if ($id !== false) {
			$this->saveRoompricelog($post);
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		if ($apply) {
			ARequest::redirectEdit(CONTROLLER_ROOM_PRICE, '');
		} else {
			ARequest::redirectList(CONTROLLER_ROOM_PRICE);
		}

	}
	function saveRoompricelog($post)
	{
		$price                  = $post['price'];
		$roomtypes              = $post['roomtype_id'];
		$data['tourpackage_id'] = $post['tourpackage_id'];
		$data['startdate']      = $post['startdate'];
		$data['enddate']        = $post['enddate'];
		$data['tour_id']        = $post['tour_id'];

		if($roomtypes){
			for($j=0; $j<count($roomtypes); $j++)
			{
				$data['roomtype_id'] = $roomtypes[$j];
				$data['price']       = $price[$j];

				$model = new BookProModelRoompricelog();
				$id = $model->store($data);
			}
		}
			
		return $id;
	}

	function getIdBydate($date, $tourpackage_id, $roomtype_id)
	{
		$id='';
		$lists  = array('date'=>$date,'tourpackage_id'=>$tourpackage_id, 'roomtype_id'=>$roomtype_id);
		$modelRoomprices = new BookproModelRoomPrices();
		$modelRoomprices->init($lists);
		$roomprices      = $modelRoomprices->getData();
		if(count($roomprices)>0) {
			for($j=0;$j<count($roomprices);$j++)
			{
				if($tourpackage_id == $roomprices[$j]->tourpackage_id && $roomtype_id == $roomprices[$j]->roomtype_id)
				$id = $roomprices[$j]->id;
			}
		}
		return $id;
	}

}

?>