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
if( isset( $userid ) )
{
	$title[]	= getUserAlias( $userid );

	shRemoveFromGETVarsList( 'userid' );
}

if( isset( $view ) && !isset( $userid ) )
{
	addView( $title , $view );
}

// URL: /user/achievements
if( isset( $userid ) && isset( $layout ) )
{
	addLayout( $title , $view , $layout );

	shRemoveFromGETVarsList( 'view' );
}

if( isset( $layout ) )
{
	addLayout( $title , $view , $layout );
}

// Determine how is the user's current id being set.
if( isset( $id ) )
{
	$alias 		= getBadgeAlias( $id );
	$title[]	= $alias;

	shRemoveFromGETVarsList( 'id' );
}
