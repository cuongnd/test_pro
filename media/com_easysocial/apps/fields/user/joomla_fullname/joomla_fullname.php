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

// Include the fields library
Foundry::import( 'admin:/includes/fields/fields' );

/**
 * Field application for Joomla full name.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserJoomla_FullName extends SocialFieldItem
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * We need to ensure that the fields data are stored so that we don't need to mess up with the name column in Joomla.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditBeforeSave( &$post , $user )
	{
		// Detect if the name is changed.
		$post[ 'nameChanged' ]	= $this->nameChanged( $post , $user );

		return $this->save( $post );
	}

	/**
	 * Triggers before a user is saved
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onAdminEditBeforeSave( &$post , $user )
	{
		// Detect if the name is changed.
		$post[ 'nameChanged' ]	= $this->nameChanged( $post , $user );

		return $this->save( $post );
	}

	/**
	 * Triggers after a user is saved by the admin
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onAdminEditAfterSave( &$data , $user )
	{
		$config 	= Foundry::config();

		if( $config->get( 'users.aliasName' ) != 'realname' )
		{
			return;
		}

		// Only proceed when the name has been changed.
		if( isset( $data[ 'nameChanged' ] ) && !$data[ 'nameChanged' ] )
		{
			return;
		}

		$this->saveAlias( $data , $user );
	}

	/**
	 * Triggers after a user is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onEditAfterSave( &$data , $user )
	{
		$config 	= Foundry::config();

		if( $config->get( 'users.aliasName' ) != 'realname' )
		{
			return;
		}

		// Only proceed when the name has been changed.
		if( isset( $data[ 'nameChanged' ] ) && !$data[ 'nameChanged' ] )
		{
			return;
		}

		$this->saveAlias( $data , $user );
	}

	/**
	 * Determines if the name has changed
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function nameChanged( $post , $user )
	{
		// Detect if the name has changed
		$firstName 		= isset( $post[ 'first_name' ] ) ? $post[ 'first_name' ] : '';
		$middleName 	= isset( $post[ 'middle_name' ] ) ? $post[ 'middle_name' ] : '';
		$lastName 		= isset( $post[ 'last_name' ] ) ? $post[ 'last_name' ] : '';

		$fullName 		= $firstName . ' ' . $middleName . ' ' . $lastName;

		if( $fullName != $user->name )
		{
			return true;
		}

		return false;
	}

	/**
	 * Responsible to save the alias of the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function saveAlias( &$data , $user )
	{
		// Get the first name.
		$firstName 		= isset( $data[ 'first_name' ] ) ? $data[ 'first_name' ] : '';
		$middleName 	= isset( $data[ 'middle_name' ] ) ? $data[ 'middle_name' ] : '';
		$lastName 		= isset( $data[ 'last_name' ] ) ? $data[ 'last_name' ] : '';

		$fullName 		= $firstName . ' ' . $middleName . ' ' . $lastName;

		$alias 			= JFilterOutput::stringURLSafe( $fullName );

		// Check if the alias exists.
		$model 			= Foundry::model( 'Users' );

		// Keep the original state of the alias
		$tmp 	= $alias;

		while( $model->aliasExists( $alias , $user->id ) )
		{
			// Generate a new alias for the user.
			$alias	= $tmp . '-' . rand( 1 , 150 );
		}

		$user->alias 	= $alias;

		$user->save();
	}

	/**
	 * We need to ensure that the fields data are stored so that we don't need to mess up with the name column in Joomla.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post )
	{
		return $this->save( $post );
	}

	/**
	 * Processes after a user registers on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onRegisterAfterSave( &$data , $user )
	{
		$config 	= Foundry::config();

		if( $config->get( 'users.aliasName' ) != 'realname' )
		{
			return;
		}

		// Only proceed when the name has been changed.
		if( isset( $data[ 'nameChanged' ] ) && !$data[ 'nameChanged' ] )
		{
			return;
		}

		$this->saveAlias( $data , $user );
	}

	private function save( &$data )
	{
		// Get the first name.
		$firstName 		= isset( $data[ 'first_name' ] ) ? $data[ 'first_name' ] : '';
		$middleName 	= isset( $data[ 'middle_name' ] ) ? $data[ 'middle_name' ] : '';
		$lastName 		= isset( $data[ 'last_name' ] ) ? $data[ 'last_name' ] : '';

		// Build the real name.
		$name 			= '';

		if( $firstName )
		{
			$name 		.= $firstName;
		}

		if( $middleName )
		{
			$name 		.= ' ' . $middleName;
		}

		if( $lastName )
		{
			$name 		.= ' ' . $lastName;
		}

		// Assign a "name" index so that `#__users`.`name` can have proper values.
		$data[ 'name' ] 	= $name;

		// Assign the data to be stored in our own table.
		$nameObj 			= new stdClass;
		$nameObj->first		= $firstName;
		$nameObj->middle	= $middleName;
		$nameObj->last		= $lastName;
		$nameObj->name 		= $name;

		$data[ $this->inputName ]		= array( 'data' => Foundry::json()->encode( $nameObj ), 'raw' => $name );

		return true;
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterValidate( &$post )
	{
		return $this->validateName( $post );
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		return $this->validateName( $post );
	}

	/**
	 * Validates the field
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function validateName( &$post )
	{
		// Get the format to display the names.
		$format 	= $this->params->get( 'format' );

		// Get the first name from the query.
		$firstName	= isset($post[ 'first_name' ]) ? trim( $post[ 'first_name' ] ) : '';

		// Get the middle name
		$middleName	= isset($post[ 'middle_name' ]) ? trim( $post[ 'middle_name' ] ) : '';

		// Get the last name
		$lastName	= isset($post[ 'last_name' ]) ? trim( $post[ 'last_name' ] ) : '';

		// Compute user's name.
		$name 		= $firstName . ' ' . $middleName . ' ' . $lastName;

		// Remove unnecessary spaces.
		$name 		= trim( $name );

		// Test if this field is required
		if( $this->isRequired() && empty( $name ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_VALIDATION_EMPTY_NAME' ) );

			return false;
		}

		return true;
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegister( &$post , SocialTableRegistration &$registration )
	{
		// Display the form
		$this->displayForm( $post );

		// Detect if there's any errors.
		$error 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'	, $error );

		return $this->display();
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @return
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$value = !empty( $post[$this->inputName] ) ? $post[$this->inputName] : $this->value;

		$default 	= Foundry::json()->decode( $value );

		// If value is empty, most likely they were being imported.
		if( empty( $this->value ) )
		{
			$default 			= new stdClass();
			$default->first		= $user->getName();
			$default->middle 	= '';
			$default->last 		= '';
			$default->name 		= $user->getName();
		}

		$this->displayForm( $post , $default );

		$error = $this->getError( $errors );

		$this->set( 'error' , $error );

		return $this->display();
	}

	public function displayForm( $post , $default = null )
	{
		// Get the format to display the names.
		$format 	= $this->params->get( 'format' );

		$firstName	= isset($post[ 'first_name' ]) ? trim( $post[ 'first_name' ] ) : '';
		$middleName	= isset($post[ 'middle_name' ]) ? trim( $post[ 'middle_name' ] ) : '';
		$lastName	= isset($post[ 'last_name' ]) ? trim( $post[ 'last_name' ] ) : '';

		if( !isset( $post[ 'first_name' ] ) && !is_null( $default ) )
		{
			$firstName 	= $default->first;
		}

		if( !isset( $post[ 'middle_name' ] ) && !is_null( $default ) )
		{
			$middleName 	= $default->middle;
		}

		if( !isset( $post[ 'last_name' ] ) && !is_null( $default ) )
		{
			$lastName 	= $default->last;
		}


		$this->set( 'firstName'	, $this->escape( $firstName ) );
		$this->set( 'middleName', $this->escape( $middleName ) );
		$this->set( 'lastName'	, $this->escape( $lastName ) );

		$name 		= '';

		if( !empty( $firstName ) )
		{
			$name 	.= $firstName;
		}

		if( !empty( $middleName ) )
		{
			$name 	.= ' ' . $middleName;
		}

		if( !empty( $lastName ) )
		{
			$name 	.= ' ' . $lastName;
		}

		$this->set( 'name'		, $this->escape( $name ) );

		return true;
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when their profile is viewed.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function onDisplay( $user )
	{
		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$name 			= new stdClass();

		if( empty( $this->value ) )
		{
			$name->first		= $this->escape( $user->getName() );
			$name->middle 		= '';
			$name->last 		= '';
			$name->name 		= $this->escape( $user->getName() );
		}
		else
		{
			$obj = Foundry::makeObject( $this->value );

			$name->first		= !empty( $obj->first ) ? $this->escape( $obj->first ) : '';
			$name->middle		= !empty( $obj->middle ) ? $this->escape( $obj->middle ) : '';
			$name->last			= !empty( $obj->last ) ? $this->escape( $obj->last ) : '';
			$name->name			= !empty( $obj->name ) ? $this->escape( $obj->name ) : '';
		}

		// $this->set( 'user'	, $user );
		$this->set( 'name'	, $name );

		return $this->display();
	}

	/**
	 * Retrieves the user's name.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @return	object
	 */
	protected function getName( SocialUser $user , $value = '' )
	{
		if( !$value )
		{
			$nameObj 			= new stdClass();
			$nameObj->first		= $user->getName();;
			$nameObj->middle 	= '';
			$nameObj->last 		= '';
			$nameObj->name 		= $user->getName();

			return $nameObj;
		}

		return Foundry::json()->decode( $value );
	}


	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}

	public function onOAuthGetMetaFields( &$fields )
	{
		$fields = array_merge( $fields, array( 'name', 'first_name', 'middle_name', 'last_name' ) );
	}

	public function onRegisterOAuthBeforeSave( &$post, &$client )
	{
		$this->save( $post );
	}

	public function onRegisterOAuthAfterSave( &$post, &$client, &$user )
	{
		$config 	= Foundry::config();

		if( $config->get( 'users.aliasName' ) != 'realname' )
		{
			return;
		}

		// Only proceed when the name has been changed.
		if( isset( $post[ 'nameChanged' ] ) && !$post[ 'nameChanged' ] )
		{
			return;
		}

		$this->saveAlias( $post , $user );
	}
}
