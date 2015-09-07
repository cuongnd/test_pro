<?php


defined('_JEXEC') or die;

class BookproViewAirportairline extends BookproJViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * (non-PHPdoc)
	 * @see JViewLegacy::display()
	 */
	public function display($tpl = null)
	{
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->addToolbar();
		BookproHelper::setSubmenu('');
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('Airportairline'), '');
		JToolBarHelper::apply('airportairline.apply');
		JToolBarHelper::save('airportairline.save');
		JToolBarHelper::cancel('airportairline.cancel');
	}
}
