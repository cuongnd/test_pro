<?php
return;
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 4/20/2015
 * Time: 2:48 PM
 */
defined('_JEXEC') or die;
//Include the syndicate functions only once
require_once __DIR__ . '/helper.php';
$tabs	= $params->get('tabs');
$background_tabs	= $params->get('background_tabs');
$color_tabs	= $params->get('color_tabs');
$background_content	= $params->get('background_content');
$active	= $params->get('active');

require JModuleHelper::getLayoutPath('mod_nav_tabs', $params->get('layout', 'default'));

?>