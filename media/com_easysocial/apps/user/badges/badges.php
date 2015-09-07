<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Friends application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppBadges extends SocialAppItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
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
		if( $item->context != 'badges' )
		{
			return;
		}

		// Get the context id.
		$id 		= $item->contextId;

		// Get the actor
		$actor 		= $item->actor;

		// Get the badge
		$badge 		= Foundry::table( 'Badge' );
		$badge->load( $id );

		$this->set( 'badge' , $badge );
		$this->set( 'actor' , $actor );


		$item->title 	= parent::display( 'logs/' . $item->verb );

		if( $includePrivacy )
		{
			$my         = Foundry::user();
			$privacy	= Foundry::privacy( $my->id );

			// item->uid is now streamitem.id
			$item->privacy 	= $privacy->form( $item->uid , SOCIAL_TYPE_ACTIVITY, $item->actor->id, 'core.view' );
		}

		return true;
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
		if( $item->context != 'badges' )
		{
			return;
		}

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $item->uid , SOCIAL_TYPE_STREAM );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments		= Foundry::comments( $item->uid , SOCIAL_TYPE_STREAM , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::stream( array( 'layout' => 'item', 'id' => $item->uid ) ) ) );
		$item->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;


		$tbl = Foundry::table( 'StreamItem' );
		$tbl->load( array('uid' => $item->uid ) );
		$uid = $tbl->id;

		if( $includePrivacy )
		{
			if(! $privacy->validate( 'core.view', $uid, SOCIAL_TYPE_ACTIVITY, $item->actor->id ) )
			{
				return;
			}
		}

		// Get the context id.
		$id 		= $item->contextId;

		// Get the actor
		$actor 		= $item->actor;

		// Get the badge
		$badge 		= Foundry::table( 'Badge' );
		$badge->load( $id );

		$this->set( 'badge' , $badge );
		$this->set( 'actor' , $actor );

		// Set the display mode to be full.
		$item->display	= SOCIAL_STREAM_DISPLAY_FULL;

		$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content	= parent::display( 'streams/' . $item->verb . '.content' );

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $uid , SOCIAL_TYPE_ACTIVITY, $item->actor->id, 'core.view' );
		}


		return true;
	}
}
