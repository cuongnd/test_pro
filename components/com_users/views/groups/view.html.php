<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of user groups.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UsersViewGroups extends JViewLegacy
{
	/**
	 * The item data.
	 *
	 * @var   object
	 * @since 1.6
	 */
	protected $items;

	/**
	 * The pagination object.
	 *
	 * @var   JPagination
	 * @since 1.6
	 */
	protected $pagination;

	/**
	 * The model state.
	 *
	 * @var   JObject
	 * @since 1.6
	 */
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');
        //$this->filterForm    = $this->get('FilterForm');
        require_once JPATH_ROOT.'/components/com_users/helpers/users.php';
        UsersHelper::addSubmenu('groups');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
        require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('groups.quick_assign_website');
		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_users');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('COM_USERS_VIEW_GROUPS_TITLE'), 'users groups');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('group.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('group.edit');
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'groups.delete');
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'groups.delete');
			JToolbarHelper::divider();
		}

		if (JFactory::getUser()->authorise('core.admin'))
		{
			JToolbarHelper::custom('groups.rebuild', 'refresh.png', 'refresh_f2.png', 'JToolbar_Rebuild', false);
		}

        $user  = JFactory::getUser();

		JToolbarHelper::help('JHELP_USERS_GROUPS');
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
				'a.title' => JText::_('COM_USERS_HEADING_GROUP_TITLE'),
				'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
