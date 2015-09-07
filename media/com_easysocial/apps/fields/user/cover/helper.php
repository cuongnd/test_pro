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

// Import necessary library
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );


/**
 * Processes ajax calls for the Joomla_Email field.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserCoverHelper
{
	/**
	 * Checks if the cover photo is a valid image
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function isValid( $filePath )
	{
		// Load image library
		$image 	= Foundry::get( 'Image' );

		// Generate a temporary name for this image
		$name 	= md5( $filePath );

		// Load up the image
		$image->load( $filePath , $name );

		// Test if it is valid.
		$valid 	= $image->isValid();

		return $valid;
	}

	/**
	 * Creates a default album if user does not have it.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function createDefaultAlbum( $userId )
	{
		// Check if user has a default album
		$model 	= Foundry::model( 'Albums' );

		$exists	= $model->hasDefaultAlbum( $userId , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_COVERS );

		if( !$exists )
		{
			$album 	= $model->createDefaultAlbum( $userId , SOCIAL_TYPE_USER , SOCIAL_ALBUM_PROFILE_COVERS );
		}
		else
		{
			$album 	= Foundry::table( 'Album' );
			$album->load( array( 'uid' => $userId , 'type' => SOCIAL_TYPE_USER , 'core' => SOCIAL_ALBUM_PROFILE_COVERS ) );
		}

		return $album;
	}

	/**
	 * Creates the photo object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function createPhotoObject( $userId , $albumId , $title , $oauth = false )
	{
		$photo 				= Foundry::table( 'Photo' );

		$photo->uid 		= $userId;
		$photo->type 		= SOCIAL_TYPE_USER;
		$photo->album_id 	= $albumId;
		$photo->title 		= $title;
		$photo->state		= SOCIAL_STATE_PUBLISHED;

		$photo->caption 	= $oauth ? JText::_( 'Cover from Facebook' ) : '';

		// Store the photo
		$photo->store();

		return $photo;
	}

	/**
	 * Creates the photo meta data
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function createPhotoMeta( SocialTablePhoto $photo , $size , $path )
	{
		$meta 	= Foundry::table( 'PhotoMeta' );

		$meta->photo_id 	= $photo->id;
		$meta->group 		= SOCIAL_PHOTOS_META_PATH;
		$meta->property 	= $size;
		$meta->value 		= $path;
		$meta->store();

		return $meta;
	}

	/**
	 * Generates a unique id
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function genUniqueId( $inputName )
	{
		$session 		= JFactory::getSession();
		$uid 			= md5( $session->getId() . $inputName );

		return $uid;
	}

	public static function getPath( $inputName )
	{
		$date 		= Foundry::date();

		// Create a temporary folder for this session.
		$session 	= JFactory::getSession();
		$uid 		= md5( $session->getId() . $inputName );
		$path 		= SOCIAL_MEDIA . '/tmp/' . $uid . '_cover';

		return $path;
	}

	public static function getStoragePath( $inputName )
	{
		$path 	= SocialFieldsUserCoverHelper::getPath( $inputName );

		// If the folder exists, delete them first.
		if( JFolder::exists( $path ) )
		{
			JFolder::delete( $path );
		}

		// Create folder if necessary.
		Foundry::makeFolder( $path );

		return $path;
	}
}
