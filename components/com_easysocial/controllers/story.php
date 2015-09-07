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

// Import main controller
Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerStory extends EasySocialController
{

	/**
	 * Stores a new story item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function create()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Check for valid users.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();
		$my 	= Foundry::user();

		// Load our story library
		$story 	= Foundry::story( SOCIAL_TYPE_USER );

		// Get posted data.
		$post 	= JRequest::get( 'post' );

		// check if the user being viewed the same user or other user.
		$id 		= $post[ 'target' ];
		$targetId	= ( $my->id != $id ) ? $id : '';

		// Determine the post types.
		$type 		= ( isset( $post[ 'attachment' ] ) && !empty( $post[ 'attachment' ] ) ) ? $post[ 'attachment' ] : SOCIAL_TYPE_STORY;

		// Check if the content is empty only for story based items.
		if( ( !isset( $post[ 'content' ] ) || empty( $post[ 'content' ] ) ) && $type == SOCIAL_TYPE_STORY )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_STORY_PLEASE_POST_MESSAGE' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if the content is empty and there's no photos.
		if(  (!isset( $post[ 'photos' ] ) || empty($post[ 'photos']) ) && $type == 'photos' )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_STORY_PLEASE_ADD_PHOTO' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$content 	= $post[ 'content' ];

		// @TODO: Check whether the user can really post something on the target
		if( $targetId )
		{
			$privacy 	= Foundry::privacy( $my->id );
			$state = $privacy->validate( 'profiles.post.status' , $targetId , SOCIAL_TYPE_USER );

			if( ! $state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_STORY_NOT_ALLOW_TO_POST' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}


		// Store the location for this story
		$shortAddress	= JRequest::getVar( 'locations_short_address' , '' );
		$address 	= JRequest::getVar( 'locations_formatted_address' , '' );
		$lat 		= JRequest::getVar( 'locations_lat' , '' );
		$lng 		= JRequest::getVar( 'locations_lng'	, '' );
		$location 	= null;

		// Only store location when there is location data
		if( !empty( $address ) && !empty( $lat ) && !empty( $lng ) )
		{
			$location 				= Foundry::table( 'Location' );
			$location->short_address	= $shortAddress;
			$location->address 		= $address;
			$location->longitude 	= $lng;
			$location->latitude		= $lat;

			$location->uid 			= $story->id;
			$location->type 		= $type;
			$location->user_id 		= $my->id;

			// Try to save the location data.
			$state 	= $location->store();
		}

		// Get which users are tagged in this post.
		$friendIds 	= JRequest::getVar( 'friends_tags' , '' );
		$ids 		= array();

		if( !empty( $friendIds ) )
		{
			// Get the friends model
			$model 	= Foundry::model( 'Friends' );

			foreach( $friendIds as $id )
			{
				// Check if the user is really a friend of him / her.
				if( !$model->isFriends( $my->id , $id ) )
				{
					continue;
				}

				$ids[]	= $id;
			}
		}

		$contextIds = 0;

		if( $type == 'photos' )
		{
			// we expecting photos here.
			$photos = isset( $post['photos'] ) ? $post['photos'] : '';

			if( $photos )
			{
				$contextIds = $photos;
			}
		}

		// Create the stream item
		$stream	= $story->create( $content , $contextIds , $type , $my->id, $targetId , $location , $ids );

		// @badge: story.create
		// Add badge for the author when a report is created.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'story.create' , $my->id , JText::_( 'COM_EASYSOCIAL_STORY_BADGE_CREATED_STORY' ) );

		// @points: story.create
		// Add points for the author when a report is created.
		$points = Foundry::points();
		$points->assign( 'story.create' , 'com_easysocial' , $my->id );

		// Set the privacy for the album
		$privacy 		= JRequest::getWord( 'privacy' , '' );
		$customPrivacy  = JRequest::getString( 'privacyCustom', '' );


		$privacyRule 	= ( $type == 'photos' ) ? 'photos.view' : 'story.view';
		$privacyLib		= Foundry::privacy();

		if( $type == 'photos' )
		{
			$photoIds = ( is_array( $contextIds ) ) ? $contextIds : array( $contextIds );

			foreach( $photoIds as $photoId )
			{
				$privacyLib->add( $privacyRule , $photoId , $type , $privacy, null, $customPrivacy );
			}

		}
		else
		{
			$privacyLib->add( $privacyRule , $stream->uid , $type , $privacy, null, $customPrivacy );
		}

		return $view->call( __FUNCTION__ , $stream );
	}

}
