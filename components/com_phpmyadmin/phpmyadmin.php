<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;
$controller = JControllerLegacy::getInstance('phpmyadmin');
$input=JFactory::getApplication()->input;
$task=$input->get('task','display','string');
$controller->execute($task);
$controller->redirect();





