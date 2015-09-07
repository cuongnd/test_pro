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

Foundry::import( 'admin:/includes/apps/apps' );

class SocialUserAppLinks extends SocialAppItem
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * event onLiked on shared link
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onAfterLikeSave( &$likes )
	{
		if( !$likes->type )
		{
			return;
		}

		// Set the default element.
		$element 	= $likes->type;
		$uid 		= $likes->uid;

		if( strpos( $element , '.' ) !== false )
		{
			$data		= explode( '.', $element );
			$group		= $data[1];
			$element	= $data[0];
		}

		if( $element != 'links' )
		{
			return;
		}

		// Get the owner of the post.
		$stream		= Foundry::table( 'Stream' );
		$stream->load( array( 'id' => $uid ) );

		// Get a list of people that also likes this
		$recipients	= Foundry::likes( $uid , $element , $group )->getParticipants( false );

		// Get people who are part of the comments
		$comments		= Foundry::comments( $uid , $element , $group );
		$recipients 	= array_merge( $recipients , $comments->getParticipants( array() , false ) );

		// Notify the actor of the story that someone likes this.
		$recipients[]	= $stream->actor_id;

		// Ensure that recipients are unique now.
		$recipients		= array_unique( $recipients );

		// Reset the indexes
		$recipients 	= array_values( $recipients );

		if( $recipients )
		{
			// Remove the current user from the list since it doesn't make sense to notify himself
			for( $i = 0; $i < count( $recipients ); $i++ )
			{
				if( $recipients[ $i ] == $likes->created_by )
				{
					unset( $recipients[ $i ] );
				}
			}

			// Get the author of the item
			$poster 	= Foundry::user( $likes->created_by );

			foreach( $recipients as $recipientId )
			{
				// Determine if this user is involved in this or the user created this item.
				$rule 	= $recipientId == $stream->actor_id ? 'likes.item' : 'likes.involved';
				$mailParams 	= array();

				$mailTemplate 	= $recipientId == $stream->actor_id ? 'new.likes.story.owner' : 'new.likes.story.involved';
				$emailTitle 	= $recipientId == $stream->actor_id ? JText::sprintf( 'COM_EASYSOCIAL_EMAILS_LIKES_ITEM_EMAIL_TITLE' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_EMAILS_LIKES_INVOLVED_EMAIL_TITLE' , $poster->getName() );
				$mailParams[ 'posterName' ]		= $poster->getName();
				$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_LARGE );
				$mailParams[ 'posterLink' ]		= $poster->getPermalink();
				$mailParams[ 'permalink' ]		= FRoute::stream( array( 'id' => $stream->id , 'layout' => 'item' ) );


				$systemTitle = ( $recipientId == $stream->actor_id ) ? JText::sprintf( 'COM_EASYSOCIAL_SYSTEM_STORY_LINKS_LIKE_ITEM' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_SYSTEM_STORY_LINKS_LIKE_INVOLVED', $poster->getName() );

				// Add new notification item
				$state 			= Foundry::notify(	$rule ,
													array( $recipientId ) ,
													array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/likes/' . $mailTemplate ),
													array( 'title' => $systemTitle, 'context_type' => $element , 'url' => $stream->getPermalink( false ) ,  'actor_id' => $likes->created_by , 'uid' => $uid , 'aggregate' => true )
												);
			}
		}
	}




	/**
	 * Processes a saved story.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onAfterStorySave( &$stream , &$streamItem , &$template )
	{
		// Get the link information from the request
		$link 		= JRequest::getVar( 'links_url' , '' );
		$title 		= JRequest::getVar( 'links_title' , '' );
		$content 	= JRequest::getVar( 'links_description' , '' );
		$image 		= JRequest::getVar( 'links_image' , '' );

		// If there's no data, we don't need to store in the assets table.
		if( empty( $title ) && empty( $content ) && empty( $image ) )
		{
			return;
		}

		$registry		= Foundry::registry();
		$registry->set( 'title'		, $title );
		$registry->set( 'content'	, $content );
		$registry->set( 'image'		, $image );
		$registry->set( 'link'		, $link );

		$assets 			= Foundry::table( 'StreamAsset' );
		$assets->stream_id 	= $streamItem->uid;
		$assets->type 		= 'links';
		$assets->data 		= $registry->toString();

		// Store the assets
		$state 	= $assets->store();

		if( empty( $template->content ) )
		{
			$content 	.= '<br />';	
		}
		
		if( $image )
		{
			$this->set( 'registry' , $registry );
			$template->content 	.= parent::display( 'notifications/email' );
		}
		
		return true;
	}

	/**
	 * Generates the stream title of group.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onPrepareStream( SocialStreamItem &$stream, $includePrivacy = true )
	{
		if( $stream->context != 'links' )
		{
			return;
		}

		//get links object, in this case, is the stream_item
		// $tbl = Foundry::table( 'StreamItem' );
		// $tbl->load( array( 'uid' => $stream->uid ) );
		$uid = $stream->uid;

		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $stream->uid , $stream->context );
		$stream->likes	= $likes;

		// Apply comments on the stream
		$comments		= Foundry::comments( $stream->uid , $stream->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::stream( array( 'layout' => 'item', 'id' => $stream->uid ) ) ) );
		$stream->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $stream->uid , SOCIAL_TYPE_STREAM );
		$stream->repost	= $repost;

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		if( $includePrivacy )
		{
			if( !$privacy->validate( 'story.view', $uid , SOCIAL_TYPE_LINKS , $stream->actor->id ) )
			{
				return;
			}
		}

		$actor 				= $stream->actor;
		$target 			= count( $stream->targets ) > 0 ? $stream->targets[0] : '';

		$stream->display	= SOCIAL_STREAM_DISPLAY_FULL;

		$assets 			= $stream->getAssets();

		if( empty( $assets ) )
		{
			return;
		}

		$assets 	= $assets[ 0 ];


		$this->set( 'assets', $assets );
		$this->set( 'actor' , $actor );
		$this->set( 'target', $target );
		$this->set( 'stream', $stream );

		$stream->title 		= parent::display( 'streams/title.' . $stream->verb );
		$stream->preview	= parent::display( 'streams/preview.' . $stream->verb );

		if( $includePrivacy )
		{
			$stream->privacy 	= $privacy->form( $uid , SOCIAL_TYPE_LINKS, $stream->actor->id, 'story.view' );
		}

		return true;
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
		if( $item->context != 'links' )
		{
			return;
		}

		//get story object, in this case, is the stream_item
		$tbl = Foundry::table( 'StreamItem' );
		$tbl->load( $item->uid ); // item->uid is now streamitem.id

		$uid = $tbl->uid;

		//get story object, in this case, is the stream_item
		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		$actor 				= $item->actor;
		$target 			= count( $item->targets ) > 0 ? $item->targets[0] : '';

		$assets 			= $item->getAssets( $uid );
		if( empty( $assets ) )
		{
			return;
		}

		$assets 	= $assets[ 0 ];

		$this->set( 'assets', $assets );
		$this->set( 'actor' , $actor );
		$this->set( 'target', $target );
		$this->set( 'stream', $item );


		$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
		$item->title	= parent::display( 'logs/' . $item->verb );

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $uid , SOCIAL_TYPE_LINKS, $item->actor->id, 'story.view' );
		}

		return true;

	}

	/**
	 * Prepares what should appear in the story form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareStoryAttachment( $story )
	{
		$plugin = $story->createPlugin('links', 'attachment');

		// Attachment button
		$theme = Foundry::get('Themes');
		$plugin->icon->html = '<i class="ies-link"></i>';
		$plugin->button->html = $theme->output('themes:/apps/user/links/story.attachment.button');
		$plugin->content->html = $theme->output( 'themes:/apps/user/links/story.attachment.content' );

		// Attachment script
		$script = Foundry::get('Script');
		$plugin->script = $script->output('apps:/user/links/story');

		return $plugin;
	}
}
