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

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

class EasySocialModelPhotos extends EasySocialModel
{
	static $_photometas = array();
	static $_cache 		= null;


	function __construct()
	{

		if( is_null( self::$_cache ) )
		{
			self::$_cache = false;
		}


		parent::__construct( 'photos' );
	}

	/**
	 * Stores the exif data for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function storeCustomMeta( SocialTablePhoto $photo , SocialExif $exif )
	{
		$config 		= Foundry::config();
		$storableItems 	= $config->get( 'photos.exif' );

		foreach( $storableItems as $property )
		{
			$method 	= 'get' . ucfirst( $property );

			if( is_callable( array( $exif ,$method ) ) )
			{
				$meta 				= Foundry::table( 'PhotoMeta' );
				$meta->photo_id 	= $photo->id;

				$meta->group		= "exif";
				$meta->property 	= $property;

				$meta->value 		= $exif->$method();

				$meta->store();
			}
		}

		return true;
	}

	/**
	 * Retrieve a list of tags for a particular photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTags( $id , $peopleOnly = false )
	{
		$db		= Foundry::db();

		$sql	= $db->sql();

		$sql->select( '#__social_photos_tag' );
		$sql->where( 'photo_id' , $id );

		if( $peopleOnly )
		{
			$sql->where( 'uid' , '' , '!=' , 'AND' );
			$sql->where( 'type' , 'person' , '=' , 'AND' );
		}

		$db->setQuery( $sql );
		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$tags 	= array();

		foreach( $result as $row )
		{
			$tag 	= Foundry::table( 'PhotoTag' );
			$tag->bind( $row );

			$tags[]	= $tag;
		}

		return $tags;
	}


	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function tag()
	{
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalPhotos( $options = array() )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_photos' );
		$sql->column( 'COUNT(1)' , 'count' );

		// Determine if we should fetch based on the unique id.
		$uid 	= isset( $options[ 'uid' ] ) 	? $options[ 'uid' ] : '';
		$state 	= isset( $options[ 'state' ] ) 	? $options[ 'state' ] : SOCIAL_STATE_PUBLISHED;

		if( $uid )
		{
			$sql->where( 'uid' , $uid );
		}

		if( $state )
		{
			$sql->where( 'state' , $state );
		}

		$db->setQuery( $sql );

		$total 	= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves list of photos
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPhotos( $options = array() )
	{
		$db		= Foundry::db();

		// Get the query object
		$sql	= $db->sql();

		$sql->select( '#__social_photos' );

		$albumId = isset( $options[ 'album_id' ] ) ? $options[ 'album_id' ] : null;

		$start = isset( $options['start'] ) ? $options['start'] : 0;
		$limit = isset( $options['limit'] ) ? $options['limit'] : 10;

		if( !is_null( $albumId ) )
		{
			$sql->where( 'album_id' , $albumId );
		}

		$state 	= isset( $options[ 'state' ] ) ? $options[ 'state' ] : SOCIAL_STATE_PUBLISHED;

		$sql->where( 'state' , $state );

		// If user id is specified, we only fetch photos that are created by the user.
		$uid 	= isset( $options[ 'uid' ] ) ? $options[ 'uid' ] : false;

		if( $uid )
		{
			$sql->where( 'uid' 	, $uid );
			$sql->where( 'type' , SOCIAL_TYPE_USER );
		}

		$storage 	= isset( $options[ 'storage' ] ) ? $options[ 'storage' ] : '';

		if( $storage )
		{
			$sql->where( 'storage' , $storage );
		}

		// Determine if we should paginate items
		$pagination 	= isset( $options[ 'pagination' ] ) ? $options[ 'pagination' ] : true;

		if( $pagination )
		{
			$sql->limit( $start, $limit );
		}

		$sql->order( 'ordering' );

		$db->setQuery( $sql );
		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$photos 	= array();

		foreach( $result as $row )
		{
			$photo 	= Foundry::table( 'Photo' );
			$photo->bind( $row );

			$photos[]	= $photo;
		}

		return $photos;
	}

	/**
	 * Retrieves the meta data about a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getMeta( $photoId , $group = '' )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		if( ! self::$_cache )
		{
			$sql->select( '#__social_photos_meta' );
			$sql->where( 'photo_id' , $photoId );
			if( $group )
			{
				$sql->where( 'group' , $group );
			}

			$db->setQuery( $sql );
			$metas 	= $db->loadObjectList();

			return $metas;
		}


		if(! isset( self::$_photometas[ $photoId ] ) )
		{
			self::$_photometas[ $photoId ] = array();

			$sql->select( '#__social_photos_meta' );
			$sql->where( 'photo_id' , $photoId );

			$db->setQuery( $sql );
			$metas 	= $db->loadObjectList();

			// var_dump( $metas );
			// exit;

			if( $metas )
			{
				foreach( $metas as $row )
				{
					self::$_photometas[ $row->photo_id ][ $row->group ][] = $row;
				}
			}
		}

		$metas = array();

		if( $group )
		{
			if( isset( self::$_photometas[ $photoId ][ $group ] ) )
			{
				$metas = self::$_photometas[ $photoId ][ $group ];
			}
		}
		else
		{
			if( isset( self::$_photometas[ $photoId ] ) )
			{
				foreach( self::$_photometas[ $photoId ] as $group => $items )
				{
					if( $items )
					{
						foreach( $items as $item )
						{
							$metas[] = $item;
						}
					}
				}
			}
		}

		return $metas;
	}

	public function setCasheable( $cache = false )
	{
		self::$_cache  = $cache;
	}

	public function setMetasBatch( $ids )
	{

		$db = Foundry::db();
		$sql = $db->sql();

		$photoIds = array();

		foreach( $ids as $pid )
		{
			if(! isset( self::$_photometas[$pid] ) )
			{
				$photoIds[] = $pid;
			}
		}

		if( $photoIds )
		{
			foreach( $photoIds as $pid )
			{
				self::$_photometas[$pid] = array();
			}

			$query = '';
			$idSegments = array_chunk( $photoIds, 5 );
			//$idSegments = array_chunk( $photoIds, count( $photoIds ) );

			for( $i = 0; $i < count( $idSegments ); $i++ )
			{
				$segment    = $idSegments[$i];
				$ids = implode( ',', $segment );

				$query .= 'select * from `#__social_photos_meta` where `photo_id` IN ( ' . $ids . ')';

				if( ($i + 1)  < count( $idSegments ) )
				{
					$query .= ' UNION ';
				}
			}

			$sql->raw( $query );
			$db->setQuery( $sql );

			$results = $db->loadObjectList();

			if( $results )
			{
				foreach( $results as $row )
				{
					self::$_photometas[ $row->photo_id ][ $row->group ][] = $row;
				}
			}
		}
	}

	/**
	 * Allows caller to delete all the metadata about a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteMeta( $photoId , $group = null )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_photos_meta' );
		$sql->where( 'photo_id' , $photoId );

		if( !is_null( $group ) )
		{
			$sql->where( 'group' , $group );
		}

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Deletes all tags associated with a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteTags( $photoId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->delete( '#__social_photos_tag' );
		$sql->where( 'photo_id' , $photoId );

		$db->setQuery( $sql );

		$db->Query();
	}

	/**
	 * Deletes all photos within the album.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The album id
	 * @return	boolean	True if success, false otherwise.
	 */
	public function deleteAlbumPhotos( $albumId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_photos' );
		$sql->column( 'id' );
		$sql->where( 'album_id', $albumId );

		$db->setQuery( $sql );

		$photoIds 	= $db->loadColumn();

		if( !$photoIds )
		{
			return false;
		}

		foreach( $photoIds as $id )
		{
			$photo 	= Foundry::table( 'Photo' );
			$photo->load( $id );

			$photo->delete();
		}

		return true;
	}

	/**
	 * Determines if the photo is used as a profile cover
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The photo id
	 * @param	int		The user id
	 * @return
	 */
	public function isProfileCover( $photoId , $uid , $type )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_covers' );
		$sql->column( 'COUNT(1)' );
		$sql->where( 'photo_id' , $photoId );
		$sql->where( 'uid' , $uid );
		$sql->where( 'type' , $type );

		$db->setQuery( $sql );

		$exists	= $db->loadResult() > 0 ? true : false;

		return $exists;
	}

	public function pushPhotosOrdering( $albumId, $except = 0, $index = 0, $type = '+' )
	{
		$query = "UPDATE `#__social_photos` SET `ordering` = `ordering` " . $type . " 1 WHERE `album_id` = '" . $albumId . "' AND `ordering` >= '" . $index . "' AND `id` <> '" . $except . "'";

		$db = Foundry::db();
		$sql = $db->sql();

		$sql->raw( $query );

		$db->setQuery( $sql );

		return $db->query();
	}
}
