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
 * Field application for profile covers
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserCover extends SocialFieldItem
{

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
		// Initialize default data.
		$covers 		= array();

		$selected 		= false;

		$value = 'media/com_easysocial/defaults/covers/users/default.jpg';

		$this->set( 'value'			, $value );
		$this->set( 'selected'		, $selected );
		$this->set( 'covers' 		, $covers );

		// Get registration error
		$error 	= $registration->getErrors( $this->inputName );

		// Set error
		$this->set( 'error' , $error );

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
		// Get the cover details from the post
		$cover 	= $post[ $this->inputName ];

		// Try to catch if this field is required, and user did not upload any avatar or did not select a default avatar.
		if( $this->isRequired() && empty( $cover ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_COVER_VALIDATION_REQUIRED' ) );

			return false;
		}

		return true;
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
		$data	= !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		unset( $post[ $this->inputName ] );

		// If there's no data stored at all, skip this altogether since we don't know what to process.
		if( empty( $data ) )
		{
			return;
		}

		// Get the stored data.
		$data	= Foundry::makeObject( $data );

		if( empty( $data->data ) )
		{
			return;
		}

		$coverObj = Foundry::makeObject( $data->data );

		// Create the default album for this cover.
		$album 	= SocialFieldsUserCoverHelper::createDefaultAlbum( $user->id );

		// Once the album is created, create the photo object.
		$photo 	= SocialFieldsUserCoverHelper::createPhotoObject( $user->id, $album->id, $coverObj->stock->title, false );

		// Set the new album with the photo as the cover.
		$album->cover_id 	= $photo->id;
		$album->store();

		// Rename temporary folder to the destination.
		jimport( 'joomla.filesystem.folder' );

		// Get destination folder path.
		$config 	= Foundry::config();
		$storage 	= JPATH_ROOT . '/' . Foundry::cleanPath( $config->get( 'photos.storage.container' ) );

		// Test if the storage folder exists
		Foundry::makeFolder( $storage );

		// Set the storage path to the album
		$storage 	= $storage . '/' . $album->id;

		// If album folder doesn't exist, create it.
		Foundry::makeFolder( $storage );

		foreach( $coverObj as $key => $value )
		{
			SocialFieldsUserCoverHelper::createPhotoMeta( $photo, $key, $storage . '/' . $value->file );
		}

		// Build the temporary path.
		$tmp	= SocialFieldsUserCoverHelper::getPath( $this->inputName );

		$state				= JFolder::move( $tmp , $storage . '/' . $photo->id );

		if( !$state )
		{
			$this->setError( JText::_( 'PLG_FIELDS_COVER_ERROR_UNABLE_TO_MOVE_FILE' ) );
			return false;
		}

		// Set the cover now.
		$cover 	= Foundry::table( 'Cover' );
		$state 	= $cover->load( array( 'uid' => $user->id , 'type' => SOCIAL_TYPE_USER ) );

		// User does not have a cover.
		if( !$state )
		{
			$cover->uid 	= $user->id;
			$cover->type 	= SOCIAL_TYPE_USER;
		}

		if( !empty( $data->position ) )
		{
			$tmp = Foundry::makeObject( $data->position );

			if( !empty( $tmp->x ) )
			{
				$cover->x = $tmp->x;
			}

			if( !empty( $tmp->y ) )
			{
				$cover->y = $tmp->y;
			}
		}

		// Set the cover to pull from photo
		$cover->setPhotoAsCover( $photo->id );

		// Save the cover.
		$cover->store();
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
		$cover 	= isset( $data[ 'cover' ] ) ? $data['cover' ] : '';

		// If cover is not provided, skip this.
		if( !$cover )
		{
			return;
		}

		// Get the cover URL
		$coverUrl 	= $cover->url;

		// Get the session object.
		$uid		= SocialFieldsUserCoverHelper::genUniqueId( $this->inputName );

		// Get the user object.
		$user 		= Foundry::user();

		// Store the cover internally first.
		$tmpPath 	= SOCIAL_TMP . '/' . $uid . '_cover';
		$tmpFile 	= $tmpPath . '/' . $uid;

		// Now we need to get the image data.
		$connector 	= Foundry::connector();
		$connector->addUrl( $coverUrl );
		$connector->connect();

		$contents 	= $connector->getResult( $coverUrl );

		jimport( 'joomla.filesystem.file' );

		if( !JFile::write( $tmpFile , $contents ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'AVATAR: Unable to store oauth cover to tmp folder, ' . $tmpPath );
			return;
		}

		// Ensure that the image is valid.
		if( !SocialFieldsUserCoverHelper::isValid( $tmpFile ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'AVATAR: Invalid image provided for cover ' . $tmpFile );
			return;
		}

		// Create the default album for this cover.
		$album 	= SocialFieldsUserCoverHelper::createDefaultAlbum( $user->id );

		// Once the album is created, create the photo object.
		$photo 	= SocialFieldsUserCoverHelper::createPhotoObject( $user->id , $album->id , $data[ 'oauth_id' ] , true );

		// Set the new album with the photo as the cover.
		$album->cover_id 	= $photo->id;
		$album->store();

		// Get the storage path for the photo
		$storage 	= $photo->getStoragePath( $album );

		// Generates a unique name for this image.
		$name 	= md5( $data[ 'oauth_id' ] . $this->inputName . Foundry::date()->toMySQL() );

		// Load our own image library
		$image 	= Foundry::image();

		// Load up the file.
		$image->load( $tmpFile , $name );

		// Load up photos library
		$photos 	= Foundry::get( 'Photos' , $image );

		// Create avatars
		$sizes 		= $photos->create( $storage );

		foreach( $sizes as $size => $path )
		{
			// Now we will need to store the meta for the photo.
			$meta 	= SocialFieldsUserCoverHelper::createPhotoMeta( $photo , $size , $path );
		}

		// Once all is done, we just need to update the cover table so the user
		// will start using this cover now.
		$coverTable 	= Foundry::table( 'Cover' );
		$state 			= $coverTable->load( array( 'uid' => $user->id , 'type' => SOCIAL_TYPE_USER ) );

		// User does not have a cover.
		if( !$state )
		{
			$coverTable->uid 	= $user->id;
			$coverTable->type 	= SOCIAL_TYPE_USER;
			$coverTable->y 		= $cover->offset_y;
		}

		// Set the cover to pull from photo
		$coverTable->setPhotoAsCover( $photo->id );

		// Save the cover.
		$coverTable->store();
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
		$cover 		= '';
		$value 		= '';
		$position	= '';

		// Only get the cover if the user is not a new user.
		if( $user->id )
		{
			$cover		= $user->getCover();

			$value 		= $cover->getSource( SOCIAL_COVER_SMALL );

			$position	= $cover->getPosition();
		}


		$error = $this->getError( $errors );

		// Set the value
		$this->set( 'value', $value );
		$this->set( 'position', $position );
		$this->set( 'error', $error );

		return $this->display();
	}

	public function onAdminEditValidate()
	{
		// Admin shouldn't need to validate
		return true;
	}

	public function onEditValidate( &$post )
	{
		// Get the cover details from the post
		$cover 	= $post[ $this->inputName ];

		// Try to catch if this field is required, and user did not upload any avatar or did not select a default avatar.
		if( $this->isRequired() && empty( $cover ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_COVER_VALIDATION_REQUIRED' ) );

			return false;
		}

		return true;
	}

	public function onEditAfterSave( &$post, &$user )
	{
		$data = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		unset( $post[ $this->inputName ] );

		if( empty( $data ) )
		{
			return;
		}

		$data = Foundry::makeObject( $data );

		$cover = Foundry::table( 'Cover' );
		$state 	= $cover->load( array( 'uid' => $user->id , 'type' => SOCIAL_TYPE_USER ) );

		// User does not have a cover.
		if( !$state )
		{
			$cover->uid 	= $user->id;
			$cover->type 	= SOCIAL_TYPE_USER;
		}

		if( !empty( $data->data ) )
		{
			$coverObj = Foundry::makeObject( $data->data );

			$albumModel	= Foundry::model( 'Albums' );

			// Retrieve the user's default album
			$album 	= $albumModel->getDefaultAlbum( $my->id , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_COVERS );

			$photo = Foundry::table( 'Photo' );
			$photo->uid = $user->id;
			$photo->type = SOCIAL_TYPE_USER;
			$photo->album_id = $album->id;
			$photo->title = $data->file;

			$photo->store();

			// Get destination folder path.
			$config 	= Foundry::config();
			$storage 	= JPATH_ROOT . '/' . Foundry::cleanPath( $config->get( 'photos.storage.container' ) );

			Foundry::makeFolder( $storage );

			$storage .= '/' . $album->id;

			Foundry::makeFolder( $storage );

			foreach( $coverObj as $key => $value )
			{
				SocialFieldsUserCoverHelper::createPhotoMeta( $photo, $key, $storage . '/' . $value->file );
			}

			// Build the temporary path.
			$tmp	= SocialFieldsUserCoverHelper::getPath( $this->inputName );

			$state = JFolder::move( $tmp , $storage . '/' . $photo->id );

			if( !$state )
			{
				$this->setError( JText::_( 'PLG_FIELDS_COVER_ERROR_UNABLE_TO_MOVE_FILE' ) );
				return false;
			}

			// Set the cover to pull from photo
			$cover->setPhotoAsCover( $photo->id );
		}

		if( !empty( $data->position ) )
		{
			$tmp = Foundry::makeObject( $data->position );

			if( isset( $tmp->x ) )
			{
				$cover->x = $tmp->x;
			}

			if( isset( $tmp->y ) )
			{
				$cover->y = $tmp->y;
			}
		}

		// Save the cover.
		$cover->store();
	}

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
		return $this->display();
	}

	public function onOAuthGetMetaFields( &$fields )
	{
		$fields[] = 'cover';
	}

	public function onOAuthGetUserMeta( &$details, &$client )
	{
		$config = Foundry::config();

		if( $config->get( 'oauth.facebook.registration.cover' ) && isset( $details['cover'] ) )
		{
			$cover 				= new stdClass();

			$cover->url 		= $details[ 'cover' ][ 'source' ];
			$cover->offset_y	= $details[ 'cover' ][ 'offset_y' ];

			$details[ 'cover' ]	= $cover;
		}
	}
}
