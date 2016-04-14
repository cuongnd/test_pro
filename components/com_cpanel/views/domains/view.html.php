<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of domains.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.5
 */
class cpanelViewdomains extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$layout = JRequest::getVar('layout');
		$tpl = JRequest::getVar('tpl');
		$this->setLayout($layout);
		switch ($tpl) {
			case "loadcomponent":
				parent::display($tpl);
				return;
				break;

		}


		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

        $this->filterForm    = $this->get('FilterForm');
		$this->state      = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Check if there are no matching items
		if (!count($this->items))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_('com_cpanel_MSG_MANAGE_NO_cpanel'),
				'warning'
			);
		}

		$this->addToolbar();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_cpanel';
        $this->controller_task='domains.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.DS.'components/com_cpanel/helpers/domains.php';
		$canDo = JHelperContent::getActions('com_cpanel');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('Domain manager'), 'power-cord component');
        JToolbarHelper::editList('domain.edit');
        JToolbarHelper::addNew('domain.add');
        JToolbarHelper::publish('domains.publish', 'JTOOLBAR_ENABLE', true);
        JToolbarHelper::unpublish('domains.unpublish', 'JTOOLBAR_DISABLE', true);
        JToolbarHelper::deleteList('do you want delete this item','domains.delete');

        $listWebsite= domainsHelper::get_list_website();
        JHtmlSidebar::addFilter(
            JText::_('Select website'),
            'filter_website_id',
            JHtml::_('select.options',$listWebsite, 'id', 'title', $this->state->get('filter.website_id'))
        );
		JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_enabled',
				JHtml::_('select.options', domainsHelper::publishedOptions(), 'value', 'text', $this->state->get('filter.enabled'), true)
		);



		$this->sidebar = JHtmlSidebar::render();

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
				'ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'enabled' => JText::_('JSTATUS'),
				'name' => JText::_('JGLOBAL_TITLE'),
				'folder' => JText::_('folder'),
				'element' => JText::_('element'),
				'access' => JText::_('JGRID_HEADING_ACCESS'),
				'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
