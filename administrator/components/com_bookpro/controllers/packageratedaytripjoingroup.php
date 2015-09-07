<?php

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller', 'tour');
AImporter::model('packageratedaytripjoingroup', 'packageratedaytripjoingrouplog', 'tourpackage');

class BookProControllerPackageRatedaytripjoingroup extends AController {

	var $_model;

	function __construct($config = array()) {
		parent::__construct($config);
		$this->_model = $this->getModel('packageratedaytripjoingroup');
		$this->_controllerName = CONTROLLER_PACKAGE_RATEDAYTRIPJOINGROUP;
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
				JRequest::setVar('view', 'packageratedaytripjoingroup');
		}

		parent::display();
	}

	/**
	 * Open editing form page
	 */
	function editing() {
		parent::editing('packageratedaytripjoingroup');
	}

	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel() {
		$mainframe = &JFactory::getApplication();
		$mainframe->enqueueMessage(JText::_('Subject editing canceled'));
		$mainframe->redirect('index.php?option=com_bookpro&view=tours');
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
		$post = JRequest::get('post');
		$model = new BookproModelPackageRatedaytripjoingroup();
		$id = $model->store($post);
		if ($id !== false) {
			$this->savePackageratedaytripjoingrouplog($post);
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		if ($apply) {
			ARequest::redirectEdit(CONTROLLER_PACKAGE_RATEDAYTRIPJOINGROUP, '');
		} else {
			$mainframe->redirect('index.php?option=com_bookpro&controller=tourpackage&tour_id=' . JRequest::getVar('tour_id'));
		}
	}

	function savePackageratedaytripjoingrouplog($data) {
		$model = new BookProModelPackageratedaytripjoingrouplog();
		$id = $model->store($data);
		return $id;
	}

	function getIdBydate($date, $tourpackage_id) {
		//echo "aaa"; exit();
		$id = '';
		$lists = array('date' => $date);
		$modelPackagerates = new BookproModelPackageRatedaytripjoingroups();
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