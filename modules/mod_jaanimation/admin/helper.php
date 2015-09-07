<?php
/**
 * ------------------------------------------------------------------------
 * JA Animation module for Joomla 2.5 & 3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

if (!defined('_JEXEC')) {
    // no direct access
	define('_JEXEC', 1);
	defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
	$path = dirname(dirname(dirname(dirname(__FILE__))));
	define('JPATH_BASE', $path);

	if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
        //Apache CGI
		$_SERVER['PHP_SELF'] = rtrim(dirname(dirname(dirname($_SERVER['PHP_SELF']))), '/\\');
	} else {
        //Others
		$_SERVER['SCRIPT_NAME'] = rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))), '/\\');
	}

	define('DS', DIRECTORY_SEPARATOR);
	require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
	require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
	JDEBUG ? $_PROFILER->mark('afterLoad') : null;

	/**
	 * CREATE THE APPLICATION
	 *
	 * NOTE :
	 */
	$japp = JFactory::getApplication('administrator');

	/**
	 * INITIALISE THE APPLICATION
	 *
	 * NOTE :
	 */
	$japp->initialise();
	$language = JFactory::getLanguage();
	$language->load('mod_jaanimation');
}

$user = JFactory::getUser();

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');


if(!$user->authorise('core.manage', 'com_modules')){
    die(json_encode(array(JText::_('NO_PERMISSION'))));
}

$helpcls = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fileconfig.php';
if(file_exists($helpcls))
include_once $helpcls;

$task = isset($_REQUEST['jaction']) ? $_REQUEST['jaction'] : '';

if ($task != '' && method_exists('JAFileConfig', $task)) {
	JAFileConfig::$task();
}