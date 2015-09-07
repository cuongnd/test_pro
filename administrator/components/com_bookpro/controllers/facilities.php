<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerFacilities extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function getModel($name = 'Facility', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;

	}




}