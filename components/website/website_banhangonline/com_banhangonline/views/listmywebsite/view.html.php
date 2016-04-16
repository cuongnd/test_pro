<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banhangonline
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of listmywebsite.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banhangonline
 * @since       1.5
 */
class banhangonlineViewlistmywebsite extends JViewLegacy
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
				JText::_('there are no website, please create new website'),
				'warning'
			);
		}

		$this->addToolbar();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_banhangonline';
        $this->controller_task='listmywebsite.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.'/components/website/website_banhangonline/com_banhangonline/helpers/listmywebsite.php';
		$canDo = listmywebsiteHelper::getActions('com_banhangonline');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('website của tôi'), 'power-cord component');
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::addNew('mywebsite.add');
        }
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('mywebsite.edit');
		}
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('listmywebsite.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('listmywebsite.unpublish', 'JTOOLBAR_DISABLE', true);
		}
        if ($this->state->get('filter.published') == -2)
        {
            JToolbarHelper::deleteList('', 'listmywebsite.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('listmywebsite.trash');
        }
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_banhangonline');
		}

		JToolbarHelper::help('JHELP_listmywebsite_component_MANAGER');

		JHtmlSidebar::setAction('index.php?option=com_banhangonline&view=banhangonline');

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