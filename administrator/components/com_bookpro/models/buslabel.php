<?php


defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class BookProModelRoomlabel extends JModelAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JModelForm::getForm()
	 */
	public function getForm($data = array(), $loadData = true)
	{
		
		$form = $this->loadForm('com_bookpro.roomlabel', 'roomlabel', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
			return false;
		return $form;
	}

	/**
	 * (non-PHPdoc)
	 * @see JModelForm::loadFormData()
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_bookpro.edit.roomlabel.data', array());
		if (empty($data))
			$data = $this->getItem();
		return $data;
	}
	
	
	
	
	
	
}