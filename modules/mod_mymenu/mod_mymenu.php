<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


// Include the module helper classes.
if (!class_exists('ModMymenuHelper'))
{
	require __DIR__ . '/helper.php';
}


require JModuleHelper::getLayoutPath('mod_mymenu', $params->get('layout', 'default'));  //get layout module
