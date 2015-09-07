<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_components
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a component.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_components
 * @since       1.5
 */
class componentsViewcomponent extends JViewLegacy
{
	protected $item;

	protected $form;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');

		$this->item		= $this->get('Item');

		$this->form		= $this->get('Form');
        // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
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
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$canDo = JHelperContent::getActions('com_components');

		JToolbarHelper::title(JText::sprintf('COM_COMPONENTS_MANAGER_component', JText::_($this->item->name)), 'power-cord component');

		// If not checked out, can save the item.
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::apply('component.apply');
			JToolbarHelper::save('component.save');
		}
		JToolbarHelper::cancel('component.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::divider();
		// Get the help information for the component item.

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
			$url = null;
		}
		JToolbarHelper::help($help->key, false, $url);
	}
}
