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

jimport( 'joomla.filesystem.file' );

class EasySocialControllerPhotos extends EasySocialController
{
	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPhoto()
	{
		// Get the current view.
		$view = $this->getCurrentView();

		// Get the album id.
		$id   = JRequest::getInt( 'id' );
		$attr = JRequest::getVar( 'attr' );

		// Load up album
		$photo = Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_PHOTO_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$data = $photo->export();

		// Decorate with additional attributes
		if (in_array('content', $attr)) {

			$album = Foundry::table( 'album' );
			$album->load( $photo->album_id );

			$creator = Foundry::user( $photo->uid );

			$theme = Foundry::themes();
			$theme->set( 'creator', $creator );
			$theme->set( 'photo'  , $photo );
			$theme->set( 'album'  , $album );

			$data['content']['inline'] = $theme->output('site/photos/content');
			$data['content']['popup']  = $theme->output('site/photos/content');
		}

		if (in_array('tags', $attr)) {
			$data['tags'] = $photo->getTags();
		}

		return $view->call(__FUNCTION__, $data);
	}

	/**
	 * Posting photos via story
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function uploadStory()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered users should be allowed to upload photos
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get current logged in user.
		$my 	= Foundry::user();

		// Get user access
		$access	= Foundry::access( $my->id , SOCIAL_TYPE_USER );

		// Set uploader options
		$options = array(
			'name' => 'file',
			'maxsize' => $access->get('photos.uploader.maxsize') . 'M'
		);

		// Get uploaded file
		$file = Foundry::uploader($options)->getFile();

		// If there was an error getting uploaded file, stop.
		if ($file instanceof SocialException) {
			$view->setMessage($file);
			return $view->call(__FUNCTION__);
		}

		// Load the iamge object
		$image 	= Foundry::image();
		$image->load( $file[ 'tmp_name' ] , $file[ 'name' ] );

		// Detect if this is a really valid image file.
		if( !$image->isValid() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_FILE_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if the albums for story exists or not.
		$albumModel	= Foundry::model( 'Albums' );

		$exists 	= $albumModel->hasDefaultAlbum( $my->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_STORY_ALBUM );

		if( !$exists )
		{
			$album 	= $albumModel->createDefaultAlbum( $my->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_STORY_ALBUM );

			// @TODO: Check for errors when creating the default album
		}
		else
		{
			$album	= Foundry::table( 'Album' );
			$album->load( array( 'uid' => $my->id , 'type' => SOCIAL_TYPE_USER , 'core' => SOCIAL_ALBUM_STORY_ALBUM ) );
		}

		$photo 				= Foundry::table( 'Photo' );
		$photo->uid 		= $my->id;
		$photo->type 		= SOCIAL_TYPE_USER;
		$photo->album_id 	= $album->id;
		$photo->title 		= $file[ 'name' ];
		$photo->caption 	= '';
		$photo->ordering	= 0;

		// Set the creation date alias
		$photo->assigned_date 	= Foundry::date()->toMySQL();

		// Let's test if exif exists
		$exif 				= Foundry::get( 'Exif' );

		// Detect the photo caption and title if exif is available.
		if( $exif->isAvailable() && $image->hasExifSupport() )
		{
			// Load the image
			$exif->load( $file[ 'tmp_name' ] );

			$title 			= $exif->getTitle();
			$caption		= $exif->getCaption();
			$createdAlias	= $exif->getCreationDate();

			if( $createdAlias )
			{
				$photo->assigned_date 	= $createdAlias;
			}

			if( $title )
			{
				$photo->title 	= $title;
			}

			if( $caption )
			{
				$photo->caption	= $caption;
			}
		}

		// Try to store the photo.
		$state 		= $photo->store();

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_UPLOAD_ERROR_STORING_DB' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Push all the ordering of the photo down
		$photosModel = Foundry::model( 'photos' );

		// Detect location for the photo
		if( $exif->isAvailable() && $image->hasExifSupport() )
		{
			$exif->load( $file[ 'tmp_name' ] );

			// Get the location
			$locationCoordinates 	= $exif->getLocation();

			// Once we have the coordinates, we need to reverse geocode it to get the address.
			if( $locationCoordinates )
			{
				$geocode 	= Foundry::get( 'GeoCode' );
				$address	= $geocode->reverse( $locationCoordinates->latitude , $locationCoordinates->longitude );

				$location 				= Foundry::table( 'Location' );
				$location->loadByType( $photo->id , SOCIAL_TYPE_PHOTO , $my->id );

				$location->address		= $address;
				$location->latitude		= $locationCoordinates->latitude;
				$location->longitude	= $locationCoordinates->longitude;
				$location->user_id 		= $my->id;
				$location->type 		= SOCIAL_TYPE_PHOTO;
				$location->uid 			= $photo->id;

				$state 	= $location->store();
			}

			// Store custom meta data for the photo
			$photosModel->storeCustomMeta( $photo , $exif );
		}

		// Get the storage path for this photo
		$storage 	= Foundry::call( 'Photos' , 'getStoragePath' , array( $album->id , $photo->id ) );

		// Get the photos library
		$photoLib 	= Foundry::get( 'Photos' , $image );
		$paths 		= $photoLib->create( $storage );

		// Create metadata about the photos
		foreach( $paths as $type => $fileName )
		{
			$meta 				= Foundry::table( 'PhotoMeta' );
			$meta->photo_id		= $photo->id;
			$meta->group 		= SOCIAL_PHOTOS_META_PATH;
			$meta->property 	= $type;
			$meta->value		= $storage . '/' . $fileName;

			$meta->store();
		}

		return $view->call( __FUNCTION__ , $photo , $paths );
	}

	/**
	 * Allows caller to upload photos
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function upload( $isAvatar = false )
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered users should be allowed to upload photos
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get current user.
		$my 	= Foundry::user();

		// Get the album id.
		$albumId = JRequest::getCmd( 'albumId' );

		$album 	= Foundry::table( 'Album' );
		$album->load( $albumId );

		// Get user access
		$access	= Foundry::access( $my->id , SOCIAL_TYPE_USER );

		// Set uploader options
		$options = array(
			'name'        => 'file',
			'maxsize' => $access->get('photos.uploader.maxsize') . 'M'
		);

		// Get uploaded file
		$file = Foundry::uploader($options)->getFile();

		// If there was an error getting uploaded file, stop.
		if ($file instanceof SocialException) {
			$view->setMessage($file);
			return $view->call(__FUNCTION__);
		}

		// Load the image object
		$image = Foundry::image();
		$image->load( $file[ 'tmp_name' ] , $file[ 'name' ] );

		// Detect if this is a really valid image file.
		if( !$image->isValid() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_FILE_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$photo 				= Foundry::table( 'Photo' );
		$photo->uid 		= $my->id;
		$photo->type 		= SOCIAL_TYPE_USER;
		$photo->album_id 	= $albumId;
		$photo->title 		= $file[ 'name' ];
		$photo->caption 	= '';
		$photo->ordering	= 0;
		$photo->state 		= SOCIAL_STATE_PUBLISHED;

		// Set the creation date alias
		$photo->assigned_date 	= Foundry::date()->toMySQL();

		// Let's test if exif exists
		$exif 				= Foundry::get( 'Exif' );

		// Cleanup photo title.
		$photo->cleanupTitle();

		// Detect the photo caption and title if exif is available.
		if( $exif->isAvailable() && $image->hasExifSupport() )
		{
			// Load the image
			$exif->load( $file[ 'tmp_name' ] );

			$title 			= $exif->getTitle();
			$caption		= $exif->getCaption();
			$createdAlias	= $exif->getCreationDate();

			if( $createdAlias )
			{
				$photo->assigned_date 	= $createdAlias;
			}

			if( $title )
			{
				$photo->title 	= $title;
			}

			if( $caption )
			{
				$photo->caption	= $caption;
			}
		}

		// Try to store the photo.
		$state 		= $photo->store();

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_UPLOAD_ERROR_STORING_DB' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Push all the ordering of the photo down
		$photosModel = Foundry::model( 'photos' );
		$photosModel->pushPhotosOrdering( $albumId, $photo->id );

		// Detect location for the photo
		if( $exif->isAvailable() && $image->hasExifSupport() )
		{
			$exif->load( $file[ 'tmp_name' ] );

			// Get the location
			$locationCoordinates 	= $exif->getLocation();

			// Once we have the coordinates, we need to reverse geocode it to get the address.
			if( $locationCoordinates )
			{
				$geocode 	= Foundry::get( 'GeoCode' );
				$address	= $geocode->reverse( $locationCoordinates->latitude , $locationCoordinates->longitude );

				$location 				= Foundry::table( 'Location' );
				$location->loadByType( $photo->id , SOCIAL_TYPE_PHOTO , $my->id );

				$location->address		= $address;
				$location->latitude		= $locationCoordinates->latitude;
				$location->longitude	= $locationCoordinates->longitude;
				$location->user_id 		= $my->id;
				$location->type 		= SOCIAL_TYPE_PHOTO;
				$location->uid 			= $photo->id;

				$state 	= $location->store();
			}

			// Store custom meta data for the photo
			$photosModel->storeCustomMeta( $photo , $exif );
		}


		// If album doesn't have a cover, set the current photo as the cover.
		if( !$album->hasCover() )
		{
			$album->cover_id 	= $photo->id;

			// Store the album
			$album->store();
		}

		// Get the photos library
		$photoLib 	= Foundry::get( 'Photos' , $image );

		// Get the storage path for this photo
		$storage 	= $photoLib->getStoragePath($album->id , $photo->id);

		$paths 		= $photoLib->create( $storage );

		// Create metadata about the photos
		foreach( $paths as $type => $fileName )
		{
			$meta 				= Foundry::table( 'PhotoMeta' );
			$meta->photo_id		= $photo->id;
			$meta->group 		= SOCIAL_PHOTOS_META_PATH;
			$meta->property 	= $type;
			$meta->value		= $storage . '/' . $fileName;

			$meta->store();
		}

		// Determine if we should create a stream item for this upload
		$createStream 	= JRequest::getBool( 'createStream' );

		// Add Stream when a new photo is uploaded
		if( $createStream )
		{
			$photo->addPhotosStream( 'create' );
		}

		// Synchronize Indexer
		$indexer 	= Foundry::get( 'Indexer' );
		$template	= $indexer->getTemplate();
		$template->setContent( $photo->title , $photo->caption );

		$url 	= FRoute::photos( array( 'layout' => 'item', 'id' => $photo->getAlias() ) );
		$url 	= '/' . ltrim( $url , '/' );
		$url 	= str_replace('/administrator/', '/', $url );

		$template->setSource( $photo->id , SOCIAL_INDEXER_TYPE_PHOTOS , $photo->uid , $url );
		$template->setThumbnail( $photo->getSource( 'thumbnail' ) );

		$indexer->index( $template );

		if( $isAvatar )
		{
			return $photo;
		}

		return $view->call( __FUNCTION__ , $photo , $paths );
	}

	/**
	 * Update photo
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function update()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// User needs to be logged in
		Foundry::requireLogin();

		// Get the photo id
		$id 	= JRequest::getInt( 'id' );

		// Get the current view
		$view	= $this->getCurrentView();

		// Loads up the photo table
		$photo	= Foundry::table( 'Photo' );
		$photo->load( $id );

		// Test if the id provided is valid.
		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NOT_FOUND' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Test if the user is really allowed to edit the photo
		if( !$photo->editable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NOT_ALLOWED_TO_EDIT_PHOTO' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the posted data
		$post 	= JRequest::get( 'post' );

		$photo->bind( $post );

		// Set the assigned_date if necessary
		$photoDate 	= JRequest::getVar( 'date' , '' );

		if( !empty( $photoDate ) )
		{
			$date 		= Foundry::date( $photoDate );

			$photo->assigned_date 	= $date->toMySQL();
		}

		$state 	= $photo->store();

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_ERROR_SAVING_PHOTO' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Get the location
		$location 				= Foundry::table( 'Location' );
		$location->loadByType( $photo->id , SOCIAL_TYPE_PHOTO , $my->id );

		$address	= JRequest::getVar( 'address' );
		$latitude 	= JRequest::getVar( 'latitude' );
		$longitude	= JRequest::getVar( 'longitude' );

		if( !empty( $address ) && !empty( $latitude) && !empty( $longitude) )
		{
			$location->address		= $address;
			$location->latitude		= $latitude;
			$location->longitude	= $longitude;
			$location->user_id 		= $my->id;
			$location->type 		= SOCIAL_TYPE_PHOTO;
			$location->uid 			= $photo->id;

			$location->store();
		}

		return $view->call( __FUNCTION__ , $photo );
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

		// Get id from request
		$id 	= JRequest::getInt( 'id' );

		// Get the view
		$view 	= $this->getCurrentView();

		// Get the current logged in user
		$my 	= Foundry::user();

		$photo	= Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id && !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Test if the user is allowed to delete the photo
		if( !$photo->deleteable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NO_PERMISSION_TO_DELETE_PHOTO' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if this photo is cover or not
		$isAlbumCover	= $photo->isCover();

		// Check if this photo is used as a profile cover
		$isProfileCover	= $photo->isProfileCover();

		$state		= $photo->delete();

		if( !$state )
		{
			$view->setMessage( $photo->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Delete the profile cover if the photo is a profile cover.
		if( $isProfileCover )
		{
			$cover 	= Foundry::table( 'Cover' );
			$cover->load( array( 'photo_id' => $photo->id ) );

			$cover->delete();
		}

		// If is cover, then get the new cover
		$newCover = $isAlbumCover ? $photo->getAlbum()->getCover() : false;

		// Let the world know that the photo is deleted successfully.
		// $view->setMessage( 'COM_EASYSOCIAL_PHOTOS_PHOTO_DELETED_SUCCESSFULLY' , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__, $newCover );
	}

	/**
	 * Allows caller to rotate a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function rotate()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered users should be allowed to rotate photos
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get photo id
		$id = JRequest::getInt( 'id' );

		// Get photo
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_PHOTO_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Rotate photo
		$tmpAngle	= JRequest::getInt( 'angle' );

		// Get the real angle now.
		$angle 	= $photo->getAngle() + $tmpAngle;

		// Update the angle
		$photo->updateAngle( $angle );

		// Get destination folder path.
		$config 	= Foundry::config();

		// Delete the previous images that are generated except the stock version
		$photo->deletePhotos( array( 'thumbnail' , 'large' , 'original' , 'featured', 'square' ) );

		// Rotate the photo
		$image 	= Foundry::image();
		$image->load( $photo->getPath( 'stock' ) );

		// Rotate the new image
		$image->rotate( $angle );

		// Save photo
		$photoLib 	= Foundry::get( 'Photos' , $image );

		// Get the storage path
		$storage 	= $photoLib->getStoragePath( $photo->album_id , $photo->id );

		$paths 		= $photoLib->create( $storage , array( 'stock' ) );

		// When a photo is rotated, we would also need to rotate the tags as well
		$photo->rotateTags( $tmpAngle );

		// Create metadata about the photos
		foreach( $paths as $type => $fileName )
		{
			$meta 				= Foundry::table( 'PhotoMeta' );
			$meta->photo_id		= $photo->id;
			$meta->group 		= SOCIAL_PHOTOS_META_PATH;
			$meta->property 	= $type;
			$meta->value		= $storage . '/' . $fileName;

			$meta->store();
		}

		// Reload photo
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		// Once image is rotated, we'll need to update the photo source back to "joomla" because
		// we will need to re-upload the image again when synchroinization happens.
		$photo->storage 	= SOCIAL_STORAGE_JOOMLA;
		$photo->store();

		return $view->call( __FUNCTION__ , $photo , $paths );
	}

	/**
	 * Allows caller to feature a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function feature()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$id 	= JRequest::getInt( 'id' );

		// Get current view
		$view 	= $this->getCurrentView();

		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Test if the user is allowed to feature the photo
		if( !$photo->featureable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NOT_ALLOWED_TO_FEATURE_PHOTO' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// If photo is previously not featured, it is being featured now.
		$isFeatured 	= !$photo->featured ? true : false;

		// Toggle the featured state
		$photo->toggleFeatured();

		return $view->call( __FUNCTION__ , $isFeatured );
	}

	/**
	 * Allows caller to move a photo over to album
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function move()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the view
		$view 		= $this->getCurrentView();

		// Get the current photo id.
		$id 		= JRequest::getInt( 'id' );

		// Get the album id to move this photo to.
		$albumId 	= JRequest::getInt( 'albumId' );

		if( !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		if( !$albumId )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_ALBUM_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}


		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		// Check if the user can actually manage this photo
		if( !$photo->moveable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NO_PERMISSION_TO_MOVE_PHOTO' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		if( !$photo->move( $albumId ) )
		{
			$view->setMessage( $photo->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_PHOTO_MOVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allows caller to download a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function download()
	{
		// Check for request forgeries
		Foundry::checkToken();
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

		$view = $this->getCurrentView();

				$view->setMessage( JText::_( 'Unable to reorder photos.' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );

		$id = JRequest::getInt( 'id', 0 );
		$order = JRequest::getInt( 'order', 0 );

		if( $id )
		{
			$photo = Foundry::table( 'photo' );
			$photo->load( $id );

			// $order > $photo->ordering = pulled backward
			// ordering in between $photo->ordering and $order
			// $order < $photo->ordering = pulled forward
			// ordering in between $order and $photo->ordering
			$query = '';
			if( $order > $photo->ordering )
			{
				$query = "UPDATE `#__social_photos` SET `ordering` = `ordering` - 1 WHERE `album_id` = '" . $photo->album_id . "' AND `ordering` > '" . $photo->ordering . "' AND `ordering` <= '" . $order . "'";
			}
			else
			{
				$query = "UPDATE `#__social_photos` SET `ordering` = `ordering` + 1 WHERE `album_id` = '" . $photo->album_id . "' AND `ordering` < '" . $photo->ordering . "' AND `ordering` >= '" . $order . "'";
			}

			$db = Foundry::db();
			$sql = $db->sql();

			$sql->raw( $query );

			$db->setQuery( $sql );
			$db->query();

			$photo->ordering = $order;

			// Try to store the photo.
			$state = $photo->store();

			if( !$state )
			{
				$view->setMessage( JText::_( 'Unable to reorder photos.' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		$view->call( __FUNCTION__ );
	}

	/**
	 * Deletes a tag
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteTag()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the photo id from the request.
		$id 	= JRequest::getInt( 'tag_id' );

		// Get the current logged in user
		$my 	= Foundry::user();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get posted data from request
		$post 	= JRequest::get( 'POST' );

		$tag 	= Foundry::table( 'PhotoTag' );
		$tag->load( $id );

		if( !$tag->deleteable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NOT_ALLOWED_TO_DELETE_TAG' ) , SOCIAL_MSG_ERROR );
			$view->call( __FUNCTION__ );
		}

		if( !$tag->delete() )
		{
			$view->setMessage( $tag->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// @points: photos.untag
		// Deduct points from the user for untagging an item
		$photo->assignPoints( 'photos.untag' , $my->id );


		return $view->call( __FUNCTION__ );
	}

	/**
	 * Creates a new tag
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createTag()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Require only logged in user to perform this action
		Foundry::requireLogin();

		// Get the photo id from the request.
		$id 	= JRequest::getInt( 'photo_id' );

		// Get the current logged in user
		$my 	= Foundry::user();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Load up the photo table
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		// Check if the photo id is valid
		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_PHOTO_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Test if the user is really allowed to tag this photo
		if( !$photo->taggable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NOT_ALLOWED_TO_TAG_PHOTO' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get posted data from request
		$post 	= JRequest::get( 'POST' );

		$tag 	= Foundry::table( 'PhotoTag' );
		$tag->bind( $post );

		// If there's empty label and the uid is not supplied, we need to throw an error
		if( empty( $tag->label ) && !$tag->uid )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_EMPTY_TAG_NOT_ALLOWED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Set the photo id.
		$tag->photo_id 		= $photo->id;
		$tag->created_by 	= $my->id;

		$state 	= $tag->store();

		// Try to store the new tag.
		if( !$state )
		{
			$view->setMessage( $tag->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// @points: photos.tag
		// Assign points to the current user for tagging items
		$photo->assignPoints( 'photos.tag' , $my->id );

		// Only notify persons if the photo is tagging a person
		if( $tag->uid && $tag->type == 'person' && $tag->uid != $my->id )
		{
			$systemOptions		= array(
											// The unique node id here is the #__social_friend id.
											'uid'			=> $tag->id,
											'title'			=> JText::_( 'COM_EASYSOCIAL_PHOTOS_NOTIFICATIONS_TAGGED' ),
											'type'			=> SOCIAL_TYPE_PHOTO,
											'context_type'	=> 'tagging',
											'url'			=> FRoute::photos( array( 'id' => $photo->getAlias() , 'layout' => 'item' ) , false ),
											'target_id'		=> $tag->uid,
											'actor_id'		=> $my->id,
											'image'			=> $photo->getSource()
										);

			$params 	= array(
									'photoTitle'		=> $photo->get( 'title' ),
									'photoPermalink'	=> $photo->getPermalink(),
									'photoThumbnail'	=> $photo->getSource( 'thumbnail' ),
									'actorName'			=> $my->getName(),
									'actorPermalink'	=> $my->getPermalink()
								);

			// Email template
			$emailOptions 		= array(
											'title'		=> JText::sprintf( 'COM_EASYSOCIAL_EMAILS_YOU_ARE_TAGGED_IN_TITLE' , $my->getName() ),
											'template'	=> 'site/photos/tagged',
											'params'	=> $params
										);
			Foundry::notify( 'photos.tagged' , array( $tag->uid ) , $emailOptions , $systemOptions );

			// Assign a badge to the user
			$photo->assignBadge( 'photos.tag' , $my->id );

			// Assign a badge to the user that is being tagged
			if( $my->id != $tag->uid )
			{
				$photo->assignBadge( 'photos.superstar' , $tag->uid );
			}
		}

		return $view->call( __FUNCTION__ , $tag , $photo );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTags()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the photo id from the request.
		$id 	= JRequest::getInt( 'photo_id' );

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the photo object.
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'Invalid photo id provided' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Retrieve the list of tags for this photo
		$tags 	= $photo->getTags();

		return $view->call( __FUNCTION__ , $tags );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function removeTag()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the photo id from the request.
		$id 	= JRequest::getInt( 'id' );

		// Get the current logged in user
		$my 	= Foundry::user();

		// Get the current view
		$view 	= $this->getCurrentView();

		$tag 	= Foundry::table( 'PhotoTag' );
		$tag->load( $id );

		if( !$id || !$tag->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_TAG_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// If user is not allowed to delete the tag, throw an error
		if( !$tag->deleteable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_NOT_ALLOWED_TO_DELETE_TAG' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Try to delete the tag.
		$state 	= $tag->delete();

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_ERROR_REMOVING_TAG' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allows caller to set profile photo based on the photo that they have.
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function createAvatar()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered users should be allowed to upload photos
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the photo id
		$id		= JRequest::getInt( 'id' );

		// Try to load the photo.
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		// Try to load the photo with the provided id.
		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// Get the image object for the photo
		// Use "original" not "stock" because it might be rotated before this.
		$image		= $photo->getImageObject( 'stock' );

		// Need to rotate as necessary here because we're loading up using the stock photo and the stock photo
		// is as is when the user initially uploaded.
		$image->rotate( $photo->getAngle() );

		$tmp 		= JFactory::getConfig()->get( 'tmp_path' );
		$tmpPath 	= $tmp . '/' . md5( $photo->id ) . $image->getExtension();

		$image->save( $tmpPath );
		unset( $image );

		$image 		= Foundry::image();
		$image->load( $tmpPath );

		// Get the current user.
		$my 		= Foundry::user();

		// Load up the avatar library
		$avatar 	= Foundry::avatar( $image , $my->id , SOCIAL_TYPE_USER );

		// Crop the image to follow the avatar format. Get the dimensions from the request.
		$width 		= JRequest::getVar( 'width' );
		$height		= JRequest::getVar( 'height' );
		$top	    = JRequest::getVar( 'top' );
		$left		= JRequest::getVar( 'left' );

		// We need to get the temporary path so that we can delete it later once everything is done.
		$avatar->crop( $top , $left , $width , $height );

		// Create the avatars now
		$avatar->store( $photo );

		// Delete the temporary file.
		JFile::delete( $tmpPath );

		return $view->call( __FUNCTION__ , $photo );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createAvatarFromFile()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered users should be allowed to upload photos
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();
		$config 	= Foundry::config();

		// Get current user.
		$my 	= Foundry::user();

		// Get the file
		$file 	= JRequest::getVar( 'avatar_file' , '' , 'FILES' );


		if( !isset( $file[ 'tmp_name' ] ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_FILE_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'createAvatar' );
		}

		// Get user access
		$access	= Foundry::access( $my->id , SOCIAL_TYPE_USER );

		// Check if the filesize is too large
		$maxFilesize = $access->get('photos.uploader.maxsize');
		$maxFilesizeBytes = (int) $access->get('photos.uploader.maxsize') * 1048576;

		if ($file['size'] > $maxFilesizeBytes) {
			$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_PHOTOS_UPLOAD_ERROR_FILE_SIZE_LIMIT_EXCEEDED', $maxFilesize . 'mb' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'createAvatar' );
		}

		// Load the image
		$image 	= Foundry::image();
		$image->load( $file[ 'tmp_name' ] , $file[ 'name' ] );

		// Check if there's a profile photos album that already exists.
		$albumModel	= Foundry::model( 'Albums' );

		// Retrieve the user's default album
		$album 	= $albumModel->getDefaultAlbum( $my->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_PHOTOS );

		$photo 				= Foundry::table( 'Photo' );
		$photo->uid 		= $my->id;
		$photo->type 		= SOCIAL_TYPE_USER;
		$photo->album_id 	= $album->id;
		$photo->title 		= $file[ 'name' ];
		$photo->caption 	= '';
		$photo->ordering	= 0;

		// Set the creation date alias
		$photo->assigned_date 	= Foundry::date()->toMySQL();

		// We need to set the photo state to "SOCIAL_PHOTOS_STATE_TMP"
		$photo->state 		= SOCIAL_PHOTOS_STATE_TMP;

		// Try to store the photo first
		$state 		= $photo->store();

		// Bind any exif data if there are any.
		// Only bind exif data for jpg files (if want to add tiff, then do add it here)
		if( $image->hasExifSupport() )
		{
			$photo->mapExif( $file );
		}

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_ERROR_CREATING_IMAGE_FILES' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'createAvatar' );
		}

		// Push all the ordering of the photo down
		$photosModel = Foundry::model( 'photos' );
		$photosModel->pushPhotosOrdering( $album->id , $photo->id );

		// Render photos library
		$photoLib 	= Foundry::get( 'Photos' , $image );
		$storage 	= $photoLib->getStoragePath( $album->id , $photo->id );
		$paths 		= $photoLib->create( $storage );

		// Create metadata about the photos
		foreach( $paths as $type => $fileName )
		{
			$meta 				= Foundry::table( 'PhotoMeta' );
			$meta->photo_id		= $photo->id;
			$meta->group 		= SOCIAL_PHOTOS_META_PATH;
			$meta->property 	= $type;
			$meta->value		= $storage . '/' . $fileName;

			$meta->store();
		}

		// Retrieve the original photo again.
		$image 		= $photo->getImageObject( 'original' );

		return $view->call( 'createAvatar' , $photo );
	}


	/**
	 * Allows caller to create a cover photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createCover()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Only registered member can use this
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user
		$my 	= Foundry::user();

		$x 		= JRequest::getVar( 'x' );
		$y 		= JRequest::getVar( 'y' );

		// Get photo id from request.
		$id 	= JRequest::getInt( 'id' );

		// Load the photo
		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id || !$photo->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_PHOTO_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Load the cover
		$cover 	= Foundry::table( 'Cover' );
		$state 	= $cover->load( array( 'uid' => $my->id , 'type' => SOCIAL_TYPE_USER ) );

		// User does not have a cover.
		if( !$state )
		{
			$cover->uid 	= $my->id;
			$cover->type 	= SOCIAL_TYPE_USER;
		}

		// Set the cover to pull from photo
		$cover->setPhotoAsCover( $photo->id , $x , $y );

		// Save the cover.
		$cover->store();

		// @Add stream item when a new profile avatar is uploaded
		$photo->addPhotosStream( 'updateCover' );

		// Set the photo state to 1 since the user has already confirmed to set it as cover
		$photo->state 	= SOCIAL_STATE_PUBLISHED;
		$photo->store();

		return $view->call( __FUNCTION__ , $cover );
	}

	/**
	 * Allows caller to upload a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function uploadCover()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Ensure that the user must be logged in
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Get user access
		$access	= Foundry::access( $my->id , SOCIAL_TYPE_USER );

		// Set uploader options
		$options = array(
			'name'        => 'cover_file',
			'maxsize' => $access->get('photos.uploader.maxsize') . 'M'
		);

		// Get uploaded file
		$file = Foundry::uploader($options)->getFile();

		// If there was an error getting uploaded file, stop.
		if ($file instanceof SocialException) {
			$view->setMessage($file);
			return $view->call(__FUNCTION__);
		}

		// Load the image
		$image 	= Foundry::image();
		$image->load( $file[ 'tmp_name' ] , $file[ 'name' ] );

		// Check if there's a profile photos album that already exists.
		$albumModel	= Foundry::model( 'Albums' );

		$exists 	= $albumModel->hasDefaultAlbum( $my->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_COVERS );

		if( !$exists )
		{
			$album 	= $albumModel->createDefaultAlbum( $my->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_COVERS );
		}
		else
		{
			$album	= Foundry::table( 'Album' );
			$album->load( array( 'uid' => $my->id , 'type' => SOCIAL_TYPE_USER , 'core' => SOCIAL_ALBUM_PROFILE_COVERS ) );
		}

		$photo 				= Foundry::table( 'Photo' );
		$photo->uid 		= $my->id;
		$photo->type 		= SOCIAL_TYPE_USER;
		$photo->album_id 	= $album->id;
		$photo->title 		= $file[ 'name' ];
		$photo->caption 	= '';
		$photo->ordering	= 0;

		// Set the creation date alias
		$photo->assigned_date 	= Foundry::date()->toMySQL();

		// Let's test if exif exists
		$exif 	= Foundry::get( 'Exif' );

		// Detect the photo caption and title if exif is available.
		if( $image->hasExifSupport() && $exif->isAvailable() )
		{
			$exif->load( $file[ 'tmp_name' ] );

			$title 		= $exif->getTitle();
			$caption	= $exif->getCaption();

			if( $title )
			{
				$photo->title 	= $title;
			}

			if( $caption )
			{
				$photo->caption	= $caption;
			}
		}

		// Try to store the photo.
		$state 		= $photo->store();

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_ERROR_CREATING_IMAGE_FILES' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Push all the ordering of the photo down
		$photosModel = Foundry::model( 'photos' );
		$photosModel->pushPhotosOrdering( $album->id , $photo->id );

		// Detect location for the photo
		if( $image->hasExifSupport() && $exif->isAvailable() )
		{
			$exif->load( $file[ 'tmp_name' ] );

			// Get the location
			$locationCoordinates 	= $exif->getLocation();

			// Once we have the coordinates, we need to reverse geocode it to get the address.
			if( $locationCoordinates )
			{
				$geocode 	= Foundry::get( 'GeoCode' );
				$address	= $geocode->reverse( $locationCoordinates->latitude , $locationCoordinates->longitude );

				$location 				= Foundry::table( 'Location' );
				$location->loadByType( $photo->id , SOCIAL_TYPE_PHOTO , $my->id );

				$location->address		= $address;
				$location->latitude		= $locationCoordinates->latitude;
				$location->longitude	= $locationCoordinates->longitude;
				$location->user_id 		= $my->id;
				$location->type 		= SOCIAL_TYPE_PHOTO;
				$location->uid 			= $photo->id;

				$state 	= $location->store();
			}

			// Store custom meta data for the photo
			$photosModel->storeCustomMeta( $photo , $exif );
		}


		// If album doesn't have a cover, set the current photo as the cover.
		if( !$album->hasCover() )
		{
			$album->cover_id 	= $photo->id;

			// Store the album
			$album->store();
		}

		// Render photos library
		$photoLib 	= Foundry::get( 'Photos' , $image );
		$storage 	= $photoLib->getStoragePath( $album->id , $photo->id );
		$paths 		= $photoLib->create( $storage );

		// Create metadata about the photos
		foreach( $paths as $type => $fileName )
		{
			$meta 				= Foundry::table( 'PhotoMeta' );
			$meta->photo_id		= $photo->id;
			$meta->group 		= SOCIAL_PHOTOS_META_PATH;
			$meta->property 	= $type;
			$meta->value		= $storage . '/' . $fileName;

			$meta->store();
		}

		return $view->call( __FUNCTION__ , $photo );
	}

	/**
	 * Allows caller to remove a photo
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function removeCover()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the user object
		$my 	= Foundry::user();

		$my->deleteCover();

		return $view->call( __FUNCTION__ );
	}

}
