<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit an website.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_website
 * @since       1.6
 */
class websiteViewDesign extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		parent::display($tpl);
	}

    public function  parentDisPlay($tpl)
    {
        parent::display($tpl);
    }


}
