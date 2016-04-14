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
 * View class for a list of listraovat.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.5
 */
class cpanelViewlistraovat extends JViewLegacy
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
        $this->controller_task='listraovat.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.'/components/com_cpanel/helpers/listraovat.php';
		$canDo = listraovatHelper::getActions('com_cpanel');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('LIST_RAOVAT_MANAGER'), 'power-cord component');
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::addNew('raovat.add');
        }
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('raovat.edit');
		}
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::custom('listraovat.add', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('listraovat.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('listraovat.unpublish', 'JTOOLBAR_DISABLE', true);
		}
        if ($this->state->get('filter.published') == -2)
        {
            JToolbarHelper::deleteList('', 'listraovat.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('listraovat.trash');
        }
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_cpanel');
		}

		JToolbarHelper::help('JHELP_listraovat_component_MANAGER');

		JHtmlSidebar::setAction('index.php?option=com_cpanel&view=cpanel');

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
