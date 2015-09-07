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
 * Followers application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppFollowers extends SocialAppItem
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
	 * Responsible to generate the activity logs.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
		if( $item->context != SOCIAL_TYPE_FOLLOWERS )
		{
			return;
		}

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );


		// Get the context id.
		$id 		= $item->contextId;

		// Get the target.
		$table 		= Foundry::table( 'Subscription' );
		$table->load( $id );

		// Get the actor
		$actor 		= $item->actor;

		// Receiving actor.
		$target		= Foundry::user( $table->uid );

		// If the current viewer is part of this stream, it should contain "You"
		$me 		= '';

		$me 	= $my->id == $actor->id ? 'actor' : $me;
		$me 	= $my->id == $target->id ? 'target' : $me;

		$this->set( 'actor'		, $actor );
		$this->set( 'target'	, $target );
		$this->set( 'me' 		, $me );

		$item->title 	= parent::display( 'logs/' . $item->verb );

		if( $includePrivacy )
		{
			$stream->privacy 	= $privacy->form( $id , SOCIAL_TYPE_FOLLOWERS, $item->actor->id, 'followers.view' );
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
		if( $item->context != SOCIAL_TYPE_FOLLOWERS )
		{
			return;
		}

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		if( $includePrivacy )
		{
			if( !$privacy->validate( 'followers.view', $item->contextId, SOCIAL_TYPE_FOLLOWERS, $item->actor->id ) )
			{
				return;
			}
		}

		// Get the context id.
		$id 		= $item->contextId;

		// Get the target.
		$table 		= Foundry::table( 'Subscription' );
		$table->load( $id );


		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $item->contextId , $item->context );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments		= Foundry::comments( $item->contextId , $item->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::stream( array( 'layout' => 'item', 'id' => $item->contextId ) ) ) );
		$item->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;

		// Get the actor
		$actor 		= $item->actor;

		// Receiving actor.
		$target		= Foundry::user( $table->uid );

		// Get the current view.
		$view 		= JRequest::getVar( 'view' );

		// Get the current id.
		$id 		= JRequest::getInt( 'id' );

		// If the current viewer is part of this stream, it should contain "You"
		$me 		= '';

		$me 	= $my->id == $actor->id ? 'actor' : $me;
		$me 	= $my->id == $target->id ? 'target' : $me;

		// If a user's profile is being viewed, we don't want to show "You"
		if( $view == 'profile' )
		{
			$me 	= '';
		}

		$this->set( 'actor'		, $actor );
		$this->set( 'target'	, $target );
		$this->set( 'me' 		, $me );

		// User A following user B
		if( $item->verb == 'follow' )
		{
			$item->display	= ( $item->display ) ? $item->display :  SOCIAL_STREAM_DISPLAY_MINI;
			$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		}

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $id , SOCIAL_TYPE_FOLLOWERS, $item->actor->id, 'followers.view' );
		}

		return true;
	}
}
