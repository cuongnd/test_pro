<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');
require_once JPATH_ROOT.'/libraries/cms/helper/website.php';
if (!JFactory::getUser()->authorise('core.manage', 'com_website'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('websiteHelper', __DIR__ . '/helpers/website.php');

$controller = JControllerLegacy::getInstance('website');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
