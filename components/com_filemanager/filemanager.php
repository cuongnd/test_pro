<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');


JLoader::register('productHelper', __DIR__ . '/helpers/product.php');

$controller = JControllerLegacy::getInstance('filemanager');

$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
