<?php
// namespace administrator\components\com_jchat;
/**
 * Entrypoint dell'application di backend
*
* @package JCHAT::administrator::components::com_jchat
* @author Joomla! Extensions Store
* @copyright (C) 2013 - Joomla! Extensions Store
* @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
*/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
//ini_set('display_errors', false);
//ini_set('error_reporting', E_ERROR);

// Register libraries classes
//JLoader::register('JChatStream', JPATH_COMPONENT_SITE . '/models/stream.php');

/*
 * Controller.task core MVC execute
*/
$controller_command = JRequest::getCmd ( 'task', 'cpanel.display' );
list ( $controller_name, $controller_task ) = explode ( '.', $controller_command );

// Defaults
if (! $controller_name) {
	$controller_name = 'cpanel';
}
if (! $controller_task) {
	$controller_task = 'display';
}

$basepath = JPATH_COMPONENT . '/controllers/jchatcontroller.php';
require_once $basepath;
$path = JPATH_COMPONENT . '/controllers/' . strtolower($controller_name) . '.php';
if (file_exists ( $path )) {
	require_once $path;
} else {
	JError::raiseWarning ( 500, JText::_('ERROR_NO_CONTROLLER_FILE') );
}

// Create the controller
$classname = 'JChatController' . ucfirst ( $controller_name );
if (class_exists ( $classname )) {
	$controller = new $classname ();
	// Perform the Request task
	$controller->execute ( $controller_task );

	// Redirect if set by the controller
	$controller->redirect ();
} else {
	JError::raiseWarning ( 500, JText::_('ERROR_NO_CONTROLLER') );
}