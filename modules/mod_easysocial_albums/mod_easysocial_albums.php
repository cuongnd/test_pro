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
$config 	= Foundry::config();

// If photos is not enabled, do not display the albums
if( !$config->get( 'photos.enabled' ) )
{
	return;
}

$my 		= Foundry::user();

// Load up the module engine
$modules 	= Foundry::modules( 'mod_easysocial_albums' );

// We need these packages
$modules->addDependency( 'css' , 'javascript' );

// Get the layout to use.
$layout 	= $params->get( 'layout' , 'default' );
$suffix 	= $params->get( 'suffix' , '' );

// module setting
$withCover 	= $params->get( 'withCover' , 0 );
$limit 		= $params->get( 'total' , 6 );


// Retrieve recent albums from the site.
$albumsModel	= Foundry::model( 'Albums' );
$recentAlbums	= $albumsModel->getAlbums('' , '' , array( 'core' => false, 'withCovers' => $withCover, 'limit' => $limit ) );


require( JModuleHelper::getLayoutPath( 'mod_easysocial_albums' , $layout ) );
