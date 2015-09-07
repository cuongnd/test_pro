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

// Include main engine
$file 	= JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';

jimport( 'joomla.filesystem.file' );

if( !JFile::exists( $file ) )
{
	return;
}

// Include the engine file.
require_once( $file );

// Check if Foundry exists
if( !Foundry::exists() )
{
	Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
	echo JText::_( 'COM_EASYSOCIAL_FOUNDRY_DEPENDENCY_MISSING' );
	return;
}

$my 		= Foundry::user();

// Load up the module engine
$modules 	= Foundry::modules( 'mod_easysocial_users' );

// We need these packages
$modules->loadComponentScripts();
$modules->loadComponentStylesheets();
$modules->addDependency( 'css' , 'javascript' );

// Get the layout to use.
$layout 	= $params->get( 'layout' , 'default' );
$suffix 	= $params->get( 'suffix' , '' );

// Get the layout to use.
$model 		= Foundry::model( 'Users' );
$options 	= array( 'ordering' => 'a.' . $params->get( 'ordering' , 'registerDate' ) , 'direction' => $params->get( 'direction' , 'desc' ) , 'limit' => $params->get( 'total' , 10 ) );

// Check filter type
if( $params->get( 'filter' , 'recent' ) == 'online' )
{
	$options[ 'login' ]	= true;
	$options[ 'frontend' ] = true;
}


// Determine if admins should be included in the user's listings. 
$config 	= Foundry::config();
$admin 		= $config->get( 'users.listings.admin' );

$options[ 'includeAdmin' ]	= $admin ? true : false;


// we only want published user.
$options[ 'published' ]	= 1;

$result 	= $model->getUsers( $options );
$users		= array();

if( !$result )
{
	return;
}

if( $result )
{
	foreach( $result as $row )
	{
		$users[]	= Foundry::user( $row->id );
	}
}

require( JModuleHelper::getLayoutPath( 'mod_easysocial_users' , $layout ) );
