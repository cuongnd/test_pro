<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The HTML Menus Menu Menus View.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusViewMenus extends JViewLegacy
{
	protected $items;

	protected $modules;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$layout=$this->getLayout();
		if($layout=='ajaxloader')
		{
			parent::display($tpl);
			return;
		}
		$this->items		= $this->get('Items');
		$this->modules		= $this->get('Modules');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		require_once JPATH_ROOT.'/components/com_menus/helpers/menus.php';
		MenusHelperFrontEnd::addSubmenu('menus');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
        $this->listWebsite=websiteHelperFrontEnd::getOptionListWebsite('menus.quick_assign_website');

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{

		$canDo	= JHelperContent::getActions('com_menus');
		require_once JPATH_ROOT.'/includes/toolbar.php';
		JToolbarHelperFrontEnd::title(JText::_('COM_MENUS_VIEW_MENUS_TITLE'), 'list menumgr');

		if ($canDo->get('core.create'))
		{
			JToolbarHelperFrontEnd::addNew('menu.add');
		}
		if ($canDo->get('core.edit'))
		{
			JToolbarHelperFrontEnd::editList('menu.edit');
		}
		if ($canDo->get('core.delete'))
		{
			JToolbarHelperFrontEnd::divider();
			JToolbarHelperFrontEnd::deleteList('', 'menus.delete');
		}

		JToolbarHelperFrontEnd::custom('menus.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', true);
		if ($canDo->get('core.admin'))
		{
			JToolbarHelperFrontEnd::divider();
			JToolbarHelperFrontEnd::preferences('com_menus');
		}
		JToolbarHelperFrontEnd::divider();
		JToolbarHelperFrontEnd::help('JHELP_MENUS_MENU_MANAGER');
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
	}
}
