<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gianhang
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of gianhang.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_gianhang
 * @since       1.5
 */
class gianhangViewplugins extends JViewLegacy
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
				JText::_('com_gianhang_MSG_MANAGE_NO_gianhang'),
				'warning'
			);
		}

        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('gianhang.quick_assign_website');
		$this->addToolbar();
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_gianhang';
        $this->controller_task='gianhang.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.'/components/com_gianhang/helpers/plugins.php';
		$canDo = JHelperContent::getActions('com_gianhang');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('com_gianhang_MANAGER_gianhang'), 'power-cord component');
        if ($canDo->get('core.create'))
        {
            // Instantiate a new JLayoutFile instance and render the layout
            $layout = new JLayoutFile('toolbar.newcomponent');

            $bar->appendButton('Custom', $layout->render(array()), 'new');
        }
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('component.edit');
		}
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::custom('gianhang.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('gianhang.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('gianhang.unpublish', 'JTOOLBAR_DISABLE', true);
			JToolbarHelper::checkin('gianhang.checkin');
		}
        if ($this->state->get('filter.published') == -2)
        {
            JToolbarHelper::deleteList('', 'gianhang.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('gianhang.trash');
        }
        JToolbarHelper::publish('gianhang.issystem','Is system');
        JToolbarHelper::unpublish('gianhang.isnotsystem','Is system');
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_gianhang');
		}

		JToolbarHelper::help('JHELP_plugins_component_MANAGER');

		JHtmlSidebar::setAction('index.php?option=com_gianhang&view=gianhang');

        $gianhang=JFactory::isgianhang();
        if($gianhang){
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
		JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_enabled',
				JHtml::_('select.options', pluginsHelper::publishedOptions(), 'value', 'text', $this->state->get('filter.enabled'), true)
		);

		JHtmlSidebar::addFilter(
				JText::_('com_gianhang_OPTION_FOLDER'),
				'filter_folder',
				JHtml::_('select.options', pluginsHelper::folderOptions(), 'value', 'text', $this->state->get('filter.folder'))
		);

		JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_ACCESS'),
				'filter_access',
				JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
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
				'folder' => JText::_('com_gianhang_FOLDER_HEADING'),
				'element' => JText::_('com_gianhang_ELEMENT_HEADING'),
				'access' => JText::_('JGRID_HEADING_ACCESS'),
				'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
