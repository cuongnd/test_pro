<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit an product.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_product
 * @since       1.6
 */
class phpMyAdminViewDataSources extends JViewLegacy
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$layout = JRequest::getVar('layout');
		$tpl = JRequest::getVar('tpl');
		$this->setLayout($layout);
		switch ($tpl) {
			case "loaddatasources":
				parent::display($tpl);
				return;
				break;

		}

		parent::display($tpl);

	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
}
