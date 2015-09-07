<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of modules.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 * @since       1.6
 */
class ModulesViewModules extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Check if there are no matching items
		if (!count($this->items)){
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_MODULES_MSG_MANAGE_NO_MODULES'),
				'warning'
			);
		}
        require_once JPATH_ROOT.'/administrator/components/com_website/helpers/website.php';
        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('modules.quick_assign_website');

        $this->addToolbar();
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        $this->addCommand();
		parent::display($tpl);
	}
    function addCommand()
    {
        $this->command='com_modules';
        $this->controller_task='modules.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= JHelperContent::getActions('com_modules');
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_MODULES_MANAGER_MODULES'), 'cube module');

		if ($canDo->get('core.create'))
		{
			// Instantiate a new JLayoutFile instance and render the layout
			$layout = new JLayoutFile('toolbar.newmodule');

			$bar->appendButton('Custom', $layout->render(array()), 'new');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('module.edit');
		}

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::custom('modules.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('modules.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('modules.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::checkin('modules.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'modules.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('modules.trash');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_modules') && $user->authorise('core.edit', 'com_modules') && $user->authorise('core.edit.state', 'com_modules'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_modules');
		}
		JToolbarHelper::help('JHELP_EXTENSIONS_MODULE_MANAGER');

		JHtmlSidebar::addEntry(
			JText::_('JSITE'),
			'index.php?option=com_modules&filter_client_id=0',
			$this->state->get('filter.client_id') == 0
		);

		JHtmlSidebar::addEntry(
			JText::_('JADMINISTRATOR'),
			'index.php?option=com_modules&filter_client_id=1',
			$this->state->get('filter.client_id') == 1
		);

		JHtmlSidebar::setAction('index.php?option=com_modules');

		JHtmlSidebar::addFilter(
			// @todo we need a label for this
			'',
			'filter_client_id',
			JHtml::_('select.options', ModulesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id')),
			false
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', ModulesHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'))
		);
        $supperAdmin=JFactory::isSupperAdmin();


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

		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
		$listScreenSize=UtilityHelper::getListScreenSize();
		$options=array();
		foreach($listScreenSize as $screenSize)
		{
			$options[$screenSize] = $screenSize;

		}
		JHtmlSidebar::addFilter(
			JText::_('select Screen size'),
			'filter_screensize',
			JHTML::_('select.options', $options,   'value', 'text', $this->state->get('filter.screensize'))
		);

		JHtmlSidebar::addFilter(
			JText::_('COM_MODULES_OPTION_SELECT_MODULE'),
			'filter_module',
			JHtml::_('select.options', ModulesHelper::getModules($this->state->get('filter.client_id')), 'value', 'text', $this->state->get('filter.module'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
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
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'position' => JText::_('COM_MODULES_HEADING_POSITION'),
			'name' => JText::_('COM_MODULES_HEADING_MODULE'),
			'pages' => JText::_('COM_MODULES_HEADING_PAGES'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'language_title' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
