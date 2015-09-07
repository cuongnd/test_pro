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
 * Photos application for EasySocial
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppPhotos extends SocialAppItem
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		JFactory::getLanguage()->load( 'app_photos' , JPATH_ROOT );

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
		if( $item->context != 'photos' )
		{
			return;
		}

		// Get the context id.
		$id 		= $item->contextId;

		// Load the profiles table.
		$photo	= Foundry::table( 'Photo' );
		$state 	= $photo->load( $id );

		$album 	= Foundry::table( 'Album' );
		$album->load( $photo->album_id );

		// Get the actor
		$actor 		= $item->actor;

		// Get the term to be displayed
		$term 		= $actor->getFieldValue( 'GENDER' );

		// If there is no term provided we use "his" by default.
		$term 		= !$term ? JText::_( 'COM_EASYSOCIAL_THEIR' ) : $term;


		$langText   =  ( $item->verb == 'share' ) ? 'APP_PHOTOS_SHARED_COUNT' : 'APP_PHOTOS_ADDED_COUNT';
		$text 		= Foundry::string()->computeNoun( $langText , 1 );
		$text 		= JText::sprintf( $text , 1 );

		$shareWith     = '';
		if( $item->verb == 'share' && $item->targets )
		{
			$shareWith = $item->targets[0];
		}

		$this->set( 'term'	, $term );
		$this->set( 'album'	, $album );
		$this->set( 'actor' , $actor );
		$this->set( 'photo'	, $photo );
		$this->set( 'cover'	, $actor->getCover() );

		$this->set( 'text'	, $text );
		$this->set( 'shareWith', $shareWith );

		// old data compatibility
		$verb = ( $item->verb == 'create' ) ? 'add' : $item->verb;

		$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
		$item->title	= parent::display( 'logs/' . $verb );

		$privacyRule = 'photos.view';
		if( $item->verb == 'uploadAvatar' || $item->verb == 'updateCover')
		{
			$privacyRule = 'core.view';
		}


		if( $includePrivacy )
		{
			$my         = Foundry::user();
			$item->privacy = Foundry::privacy( $my->id )->form( $photo->id, 'photos', $item->actor->id, $privacyRule );
		}
	}

	/**
	 * Trigger for onPrepareStream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		// We only want to process related items
		if( $item->context != 'photos' )
		{
			return;
		}

		$config	= Foundry::config();

		// Do not allow user to access photos if it's not enabled
		if( !$config->get( 'photos.enabled' ) && $item->verb != 'uploadAvatar' && $item->verb != 'updateCover' )
		{
			return;
		}

		// Get current logged in user.
		$my         = Foundry::user();

		// Get user's privacy.
		$privacy 	= Foundry::privacy( $my->id );

		$element	= $item->context;
		$uid     = $item->contextId;
		$useAlbum = false;

		$photo = Foundry::table( 'Photo' );
		$photoId = $item->contextId;

		$contextItemParam = ( isset( $item->contextParams[ $photoId ] ) ) ? $item->contextParams[ $photoId ] : '';
		if( $contextItemParam )
		{
			$photoObj = Foundry::json()->decode( $contextItemParam );
			$photo->bind( $photoObj );

			// safe step to ensure the photo is always valid.
			if(! $photo->id )
			{
				$photo->load( $photoId );
			}
		}
		else
		{
			// Load the photo and album objects
			$photo->load( $photoId );
		}


		// photos has the special threatment. if the item is a aggregated item, then the context is album and the uid is albumid.
		if( count( $item->contextIds ) > 1 )
		{
			if( $photo->id )
			{
				$element 	= SOCIAL_TYPE_ALBUM;
				$uid 		= $photo->album_id;
				$useAlbum  = true;

				// Format the likes for the stream
				$likes 			= Foundry::likes();
				$likes->get( $photo->album_id , 'albums' );
				$item->likes	= $likes;

				// Apply comments on the stream
				$comments			= Foundry::comments( $photo->album_id , 'albums' , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::albums( array( 'layout' => 'item', 'id' => $photo->album_id ) ) ) );
				$item->comments 	= $comments;
			}

		}
		else
		{
			$likes 			= Foundry::likes();
			$likes->get( $item->contextId , $item->context );
			$item->likes	= $likes;

			// Apply comments on the stream
			$comments			= Foundry::comments( $item->contextId , $item->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::photos( array( 'layout' => 'item', 'id' => $item->contextId ) ) ) );
			$item->comments 	= $comments;
		}

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;

		$privacyRule = ( $useAlbum ) ? 'albums.view' : 'photos.view';

		if( $item->verb == 'uploadAvatar' || $item->verb == 'updateCover')
		{
			$privacyRule = 'core.view';
		}


		if( $includePrivacy )
		{
			// Determine if the user can view this current context
			if( !$privacy->validate( $privacyRule , $uid, $element , $item->actor->id ) )
			{
				return;
			}
		}

		if( $privacyRule == 'photos.view' )
		{
			// we need to check the photo's album privacy to see if user allow to view or not.
			if( !$privacy->validate( 'albums.view' , $photo->album_id,  SOCIAL_TYPE_ALBUM, $item->actor->id ) )
			{
				return;
			}
		}


		// Get the single context id
		$id 		= $item->contextId;
		$albumId 	= '';

		if( $item->verb == 'uploadAvatar' )
		{
			$this->prepareUploadAvatarStream( $item, $privacy, $includePrivacy );
		}

		if( $item->verb == 'add' || $item->verb == 'create' || $item->verb == 'share' )
		{
			$this->preparePhotoStream( $item, $privacy, $includePrivacy, $useAlbum );
		}

		if( $item->verb == 'updateCover' )
		{
			$this->prepareUpdateCoverStream( $item, $privacy, $includePrivacy );
		}

		return true;
	}


	/**
	 * Prepares the stream items for photo uploads
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function preparePhotoStream( &$item, $privacy, $includePrivacy = true, $useAlbum = false )
	{
		$photos  = array();

		$element = $item->context;
		$uid     = $item->contextId;

		if( count( $item->contextIds ) > 0 )
		{
			// We only want to get a maximum of 5 photos.
			$photoIds 	= array_reverse( $item->contextIds );

			// $photoIds 	= array_slice( $photoIds , 0 , 4 );

			// We only want 5 latest photos
			for($i = 0; $i < count( $photoIds ) && $i < 5; $i++ )
			{
				$photoId 		= $photoIds[ $i ];

				$contextItemParam = ( isset( $item->contextParams[ $photoId ] ) ) ? $item->contextParams[ $photoId ] : '';

				$photo 		= Foundry::table( 'Photo' );

				if( $contextItemParam )
				{
					$photoObj = Foundry::json()->decode( $contextItemParam );
					$photo->bind( $photoObj );

					// safe step to ensure the photo is always valid.
					if(! $photo->id )
					{
						$photo->load( $photoId );
					}
				}
				else
				{

					// Load the photo and album objects
					$photo->load( $photoId );
				}

				// Determine if the user can view this photo or not.
				if( $privacy->validate( 'photos.view' , $photo->id, 'photos' , $item->actor->id ) )
				{
					$photos[] = $photo;
				}

				// assuming the aggregated data from the same album.
				$albumId  = $photo->album_id;
			}
		}

		$privacyRule = ( $useAlbum ) ? 'albums.view' : 'photos.view';

		$album = Foundry::table( 'Album' );
		$album->load( $albumId );

		// Get the actor
		$actor 		= $item->actor;

		$langText     =  ( $item->verb == 'share' ) ? 'APP_PHOTOS_SHARED_COUNT' : 'APP_PHOTOS_ADDED_COUNT';


		$count 		= count( $item->contextIds );
		$text 		= Foundry::string()->computeNoun( $langText , $count );
		$text 		= JText::sprintf( $text , $count );

		$shareWith     = '';
		$shareText     =  ( $item->verb == 'share' ) ? $item->content : '';

		if( $item->verb == 'share' && $item->targets )
		{
			$shareWith = $item->targets[0];
		}

		$this->set( 'totalPhotos' , count( $photos ) );
		$this->set( 'text'	, $text );
		$this->set( 'photos', $photos );
		$this->set( 'album'	, $album );
		$this->set( 'actor'	, $actor );
		$this->set( 'sharetext', $shareText );
		$this->set( 'shareWith', $shareWith );

		// old data compatibility
		$verb = ( $item->verb == 'create' ) ? 'add' : $item->verb;

		// Set the display mode to be full.
		$item->display	= SOCIAL_STREAM_DISPLAY_FULL;

		$item->title 	= parent::display( 'streams/' . $verb . '.title' );

		$item->content 	= parent::display( 'streams/' . $verb . '.content' );

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $uid, $element, $item->actor->id, $privacyRule );
		}
	}

	/**
	 * Prepares the upload avatar stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStream
	 * @return
	 */
	public function prepareUploadAvatarStream( &$item , $privacy, $includePrivacy = true)
	{
		$element = $item->context;
		$uid     = $item->contextId;

		// Load the photo
		$photo 			= Foundry::table( 'Photo' );

		$photoId = $item->contextId;
		$contextItemParam = ( isset( $item->contextParams[ $photoId ] ) ) ? $item->contextParams[ $photoId ] : '';
		if( $contextItemParam )
		{
			$photoObj = Foundry::json()->decode( $contextItemParam );
			$photo->bind( $photoObj );

			// safe step to ensure the photo is always valid.
			if(! $photo->id )
			{
				$photo->load( $photoId );
			}
		}
		else
		{
			// Load the photo and album objects
			$photo->load( $photoId );
		}

		// Get the term to be displayed
		$term 		= $item->actor->getFieldValue( 'GENDER' );

		// If there is no term provided we use "his" by default.
		$term 		= !$term ? JText::_( 'COM_EASYSOCIAL_THEIR' ) : $term;

		$this->set( 'term'	, $term );
		$this->set( 'photo' , $photo );
		$this->set( 'actor'	, $item->actor );

		$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content 	= parent::display( 'streams/' . $item->verb . '.content' );

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $uid, $element, $item->actor->id, 'core.view' );
		}

	}

	/**
	 * Prepares the upload avatar stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStream
	 * @return
	 */
	public function prepareUpdateCoverStream( &$item, $privacy, $includePrivacy = true )
	{
		$element = $item->context;
		$uid     = $item->contextId;

		// Load the photo
		$photo 			= Foundry::table( 'Photo' );

		$photoId = $item->contextId;
		$contextItemParam = ( isset( $item->contextParams[ $photoId ] ) ) ? $item->contextParams[ $photoId ] : '';
		if( $contextItemParam )
		{
			$photoObj = Foundry::json()->decode( $contextItemParam );
			$photo->bind( $photoObj );

			// safe step to ensure the photo is always valid.
			if(! $photo->id )
			{
				$photo->load( $photoId );
			}
		}
		else
		{
			// Load the photo and album objects
			$photo->load( $photoId );
		}


		// Get the term to be displayed
		$term 		= $item->actor->getFieldValue( 'GENDER' );

		// If there is no term provided we use "his" by default.
		$term 		= !$term ? JText::_( 'COM_EASYSOCIAL_THEIR' ) : $term;

		$cover 		= $item->actor->getCover();

		$this->set( 'cover'	, $cover );
		$this->set( 'term'	, $term );
		$this->set( 'photo' , $photo );
		$this->set( 'actor'	, $item->actor );

		$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content 	= parent::display( 'streams/' . $item->verb . '.content' );

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $uid, $element, $item->actor->id, 'core.view' );
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
	public function onAfterStorySave( &$stream , $streamItem , &$template )
	{
		$photos	= JRequest::getVar( 'photos' );

		// If there's no data, we don't need to do anything here.
		if( empty( $photos ) )
		{
			return;
		}

		if( empty( $template->content ) )
		{
			$content 	.= '<br />';
		}


		// Now that we know the saving is successfull, we want to update the state of the photo table.
		foreach( $photos as $photoId )
		{
			$table 	= Foundry::table( 'Photo' );
			$table->load( $photoId );

			$album	= Foundry::table( 'Album' );
			$album->load( $table->album_id );

			$table->state	= SOCIAL_STATE_PUBLISHED;
			$table->store();

			// Determine if there's a cover for this album.
			if( !$album->hasCover() )
			{
				$album->cover_id	= $table->id;
				$album->store();
			}

			$template->content 	.= '<img src="' . $table->getSource( 'thumbnail' ) . '" width="128" />';
		}

		return true;
	}

	/*
	 * Save trigger which is called after really saving the object.
	 */
	public function onAfterSave( &$data )
	{
	    // for now we only support the photo added by person. later on we will support
	    // for groups, events and etc.. the source will determine the type.
	    $source		= isset( $data->source ) ? $data->source : 'people';
	    $actor		= ($source == 'people' ) ? Foundry::get('People', $data->created_by) : '0';

	    // save into activity streams
	    $item   = new StdClass();
	    $item->actor_id 	= $actor->get( 'node_id' );
	    $item->source_type	= $source;
	    $item->source_id 	= $actor->id;
	    $item->context_type = 'photos';
	    $item->context_id 	= $data->id;
	    $item->verb 		= 'upload';
	    $item->target_id 	= $data->album_id;

	    //$item   = get_object_vars($item);
        //Foundry::get('Stream')->addStream( array($item, $item, $item) );
        Foundry::get('Stream')->addStream( $item );
		return true;
	}


	public function onPrepareStoryAttachment($story)
	{
		$config 	= Foundry::config();

		if( !$config->get( 'photos.enabled' ) )
		{
			return;
		}

		$plugin = $story->createPlugin("photos", "attachment");

		$theme = Foundry::get('Themes');
		$plugin->icon->html = '<i class="ies-pictures-2"></i>';
		$plugin->button->html = $theme->output('themes:/apps/user/photos/story.attachment.button');
		$plugin->content->html = $theme->output( 'themes:/apps/user/photos/story.attachment.content' );

		$script = Foundry::get('Script');
		$plugin->script = $script->output('apps:/user/photos/story');

		return $plugin;
	}

	/**
	 * Triggers when unlike happens
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onAfterLikeDelete( &$likes )
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

		if( $element != SOCIAL_TYPE_PHOTO )
		{
			return;
		}

		// Get the photo object
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $uid );

		// @points: photos.unlike
		// Deduct points for the current user for unliking this item
		$photo->assignPoints( 'photos.unlike' , Foundry::user()->id );
	}

	/**
	 * Triggers after a like is saved
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

		if( $element != SOCIAL_TYPE_PHOTO )
		{
			return;
		}

		// Get the photo object
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $uid );

		// @points: photos.like
		// Assign points for the author for liking this item
		$photo->assignPoints( 'photos.like' , Foundry::user()->id );

		// Default recipients
		$recipients = array();

		// Get a list of tagged users
		$tags 	= $photo->getTags( true );

		if( $tags )
		{
			foreach( $tags as $tag )
			{
				$recipients[]	= $tag->uid;
			}
		}

		// Get a list of likers
		$likers 		= Foundry::likes( $uid , $element , $group )->getParticipants( false );

		// Merge the list now
		$recipients		= array_merge( $recipients , $likers );

		// Get people who are part of the comments
		$comments		= Foundry::comments( $uid , $element , $group );
		$recipients 	= array_merge( $recipients , $comments->getParticipants( array() , false ) );

		// Notify the actor of the story that someone likes this.
		$recipients[]	= $photo->uid;

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
		}

		// Reset the indexes
		$recipients 	= array_values( $recipients );

		// Stop if there's nothing to process
		if( !$recipients )
		{
			return;
		}

		// Load up recipients
		$recipients 	= Foundry::user( $recipients );

		// Get the author of the item
		$poster 	= Foundry::user( $likes->created_by );
		$photoOwner	= Foundry::user( $photo->uid );

		foreach( $recipients as $recipient )
		{
			// Determine if this user is involved in this or the user created this item.
			$mailParams 	= array();

			$mailTemplate 	= $recipient->id == $photo->uid ? 'new.likes.owner' : 'new.likes.involved';
			$emailTitle 	= $recipient->id == $photo->uid ? JText::sprintf( 'COM_EASYSOCIAL_EMAILS_LIKES_PHOTO_OWNER_EMAIL_TITLE' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_EMAILS_LIKES_PHOTO_INVOLVED_EMAIL_TITLE' , $poster->getName() , $photoOwner->getName() );

			$mailParams[ 'posterName' ]		= $poster->getName();
			$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_LARGE );
			$mailParams[ 'posterLink' ]		= $poster->getPermalink();
			$mailParams[ 'permalink' ]		= $photo->getPermalink( false );
			$mailParams[ 'photoThumbnail' ]	= $photo->getSource();
			$mailParams[ 'photoPermalink' ]	= $photo->getPermalink( false );
			$mailParams[ 'photoTitle' ]		= $photo->get( 'title' );
			$mailParams[ 'photoOwner' ]		= $photoOwner->getName();

			$systemOptions 	= array( 'type' => SOCIAL_TYPE_LIKES ,'context_type' => $element , 'url' => $photo->getPermalink( false ) ,  'actor_id' => $likes->created_by , 'uid' => $uid , 'aggregate' => true , 'image' => $photo->getSource() );

			// Add new notification item
			$state 			= Foundry::notify(	'photos.likes' ,  array( $recipient->id ) , array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/photos/' . $mailTemplate ), $systemOptions );
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
		if( $element != 'photos' )
		{
			return;
		}

		// Get a list of users that are tagged in this photo.

		// Determine the owner of this commented item.
		$photo 		= Foundry::table( 'Photo' );
		$photo->load( $comment->uid );

		// @points: photos.comment.add
		// Assign points to the user for posting a new comment
		$photo->assignPoints( 'photos.comment.add' , $comment->created_by );

		// Default recipients
		$recipients = array();

		// Only get tags that are relevant to other users
		$tags 		= $photo->getTags( true );

		if( $tags )
		{
			foreach( $tags as $tag )
			{
				$recipients[]	= $tag->uid;
			}
		}

		// Add the owner of the photo if it's not him that is commenting on the item.
		if( $comment->created_by != $photo->uid )
		{
			$recipients 	= array_merge( $recipients , array( $photo->uid ) );
		}

		// Unique the list of items now
		$recipients 	= array_unique( $recipients );

		// Remove myself from the recipients
		if( $recipients )
		{
			for( $i = 0; $i < count( $recipients ); $i++ )
			{
				if( $recipients[ $i ] == $comment->created_by )
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
		$photoOwner		= Foundry::user( $photo->uid );

		foreach( $recipients as $recipient )
		{
			$mailParams 	= array();

			$mailTemplate 	= $recipient->id == $photo->uid ? 'new.comment.owner' : 'new.comment.involved';
			$emailTitle 	= $recipient->id == $photo->uid ? JText::sprintf( 'COM_EASYSOCIAL_EMAILS_COMMENT_OWNER_PHOTO_TITLE' , $poster->getName() ) : JText::sprintf( 'COM_EASYSOCIAL_EMAILS_COMMENT_INVOLVED_PHOTO_TITLE' , $poster->getName() );

			$mailParams[ 'posterName' ]		= $poster->getName();
			$mailParams[ 'posterAvatar' ]	= $poster->getAvatar( SOCIAL_AVATAR_LARGE );
			$mailParams[ 'posterLink' ]		= $poster->getPermalink();
			$mailParams[ 'comment' ]		= $comment->comment;
			$mailParams[ 'permalink' ]		= $photo->getPermalink( false );
			$mailParams[ 'photoThumbnail' ]	= $photo->getSource();
			$mailParams[ 'photoPermalink' ]	= $photo->getPermalink( false );
			$mailParams[ 'photoTitle' ]		= $photo->get( 'title' );
			$mailParams[ 'photoOwner' ]		= $photoOwner->getName();

			$systemOptions 	= array(
									'type'			=> SOCIAL_TYPE_COMMENTS,
									'title' 		=> $comment->comment,
									'context_type'	=> $element ,
									'url' 			=> $photo->getPermalink( false ),
									'actor_id'	 	=> $comment->created_by ,
									'uid' 			=> $comment->uid ,
									'aggregate' 	=> true,
									'image'			=> $photo->getSource()
								);

			// Determine if this user is involved in this or the user created this ite
			$state 	= Foundry::notify( 'photos.comment.add' , array( $recipient ),  array( 'title' => $emailTitle , 'params' => $mailParams , 'template' => 'site/photos/' . $mailTemplate , ''), $systemOptions );

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
		if( $item->context_type != SOCIAL_TYPE_PHOTO )
		{
			return;
		}

		// Load up the photos table based on the uid
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $item->uid );

		// Get a list of tagged users
		$tags 			= $photo->getTags( true );
		$taggedUsers	= array();

		if( $tags )
		{
			foreach( $tags as $tag )
			{
				$taggedUsers[]	= $tag->uid;
			}
		}

		// Default list of users
		$users 		= array();

		// Process like notifications
		if( $item->type == SOCIAL_TYPE_LIKES )
		{
			// Load up likes model
			$model 			= Foundry::model( 'Likes' );

			// Get list of users who liked this story.
			$users 			= $model->getLikerIds( $item->uid , 'photos.user' );

			// Exclude myself from the list of users.
			$index 			= array_search( Foundry::user()->id , $users );

			if( $index !== false )
			{
				unset( $users[ $index ] );

				$users 	= array_values( $users );
			}

			// Add the author of the photo as the recipient
			if( $item->actor_id != $photo->uid )
			{
				$users[]	= $photo->uid;
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
			$names 			= Foundry::string()->namesToStream( $users , false , 2 , true , false );
			$usePlural 		= false;

			// We need to generate the notification message differently for the author of the item and the recipients of the item.
			if( $photo->uid == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
			{
				// This is for 3rd party viewers
				if( $usePlural )
				{
					$item->title 	= JText::sprintf( 'APP_PHOTOS_LIKES_OWNER_PHOTO_PLURAL' , $names );
				}
				else
				{
					$item->title 	= JText::sprintf( 'APP_PHOTOS_LIKES_OWNER_PHOTO' , $names );
				}


				return $item;
			}

			// This is for 3rd party viewers
			if( $usePlural )
			{
				$item->title 	= JText::sprintf( "APP_PHOTOS_LIKES_USER_PHOTO_PLURAL" , $names , Foundry::user( $photo->uid )->getName() );
			}
			else
			{
				$item->title 	= JText::sprintf( "APP_PHOTOS_LIKES_USER_PHOTO" , $names , Foundry::user( $photo->uid )->getName() );
			}

			// This is for 3rd party viewers


			return $item;
		}

		// Process comment notifications
		if( $item->type == SOCIAL_TYPE_COMMENTS )
		{
			// Get a list of comment participants
			$model 		= Foundry::model( 'Comments' );
			$users 		= $model->getParticipants( $item->uid , 'photos.user' );

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
			if( $photo->uid == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
			{
				if( $content )
				{
					$item->title 	= JText::sprintf( "APP_PHOTOS_COMMENTS_YOUR_PHOTO_CONTENT" , $names , $content );
				}
				else
				{
					$item->title 	= JText::sprintf( "APP_PHOTOS_COMMENTS_YOUR_PHOTO" , $names );
				}

				return $item;
			}

			// We need to generate the notification message differently for users that are tagged in the photo
			if( in_array( $item->target_id , $taggedUsers ) && $item->target_type == SOCIAL_TYPE_USER )
			{
				if( $content )
				{
					$item->title 	= JText::sprintf( "APP_PHOTOS_COMMENTS_PHOTO_YOU_ARE_TAGGED_CONTENT" , $names , $content );
				}
				else
				{
					$item->title 	= JText::sprintf( "APP_PHOTOS_COMMENTS_PHOTO_YOU_ARE_TAGGED" , $names );
				}

				return $item;
			}

			// This is for 3rd party viewers
			if( $content )
			{
				$item->title 	= JText::sprintf( "APP_PHOTOS_COMMENTS_PHOTO_INVOLVING_YOU_CONTENT" , $names , Foundry::user( $photo->uid )->getName() , $content );
			}
			else
			{
				$item->title 	= JText::sprintf( "APP_PHOTOS_COMMENTS_PHOTO_INVOLVING_YOU" , $names , Foundry::user( $photo->uid )->getName() );
			}


			return $item;
		}
	}
}
