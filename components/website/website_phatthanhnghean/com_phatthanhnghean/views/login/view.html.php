<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_products
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a plugin.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_products
 * @since       1.5
 */
class countdownViewLogin extends JViewLegacy
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app=JFactory::getApplication();
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->addToolbar();
		parent::display($tpl);
	}


}
