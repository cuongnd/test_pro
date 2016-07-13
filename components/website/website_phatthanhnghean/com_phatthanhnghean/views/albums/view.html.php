<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_phatthanhnghean
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of albums.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_phatthanhnghean
 * @since       1.5
 */
class phatthanhngheanViewalbums extends JViewLegacy
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
				JText::_('com_phatthanhnghean_MSG_MANAGE_NO_products'),
				'warning'
			);
		}

        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('albums.quick_assign_website');
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
        $this->command='com_phatthanhnghean';
        $this->controller_task='albums.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.'/components/website/website_phatthanhnghean/com_phatthanhnghean/helpers/albums.php';
		$canDo = JHelperContent::getActions('com_phatthanhnghean');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('albums manager'), 'power-cord component');
        $layout = new JLayoutFile('toolbar.newcomponent');

        $bar->appendButton('Custom', $layout->render(array()), 'new');
        JToolbarHelper::editList('album.edit');
        JToolbarHelper::addNew('album.add');
        if ($canDo->get('core.create'))
        {
            // Instantiate a new JLayoutFile instance and render the layout
            $layout = new JLayoutFile('toolbar.newcomponent');

            $bar->appendButton('Custom', $layout->render(array()), 'new');
        }
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('album.edit');
		}
        if ($canDo->get('core.create'))
        {
            JToolbarHelper::custom('albums.add', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }
		JToolbarHelper::duplicate('albums.duplicate');
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('albums.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('albums.unpublish', 'JTOOLBAR_DISABLE', true);
			JToolbarHelper::checkin('albums.checkin');
		}
        if ($this->state->get('filter.published') == -2)
        {
            JToolbarHelper::deleteList('', 'albums.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('albums.trash');
        }
        JToolbarHelper::publish('albums.issystem','Is system');
        JToolbarHelper::unpublish('albums.isnotsystem','Is system');
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_phatthanhnghean');
		}

		JToolbarHelper::help('JHELP_albums_component_MANAGER');



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
