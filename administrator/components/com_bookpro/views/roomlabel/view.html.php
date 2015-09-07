<?php

defined('_JEXEC') or die;

class BookproViewRoomlabel extends BookproJViewLegacy
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
		JToolBarHelper::title(JText::_('Edit Roomlabel'), 'roomlabel');
		JToolBarHelper::apply('roomlabel.apply');
		JToolBarHelper::save('roomlabel.save');
		JToolBarHelper::cancel('roomlabel.cancel');
	}
}