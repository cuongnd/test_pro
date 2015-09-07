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
 * Processes ajax calls for the Joomla_Email field.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserAvatar extends SocialFieldItem
{
	/**
	 * Performs the file uploading here when the user selects their profile picture.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function upload()
	{
		// Get the ajax library
		$ajax 		= Foundry::ajax();

		// Get the file
		$tmp 		= JRequest::getVar( $this->inputName, '', 'FILES' );

		$file = array();
		foreach( $tmp as $k => $v )
		{
			$file[$k] = $v['file'];
		}

		// Check if it is a valid file
		if( empty( $file[ 'tmp_name' ] ) )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_AVATAR_ERROR_INVALID_FILE' ) );
		}

		// Get user access
		$access = Foundry::access( $this->profileId , SOCIAL_TYPE_PROFILES );

		// Check if the filesize is too large
		$maxFilesize = $access->get( 'photos.uploader.maxsize' );
		$maxFilesizeBytes = (int) $access->get( 'photos.uploader.maxsize' ) * 1048576;

		if( $file['size'] > $maxFilesizeBytes )
		{
			return $ajax->reject( JText::sprintf( 'COM_EASYSOCIAL_PHOTOS_UPLOAD_ERROR_FILE_SIZE_LIMIT_EXCEEDED', $maxFilesize . 'mb' ) );
		}

		// Copy this to temporary location first
		$tmpPath = SocialFieldsUserAvatarHelper::getStoragePath( $this->inputName );

		$tmpName = md5( $file[ 'name' ] . $this->inputName . Foundry::date()->toMySQL() );

		$state = JFile::copy( $file['tmp_name'], $tmpPath . '/' . $tmpName );

		if( !$state )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_AVATAR_ERROR_UNABLE_TO_MOVE_FILE' ) );
		}

		$tmpUri = SocialFieldsUserAvatarHelper::getStorageURI( $this->inputName );

		return $ajax->resolve( $file, $tmpUri . '/' . $tmpName, $tmpPath . '/' . $tmpName );
	}

	public function loadDefault()
	{
		$ajax = Foundry::ajax();

		$id = JRequest::getInt( 'id', 0 );

		if( empty( $id ) )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_AVATAR_ERROR_RETRIEVING_AVATAR' ) );
		}

		$default = Foundry::table( 'defaultavatar' );
		$default->load( $id );

		// $path = $default->getSource( SOCIAL_AVATAR_SQUARE );

		$uri = $default->getSource( SOCIAL_AVATAR_SQUARE, true );

		return $ajax->resolve( $uri );
	}

}
