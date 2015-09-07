<?php
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';
$list=ModMenuLeftHelperFrontEnd::getList($params);
require JModuleHelper::getLayoutPath('mod_menu_left', $params->get('layout', 'default'));

?>