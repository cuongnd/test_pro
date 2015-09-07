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

class EasySocialControllerAlbums extends EasySocialController
{
	public function __construct()
	{
		parent::__construct();

		$this->registerTask( 'create'	, 'store' );
		$this->registerTask( 'update'	, 'store' );
	}

	/**
	 * Custom implementation of likes for albums
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function like()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered users are allowed to like an album
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the album id.
		$id 	= JRequest::getInt( 'id' );

		// Load up album
		$album 	= Foundry::table( 'Album' );
		$album->load( $id );

		if( !$id || !$album->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_INVALID_ALBUM_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get current logged in user.
		$my 	= Foundry::user();

		// Load up likes library
		$likes 	= Foundry::get( 'Likes' );
		$isLike = false;

		if( $likes->hasLiked( $album->id , SOCIAL_TYPE_ALBUM , $my->id ) )
		{
			$state 	= $likes->delete( $album->id , SOCIAL_TYPE_ALBUM , $my->id );
		}
		else
		{
			$isLike = true;
			$state 	= $likes->add( $album->id , SOCIAL_TYPE_ALBUM , $my->id );
		}

		if( $state === false )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_ERROR_SAVING_LIKES' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$method 	= $isLike ? __FUNCTION__ : 'unlike';

		return $view->call( $method , $state );
	}

	/**
	 * Retrieves a list of albums a user owns
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function listItems()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the current user.
		$my 	= Foundry::user();

		$model 	= Foundry::model( 'Albums' );
		$albums	= $model->getAlbums( $my->id , SOCIAL_TYPE_USER );

		return $view->call( __FUNCTION__ , $albums );
	}

	/**
	 * Retrieve album object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlbum()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view.
		$view 	= $this->getCurrentView();

		$id = JRequest::getInt( 'id', 0 );

		if( $id === 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_INVALID_ALBUM_ID_PROVIDED' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false );
		}

		// Load the album object
		$album = Foundry::table( 'Album' );
		$album->load( $id );

		return $view->call( __FUNCTION__, $album );
	}

	/**
	 * Creating of new albums
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$view 	= $this->getCurrentView();

		// Check if the user is allowed to create albums
		$access	= Foundry::access();

		if( !$access->allowed( 'create.albums' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_ACCESS_NOT_ALLOWED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Get the data from request.
		$post 	= JRequest::get( 'post' );

		// Load the album
		$album	= Foundry::table( 'Album' );
		$album->load( $post[ 'id' ] );

		$isNew = true;
		if( $album->id )
		{
			$isNew = false;
		}

		// Determine if the user has already exceeded the album creation
		if( $isNew && $access->exceeded( 'albums.total' , $my->getTotalAlbums( true ) ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_ACCESS_EXCEEDED_LIMIT' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Set the album creation alias
		$album->assigned_date 	= Foundry::date()->toMySQL();

		// Set custom date
		if( isset( $post['date'] ) )
		{
			$album->assigned_date 	= $post[ 'date' ];

			unset( $post['date'] );
		}

		// Map the remaining post data with the album.
		$album->bind( $post );

		// Set the uid and type if it's a new album.
		if( $isNew )
		{
			$album->uid 	= $my->id;
			$album->type 	= SOCIAL_TYPE_USER;
		}

		// Try to store the album
		$state 			= $album->store();

		// Throw error when there's an error saving album
		if( !$state )
		{
			$view->setMessage( $album->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// Detect for location
		$address 	= JRequest::getVar( 'address' , '' );
		$latitude 	= JRequest::getVar( 'latitude' , '' );
		$longitude	= JRequest::getVar( 'longitude' , '' );

		if( !empty( $address ) && !empty( $latitude) && !empty( $longitude ) )
		{
			$location 	= Foundry::location();

			$location->create( $album->id , SOCIAL_TYPE_ALBUM , $my->id );
		}

		// Set the privacy for the album
		$privacy 		= JRequest::getWord( 'privacy' );
		$privacyCustom  = JRequest::getString( 'privacyCustom', '' );

		$privacyLib		= Foundry::privacy();
		$privacyLib->add( 'albums.view' , $album->id , 'albums' , $privacy, null, $privacyCustom );


		$albumPhotos = array();

		if( isset( $post['photos'] ) )
		{
			// Save individual photos
			foreach( $post['photos'] as $photo )
			{
				$photo = (object) $photo;

				$photoTable = Foundry::table( 'photo' );

				$photoTable->load( $photo->id );

				$photoTable->album_id	= $album->id;
				$photoTable->title 		= $photo->title;
				$photoTable->caption	= $photo->caption;

				if( isset( $post['ordering'] ) && isset( $post['ordering'][$photo->id] ) )
				{
					$photoTable->ordering = $post['ordering'][$photo->id];
				}

				if( isset( $photo->date ) && !empty( $photo->date ) )
				{
					$photoTable->assigned_date 	= Foundry::date( $photo->date )->toMySQL();
				}

				// Throw error when there's an error saving photo
				if( !$photoTable->store() )
				{
					$view->setMessage( $photoTable->getError(), SOCIAL_MSG_ERROR );

					return $view->call( __FUNCTION__ );
				}

				// Add stream item for the photos.
				$createStream 	= JRequest::getBool( 'createStream' );

				if( $createStream )
				{
					$photoTable->addPhotosStream( 'create' );
				}

				// Store / update photo location when necessary
				if( !empty( $photo->address ) && !empty( $photo->latitude ) && !empty( $photo->longitude ) )
				{
					$location 	= Foundry::location();
					$data 		= array( 'address' => $photo->address , 'longitude' => $photo->longitude , 'latitude' => $photo->latitude );
					$location->create( $photo->id , SOCIAL_TYPE_PHOTO , $my->id , $data );
				}

				$albumPhotos[] = $photoTable;
			}
		}

		// Assign the photos back to the album object
		if( !empty( $albumPhotos ) )
		{
			$album->photos = $albumPhotos;
		}

		return $view->call( __FUNCTION__ , $album );
	}

	/**
	 * Delete albums
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function delete()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the id of the album
		$id 	= JRequest::getInt( 'id' );

		// Get the current view
		$view	= $this->getCurrentView();

		// Load the album object
		$album 	= Foundry::table( 'Album' );
		$album->load( $id );

		// Check if the current user is allowed to delete an album
		$my 	= Foundry::user();

		// Checks if the user can delete
		if( !$album->deleteable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_NO_PERMISSIONS_TO_DELETE_ALBUM' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false);
		}

		// Try to delete the album
		$state 	= $album->delete();

		if( !$state )
		{
			$view->setMessage( $album->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false);
		}

		// @points: photos.albums.delete
		// Deduct points from creator when his album is deleted.
		$album->assignPoints( 'photos.albums.delete' , $album->uid );

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_ALBUM_DELETED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__, true);
	}

	/**
	 * Reordering of albums.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function reorder()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$this->getCurrentView()->call( __FUNCTION__ );
	}

	public function setCover()
	{
		$view = $this->getCurrentView();

		$albumId = JRequest::getInt( 'albumId', 0 );

		if( $albumId === 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_INVALID_ALBUM_ID_PROVIDED' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false );
		}

		$coverId = JRequest::getInt( 'coverId', 0 );

		if( $coverId === 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_INVALID_COVER_ID' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false );
		}

		// Check if the photo is in this album
		$photo = Foundry::table( 'photo' );
		$photo->load( $coverId );

		if( $photo->album_id != $albumId )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTO_NOT_IN_THIS_ALBUM' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false );
		}

		$album = Foundry::table( 'album' );
		$album->load( $albumId );

		$album->cover_id = $coverId;

		$result = $album->store();

		if( !$result )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_UNABLE_TO_SAVE_COVER_ID' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__, false );
		}

		return $view->call( __FUNCTION__, $photo );
	}

	public function loadMore()
	{
		$view = $this->getCurrentView();

		$albumId = JRequest::getInt( 'albumId', 0 );

		$start = JRequest::getInt( 'start', 0 );

		if( $start == '-1' )
		{
			return $view->call( __FUNCTION__, '', $start );
		}

		$lib = Foundry::getInstance( 'albums' );

		$result = $lib->getPhotos( $albumId, array( 'start' => $start ) );

		// This will generate $photos, $nextStart
		extract( $result );

		return $view->call( __FUNCTION__, $photos, $nextStart );
	}
}
