<?php

defined('_JEXEC') or die;
AImporter::helper('bookpro', 'request','image','file');
//import needed assets

class BookproViewReview extends BookproJViewLegacy
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
		JToolBarHelper::title(JText::_('Edit Review'), 'review');
		JToolBarHelper::apply('review.apply');
		JToolBarHelper::save('review.save');
		JToolBarHelper::cancel('review.cancel');
	}
}