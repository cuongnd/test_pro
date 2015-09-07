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
class SocialUserAppFriends extends SocialAppItem
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
	 * Notification triggered when generating notification item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableNotification	The notification table object
	 * @return	null
	 */
	public function onNotificationLoad( &$item )
	{
		switch( $item->cmd )
		{
			case 'approved':

				// Retrieve the target user.
				$user			= Foundry::user( $item->uid );
				$item->title 	= JText::sprintf( 'APP_FRIENDS_NOTIFICATION_APPROVED' , $user->getName() );

				break;
		}
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
		if( $item->context != 'friends' )
		{
			return;
		}

		// Get the context id.
		// $id 		= $item->contextId;

		// Get the actor
		$actor 		= $item->actor;

		// Set the actor for the themes.
		$this->set( 'actor' , $actor );

		// no target. this could be data error. ignore this item.
		if(! $item->targets )
			return;

		// Receiving actor.
		$target		= $item->targets[0]; //Foundry::user( $id );

		// Set the target.
		$this->set( 'target'	, $target );

		// If the current viewer is part of this stream, it should contain "You"
		$my = Foundry::user();

		$me 		= '';
		$this->set( 'me' , $me );
		// User A made friends with user B
		if( $item->verb == 'add' )
		{
			$item->display	= SOCIAL_STREAM_DISPLAY_MINI;
			$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		}

		if( $includePrivacy )
		{
			$privacy	= Foundry::privacy( $my->id );
			$item->privacy 	= $privacy->form( $item->contextId , 'friends', $item->actor->id, 'core.view' );
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
		if( $item->context != 'friends' )
		{
			return;
		}

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		if( $includePrivacy )
		{
			if(! $privacy->validate( 'core.view', $item->contextId, 'friends', $item->actor->id ) )
			{
				return;
			}
		}

		// Get the context id.
		$id 		= $item->contextId;

		// Get the actor
		$actor 		= $item->actor;

		// no target. this could be data error. ignore this item.
		if(! $item->targets )
			return;

		// Receiving actor.
		$target		= $item->targets[0];

		// Get the current view.
		$view 		= JRequest::getVar( 'view' );

		// Get the current id.
		$id 		= JRequest::getInt( 'id' );

		// If the current viewer is part of this stream, it should contain "You"
		$me 		= false;

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

		// If the current viewer is the target, always shift the viewer as the actor
		if( isset($target) && isset($target->id) && $target->id == $my->id )
		{
			$target 	= $actor;
			$actor 		= $my;
		}

		if( isset($actor) && isset($actor->id) )
		{
			$me 		= $actor->id == $my->id ? true : $me;
		}

		// If a user's profile is being viewed, we don't want to show "You"
		if( $view == 'profile' )
		{
			$me 	= false;
		}


		$this->set( 'actor' 	, $actor );
		$this->set( 'target'	, $target );
		$this->set( 'me' 		, $me );

		// User A made friends with user B
		if( $item->verb == 'add' )
		{
			$item->display	= ( $item->display ) ? $item->display :  SOCIAL_STREAM_DISPLAY_MINI;
			$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		}

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $item->contextId , 'friends', $item->actor->id, 'core.view' );
		}

		return true;
	}

	/**
	 * Processes a saved story so that we can notify users who are tagged in the system
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onAfterStorySave( &$stream , $streamItem , $streamTemplate )
	{
		// If there's no "with" data, skip this.
		if( !$streamTemplate->with )
		{
			return;
		}

		// Get list of users that are tagged in this post.
		$taggedUsers 	= $streamTemplate->with;

		// Get the creator of this update
		$poster 		= Foundry::user( $streamTemplate->actor_id );

		// Get the permalink to the stream item
		$permalink 		= $streamItem->getPermalink();

		// Get the content of the stream item.
		$content 		= $streamTemplate->content;

		foreach( $taggedUsers as $id )
		{
			$taggedUser 	= Foundry::user( $id );

			// Set the email title
			$emailTitle		= JText::sprintf( 'COM_EASYSOCIAL_EMAILS_TITLE_TAGGED_IN_POST' , $poster->getName() );

			// Determine if this user is involved in this or the user created this item.
			$mailParams 	= array();

			$mailParams[ 'posterName' ]		= $poster->getName();
			$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_LARGE );
			$mailParams[ 'posterLink' ]		= $poster->getPermalink();
			$mailParams[ 'permalink' ]		= $permalink;
			$mailParams[ 'content' ]		= $content;

			$systemOptions 	= array(
									'type' 			=> 'stream',
									'context_type'	=> 'tagged', 
									'url' 			=> $permalink, 
									'actor_id' 		=> $poster->id, 
									'uid' 			=> $streamItem->id,
									'aggregate' 	=> false, 
								);

			// Add new notification item
			$state 			= Foundry::notify(	'stream.tagged' ,  array( $taggedUser->id ) , array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/stream/tagged' ), $systemOptions );
		}

		return true;
	}

	/**
	 * Displays the friend mentions link in the story form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareStoryPanel($story)
	{
		$config	= Foundry::config();

		if (!$config->get('story.friends_enabled')) return;

		$panel = $story->createPlugin('friends', 'panel');

		// Panel button & content
		$theme = Foundry::get('Themes');
		$panel->button->html = $theme->loadTemplate('themes:/apps/user/friends/story.panel.button');
		$panel->content->html = $theme->loadTemplate('themes:/apps/user/friends/story.panel.content');

		// Panel script
		$script = Foundry::get('Script');
		$panel->script = $script->output('apps:/user/friends/story');

		return $panel;
	}
}
