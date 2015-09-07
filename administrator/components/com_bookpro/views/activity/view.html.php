<?php

defined('_JEXEC') or die;

class BookproViewActivity extends BookproJViewLegacy
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
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_('Edit Activity'), 'activity');
		JToolBarHelper::apply('activity.apply');
		JToolBarHelper::save('activity.save');
		JToolBarHelper::cancel('activity.cancel');
	}
}