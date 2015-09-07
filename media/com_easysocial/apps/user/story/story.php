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

class SocialUserAppStory extends SocialAppItem
{
	/**
	 * event onLiked on story
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

		if( $element != 'story' )
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
				$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_SQUARE );
				$mailParams[ 'posterLink' ]		= $poster->getPermalink();
				$mailParams[ 'permalink' ]		= FRoute::stream( array( 'id' => $stream->id , 'layout' => 'item' ) );


				$systemTitle = ( $recipientId == $stream->actor_id ) ? JText::sprintf( 'COM_EASYSOCIAL_SYSTEM_STORY_LIKE_ITEM' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_SYSTEM_STORY_LIKE_INVOLVED', $poster->getName() );

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
	 * Triggered before comments notify subscribers
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableComments	The comment object
	 * @return
	 */
	public function onAfterCommentSave( &$comment )
	{
		if( $comment->element != 'story.user' )
		{
			return;
		}

		$element 	= explode( '.' , $comment->element );
		$element 	= $element[ 0 ];

		if( $element == SOCIAL_TYPE_STORY )
		{
			// Determine the owner of this commented item.
			$model 			= Foundry::model( 'Comments' );
			$recipients 	= $model->getParticipants( $comment->uid , $comment->element );

			// Add the story owner.
			$stream 		= Foundry::table( 'Stream' );
			$stream->load( array( 'id' => $comment->uid ) );

			// Get the list of recipients
			$recipients 	= array_merge( $recipients , array( $stream->actor_id ) );

			// If there's no recipients, forget about it.
			if( !$recipients )
			{
				return;
			}

			// Remove the current user from the list since it doesn't make sense to notify himself if he posted a new comment
			for( $i = 0; $i <= count( $recipients ); $i++ )
			{
				if( $recipients[ $i ] == $comment->created_by )
				{
					unset( $recipients[ $i ] );
				}
			}

			// Reset the values
			$recipients 	= array_values( $recipients );

			$poster 		= Foundry::user( $comment->created_by );

			foreach( $recipients as $recipient )
			{
				$mailParams 	= array();

				$mailTemplate 	= $recipient == $stream->actor_id ? 'new.comment.story.owner' : 'new.comment.story.involved';
				$emailTitle 	= $recipient == $stream->actor_id ? JText::sprintf( 'COM_EASYSOCIAL_EMAILS_COMMENTS_ITEM_EMAIL_TITLE' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_EMAILS_COMMENTS_INVOLVED_EMAIL_TITLE' , $poster->getName() );
				$mailParams[ 'posterName' ]		= $poster->getName();
				$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_SQUARE );
				$mailParams[ 'posterLink' ]		= $poster->getPermalink();
				$mailParams[ 'comment' ]		= $comment->comment;
				$mailParams[ 'permalink' ]		= FRoute::stream( array( 'id' => $stream->id , 'layout' => 'item' ) );

				// Determine if this user is involved in this or the user created this item.
				$rule 	= $recipient == $stream->actor_id ? 'comments.item' : 'comments.involved';

				$state 	= Foundry::notify( $rule , array( $recipient ),
											array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/comments/' . $mailTemplate , ''),
											array( 'title' => $comment->comment , 'context_type' => $element , 'url' => $stream->getPermalink( false ), 'actor_id' => $comment->created_by , 'uid' => $comment->uid , 'aggregate' => true )
										);
			}
		}

	}

	/**
	 * Renders the notification item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onNotificationLoad( &$item )
	{
		// Process links
		if( $item->context_type == 'links' )
		{
			$this->processLinksNotifications( $item );
		}

		// Process story photos
		if( $item->context_type == 'photos' )
		{
			$this->processPhotosNotifications( $item );
		}

		if( $item->context_type != SOCIAL_TYPE_STORY )
		{
			return;
		}

		// Process likes notifications
		if( $item->type == 'likes' )
		{
			// Get the stream based on the UID of the notification item.
			$stream 	= Foundry::table( 'Stream' );
			$stream->load( array( 'id' => $item->uid ) );

			// Load up likes model
			$model 			= Foundry::model( 'Likes' );

			// Get list of users who liked this story.
			$users 			= $model->getLikerIds( $item->uid , 'story.user' );

			// We want to format the content so that it's suitable to display in the notifications area.
			$content 		= $stream->content;
			$content 		= JString::substr( strip_tags( $stream->content ) , 0 , 15 ) . JText::_( 'COM_EASYSOCIAL_ELLIPSES' );

			// Exclude the stream creator and the current logged in user from the list.
			if( $users )
			{
				for($i = 0; $i < count( $users ); $i++ )
				{
					if( $users[ $i ] == Foundry::user()->id )
					{
						unset( $users[ $i ] );
					}
				}

				$users 	= array_values( $users );
			}

			// We need to determine if this is singular or not
			$usePlural	 	= false;
			$total 			= count( $users );

			// Singular items
			if( $total == 1 )
			{
				$user 	= Foundry::user( $users[ 0 ] );

				if( !$user->isViewer() )
				{
					$usePlural 	= true;
				}
			}
			else
			{
				$usePlural 	= false;
			}

			// Convert the names to stream-ish
			$names 			= Foundry::string()->namesToStream( $users , false , 3 , true , false );

			// We need to generate the notification message differently for the author of the item and the recipients of the item.
			if( $stream->actor_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
			{
				// This is for 3rd party viewers
				if( $usePlural )
				{
					$item->title 	= JText::sprintf( "APP_STORY_LIKES_YOUR_STATUS_PLURAL" , $names , $stream->content );
				}
				else
				{
					$item->title 	= JText::sprintf( "APP_STORY_LIKES_YOUR_STATUS" , $names , $stream->content );
				}

				return $item;
			}


			// This is for 3rd party viewers
			if( $usePlural )
			{
				$item->title 	= JText::sprintf( 'APP_STORY_LIKES_USER_STATUS_PLURAL' , $names , Foundry::user( $stream->actor_id )->getName() , $stream->content );
			}
			else
			{
				$item->title 	= JText::sprintf( "APP_STORY_LIKES_USER_STATUS" , $names , Foundry::user( $stream->actor_id )->getName() , $stream->content );
			}

			return $item;
		}

		// Process comment notifications
		if( $item->type == 'comments' )
		{
			// Get the stream based on the UID of the notification item.
			$stream 	= Foundry::table( 'Stream' );
			$stream->load( array( 'id' => $item->uid ) );

			$model 		= Foundry::model( 'Comments' );
			$users 		= $model->getParticipants( $item->uid , 'story.user' );

			// Include the actor of the stream item as the recipient
			$users 		= array_merge( array( $item->actor_id ) , $users );

			// Ensure that the values are unique
			$users		= array_unique( $users );
			$users 		= array_values( $users );

			// Exclude myself from the list of users.
			$index 		= array_search( Foundry::user()->id , $users );

			if( $index !== false )
			{
				unset( $users[ $index ] );

				$users 	= array_values( $users );
			}

			// Only show the content when there is only 1 item
			if( count( $users ) == 1 )
			{
				// We want to format the content so that it's suitable to display in the notifications area.
				$content 		= $item->title;
				$content 		= JString::substr( strip_tags( $content ) , 0 , 30 );

				if( JString::strlen( $item->title ) > 30 )
				{
					$content .= JText::_( 'COM_EASYSOCIAL_ELLIPSES' );
				}
			}
			else
			{
				$content 	= '';
			}


			// Convert the names to stream-ish
			$names 			= Foundry::string()->namesToStream( $users , false , 3 , true , false );

			// We need to generate the notification message differently for the author of the item and the recipients of the item.
			if( $stream->actor_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
			{
				if( $content )
				{
					$item->title 	= JText::sprintf( "APP_STORY_COMMENTS_YOUR_STATUS_CONTENT" , $names , $content );
				}
				else
				{
					$item->title 	= JText::sprintf( "APP_STORY_COMMENTS_YOUR_STATUS" , $names );
				}

				return $item;
			}

			// This is for 3rd party viewers
			if( $content )
			{
				$item->title 	= JText::sprintf( "APP_STORY_COMMENTS_USER_STATUS_CONTENT" , $names , Foundry::user( $stream->actor_id )->getName() , $content );
			}
			else
			{
				$item->title 	= JText::sprintf( "APP_STORY_COMMENTS_USER_STATUS" , $names , Foundry::user( $stream->actor_id )->getName() );
			}



			return $item;
		}
	}

	/**
	 * Process notifications for urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function processLinksNotifications( &$item )
	{
		// Get the stream id.
		$streamId 	= $item->uid;

		// We don't want to process notification for likes here.
		if( $item->type == 'likes' )
		{
			return;
		}
		
		// Get the links that are posted for this stream
		$model 		= Foundry::model( 'Stream' );
		$links		= $model->getAssets( $streamId , SOCIAL_TYPE_LINKS );

		if( !isset( $links[ 0 ] ) )
		{
			return;
		}

		// Initialize default values
		$link 	= $links[ 0 ];
		$actor 	= Foundry::user( $item->actor_id );
		$meta 	= Foundry::registry( $link->data );

		$item->title 	= JText::sprintf( 'APP_STORY_POSTED_LINK_ON_YOUR_TIMELINE' , $meta->get( 'link' ) );
	}

	public function processPhotosNotifications( &$item )
	{
		if( $item->context_ids )
		{
			// If this is multiple photos, we just show the last one.
			$ids 	= Foundry::json()->decode( $item->context_ids );
			$id 	= $ids[ count( $ids ) - 1 ];

			$photo 			= Foundry::table( 'Photo' );
			$photo->load( $id );

			$item->image 	= $photo->getSource();

			$actor 			= Foundry::user( $item->actor_id );

			$title 			= JText::sprintf( 'APP_STORY_POSTED_PHOTO_ON_YOUR_TIMELINE' , $actor->getName() );
			if( count( $ids ) > 1 )
			{
				$title 			= JText::sprintf( 'APP_STORY_POSTED_PHOTO_ON_YOUR_TIMELINE_PLURAL' , $actor->getName(), count( $ids ) );
			}

			$item->title 	= $title;

		}

	}

	/**
	 * Prepares the activity log for user's actions
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareActivityLog( SocialStreamItem &$stream, $includePrivacy = true )
	{
		if( $stream->context != 'story')
		{
			return;
		}

		// Stories wouldn't be aggregated
		$actor 		= $stream->actor;
		$target 	= count( $stream->targets ) > 0 ? $stream->targets[0] : '';

		//$stream->title 		= '<a href="#">' . $actor->getName() . '</a> posted a story on profile.';
		$stream->display	= SOCIAL_STREAM_DISPLAY_MINI;

		// @triggers: onPrepareStoryContent
		// Processes any apps to process the content.
		Foundry::apps()->load( SOCIAL_TYPE_USER );

		$args 			= array( &$story , &$stream );
		$dispatcher 	= Foundry::dispatcher();

		$stream->content = Foundry::string()->replaceHyperlinks( $stream->content );

		$result 		= $dispatcher->trigger( SOCIAL_TYPE_USER , 'onPrepareStoryContent' , $args );

		$this->set( 'actor' , $actor );
		$this->set( 'target', $target );
		$this->set( 'stream', $stream );
		$this->set( 'result', $result );


		$stream->title 		= parent::display( 'logs/title.' . $stream->verb );
		$stream->content	= parent::display( 'logs/content.' . $stream->verb );

		if( $includePrivacy )
		{
			$my         = Foundry::user();

			// only activiy log can use stream->uid directly bcos now the uid is holding id from social_stream_item.id;
			$stream->privacy = Foundry::privacy( $my->id )->form( $stream->uid, SOCIAL_TYPE_STORY, $stream->actor->id, 'story.view' );
		}


		return true;
	}


	/**
	 * Triggered to prepare the stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareStream( SocialStreamItem &$stream, $includePrivacy = true )
	{
		// If this is not it's context, we don't want to do anything here.
		if( $stream->context != 'story')
		{
			return;
		}

		$uid		= $stream->uid;
		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		if( $includePrivacy )
		{
			if( !$privacy->validate( 'story.view', $uid , SOCIAL_TYPE_STORY , $stream->actor->id ) )
			{
				return;
			}
		}

		$actor 		= $stream->actor;
		$target 	= count( $stream->targets ) > 0 ? $stream->targets[0] : '';

		$stream->display	= SOCIAL_STREAM_DISPLAY_FULL;

		$content 		= $stream->content;
		
		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $stream->uid , $stream->context );
		$stream->likes	= $likes;

		// Apply comments on the stream
		$comments			= Foundry::comments( $stream->uid , $stream->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::stream( array( 'layout' => 'item', 'id' => $stream->uid ) ) ) );
		$stream->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $stream->uid , SOCIAL_TYPE_STREAM );
		$stream->repost	= $repost;

		$stream->content = $content;


		$this->set( 'actor' , $actor );
		$this->set( 'target', $target );
		$this->set( 'stream', $stream );

		$stream->title 		= parent::display( 'streams/title.' . $stream->verb );
		$stream->content	=  parent::display( 'streams/content.' . $stream->verb );

		if( $includePrivacy )
		{
			$stream->privacy 	= $privacy->form( $uid , SOCIAL_TYPE_STORY, $stream->actor->id, 'story.view' );
		}

		return true;
	}

}
