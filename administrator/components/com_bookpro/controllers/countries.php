<?php



defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerCountries extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */

	function getAjaxItems()
	{
		$countriesModel=$this->getModel();
		$items=$countriesModel->getItems();
		echo json_encode($items);
		die;
	}
	public function getModel($name = 'Countries', $prefix = 'BookProModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	public function  DeleteItem()
	{
		$app=JFactory::getApplication();
		$id=$app->input->get('id',0,'int');
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
		$tableCountry=JTable::getInstance('Country','Table');
		if($tableCountry->delete($id)){
			echo 'ok';
		}else{
			echo $tableCountry->getError();
		}
		die;


	}
	/*function  delete_row()
	{
		$app=JFactory::getApplication();
		$id=$app->input->get('id',0,'int');
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
		$tableCountry=JTable::getInstance('Country','Table');
		if($tableCountry->delete($id)){
			echo 'ok';
		}else{
			echo $tableCountry->getError();
		}
		die;


	}*/
	function  update_row()
	{
		$app=JFactory::getApplication();
		$ids=$app->input->get('cid',array(),'array');
		$id=$ids[0];
		$path=$app->input->get('path','','string');
		$country_name=$app->input->get('country_name','','string');
		$country_code=$app->input->get('country_code','','string');
		$phone_code=$app->input->get('phone_code','','string');
		$state_number=$app->input->get('state_number','','string');
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
		$tableCountry=JTable::getInstance('Country','Table');
		$tableCountry->load($id);
		$tableCountry->path=$path;
		$tableCountry->country_name=$country_name;
		$tableCountry->country_code=$country_code;
		$tableCountry->phone_code=$phone_code;
		$tableCountry->state_number=$state_number;
		if(!$tableCountry->store())
		{
			echo $tableCountry->getError();
		}
		die;
	}
	function  save_row()
	{
		$app=JFactory::getApplication();
		$id=$app->input->get('id',0,'int');
		$path=$app->input->get('path','','string');
		$country_name=$app->input->get('country_name','','string');
		$country_code=$app->input->get('country_code','','string');
		$phone_code=$app->input->get('phone_code','','string');
		$state_number=$app->input->get('state_number','','string');
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
		$tableCountry=JTable::getInstance('Country','Table');
		$tableCountry->load($id);
		$tableCountry->path=$path;
		$tableCountry->country_name=$country_name;
		$tableCountry->country_code=$country_code;
		$tableCountry->phone_code=$phone_code;
		$tableCountry->state_number=$state_number;
		if(!$tableCountry->store())
		{
			echo $tableCountry->getError();
		}
		die;
	}
}