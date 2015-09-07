<?php


defined('_JEXEC') or die;

class BookproViewAirlines extends BookproJViewLegacy
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
		JToolBarHelper::title(JText::_('Arilines'), 'airline');
		JToolbarHelper::addNew('airline.add');
		JToolbarHelper::editList('airline.edit');
		JToolbarHelper::divider();
		JToolbarHelper::publish('airlines.publish', 'Publish', true);
		JToolbarHelper::unpublish('airlines.unpublish', 'UnPublish', true);
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('', 'airlines.delete');
	}
}
