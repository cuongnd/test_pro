<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app		= JFactory::getApplication();
require_once __DIR__ . '/helper.php';

$list_product_today=mod_templatetoday_helper::get_list_product_today();
require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));
