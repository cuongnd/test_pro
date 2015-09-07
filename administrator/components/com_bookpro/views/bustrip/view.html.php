<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bustrips
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a bustrip.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bustrips
 * @since       1.6
 */
class BookproViewbustrip extends BookproJViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
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
        JToolbarHelper::apply('bustrip.apply');
        JToolbarHelper::save('bustrip.save');
        JToolbarHelper::cancel('bustrip.cancel');

	}
}
