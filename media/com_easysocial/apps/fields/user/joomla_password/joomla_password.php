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
 * Field application for Joomla password
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserJoomla_Password extends SocialFieldItem
{
	protected $password	= null;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array		The post data.
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegister( &$post , &$registration )
	{
		// Check for errors
		$error		= $registration->getErrors( $this->inputName );

		// Set errors.
		$this->set( 'error', $error );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterValidate( &$post )
	{
		$input	 	= !empty( $post[$this->inputName . '-input'] ) ? $post[$this->inputName . '-input'] : '';
		$reconfirm	= !empty( $post[$this->inputName . '-reconfirm'] ) ? $post[$this->inputName . '-reconfirm'] : '';

		// Check if reconfirm passwords is disabled.
		if( !$this->params->get( 'reconfirm_password' ) )
		{
			$reconfirm = $input;
		}

		return $this->validatePassword( $input, $reconfirm );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post, &$user )
	{
		// We do not need to validate against reconfirm here
		$input	 	= !empty( $post[$this->inputName . '-input'] ) ? $post[$this->inputName . '-input'] : '';

		// The user->bind function expects the post to have a password key
		$post['password'] = $input;

		// Remove the data from $post to prevent passwords saving in fields table
		unset( $post[$this->inputName . '-input'] );
		unset( $post[$this->inputName . '-reconfirm'] );

		return true;
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser	User object who is editing the profile
	 * @param	array		The post data
	 * @param	array		The error data
	 * @return	string		The html output
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		// Get errors.
		$error = $this->getError( $errors );

		// Set errors.
		$this->set( 'error', $error );

		return $this->display();
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onAdminEditValidate( &$post, &$user )
	{
		// Get the input
		$input	 	= !empty( $post[$this->inputName . '-input'] ) ? $post[$this->inputName . '-input'] : '';
		$reconfirm	= !empty( $post[$this->inputName . '-reconfirm'] ) ? $post[$this->inputName . '-reconfirm'] : '';

		// Check if reconfirm passwords is disabled.
		if( !$this->params->get( 'reconfirm_password' ) )
		{
			$reconfirm = $input;
		}

		// Check if user is registered user or new user
		$newUser = empty( $user->id );

		if( $newUser || !( empty( $input ) && empty( $reconfirm ) ) )
		{
			return $this->validatePassword( $input, $reconfirm );
		}

		return true;
	}

	/**
	 * Validates the password when the user edits their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		$input	 	= !empty( $post[$this->inputName . '-input'] ) ? $post[$this->inputName . '-input'] : '';
		$reconfirm	= !empty( $post[$this->inputName . '-reconfirm'] ) ? $post[$this->inputName . '-reconfirm'] : '';

		// Check if reconfirm passwords is disabled.
		if( !$this->params->get( 'reconfirm_password' ) )
		{
			$reconfirm = $input;
		}

		if( !( empty( $reconfirm ) && empty( $input ) ) )
		{
			return $this->validatePassword( $input, $reconfirm );
		}

		return true;
	}

	/**
	 * Executes before a user's edit is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditBeforeSave( &$post, &$user )
	{
		// We do not need to validate against reconfirm here
		$input	 	= !empty( $post[$this->inputName . '-input'] ) ? $post[$this->inputName . '-input'] : '';

		// If input is empty then don't change the password
		if( !empty( $input ) )
		{
			// The user->bind function expects the post to have a password key
			// For changing password, Joomla expects both key to be the same in order for password to change properly
			$post['password'] = $input;
			$post['password2'] = $input;
		}

		// Remove the data from $post to prevent passwords saving in fields table
		unset( $post[$this->inputName . '-input'] );
		unset( $post[$this->inputName . '-reconfirm'] );

		return true;
	}

	private function validatePassword( $input, $reconfirm )
	{
		// Verify that the passwords are valid and not empty
		if( empty( $input ) || empty( $reconfirm ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_EMPTY_PASSWORD' ) );

			return false;
		}

		if( $this->params->get( 'min' ) > 0 && strlen( $input ) < $this->params->get( 'min' ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_TOO_SHORT' ) );

			return false;
		}

		if( $input !== $reconfirm )
		{
			$this->setError( JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_NOT_MATCHING' ) );

			return false;
		}

		return true;
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}
}
