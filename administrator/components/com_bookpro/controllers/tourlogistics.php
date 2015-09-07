<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerTourLogistics extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	function  delete_row()
	{
		echo "hello delete row";
		die;
	}
	function  save_row($data)
	{
//		$db = JFactory::getDbo();
//		$query = $db->getQuery(true);
//		$query->insert('#__bookpro_country');
//		$query->columns('country_name','country_code','phone_code','state_number');
//		$query->values('United States','US ss','2','');
	}

	
	
	
}