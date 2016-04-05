<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/route.php';
$controller = JControllerLegacy::getInstance('Users');
$app=JFactory::getApplication();
$task=$app->input->get('task', 'display');

$controller->execute($task);
$controller->redirect();
