<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerTransports extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function getModel($name = 'Transport', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	
	
}