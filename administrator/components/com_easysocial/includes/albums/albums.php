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
 *
 * Photo albums library
 *
 * @since	1.0
 * @access	public
 *
 */
class SocialAlbums
{
	/**
	 * Static variable for caching.
	 * @var	SocialAlbums
	 */
	private static $instance = null;

	public $data = null;

	public function __construct( $id = null )
	{
		$data = Foundry::table( 'Album' );
		$data->load( $id );

		$this->data = $data;
	}

	public static function factory( $id = null )
	{
		return new self( $id );
	}

	public function groupAlbums( $rows )
	{
		if( !$rows )
		{
			return $rows;
		}

		$albums 	= array();

		foreach( $rows as $row )
		{
			$date 	= Foundry::date( $row->created );
			$format	= JText::_( 'COM_EASYSOCIAL_ALBUMS_GROUP_DATE_FORMAT' );
			$index 	= $date->format( $format );

			if( !isset( $albums[ $index ] ) )
			{
				$albums[ $index ]	= array();
			}

			$albums[ $index ][]	= $row;
		}

		return $albums;
	}

	/**
	 * Object initialisation for the class. Albums should be initialized using
	 * Foundry::getInstance( 'albums' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialAlbums	The SocialAlbums object.
	 */
	public static function getInstance()
	{
		if( !self::$instance )
		{
			self::$instance	= new self();
		}

		return self::$instance;
	}

	public function getPhotos( $albumId, $options = array() )
	{
		$config	= Foundry::config();

		$start = isset( $options['start'] ) ? $options['start'] : 0;

		$limit = isset( $options['limit'] ) ? $options['limit'] : $config->get( 'photos.pagination.photo' );

		$model = Foundry::model( 'photos' );

		$counter = 0;

		$nextStart = $start;

		$photos = array();

		$privacy 	= Foundry::privacy( Foundry::user()->id );

		while( $counter < $limit )
		{
			$newPhotos = $model->getPhotos( array( 'album_id' => $albumId, 'start' => $nextStart, 'limit' => $limit , 'state' => SOCIAL_STATE_PUBLISHED ) );

			$photosCount = count( $newPhotos );

			if( $photosCount === 0 )
			{
				// If photosCount is 0, means there are no more photos left to load
				$nextStart = -1;
				break;
			}

			foreach( $newPhotos as $photo )
			{
				// Check for privacy here
				$result = $privacy->validate( 'photos.view' , $photo->id, SOCIAL_TYPE_PHOTO , $photo->uid );

				if( $result )
				{
					// Add this photo into the photos list if privacy is true
					$photos[] = $photo;

					// Add the counter if privacy is true
					$counter++;
				}

				// Add the nextStart count regardless of the privacy
				$nextStart++;

				// If before the loop ends but we already reach the limit that we need, then break here and we will have the correct nextStart value
				if( $counter >= $limit )
				{
					break;
				}
			}
		}

		return array( 'photos' => $photos, 'nextStart' => $nextStart );
	}

	private $renderItemOptions = array(
		'viewer'       => null,
		'layout'       => 'item',
		'limit'        => 'auto',
		'canReorder'   => false,
		'canUpload'    => false,
		'showToolbar'  => true,
		'showInfo'     => true,
		'showStats'    => false,
		'showPhotos'   => true,
		'showResponse' => true,
		'showTags'     => true,
		'showForm'     => true,
		'showLoadMore' => true,
		'showViewButton' => false,
		'photoItem'    => array(
			'viewer'       => null,
			'layout'       => 'item',
			'showToolbar'  => true,
			'showInfo'     => true,
			'showStats'    => true,
			'showResponse' => false,
			'showTags'     => false,
			'showForm'     => true
		)
	);

	/**
	 * Wraps the provided album
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function renderItem($customOptions=array())
	{
		// Temporary reassignment
		$album = $this->data;

		// Built preset options
		$presetOptions = array(
			'canUpload' => $this->data->isMine()
		);

		// Normalize render options
		$options = array_merge($this->renderItemOptions, $presetOptions, $customOptions);

		if (!empty($customOptions['photoItem'])) {
			$options['photoItem'] = array_merge($this->renderItemOptions['photoItem'], $customOptions['photoItem']);
		}

		// Inherit photo item's viewer from album if it is not given
		if (empty($options['photoItem']['viewer'])) {
			$options['photoItem']['viewer'] = $options['viewer'];
		}

		// Photos cannot be uploaded to core albums
		if ($this->data->core) {
			$options['canUpload'] = false;
		}

		// Get album privacy
		// @TODO: Get proper album privacy
		$privacy	= Foundry::privacy();

		// Get album creator
		$creator	= Foundry::user( $album->uid );

		// Get album viewer
		$viewer     = Foundry::user( $options['viewer'] );

		$photoOptions = array();

		if ($options['limit']!=='auto') {
			$photoOptions['limit'] = $options['limit'];
		}

		// Get album phtoos
		$photos		= $album->getPhotos($photoOptions);

		// Get album likes
		$likes		= Foundry::likes( $album->id, SOCIAL_TYPE_ALBUM , SOCIAL_APPS_GROUP_USER );

		// Get album shares
		$shares     = Foundry::get( 'Repost', $album->id, SOCIAL_TYPE_ALBUM , SOCIAL_APPS_GROUP_USER );

		// Get album comments
		$comments	= Foundry::comments( $album->id , SOCIAL_TYPE_ALBUM , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::albums( array( 'id' => $album->id ) ) ) );

		// Get a list of tags from this album
		$tags 		= $album->getTags( true );

		// Build the user alias
		$userAlias 	= $creator->getAlias();

		// Generate item layout
		$theme = Foundry::themes();
		$theme->set( 'options'  , $options );
		$theme->set( 'userAlias', $userAlias );
		$theme->set( 'album'	, $album );
		$theme->set( 'tags'		, $tags );
		$theme->set( 'creator'	, $creator );
		$theme->set( 'privacy'	, $privacy );
		$theme->set( 'likes'	, $likes );
		$theme->set( 'shares'	, $shares );
		$theme->set( 'comments' , $comments );
		$theme->set( 'photos'	, $photos['photos'] );
		$theme->set( 'nextStart', $photos['nextStart'] );

		return $theme->output( 'site/albums/item' );
	}

}
