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

Foundry::import( 'admin:/tables/table' );

/**
 * Object mapping for `#__social_languages` table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableLanguage extends SocialTable
{
	/**
	 * The unique id of the application
	 * @var int
	 */
	public $id			= null;

	/**
	 * The type of the application. E.g: fields, applications
	 * @var string
	 */
	public $title		= null;

	/**
	 * Determines if the application is a core application.
	 * @var int
	 */
	public $locale		= null;

	/**
	 * Determines if the application is only used for processing only.
	 * @var int
	 */
	public $updated		= null;

	/**
	 * Determines if the application is a unique application.
	 * @var int
	 */
	public $state		= null;

	/**
	 * The unique element of the application.
	 * @var string
	 */
	public $translator		= null;

	/**
	 * The group type of the application. E.g: people, groups , events etc.
	 * @var string
	 */
	public $progress 		= null;

	/**
	 * The title of the application
	 * @var string
	 */
	public $params		= null;

	/**
	 * Used for caching internally.
	 * @var Array
	 */
	public $layouts 		= null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_languages' , 'id' , $db );
	}

	/**
	 * Installs a language file
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function install()
	{
		$params 	= $this->getParams();

		// Get the api key
		$key 		= Foundry::config()->get( 'general.key' );

		// Get the download url
		$url 		= $params->get( 'download' );

		if( !$url )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_LANGUAGES_DOWNLOAD_URL_EMPTY' ) );
			return false;
		}

		// Get the md5 hash
		$md5		= $params->get( 'md5' );

		if( !$md5 )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_LANGUAGES_MD5_EMPTY' ) );
			return false;
		}

		// Download the language file
		$connector 	= Foundry::connector();
		$connector->addUrl( $url );
		$connector->setMethod( 'POST' );
		$connector->addQuery( 'key' , $key );
		$connector->connect();

		// Do an md5 hash to match the file
		$result 	= $connector->getResult( $url );

		$storage 	= SOCIAL_TMP . '/' . $md5 . '.zip';
		$state		= JFile::write( $storage , $result );

		// Check the md5 hash of the file
		$hash		= md5_file( $storage );
		$md5		= $this->getParams()->get( 'md5' );

		if( $this->getParams()->get( 'md5' ) != $hash )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_LANGUAGES_MD5_CHECKSUM_DOES_NOT_MATCH' ) );
			return false;
		}

		$extractedFolder 	= SOCIAL_TMP . '/' . $md5;

		jimport( 'joomla.filesystem.archive' );

		// Extract the language's archive file
		$state 		= JArchive::extract( $storage , $extractedFolder );

		// Throw some errors when we are unable to extract the zip file.
		if( !$state )
		{
			return false;
		}

		$metaPath 	= $extractedFolder . '/meta.json';

		$obj 		= Foundry::makeObject( $metaPath );

		// Get a list of files to update
		$files 		= $obj->files;

		foreach( $files as $file )
		{
			// Get the correct path based on the meta's path
			$languageFolder = $this->getPath( $file->path );

			$languageFolder	= $languageFolder . '/language';
			
			// Construct the absolute path
			$path		= $languageFolder . '/' . $file->locale;

			// If the folder does not exist, create it first
			if( !JFolder::exists( $path ) )
			{
				JFolder::create( $path );
			}

			$destFile 	= $path . '/' . $file->locale . '.' . $file->title;
			$sourceFile	= $extractedFolder . '/' . $file->path . '/' . $file->locale . '.' . $file->title;

			// Try to copy the file
			$state		= JFile::copy( $sourceFile , $destFile );

			if( !$state )
			{
				$this->setError( JText::_( 'COM_EASYSOCIAL_LANGUAGES_ERROR_COPYING_FILES' ) );
				return false;
			}
		}

		// Once the language files are copied accordingly, update the state
		$this->state 	= SOCIAL_LANGUAGES_INSTALLED;

		return $this->store();
	}

	public function getPath( $metaPath )
	{
		switch( $metaPath )
		{
			case 'site':
			case 'module':
				$path	= JPATH_ROOT;
			break;

			case 'admin':
			case 'fields':
			case 'plugins':
			case 'plugin':
			case 'menu':
			case 'apps':
				$path 	= JPATH_ROOT . '/administrator';
			break;
		}

		return $path;
	}
}
