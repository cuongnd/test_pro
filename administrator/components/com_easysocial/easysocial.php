<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Test for installation requests.
jimport( 'joomla.filesystem.folder' );

$setupFolder	= dirname( __FILE__ ) . '/setup';
$folderExists	= ( JFolder::exists( $setupFolder ) && !JFolder::exists( $setupFolder . '/views' ) );
$isInstall		= JRequest::getBool( 'install' ) || JRequest::getBool( 'reinstall' ) || JRequest::getBool( 'update' );

if( $isInstall || !$folderExists )
{
	require_once( dirname( __FILE__ ) . '/setup/bootstrap.php' );
	exit;
}

// Load main engine
require_once( dirname( __FILE__ ) . '/includes/foundry.php' );

// Check if we need to synchronize the database columns
$sync	= JRequest::getBool( 'sync' , false );

if( $sync )
{
	JRequest::setVar( 'task' , 'sync' );
	JRequest::setVar( 'controller' , 'easysocial' );
}

// Check if Foundry exists
if( !Foundry::exists() )
{
	Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
	echo JText::_( 'COM_EASYSOCIAL_FOUNDRY_DEPENDENCY_MISSING' );
	return;
}

// Load language.
Foundry::language()->load( 'com_easysocial' , JPATH_ADMINISTRATOR );

// Start collecting page objects.
Foundry::page()->start();

// @rule: Process AJAX calls
Foundry::ajax()->listen();

// Get the task
$task		= JRequest::getCmd( 'task' , 'display' );

// We treat the view as the controller. Load other controller if there is any.
$controller	= JRequest::getWord( 'controller' , '' );

// We need the base controller
Foundry::import( 'admin:/controllers/controller' );

if( !empty( $controller ) )
{
	$controller	= JString::strtolower( $controller );

	if( !Foundry::import( 'admin:/controllers/' . $controller ) )
	{
		JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_INVALID_CONTROLLER' , $controller ) );
	}
}

$class	= 'EasySocialController' . JString::ucfirst( $controller );

// Test if the object really exists in the current context
if( !class_exists( $class ) )
{
	JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_INVALID_CONTROLLER_CLASS_ERROR' , $class ) );
}

$controller	= new $class();

// Task's are methods of the controller. Perform the Request task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();

// End page
Foundry::page()->end();
