<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerTours extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	
		$this->registerTask('unfeatured',	'featured');
	}
	public function getModel($name = 'Tour', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	public function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$user   = JFactory::getUser();
		$ids    = $this->input->get('cid', array(), 'array');
	
		$values = array('featured' => 1, 'unfeatured' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');
	
		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();
			// Publish the items.
			if (!$model->featured($ids,$value))
			{
				JError::raiseWarning(500, $model->getError());
			}
		}
	
		$this->setRedirect('index.php?option=com_bookpro&view=tours');
	}
	
	
	
	
}