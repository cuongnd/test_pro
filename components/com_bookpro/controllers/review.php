<?php
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');

AImporter::model('room', 'roomrate');

class BookProControllerReview extends AController
{
	var $_model;
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('review');
		$this->_controllerName = 'review';
	}

	/**
	 * Display default view - Airport list
	 */
	function display()
	{

		switch ($this->getTask()) {
			case 'publish':
				$this->publish();
				break;
			case 'unpublish':
				$this->unpublish();
				break;
			case 'trash':
				$this->trash();
				break;
			default:
				JRequest::setVar('view', 'reviews');
		}

		parent::display();
	}
	function save()
	{
		$cart = JModelLegacy::getInstance('ReviewCart', 'bookpro');

		$cart->load();
			
		JRequest::checkToken() or jexit('Invalid Token');
		$input=JFactory::getApplication()->input;

		$mainframe = &JFactory::getApplication();

		$mainframe = &JFactory::getApplication();
		$data = JRequest::get('post');

		$user = JFactory::getUser();
		if ($user->id) {
			AImporter::model('customer');
			$model = new BookProModelCustomer();
			$customer = $model->getObjectByUserId($user->id);

			$data['customer_id'] = $customer->id;
		}
			
		$data['type'] = 'TOUR';

		$id = $this->_model->store($data);

		if($id)
		{
			$mainframe -> enqueueMessage(JText::_('Successfully saved'), 'message');
			$mainframe->redirect('index.php?option=com_bookpro&view=review&id='.$id.'&Itemid='.JRequest::getVar('Itemid'));
		}
		else
		{
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}


	}
	function trash()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$cid = JRequest::getVar('cid');

		$mainframe = &JFactory::getApplication();
		$db=JFactory::getDbo();
		foreach($cid as $id)
		{
			$query=$db->getQuery(true);
			$query->delete('#__bookpro_facility');
			$query->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			$db->execute();
		}
		$mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=facilities&Itemid='.JRequest::getVar('Itemid'),Jtext::_('COM_BOOKPRO_DALETE_SUCCESS'));
	}


}

?>