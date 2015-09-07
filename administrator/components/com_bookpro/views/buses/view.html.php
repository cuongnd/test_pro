<?php
/**
 * @package     Joomla.Administrator
 * @subpackage
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of buses.
 *
 * @package     Joomla.Administrator
 * @subpackage
 * @since       1.6
 */
class BookproViewbuses extends BookproJViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;
	protected $context;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $input=JFactory::getApplication()->input;
		$this->items		= $this->get('Items');
        //echo $this->get('DBO')->getQuery()->dump();
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
				JText::_('COM_buses_MSG_MANAGE_NO_buses'),
				'warning'
			);
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
    {

        $layout = new JLayoutFile('toolbar.newbus');
        $bar = JToolBar::getInstance('toolbar');
        JToolbarHelper::addNew('bus.add');
        $bar->appendButton('Custom', $layout->render(array()), 'new');
        JToolbarHelper::publish('buses.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('buses.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::editList('bus.edit');
        JToolBarHelper::deleteList('', 'buses.delete', 'Delete');
        AImporter::helper('buses');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'bus_filter_state',
            JHtml::_('select.options', busesHelper::getStateOptions(), 'value', 'text', $this->state->get('bus_filter_state'))
        );

        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
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
			'bus.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'bus.published' => JText::_('JSTATUS'),
			'bus.title' => JText::_('Title'),
			'bus.desc' => JText::_('Desc'),
			'bus.id' => JText::_('JGRID_HEADING_ID')
		);
	}

}
