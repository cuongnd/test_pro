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

// Import main table.
Foundry::import( 'admin:/tables/table' );

/**
 * Object mapping for profile types table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableProfile extends SocialTable
{
	/**
	 * The unique id for the profile type.
	 * @var int
	 */
	public $id			= null;

	/**
	 * The title of the profile type
	 * @var string
	 */
	public $title		= null;

	/**
	 * The alias of the profile type
	 * @var string
	 */
	public $alias		= null;

	/**
	 * The description of the profile type
	 * @var string
	 */
	public $description = null;

	/**
	 * The title of the profile type
	 * @var int
	 */
	public $gid         = null;

	/**
	 * The title of the profile type
	 * @var int
	 */
	public $default     = false;

	/**
	 * The title of the profile type
	 * @var int
	 */
	public $registration     = 1;

	/**
	 * The default avatar for this profile type.
	 * @var int
	 */
	public $default_avatar	= false;

	/**
	 * The creation date time of the profile
	 * @var datetime
	 */
	public $created     = null;

	/**
	 * The state of the profile
	 * @var int
	 */
	public $state       = 1;

	/**
	 * The parameters (JSON)
	 * @var string
	 */
	public $params      = null;

	/**
	 * The ordering of the profile
	 * @var int
	 */
	public $ordering	= null;

	/**
	 * The number of users in this profile type.
	 * @var int
	 */
	public $totalUsers 	= null;

	/**
	 * The users from this profile type.
	 * @var int
	 */
	public $users 	= null;


	public function __construct(& $db )
	{
		parent::__construct( '#__social_profiles' , 'id' , $db );
	}

	/**
	 * Retrieves the list of steps for this particular profile type.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Profile' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 *
	 * // Returns the steps for a particular profile type.
	 * $table->getSteps();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  array   An array of SocialTableWorkflow objects.
	 */
	public function getSteps( $type = null )
	{
		// Load language file from the back end as the steps title are most likely being translated.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		$model 		= Foundry::model( 'Steps' );
		$steps		= $model->getSteps( $this->id , SOCIAL_TYPE_PROFILES, $type );

		return $steps;
	}

	/**
	 * Retrieves the total number of steps for this particular profile type.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Profile' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 *
	 * // Returns the count in integer.
	 * $table->getTotalSteps();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  int		The number of steps involved for this profile type.
	 */
	public function getTotalSteps()
	{
		static $total 	= null;

		if( is_null( $total ) )
		{
			$model	= Foundry::model( 'Fields' );
			$total	= $model->getTotalSteps( $this->id , SOCIAL_TYPE_PROFILES );
		}

		return $total;
	}

	/**
	 * Returns parameters for the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialRegistry
	 *
	 */
	public function getParams()
	{
		static $registry 	= array();

		if( !isset( $registry[ $this->id ] ) )
		{
			$registry[ $this->id ] 	= Foundry::get( 'Registry' , $this->params );
		}

		return $registry[ $this->id ];
	}

	/**
	 * Removes all the workflows for a particular profile type.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True if success, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function resetSteps()
	{
		$db 	= Foundry::db();
		$query  = 'DELETE FROM ' . $db->nameQuote( '#__social_fields_steps' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'profile_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );

		$db->Query();
	}

	/**
	 * Binds the access for this profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function bindAccess( $post )
	{
		if( !is_array( $post ) || empty( $post ) )
		{
			return false;
		}

		// Load up the access table binding.
		$access	= Foundry::table( 'Access' );

		// Try to load the access records.
		$access->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_PROFILES ) );

		// Load the registry
		$registry 	= Foundry::registry( $access->params );

		foreach( $post as $key => $value )
		{
			$key 	= str_ireplace( '_' , '.' , $key );

			$registry->set( $key , $value );
		}

		$access->uid 		= $this->id;
		$access->type 		= SOCIAL_TYPE_PROFILES;
		$access->params 	= $registry->toString();

		// Try to store the access item
		if( !$access->store() )
		{
			$this->setError( $access->getError() );

			return false;
		}

		return true;
	}


	/**
	 * Adds a user into this profile.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $profile 	= Foundry::table( 'Profile' );
	 *
	 * // Adding logged in user into an existing profile.
	 * $profile->addUser( JFactory::getUser() );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The user's id.
	 */
	public function addUser( $userId )
	{
		$member		= Foundry::table( 'ProfileMap' );

		// Try to load previous record if it exists.
		$member->loadByUser( $userId );
		$member->profile_id 	= $this->id;
		$member->user_id		= $userId;
		$member->state			= SOCIAL_STATE_PUBLISHED;

		return $member->store();
	}

	/**
	 * Gets number of members from this profile group.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  int The total number of members
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getMembersCount( $publishedOnly = true )
	{
		static $count 	= array();

		if( !isset( $count[ $this->id ] ) )
		{
			$model 	= Foundry::model( 'Profiles' );
			$count[ $this->id ]	= $model->getMembersCount( $this->id, $publishedOnly );
		}

		return $count[ $this->id ];
	}

	/**
	 * API to determine if the profile contains any members.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	boolean		True if contains members, false otherwise.
	 */
	public function hasMembers()
	{
		return $this->getMembersCount() > 0;
	}

	/**
	 * Returns the title of the profile.
	 *
	 * @access	public
	 * @param	null
	 */
	public function getTitle()
	{
		return JText::_( $this->title );
	}

	/**
	 * Retrieves the profile avatar.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAvatar( $size = SOCIAL_AVATAR_MEDIUM )
	{
		$config 	= Foundry::config();
		$avatar 	= Foundry::Table( 'Avatar' );
		$state 		= $avatar->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_PROFILES ) );

		if( !$state )
		{
			$path 	= rtrim( JURI::root() , '/' ) . $config->get( 'avatars.default.profiles.' . $size );

			return $path;
		}

		return $avatar->getSource( $size );
	}

	/**
	 * Override parent's bind method implementation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of key / value pairs for the table columns
	 * @param	Array	A list of ignored columns. (Optional)
	 * @return	bool	True if binded successfully.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function bind( $data , $ignore = array() )
	{
		// Request the parent to bind the data.
		$state 	= parent::bind( $data , $ignore );

		// Try to see if there's any params being set to the property as an array.
		if( !is_null( $this->params ) && is_array( $this->params ) )
		{
			$registry 	= Foundry::get( 'Registry' );

			foreach( $this->params as $key => $value )
			{
				$registry->set( $key , $value );
			}

			// Set the params to a proper string.
			$this->params	= $registry->toString();
		}

		return $state;
	}

	/**
	 * Allows caller to pass in an array of gid to be binded to the object property.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of group id's.
	 * @return	boolean	True on success false otherwise.
	 */
	public function bindUserGroups( $gids = array() )
	{
		$gids 	= Foundry::makeArray( $gids );

		if( is_array( $gids ) && !empty( $gids ) )
		{
			$this->gid 	= Foundry::json()->encode( $gids );

			return true;
		}

		return false;
	}

	/**
	 * Override parent's store behavior.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Update null values.
	 * @return	bool	True on success, false otherwise.
	 * @author	Mark Lee <mark@stackides.com>
	 */
	public function store( $updateNulls = false )
	{
		// If created date is not provided, we generate it automatically.
		if( is_null( $this->created ) )
		{
			$this->created 	= Foundry::date()->toMySQL();
		}

		// Check if this is a new profile type.
		$isNew			= !$this->id;

		// Update ordering column.
		$this->ordering = $this->getNextOrder();

		// Store the item now so that we can get the incremented profile id.
		$state	= parent::store( $updateNulls );

		// Create the default step and insert the core fields.
		if( $isNew )
		{
			// Create default profile steps and fields.
			$model		= Foundry::model( 'Profiles' );
			$model->createDefaultItems( $this->id );
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
	public function insertStream( $verb )
	{
		// Activity logging.
		// Announce to the world when a new profile is created so that the users can view this profile type.
		$config 			= Foundry::config();

		// If not allowed, we will not want to proceed here.
		if( !$config->get( 'profiles.stream.' . $verb ) )
		{
			return false;
		}

		// Get the stream library.
		$stream				= Foundry::stream();

		// Get stream template
		$streamTemplate		= $stream->getTemplate();

		// Set the actors.
		$streamTemplate->setActor( Foundry::user()->id , SOCIAL_TYPE_USER );

		// Set the context for the stream.
		$streamTemplate->setContext( $this->id , SOCIAL_TYPE_PROFILES );

		// Set the verb for this action as this is some sort of identifier.
		$streamTemplate->setVerb( $verb );

		$streamTemplate->setSiteWide();

		$streamTemplate->setPublicStream( 'core.view' );


		// Add the stream item.
		return $stream->add( $streamTemplate );
	}

	/*
	 * Determines whether this current profile type is associated with
	 * a custom field.
	 *
	 * @param   int $fieldId    The custom field's id.
	 * @return  boolean     True if associated, false otherwise.
	 */
	public function isChild( $fieldId )
	{
		$db 	= Foundry::db();
		$query  = 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__social_fields' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'field_id' ) . '=' . $db->Quote( $fieldId ) . ' '
				. 'AND ' . $db->nameQuote( 'profile_id' ) . '=' . $db->Quote( $this->id );

		$db->setQuery( $query );

		return $db->loadResult() > 0 ? true : false;
	}

	/**
	 * Sets this profile as the default profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function makeDefault()
	{
		$db         = Foundry::db();

		// Only 1 item can be default at a time, FIFO model
		$query 		= array();

		$query[]	= 'UPDATE ' . $db->nameQuote( $this->_tbl );
		$query[]	= 'SET ' . $db->nameQuote( 'default' ) . '=' . $db->Quote( 0 );

		$db->setQuery( $query );
		$db->Query();

		// Update the curent profile to default.
		$this->default  = true;

		$state	= $this->store();

		return $state;
	}

	/**
	 * Deletes a profile off the system. Any related profiles stuffs should also be deleted here.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   mixed	An optional primary key value to delete.  If not set the instance property value is used.
	 */
	public function delete( $pk = null )
	{
		// Try to delete this profile item.
		$state	= parent::delete( $pk );

		// Delete custom fields created for this profile type
		if( $state )
		{
			// Delete all field relations to this profile.
			$model 	= Foundry::model( 'Fields' );
			$model->deleteFields( $this->id , SOCIAL_TYPE_PROFILES );

			// Delete all stream related items.
			$stream 	= Foundry::stream();
			$stream->delete( $this->id , SOCIAL_TYPE_PROFILES );

			// Delete profile avatar.
			$avatar 	= Foundry::table( 'Avatar' );

			if( $avatar->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_PROFILES ) ) )
			{
				$avatar->delete();
			}

			// Delete default avatars for this profile.
			$avatarModel 	= Foundry::model( "Avatars" );
			$avatarModel->deleteDefaultAvatars( $this->id , SOCIAL_TYPE_PROFILES );
		}

		return $state;
	}

	/**
	 * Validates the profile before storing. Basically, check on the necessary fields that is required and see if there's any errors.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True if validates, false if there's errors.
	 */
	public function validate()
	{
		// Test if the title is provided since it is necessary.
		if( !$this->title )
		{
			$this->setError( JText::_( 'Profile title is invalid.' ) );
			return false;
		}

		// Test if the 'gid' is provided. Otherwise the user wont be in any group at all.

		return true;
	}

	public function getEmailTitle( $type = '' )
	{
		// @TODO: Make this configurable.
		$title 	= Foundry::jConfig()->getValue( 'sitename' );

		return JText::sprintf( 'COM_EASYSOCIAL_EMAILS_REGISTRATION_EMAIL_TITLE' , $title );
	}

	/**
	 * Retrieves the email title for moderator.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getModeratorEmailTitle( $type = '' )
	{
		// @TODO: Make this configurable.
		$title 	= Foundry::jConfig()->getValue( 'sitename' );

		return JText::sprintf( 'COM_EASYSOCIAL_EMAILS_REGISTRATION_MODERATOR_EMAIL_TITLE' , $title );

		return $title;
	}

	/**
	 * Retrieve the email template path.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of email.
	 *
	 */
	public function getEmailTemplate( $type = '' )
	{
		$param	= $this->getParams();
		$type	= !empty( $type ) ? $type : $param->get( 'registration' );

		$path 	= 'site/registration/' . $type;

		return $path;
	}

	/**
	 * Retrieve the email template path.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of email.
	 *
	 */
	public function getModeratorEmailTemplate( $type = '' )
	{
		$param	= $this->getParams();
		$type	= !empty( $type ) ? $type : $param->get( 'registration' );

		$path 	= 'site/registration/moderator.' . $type;

		return $path;
	}

	/**
	 * Retrieve the email contents for a particular email type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of email.
	 *
	 */
	public function getEmailContents( $type = '' )
	{
		$param	= $this->getParams();
		$type	= !empty( $type ) ? $type : $param->get( 'registration' );

		return Foundry::themes()->output( 'site/registration.' . $type );
	}

	/**
	 * Retrieve the email contents for a particular email type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of email.
	 *
	 */
	public function getModeratorEmailContents( $type = '' )
	{
		$param	= $this->getParams();
		$type	= !empty( $type ) ? $type : $param->get( 'registration' );

		return Foundry::themes()->output( 'site/registration.' . $type . '.moderator' );
	}

	public function getEmailFormat( $type = '' )
	{
		$param  	= $this->getParams();
		$type   	= !empty( $type ) ? $type : $param->get( 'registration' );
		$html		= $param->get( 'email_html_' . $type );
		$html       = (bool) !empty( $html ) ? $html : true;
		return $html;
	}

	/**
	 * Retrieves the registration type for this profile type.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Profile' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 *
	 * // Returns the registration type.
	 * $table->getRegistrationType();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Translated result if true.
	 * @return	string	The registration type in string.
	 */
	public function getRegistrationType( $translate = false )
	{
		// Load the params if it's not loaded.
		$param  = $this->getParams();
		$type 	= $param->get( 'registration' );

		$data 	= $type;

		if( $translate )
		{
			$data 	= JText::_( 'COM_EASYSOCIAL_REGISTRATIONS_TYPE_' . strtoupper( $type ) );
		}

		return $data;
	}

	/**
	 * Logics to store a profile avatar.
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function uploadAvatar( $file )
	{
		$avatar 	= Foundry::table( 'Avatar' );
		$state 		= $avatar->load( array( 'uid' => $this->id , 'type' => SOCIAL_TYPE_PROFILES ) );

		if( !$state )
		{
			$avatar->uid 	= $this->id;
			$avatar->type 	= SOCIAL_TYPE_PROFILES;

			$avatar->store();
		}

		// Determine the state of the upload.
		$state	= $avatar->upload( $file );

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'PROFILES: Unable to upload the avatar.' );

			$this->setError( JText::_( 'There was some problems uploading the avatar' ) );
			return false;
		}

		// Store the data.
		$avatar->store();

		return;
	}

	/*
	 * Override parent's store params method to allow html codes
	 */
	public function storeParams()
	{
		$raw		= JRequest::getVar( 'params' , '' , 'POST' , 'none' , JREQUEST_ALLOWHTML );

		$param      = Foundry::get( 'Parameter' , '' );
		$param->bind( $raw );

		$this->params   = $param->toJSON();

		$this->store();
	}

	/**
	 * Gets the permalink to the profiles view.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPermalink()
	{
		return FRoute::_( 'index.php?option=com_easysocial&view=profiles&id=' . $this->id );
	}

	public function isValidStep( $step, $mode = null )
	{
		$db = Foundry::db();

		$sql = $db->sql();

		$sql->select( '#__social_fields_steps' )
			->where( 'uid', $this->id )
			->where( 'type', SOCIAL_TYPE_PROFILES )
			->where( 'state', 1 )
			->where( 'sequence', $step );

		if( !empty( $mode ) )
		{
			$sql->where( 'visible_' . $mode, 1 );
		}

		$db->setQuery( $sql );

		$result = $db->loadResult();

		return !empty( $result );
	}

	public function getSequenceFromIndex( $index, $mode = null )
	{
		$steps = $this->getSteps( $mode );

		if( !isset( $steps[$index - 1] ) )
		{
			return 1;
		}

		return $steps[$index - 1]->sequence;
	}

	public function getIndexFromSequence( $sequence, $mode = null )
	{
		$steps = $this->getSteps( $mode );

		if( !empty( $steps ) && is_array( $steps ) )
		{
			$index = 1;

			foreach( $steps as $step )
			{
				if( $step->sequence == $sequence )
				{
					return $index;
				}

				$index++;
			}
		}

		return 1;
	}

	public function getCustomFields( $mode = null )
	{
		static $profileFields = array();

		if( empty( $profileFields[$this->id] ) )
		{
			$model = Foundry::model( 'Fields' );
			$fields = $model->getCustomFields( array( 'profile_id' => $this->id, 'state' => SOCIAL_STATE_PUBLISHED ) );

			$profileFields[$this->id] = $fields;
		}

		if( !empty( $mode ) )
		{
			$key = 'visible_' . $mode;
			$result = array();

			foreach( $profileFields[$this->id] as $field )
			{
				if( !empty( $field->$key ) )
				{
					$result[] = $field;
				}
			}

			return $result;
		}

		return $profileFields[$this->id];
	}

	public function isFieldExist( $key )
	{
		$fields = $this->getFields();

		foreach( $fields as $field )
		{
			if( $field->unique_key === strtoupper( $key ) )
			{
				return true;
			}
		}

		return false;
	}
}
