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
 * The HTML Menus Menu Item View.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusViewItem extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $modules;

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
			case "config":
				parent::display($tpl);
				return;
				break;

		}
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');

		$this->modules	= $this->get('Modules');
		$this->state	= $this->get('State');
		$this->canDo	= JHelperContent::getActions('com_menus');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= $this->canDo;
		require_once JPATH_ROOT.'/includes/toolbar.php';
		JToolbarHelperFrontEnd::title(JText::_($isNew ? 'COM_MENUS_VIEW_NEW_ITEM_TITLE' : 'COM_MENUS_VIEW_EDIT_ITEM_TITLE'), 'list menu-add');

		// If a new item, can save the item.  Allow users with edit permissions to apply changes to prevent returning to grid.
		if ($isNew && $canDo->get('core.create'))
		{
			if ($canDo->get('core.edit'))
			{
				JToolbarHelperFrontEnd::apply('item.apply');
			}
			JToolbarHelperFrontEnd::save('item.save');
		}

		// If not checked out, can save the item.
		if (!$isNew && !$checkedOut && $canDo->get('core.edit'))
		{
			JToolbarHelperFrontEnd::apply('item.apply');
			JToolbarHelperFrontEnd::save('item.save');
		}

		// If the user can create new items, allow them to see Save & New
		if ($canDo->get('core.create'))
		{
			JToolbarHelperFrontEnd::save2new('item.save2new');
		}

		// If an existing item, can save to a copy only if we have create rights.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolbarHelperFrontEnd::save2copy('item.save2copy');
		}

		if ($isNew)
		{
			JToolbarHelperFrontEnd::cancel('item.cancel');
		}
		else
		{
			JToolbarHelperFrontEnd::cancel('item.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelperFrontEnd::divider();

		// Get the help information for the menu item.
		$lang = JFactory::getLanguage();

		$help = $this->get('Help');
		if ($lang->hasKey($help->url))
		{
			$debug = $lang->setDebug(false);
			$url = JText::_($help->url);
			$lang->setDebug($debug);
		}
		else
		{
			$url = $help->url;
		}
		JToolbarHelperFrontEnd::help($help->key, $help->local, $url);
	}
}
