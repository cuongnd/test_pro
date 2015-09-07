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

/**
 * Albums application for EasySocial
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppAlbums extends SocialAppItem
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Triggers after a like is saved on an album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableLikes
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

		if( $element != SOCIAL_TYPE_ALBUM )
		{
			return;
		}

		// Load up the album object
		$album 	= Foundry::table( 'Album' );
		$album->load( $uid );

		// Default recipients
		$recipients = array();

		// Get a list of likers
		$likers 		= Foundry::likes( $uid , $element , $group )->getParticipants( false );

		// Get a list of commenters
		$commenters 	= Foundry::comments( $uid , $element , $group )->getParticipants( array() , false );

		// Merge the list now
		$recipients		= array_merge( $recipients , $likers , $commenters );

		// Notify the actor of the story that someone likes this.
		$recipients[]	= $album->uid;

		// Ensure that recipients are unique now.
		$recipients		= array_unique( $recipients );

		// Reset the indexes
		$recipients 	= array_values( $recipients );
		$totals 		= count( $recipients );

		// Remove the current user from the list since it doesn't make sense to notify himself
		for( $i = 0; $i < $totals; $i++ )
		{
			if( isset($recipients[ $i ]) && $recipients[ $i ] == $likes->created_by )
			{
				unset( $recipients[ $i ] );
			}
		}

		// Stop if there's nothing to process
		if( !$recipients )
		{
			return;
		}

		// Reset the indexes
		$recipients 	= array_values( $recipients );

		// Load up recipients
		$recipients 	= Foundry::user( $recipients );

		// Get the author of the item
		$poster 	= Foundry::user( $likes->created_by );
		$albumOwner	= Foundry::user( $album->uid );

		foreach( $recipients as $recipient )
		{
			// Determine if this user is involved in this or the user created this item.
			$mailParams 	= array();

			$mailTemplate 	= $recipient->id == $album->uid ? 'new.likes.owner' : 'new.likes.involved';
			$emailTitle 	= $recipient->id == $album->uid ? JText::sprintf( 'COM_EASYSOCIAL_EMAILS_LIKES_ALBUM_OWNER_EMAIL_TITLE' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_EMAILS_LIKES_ALBUM_INVOLVED_EMAIL_TITLE' , $poster->getName() , $albumOwner->getName() );

			$mailParams[ 'posterName' ]		= $poster->getName();
			$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_LARGE );
			$mailParams[ 'posterLink' ]		= $poster->getPermalink();
			$mailParams[ 'albumThumbnail' ]	= $album->getCoverUrl();
			$mailParams[ 'albumPermalink' ]	= $album->getPermalink( false );
			$mailParams[ 'albumTitle' ]		= $album->get( 'title' );
			$mailParams[ 'albumOwner' ]		= $albumOwner->getName();

			$systemOptions 	= array( 'type' => SOCIAL_TYPE_LIKES ,
										'context_type' => $element ,
										'url' => $album->getPermalink( false ) ,
										'actor_id' => $likes->created_by ,
										'uid' => $uid ,
										'aggregate' => true ,
										'image' => $album->getCoverUrl() );

			// Add new notification item
			$state 			= Foundry::notify(	'photos.likes' ,  array( $recipient ) , array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/albums/' . $mailTemplate ), $systemOptions );
		}
	}

	/**
	 * Triggered when a comment save occurs
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableComments	The comment object
	 * @return
	 */
	public function onAfterCommentSave( &$comment )
	{
		$element 	= explode( '.' , $comment->element );
		$element 	= $element[ 0 ];

		// We don't want to modify anything if this wasn't a comment for the photo
		if( $element != SOCIAL_TYPE_ALBUM )
		{
			return;
		}

		// Determine the owner of this commented item.
		$album 		= Foundry::table( 'Album' );
		$album->load( $comment->uid );

		// Default recipients
		$recipients = array();

		// Add the owner of the album if the comment owner is not the album owner
		if( $comment->created_by != $album->uid )
		{
			$recipients 	= array_merge( $recipients , array( $album->uid ) );
		}

		// Get a list of likes participants
		$likesModel 	= Foundry::model( 'Likes' );

		// Get list of users who liked this story.
		$participants 	= $likesModel->getLikerIds( $album->id , SOCIAL_TYPE_ALBUM . '.' .  SOCIAL_TYPE_USER );

		// Merge the list of recipients
		$recipients 	= array_merge( $recipients , $participants );

		// Get a list of comment participants
		$model 			= Foundry::model( 'Comments' );
		$participants 	= $model->getParticipants( $album->id , SOCIAL_TYPE_ALBUM . '.' .  SOCIAL_TYPE_USER );

		// Merge the list of recipients
		$recipients 	= array_merge( $recipients , $participants );

		// Unique the list of items now
		$recipients 	= array_unique( $recipients );

		// Reset the recipients index
		$recipients 	= array_values( $recipients );

		// Get the poster of the comment
		$poster 		= Foundry::user( $comment->created_by );

		// Remove myself from the recipients
		if( $recipients )
		{
			for( $i = 0; $i < count( $recipients ); $i++ )
			{
				if( $recipients[ $i ] == $poster->id )
				{
					unset( $recipients[ $i ] );
				}
			}
		}

		// If there's no recipients, forget about it.
		if( !$recipients )
		{
			return;
		}

		// Re-index the array again
		$recipients 	= array_values( $recipients );

		$recipients 	= Foundry::user( $recipients );

		// Get the poster of the comment
		$poster 		= Foundry::user( $comment->created_by );

		// Get the owner of the photo
		$albumOwner 	= Foundry::user( $album->uid );

		foreach( $recipients as $recipient )
		{
			$mailParams 	= array();

			$mailTemplate 	= $recipient->id == $album->uid ? 'new.comment.owner' : 'new.comment.involved';
			$emailTitle 	= $recipient->id == $album->uid ? JText::sprintf( 'COM_EASYSOCIAL_EMAILS_COMMENT_OWNER_ALBUM_TITLE' , $poster->getName() )
															: JText::sprintf( 'COM_EASYSOCIAL_EMAILS_COMMENT_INVOLVED_ALBUM_TITLE' , $poster->getName() , $albumOwner->getName() );

			$mailParams[ 'posterName' ]		= $poster->getName();
			$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_LARGE );
			$mailParams[ 'posterLink' ]		= $poster->getPermalink();
			$mailParams[ 'comment' ]		= $comment->comment;
			$mailParams[ 'permalink' ]		= $album->getPermalink( false );
			$mailParams[ 'albumThumbnail' ]	= $album->getCoverUrl();
			$mailParams[ 'albumPermalink' ]	= $album->getPermalink( false );
			$mailParams[ 'albumTitle' ]		= $album->get( 'title' );
			$mailParams[ 'albumOwner' ]		= $albumOwner->getName();

			$systemOptions 	= array(
									'type'			=> SOCIAL_TYPE_COMMENTS,
									'title' 		=> $comment->comment,
									'context_type'	=> $element ,
									'url' 			=> $album->getPermalink( false ),
									'actor_id'	 	=> $comment->created_by ,
									'uid' 			=> $comment->uid ,
									'aggregate' 	=> true,
									'image'			=> $album->getCoverUrl()
								);

			// For albums, we'll reuse the same alert rules from photos.comment.add
			// Determine if this user is involved in this or the user created this ite
			$state 	= Foundry::notify( 'photos.comment.add' , array( $recipient ),  array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/albums/' . $mailTemplate ), $systemOptions );
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
	public function onNotificationLoad( $item )
	{
		if( $item->context_type != SOCIAL_TYPE_ALBUM )
		{
			return;
		}

		// Load up the albums table based on the uid
		$album 			= Foundry::table( 'Album' );
		$album->load( $item->uid );

		// Default list of users
		$users 		= array();

		// Process comment notifications
		if( $item->type == SOCIAL_TYPE_COMMENTS )
		{
			return $this->displayNotificationComments( $album , $item );
		}

		if( $item->type == SOCIAL_TYPE_LIKES )
		{
			return $this->displayNotificationLikes( $album , $item );
		}
	}

	/**
	 * Responsible to process system notifications for comments
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return
	 */
	private function displayNotificationLikes( &$album , &$item )
	{
		// Load up likes model
		$model 			= Foundry::model( 'Likes' );

		// Get list of users who liked this story.
		$users 			= $model->getLikerIds( $album->id , SOCIAL_TYPE_ALBUM . '.' . SOCIAL_TYPE_USER );

		// Exclude myself from the list of users.
		$index 			= array_search( Foundry::user()->id , $users );

		if( $index !== false )
		{
			unset( $users[ $index ] );

			$users 	= array_values( $users );
		}

		// Add the author of the album as the recipient
		if( $item->actor_id != $album->uid )
		{
			$users[]	= $album->uid;
		}

		// Ensure that the values are unique
		$users		= array_unique( $users );
		$users 		= array_values( $users );

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

		// Convert the names to stream-ish
		$names 			= Foundry::string()->namesToStream( $users , false , 2 , true , true );
		$usePlural 		= false;

		if( count( $users ) == 1 )
		{
			$usePlural 	= true;

			// If the only user is the current viewer, it should not be plural
			$user 	= Foundry::user( $users[ 0 ] );

			if( $user->isViewer() )
			{
				$usePlural 	= false;
			}
		}
		else
		{
			$usePlural 	= false;
		}

		// We need to generate the notification message differently for the author of the item and the recipients of the item.
		if( $album->uid == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
		{
			$item->title 	= JText::sprintf( 'APP_ALBUMS_LIKES_OWNER_ALBUM' , $names );

			return $item;
		}

		// This is for 3rd party viewers
		$text 			= $usePlural ? 'APP_ALBUMS_LIKES_USER_ALBUM_PLURAL' : 'APP_ALBUMS_LIKES_USER_ALBUM_SINGULAR';
		$item->title 	= JText::sprintf( $text , $names , Foundry::user( $album->uid )->getName() );

		return $item;
	}

	/**
	 * Responsible to process system notifications for comments
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return
	 */
	private function displayNotificationComments( &$album , &$item )
	{
		// Get a list of comment participants
		$model 		= Foundry::model( 'Comments' );
		$users 		= $model->getParticipants( $item->uid , SOCIAL_TYPE_ALBUM . '.' . SOCIAL_TYPE_USER );

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
		$names 			= Foundry::string()->namesToStream( $users , false , 3 , true , true );

		// We need to generate the notification message differently for the author of the item and the recipients of the item.
		if( $album->uid == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
		{
			if( $content )
			{
				$item->title 	= JText::sprintf( "APP_ALBUMS_COMMENTS_YOUR_ALBUM_CONTENT" , $names , $content );
			}
			else
			{
				$item->title 	= JText::sprintf( "APP_ALBUMS_COMMENTS_YOUR_ALBUM" , $names );
			}

			return $item;
		}

		// This is for 3rd party viewers
		if( $content )
		{
			$item->title 	= JText::sprintf( "APP_ALBUMS_COMMENTS_ALBUM_INVOLVING_YOU_CONTENT" , $names , Foundry::user( $album->uid )->getName() , $content );
		}
		else
		{
			$item->title 	= JText::sprintf( "APP_ALBUMS_COMMENTS_ALBUM_INVOLVING_YOU" , $names , Foundry::user( $album->uid )->getName() );
		}


		return $item;
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
		if( $item->context != 'albums' )
			return;

		// Get the context id.
		$id 		= $item->contextId;

		// Load the profiles table.
		$album	= Foundry::table( 'Album' );
		$state 	= $album->load( $id );

		// Get the actor
		$actor 		= $item->actor;
		// $my 		= Foundry::user();

		$userAlias 	= $actor->getAlias();

		// Set the actor for the themes.
		$this->set( 'userAlias'	, $userAlias );
		$this->set( 'actor' 	, $actor );
		$this->set( 'album'		, $album );

		$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
		$item->title	= parent::display( 'streams/' . $item->verb . '.title' );

		if( $includePrivacy )
		{
			$my         = Foundry::user();
			$item->privacy = Foundry::privacy( $my->id )->form( $album->id, 'albums', $item->actor->id, 'albums.view' );
		}
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
		if( $item->context != 'albums' )
		{
			return;
		}

		// Get the single context id
		$id 		= $item->contextId;

		// Load the profiles table.
		$album	= Foundry::table( 'Album' );
		$state 	= $album->load( $id );

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'ALBUM: Unable to load album with the id of ' . $id );

			return false;
		}

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );

		if( $includePrivacy )
		{
			if(! $privacy->validate( 'albums.view', $item->contextId, 'albums', $item->actor->id ) )
			{
				return;
			}
		}

		// Format the likes for the stream
		$likes 			= Foundry::likes();
		$likes->get( $album->id , 'albums' );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments			= Foundry::comments( $album->id , 'albums' , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::albums( array( 'layout' => 'item', 'id' => $album->id ) ) ) );
		$item->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;


		// get album cover photo
		$coverImg 	= $album->getCoverUrl();

		$userAlias 	= $actor->getAlias();

		$this->set( 'actor'		, $item->actor );
		$this->set( 'album'	, $album );
		$this->set( 'coverImg'	, $coverImg );
		$this->set( 'userAlias'	, $userAlias );

		// Set stream display mode.
		$item->display	= ( $item->display ) ? $item->display : SOCIAL_STREAM_DISPLAY_FULL;

		// Set the content
		$item->content 	= parent::display( 'streams/' . $item->verb . '.content' );

		// Set the title
		$item->title	= parent::display( 'streams/' . $item->verb . '.title' );

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $album->id, 'albums', $item->actor->id, 'albums.view' );
		}
	}

}
