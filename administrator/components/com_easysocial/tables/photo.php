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
Foundry::import( 'admin:/includes/stream/dependencies' );
Foundry::import( 'admin:/includes/indexer/indexer' );

class SocialTablePhoto extends SocialTable
	implements ISocialIndexerTable, ISocialStreamItemTable
{
	/**
	 * The unique id for this record.
	 * @var int
	 */
	public $id			= null;

	/**
	 * The unique type id for this record.
	 * @var int
	 */
	public $uid 		= null;

	/**
	 * The unique type string for this record.
	 * @var string
	 */
	public $type 		= null;

	/**
	 * The album id for this photo
	 * @var int
	 */
	public $album_id 	= null;

	/**
	 * The title for this photo
	 * @var string
	 */
	public $title 		= null;

	/**
	 * The caption for this photo
	 * @var string
	 */
	public $caption 		= null;

	/**
	 * The creation date of this photos
	 * @var string
	 */
	public $created 		= null;

	/**
	 * The creation date alias of this photo.
	 * @var string
	 */
	public $assigned_date 		= null;

	/**
	 * The unique type string for this record.
	 * @var string
	 */
	public $ordering 		= null;

	/**
	 * The unique type string for this record.
	 * @var string
	 */
	public $featured 		= null;

	/**
	 * This determines the storage location for this photo
	 * @var string
	 */
	public $storage 		= 'joomla';


	/**
	 * The state of the photo. default is true
	 * @var string
	 */
	public $state 		= null;

	public $_uuid = null;

	static $_photos = array();

	static $_cache = null;

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

		//determide if load method should get from cache variable or not.
		if( is_null( self::$_cache ) )
		{
			self::$_cache = false;
		}

		parent::__construct('#__social_photos', 'id', $db);
	}

	/**
	 * Overrides parent's load implementation
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function load( $keys = null, $reset = true )
	{
		if( self::$_cache )
		{
			if( is_array( $keys ) )
			{
				return parent::load( $keys, $reset );
			}

			if(! isset( self::$_photos[ $keys ] ) )
			{
				$state = parent::load( $keys );
				self::$_photos[ $keys ] = $this;
				return $state;
			}

			if( is_bool( self::$_photos[ $keys ] ) )
			{
				return false;
			}

			return parent::bind( self::$_photos[ $keys ] );
		}
		else
		{
			return parent::load( $keys, $reset );
		}
	}

	public function setCasheable( $cache = false )
	{
		self::$_cache  = $cache;
	}

	public function loadByBatch( $ids )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$photoIds = array();
		$albumIds = array();

		foreach( $ids as $pid )
		{
			if(! isset( self::$_photos[$pid] ) )
			{
				$photoIds[] = $pid;
			}
		}

		if( $photoIds )
		{
			foreach( $photoIds as $pid )
			{
				self::$_photos[$pid] = false;
			}

			$query = '';
			$idSegments = array_chunk( $photoIds, 5 );
			//$idSegments = array_chunk( $photoIds, count( $photoIds ) );


			for( $i = 0; $i < count( $idSegments ); $i++ )
			{
				$segment    = $idSegments[$i];
				$ids 		= implode( ',', $segment );

				$query .= 'select * from `#__social_photos` where `id` IN ( ' . $ids . ')';
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
					$albumIds[] = $row->album_id;
					self::$_photos[$row->id] = $row;
				}
			}
		}

		return $albumIds;

	}

	/**
	 * Rotates a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The angle to rotate the photo
	 * @return
	 */
	public function rotate( $angle )
	{
		// Try to rotate the image
		$image 	= Foundry::image();
		$image->load( $this->getPath( 'stock' ) );
		$image->rotate( $angle );

		return $image;
	}

	/**
	 * Rotates tags in a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The angle to rotate the photo
	 * @return
	 */
	public function rotateTags( $angle )
	{
		$model 	= Foundry::model( 'Photos' );
		$tags 	= $model->getTags( $this->id );

		foreach( $tags as $tag )
		{
			$oldTag 	= clone( $tag );

			if( $angle == 90 )
			{
				$tag->width 	= $oldTag->height;
				$tag->height 	= $oldTag->width;

				$tag->left 		= ( 1 - $oldTag->top ) - $tag->width;
				$tag->top 		= $oldTag->left;
			}

			if( $angle == -90 )
			{
				$tag->width 	= $oldTag->height;
				$tag->height 	= $oldTag->width;
				$tag->top 		= $oldTag->left;
				$tag->left 		= $oldTag->top;
			}

			$tag->store();
		}
	}



	/**
	 * Toggle's a photo featured state
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function toggleFeatured()
	{
		$this->featured 	= $this->featured ? false : true;

		return $this->store();
	}

	/**
	 * Determines if the photo is featured
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function isFeatured()
	{
		return (bool) $this->featured;
	}

	/**
	 * Cleanup photo title
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function cleanupTitle()
	{
		$knownExtensions	= array( '.jpg' , '.jpeg' , '.gif' , '.png' );

		$this->title 	= str_ireplace( $knownExtensions , '' , $this->title );
	}

	/**
	 * Determines if the photo is owned by the provided user.
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
	 * Deletes the photos generated for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deletePhotos( $types )
	{
		$files 	= array();

		foreach( $types as $type )
		{
			$meta 	= Foundry::table( 'PhotoMeta' );
			$meta->load( array( 'photo_id' => $this->id , 'group' => SOCIAL_PHOTOS_META_PATH , 'property' => $type ) );

			if( $type != 'stock' )
			{
				$files[]	= str_ireplace( JPATH_ROOT , '' , $meta->value );
			}


			$meta->delete();
		}

		// Since remote storages doesn't store the "stock" photo, we need to manually delete it
		$storage 	= Foundry::storage( $this->storage );
		$storage->delete( $files );

		return true;
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

	/**
	 * Creates a badge record
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignBadge( $rule , $actorId )
	{
		if( $rule == 'photos.create' )
		{
			// @badge: photos.create
			$badge 	= Foundry::badges();
			$badge->log( 'com_easysocial' , 'photos.create' , $actorId , JText::_( 'COM_EASYSOCIAL_PHOTOS_BADGE_UPLOADED' ) );
		}

		if( $rule == 'photos.browse' )
		{
			// @badge: photos.browse
			$badge 	= Foundry::badges();
			$badge->log( 'com_easysocial' , 'photos.browse' , $actorId , JText::_( 'COM_EASYSOCIAL_PHOTOS_BADGE_BROWSE' ) );
		}

		if( $rule == 'photos.tag' )
		{
			// @badge: photos.tag
			$badge 	= Foundry::badges();
			$badge->log( 'com_easysocial' , 'photos.tag' , $actorId , JText::_( 'COM_EASYSOCIAL_PHOTOS_BADGE_TAG' ) );
		}

		if( $rule == 'photos.superstar' )
		{
			// @badge: photos.tag
			$badge 	= Foundry::badges();
			$badge->log( 'com_easysocial' , 'photos.tag' , $actorId , JText::_( 'COM_EASYSOCIAL_PHOTOS_BADGE_TAG' ) );
		}
	}

	/**
	 * Updates the angle of the photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function updateAngle( $angle )
	{
		$meta 	= Foundry::table( 'PhotoMeta' );
		$exists	= $meta->load( array( 'photo_id' => $this->id , 'group' => SOCIAL_PHOTOS_META_TRANSFORM , 'property' => 'rotation' ) );

		if( !$exists )
		{
			$meta->photo_id	= $this->id;
			$meta->group 	= SOCIAL_PHOTOS_META_TRANSFORM;
			$meta->property	= 'rotation';
		}

		// Angle should not be more than 360.
		$angle 			= $angle >= 360 ? 0 : $angle;

		$meta->value 	= $angle;

		$state 	= $meta->store();

		if( !$state )
		{
			$this->setError( $meta->getError() );
		}

		return $state;
	}

	/**
	 * Get's the current
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAngle()
	{
		$meta 	= Foundry::table( 'PhotoMeta' );
		$meta->load( array( 'photo_id' => $this->id , 'group' => SOCIAL_PHOTOS_META_TRANSFORM , 'property' => 'rotation' ) );

		return $meta->value;
	}

	/**
	 * Override store method
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function store( $updateNulls = false )
	{
		$isNew 	= $this->id ? false : true;

		$state 	= parent::store();

		if( $isNew )
		{
			// @points: photos.upload
			// Add points for the author
			$points = Foundry::points();
			$points->assign( 'photos.upload' , 'com_easysocial' , $this->uid );

			// @badge: photos.upload
			// Assign a badge for the user
			$this->assignBadge( 'photos.upload' , $this->uid );

			// Store the meta for the angle of the photo to be 0 by default.
			$meta	= Foundry::table( 'PhotoMeta' );

			$meta->photo_id 	= $this->id;
			$meta->group 		= SOCIAL_PHOTOS_META_EXIF;
			$meta->property 	= 'angle';
			$meta->value		= 0;

			$meta->store();
		}

		return $state;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function addPhotosStream( $verb, $mysqldatestring = '' )
	{

		$item_params = Foundry::json()->encode( $this );


		if( $verb == 'create' )
		{
			// Add stream item when a new photo is uploaded.
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor.
			$streamTemplate->setActor( $this->uid , SOCIAL_TYPE_USER );

			// Set the context.
			$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PHOTO, $item_params );

			// set the target id, in this case, the album id.
			$streamTemplate->setTarget( $this->album_id );

			// Set the verb.
			$streamTemplate->setVerb( 'add' );

			// set to aggreate photo
			$streamTemplate->setAggregate( true );

			//
			$streamTemplate->setType( 'full' );

			if( !empty( $mysqldatestring ) )
			{
				$streamTemplate->setDate( $mysqldatestring );
			}

			$streamTemplate->setPublicStream( 'photos.view' );


			// Create the stream data.
			$stream->add( $streamTemplate );
		}

		if( $verb == 'uploadAvatar' )
		{
			// Add stream item when a new photo is uploaded.
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor.
			$streamTemplate->setActor( $this->uid , SOCIAL_TYPE_USER );

			// Set the context.
			$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PHOTO, $item_params );

			// Set the verb.
			$streamTemplate->setVerb( 'uploadAvatar' );

			// we shouldnt aggreate photo upload that is for avatar.
			$streamTemplate->setAggregate( false );

			//
			$streamTemplate->setType( 'full' );

			if( !empty( $mysqldatestring ) )
			{
				$streamTemplate->setDate( $mysqldatestring );
			}

			$streamTemplate->setPublicStream( 'photos.view' );


			// Create the stream data.
			$stream->add( $streamTemplate );
		}

		if( $verb == 'updateCover' )
		{
			// Add stream item when a new photo is uploaded.
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor.
			$streamTemplate->setActor( $this->uid , SOCIAL_TYPE_USER );

			// Set the context.
			$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PHOTO, $item_params );

			// Set the verb.
			$streamTemplate->setVerb( 'updateCover' );

			// we shouldnt aggreate photo upload that is for avatar.
			$streamTemplate->setAggregate( false );

			//
			$streamTemplate->setType( 'full' );

			if( !empty( $mysqldatestring ) )
			{
				$streamTemplate->setDate( $mysqldatestring );
			}

			$streamTemplate->setPublicStream( 'photos.view' );


			// Create the stream data.
			$stream->add( $streamTemplate );
		}
	}

	public function addStream( $verb )
	{
		// do nothing. do not remove this function!
		// this method is needed to fullfil the interface implmentation.
	}



	public function removeStream()
	{
		$stream	= Foundry::stream();
		$stream->delete( $this->id, SOCIAL_TYPE_PHOTO );
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

		// Now, try to delete the folder that houses this photo.
		$config 	= Foundry::config();

		// Needs to create an instance of image to create
		// and instnance of photos
		$image      = Foundry::image();
		$photoLib   = Foundry::get( 'Photos' , $image );
		$basePath   = $photoLib->getStoragePath( $this->album_id , $this->id );

		$relative 	= str_ireplace( JPATH_ROOT , '' , $basePath );

		$storage 	= Foundry::storage( $this->storage );
		$storage->delete( $relative , true );

		$model 	= Foundry::model( 'Photos' );

		// Delete the meta's related to this photo
		$model->deleteMeta( $this->id );

		// Delete all tags associated with this photo
		$model->deleteTags( $this->id );

		// Delete all comments associated with this photo
		$comments 	= Foundry::comments( $this->id, SOCIAL_TYPE_PHOTO, SOCIAL_APPS_GROUP_USER );
		$comments->delete();

		// Delete all likes associated with this photo
		$likes 		= Foundry::get( 'Likes' );
		$likes->delete( $this->id , SOCIAL_TYPE_PHOTO );

		// @points: photos.remove
		// Deduct points for the author
		$points = Foundry::points();
		$points->assign( 'photos.remove' , 'com_easysocial' , $this->uid );

		// Push the ordering of other photos
		$model->pushPhotosOrdering( $this->album_id, 0, $this->ordering, '-' );

		// Need to set cover to another photo
		if( $this->isCover() )
		{
			$album = $this->getAlbum();

			if( $album->hasPhotos() )
			{
				$result = $album->getPhotos( array( 'limit' => 1 ) );

				$album->cover_id = $result['photos'][0]->id;
			}
			else
			{
				$album->cover_id = 0;
			}

			$album->store();
		}

		return $state;
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
	 * Tests if the user is allowed to download the photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function downloadable( $id = null )
	{
		$user 	= Foundry::user( $id );

		return true;
	}

	public function viewable( $id = null )
	{
		// If id not given, use current logged in user.
		if (!$id)
		{
			$my		= Foundry::user();
			$id		= $my->id;
		}

		// The privacy of photos are dependent on the album
		$privacy = Foundry::privacy( $id );
		return $privacy->validate( 'photos.view', $this->id , 'photos' , $this->uid );
	}

	/**
	 * Tests if the user is allowed to use this photo as their avatar.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function canSetProfilePicture( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $user->id == $this->uid )
		{
			return true;
		}

		return false;
	}

	/**
	 * Tests if the user is allowed to use this photo as cover
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function canSetProfileCover( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $user->id == $this->uid )
		{
			return true;
		}

		return false;
	}

	/**
	 * Tests if the user is allowed to feature this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function featureable( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $this->uid == $user->id || $user->isSiteAdmin() )
		{
			return true;
		}

		// @TODO: Test if this photo privacy allow friends to tag on the photo

		return false;
	}

	/**
	 * Tests if the user is allowed to share this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function shareable( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $this->uid == $user->id || $user->isSiteAdmin() )
		{
			return true;
		}

		// @TODO: Test if this photo privacy allow friends to tag on the photo

		return false;
	}

	/**
	 * Tests if the user is allowed to tag on this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function taggable( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $this->uid == $user->id || $user->isSiteAdmin() )
		{
			return true;
		}

		// @TODO: Test if this photo privacy allow friends to tag on the photo
		$privacyLib = Foundry::privacy( $this->uid );
		if( !$privacyLib->validate( 'photo.tag' , $user->id , SOCIAL_TYPE_USER ) )
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
		if( $type == SOCIAL_TYPE_USER )
		{
			$user 	= Foundry::user( $id );

			// @TODO: Allow users with moderation / super admins to delete
			if( $this->uid == $user->id || $user->isSiteAdmin() )
			{
				return true;
			}

			return false;
		}

		return false;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function move( $newAlbumId  , $where = '' )
	{
		// Get the old album id as we need to move the old photo folder over
		$oldAlbumId 		= $this->album_id;

		// Get the path to the photos folder.
		$oldFolder 			= $this->getFolder();

		// Set the new album id.
		$this->album_id 	= $newAlbumId;

		// Get the new photo folder
		$newFolder			= $this->getFolder();

		// Save the photo with the new album
		$state 	= parent::store();

		if( $state )
		{
			jimport( 'joomla.filesystem.folder' );

			JFolder::move( $oldFolder , $newFolder );

			// Once the folder is moved, we also need to update all the metas.
			$model 	= Foundry::model( 'Photos' );
			$metas 	= $model->getMeta( $this->id , SOCIAL_PHOTOS_META_PATH );

			foreach( $metas as $meta )
			{
				$table 	= Foundry::table( 'PhotoMeta' );
				$table->bind( $meta );

				$fileName 			= basename( $table->value );

				// Rebuild the new path
				$table->value 		= $newFolder . '/' . $fileName;

				$table->store();
			}
		}

		return $state;
	}

	/**
	 * Determines if the user is allowed to move the photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function moveable( $id = null , $type = SOCIAL_TYPE_USER )
	{
		$album = $this->getAlbum();

		// If this is a system album like cover photos, profile pictures, they will not be able to move photos within this album.
		$disallowed = array( SOCIAL_ALBUM_STORY_ALBUM , SOCIAL_ALBUM_PROFILE_COVERS , SOCIAL_ALBUM_PROFILE_PHOTOS );

		if( in_array( $album->core , $disallowed ) )
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

	public function getCreator()
	{
		return Foundry::user($this->uid);
	}

	/**
	 * Retrieves the creation date of the photo
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
	 * Retrieves the assigned date of the photo
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

	/**
	 * Get a list of tags for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTags( $peopleOnly = false )
	{
		$model 	= Foundry::model( 'Photos' );

		// Retrieve list of tags for this photo.
		$tags 	= $model->getTags( $this->id , $peopleOnly );

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
	public function getStoragePath( SocialTableAlbum $album , $relative = false )
	{
		// Rename temporary folder to the destination.
		jimport( 'joomla.filesystem.folder' );

		// Get destination folder path.
		$storage 	= $album->getStoragePath( $relative );

		// Build the storage path now with the album id
		$storage 	= $storage . '/' . $this->id;

		// Ensure that the final storage path exists.
		if( !$relative )
		{
			Foundry::makeFolder( $storage );
		}

		return $storage;
	}

	/**
	 * Retrieves the likes count for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLikesCount()
	{
		static $likes 	= array();

		if( !isset( $likes[ $this->id ] ) )
		{
			$likes[ $this->id ]	= Foundry::get( 'Likes' )->getCount( $this->id , SOCIAL_TYPE_PHOTO );
		}

		return $likes[ $this->id ];
	}

	/**
	 * Retrieves the comments count for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCommentsCount()
	{
		static $comments 	= array();

		if( !isset( $comments[ $this->id ] ) )
		{
			$comments[ $this->id ]	= Foundry::comments( $this->id, SOCIAL_TYPE_PHOTO, SOCIAL_APPS_GROUP_USER )->getCount();
		}

		return $comments[ $this->id ];
	}

	/**
	 * Get's the absolute path of the photo given the type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getFolder()
	{
		$config 	= Foundry::config();
		$storage 	= Foundry::cleanPath( $config->get( 'photos.storage.container' ) );
		$path 		= JPATH_ROOT . '/' . $storage . '/' . $this->album_id . '/' . $this->id;

		return $path;
	}

	/**
	 * Get's the absolute path of the photo given the type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPath( $type , $relative = false )
	{
		static $paths 	= array();

		if( !isset( $paths[ $this->id ] ) )
		{
			$model 		= Foundry::model( 'Photos' );
			$metas 		= $model->getMeta( $this->id , SOCIAL_PHOTOS_META_PATH );
			$obj 		= new stdClass();

			foreach( $metas as $meta )
			{
				$obj->{$meta->property}	= $meta->value;
			}

			$paths[ $this->id ]	= $obj;
		}

		return $paths[ $this->id ]->{$type};
	}

	/**
	 * Allows caller to download a file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function download()
	{
		// Get the original file path.
		$file 	= $this->getPath( 'original' );

		// Make the path relative
		if( $this->storage != 'joomla' )
		{
			$file 		= str_ireplace( JPATH_ROOT , '' , $file );

			$storage 	= Foundry::storage( $this->storage );

			return JFactory::getApplication()->redirect( $storage->getPermalink( $file ) );
		}

		// Get the mime of the image
		$mime 	= $this->getMime( 'original' );

		// @TODO: Set the proper header
		header('Content-Description: File Transfer');
		header('Content-Type: ' . $mime );
		header("Content-Disposition: attachment; filename=\"". $this->title ."\";" );
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize( $file ) );

		ob_clean();
		flush();
		readfile( $file );
		exit;
	}

	/**
	 * Retrieves the photo extension .jpg | .png
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getExtension()
	{
		// Use the stock photo to retrieve the extension
		$mime 	= $this->getMime( 'stock' );

		switch( $mime )
		{
			case 'image/png':
				$extension 	= '.png';
				break;

			case 'image/jpeg':
			default:
				$extension 	= '.jpg';
				break;
		}

		return $extension;
	}

	/**
	 * Returns the mime type for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getMime( $type )
	{
		$path 	= $this->getPath( $type );
		$info 	= getimagesize( $path );

		return $info[ 'mime' ];
	}

	/**
	 * Gets the image object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getImageObject( $type )
	{
		$path 	= $this->getPath( $type );

		if( !JFile::exists( $path ) )
		{
			return false;
		}

		$image 	= Foundry::image();
		$image->load( $path );

		return $image;
	}

	public function getUri( $type )
	{
			$model 		= Foundry::model( 'Photos' );
			$metas 		= $model->getMeta( $this->id , SOCIAL_PHOTOS_META_PATH );
	}

	/**
	 * Retrieves the permalink to the image given the size
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getSource( $type = 'thumbnail' )
	{
		static $paths = array();

		$config 	= Foundry::config();

		// Load the paths for this photo
		$model 		= Foundry::model( 'Photos' );
		$metas 		= $model->getMeta( $this->id , SOCIAL_PHOTOS_META_PATH );

		$obj 		= new stdClass();

		$path 		= Foundry::cleanPath( $config->get( 'photos.storage.container' ) );
		$allowed 	= array( 'thumbnail' , 'large' , 'original', 'square' , 'featured' , 'medium' );

		foreach( $metas as $meta )
		{
			$relative	= $path . '/' . $this->album_id . '/' . $this->id . '/' . basename( $meta->value );

			if( $this->storage != SOCIAL_STORAGE_JOOMLA && in_array( $meta->property , $allowed ) )
			{
				$storage 	= Foundry::storage( $this->storage );
				$url 		= $storage->getPermalink( $relative );
			}
			else
			{
				$url 	= rtrim( JURI::root() , '/' ) . '/' . $relative;
			}

			$obj->{$meta->property}	= $url;
		}

		$paths[ $this->id ]	= $obj;

		if( !isset( $paths[ $this->id ]->$type ) )
		{
			$paths[ $this->id ]->$type 	= false;
		}


		return $paths[ $this->id ]->$type;
	}


	public function syncIndex()
	{
		$indexer = Foundry::get( 'Indexer' );

		$item 	= $indexer->getTemplate();

		$url 	= FRoute::photos( array( 'layout' => 'item', 'id' => $this->getAlias() ) );
		$url 	= '/' . ltrim( $url , '/' );
		$url 	= str_replace('/administrator/', '/', $url );

		$item->setSource( $this->id , SOCIAL_INDEXER_TYPE_PHOTOS , $this->uid , $url );

		$content = ( $this->caption ) ? $this->caption : $this->title;
		$item->setContent( $this->title, $content );

		$date = Foundry::date();
		$item->setLastUpdate( $date->toMySQL() );

		$state = $indexer->index( $item );
		return $state;
	}

	public function deleteIndex()
	{
		$indexer = Foundry::get( 'Indexer' );
		$indexer->delete( $this->id, SOCIAL_INDEXER_TYPE_PHOTOS);
	}

	/**
	 * Retrieves the location for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLocation()
	{
		static $locations = array();

		if( !isset( $locations[ $this->id ] ) )
		{
			$location 	= Foundry::table( 'Location' );
			$state 		= $location->loadByType( $this->id , SOCIAL_TYPE_PHOTO , $this->uid );

			if( !$state )
			{
				$locations[ $this->id ] = $state;
			}
			else
			{
				$locations[ $this->id ]	= $location;
			}
		}

		return $locations[ $this->id ];
	}

	/**
	 * Retrieves the album for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlbum()
	{
		static $album = array();

		if( empty( $album[$this->album_id] ) )
		{
			$album[$this->album_id] = Foundry::table( 'album' );
			$album[$this->album_id]->load( $this->album_id );
		}

		return $album[$this->album_id];
	}

	/**
	 * Determines if this photo is used as a profile cover
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function isProfileCover()
	{
		$model 			= Foundry::model( 'Photos' );
		$isProfileCover	= $model->isProfileCover( $this->id , $this->uid , $this->type );

		return $isProfileCover;
	}

	public function isCover()
	{
		return ( $this->getAlbum()->cover_id == $this->id );
	}

	/**
	 * Constructs the alias for this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlias()
	{
		$title 	= $this->title;

		$title 	= JFilterOutput::stringURLSafe( $title );

		$alias 	= $this->id . ':' . $title;

		return $alias;
	}

	/**
	 * Returns the permalink to the photo
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string
	 */
	public function getPermalink( $xhtml = true , $external = false )
	{
		$user 		= Foundry::user( $this->uid );

		$options 	= array( 'layout' => 'item', 'id' => $this->getAlias() , 'userid' => $user->getAlias() );

		if( $external )
		{
			$options[ 'external' ]	= true;
		}

		return FRoute::photos( $options , $xhtml );
	}

	public function export()
	{
		$properties = get_object_vars( $this );

		$photo = array();

		foreach( $properties as $key => $value )
		{
			if( $key[0] != '_' )
			{
				$photo[$key] = $value;
			}
		}

		$photo['sizes'] = array();

		foreach( array( 'large', 'square', 'thumbnail', 'featured', 'original', 'stock' ) as $size )
		{
			$photo['sizes'][$size] = array();

			$photo['sizes'][$size]['url'] = $this->getSource( $size );
		}

		$photo['permalink'] = $this->getPermalink();

		return $photo;
	}

	public function uuid()
	{
		return $this->_uuid;
	}

	/**
	 * Determines if the user is allowed to edit this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user to check against (optional)
	 * @return	bool	True if success, false otherwise
	 */
	public function editable( $id = null )
	{
		$user 	= Foundry::user( $id );

		// If the user is the owner of this photo we need to allow this
		if( $user->id == $this->uid )
		{
			return true;
		}

		// If the user is the site admin
		if( $user->isSiteAdmin() )
		{
			return true;
		}

		return false;
	}

	/**
	 * Maps the exif data to this photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function mapExif( $file )
	{
		$exif 	= Foundry::get( 'Exif' );

		// Detect the photo caption and title if exif is available.
		if( !$exif->isAvailable() )
		{
			return;
		}

		$exif->load( $file[ 'tmp_name' ] );

		$title 		= $exif->getTitle();
		$caption	= $exif->getCaption();

		if( $title )
		{
			$this->title 	= $title;
		}

		if( $caption )
		{
			$this->caption	= $caption;
		}

		// Store the photo again since the title or caption might change
		if( $title || $caption )
		{
			$this->store();
		}

		// Get the photo model
		$model 		= Foundry::model( 'Photos' );

		// Get the location
		$locationCoordinates 	= $exif->getLocation();

		// Once we have the coordinates, we need to reverse geocode it to get the address.
		if( $locationCoordinates )
		{
			$geocode 	= Foundry::get( 'GeoCode' );
			$address	= $geocode->reverse( $locationCoordinates->latitude , $locationCoordinates->longitude );

			$location 				= Foundry::table( 'Location' );
			$location->loadByType( $this->id , SOCIAL_TYPE_PHOTO , $this->uid );

			$location->address		= $address;
			$location->latitude		= $locationCoordinates->latitude;
			$location->longitude	= $locationCoordinates->longitude;
			$location->user_id 		= $this->uid;
			$location->type 		= SOCIAL_TYPE_PHOTO;
			$location->uid 			= $this->id;

			$state 	= $location->store();
		}

		// Store custom meta data for the photo
		$model->storeCustomMeta( $this , $exif );
	}
}
