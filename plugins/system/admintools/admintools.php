<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

// Make sure Admin Tools is installed, otherwise bail out
if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_admintools'))
{
	return;
}

// You can't fix stupid… but you can try working around it
if ((!function_exists('json_encode')) || (!function_exists('json_decode')))
{
	require_once JPATH_ADMINISTRATOR . '/components/com_admintools/helpers/jsonlib.php';
}

// PHP version check
if (defined('PHP_VERSION'))
{
	$version = PHP_VERSION;
}
elseif (function_exists('phpversion'))
{
	$version = phpversion();
}
else
{
	$version = '5.0.0'; // all bets are off!
}
if (!version_compare($version, '5.3.0', '>='))
{
	return;
}

// Joomla! version check
if (version_compare(JVERSION, '2.5', 'lt') && version_compare(JVERSION, '1.6.0', 'ge'))
{
	// Joomla! 1.6.x and 1.7.x: sorry fellas, no go.
	return;
}

// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
	if (function_exists('error_reporting'))
	{
		$oldLevel = error_reporting(0);
	}
	$serverTimezone = @date_default_timezone_get();
	if (empty($serverTimezone) || !is_string($serverTimezone))
	{
		$serverTimezone = 'UTC';
	}
	if (function_exists('error_reporting'))
	{
		error_reporting($oldLevel);
	}
	@date_default_timezone_set($serverTimezone);
}

// Include F0F's loader if required
if (!defined('F0F_INCLUDED'))
{
	$libraries_dir = defined('JPATH_LIBRARIES') ? JPATH_LIBRARIES : JPATH_ROOT . '/libraries';
	$mainFile = $libraries_dir . '/f0f/include.php';

	@include_once $mainFile;
}

// If F0F is not present (e.g. not installed) bail out
if (!defined('F0F_INCLUDED') || !class_exists('F0FLess', true))
{
	return;
}

JLoader::import('joomla.filesystem.file');
$target_include = JPATH_ROOT . '/plugins/system/admintools/admintools/main.php';

if (JFile::exists($target_include))
{
	require_once $target_include;
}
else
{
	$target_include = $target_include = JPATH_ROOT . 'plugins/system/admintools/admintools/main.php';
	if (JFile::exists($target_include))
	{
		require_once $target_include;
	}
}