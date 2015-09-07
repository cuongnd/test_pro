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

// Include the fields library
Foundry::import( 'admin:/includes/fields/fields' );

require_once( dirname( __FILE__ ) . '/helper.php' );

/**
 * Field application for Avatar
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserAvatar extends SocialFieldItem
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
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegister( &$post, &$registration )
	{
		// Load the default avatars
		$model 		= Foundry::model( 'Avatars' );
		$avatars 	= $model->getDefaultAvatars( $registration->profile_id );

		$this->set( 'avatars'		, $avatars );

		// Set the blank avatar
		$this->set( 'imageSource', 'media/com_easysocial/defaults/avatars/users/square.png' );

		// Set errors
		$error	= $registration->getErrors( $this->inputName );

		$this->set( 'error', $error );

		// Display the output.
		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterValidate( &$post, SocialTableRegistration &$registration )
	{
		$state 	= $this->validate( $post, $registration->profile_id );

		return $state;
	}

	/**
	 * Once a user registration is completed, the field should automatically
	 * move the temporary avatars into the user's folder if required.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onRegisterAfterSave( &$post, &$user )
	{
		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		if( !empty( $value ) )
		{
			$this->createAvatar( $value, $user->id );
		}

		unset( $post[ $this->inputName ] );
	}


	/**
	 * Processes before the user account is created when user signs in with oauth.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onRegisterOAuthAfterSave( &$data, &$oauthClient, SocialUser &$user )
	{
		// Let's see if avatarUrl is provided.
		if( !isset( $data[ 'avatar' ] ) || empty( $data[ 'avatar' ] ) )
		{
			return;
		}

		$avatarUrl	 		= $data[ 'avatar' ];

		// Store the avatar internally.
		$key 				= md5( $data[ 'oauth_id' ] . $data[ 'username' ] );
		$tmpAvatarPath 		= SOCIAL_MEDIA . '/tmp/' . $key;
		$tmpAvatarFile 		= $tmpAvatarPath . '/' . $key;

		jimport( 'joomla.filesystem.folder' );

		if( !JFolder::exists( $tmpAvatarPath ) )
		{
			$state 	= JFolder::create( $tmpAvatarPath );

			if( !$state )
			{
				Foundry::logError( __FILE__, __LINE__, 'OAUTH: Unable to create avatar folder.' );
			}
		}

		$connector 	= Foundry::get( 'Connector' );
		$connector->addUrl( $avatarUrl );
		$connector->connect();

		$contents 	= $connector->getResult( $avatarUrl );

		jimport( 'joomla.filesystem.file' );

		if( !JFile::write( $tmpAvatarFile, $contents ) )
		{
			dump( 'here' );
			Foundry::logError( __FILE__, __LINE__, 'AVATAR: Unable to store oauth avatar to tmp folder, ' . $tmpAvatarFile );
			return;
		}

		$image = Foundry::image();
		$image->load( $tmpAvatarFile );

		$avatar		= Foundry::avatar( $image , $user->id , SOCIAL_TYPE_USER );

		// Check if there's a profile photos album that already exists.
		$albumModel	= Foundry::model( 'Albums' );

		// Retrieve the user's default album
		$album		= $albumModel->getDefaultAlbum( $user->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_PHOTOS );

		$photo 				= Foundry::table( 'Photo' );
		$photo->uid 		= $user->id;
		$photo->type 		= SOCIAL_TYPE_USER;
		$photo->album_id 	= $album->id;
		$photo->title 		= $user->getName();
		$photo->caption 	= JText::_( 'Photo imported from Facebook' );
		$photo->ordering	= 0;

		// We need to set the photo state to "SOCIAL_PHOTOS_STATE_TMP"
		$photo->state 		= SOCIAL_PHOTOS_STATE_TMP;

		// Try to store the photo first
		$state 		= $photo->store();

		if( !$state )
		{
			$this->setError( JText::_( 'PLG_FIELDS_AVATAR_ERROR_CREATING_PHOTO_OBJECT' ) );
			return false;
		}

		// Push all the ordering of the photo down
		$photosModel = Foundry::model( 'photos' );
		$photosModel->pushPhotosOrdering( $album->id , $photo->id );

		// If album doesn't have a cover, set the current photo as the cover.
		if( !$album->hasCover() )
		{
			$album->cover_id 	= $photo->id;

			// Store the album
			$album->store();
		}

		// Get the photos library
		$photoLib 	= Foundry::get( 'Photos' , $image );
		$storage   = $photoLib->getStoragePath($album->id, $photo->id);
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

		// Assign a badge for the user
		$photo->assignBadge( 'photos.upload' , $user->id );

		// @points: photos.upload
		// Assign points when user uploads a new photo
		$photo->assignPoints( 'photos.upload' , $user->id );

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

		// Create the avatars now
		$avatar->store( $photo );

		// Once we are done creating the avatar, delete the temporary folder.
		$state		= JFolder::delete( $tmpAvatarPath );
	}

	/**
	 * Displays the field input for user when they edit their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @param	Array			The post data.
	 * @param	Array			The error data.
	 * @return	string			The html string of the field
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		// Load the default avatars
		$model 		= Foundry::model( 'Avatars' );
		$avatars 	= $model->getDefaultAvatars( $user->profile_id );

		$imageSource = $user->getAvatar( SOCIAL_AVATAR_SQUARE );

		$this->set( 'imageSource'	, $imageSource );
		$this->set( 'avatars'		, $avatars );

		// Set errors
		$error = $this->getError( $errors );

		$this->set( 'error', $error );

		// Display the output.
		return $this->display();
	}

	/**
	 * Performs validation checks when a user edits their profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onAdminEditValidate( &$post, &$user )
	{
		// Admins shouldn't need to validate
		return true;

		$profileId 	= isset( $post[ 'profileId' ] ) ? $post[ 'profileId' ] : $user->profile_id;

		$state 		= $this->validate( $post, $profileId, $user );

		return $state;
	}

	/**
	 * Performs validation checks when a user edits their profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onEditValidate( &$post, &$user )
	{
		$state 	= $this->validate( $post, $user->profile_id, $user );

		return $state;
	}

	/**
	 * Once a user edit is completed, the field should automatically
	 * move the temporary avatars into the user's folder if required.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onEditAfterSave( &$post, &$user )
	{
		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		if( !empty( $value ) )
		{
			$this->createAvatar( $value, $user->id );
		}

		unset( $post[ $this->inputName ] );
	}

	/**
	 * Retrieves the default profile pictures album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getDefaultAlbum( $userId = null )
	{
		if( is_null( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		// Check if there's a profile photos album that already exists.
		$albumModel	= Foundry::model( 'Albums' );

		$exists 	= $albumModel->hasDefaultAlbum( $userId, SOCIAL_TYPE_USER, SOCIAL_ALBUM_PROFILE_PHOTOS );

		if( !$exists )
		{
			$album 	= $albumModel->createDefaultAlbum( $userId, SOCIAL_TYPE_USER, SOCIAL_ALBUM_PROFILE_PHOTOS );
		}
		else
		{
			$album	= Foundry::table( 'Album' );
			$album->load( array( 'uid' => $userId, 'type' => SOCIAL_TYPE_USER, 'core' => SOCIAL_ALBUM_PROFILE_PHOTOS ) );
		}

		return $album;
	}

	/**
	 * Performs validation checks when a user edits their profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function validate( &$post, $profileId, $user = null )
	{
		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		if( ( empty( $user ) || !$user->hasAvatar() ) && $this->isRequired() && empty( $value ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_AVATAR_VALIDATION_EMPTY_PROFILE_PICTURE' ) );

			return false;
		}

		if( !empty( $value ) )
		{
			$value = Foundry::json()->decode( $value );

			if( ( empty( $user ) || !$user->hasAvatar() ) && $this->isRequired() && empty( $value->source ) )
			{
				$this->setError( JText::_( 'PLG_FIELDS_AVATAR_VALIDATION_EMPTY_PROFILE_PICTURE' ) );

				return false;
			}

			// if( $value->type === 'gallery' )
			// {
			// 	// Load avatars model.
			// 	$model 	= Foundry::model( 'Avatars' );

			// 	// If user pre-selected an avatar, we need to check if the avatar can be used for this profile type.
			// 	if( !$model->isAllowed( $value->source, $profileId, SOCIAL_TYPE_PROFILES ) )
			// 	{
			// 		$this->setError( JText::_( 'PLG_FIELDS_AVATAR_VALIDATION_GALLERY_NOT_ALLOWED' ) );

			// 		return false;
			// 	}
			// }
		}

		return true;
	}

	public function createAvatar( $value, $userid )
	{
		$value = Foundry::makeObject( $value );

		if( !empty( $value->data ) )
		{
			$value->data = Foundry::makeObject( $value->data );
		}

		if( $value->type === 'gallery' )
		{
			$table = Foundry::table( 'avatar' );
			$state = $table->load( array( 'uid' => $userid, 'type' => SOCIAL_TYPE_USER ) );

			if( !$state )
			{
				$table->uid = $userid;
				$table->type = SOCIAL_TYPE_USER;
			}

			$table->avatar_id = $value->source;

			$table->store();

			return true;
		}

		if( $value->type === 'upload' )
		{
			$data = new stdClass();

			if( !empty( $value->path ) )
			{
				$image = Foundry::image();
				$image->load( $value->path );

				$avatar	= Foundry::avatar( $image, $userid, SOCIAL_TYPE_USER );

				// Check if there's a profile photos album that already exists.
				$albumModel	= Foundry::model( 'Albums' );

				// Retrieve the user's default album
				$album 	= $albumModel->getDefaultAlbum( $userid , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_PHOTOS );

				$photo 				= Foundry::table( 'Photo' );
				$photo->uid 		= $userid;
				$photo->type 		= SOCIAL_TYPE_USER;
				$photo->album_id 	= $album->id;
				$photo->title 		= $value->name;
				$photo->caption 	= '';
				$photo->ordering	= 0;

				// We need to set the photo state to "SOCIAL_PHOTOS_STATE_TMP"
				$photo->state 		= SOCIAL_PHOTOS_STATE_TMP;

				// Try to store the photo first
				$state 		= $photo->store();

				if( !$state )
				{
					$this->setError( JText::_( 'PLG_FIELDS_AVATAR_ERROR_CREATING_PHOTO_OBJECT' ) );
					return false;
				}

				// Push all the ordering of the photo down
				$photosModel = Foundry::model( 'photos' );
				$photosModel->pushPhotosOrdering( $album->id , $photo->id );

				// If album doesn't have a cover, set the current photo as the cover.
				if( !$album->hasCover() )
				{
					$album->cover_id 	= $photo->id;

					// Store the album
					$album->store();
				}

				// Get the photos library
				$photoLib 	= Foundry::get( 'Photos' , $image );
				$storage    = $photoLib->getStoragePath($album->id, $photo->id);
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

				// Assign a badge for the user
				$photo->assignBadge( 'photos.upload' , $userid );

				// @points: photos.upload
				// Assign points when user uploads a new photo
				$photo->assignPoints( 'photos.upload' , $userid );

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

				// Crop the image to follow the avatar format. Get the dimensions from the request.
				if( !empty( $value->data ) && is_object( $value->data ) )
				{
					$width = $value->data->width;
					$height = $value->data->height;
					$top = $value->data->top;
					$left = $value->data->left;

					$avatar->crop( $top , $left , $width , $height );
				}

				// Create the avatars now
				$avatar->store( $photo );
			}

			return true;
		}
	}

	// /**
	//  * Creates the avatar object
	//  *
	//  * @since	1.0
	//  * @access	public
	//  * @param	string
	//  * @return
	//  */
	// public function createAvatar( &$post, $userId, $inputName )
	// {
	// 	// Get the session object.
	// 	$session 		= JFactory::getSession();

	// 	// Build the temporary path.
	// 	$tmp 			= SocialFieldsUserAvatarHelper::getStoragePath( $inputName, false );

	// 	// Get the user object.
	// 	$user 			= Foundry::user();

	// 	// Get the value from the posted data
	// 	$value 			= isset( $post[ $inputName ] ) ? $post[ $inputName ] : '';

	// 	if( !$value )
	// 	{
	// 		return;
	// 	}

	// 	// Decode value
	// 	$value			= Foundry::makeObject( $value );

	// 	// Determine if it is gallery avatar
	// 	$isGalleryAvatar = $value->type === 'gallery';

	// 	// Load avatar table
	// 	$avatar 		= Foundry::table( 'Avatar' );
	// 	$avatar->load( array( 'uid' => $userId, 'type' => SOCIAL_TYPE_USER ) );

	// 	// Set the avatar composite indices.
	// 	$avatar->uid 	= $userId;
	// 	$avatar->type 	= SOCIAL_TYPE_USER;

	// 	// Set the last modified time to now.
	// 	$avatar->modified 	= Foundry::date()->toMySQL();

	// 	// If user is choosing from one of the predefined avatars, skip the rest.
	// 	$avatar->avatar_id 	= $isGalleryAvatar ? $value->source : 0;

	// 	if( $isGalleryAvatar )
	// 	{
	// 		$avatar->store();
	// 		return $avatar;
	// 	}

	// 	// If it is not gallery avatar, then decode the uploaded data
	// 	$avatarData 	= Foundry::makeObject( $value->source );

	// 	// Create the files for the avatar
	// 	$avatarSizes	= $this->createAvatarFiles( $tmp, $avatarData, $userId );

	// 	foreach( $avatarSizes as $size => $value )
	// 	{
	// 		$avatar->$size 	= $value;
	// 	}

	// 	// Get the default profile pictures album
	// 	$album		= $this->getDefaultAlbum();

	// 	// Create the new photo object.
	// 	$photo		= $this->createPhoto( $album, $tmp, $avatarData, $userId );

	// 	// Store the avatar
	// 	$avatar->store();

	// 	// Return the avatar object
	// 	return $avatar;
	// }

	// /**
	//  * Creates the avatar files
	//  *
	//  * @since	1.0
	//  * @access	public
	//  * @param	string
	//  * @return
	//  */
	// public function createAvatarFiles( $tmpPath, $avatarData, $userId )
	// {
	// 	$stockFile 		= $tmpPath . '/' . $avatarData->stock;
	// 	$tmpStockFile	= $tmpPath . '/' . md5( $stockFile );

	// 	// Copy the photo stock file
	// 	JFile::copy( $stockFile, $tmpStockFile );

	// 	// Once the photo is created, we need to store the avatars
	// 	$image 	= Foundry::image();
	// 	$image->load( $tmpStockFile );

	// 	// Generate the storage path for the user's avatar.
	// 	$storage 	= Foundry::call( 'Avatar', 'getStoragePath', array( $userId, SOCIAL_TYPE_USER ) );

	// 	// Process avatars
	// 	$avatarLib 		= Foundry::get( 'Avatar', $image );
	// 	$avatarSizes	= $avatarLib->create( $storage );

	// 	return $avatarSizes;
	// }

	// /**
	//  * Create photo
	//  *
	//  * @since	1.0
	//  * @access	public
	//  * @param	string
	//  * @return
	//  */
	// public function createPhoto( $album, $tmpPath, $avatarData, $userId )
	// {
	// 	// Generate new photo object.
	// 	$photo 				= Foundry::table( 'Photo' );
	// 	$photo->uid 		= $userId;
	// 	$photo->type 		= SOCIAL_TYPE_USER;
	// 	$photo->album_id 	= $album->id;
	// 	$photo->title 		= $avatarData->title;
	// 	$photo->caption 	= '';
	// 	$photo->ordering	= 0;

	// 	// Let's test if exif exists
	// 	$exif 	= Foundry::get( 'Exif' );

	// 	$path 	= $tmpPath 	. '/' . $avatarData->stock;

	// 	// Detect the photo caption and title if exif is available.
	// 	if( $exif->isAvailable() )
	// 	{
	// 		$exif->load( $path );

	// 		$photo->title 	= $exif->getTitle() ? $exif->getTitle() : $photo->title;
	// 		$photo->caption	= $exif->getCaption() ? $exif->getCaption() : $photo->caption;
	// 	}

	// 	// Try to store the photo.
	// 	$state 		= $photo->store();

	// 	if( !$state )
	// 	{
	// 		return false;
	// 	}

	// 	// Push all the ordering of the photo down
	// 	$photosModel = Foundry::model( 'photos' );
	// 	$photosModel->pushPhotosOrdering( $album->id, $photo->id );

	// 	// Detect location for the photo
	// 	if( $exif->isAvailable() )
	// 	{
	// 		// Get the location
	// 		$locationCoordinates 	= $exif->getLocation();

	// 		// Once we have the coordinates, we need to reverse geocode it to get the address.
	// 		if( $locationCoordinates )
	// 		{
	// 			$geocode 				= Foundry::get( 'GeoCode' );
	// 			$address				= $geocode->reverse( $locationCoordinates->latitude, $locationCoordinates->longitude );

	// 			$location 				= Foundry::table( 'Location' );
	// 			$location->loadByType( $photo->id, SOCIAL_TYPE_PHOTO, $userId );

	// 			$location->address		= $address;
	// 			$location->latitude		= $locationCoordinates->latitude;
	// 			$location->longitude	= $locationCoordinates->longitude;
	// 			$location->user_id 		= $userId;
	// 			$location->type 		= SOCIAL_TYPE_PHOTO;
	// 			$location->uid 			= $photo->id;

	// 			$state 	= $location->store();
	// 		}

	// 		// Store custom meta data for the photo
	// 		$photosModel->storeCustomMeta( $photo, $exif );
	// 	}


	// 	// If album doesn't have a cover, set the current photo as the cover.
	// 	if( !$album->hasCover() )
	// 	{
	// 		$album->cover_id 	= $photo->id;

	// 		// Store the album
	// 		$album->store();
	// 	}

	// 	// Get destination folder path.
	// 	$storage 	= Foundry::call( 'Photos', 'getStoragePath', array( $album->id, $photo->id ) );

	// 	// Move the temporary folder back to the photos album folder.

	// 	// canot use move because we created the folders beforehand.
	// 	// $state 		= JFolder::move( $tmpPath, $storage );

	// 	$state 		= JFolder::copy( $tmpPath, $storage, '', true );
	// 	if( $state )
	// 	{
	// 		// @TODO: now we delete the files in tmp folder.
	// 		JFolder::delete( $tmpPath );
	// 	}


	// 	// Initialize all sizes.
	// 	$sizes 		= array( 'square', 'thumbnail', 'featured', 'large', 'original', 'stock' );

	// 	foreach( $sizes as $size )
	// 	{
	// 		$meta 				= Foundry::table( 'PhotoMeta' );
	// 		$meta->photo_id		= $photo->id;
	// 		$meta->group 		= SOCIAL_PHOTOS_META_PATH;
	// 		$meta->property 	= $size;
	// 		$meta->value		= $storage . '/' . $avatarData->$size;

	// 		$meta->store();
	// 	}

	// 	// @stream: Add stream item when a new profile avatar is uploaded
	// 	$photo->addPhotosStream( 'uploadAvatar' );

	// 	return $photo;
	// }

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		$id = JRequest::getInt( 'id' );

		$model 		= Foundry::model( 'Avatars' );
		$avatars 	= $model->getDefaultAvatars( $id );

		$this->set( 'avatars', $avatars );

		return $this->display();
	}

	public function onOAuthGetUserMeta( &$details, &$client )
	{
		// Future's sake, check client name through $client

		$config = Foundry::config();

		if( $config->get( 'oauth.facebook.registration.avatar' ) )
		{
			$avatar = $client->api( 'me/picture', array( 'type' => 'large', 'redirect' => false ) );
			$avatarUrl = $avatar['data']['url'];

			$details['avatar'] = $avatarUrl;
		}
	}
}
