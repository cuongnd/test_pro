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

Foundry::import( 'admin:/includes/themes/themes' );
Foundry::import( 'admin:/includes/apps/apps' );

class SocialUserAppShares extends SocialAppItem
{
	public function __construct()
	{
	    // Foundry::get( 'Language' )->load( 'app_albums' , JPATH_ROOT );
		parent::__construct();
	}

	/**
	 * Responsible to generate the activity contents.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
		if( $item->context != 'shares' )
		{
			return;
		}

		// Get the context id.
		$id 		= $item->contextId;

		// Get the actor
		$actor 		= $item->actor;

		// Set the actor for the themes.
		$this->set( 'actor' , $actor );


		// Load the profiles table.
		$share	= Foundry::table( 'Share' );
		$state 	= $share->load( $id );

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'Share::onPrepareActivity : Unable to load share item with the id of ' . $id );
			return false;
		}


		$source 	= explode( '.', $share->element );
		$element 	= $source[0];
		$group 		= $source[1];

		$config 	= Foundry::config();
		$file 		= dirname( __FILE__ ) . '/helpers/'.$element.'.php';

		if( JFile::exists( $file ) )
		{
			require_once( $file );

			// Get class name.
			$className 	= 'SocialSharesHelper' . ucfirst( $element );

			// Instantiate the helper object.
			$helper		= new $className( $item, $share );

			$item->content 	= $helper->getContent();
			$item->title 	= $helper->getTitle();

		}

		$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
	}



	/**
	 * Responsible to generate the stream contents.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		// Only process this if the stream type is shares
		if( $item->context != 'shares' )
		{
			return;
		}

		// Get the single context id
		$id 		= $item->contextId;

		// We only need the single actor.
		// Load the profiles table.
		$share	= Foundry::table( 'Share' );
		$share->load( $id );

		if( ! $share->id )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'Share::onPrepareStream : Unable to load share item with the id of ' . $id );
			return false;
		}

		$my         = Foundry::user();
		$source 	= explode( '.', $share->element );

		$element 	= $source[0];
		$group 		= $source[1];

		$allowed 	= array( 'albums' , 'photos' , 'stream' );

		if( !in_array( $element , $allowed ) )
		{
			return;
		}

		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $item->contextId , $item->context );
		$item->likes	= $likes;

		// Apply comments on the stream
		$link = '';
		if( $element === 'stream' )
		{
			$link = FRoute::stream( array( 'layout' => 'item', 'id' => $id ) );
		}
		if( $element === 'albums' )
		{
			$link = FRoute::albums( array( 'id' => $id ) );
		}
		if( $element === 'photos' )
		{
			$link = FRoute::photos( array( 'id' => $id ) );
		}
		$comments		= Foundry::comments( $item->contextId , $item->context , SOCIAL_APPS_GROUP_USER , array( 'url' => $link ) );
		$item->comments 	= $comments;

		// share app doesnt allow to repost itself.
		$item->repost	= false;

		$file 		= dirname( __FILE__ ) . '/helpers/'.$element.'.php';
		require_once( $file );

		$this->set( 'actor'		, $item->actor );

		// Get class name.
		$className 	= 'SocialSharesHelper' . ucfirst( $element );

		// Instantiate the helper object.
		$helper			= new $className( $item, $share );

		$item->content 	= $helper->getContent();
		if( $item->content === false )
		{
			//when its a false, mean the privacy retriction.
			return;
		}

		$item->title 	= $helper->getTitle();

		// Set stream display mode.
		$item->display	= ( $item->display ) ? $item->display : SOCIAL_STREAM_DISPLAY_FULL;
	}

}
