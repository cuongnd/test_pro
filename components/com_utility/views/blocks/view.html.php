<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Login view class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.5
 */
class UtilityViewblocks extends JViewLegacy
{

	/**
	 * Method to display the view.
	 *
	 * @param   string  The template file to include
	 * @since   1.5
	 */
	public function display($tpl = null)
	{
		$layout = JRequest::getVar('layout');
		$tpl = JRequest::getVar('tpl');
		$this->setLayout($layout);
		switch ($tpl) {
			case "loadelement":
				parent::display($tpl);
				return;
				break;
			case "ajaxloadblocks":
				parent::display($tpl);
				return;
				break;

		}

		$this->htmlAllBlock=$this->get('HtmlAllBlock');
		parent::display($tpl);
	}
}
