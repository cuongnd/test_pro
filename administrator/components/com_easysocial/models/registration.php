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

Foundry::import( 'admin:/includes/model' );

/**
 * Model for registrations.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class EasySocialModelRegistration extends EasySocialModel
{
	/**
	 * Class construct happens here.
	 *
	 * @since	1.0
	 * @access	public
	 */
	function __construct()
	{
		parent::__construct( 'registration' );
	}

	/**
	 * Rejects a user from the whole registration process
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	User's id.
	 * @return	bool	True if success, false otherwise.
	 */
	public function reject( $id )
	{
		// Load user's object.
		$user 	= Foundry::user( $id );

		// Try to delete the user.
		$user->delete();

		// @rule: Delete node from profile maps
		$member	= Foundry::table( 'ProfileMap' );
		$member->loadByUser( $user->id );
		$member->delete();

		return $state;
	}

	/*
	 * Retrieve a list of related field id's.
	 *
	 * @param	int		$fieldId	The field id.
	 * @return	Array	An array of field id's.
	 */
	public function getRelatedFieldIds( $uid , $match , $fieldId )
	{
		$db		= Foundry::db();

		$query	= 'SELECT c.' . $db->nameQuote( 'field_id' ) . ' '
				. 'FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__social_fields_groups' ) . ' AS b '
				. 'ON a.' . $db->nameQuote( 'group_id' ) . ' = b.' . $db->nameQuote( 'id' ) . ' '
				. 'INNER JOIN ' . $db->nameQuote( '#__social_fields_rules' ) . ' AS c '
				. 'ON a.' . $db->nameQuote( 'id' ) . ' = c.' . $db->nameQuote( 'parent_id' ) . ' '
				. 'WHERE a.' . $db->nameQuote( 'id' ) . ' = ' . $db->Quote( $fieldId ) . ' '
				. 'AND b.' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $uid ) . ' '
				. 'AND a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_STATE_PUBLISHED ) . ' '
				. 'AND c.' . $db->nameQuote( 'match_text' ) . ' = ' . $db->Quote( $match );
		$db->setQuery( $query );
		$ids	= $db->loadColumn();

		return $ids;
	}

	/**
	 * Retrives a list of custom field groups given the work flow id.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Registration' );
	 * $model->getFieldGroups( $workflowId );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param   int     The unique workflow id.
	 * @return	Array	An array of SocialTableFieldGroup table.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getFieldGroups( $workflowId )
	{
		$db		= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT a.* FROM ' . $db->nameQuote( '#__social_fields_groups' ) . ' AS a';
		$query[]	= 'WHERE a.' . $db->nameQuote( 'workflow_id' ) . '=' . $db->Quote( $workflowId );
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$result		= $db->loadObjectList();

		// If there's nothing, just return false.
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

	/*
	 * Retrieves a specific custom id
	 *
	 */
	public function getCustomField( $fieldIds , $post = array() )
	{
		$db     	= Foundry::db();

		$query  = 'SELECT a.*,b.element AS element,c.field_id as smartfield FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b '
				. 'ON b.id=a.field_id '
				. 'LEFT JOIN ' . $db->nameQuote( '#__social_fields_rules' ) . ' AS c '
				. 'ON c.' . $db->nameQuote( 'field_id' ) . ' = a.' . $db->nameQuote( 'id' ) . ' '
				. 'WHERE a.`id` IN(';

		for( $i = 0; $i < count( $fieldIds ); $i++ )
		{
			$query	.= $db->Quote( $fieldIds[ $i ] );
		}

		$query  .= ') '
				. 'AND a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		$db->setQuery( $query );
		$data	= $db->loadObjectList();

		$fields = array();

		if( !$data )
		{
			return $data;
		}

		// Bind the fields to SocialTableField
		for( $x = 0; $x < count( $data ); $x++ )
		{
			$field      =& $data[ $x ];
			$item       = Foundry::table( 'Field' );
			$item->bind( $field );
			$fields[]   = $item;
		}

		// Destroy unused variables
		unset( $data );

		$fields		= Foundry::get( 'Fields' )->onRegister( $fields , $post );
		return $fields;
	}

	/**
	 * Retrieves a list of fields which should be displayed during the registration process.
	 * This should not be called elsewhere apart from the registration since it uses different steps, for processes.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	Existing values that are previously posted from $_POST.
	 * @return	Mixed	An array of group and field items as it's child items.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCustomFieldsForProfiles( $profileId )
	{
		$db     	= Foundry::db();
		$fields 	= array();

		$query 		= array();
		$query[]	= 'SELECT b.*, c.' . $db->nameQuote( 'element' ) . ' AS element,d.' . $db->nameQuote( 'field_id' ) . ' as smartfield';

		$query[]	= 'FROM ' . $db->nameQuote( '#__social_fields_steps' ) . ' AS a';

		// Only want fields from the steps associated to the profile.
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_fields' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'step_id' );

		// Join with apps table to obtain the element
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS c';
		$query[]	= 'ON c.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'app_id' );

		// Join with rules table.
		$query[]	= 'LEFT JOIN ' . $db->nameQuote( '#__social_fields_rules' ) . ' AS d';
		$query[]	= 'ON d.' . $db->nameQuote( 'parent_id' ) . ' = b.' . $db->nameQuote( 'id' );

		// Core fields should not be dependent on the state because it can never be unpublished.
		$query[]	= 'WHERE(';
		$query[]	= 'b.' . $db->nameQuote( 'core' ) . '=' . $db->Quote( 1 );
		$query[]	= 'OR';
		$query[]	= 'b.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );
		$query[]	= ')';

		// Registration field should not select dependant fields by default unless it is selected.
		$query[]	= 'AND b.' . $db->nameQuote( 'id' ) . ' NOT IN (';
		$query[]	= 'SELECT ' . $db->nameQuote( 'field_id' ) . ' FROM ' . $db->nameQuote( '#__social_fields_rules' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'field_id' ) . ' = b.' . $db->nameQuote( 'id' );
		$query[]	= ')';

		// Make sure that the field is set to be visible during registrations.
		$query[]	= 'AND b.' . $db->nameQuote( 'visible_registration' ) . '=' . $db->Quote( 1 );
		// $query[]	= 'AND b.' . $db->nameQuote( 'core' ) . '=' . $db->Quote( 1 );

		// Make sure that only visible_registration is enabled only.


		// Make sure to load fields that are in the current step only.
		$query[]	= 'AND a.' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $profileId );
		$query[]	= 'AND a.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( SOCIAL_TYPE_PROFILES );

		// Join back the queries.
		$query 		= implode( ' ' , $query );

		// echo str_ireplace( '#__' , 'jos_' , $query );
		// exit;

		$db->setQuery( $query );

		$rows	= $db->loadObjectList();

		// If there's no fields at all, just skip this whole block.
		if( !$rows )
		{
			return false;
		}

		$fields 	= array();

		// We need to bind the fields with SocialTableField
		foreach( $rows as $row )
		{
			$field 	= Foundry::table( 'Field' );
			$field->bind( $row );

			// Manually push profile_id into the field
			$field->profile_id = $profileId;

			$fields[]	= $field;
		}

		return $fields;
	}


	/**
	 * Retrieves a list of core custom fields.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Registration' );
	 * $model->getCoreFields( JRequest::getInt( 'step_id' ) );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The step id.
	 * @param	array 	Some additional data.
	 * @return
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCoreFields( $stepId , $post = array() )
	{
		$db     	= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT a.*, b.' . $db->nameQuote( 'element' ) . ' AS ' . $db->nameQuote( 'element' ) . ', c.uid AS ' . $db->nameQuote( 'profile_id' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b';
		$query[]	= 'ON b.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'app_id' );
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_fields_steps' ) . ' AS c';
		$query[]	= 'ON c.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'step_id' );
		$query[]	= 'WHERE b.' . $db->nameQuote( 'core' ) . '=' . $db->Quote( 1 );


		// @rule: We already know before hand which elements are the core fields for the profile types.
		$elements   = array( $db->Quote( 'joomla_username' ) , $db->Quote( 'joomla_fullname' ) , $db->Quote( 'joomla_email' ) ,
							$db->Quote( 'joomla_password' ), $db->Quote( 'joomla_timezone' ) , $db->Quote('joomla_user_editor' ) );

		$query[]	= 'AND b.' . $db->nameQuote( 'element' ) . ' IN(' . implode( ',' , $elements ) . ')';

		// Only select from specific steps.
		$query[]	= 'AND a.' . $db->nameQuote( 'step_id' ) . '=' . $db->Quote( $stepId );

		// The fields should be ordered correctly.
		$query[]	= 'ORDER BY a.' . $db->nameQuote( 'ordering' ) . ' ASC';

		// Let's merge the queries.
		$query 		= implode( ' ' , $query );

		// @TODO: There should be some checking here to check for fields that are not added into any steps.

		$db->setQuery( $query );

		$result		= $db->loadObjectList();

		// If all the core fields have already been mapped, just ignore this.
		if( !$result )
		{
			return $result;
		}

		$fields     = array();

		foreach( $result as $row )
		{
			$field      = Foundry::table( 'Field' );
			$field->bind( $row );

			// Manually push in profile id
			$field->profileId = $row->profile_id;

			$fields[]   = $field;
		}

		return $fields;
	}

	/**
	 * Allows purging of expired registration data.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Registration' );
	 *
	 * // Returns boolean value.
	 * $model->purgeExpired();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	True or false state.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function purgeExpired()
	{
		$db 	= Foundry::db();

		$date 		= Foundry::get( 'Date' );

		$query[]	= 'DELETE FROM ' . $db->nameQuote( '#__social_registrations' );

		// @TODO: Configurable interval period
		$query[]	= 'WHERE ' . $db->nameQuote( 'created' ) . ' <= DATE_SUB( ' . $db->Quote( $date->toMySQL() ) . ' , INTERVAL 12 HOUR)';

		$db->setQuery( implode( ' ' , $query ) );
		$state 		= $db->Query();

		return $state;
	}

	/**
	 * Links a user account with an oauth client.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function linkOAuthUser( SocialOAuth $client , SocialUser $user , $pull = true , $push = true )
	{
		$accessToken	 = $client->getAccess();

		$oauth 				= Foundry::table( 'OAuth' );
		$oauth->uid 		= $user->id;
		$oauth->type 		= SOCIAL_TYPE_USER;
		$oauth->client		= $client->getType();
		$oauth->oauth_id	= $client->getUser();
		$oauth->token 		= $accessToken->token;
		$oauth->secret 		= $accessToken->secret;
		$oauth->expires 	= $accessToken->expires;
		$oauth->pull 		= $pull;
		$oauth->push 		= $push;

		// Store the user's meta here.
		$params 	= Foundry::registry();
		$params->bind( $client->getUserMeta() );

		// Store the permissions
		$oauth->permissions	= Foundry::makeJSON( $client->getPermissions() );

		// Set the params
		$oauth->params 		= $params->toString();

		// Store oauth record
		$state 	= $oauth->store();

		if( !$state )
		{
			$this->setError( $oauth->getError() );

			return false;
		}

		// @TODO: Send email notification to admin that a user linked their social account with an existing account

		// @TODO: Send email notification to the account owner that they have successfully associated their social account.

		return $state;
	}

	/**
	 * Creates a user in the system for users who logged in via oauth
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Registration' );
	 * $model->createUser( $registrationTable );
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableRegistration		The registration object.
	 * @return	int		The last sequence for the profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function createOAuthUser( $accessToken , $data , $client , $import = true , $sync = true )
	{
		$config 	= Foundry::config();

		// Registrations needs to be enabled.
		if( !$config->get( 'registrations.enabled' ) )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_REGISTRATIONS_DISABLED' ) );
			return false;
		}

		// Load profile type.
		$profile 		= Foundry::table( 'Profile' );
		$profile->load( $data[ 'profileId' ] );

		// Get all published fields apps.
		$fieldsModel 	= Foundry::model( 'Fields' );
		$fields 		= $fieldsModel->getCustomFields( array( 'profile_id' => $profile->id, 'state' => SOCIAL_STATE_PUBLISHED ) );
		$args       		= array( &$data , &$client );

		// Perform field validations here. Validation should only trigger apps that are loaded on the form
		// @trigger onRegisterAfterSave
		$lib 		= Foundry::getInstance( 'Fields' );
		$errors 	= $lib->trigger( 'onRegisterOAuthBeforeSave' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// Get a list of user groups this profile is assigned to
		$json 		= Foundry::json();
		$groups 	= $json->decode( $profile->gid );

		// Need to bind the groups under the `gid` column from Joomla.
		$data[ 'gid' ]  = $groups;

		// Bind the posted data for the user.
		$user 	= Foundry::user();
		$user->bind( $data , SOCIAL_POSTED_DATA );

		// Detect the profile type's registration type.
		$type 	= $profile->getRegistrationType();

		// We need to generate an activation code for the user.
		if( $type == 'verify' )
		{
			$user->activation 	= Foundry::getHash( JUserHelper::genRandomPassword() );
		}

		// If the registration type requires approval or requires verification, the user account need to be blocked first.
		if( $type == 'approvals' || $type == 'verify')
		{
			$user->block 	= 1;
		}

		// Get registration type and set the user's state accordingly.
		$user->set( 'state' , constant( 'SOCIAL_REGISTER_' . strtoupper( $type ) ) );

		// Set the account type.
		$user->set( 'type'	, $client->getType() );

		// Let's try to save the user now.
		$state 		= $user->save();

		// If there's a problem saving the user object, set error message.
		if( !$state )
		{
			$this->setError( $user->getError() );
			return false;
		}

		// Set the user with proper `profile_id`
		$user->profile_id 	= $profile->id;

		// Once the user is saved successfully, add them into the profile mapping.
		$profile->addUser( $user->id );

		// Store user's custom fields information now that we got their proper id.
		$postData	= array( $data , Foundry::user() );

		// Assign user object back into the data.
		$data[ 'user' ]   = $user;

		// Bind custom fields for this user.
		if( $import )
		{
			$user->bindCustomFields( $data );
		}

		// Allow field applications to manipulate custom fields data
		$args       = array( &$data , &$oauthClient , &$user );

		// Allow fields app to make necessary changes if necessary. At this point, we wouldn't want to allow
		// the field to stop the registration process already.
		// @trigger onRegisterAfterSave
		$lib->trigger( 'onRegisterOAuthAfterSave' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// Create a new oauth record on the `#__social_oauth` table so we can simulate the user.
		$oauth 				= Foundry::table( 'OAuth' );
		$oauth->uid 		= $user->id;
		$oauth->type 		= SOCIAL_TYPE_USER;
		$oauth->client		= $client->getType();
		$oauth->oauth_id	= $data[ 'oauth_id' ];
		$oauth->token 		= $accessToken->token;
		$oauth->secret 		= $accessToken->secret;
		$oauth->expires 	= $accessToken->expires;
		$oauth->pull 		= $sync;
		$oauth->push 		= $sync;

		// Store oauth record
		$oauth->store();

		// @TODO: Send notification email to admin

		// @OTOD: Send registration confirmation email to user.

		return $user;
	}

	/**
	 * Generates a username until it no longer exists on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function generateUsername( $username , $min = 1 , $max = 500 )
	{
		$postfix	= rand( $min , $max );
		$original 	= $username;

		while( $this->isUsernameExists( $username ) )
		{
			$username 	= $original . '_' . $postfix;
		}

		return $username;
	}

	/**
	 * Determines if a username exists on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isUsernameExists( $username )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__users' );
		$sql->column( 'COUNT(1)' );
		$sql->where( 'username' , $username );

		$db->setQuery( $sql );

		$exists 	= $db->loadResult() > 0;

		return $exists;
	}

	/**
	 * Determines if an email exists on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isEmailExists( $email )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__users' );
		$sql->column( 'COUNT(1)' );
		$sql->where( 'email' , $email );

		$db->setQuery( $sql );

		$exists 	= $db->loadResult() > 0;

		return $exists;
	}

	/**
	 * Creates a user in the system given it's registration data.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Registration' );
	 * $model->createUser( $registrationTable );
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableRegistration		The registration object.
	 * @return	int		The last sequence for the profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function createUser( SocialTableRegistration &$registration )
	{
		$config 	= Foundry::config();

		// Registrations needs to be enabled.
		if( !$config->get( 'registrations.enabled' ) )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_REGISTRATIONS_DISABLED' ) );
			return false;
		}

		// Create a user object first
		$user 	= Foundry::user();

		// Load up the values which the user inputs
		$param 	= Foundry::get( 'Registry' );

		// Bind the JSON values.
		$param->bind( $registration->values );

		// Convert the data into an array of result.
		$data       = $param->toArray();

		// Get all published fields apps.
		$fields 	= $this->getCustomFieldsForProfiles( $registration->profile_id );

		// Pass in data and new user object by reference for fields to manipulate
		$args       = array( &$data, &$user );

		// Perform field validations here. Validation should only trigger apps that are loaded on the form
		// @trigger onRegisterBeforeSave
		$lib 		= Foundry::getInstance( 'Fields' );
		$errors 	= $lib->trigger( 'onRegisterBeforeSave' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// We need to know the password of the user because they might need to login after registrations.
		$data[ 'password_clear' ]	= $data[ 'password' ];

		// If there are any errors, throw them on screen.
		if( is_array( $errors) )
		{
			if( in_array( false , $errors , true ) )
			{
				$this->setError( $errors );
				return false;
			}
		}

		// Load profile type.
		$profile        = Foundry::table( 'Profile' );
		$profile->load( $registration->profile_id );

		// Get a list of user groups this profile is assigned to
		$json 		= Foundry::json();
		$groups 	= $json->decode( $profile->gid );

		// Need to bind the groups under the `gid` column from Joomla.
		$data[ 'gid' ]  = $groups;

		// Bind the posted data for the user.
		$user->bind( $data , SOCIAL_POSTED_DATA );

		// Detect the profile type's registration type.
		$type 	= $profile->getRegistrationType();

		// We need to generate an activation code for the user.
		if( $type == 'verify' )
		{
			$user->activation 	= Foundry::getHash( JUserHelper::genRandomPassword() );
		}

		// If the registration type requires approval or requires verification, the user account need to be blocked first.
		if( $type == 'approvals' || $type == 'verify')
		{
			$user->block 	= 1;
		}

		// Get registration type and set the user's state accordingly.
		$user->set( 'state' , constant( 'SOCIAL_REGISTER_' . strtoupper( $type ) ) );

		// Let's try to save the user now.
		$state 		= $user->save();

		// If there's a problem saving the user object, set error message.
		if( !$state )
		{
			$this->setError( $user->getError() );
			return false;
		}

		// Set the user with proper `profile_id`
		$user->profile_id 	= $profile->id;

		// Once the user is saved successfully, add them into the profile mapping.
		$profile->addUser( $user->id );

		// Allow field applications to manipulate custom fields data
		$args	= array( &$data, &$user );

		// Allow fields app to make necessary changes if necessary. At this point, we wouldn't want to allow
		// the field to stop the registration process already.
		// @trigger onRegisterAfterSave
		$lib->trigger( 'onRegisterAfterSave' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// Bind custom fields for this user.
		$user->bindCustomFields( $data );

		// Reform the args with the binded custom field data in the user object
		$args = array( &$data, &$user );

		// @trigger onRegisterAfterSaveFields
		$lib->trigger( 'onRegisterAfterSaveFields' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// We need to set the "data" back to the registration table
		$newData 	= Foundry::json()->encode( $data );
		$registration->values 	= $newData;

		return $user;
	}

	/**
	 * Notify users and administrator when they create an account on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser			The user object.
	 * @param	SocialTableProfile	The profile type.
	 * @return	bool				True if success, false otherwise.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function notifyAdmins( $data , SocialUser $user , SocialTableProfile $profile )
	{
		// Get the application data.
		$jConfig 	= Foundry::jConfig();

		// Generate a key for the admin's actions.
		$key 		= md5( $user->password . $user->email . $user->name . $user->username );

		// Push arguments to template variables so users can use these arguments
		$params 	= array(
								'site'			=> $jConfig->getValue( 'sitename' ),
								'username'		=> $data[ 'username' ],
								'password'		=> $data[ 'password' ],
								'firstName'		=> !empty( $data[ 'first_name' ] ) ? $data[ 'first_name' ] : '',
								'middleName'	=> !empty( $data[ 'middle_name' ] ) ? $data[ 'middle_name' ] : '',
								'lastName'		=> !empty( $data[ 'last_name' ] ) ? $data[ 'last_name' ] : '',
								'name'			=> $user->getName(),
								'avatar'		=> $user->getAvatar( SOCIAL_AVATAR_LARGE ),
								'profileLink'	=> $user->getPermalink(),
								'email'			=> $user->email,
								'activation'	=> FRoute::controller( 'registration' , array( 'external' => true , 'task' => 'activate' , 'activation' => $user->activation ) ),
								'reject'		=> FRoute::controller( 'registration' , array( 'external' => true , 'task' => 'rejectUser' , 'id' => $user->id , 'key' => $key ) ),
								'approve'		=> FRoute::controller( 'registration' , array( 'external' => true , 'task' => 'approveUser' , 'id' => $user->id , 'key' => $key ) ),
								'profileType'	=> $profile->get( 'title' )
						);


		// Get the email title.
		$title      = $profile->getModeratorEmailTitle();

		// Get the email format.
		$format 	= $profile->getEmailFormat();

		// Get a list of super admins on the site.
		$usersModel = Foundry::model( 'Users' );

		$admins 	= $usersModel->getSiteAdmins();

		foreach( $admins as $admin )
		{
			// Immediately send out emails
			$mailer 	= Foundry::mailer();

			// Set the admin's name.
			$params[ 'adminName' ]	= $admin->getName();

			// Get the email template.
			$mailTemplate	= $mailer->getTemplate();

			if( !$admin->isSiteAdmin() )
			{
				continue;
			}

			// Set recipient
			$mailTemplate->setRecipient( $admin->getName() , $admin->email );

			// Set title
			$mailTemplate->setTitle( $title );

			// Set the template
			$mailTemplate->setTemplate( $profile->getModeratorEmailTemplate() , $params , $format );

			// Set the priority. We need it to be sent out immediately since this is user registrations.
			$mailTemplate->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

			// Try to send out email to the admin now.
			$state 		= $mailer->create( $mailTemplate );
		}

		return true;
	}

	/**
	 * Notify users and administrator when they create an account on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser			The user object.
	 * @param	SocialTableProfile	The profile type.
	 * @return	bool				True if success, false otherwise.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function notify( $data , SocialUser $user , SocialTableProfile $profile )
	{
		// Get the application data.
		$jConfig 	= Foundry::jConfig();

		// Push arguments to template variables so users can use these arguments
		$params 	= array(
								'site'			=> $jConfig->getValue( 'sitename' ),
								'username'		=> $data[ 'username' ],
								'password'		=> $user->password_clear,
								'firstName'		=> !empty( $data[ 'first_name' ] ) ? $data[ 'first_name' ] : '',
								'middleName'	=> !empty( $data[ 'middle_name' ] ) ? $data[ 'middle_name' ] : '',
								'lastName'		=> !empty( $data[ 'last_name' ] ) ? $data[ 'last_name' ] : '',
								'name'			=> $user->getName(),
								'id'			=> $user->id,
								'avatar'		=> $user->getAvatar( SOCIAL_AVATAR_LARGE ),
								'profileLink'	=> $user->getPermalink(),
								'email'			=> $user->email,
								'activation'	=> FRoute::registration( array( 'external' => true , 'task' => 'activate' , 'controller' => 'registration' , 'token' => $user->activation ) ),
								'token'			=> $user->activation,
								'profileType'	=> $profile->get( 'title' )
						);

		// Get the email title.
		$title      = $profile->getEmailTitle();

		// Get the email format.
		$format 	= $profile->getEmailFormat();

		// Immediately send out emails
		$mailer 	= Foundry::mailer();

		// Get the email template.
		$mailTemplate	= $mailer->getTemplate();

		// Set recipient
		$mailTemplate->setRecipient( $user->name , $user->email );

		// Set title
		$mailTemplate->setTitle( $title );

		// Set the contents
		$mailTemplate->setTemplate( $profile->getEmailTemplate() , $params , $format );

		// Set the priority. We need it to be sent out immediately since this is user registrations.
		$mailTemplate->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

		// Try to send out email now.
		$state 		= $mailer->create( $mailTemplate );

		return $state;
	}

	/**
	 * Activates user account
	 *
	 * @param   string  The activation token.
	 * @return  mixed  	False on failure, user object on success.
	 * @since   1.6
	 */
	public function activate($token)
	{
		$db		= Foundry::db();

		$sql	= $db->sql();

		$sql->select( '#__users' );
		$sql->column( 'id' );
		$sql->where( 'activation', $token );
		$sql->where( 'block', '1' );
		$sql->where( 'lastvisitDate', $db->getNullDate() );

		$db->setQuery( $sql );

		$id 		= (int) $db->loadResult();

		// If user id cannot be located, throw an error.
		if( !$id )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_REGISTRATION_ACTIVATION_TOKEN_NOT_FOUND' ) );
			return false;
		}

		// Activate the user.
		$user	= Foundry::user( $id );
		$state 	= $user->activate( $token );

		if( !$state )
		{
			$this->setError( $user->getError() );
			return false;
		}

		return $user;
	}

}
