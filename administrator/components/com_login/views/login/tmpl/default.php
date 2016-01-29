<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.noframes');

// Get the login modules
// If you want to use a completely different login module change the value of name
// in your layout override.

$loginmodule = LoginModelLogin::getLoginModule('mod_login');
echo JModuleHelper::renderModule($loginmodule, array('style' => 'rounded', 'id' => 'section-box'));


//Get any other modules in the login position.
//If you want to use a different position for the modules, change the name here in your override.
$modules = JModuleHelper::getModules('login');
$show_list_user=true;
if($show_list_user) {
	$website=JFactory::getWebsite();
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('user.*')
		->from('#__users AS user')
		->leftJoin('#__user_usergroup_map AS user_usergroup_map ON user_usergroup_map.user_id=user.id')
		->leftJoin('#__usergroups AS usergroups ON usergroups.id=user_usergroup_map.group_id')
		->where('usergroups.website_id='.(int)$website->website_id)
        ->group('user.id')
        ->order('user.id')
	;
	$list_user=$db->setQuery($query,0,10)->loadObjectList();
/*	echo "<pre>";
	print_r($list_user);
	echo "</pre>";
	die;*/
}

/*echo "<pre>";
print_r($modules);
echo "</pre>";*/
/*die;
foreach ($modules as $module)
// Render the login modules

if ($module->module != 'mod_login'){
	echo JModuleHelper::renderModule($module, array('style' => 'rounded', 'id' => 'section-box'));
}*/
