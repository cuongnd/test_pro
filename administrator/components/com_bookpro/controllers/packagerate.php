<?php

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller', 'tour');
AImporter::model('packagerates', 'packagerate', 'packageratelog', 'tourpackage');

class BookProControllerPackageRate extends AController {

	var $_model;

	function __construct($config = array()) {
		parent::__construct($config);
		$this->_model = $this->getModel('packagerate');
		$this->_controllerName = CONTROLLER_PACKAGE_RATE;
	}

	/**
	 * Display default view - Airport list
	 */
	function display() {
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
				JRequest::setVar('view', 'packagerate');
		}

		parent::display();
	}

	/**
	 * Open editing form page
	 */
	function editing() {
		parent::editing('packagerate');
	}

	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel() {
		$mainframe = &JFactory::getApplication();
		$mainframe->enqueueMessage(JText::_('Subject editing canceled'));
		$mainframe->redirect('index.php?option=com_bookpro&controller=tourpackage&tour_id=' . JRequest::getVar('tour_id'));
	}

	/**
	 * Save items ordering
	 */
	function saveorder() {
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
		ARequest::redirectList(CONTROLLER_PACKAGE_RATE);
	}

	/**
	 * Move item up in ordered list
	 */
	function orderup() {
		$this->setOrder(- 1);
	}

	/**
	 * Move item down in ordered list
	 */
	function orderdown() {
		$this->setOrder(1);
	}

	/**
	 * Set item order
	 *
	 * @param int $direct move direction
	 */
	function setOrder($direct) {
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = ARequest::getCid();
		$mainframe = &JFactory::getApplication();
		if ($this->_model->move($cid, $direct)) {
			$mainframe->enqueueMessage(JText::_('Successfully moved item'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Item move failed'), 'error');
		}
		ARequest::redirectList(CONTROLLER_PACKAGE_RATE);
	}

	/**
	 * Save subject and state on edit page.
	 */
	function apply() {
		$this->save(true);
	}

	/**
	 * Save subject.
	 *
	 * @param boolean $apply true state on edit page, false return to browse list
	 */
	function save($apply = false) {
		JRequest::checkToken() or jexit('Invalid Token');
		$mainframe = &JFactory::getApplication();
		$input = $mainframe->input;
		$post = JRequest::get('post');

		$model = new BookproModelPackageRate();
		$id = $model->store($post);

		if ($id !== false) {
			$this->savePackageratelog($post);
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		if ($apply) {
			ARequest::redirectEdit(CONTROLLER_PACKAGE_RATE, '');
		} else {
			$mainframe->redirect('index.php?option=com_bookpro&controller=tourpackage&tour_id=' . JRequest::getVar('tour_id'));
		}
	}

	function saveRoomPrice() {

	}

	function savePackageratelog($data) {

		if ($data['tourpackage_id']) {
			$modelTourPackage = new BookProModelTourPackage();
			$modelTourPackage->setId($data['tourpackage_id']);
			$tourpackage = $modelTourPackage->getObject();
			$data['$tour_id'] = $tourpackage->tour_id;
		}
		$model = new BookProModelPackageratelog();
		$id = $model->store($data);
		return $id;
	}

	function getIdBydate($date, $tourpackage_id) {
		//echo "aaa"; exit();
		$id = '';
		$lists = array('date' => $date);
		$modelPackagerates = new BookproModelPackageRates();
		$modelPackagerates->init($lists);
		$packagerates = $modelPackagerates->getData();
		if (count($packagerates) > 0) {
			for ($j = 0; $j < count($packagerates); $j++) {
				if ($tourpackage_id == $packagerates[$j]->tourpackage_id)
				$id = $packagerates[$j]->id;
			}
		}
		return $id;
	}

}

?>