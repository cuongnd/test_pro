<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');
$user=JFactory::getUser();
$app=JFactory::getApplication();
if(!$user->id)
{
    $app->enqueueMessage('you must login');
    $app->redirect('index.php?option=com_users&view=login');
}
if (!JFactory::getUser()->authorise('core.manage', 'com_supperadmin'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('supperadmin');

$task=JFactory::getApplication()->input->get('task');

$controller->execute($task);
$controller->redirect();
