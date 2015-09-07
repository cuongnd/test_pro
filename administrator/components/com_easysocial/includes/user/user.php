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
Foundry::import( 'admin:/includes/indexer/indexer' );

/**
 * This class allows caller to fetch a user object easily.
 * Brief example of use:
 *
 * <code>
 * // Loading of the current logged in user.
 * $user	= Foundry::get( 'User' )
 *
 * // Shorthand loading
 * $user 	= Foundry::user();
 *
 * // Loading of a user based on the id.
 * $user	= Foundry::get( 'User' , 42 );
 *
 * // Loading of multiple users based on an array of id's.
 * $users	= Foundry::get( 'User' , array( 42 , 43 , 44 ) );
 *
 * </code>
 *
 * @since	1.0
 * @access	public
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUser extends JUser
{
	/**
	 * The user's unique id.
	 * @var int
	 */
	public $id 			= null;

	/**
	 * The user's name which is stored in `#__users` table.
	 * @var string
	 */
	public $name		= null;

	/**
	 * The user's username which is stored in `#__users` table.
	 * @var string
	 */
	public $username	= null;

	/**
	 * The user's email which is stored in `#__users` table.
	 * @var string
	 */
	public $email 		= null;

	/**
	 * The user's password which is a md5 hash which is stored in `#__users` table.
	 * @var string
	 */
	public $password 	= null;

	/**
	 * The user's type which is stored in `#__users` table. (Only for Joomla 1.5)
	 * @var string
	 */
	public $usertype 	= null;

	/**
	 * The user's published status which is stored in `#__users` table.
	 * @var int
	 */
	public $block 			= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $sendEmail 		= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $registerDate	= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $lastvisitDate	= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $activation 		= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $params 			= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $privacy			= null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $connections		= 0;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $param		= null;

	/**
	 * User's current state. Stored in `#__social_users` table.
	 * @var int
	 */
	public $state       = null;

	/**
	 * User's preferences on receiving emails. Stored in `#__users` table.
	 * @var int
	 */
	public $profile_id   = null;

	/**
	 * User's avatar id (from gallery). Stored in `#__social_avatars` table.
	 * @var int
	 */
	public $avatar_id    = null;

	/**
	 * User's avatar id (from uploaded photos). Stored in `#__social_avatars` table.
	 * @var int
	 */
	public $photo_id    = null;

	/**
	 * User's permalink
	 * @var string
	 */
	public $permalink	= null;

	/**
	 * User's online status. This isn't stored anywhere. It's just loaded
	 * initially, to let other's know of the user's online state.
	 * @var int
	 */
	public $online		= null;

	/**
	 * User's alias.
	 *
	 * @var string
	 */
	public $alias 		= null;


	// /* Joomla 1.6 */
	// public $groups			= null;

	/*
	 * Custom values
	 */
	public $password_clear   = null;


	// Default avatar sizes
	public $avatarSizes	= array( 'small' , 'medium' , 'large' , 'square' );

	// Avatars
	public $avatars 		= array( 'small' 	=> '',
									 'medium' 	=> '',
									 'large'	=> '',
									 'square'	=> ''
									);

	// Cover Photo
	public $cover 			= null;

	/**
	 * Stores the default avatar property if exists.
	 * @var SocialTableDefaultAvatar
	 */
	public $defaultAvatar	= null;

	/**
	 * The user's points
	 * @var int
	 */
	public $points 		= 0;

	/**
	 * The user's fields
	 * @var	Array
	 */
	public $fields 		= array();

	/**
	 * Stores the user type.
	 * @var	string
	 */
	public $type = 'joomla';

	/**
	 * Keeps a list of users that are already loaded so we
	 * don't have to always reload the user again.
	 * @var Array
	 */
	static $userInstances	= array();

	/**
	 * Keeps a list of super admin ids.
	 * @var Array
	 */
	static $admins 		= array();

	/**
	 * Stores user badges
	 * @var Array
	 */
	protected $badges 		= array();

	/**
	 * Helper object for various cms versions.
	 * @var	object
	 */
	protected $helper 		= null;

	/**
	 * Determines the storage type for the avatars
	 * @var string
	 */
	protected $avatarStorage = 'joomla';

	/**
	 * Class Constructor
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function __construct( $params = array() , $debug = false )
	{
		// Get our version info
		$version 	= Foundry::version();

		// Initialize helper object.
		$className 		= 'SocialUserHelper' . ucfirst( $version->getCodeName() );

		// Get the path to the helper file.
		$file 			= dirname( __FILE__ ) . '/helpers/' . $version->getCodeName() . '.php';

		require_once( $file );

		$this->helper	= new $className( $this );

		// Create the user parameters object
		$this->_params = new JRegistry;

		// Initialize user's property locally.
		$this->initParams( $params );
	}

	/**
	 * Blocks a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function block()
	{
		// Set juser data first
		$this->block 	= SOCIAL_JOOMLA_USER_BLOCKED;

		// Set our own state data
		$this->state 	= SOCIAL_USER_STATE_DISABLED;

		// onBeforeBlock

		$state 	= $this->save();

		// onAfterBlock

		return $state;
	}

	/**
	 * Blocks a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function unblock()
	{
		// Set juser data first
		$this->block 	= SOCIAL_JOOMLA_USER_UNBLOCKED;

		// Set our own state data
		$this->state 	= SOCIAL_USER_STATE_ENABLED;

		// onBeforeUnblock

		$state 		= $this->save();

		// onAfterUnblock

		return $state;
	}

	/**
	 * Determines if this user is blocked
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True if user is blocked, false otherwise
	 */
	public function isBlock()
	{
		return $this->block;
	}

	/**
	 * Assign this user to a group
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The group id
	 * @return	bool	True if success, false otherwise
	 */
	public function assign( $gid )
	{
		$model = Foundry::model( 'Users' );

		$model->assignToGroup( $this->id , $gid );
	}

	/**
	 * Initializes the provided properties into the existing object. Instead of
	 * trying to query to fetch more info about the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	void
	 */
	public function initParams( &$params )
	{
		// Get all properties of this object
		$properties	= get_object_vars( $this );

		// Bind parameters to the object
		foreach( $properties as $key => $val )
		{
			if( isset( $params->$key ) )
			{
				$this->$key		= $params->$key;
			}
		}

		// Bind params json object here
		$this->_params->loadString( $this->params );

		// Bind user avatars here.
		foreach( $this->avatars as $size => $value )
		{
			if( isset( $params->$size ) )
			{
				$this->avatars[ $size ]	= $params->$size;
			}
		}

		// set the list of user groups
		$this->groups 	= $this->helper->getUserGroups();

	}

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   $id     int/Array     Optional parameter
	 * @return  SocialUser   The person object.
	 */
	public static function factory( $ids = null , $debug = false )
	{
		$items	= self::loadUsers( $ids , $debug );

		return $items;
	}

	/**
	 * Processes user related stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function addStream( $verb )
	{
		if( $verb == 'uploadAvatar' )
		{
			// Add stream item when a new photo is uploaded.
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor.
			$streamTemplate->setActor( $this->id , SOCIAL_TYPE_USER );

			// Set the context.
			$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PHOTO );

			// Set the verb.
			$streamTemplate->setVerb( 'add' );

			$streamTemplate->setPublicStream( 'photos.view' );


			//
			$streamTemplate->setType( 'full' );

			// Create the stream data.
			$stream->add( $streamTemplate );
		}

		if( $verb == 'updateProfile' )
		{
			// Add stream item when a new photo is uploaded.
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor.
			$streamTemplate->setActor( $this->id , SOCIAL_TYPE_USER );

			// Set the context.
			$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PROFILES );

			// Set the verb.
			$streamTemplate->setVerb( 'update' );


			$streamTemplate->setAggregate( true );


			$streamTemplate->setPublicStream( 'core.view' );


			// Set stream style
			$streamTemplate->setType( 'mini' );

			// Create the stream data.
			$stream->add( $streamTemplate );
		}
	}

	/**
	 * Retrieves a list of apps for a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getApps( $view )
	{
		static $apps 	= array();

		if( !isset( $apps[ $this->id ][ $view ] ) )
		{
			$model 		= Foundry::model( 'Apps' );
			$options 	= array( 'view' => $view , 'uid' => $this->id , 'key' => SOCIAL_TYPE_USER );
			$userApps 	= $model->getApps( $options );

			$apps[ $this->id ][ $view ]	= $userApps;
		}

		return $apps[ $this->id ][ $view ];
	}

	/**
	 * Creates a guest object and store them into the property as static instance.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function createGuestObject()
	{
		// Set guest property
		if( !isset( self::$userInstances[ 0 ] ) )
		{
			$guest	= Foundry::table( 'Users' );
			$data	= array();

			$obj 	= new self( $guest ,  $data );

			$obj->id 	= 0;
			$obj->name 	= JText::_( 'COM_EASYSOCIAL_GUEST_NAME' );

			self::$userInstances[0]	= $obj;
		}
	}

	/**
	 * Loads a given user id or an array of id's.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Loads current logged in user.
	 * $my 		= Foundry::get( 'User' );
	 * // Shorthand
	 * $my 		= Foundry::user();
	 *
	 * // Loads a single user.
	 * $user	= Foundry::get( 'User' , 42 );
	 * // Shorthand
	 * $user 	= Foundry::user( 42 );
	 *
	 * // Loads multiple users.
	 * $users 	= Foundry::get( 'User' , array( 42 , 43 ) );
	 * // Shorthand
	 * $users 	= Foundry::user( array( 42 , 43 ) );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int|Array	Either an int or an array of id's in integer.
	 * @return	SocialUser	The user object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function loadUsers( $ids = null , $debug = false )
	{
		// Determine if the argument is an array.
		$argumentIsArray	= is_array( $ids );

		// If it is null or 0, the caller wants to retrieve the current logged in user.
		if( is_null( $ids ) || (is_string( $ids ) && $ids == '' ) )
		{
			$ids 	= array( JFactory::getUser()->id );
		}

		// Ensure that id's are always an array
		$ids 	= Foundry::makeArray( $ids );

		// Reset the index of ids so we don't load multiple times from the same user.
		$ids 	= array_values( $ids );

		// Always create the guest objects first.
		self::createGuestObject();

		// Total needs to be computed here before entering iteration as it might be affected by unset.
		$total		= count( $ids );

		// Placeholder for items that are already loaded.
		$loaded		= array();

		// @task: We need to only load user's that aren't loaded yet.
		for( $i = 0; $i < $total; $i++ )
		{
			if( empty( $ids ) )
			{
				break;
			}

			if(! isset( $ids[ $i ] ) && empty( $ids[ $i ] ) )
			{
				continue;
			}

			$id		= $ids[ $i ];

			// If id is null, we know we want the current user.
			if( is_null( $id ) )
			{
				$ids[ $i ] 	= JFactory::getUser()->id;
			}

			// The parsed id's could be an object from the database query.
			if( is_object( $id ) && isset( $id->id ) )
			{
				$id			= $id->id;

				// Replace the current value with the proper value.
				$ids[ $i ]	= $id;
			}

			if( isset( self::$userInstances[ $id ]) )
			{
				$loaded[]	= $id;
				unset( $ids[ $i ] );
			}
		}

		// @task: Reset the ids after it was previously unset.
		$ids	= array_values( $ids );

		// Place holder for result items.
		$result	= array();

		foreach( $loaded as $id )
		{
			$result[]	= self::$userInstances[ $id ];
		}

		if( !empty( $ids ) )
		{
			// @task: Now, get the user data.
			$model 	= Foundry::model( 'Users' );

			$users	= $model->getUsersMeta( $ids );

			if( $users )
			{
				// @task: Iterate through the users list and add them into the static property.
				foreach( $users as $user )
				{
					// Get the user's cover photo
					$user->cover	= self::getCoverObject( $user );

					// Detect if the user has an avatar.
					$user->defaultAvatar 	= false;

					if( $user->avatar_id )
					{
						$defaultAvatar			= Foundry::table( 'DefaultAvatar' );
						$defaultAvatar->load( $user->avatar_id );
						$user->defaultAvatar 	= $defaultAvatar;
					}

					// Try to load the user from `#__social_users`
					// If the user record doesn't exists in #__social_users we need to initialize it first.
					if( !$model->metaExists( $user->id ) )
					{
						$model->createMeta( $user->id );
					}

					// Attach fields for this user.
					$user->fields 	= $model->initUserData( $user->id );

					// // Get user's badges
					$user->badges 	= Foundry::model( 'Badges' )->getBadges( $user->id );

					// Create an object of itself and store in the static object.
					$obj 	= new SocialUser( $user );

					self::$userInstances[ $user->id ]	= $obj;

					$result[]	= self::$userInstances[ $user->id ];
				}
			}
			else
			{
				foreach( $ids as $id )
				{
					// Since there are no such users, we just use the guest object.
					self::$userInstances[ $id ] = self::$userInstances[ 0 ];

					$result[]	= self::$userInstances[ $id ];
				}
			}
		}

		// If the argument passed in is not an array, just return the proper value.
		if( !$argumentIsArray && count( $result ) == 1 )
		{
			return $result[0];
		}

		return $result;
	}

	/**
	 * Bind the cover object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getCoverObject( $user )
	{
		$cover 					= Foundry::table( 'Cover' );

		$coverData 				= new stdClass();
		$coverData->id			= $user->cover_id;
		$coverData->uid			= $user->cover_uid;
		$coverData->type 		= $user->cover_type;
		$coverData->photo_id	= $user->cover_photo_id;
		$coverData->cover_id 	= $user->cover_cover_id;
		$coverData->x 			= $user->cover_x;
		$coverData->y 			= $user->cover_y;
		$coverData->modified	= $user->cover_modified;

		$cover->bind( $coverData );


		return $cover;
	}

	/**
	 * Determines whether the current user is active or not.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	boolean		True if online, false otherwise.
	 */
	public function isOnline()
	{
		static $states 	= array();

		if( !isset( $states[ $this->id ] ) )
		{
			$model 	= Foundry::model( 'Users' );

			$online	= $model->isOnline( $this->id );

			$states[ $this->id ]	= $online;
		}

		return $states[ $this->id ];
	}

	/**
	 * Determines if the current logged in user is viewing this current page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	boolean		True if online, false otherwise.
	 */
	public function isViewer()
	{
		$my 	= Foundry::user();

		$isViewer	= $my->id == $this->id;

		return $isViewer;
	}

	/**
	 * Determines if the user is logged in
	 *
	 * @since	1.0
	 * @access	public
	 * @return	boolean
	 */
	public function isLoggedIn()
	{
		return $this->id > 0;
	}

	/**
	 * Logs the user out from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function logout()
	{
		$app = JFactory::getApplication();

		// Try to logout the user.
		$error = $app->logout();

		return $error;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function addPoints( $point )
	{
		$this->points 	+= $point;

		return $this;
	}

	/**
	 * Determines if the current user is a super administrator of the site or not.
	 *
	 * @access	public
	 * @param	null
	 * @return	boolean	True on success false otherwise.
	 */
	public function isSiteAdmin()
	{
		$isSiteAdmin	= false;
		$version 		= Foundry::getInstance( 'Version' );

		$isSiteAdmin	= $this->authorise( 'core.admin' );

		return ( $isSiteAdmin ) ? true : false ;
	}

	/**
	 * Determines if the current user is followed by the target id
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 	The target user id.
	 *
	 * @return	boolean 		True if success, false otherwise.
	 */
	public function isFollowed( $id )
	{
		static $followed	= null;

		if( !isset( $followed[ $this->id ][ $id ] ) )
		{
			$subscription 					= Foundry::get( 'Subscriptions' );
			$followed[ $this->id ][ $id ]	= $subscription->isFollowing( $this->id , SOCIAL_TYPE_USER , SOCIAL_APPS_GROUP_USER , $id );
		}

		return $followed[ $this->id ][ $id ];
	}

	/**
	 * Determines if the current user is friends with the specified user id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 	The target user id.
	 *
	 * @return	boolean 		True if success, false otherwise.
	 */
	public function isFriends( $id )
	{
		static $isFriends	= null;

		if( !isset( $isFriends[ $this->id ][ $id ] ) )
		{
			$model 	= Foundry::model( 'Friends' );

			$isFriends[ $this->id ][ $id ]	= $model->isFriends( $this->id , $id );
		}

		return $isFriends[ $this->id ][ $id ];
	}

	/**
	 * Determines if the current user is friends with the specified user id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 	The target user id.
	 *
	 * @return	boolean 		True if success, false otherwise.
	 */
	public function getFriend( $id )
	{
		static $data = array();

		if( !isset( $data[ $this->id ] ) )
		{
			$data[ $this->id ]	= array();
		}

		if( !isset( $data[ $this->id ][ $id ] ) )
		{
			$friend 	= Foundry::table( 'Friend' );
			$friend->loadByUser( $this->id , $id );
		}

		return $friend;
	}

	/**
	 * Determines if the person is a registered member or not.
	 *
	 * @param	null
	 * @return	boolean		True if registered, false otherwise.
	 */
	public function isRegistered()
	{
		return $this->id > 0;
	}

	/**
	 * Determines if the current user record is a new user or not.
	 *
	 * @access	private
	 * @param	null
	 * @return	boolean	True on success false otherwise.
	 */
	private function isNew()
	{
		return $this->id < 1;
	}

	/**
	 * Determines if the person is pending approval or not.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	boolean		True if still pending, false otherwise.
	 */
	public function isPending()
	{
		if( $this->status == SOCIAL_REGISTER_APPROVAL )
		{
			return true;
		}

		return false;
	}

	public function hasAvatar()
	{
		return !empty($this->avatar_id) || !empty($this->photo_id);
	}

	/**
	 * Retrieves the user's avatar location
	 *
	 * @access	public
	 * @param   string	$size 	The avatar size to retrieve for.
	 * @return  string  The current user's username.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getAvatar( $size = SOCIAL_AVATAR_MEDIUM )
	{
		$config 	= Foundry::config();

		// If avatar id is being set, we need to get the avatar source
		if( $this->defaultAvatar )
		{
			$default =  $this->defaultAvatar->getSource( $size );

			return $default;
		}

		// If the avatar size that is being requested is invalid, return default avatar.
		$default = rtrim( JURI::root() , '/' ) . $config->get( 'avatars.default.user.' . $size );

		if( !$this->avatars[ $size ] || empty( $this->avatars[ $size ] ) )
		{
			return $default;
		}
		// Get the path to the avatar storage.
		$avatarLocation 		= Foundry::cleanPath( $config->get( 'avatars.storage.container' ) );
		$usersAvatarLocation	= Foundry::cleanPath( $config->get( 'avatars.storage.user' ) );

		// Build the path now.
		$path 			= $avatarLocation . '/' . $usersAvatarLocation . '/' . $this->id . '/' . $this->avatars[ $size ];

		if( $this->avatarStorage == SOCIAL_STORAGE_JOOMLA )
		{
			// Build final storage path.
			$absolutePath 	= JPATH_ROOT . '/' . $path;

			// Detect if this file really exists.
			if( !JFile::exists( $absolutePath ) )
			{
				return $default;
			}

			$uri 	= rtrim( JURI::root() , '/' ) . '/' . $path;
		}
		else
		{
			$storage 	= Foundry::storage( $this->avatarStorage );
			$uri 		= $storage->getPermalink( $path );
		}

	    return $uri;
	}

	/**
	 * Retrieves the photo table for the user's avatar
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAvatarPhoto()
	{
		static $photos 	= array();

		if( !isset( $photos[ $this->id ] ) )
		{
			$model 	= Foundry::model( 'Avatars' );
			$photo	= $model->getPhoto( $this->id );

			$photos[ $this->id ]	= $photo;
		}

		return $photos[ $this->id ];
	}

	public function hasCover()
	{
		return !(empty($user->cover) || empty($user->cover->id));
	}

	/**
	 * Retrieves the user's cover location
	 *
	 * @access	public
	 * @param   string	$size 	The avatar size to retrieve for.
	 * @return  string  The current user's username.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCover()
	{
		return $this->cover;
	}

	/**
	 * Retrieves the user badges
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getBadges()
	{
		// Returns a list of badges earned by the user.
		return $this->badges;
	}

	/**
	 * Retrieves the user's username
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  string  The current user's username.
	 */
	public function getUserName()
	{
		return $this->username;
	}

	/**
	 * Retrieves the user's real name dependent on the system configurations.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  string  The current user's screen name. It can be in a form of (name, username or email)
	 */
	public function getName( $useFormat = '' )
	{
		$config 	= Foundry::config();
		$name 		= $this->username;

		if( $useFormat )
		{
			if( $useFormat == 'realname' )
				$name 	= JString::ucfirst( $this->name );
		}
		else
		{
			if( $config->get( 'users.displayName' ) == 'realname' )
				$name 	= JString::ucfirst( $this->name );
		}

		return $name;
	}

	/**
	 * Get's a user stream name. If the current logged in user is him/her self, use "You" instead.
	 * This can be applied to anyone that is trying to apply stream like-ish contents.
	 *
	 * @access	public
	 * @return	string
	 */
	public function getStreamName( $uppercase = true )
	{
		$my			= Foundry::user();

		if( $my->id == $this->id )
		{
			$uppercase 	= $uppercase ? '' : '_LOWERCASE';

			return JText::_( 'COM_EASYSOCIAL_YOU' . $uppercase );
		}

		return $this->getName();
	}

	/**
	 * Retrieves the user's connection.
	 *
	 * @param   null
	 * @return  string  The current user's connection.
	 */
	public function getConnections()
	{
	    return $this->connections;
	}

	/**
	 * TODO: Gets the user's points
	 *
	 * @access	public
	 * @param	null
	 * @return	float	The points that a user has.
	 */
	public function getPoints()
	{
		return $this->points;
	}

	/**
	 * Returns the last visited date from a user.
	 *
	 * Example of usage:
	 * <code>
	 * <?php
	 * $user 	= Foundry::user();
	 *
	 * // Displays: 5 mins ago
	 * echo $user->getLastVisitDate()->toLapsed();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialDate		The last visited date object.
	 */
	public function getLastVisitDate( $type = '' )
	{
		// If user wants a lapsed type.
		if( $type == 'lapsed' )
		{
			$date 	= Foundry::date( $this->lastvisitDate );

			return $date->toLapsed();
		}

		return $this->lastvisitDate;
	}

	/**
	 * Returns the user's user group that they belong to.
	 *
	 * Example of usage:
	 * <code>
	 * <?php
	 * $user 	= Foundry::user();
	 *
	 * // Returns array( 'ID' => 'Super User' , 'ID' => 'Registered' )
	 * $user->getUserGroups();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array	An array of group in string.
	 */
	public function getUserGroups( $gids = false )
	{
		$groups 	= $this->helper->getUserGroups();

		if( $gids )
		{
			return array_keys( $groups );
		}

		return $groups;
	}

	/**
	 * Returns the last visited date from a user.
	 *
	 * Example of usage:
	 * <code>
	 * <?php
	 * $user 	= Foundry::user();
	 *
	 * // Displays: 5 mins ago
	 * echo $user->getLastVisitDate()->toLapsed();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialDate		The last visited date object.
	 */
	public function getRegistrationDate()
	{
		$date 	= Foundry::get( 'Date' , $this->registerDate );

		return $date;
	}

	/**
	 * Retrieves the profile type of the current user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	TableProfiles
	 */
	public function getProfile()
	{
		static $profiles 	= array();

		if( !isset( $profiles[ $this->profile_id ] ) )
		{
			$profile 	= Foundry::table( 'Profile' );
			$profile->load( $this->profile_id );

			$profiles[ $this->profile_id ]	= $profile;
		}

		return $profiles[ $this->profile_id ];
	}

	/**
	 * Retrieves the privacy object of the current user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialPrivacy
	 */
	public function getPrivacy()
	{
		$privacy 	= Foundry::privacy( $this->id );

		return $privacy;
	}

	/**
	 * Get the alias of the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlias( $withId = true )
	{
		$config 	= Foundry::config();

		// Default permalink to use.
		$name 		= $config->get( 'users.aliasName' ) == 'realname' ? $this->name : $this->username;

		// Check if the permalink is set
		if( $this->permalink && !empty( $this->permalink ) )
		{
			$name 	= $this->permalink;
		}

		// If alias exists and permalink doesn't we use the alias
		if( $this->alias && !empty( $this->alias ) && !$this->permalink )
		{
			$name 	= $this->alias;
		}

		$name 		= $this->id . ':' . $name;

		// Ensure that the name is a safe url.
		$name 	= JFilterOutput::stringURLSafe( $name );

		return $name;
	}

	/**
	 * Centralized method to retrieve a person's profile link.
	 * This is where all the magic happens.
	 *
	 * @access	public
	 * @param	null
	 *
	 * @return	string	The url for the person
	 */
	public function getPermalink( $xhtml = true , $external = false )
	{
		$options	= array( 'id' => $this->getAlias() );

		if( $external )
		{
			$options[ 'external' ]	= true;
		}

		$url 	= FRoute::profile( $options , $xhtml );

		return $url;
	}

	/**
	 * Retrieves the custom field value from this user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getFieldValue( $key , $default = '' )
	{
		static $processed 	= array();

		if( !isset( $processed[ $this->id ] ) )
		{
			$processed[ $this->id ]	= array();
		}

		if( !isset( $processed[ $this->id ][ $key ] ) )
		{
			$field 	= isset( $this->fields[ $key ] ) ? $this->fields[ $key ] : false;

			// Initialize a default property
			$processed[ $this->id ][ $key ]	= '';

			if( $field )
			{
				// Trigger the getFieldValue to obtain data from the field.
				$value 	= Foundry::fields()->getValue( $field , SOCIAL_FIELDS_GROUP_USER );

				$processed[ $this->id ][ $key ] 	= $value;
			}
		}

		return $processed[ $this->id ][ $key ];
	}

	/**
	 * Returns the total number of followers the user has
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getTotalFollowers()
	{
		static $total 	= array();

		if( !isset($total[ $this->id ] ) )
		{
			$model	= Foundry::model( 'Followers' );

			$total[ $this->id ]	= $model->getTotalFollowers( $this->id );
		}

		return $total[ $this->id ];
	}

	/**
	 * Retrieves the total albums the user has
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalAlbums( $excludeCore = false )
	{
		static $total 	= array();

		if( !isset( $total[ $this->id ] ) )
		{
			$model 		= Foundry::model( 'Albums' );
			$options 	= array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_USER );

			if( $excludeCore )
			{
				$options[ 'excludeCore' ]	= $excludeCore;
			}

			$total[ $this->id ] = $model->getTotalAlbums( $options );
		}

		return $total[ $this->id ];
	}

	/**
	 * Returns the total number of badges the user has
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getTotalBadges()
	{
		static $total 	= array();

		if( !isset($total[ $this->id ] ) )
		{
			$model	= Foundry::model( 'Badges' );

			$total[ $this->id ]	= $model->getTotalBadges( $this->id );
		}

		return $total[ $this->id ];
	}

	/**
	 * Returns the total number of users this user follows.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getTotalFollowing()
	{
		static $total 	= array();

		if( !isset($total[ $this->id ] ) )
		{
			$model	= Foundry::model( 'Followers' );

			$total[ $this->id ]	= $model->getTotalFollowing( $this->id );
		}

		return $total[ $this->id ];
	}

	/**
	 * Retrieves the default friend list for this user.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialTableList
	 */
	public function getDefaultFriendList()
	{
		static $lists 	= array();

		if( !isset( $lists[ $this->id ] ) )
		{
			$list 	= Foundry::table( 'List' );
			$exists	= $list->load( array( 'default' => 1 , 'user_id' => $this->id ) );

			if( !$exists )
			{
				$lists[ $this->id ]	= false;
			}
			else
			{
				$lists[ $this->id ]	= $list;
			}
		}


		return $lists[ $this->id ];
	}

	/**
	 * Returns the total number of friends list the current user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getTotalFriendsList()
	{
		static $total	= array();

		if( ! isset( $total[ $this->id ] ) )
		{
			$model					= Foundry::model( 'Lists' );
			$total[ $this->id ] 	= $model->getTotalLists( $this->id );
		}

		return $total[ $this->id ];
	}

	/**
	 * Returns the total number of friends the current user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getTotalFriends()
	{
		static $total	= array();

		if( ! isset( $total[ $this->id ] ) )
		{
			$model	= Foundry::model( 'Friends' );
			$total[ $this->id ] 	= $model->getTotalFriends( $this->id );
		}

		return $total[ $this->id ];
	}

	/**
	 * Retrieves the oauth token
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getOAuth( $client = '' )
	{
		$oauth 	= Foundry::table( 'OAuth' );

		$state 	= $oauth->load( array( 'client' => $client , 'uid' => $this->id , 'type' => SOCIAL_TYPE_USER ) );

		if( !$state )
		{
			return false;
		}

		return $oauth;
	}

	/**
	 * Retrieves the oauth token
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getOAuthToken( $client = '' )
	{
		$oauth 	= Foundry::table( 'OAuth' );

		$oauth->load( array( 'client' => $client , 'uid' => $this->id , 'type' => SOCIAL_TYPE_USER ) );

		return $oauth->token;
	}

	/**
	 * Gets the oauth library for this user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isAssociated( $clientType = '' )
	{
		$oauth 	= Foundry::table( 'OAuth' );
		$state 	= $oauth->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_USER ) );

		if( !$state )
		{
			return false;
		}

		return true;
	}

	/**
	 * Retrieves the total number of mutual friends.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalMutualFriends( $targetId )
	{
		static $data 	= array();

		if( !isset( $data[ $this->id ] ) )
		{
			$model 		= Foundry::model( 'Friends' );

			$total 		= $model->getMutualFriendCount( $this->id , $targetId );

			$data[ $this->id ]	= $total;
		}

		return $data[ $this->id ];
	}

	/**
	 * Gets the @SocialAccess object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialAccess
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getAccess()
	{
		static $data	= null;

		if( !isset( $data[ $this->id ] ) )
		{
			$access	= Foundry::access( $this->id , SOCIAL_TYPE_USER );

			$data[ $this->id ]	= $access;
		}

		return $data[ $this->id ];
	}

	/**
	 * Returns the total number of new notifications for this user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	int		The total number of unread notifications the user has.
	 */
	public function getTotalNewNotifications()
	{
		static $total 	= null;

		if( is_null( $total ) )
		{
			$model 	= Foundry::model( 'Notifications' );
			$total	= $model->getCount( array( 'unread' => 1 , 'target' => array( 'id' => $this->id , 'type' => SOCIAL_TYPE_USER ) ) );
		}

		return $total;
	}

	/**
	 * Returns the total number of new conversations this user has not yet read.
	 *
	 * @param	null
	 * @return	int 	The total new conversations
	 */
	public function getTotalNewConversations()
	{
		static $results	= array();

		if( !isset( $results[ $this->id ] ) )
		{
			$model	= Foundry::model( 'Conversations' );
			$total 	= $model->getConversations( $this->id , array( 'count' => true , 'filter' => 'unread' ) );

			$results[ $this->id ]	= $total;
		}

		return $results[ $this->id ];
	}

	/**
	 * Returns the total number of new friend requests the user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	int 		The total number of requests.
	 */
	public function getTotalFriendRequests()
	{
		static $results 	= array();

		if( !isset( $results[ $this->id ] ) )
		{
			$model 	= Foundry::model( 'Friends' );
			$total 	= $model->getTotalRequests( $this->id );

			$results[ $this->id ]	= $total;
		}

		return $results[ $this->id ];
	}

	/**
	 * Loads the user's session
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function loadSession()
	{
		$user 	= Foundry::user();

		$this->helper->loadSession( $this , $user );
	}

	/*
	 * Allows caller to update a specific field item given it's unique id and value.
	 *
	 * @param   int     $fieldId    The field id.
	 * @param   mixed   $value      The value for that field.
	 *
	 * @return  boolean True on success, false otherwise.
	 */
	public function updateField( $fieldId , $value )
	{
		$data   = Foundry::table( 'FieldData' );
		$data->loadByField( $fieldId , $this->node_id );

		$data->node_id  = $this->node_id;
		$data->field_id = $fieldId;
		$data->data     = $value;
		$data->data_binary  = $value;

		return $data->store();
	}

	/**
	 * Determines if this user account can be deleted.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteable()
	{
		if( $this->isSiteAdmin() )
		{
			return false;
		}

		// Check if this user's profile allows deletion.
		$profile 	= $this->getProfile();
		$params 	= $profile->getParams();

		if( $params->get( 'delete_account' ) )
		{
			return true;
		}

		return false;
	}

	public function deleteCover()
	{
		$state 	= $this->cover->delete();

		// Reset this user's cover
		$this->cover 	= Foundry::table( 'Cover' );

		return $state;
	}

	/**
	 * Override parent's delete implementation if necessary.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	The delete state. True on success, false otherwise.
	 */
	public function delete()
	{
		$state 	= parent::delete();

		// Once the user is deleted, we also need to delete it from the #__social_users table.
		if( $state )
		{
			// Perform cleanup here.
			$model 	= Foundry::model( 'Users' );
			$model->delete( $this->id );
		}

		return $state;
	}

	/**
	 * Override parent's implementation when save so we could run some pre / post rendering.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool
	 * @return	bool
	 */
	public function save( $updateOnly = false )
	{
		// Determine if this record is a new user by identifying the id.
		$isNew 		= $this->isNew();

		// Request parent to store data.
		$state 		= parent::save( $updateOnly );

		// Once the #__users table is updated, we need to update ours as well.
		if( $state )
		{
			$user 	= Foundry::table( 'Users' );
			$user->loadByUser( $this->id );

			$user->user_id 	= $this->id;
			$user->state 	= $this->state;
			$user->type 	= $this->type;
			$user->alias 	= $this->alias;

			$state 	= $user->store();

			// @TODO: Set the default parameters and connections?
			// $this->params = $this->param->toString();
			// $user->set( 'params'		, $params );
			// $user->set( 'connections'	, $connections );
		}


		return $state;
	}

	/**
	 * Activates a user account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function activate( $sendEmail = true )
	{
		// Load Joomla users plugin for triggers.
		JPluginHelper::importPlugin('user');

		// Set joomla parameters
		$this->activation 	= '';
		$this->block 		= 0;

		// Update the current state property.
		$this->state 	= SOCIAL_USER_STATE_ENABLED;

		// Try to save the user.
		$state 			= $this->save();

		// Save the user.
		if( !$state )
		{
			$this->setError( $this->getError() );
			return false;
		}

		return true;
	}

	/**
	 * Approves a user's registration application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function approve( $sendEmail = true )
	{
		// Update the JUser object.
		$this->block 	= 0;

		// Update the current state property.
		$this->state 	= SOCIAL_USER_STATE_ENABLED;

		// Store the block status
		$this->save();

		// Activity logging.
		// Announce to the world when a new user registered on the site.
		$config 			= Foundry::config();

		// If not allowed, we will not want to proceed here.
		if( $config->get( 'registrations.stream.create' ) )
		{
			// Get the stream library.
			$stream				= Foundry::stream();

			// Get stream template
			$streamTemplate		= $stream->getTemplate();

			// Set the actors.
			$streamTemplate->setActor( $this->id , SOCIAL_TYPE_USER );

			// Set the context for the stream.
			$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PROFILES );

			// Set the verb for this action as this is some sort of identifier.
			$streamTemplate->setVerb( 'register' );

			$streamTemplate->setSiteWide();


			$streamTemplate->setPublicStream( 'core.view' );


			// Add the stream item.
			$stream->add( $streamTemplate );
		}

		// @badge: registration.create
		// Assign badge for the person that initiated the friend request.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'registration.create' , $this->id , JText::_( 'COM_EASYSOCIAL_REGISTRATION_BADGE_REGISTERED' ) );

		// If we need to send email to the user, we need to process this here.
		if( $sendEmail )
		{
			// Get the application data.
			$jConfig 	= Foundry::jConfig();

			// Get the current profile this user has registered on.
			$profile 	= $this->getProfile();

			// Push arguments to template variables so users can use these arguments
			$params 	= array(
									'site'			=> $jConfig->getValue( 'sitename' ),
									'username'		=> $this->username,
									'name'			=> $this->getName(),
									'avatar'		=> $this->getAvatar( SOCIAL_AVATAR_LARGE ),
									'email'			=> $this->email,
									'profileType'	=> $profile->get( 'title' )
							);

			JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT );

			// Get the email title.
			$title      = JText::_( 'COM_EASYSOCIAL_EMAILS_REGISTRATION_APPLICATION_APPROVED' );

			// Immediately send out emails
			$mailer 	= Foundry::mailer();

			// Get the email template.
			$mailTemplate	= $mailer->getTemplate();

			// Set recipient
			$mailTemplate->setRecipient( $this->getName() , $this->email );

			// Set title
			$mailTemplate->setTitle( $title );

			// Set the contents
			$mailTemplate->setTemplate( 'site/registration/approved' , $params );

			// Set the priority. We need it to be sent out immediately since this is user registrations.
			$mailTemplate->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

			// Try to send out email now.
			$mailer->create( $mailTemplate );
		}

		return true;
	}

	/**
	 * Reject's a user's registration application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reject( $reason = '' , $sendEmail = true , $deleteUser = false )
	{
		// Announce to the world when a new user registered on the site.
		$config 			= Foundry::config();

		// If we need to send email to the user, we need to process this here.
		if( $sendEmail )
		{
			// Get the application data.
			$jConfig 	= Foundry::jConfig();

			// Get the current profile this user has registered on.
			$profile 	= $this->getProfile();

			// Push arguments to template variables so users can use these arguments
			$params 	= array(
									'site'			=> $jConfig->getValue( 'sitename' ),
									'username'		=> $this->username,
									'name'			=> $this->getName(),
									'email'			=> $this->email,
									'reason'		=> $reason,
									'profileType'	=> $profile->get( 'title' ),
									'manageAlerts'	=> false
							);

			JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT );

			// Get the email title.
			$title      = JText::_( 'COM_EASYSOCIAL_EMAILS_REGISTRATION_REJECTED_EMAIL_TITLE' );

			// Immediately send out emails
			$mailer 	= Foundry::mailer();

			// Get the email template.
			$mailTemplate	= $mailer->getTemplate();

			// Set recipient
			$mailTemplate->setRecipient( $this->getName() , $this->email );

			// Set title
			$mailTemplate->setTitle( $title );

			// Set the contents
			$mailTemplate->setTemplate( 'site/registration/rejected' , $params );

			// Set the priority. We need it to be sent out immediately since this is user registrations.
			$mailTemplate->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

			// Try to send out email now.
			$mailer->create( $mailTemplate );
		}

		// If required, delete the user from the site.
		if( $deleteUser )
		{
			$this->delete();
		}

		return true;
	}

	/**
	 * Bind an array of data to the current user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	The object's properties.
	 * @param	bool	Determines whether the data is from $_POST method.
	 *
	 * @return 	bool	True if success false otherwise.
	 */
	public function bind( &$data , $post = false )
	{
		// Request the helper to bind specific additional details
		$this->helper->bind( $this , $data );

		// Request the parent to bind the data for us.
		return parent::bind( $data );
	}

	/**
	 * Binds the user custom fields.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of data that is being posted.
	 * @return	bool	True on success, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function bindCustomFields( $data )
	{
		// Get the registration model.
		$model 	= Foundry::model( 'Fields' );

		// Get the field id's that this profile is allowed to store data on.
		$fields	= $model->getStorableFields( $this->profile_id , SOCIAL_TYPE_PROFILES );

		// If there's nothing to process, just ignore.
		if( !$fields )
		{
			return false;
		}

		// Let's go through all the storable fields and store them.
		foreach( $fields as $id )
		{
			$key 	= SOCIAL_FIELDS_PREFIX . $id;
			$value 	= isset( $data[ $key ] ) ? $data[ $key ] : '';

			// Test if field really exists to avoid any unwanted input
			$field		= Foundry::table( 'Field' );

			// If field doesn't exist, just skip this.
			if( !$field->load( $id ) )
			{
				continue;
			}

			// Store the field data.
			$fieldData  			= Foundry::table( 'FieldData' );

			// Try to load existing data.
			$state 	= $fieldData->loadByField( $field->id , $this->id , SOCIAL_TYPE_USER );

			// If the data was never stored before, try storing them.
			if( !$state )
			{
				// Set the unique id to the current user's id.
				$fieldData->uid 		= $this->id;

				// Set the unique type.
				$fieldData->type 		= SOCIAL_TYPE_USER;

				// Set foreign key to field's id.
				$fieldData->field_id	= $id;
			}

			// Set the value of course.
			if( is_array( $value ) || is_object( $value ) )
			{
				$value = Foundry::makeArray( $value );

				if( isset( $value['data'] ) && isset( $value['raw'] ) )
				{
					$fieldData->data = $value['data'];
					$fieldData->raw = $value['raw'];
				}
				else
				{
					$fieldData->data = Foundry::json()->encode( $value );
					$fieldData->raw = implode( ' ', $value );
				}
			}
			else
			{
				$fieldData->data = $value;
				$fieldData->raw = $value;
			}

			$fieldData->store();
		}
	}

	/**
	 * Binds the privacy object for the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function bindPrivacy( $privacy , $privacyIds , $customIds, $privacyOld, $resetPrivacy = false )
	{
		$privacyLib = Foundry::privacy();
		//$resetMap 	= call_user_func_array( array( $privacyLib , 'getResetMap' ) );
		$resetMap 	= $privacyLib->getResetMap();

		$result 	= array();

		if( empty( $privacy ) )
		{
			return false;
		}

		foreach( $privacy as $group => $items )
		{
			foreach( $items as $rule => $value )
			{
				$id		= $privacyIds[ $group ][ $rule ];
				$id 	= explode( '_' , $id );

				$custom			= $customIds[ $group ][ $rule ];
				$customUsers	= array();
				$curVal 	 	= $privacyOld[ $group ][ $rule ];

				// Break down custom user rules
				if( !empty( $custom ) )
				{
					$tmp 	= explode( ',' , $custom );

					foreach( $tmp as $userId )
					{
						if( !empty( $userId ) )
						{
							$customUsers[]	= $userId;
						}
					}
				}

				$obj 			= new stdClass();
				$obj->id		= $id[ 0 ];
				$obj->mapid		= $id[ 1 ];
				$obj->value		= $value;
				$obj->custom	= $customUsers;

				$obj->reset  = false;

				//check if require to reset or not.
				$gr = strtolower( $group . '.' . $rule );
				if( $resetPrivacy && in_array( $gr,  $resetMap ) )
				{
					$obj->reset = true;
				}

				$result[]	= $obj;
			}
		}

		$model 		= Foundry::model( 'Privacy' );
		$state 		= $model->updatePrivacy( $this->id , $result , SOCIAL_PRIVACY_TYPE_USER );

		return $state;
	}


	public function syncIndex()
	{
		$config = Foundry::config();

		$indexer = Foundry::get( 'Indexer' );

		$idxTemplate = $indexer->getTemplate();

		// should get the story, and customfields.
		$contentSnapshot	= array();
		$contentSnapshot[] 	= $this->getName( $config->get( 'users.indexer.name' ) );

		if( $config->get( 'users.indexer.email' ) )
		{
			$contentSnapshot[] 	= $this->email;
		}

		// get customfields.
		$fieldsLib		= Foundry::fields();
		$fieldModel  	= Foundry::model( 'Fields' );
		$fieldsResult 	= array();

		$options = array();
		$options['data'] 		= true;
		$options['dataId'] 		= $this->id;
		$options['dataType'] 	= SOCIAL_TYPE_USER;
		$options['searchable'] 	= 1;

		//todo: get customfields.
		$fields = $fieldModel->getCustomFields( $options );

		if( count( $fields ) > 0 )
		{
			//foreach( $fields as $item )
			foreach( $fields as $field )
			{

				//var_dump( $field );

				$userFieldData = isset( $field->data ) ? $field->data : '';

				$args 			= array( $userFieldData );
				$f 				= array( &$field );
				$dataResult 	= $fieldsLib->trigger( 'onIndexer' , SOCIAL_FIELDS_GROUP_USER , $f , $args );

				if( $dataResult !== false && count( $dataResult ) > 0 )
					$fieldsResult[]  	= $dataResult[0];
			}

			if( $fieldsResult )
			{
				$customFieldsContent 	= implode( ' ', $fieldsResult );
				$contentSnapshot[] 		= $customFieldsContent;
			}
		}

		$content = implode( ' ', $contentSnapshot );
		$idxTemplate->setContent( $this->getName( $config->get( 'users.indexer.name' ) ), $content );

		//$url = FRoute::_( 'index.php?option=com_easysocial&view=profile&id=' . $this->id );
		$url = '';

		$idxTemplate->setSource($this->id, SOCIAL_INDEXER_TYPE_USERS, $this->id, $url);

		$date = Foundry::date();
		$idxTemplate->setLastUpdate( $date->toMySQL() );

		$state = $indexer->index( $idxTemplate );
		return $state;
	}

	/**
	 * Allows caller to remove the user's avatar
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function removeAvatar()
	{
		$avatar 	= Foundry::table( 'Avatar' );
		$state 		= $avatar->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_USER ) );

		if( $state )
		{
			$state 		= $avatar->delete();
		}

		return $state;
	}
}
