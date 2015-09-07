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
 * components master display controller.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_components
 * @since       1.5
 */
class componentsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/components.php';

		// Load the submenu.
		componentsHelper::addSubmenu($this->input->get('view', 'components'));

		$view   = $this->input->get('view', 'components');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'component' && $layout == 'edit' && !$this->checkEditId('com_components.edit.component', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_components&view=components', false));

			return false;
		}

		parent::display();
	}
}
