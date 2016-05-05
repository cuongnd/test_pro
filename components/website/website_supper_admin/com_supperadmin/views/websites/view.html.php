<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of websites.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.5
 */
class supperadminViewwebsites extends JViewLegacy
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
				JText::_('COM_supperadmin_MSG_MANAGE_NO_supperadmin'),
				'warning'
			);
		}

        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('websites.quick_assign_website');
		$this->addToolbar();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_supperadmin';
        $this->controller_task='websites.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.DS.'components/website/website_supper_admin/com_supperadmin/helpers/websites.php';
		$canDo = JHelperContent::getActions('com_supperadmin');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('Website manager'), 'power-cord component');
        JToolbarHelper::addNew('website.adđ');
        JToolbarHelper::editList('website.edit');
        JToolbarHelper::publish('websites.publish');
        JToolbarHelper::unpublish('websites.unpublish');
        JToolbarHelper::deleteList('Do you want delete this websites ','websites.delete');
        JToolbarHelper::custom('websites.request_update_supper_admin','publish','','Set request update supper admin');



		JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_enabled',
				JHtml::_('select.options', websitesHelper::publishedOptions(), 'value', 'text', $this->state->get('filter.enabled'), true)
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
				'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
