<?php

defined('_JEXEC') or die;
AImporter::model('tours');
class BookproViewFaq extends BookproJViewLegacy
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
		$document = &JFactory::getDocument();   
		
                $this->tour_id = JFactory::getApplication()->getUserStateFromRequest('tour_id', 'tour_id', 0);
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
		JToolBarHelper::title(JText::_('COM_BOOKPRO_FAQ_EDIT'), 'faq');
		JToolBarHelper::apply('faq.apply');
		JToolBarHelper::save('faq.save');
		JToolBarHelper::cancel('faq.cancel');
	}
	
}