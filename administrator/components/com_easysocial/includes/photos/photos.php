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

// Import the required file and folder classes.
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

/**
 * Photos library.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialPhotos
{
	private $path	= null;
	private $uid	= null;
	private $type	= null;

	/**
	 * Stores the size map of avatars.
	 * @var	Array
	 */
	static $sizes = array(

		'square' => array(
			'width'  => SOCIAL_PHOTOS_SQUARE_WIDTH,
			'height' => SOCIAL_PHOTOS_SQUARE_HEIGHT,
			'mode'   => 'fill'
		),

		'thumbnail' => array(
			'width'  => SOCIAL_PHOTOS_THUMB_WIDTH,
			'height' => SOCIAL_PHOTOS_THUMB_HEIGHT,
			'mode'   => 'outerFit'
		),

		'featured' => array(
			'width'  => SOCIAL_PHOTOS_FEATURED_WIDTH,
			'height' => SOCIAL_PHOTOS_FEATURED_HEIGHT,
			'mode'   => 'outerFit'		
		),

		'large' => array(
			'width'  => SOCIAL_PHOTOS_LARGE_WIDTH,
			'height' => SOCIAL_PHOTOS_LARGE_HEIGHT,
			'mode'   => 'fit'			
		)
	);

	/**
	 * Stores the image object.
	 * @var	SocialImage
	 */
	private $image = null;

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function __construct( SocialImage &$image )
	{
		// Set the current image object.
		$this->image = $image;
	}

	/**
	 * Factory maker for this class.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function factory( $image )
	{
		$photo 	= new self( $image );

		return $photo;
	}

	/**
	 * Returns the image resource
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * Gets the storage path for photos folder
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getStoragePath( $albumId , $photoId , $createFolders = true )
	{
		// Get destination folder path.
		$config 	= Foundry::config();
		$storage 	= JPATH_ROOT . '/' . Foundry::cleanPath( $config->get( 'photos.storage.container' ) );

		// Test if the storage folder exists
		if( $createFolders )
		{
			Foundry::makeFolder( $storage );
		}

		// Set the storage path to the album
		$storage 	= $storage . '/' . $albumId;

		// If it doesn't exist, create it.
		if( $createFolders )
		{
			Foundry::makeFolder( $storage );
		}

		// Create a new folder for the photo
		$storage 	= $storage . '/' . $photoId;

		if( $createFolders )
		{
			Foundry::makeFolder( $storage );
		}

		return $storage;
	}

	public function generateFilename( $size )
	{
		return $this->image->getName(true) . "_" . $size . $this->image->getExtension();
	}

	/**
	 * Creates the necessary images to be used as an avatar.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The target location to store the avatars
	 * @param	Array		An array of excluded sizes.
	 * @return
	 */
	public function create( $path , $exclusion = array() )
	{
		// Files array store a list of files
		// created for this photo.
		$files = array();

		// Create stock image
		$filename       = $this->generateFilename( 'stock' );
		$file           = $path . '/' . $filename;
		$files['stock'] = $filename;
		$this->image->copy( $path . '/' . $filename );

		// Create original image
		$filename          = $this->generateFilename( 'original' );
		$file              = $path . '/' . $filename;
		$files['original'] = $filename; 
		$this->image->rotate(0); // Fake an operation queue
		$this->image->save( $file );

		// Use original image as source image
		// for all other image sizes.
		$sourceImage = Foundry::image()->load( $file );

		// Create the rest of the image sizes
		foreach( self::$sizes as $name => $size )
		{
			if( in_array( $name , $exclusion ) ) continue;

			// Clone an instance of the source image.
			// Otherwise subsequent resizing operations
			// in this loop would end up using the image
			// instance that was resized by the previous loop.
			$image    = $sourceImage->cloneImage();

			$filename = $this->generateFilename( $name );
			$file     = $path . '/' . $filename;
			$files[$name] = $filename;

			// Resize image
			$method = $size['mode'];
			$image->$method( $size['width'], $size['height'] );

			// Save image
			$image->save( $file );

			// Free up memory
			unset( $image );
		}

		return $files;
	}
}
