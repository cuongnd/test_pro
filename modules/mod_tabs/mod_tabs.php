<?php
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';
$contentInTabs=ModTabsHelperFrontEnd::getContentInTab();
require JModuleHelper::getLayoutPath('mod_tabs', $params->get('layout', 'default'));

?>