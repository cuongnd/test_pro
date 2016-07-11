<?php
$input=JFactory::getApplication()->input;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_products
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_phatthanhnghean')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once JPATH_ROOT.'/components/website/website_phatthanhnghean/com_phatthanhnghean/helpers/phatthanhngheanconfig.php';
$controller	= JControllerLegacy::getInstance('phatthanhnghean');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

