<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of listtemplate.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.5
 */
class websitetemplateproViewlisttemplate extends JViewLegacy
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
        $this->command='com_websitetemplatepro';
        $this->controller_task='listtemplate.ajaxSaveForm';
    }
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_ROOT.DS.'components/website/website_websitetemplatepro/com_websitetemplatepro/helpers/listtemplatecategory.php';
		$canDo = listtemplatecategoryHelper::getActions('com_websitetemplatepro');
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('List website template'), 'power-cord component');
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('listtemplate.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('listtemplate.unpublish', 'JTOOLBAR_DISABLE', true);
		}
        JToolbarHelper::publish('listtemplate.enable_clone_website', 'Enable clone website', true);
        JToolbarHelper::unpublish('listtemplate.disable_clone_website', 'Disable clone website', true);
        JToolbarHelper::custom('listtemplate.asign_vm_product','','','Asign vm product',true);
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_websitetemplatepro');
		}

		JToolbarHelper::help('JHELP_listtemplate_component_MANAGER');

		JHtmlSidebar::setAction('index.php?option=com_websitetemplatepro&view=websitetemplatepro');

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
