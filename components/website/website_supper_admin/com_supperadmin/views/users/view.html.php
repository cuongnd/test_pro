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
 * View class for a list of users.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.5
 */
class supperadminViewusers extends JViewLegacy
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

        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('users.quick_assign_website');
		$this->addToolbar();

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
        );

        $this->sidebar = JHtmlSidebar::render();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_supperadmin';
        $this->controller_task='users.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.'/components/website/website_supper_admin/com_supperadmin/helpers/users.php';
		$canDo = JHelperContent::getActions('com_supperadmin');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('user manager'), 'power-cord component');
        $layout = new JLayoutFile('toolbar.newcomponent');

        $bar->appendButton('Custom', $layout->render(array()), 'new');
        JToolbarHelper::editList('user.edit');
        JToolbarHelper::addNew('user.add');
        if ($canDo->get('core.create'))
        {
            // Instantiate a new JLayoutFile instance and render the layout
            $layout = new JLayoutFile('toolbar.newcomponent');

            $bar->appendButton('Custom', $layout->render(array()), 'new');
        }
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('user.edit');
		}
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::custom('users.add', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }
		JToolbarHelper::custom('users.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);

		JToolbarHelper::duplicate('users.duplicate');
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('users.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('users.unpublish', 'JTOOLBAR_DISABLE', true);
			JToolbarHelper::checkin('users.checkin');
		}
        if ($this->state->get('filter.published') == -2)
        {
            JToolbarHelper::deleteList('', 'users.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('users.trash');
        }
        JToolbarHelper::publish('users.issystem','Is system');
        JToolbarHelper::unpublish('users.isnotsystem','Is system');
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_supperadmin');
		}

		JToolbarHelper::help('JHELP_userS_component_MANAGER');



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
