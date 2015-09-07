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
require_once JPATH_ROOT.'/administrator/components/com_product/helpers/products.php';
productsHelper::createTableProduct();

if (!JFactory::getUser()->authorise('core.manage', 'com_product'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('productHelper', __DIR__ . '/helpers/product.php');

$controller = JControllerLegacy::getInstance('product');

$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
