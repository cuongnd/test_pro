<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

include_once __DIR__ . '/../default/view.php';

/**
 * Extension Manager Manage View
 *
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 * @since       1.6
 */
class InstallerViewManage extends InstallerViewDefault
{
	protected $items;

	protected $pagination;

	protected $form;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template
	 *
	 * @return  mixed|void
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		// Get data from the model
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

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
				JText::_('COM_INSTALLER_MSG_MANAGE_NOEXTENSION'),
				'warning'
			);
		}
		$this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('manage.quick_assign_website');
		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		// Display the view
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$supperAdmin=JFactory::isSupperAdmin();
		$canDo	= JHelperContent::getActions('com_installer');
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

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('manage.publish', 'JTOOLBAR_ENABLE', true);
			JToolbarHelper::unpublish('manage.unpublish', 'JTOOLBAR_DISABLE', true);
			JToolbarHelper::divider();
		}
		JToolbarHelper::custom('manage.refresh', 'refresh', 'refresh', 'JTOOLBAR_REFRESH_CACHE', true);
		JToolbarHelper::divider();
		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'manage.remove', 'JTOOLBAR_UNINSTALL');
			JToolbarHelper::divider();
		}

		JToolbarHelper::help('JHELP_EXTENSIONS_EXTENSION_MANAGER_MANAGE');

		JHtmlSidebar::setAction('index.php?option=com_installer&view=manage');

		JHtmlSidebar::addFilter(
			JText::_('COM_INSTALLER_VALUE_CLIENT_SELECT'),
			'filter_client_id',
			JHtml::_('select.options', array('0' => 'JSITE', '1' => 'JADMINISTRATOR'), 'value', 'text', $this->state->get('filter.client_id'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('COM_INSTALLER_VALUE_STATE_SELECT'),
			'filter_status',
			JHtml::_('select.options', array('0' => 'JDISABLED', '1' => 'JENABLED', '2' => 'JPROTECTED', '3' => 'JUNPROTECTED'), 'value', 'text', $this->state->get('filter.status'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('COM_INSTALLER_VALUE_TYPE_SELECT'),
			'filter_type',
			JHtml::_('select.options', InstallerHelper::getExtensionTypes(), 'value', 'text', $this->state->get('filter.type'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('COM_INSTALLER_VALUE_FOLDER_SELECT'),
			'filter_group',
			JHtml::_('select.options', array_merge(InstallerHelper::getExtensionGroupes(), array('*' => JText::_('COM_INSTALLER_VALUE_FOLDER_NONAPPLICABLE'))), 'value', 'text', $this->state->get('filter.group'), true)
		);

		parent::addToolbar();
	}
}
