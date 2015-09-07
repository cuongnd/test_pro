<?php

defined('_JEXEC') or die;

class BookproViewStats extends BookproJViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * (non-PHPdoc)
	 * @see JViewLegacy::display()
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		
		
		$airportFrombox=$this->getAirportSelectBox(0,'jform[desfrom]');
		$airportTobox=$this->getAirportSelectBox(0,'jform[desto]');
		$this->weekday = $this->getDayWeek('weekday[]');
		
		$this->assignRef("airportfrom",$airportFrombox);
		$this->assignRef("airportto",$airportTobox);
		
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	static function getDayWeek($name){
		AImporter::helper('date');
		$days=DateHelper::dayofweek();
		$daysweek=array();
		foreach ($days as $key => $value)
		{
			
			$object=new stdClass();
			$object->key=$key;
			$object->value=$value->text;
			$daysweek[]=$object;
		}
		$selected=array_keys($days);
		return AHtml::checkBoxList($daysweek,$name,'',$selected,'key','value');
	
	}
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_('Add flight stats'), 'flight');
		
		JToolBarHelper::save('saveStats');
		JToolBarHelper::cancel('cancel');
	}
	function getAirportSelectBox($select, $field = 'desfrom', $autoSubmit = false)
	{
		AImporter::model('airports');
		$model = new BookProModelAirports();
		 
		 
		$state=$model->getState();
		$state->set('list.start',0);
		$state->set('list.limit', 0);
		$state->set('list.state', 1);
		$state->set('list.air', 1);
		$state->set('list.parent_id', 1);
		$fullList = $model->getItems();
	
		 
		return AHtml::getFilterSelect($field, 'Select Airport', $fullList, $select, $autoSubmit, 'class="required validate-select"', 'code', 'title');
	}
}