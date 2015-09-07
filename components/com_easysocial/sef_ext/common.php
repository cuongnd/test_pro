<?php
/**
* @package    EasySocial
* @copyright  Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Determine how is the user's current id being set.
function addView( &$title , $view )
{
	$title[]	= JString::ucwords( JText::_( 'COM_EASYSOCIAL_ROUTER_' . strtoupper( $view ) ) );

	shRemoveFromGETVarsList( 'view' );
}

function addLayout( &$title , $view , $layout )
{
	$title[]	= JString::ucwords( JText::_( 'COM_EASYSOCIAL_ROUTER_' . strtoupper( $view ) . '_LAYOUT_' . strtoupper( $layout ) ) );

	shRemoveFromGETVarsList( 'layout' );
}

function stripExtensions( $title )
{
	// Remove known extensions from title
	$extensions = array( 'jpg' , 'png' , 'gif' );

	$title 	= JString::str_ireplace( $extensions , '' , $title );

	return $title;
}

function getAppAlias( $id )
{
	$app 		= Foundry::table( 'App' );
	$app->load( (int) $id );

	$alias 		= JFilterOutput::stringURLSafe( $app->alias );
	return $alias;
}

function getListAlias( $id )
{
	$list 		= Foundry::table( 'List' );
	$list->load( $id );

	$alias 		= JFilterOutput::stringURLSafe( $list->title );
	return $alias;
}

function getBadgeAlias( $id )
{
	$badge 		= Foundry::table( 'Badge' );
	$badge->load( $id );

	$alias 		= JFilterOutput::stringURLSafe( $badge->alias );
	return $alias;
}

function getUserAlias( $id )
{
	static $users 	= array();

	$id = (int) $id;

	if( !isset( $users[ $id ] ) )
	{
		$user 		= Foundry::user( $id );
		$config 	= Foundry::config();
		$alias 		= $user->username;

		if( $config->get( 'users.aliasName' ) == 'realname' )
		{
			$alias	= $user->id . '-' . $user->name;
		}

		if( $user->permalink )
		{
			$alias 	= $user->permalink;
		}

		$users[ $id ]	= $alias;
	}

	return $users[ $id ];
}

function uniqueUrl( $title , $fragment )
{
	$i 	= 1;

	$url 	= implode( '/' , $title ) . '/' . $fragment;

	while( urlExists( $url ) )
	{
		$fragment 	= $fragment . '-' . $i;

		$url 	= $url . $fragment;
		$i++;
	}

	return $fragment;
}

function urlExists( $title )
{
	$url 	= $title;

	if( is_array( $title ) )
	{
		$url 	= implode( '/' , $title );
	}

	$db 	= Foundry::db();
	$sql	= $db->sql();
	$sql->select( '#__sh404sef_urls' );
	$sql->where( 'oldurl' , $url , '=' , 'OR' );
	$sql->where( 'oldurl' , $url . '.html' , '=' , 'OR' );

	$db->setQuery( $sql );

	$exists	= $db->loadResult() > 0 ? true : false;

	return $exists;
}
