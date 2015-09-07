<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_plugins
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of plugins.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_plugins
 * @since       1.5
 */
class PluginsViewPlugins extends JViewLegacy
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
				JText::_('COM_PLUGINS_MSG_MANAGE_NO_PLUGINS'),
				'warning'
			);
		}

        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('plugins.quick_assign_website');
		$this->addToolbar();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_plugins';
        $this->controller_task='plugins.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_plugins');
        $bar = JToolBar::getInstance('toolbar');
        $supperAdmin=JFactory::isSupperAdmin();
		JToolbarHelper::title(JText::_('COM_PLUGINS_MANAGER_PLUGINS'), 'power-cord plugin');
        if ($canDo->get('core.create'))
        {
            // Instantiate a new JLayoutFile instance and render the layout
            $layout = new JLayoutFile('toolbar.newplugin');

            $bar->appendButton('Custom', $layout->render(array()), 'new');
        }
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('plugin.edit');
		}
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::custom('plugins.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('plugins.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('plugins.unpublish', 'JTOOLBAR_DISABLE', true);
            if($supperAdmin) {
                JToolbarHelper::publish('plugins.issystem', 'JTOOLBAR_IS_SYSTEM', true);
                JToolbarHelper::unpublish('plugins.isnotsystem', 'JTOOLBAR_IS_NOT_SYSTEM', true);
            }
			JToolbarHelper::checkin('plugins.checkin');
		}
        if ($this->state->get('filter.published') == -2)
        {
            JToolbarHelper::deleteList('', 'plugins.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('plugins.trash');
        }

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_plugins');
		}

		JToolbarHelper::help('JHELP_EXTENSIONS_PLUGIN_MANAGER');

		JHtmlSidebar::setAction('index.php?option=com_plugins&view=plugins');


        if($supperAdmin){
            $option1=new stdClass();
            $option1->id=-1;
            $option1->title="Run for all";
            $listWebsite1[]=$option1;
            $option1=new stdClass();
            $option1->id=-0;
            $option1->title="None";
            $listWebsite1[]=$option1;
            $listWebsite2= websiteHelperFrontEnd::getWebsites();
            $listWebsite=array_merge($listWebsite1,$listWebsite2);
            JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_WEBSITE'),
                'filter_website_id',
                JHtml::_('select.options',$listWebsite, 'id', 'title', $this->state->get('filter.website_id'))
            );
        }

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
				'folder' => JText::_('COM_PLUGINS_FOLDER_HEADING'),
				'element' => JText::_('COM_PLUGINS_ELEMENT_HEADING'),
				'access' => JText::_('JGRID_HEADING_ACCESS'),
				'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
