<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerAirlines extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function __construct($config = array())
	{
		$this->view_list = 'airlines';
		parent::__construct($config);
	
	}
	public function getModel($name = 'Airline', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function publish() {
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$this->getModel()->publish($cid);
		$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=airlines', false));
	}
	
	public function unpublish() {
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$this->getModel()->unpublish($cid);
		$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=airlines', false));
	}
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}
	
	
}