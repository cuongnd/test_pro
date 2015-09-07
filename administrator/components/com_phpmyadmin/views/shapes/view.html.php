<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_phpmyadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of plugins.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_phpmyadmin
 * @since       1.5
 */
class phpMyAdminViewShapes extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');
        $this->authors       = $this->get('Authors');
        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

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
				JText::_('com_phpmyadmin_MSG_MANAGE_NO_PLUGINS'),
				'warning'
			);
		}
		require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('plugins.quick_assign_website');
		$this->addToolbar();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_phpmyadmin';
        $this->controller_task='plugins.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo	= JHelperContent::getActions('com_menus');
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_MENUS_VIEW_ITEMS_TITLE'), 'list menumgr');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('shape.add');
		}

		if ($canDo->get('shape.edit'))
		{
			JToolbarHelper::editList('shape.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('items.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('items.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		}

		if (JFactory::getUser()->authorise('core.admin'))
		{
			JToolbarHelper::checkin('items.checkin', 'JTOOLBAR_CHECKIN', true);
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'items.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('shapes.trash');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::makeDefault('items.setDefault', 'COM_MENUS_TOOLBAR_SET_HOME');
		}

		if (JFactory::getUser()->authorise('core.admin'))
		{
			JToolbarHelper::custom('shapes.rebuild', 'refresh.png', 'refresh_f2.png', 'JToolbar_Rebuild', false);
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_menus') && $user->authorise('core.edit', 'com_menus') && $user->authorise('core.edit.state', 'com_menus'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		JToolbarHelper::help('JHELP_MENUS_MENU_ITEM_MANAGER');

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
				'folder' => JText::_('com_phpmyadmin_FOLDER_HEADING'),
				'element' => JText::_('com_phpmyadmin_ELEMENT_HEADING'),
				'access' => JText::_('JGRID_HEADING_ACCESS'),
				'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
