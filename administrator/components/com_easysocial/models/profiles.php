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

class EasySocialModelProfiles extends EasySocialModel
{
	public function __construct( $config = array() )
	{
		parent::__construct( 'profiles' , $config );
	}

	/**
	 * Initializes all the generic states from the form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	protected function initStates()
	{
		$filter 	= $this->getUserStateFromRequest( 'state' , 'all' );
		$ordering 	= $this->getUserStateFromRequest( 'ordering' , 'ordering' );
		$direction	= $this->getUserStateFromRequest( 'direction' , 'ASC' );

		$this->setState( 'state' , $filter );


		parent::initStates();

		// Override the ordering behavior
		$this->setState( 'ordering' , $ordering );
		$this->setState( 'direction' , $direction );
	}

	/**
	 * Saves the ordering of profiles
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function saveOrder( $ids , $ordering )
	{
		$table 	= Foundry::table( 'Profile' );
		$table->reorder();
	}

	/**
	 * Gets the default profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getDefaultProfile()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_profiles' );
		$sql->where( 'default' , 1 );

		$db->setQuery( $sql );

		$row 	= $db->loadObject();

		// If no default profile found then fetch the first one from the database
		if( !$row )
		{
			$sql->clear();
			$sql->select( '#__social_profiles' );
			$sql->limit( 1 );

			$db->setQuery( $sql );

			$row->$db->loadObject();
		}

		$profile	= Foundry::table( 'Profile' );
		$profile->bind( $row );

		return $profile;
	}

	/**
	 * Retrieves the total number of profiles in the system.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	int		The total number of profiles.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getTotalProfiles()
	{
		$db 	= Foundry::db();

		$query	= array();

		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__social_profiles' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );
		$count 		= (int) $db->loadResult();

		return $count;
	}

	/**
	 * Retrieves a list of custom profiles from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array	An array list of SocialTableProfile
	 *
	 */
	public function getItems( $options = array() )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_profiles' );

		// Check for search
		$search 	= $this->getState( 'search' );

		if( $search )
		{
			$sql->where( 'title' , '%' . $search . '%' , 'LIKE' );
		}

		// Check for ordering
		$ordering 	= $this->getState( 'ordering' );

		if( $ordering )
		{
			$direction	 = $this->getState( 'direction' ) ? $this->getState( 'direction' ) : 'DESC';

			$sql->order( $ordering , $direction );
		}

		// Check for state
		$state 		= $this->getState( 'state' );

		if( $state != 'all' )
		{
			$sql->where( 'state' , $state );
		}

		// Set the total records for pagination.
		$this->setTotal( $sql->getTotalSql() );

		$db->setQuery( $sql );

		$result		= $this->getData( $sql->getSql() );

		if( !$result )
		{
			return false;
		}

		$profiles	= array();
		$total      = count( $result );

		for( $i = 0; $i < $total; $i++ )
		{
			$profile       = Foundry::table( 'Profile' );
			$profile->bind( $result[ $i ] );

			$profiles[]    = $profile;
		}

		return $profiles;
	}

	/**
	 * Retrieves a list of users not in any profiles.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Profiles' );
	 * $count 	= $model->getOrphanMembersCount( false );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param   int 	The unique profile id.
	 * @param	boolean	true / false
	 * @return  int 	count of users who doesnt assigned with profile
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function getOrphanMembersCount( $publishedOnly = true )
	{
		$db 		= Foundry::db();
		$query 		= array();
		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__users' ) . ' AS a';
		$query[]	= 'WHERE NOT EXISTS ( select user_id from ' . $db->nameQuote( '#__social_profiles_maps' ) . ' AS b';
		$query[]	= 'where a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'user_id' ) . ')';

		if( $publishedOnly )
			$query[]	= 'AND a.' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );

		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		return $db->loadResult();
	}

	public function deleteOrphanItems()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$query = 'delete from `#__social_profiles_maps` where not exists ( select `id` from `#__social_profiles` where `profile_id` = `id` )';
		$sql->raw( $query );

		$db->setQuery( $sql );
		$db->query();

		return true;
	}



	/**
	 * Retrieves a list of users in this profile type.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Profiles' );
	 *
	 * //Displays 10 members from the profile.
	 * $model->getMembers( JRequest::getInt( 'id' ) , array( 'limit' => 10 ) );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param   int 	The unique profile id.
	 * @param	Array	An array of options. (randomize=>bool,limit => int)
	 * @return  Array   An array of SocialUser object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getMembers( $profileId , $options = array() )
	{
		$db 		= Foundry::db();

		// Determine if we should randomize the result.
		$randomize 	= isset( $options[ 'randomize' ] ) ? true : false;
		$limit 		= isset( $options[ 'limit' ] ) ? (int) $options[ 'limit' ] : false;

		$query		= array();
		$query[]	= 'SELECT b.' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__social_profiles_maps' ) . ' AS a';

		// Joins
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b';
		$query[]	= 'ON b.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'user_id' );
		$query[]	= 'AND b.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( 0 );

		// Where
		$query[]	= 'WHERE a.' . $db->nameQuote( 'profile_id' ) . '=' . $db->Quote( $profileId );

		// Randomize the result if necessary
		if( $randomize )
		{
			$query[]	= 'ORDER BY RAND()';
		}

		// If limit is set, we need to define the limit here.
		if( $limit )
		{
			$query[]	= 'LIMIT 0,' . $limit;
		}


		// Merge queries back.
		$query 	= implode( ' ' , $query );

		// Debug
		// echo str_ireplace( '#__' , 'jos_' , $query ) . '<br />';
		// exit;

		$db->setQuery( $query );

		// Load by column
		$result = $db->loadColumn();

		if( !$result )
		{
			return $result;
		}

		// Pre-load these users.
		$users	= Foundry::user( $result );

		// Ensure that $users is an array.
		$users	= Foundry::makeArray( $users );

		// Randomize the result if necessary.
		if( $randomize )
		{
			shuffle( $users );
		}

		return $users;
	}

	/**
	 * Removes user from existing profiles
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id.
	 * @return
	 */
	public function removeUserFromProfiles( $id )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_profiles_maps' );
		$sql->where( 'user_id' , $id );

		$db->setQuery( $sql );

		$db->Query();
	}

	/*
	 * Update the fields that are associated to certain profile type.
	 *
	 * @param   int     $profileId  The profile type id.
	 */
	public function updateFields( $profileId , $fields )
	{
		$db 	= Foundry::db();

		// First in first out.
		$query  = 'DELETE FROM ' . $db->nameQuote( '#__social_profile_types_fields' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'profile_id' ) . '=' . $db->Quote( $profileId );

		$db->setQuery( $query );
		$db->Query();

		$query  = 'INSERT INTO ' . $db->nameQuote( '#__social_profile_types_fields' ) . ' VALUES ';

		if( is_array( $fields ) )
		{
			$total  = count( $fields );
			for( $i = 0; $i < $total; $i++ )
			{
				$query  .= '(' . $db->Quote( $profileId ) . ',' . $db->Quote( $fields[ $i ] ) . ')';

				if( ( $i + 1 ) != $total )
				{
					$query  .= ',';
				}
			}
		}
		$db->setQuery( $query );
		$db->Query();
	}

	/**
	 * Updates a user profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function updateUserProfile( $uid , $profileId )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$map 		= Foundry::table( 'ProfileMap' );
		$exists		= $map->load( array( 'user_id' => $uid ) );

		if( !$exists )
		{
			$map->user_id	= $uid;
			$map->state 	= SOCIAL_STATE_PUBLISHED;
		}

		$map->profile_id	= $profileId;

		$state 		= $map->store();

		if( !$state )
		{
			$this->setError( $map->getError() );
			return $state;
		}

		$sql->delete( '#__social_fields_data' );
		$sql->where( 'uid' , $uid );
		$sql->where( 'type' , SOCIAL_TYPE_USER );

		$db->setQuery( $sql );
		return $db->Query();
	}

	/**
	 * Retrieves a list of profile types throughout the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options. (state - Determine the state of the profiles, ordering - The ordering type)
	 * @return
	 */
	public function getProfiles( $config = array() )
	{
		$db 		= Foundry::db();
		$query 		= array();

		// Ensure that the user's are published
		$validUsers 	= isset( $config[ 'validUser' ] ) ? $config[ 'validUser' ] : null;

		$query[]	= 'SELECT a.* , COUNT(b.' . $db->nameQuote( 'id' ) . ') AS ' . $db->nameQuote( 'count' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_profiles' ) . ' AS a';
		$query[]	= 'LEFT JOIN ' . $db->nameQuote( '#__social_profiles_maps' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'profile_id' );

		if( $validUsers )
		{
			$query[]	= 'LEFT JOIN ' . $db->nameQuote( '#__users' ) . ' AS c';
			$query[]	= 'ON c.' . $db->nameQuote( 'id' ) . '= b.' . $db->nameQuote( 'user_id' );
			$query[]	= 'AND c.' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );
		}

		$query[]	= 'WHERE 1';

		// Need to filter by state.
		if( isset( $config[ 'state' ] ) )
		{
			$state 	= (int) $config[ 'state' ];

			$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( $state );
		}


		// Need to filter by registration flag.
		if( isset( $config[ 'registration' ] ) )
		{
			$registration 	= (int) $config[ 'registration' ];

			$query[]	= 'AND a.' . $db->nameQuote( 'registration' ) . '=' . $db->Quote( $registration );
		}


		// Group results up since we joined with profile maps
		$query[]	= 'GROUP BY a.' . $db->nameQuote( 'id' );

		// Specify the ordering.
		if( isset( $config[ 'ordering' ] ) )
		{
			$ordering	= $config[ 'ordering' ];
			$query[]	= 'ORDER BY a.' . $db->nameQuote( $ordering ) . ' ASC';
		}
		else
		{
			$query[]	= 'ORDER BY a.' . $db->nameQuote( 'ordering' ) . ' ASC';
		}


		// Glue the query up.
		$query 		= implode( ' ' , $query );

		// Debug
		// echo str_ireplace( '#__' , 'jos_' , $query );
		// exit;

		// Determine wheter or not to use pagination
		$paginate 	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : SOCIAL_PAGINATION_ENABLE;
		$paginate	= $paginate == SOCIAL_PAGINATION_NO_LIMIT ? false : SOCIAL_PAGINATION_ENABLE;

		$result		= $this->getData( $query , $paginate );
		$profiles   = array();

		foreach( $result as $row )
		{
			$profile    = Foundry::table( 'Profile' );
			$profile->bind( $row );

			// Assign temporary data.
			$profile->totalUsers 	= $row->count;

			// Set the profile object back.
			$profiles[]	= $profile;
		}

		return $profiles;
	}

	/**
	 * Retrieve the total number of users in this profile type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	int		The total.
	 */
	public function getMembersCount( $profileId, $publishedOnly = true )
	{
		$db 		= Foundry::db();
		$query 		= array();
		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__social_profiles_maps' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b';
		$query[]	= 'ON b.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'user_id' );
		if( $publishedOnly )
			$query[]	= 'AND b.' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );
		$query[]	= 'WHERE a.' . $db->nameQuote( 'profile_id' ) . '=' . $db->Quote( $profileId );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		return $db->loadResult();
	}

	/*
	 * Retreive custom field groups based on a specific step.
	 *
	 * @param   int     $stepId     The step id.
	 */
	public function getFieldsGroups( $stepId , $type = 'profiletype' )
	{
		$db		= Foundry::db();

		$query  = 'SELECT a.* '
				. 'FROM ' . $db->nameQuote( '#__social_fields_groups' ) . ' AS a '
				. 'WHERE a.' . $db->nameQuote( 'steps_id' ) . ' = ' . $db->Quote( $stepId ) . ' '
				. 'AND a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		$db->setQuery( $query );

		$result		= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$groups = array();

		foreach( $result as $row )
		{
			$group  = Foundry::table( 'FieldGroup' );
			$group->bind( $row );
			$groups[]   = $group;
		}
		return $groups;
	}

	public function getFields( &$groups , $filters = array() )
	{
		$db     = Foundry::db();

		foreach( $groups as $group )
		{
			$query  = 'SELECT a.*,b.title AS addon_title , b.element AS addon_element FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
					. 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b '
					. 'ON b.id=a.field_id '
					. 'WHERE a.`group_id`=' . $db->Quote( $group->id );

			if( $filters )
			{
				$subquery     = array();

				foreach( $filters as $key => $value )
				{
					$subquery[]		= 'a.' . $db->nameQuote( $key ) . '=' . $db->Quote( $value );
				}

				$query .= ' ' . count( $subquery ) == 1 ? ' AND ' . $subquery[ 0 ] : implode( ' AND ' , $subquery );
			}
			$db->setQuery( $query );

			$fields	= $db->loadObjectList();
			$group->childs  = array();

			foreach( $fields as $field )
			{
				$table      = Foundry::table( 'Field' );
				$table->bind( $field );
				$table->addon_title = $field->addon_title;

				$group->childs[]    = $table;
			}
		}

		return $groups;
	}

	/**
	 * Responsible to create the default custom fields for a profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createDefaultItems( $profileId )
	{
		// Read the default profile json file first.
		$path 		= SOCIAL_ADMIN_DEFAULTS . '/profile.json';
		$defaults 	= Foundry::makeObject( $path );

		// If there's a problem decoding the file, log some errors here.
		if( !$defaults )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'PROFILES: Unable to read default profile.json file' );
		}

		// Init sequence
		$sequence = 1;

		// Init uniquekeys
		$uniqueKeys = array();

		// Let's go through each of the default items.
		foreach( $defaults as $step )
		{
			// Create default step for this profile.
			$stepTable				= Foundry::table( 'FieldStep' );
			$stepTable->bind( $step );

			// Set the sequence
			$stepTable->sequence	= $sequence++;

			// Map the correct uid and type.
			$stepTable->uid			= $profileId;
			$stepTable->type		= SOCIAL_TYPE_PROFILES;

			// Set the state
			$stepTable->state		= isset( $step->state ) ? $step->state : SOCIAL_STATE_PUBLISHED;

			// Set this to show in registration by default
			$stepTable->visible_registration	= isset( $step->visible_registration ) ? $step->visible_registration : SOCIAL_STATE_PUBLISHED;

			// Set this to show in edit by default
			$stepTable->visible_edit			= isset( $step->visible_edit ) ? $step->visible_edit : SOCIAL_STATE_PUBLISHED;

			// Set this to show in display by default
			$stepTable->visible_display			= isset( $step->visible_display ) ? $step->visible_display : SOCIAL_STATE_PUBLISHED;

			// Try to store the default steps.
			$state	= $stepTable->store();

			// Now we need to create all the fields that are in the current step
			if( $step->fields && $state )
			{
				// Init ordering
				$ordering = 0;

				foreach( $step->fields as $field )
				{
					$appTable 		= Foundry::table( 'App' );
					$state 			= $appTable->load( array( 'element' => $field->element , 'group' => SOCIAL_TYPE_USER , 'type' => SOCIAL_APPS_TYPE_FIELDS ) );

					// If the app doesn't exist, we shouldn't add it.
					if( $state && $appTable->state != SOCIAL_APP_STATE_DISCOVERED )
					{
						$fieldTable		= Foundry::table( 'Field' );
						$fieldTable->bind( $field );

						// Set the ordering
						$fieldTable->ordering				= $ordering++;

						// Ensure that the main items are being JText correctly.
						$fieldTable->title					= $field->title;
						$fieldTable->description			= $field->description;
						$fieldTable->default				= isset( $field->default ) ? $field->default : '';

						// Set the app id.
						$fieldTable->app_id					= $appTable->id;

						// Set the step.
						$fieldTable->step_id				= $stepTable->id;

						// Set this to show title by default
						$fieldTable->display_title			= isset( $field->display_title ) ? $field->display_title : SOCIAL_STATE_PUBLISHED;

						// Set this to show description by default
						$fieldTable->display_description	= isset( $field->display_description ) ? $field->display_description : SOCIAL_STATE_PUBLISHED;

						// Set this to be published by default.
						$fieldTable->state					= isset( $field->state ) ? $field->state : SOCIAL_STATE_PUBLISHED;

						// Set this to be searchable by default.
						$fieldTable->searchable				= isset( $field->searchable ) ? $field->searchable : SOCIAL_STATE_PUBLISHED;

						// Set this to be required by default.
						$fieldTable->required				= isset( $field->required ) ? $field->required : SOCIAL_STATE_PUBLISHED;

						// Set this to show in registration by default
						$fieldTable->visible_registration	= isset( $field->visible_registration ) ? $field->visible_registration : SOCIAL_STATE_PUBLISHED;

						// Set this to show in edit by default
						$fieldTable->visible_edit	= isset( $field->visible_edit ) ? $field->visible_edit : SOCIAL_STATE_PUBLISHED;

						// Set this to show in display by default
						$fieldTable->visible_display	= isset( $field->visible_display ) ? $field->visible_display : SOCIAL_STATE_PUBLISHED;

						// Check if the default items has a params.
						if( isset( $field->params ) )
						{
							$fieldTable->params 	= Foundry::json()->encode( $field->params );
						}

						// Store the field item.
						$fieldTable->store();

						// Generate unique key for this field after store (this is so that we have the field id)
						$keys = !empty( $uniqueKeys[ $stepTable->id ][ $fieldTable->id ] ) ? $uniqueKeys[ $stepTable->id ][ $fieldTable->id ] : null;
						$fieldTable->generateUniqueKey( $keys );

						// Store the unique key into list of unique keys to prevent querying for keys unnecessarily
						$uniqueKeys[ $stepTable->id ][ $fieldTable->id ][] = $fieldTable->unique_key;

						// We store again to save the unique key
						$fieldTable->store();
					}
				}
			}
		}
	}

	/**
	 * Creates the necessary core fields required in order for the system to work.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique profile id.
	 * @return	bool	True if success and false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function createDefaultFields( $stepId )
	{
		// Load apps model
		$model 		= Foundry::model( 'Apps' );

		// Get a list of core and default apps
		$apps 		= $model->getDefaultApps( array( 'type' => SOCIAL_APPS_TYPE_FIELDS ) );

		// Get default data from the manifest files.
		$lib 		= Foundry::fields();
		$fields 	= $lib->getCoreManifest( SOCIAL_FIELDS_GROUP_USER , $apps );

		// Only get fields that doesn't exist for the profile type.
		if( !$fields )
		{
			return false;
		}

		foreach( $fields as $row )
		{
			$field		= Foundry::table( 'Field' );

			// Set the current profile's id.
			$field->bind( $row );

			// If there is a params set in the defaults.json, we need to decode it back to a string.
			if( $row->params && is_object( $row->params ) )
			{
				$field->params 	= Foundry::json()->encode( $row->params );
			}

			// Set the core identifier
			$field->core 	= SOCIAL_STATE_PUBLISHED;

			// Set the step id this field belongs to.
			$field->step_id = $stepId;

			// Let's try to store the custom field now.
			$field->store();
		}

		return true;
	}

	/**
	 * Retrieves a list of core fields from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The profile id
	 * @return	Array	An array of SocialTableField
	 */
	public function getCoreFields( $profileId )
	{
		$db     = Foundry::db();

		$query  = 'SELECT a.*, b.title AS addon_title '
				. 'FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b '
				. 'ON a.' . $db->nameQuote( 'app_id' ) . ' = b.' . $db->nameQuote( 'id' ) . ' '
				. 'WHERE b.' . $db->nameQuote( 'core' ) . ' = ' . $db->Quote( 1 );

		// @rule: We already know before hand which elements are the core fields for the profile types.
		$elements   = array( $db->Quote( 'joomla_username' ) , $db->Quote( 'joomla_fullname' ) , $db->Quote( 'joomla_email' ) ,
							$db->Quote( 'joomla_password' ), $db->Quote( 'joomla_timezone' ) , $db->Quote('joomla_user_editor' ) , $db->Quote( 'joomla_password2' ) );

		$query  .= ' AND b.' . $db->nameQuote( 'element' ) . ' IN(' . implode( ',' , $elements ) . ')';

		$db->setQuery( $query );

		$result		= $db->loadObjectList();
		$fields     = array();

		foreach( $result as $row )
		{
			$field      = Foundry::table( 'Field' );
			$field->bind( $row );
			$field->set( 'addon_title' , $row->addon_title );

			// Manually push in profile_id
			$field->profile_id = $profileId;
			$fields[]   = $field;
		}
		return $fields;
	}


	/**
	 * Retrieves the past 7 days statistics for new sign ups.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Array
	 */
	public function getRegistrationStats()
	{
		$db			= Foundry::db();
		$dates 		= array();

		// Get the past 7 days
		$curDate 	= Foundry::date();
		for( $i = 0 ; $i < 7; $i++ )
		{
			$obj = new stdClass();

			if( $i == 0 )
			{
				$dates[]		= $curDate->toMySQL();
			}
			else
			{
				$unixdate 		= $curDate->toUnix();
				$new_unixdate 	= $unixdate - ( $i * 86400);
				$newdate  		= Foundry::date( $new_unixdate );

				$dates[] 	= $newdate->toMySQL();
			}
		}

		// Reverse the dates
		$dates 			= array_reverse( $dates );

		$result 		= new stdClass();
		$result->dates	= $dates;

		$profiles 	= array();


		foreach( $dates as $date )
		{
			// Registration date should be Y, n, j
			$date	= Foundry::date( $date )->format( 'Y-m-d' );

			$query = 'select a.' . $db->nameQuote( 'id' ) . ', a.' . $db->nameQuote( 'title' ) . ', count( b.' . $db->nameQuote( 'id' ) . ' ) as cnt';
			$query .= ' from ' . $db->nameQuote( '#__social_profiles' ) . ' as a';
			$query .= '	left join ' . $db->nameQuote( '#__social_profiles_maps' ) . ' as b';
			$query .= '		on a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'profile_id' );
			$query .= '		and date_format( b.' . $db->nameQuote( 'created' ) . ', GET_FORMAT( DATE,' . $db->Quote( 'ISO' ) . ') ) = ' . $db->Quote( $date );
			$query .= ' group by a.' . $db->nameQuote( 'id' );

			$db->setQuery( $query );

			$items				= $db->loadObjectList();

			foreach( $items as $item )
			{
				if( !isset( $profiles[ $item->id ] ) )
				{
					$profiles[ $item->id ]	= new stdClass();
					$profiles[ $item->id ]->title 	= $item->title;

					$profiles[ $item->id ]->items 	= array();
				}

				if( $item->cnt )
				{
					// $item->cnt	+= 10;
				}
				$profiles[ $item->id ]->items[]	= $item->cnt;
			}
		}

		// Reset the index.
		$profiles 	= array_values( $profiles );

		$result->profiles 	= $profiles;

		return $result;
	}

}
