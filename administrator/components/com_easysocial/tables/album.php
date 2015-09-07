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
Foundry::import( 'admin:/includes/stream/stream' );
Foundry::import( 'admin:/includes/stream/dependencies' );
Foundry::import( 'admin:/includes/indexer/indexer' );

class SocialTableAlbum extends SocialTable
	implements ISocialIndexerTable, ISocialStreamItemTable
{
	/**
	 * The unique id for this record.
	 * @var int
	 */
	public $id			= null;

	/**
	 * The photo id that is used for this album
	 * @var int
	 */
	public $cover_id 		= null;

	/**
	 * The user id for this record.
	 * @var int
	 */
	public $uid 		= null;

	/**
	 * The unique type string for this record.
	 * @var string
	 */
	public $type 		= null;

	/**
	 * The unique type string for this record.
	 * @var string
	 */
	public $title 		= null;

	/**
	 * The unique type string for this record.
	 * @var string
	 */
	public $caption 		= null;

	/**
	 * The created date of this album.
	 * @var string
	 */
	public $created 		= null;

	/**
	 * The creation date alias of this album.
	 * @var string
	 */
	public $assigned_date 		= null;

	/**
	 * The ordering of this album.
	 * @var string
	 */
	public $ordering 		= null;

	/**
	 * Extended parameters of this album in json format.
	 * @var string
	 */
	public $params 		= null;

	/**
	 * Determines if this album is used for the system (Which means it cannot be deleted.)
	 * @var string
	 */
	public $core 		= null;

	public $_uuid = null;

	static $_albums = array();

	private $cover 	= null;

	/**
	 * Class Constructor
	 *
	 * @since	1.0
	 * @param	JDatabase
	 */
	public function __construct( $db )
	{
		// Create a unique id only for each table instance
		// This is to help controller implement the right element.
		$this->_uuid = uniqid();

		parent::__construct('#__social_albums', 'id', $db);
	}

	/**
	 * Overrides parent's load implementation
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function load( $keys = null, $reset = true )
	{
		$state = false;
		$loaded = false;

		if( is_array( $keys ) )
		{
			$state = parent::load( $keys, $reset );
		}
		else
		{
			if(! isset( self::$_albums[ $keys ] ) )
			{
				$state = parent::load( $keys );
				self::$_albums[ $keys ] = $this;
			}
			else
			{
				$state = parent::bind( self::$_albums[ $keys ] );
				$loaded = true;
			}
		}

		if( $state && !$loaded)
		{
			// Converts params into an object first
			if( empty( $this->params ) )
			{
				$this->params = new stdClass();
			}
			else
			{
				$this->params = Foundry::json()->decode( $this->params );
			}
		}

		return $state;
	}


	/**
	 *  load albums by batch
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function loadByBatch( $ids )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$albumIds = array();

		foreach( $ids as $pid )
		{
			if(! isset( self::$_albums[$pid] ) )
			{
				$albumIds[] = $pid;
			}
		}

		if( $albumIds )
		{
			foreach( $albumIds as $pid )
			{
				self::$_albums[$pid] = false;
			}

			$query = '';
			$idSegments = array_chunk( $albumIds, 5 );
			//$idSegments = array_chunk( $albumIds, count( $albumIds ) );


			for( $i = 0; $i < count( $idSegments ); $i++ )
			{
				$segment    = $idSegments[$i];
				$ids = implode( ',', $segment );

				$query .= 'select * from `#__social_albums` where `id` IN ( ' . $ids . ')';

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
					$tbl = Foundry::table( 'Album' );
					$tbl->bind( $row );

					if( empty( $tbl->params ) )
					{
						$tbl->params = new stdClass();
					}
					else
					{
						$tbl->params = Foundry::json()->decode( $tbl->params );
					}


					self::$_albums[$row->id] = $tbl;
				}
			}
		}

	}


	/**
	 * Overrides parent's store implementation
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store( $updateNulls = false )
	{
		// Detect if this is a new album
		$isNew 	= $this->id ? false : true;

		// Set a default title if the title is not set.
		if( empty( $this->title ) )
		{
			$this->title 	= JText::_( 'COM_EASYSOCIAL_UNTITLED_ALBUM' );
		}

		// Convert params back into json string
		if( !is_string( $this->params ) )
		{
			$this->params = Foundry::json()->encode( $this->params );
		}

		// Set the date to now if created is empty
		if( empty( $this->created ) )
		{
			$this->created = Foundry::date()->toSql();
		}

		// Update ordering column.
		$this->ordering = $this->getNextOrder( array( 'uid' => $this->uid , 'type' => $this->type ) );

		// Invoke paren't store method.
		$state 	= parent::store( $updateNulls );

		if( $isNew && !$this->core )
		{
			// @points: photos.albums.create
			// Add points for the author for creating an album
			$points = Foundry::points();
			$points->assign( 'photos.albums.create' , 'com_easysocial' , $this->uid );
		}

		return $state;
	}


	public function syncIndex()
	{
		$indexer = Foundry::get( 'Indexer' );

		$tmpl 	= $indexer->getTemplate();

		$creator 	= Foundry::user( $this->uid );
		$userAlias 	= $creator->getAlias();

		$url 	= FRoute::albums( array( 'id' => $this->getAlias() , 'userid' => $userAlias , 'layout' => 'item' ) );
		$url 	= '/' . ltrim( $url , '/' );
		$url 	= str_replace('/administrator/', '/', $url );

		$tmpl->setSource( $this->id , SOCIAL_INDEXER_TYPE_ALBUMS , $this->uid , $url );

		$content = ( $this->caption ) ? $this->caption : $this->title;
		$tmpl->setContent( $this->title, $content );

		if( $this->cover_id )
		{
			$photo = Foundry::table( 'Photo' );
			$photo->load( $this->cover_id );

			$thumbnail 	= $photo->getSource( 'thumbnail' );
			if( $thumbnail )
			{
				$tmpl->setThumbnail( $thumbnail );
			}
		}

		$date = Foundry::date();
		$tmpl->setLastUpdate( $date->toMySQL() );

		$state = $indexer->index( $tmpl );
		return $state;
	}

	public function deleteIndex()
	{
		$indexer = Foundry::get( 'Indexer' );
		$indexer->delete( $this->id, SOCIAL_INDEXER_TYPE_ALBUMS);
	}


	public function uuid()
	{
		return $this->_uuid;
	}

	/**
	 * Retrieves the likes count for this album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLikesCount()
	{
		static $likes 	= array();

		if (!$this->id) return 0;

		if( !isset( $likes[ $this->id ] ) )
		{
			$likes[ $this->id ]	= Foundry::get( 'Likes' )->getCount( $this->id , SOCIAL_TYPE_ALBUM );
		}

		return $likes[ $this->id ];
	}

	/**
	 * Retrieves the comments count for this album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCommentsCount()
	{
		static $comments 	= array();

		if (!$this->id) return 0;

		if( !isset( $comments[ $this->id ] ) )
		{
			$comments[ $this->id ]	= Foundry::comments( $this->id, SOCIAL_TYPE_ALBUM, SOCIAL_APPS_GROUP_USER )->getCount();
		}

		return $comments[ $this->id ];
	}

	/**
	 * Get the total number of tags for this album
	 *
	 * @since	1.0
	 * @access	public
	 * @return	int
	 */
	public function getTagsCount()
	{
		$model 	= Foundry::model( 'Albums' );

		$tags 	= $model->getTotalTags( $this->id );

		return $tags;
	}

	/**
	 * Retrieves a list of tags from all albums
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTags( $usersOnly = false )
	{
		$model 	= Foundry::model( 'Albums' );

		$tags 	= $model->getTags( $this->id , $usersOnly );

		return $tags;
	}

	/**
	 * Retrieves the storage path for this album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getStoragePath( $relative = false )
	{
		// Rename temporary folder to the destination.
		jimport( 'joomla.filesystem.folder' );

		// Get destination folder path.
		$config 	= Foundry::config();
		$path 		= '';

		if( !$relative )
		{
			$path 	= JPATH_ROOT;
		}

		$path 		= $path . '/' . Foundry::cleanPath( $config->get( 'photos.storage.container' ) );

		// Ensure that the storage folder exists.
		if( !$relative )
		{
			Foundry::makeFolder( $path );
		}

		// Build the storage path now with the album id
		$path 	= $path . '/' . $this->id;

		// Ensure that the final storage path exists.
		if( !$relative )
		{
			Foundry::makeFolder( $path );
		}

		return $path;
	}

	/**
	 * Gets the total number of photos for an album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalPhotos()
	{
		static $total = array();

		if( !isset( $total[ $this->id ] ) )
		{
			$model 				= Foundry::model( 'Albums' );
			$total[ $this->id ]	= $model->getTotalPhotos( $this->id );
		}

		return $total[ $this->id ];
	}

	/**
	 * Determines if the album is owned by the provided user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isMine( $id = null )
	{
		$user 	= Foundry::user( $id );

		$isOwner	= $user->id == $this->uid;

		return $isOwner;
	}


	/**
	 * Determines if an album has a cover.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function hasCover()
	{
		if( $this->cover_id )
		{
			return true;
		}

		return false;
	}

	/**
	 * Build's the album's alias
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlias()
	{
		$alias 	= $this->id . ':' . JFilterOutput::stringURLSafe( $this->title );

		return $alias;
	}

	/**
	 * Retrieves the cover photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCover()
	{
		$covers 	= array();

		if( !isset( $covers[ $this->id ] ) )
		{
			if( !$this->hasCover() )
			{
				return false;
			}

			$photo 	= Foundry::table( 'Photo' );
			$photo->load( $this->cover_id );

			$covers[ $this->id ]	= $photo;
		}

		return $covers[ $this->id ];
	}

	/**
	 * Retrieves the cover photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCoverUrl( $source = 'thumbnail' )
	{
		if( !is_null( $this->cover ) )
		{
			return $this->cover;
		}

		if( !$this->cover && $this->hasCover() )
		{
			$photo 			= Foundry::table( 'Photo' );
			$photo->load( $this->cover_id );

			$this->cover	= $photo->getSource( $source );
		}
		else
		{
			// @TODO: Make this configurable
			$this->cover 	= SOCIAL_DEFAULTS_URI . '/albums/cover.png';
		}

		return $this->cover;
	}

	/**
	 * Override parent's delete method
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete( $pk = null )
	{
		// Delete the record from the database first.
		$state 	= parent::delete();

		// Delete the photos from the site first.
		$photosModel = Foundry::model( 'photos' );
		$photosModel->deleteAlbumPhotos( $this->id );

		// @points: photos.albums.remove
		// Deduct points for the author for deleting an album
		$points = Foundry::points();
		$points->assign( 'photos.albums.remove' , 'com_easysocial' , $this->uid );

		// Now, try to delete the folder that houses this photo.
		$config 	= Foundry::config();
		$storage 	= JPATH_ROOT . '/' . Foundry::cleanPath( $config->get( 'photos.storage.container' ) );
		$storage 	= $storage . '/' . $this->id;

		jimport( 'joomla.filesystem.folder' );

		$exists 	= JFolder::exists( $storage );

		// Test if the folder really exists first before deleting it.
		if( $exists )
		{
			$state 	= JFolder::delete( $storage );

			if( !$state )
			{
				Foundry::logError( __FILE__ , __LINE__ , 'ALBUMS: Unable to delete the photos folder ' . $storage );
			}
		}

		// Delete likes related to the album
		$likes 	= Foundry::get( 'Likes' );

		if( !$likes->delete( $this->id , SOCIAL_TYPE_ALBUM ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'ALBUMS: Unable to delete the likes for the album ' . $this->id );
		}

		// Delete comments related to the album
		$comments = Foundry::comments( $this->id, SOCIAL_TYPE_ALBUM, SOCIAL_APPS_GROUP_USER );

		if( !$comments->delete() )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'ALBUMS: Unable to delete the comments for the album ' . $this->id );
		}


		return $state;
	}

	public function addAlbumStream( $verb )
	{
		// for album, we only want to create stream when is a new album creation and not during update.

		if( $verb == 'create' )
		{
			$stream 	= Foundry::stream();

			$template 	= $stream->getTemplate();
			$template->setActor( $this->uid , $this->type );
			$template->setContext( $this->id , SOCIAL_STREAM_CONTEXT_ALBUMS );
			$template->setVerb( $verb );

			$template->setPublicStream( 'albums.view' );

			$template->setDate( $this->created );

			$stream->add( $template );
		}
	}

	/**
	 * Generates a new stream method.
	 *
	 */
	public function addStream( $verb )
	{
		// do nothing. Please do not remove this function!
	}

	/**
	 * Deletes a stream item
	 *
	 */
	public function removeStream()
	{
		$stream 	= Foundry::stream();

		return $stream->delete( $this->id , SOCIAL_STREAM_CONTEXT_ALBUMS );
	}

	/**
	 * Determines if this is a core album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isCore()
	{
		// If this is a system album like cover photos, profile pictures, they will not be able to delete them.
		$disallowed 	= array( SOCIAL_ALBUM_STORY_ALBUM , SOCIAL_ALBUM_PROFILE_COVERS , SOCIAL_ALBUM_PROFILE_PHOTOS );

		if( in_array( $this->core , $disallowed ) )
		{
			return true;
		}

		false;
	}

	/**
	 * Tests if the album is editable by the provided user id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	User id.
	 * @return
	 */
	public function editable( $id = null , $type = SOCIAL_TYPE_USER )
	{
		// Previously there is a isCore check here.
		// Restrictions limited to core albums should be
		// checked with $album->isCore(), not $album->editable().

		if( $type == SOCIAL_TYPE_USER )
		{
			if (empty($id))
			{
				$user = Foundry::user();
				$id = $user->id;
			}
			else
			{
				$user = Foundry::user( $id );
			}

			// @TODO: Allow users with moderation / super admins to delete
			if( $this->uid == $user->id || $user->isSiteAdmin() )
			{
				return true;
			}

			return false;
		}

		return false;
	}

	public function viewable( $id = null )
	{
		// TODO: Check if user can view this album.

		// If id not given, use current logged in user.
		if (!$id) {
			$my = Foundry::user();
			$id = $my->id;
		}

		// Get the privacy object
		$privacy = Foundry::privacy( $id );
		return $privacy->validate( 'albums.view', $this->id, 'albums', $this->uid );


		return true;
	}

	/**
	 * Determines if the album needs to display the date
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function hasDate()
	{
		if( $this->core )
		{
			return false;
		}

		return true;
	}

	/**
	 * Tests if the album is delete able by the provided user id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	User id.
	 * @return
	 */
	public function deleteable( $id = null , $type = SOCIAL_TYPE_USER )
	{
		if( $this->isCore() )
		{
			return false;
		}

		if( $type == SOCIAL_TYPE_USER )
		{
			$user 	= Foundry::user( $id );

			// @TODO: Allow users with moderation / super admins to delete
			if( $this->uid == $user->id )
			{
				return true;
			}

			return false;
		}

		return false;
	}

	/**
	 * Assign points to a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 		The actor's id
	 * @return
	 */
	public function assignPoints( $rule , $actorId )
	{
		$points = Foundry::points();
		$points->assign( $rule , 'com_easysocial' , $actorId );
	}

	public function getPhotos( $options = array() )
	{
		if (!$this->id) return array( 'photos' => array(), 'nextStart' => -1 );

		$lib = Foundry::getInstance( 'albums' );

		return $lib->getPhotos( $this->id, $options );
	}

	public function hasPhotos()
	{
		return ( $this->getTotalPhotos() > 0 );
	}

	/**
	 * Retrieves the permalink for the album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Determines if it should be xhtml encoded
	 * @param	bool	Determines if the URL should be an external url.
	 * @return
	 */
	public function getPermalink( $xhtml = true , $external = false )
	{
		$user 		= Foundry::user( $this->uid );

		$options 	= array( 'id' => $this->getAlias() , 'layout' => 'item' , 'userid' => $user->getAlias() );

		if( $external )
		{
			$options[ 'external' ]	= true;
		}
		return FRoute::albums( $options , $xhtml );
	}

	public function getCreator()
	{
		return Foundry::user($this->uid);
	}

	/**
	 * Retrieves the location of the album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLocation()
	{
		static $locations 	= array();

		if( !isset( $locations[ $this->id ] ) )
		{
			$location 	= Foundry::table( 'Location' );
			$state 		= $location->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_ALBUM ) );

			if( !$state )
			{
				$locations[ $this->id ]	= $state;
			}
			else
			{
				$locations[ $this->id ]	= $location;
			}
		}

		return $locations[ $this->id ];
	}

	/**
	 * Retrieves the creation date of the album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCreationDate()
	{
		return $this->created;
	}

	/**
	 * Retrieves the assigned date of the album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAssignedDate()
	{
		return $this->assigned_date;
	}

	public function export( $flags = array() )
	{
		$properties = get_object_vars( $this );

		$album = array();

		foreach( $properties as $key => $value )
		{
			if( $key[0] != '_' )
			{
				$album[$key] = $value;
			}
		}

		$album['permalink'] = $this->getPermalink(false);

		if( in_array( 'cover', $flags ) )
		{
			if( $this->hasCover() )
			{
				$cover = Foundry::table( 'photo' );
				$cover->load( $this->cover_id );

				$album['cover'] = $cover->export();
			}
			else
			{
				$album['cover'] = array();
			}
		}

		if( in_array( 'photos', $flags ) )
		{
			$album['photos'] = array();

			$model 		= Foundry::model( 'Photos' );

			$result 	= $model->getPhotos( array( 'album_id' => $this->id , 'pagination' => false ) );
			$album[ 'photos' ]	= array();

			if( $result )
			{
				foreach( $result as $photo )
				{
					$album['photos'][] = $photo->export();
				}
			}

		}

		return $album;
	}
}
