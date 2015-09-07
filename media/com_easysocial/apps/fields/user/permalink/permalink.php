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

// Include helper file.
Foundry::import( 'fields:/user/permalink/helper' );

/**
 * Field application for Gender
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserPermalink extends SocialFieldItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $options )
	{
		parent::__construct( $options );
	}

	/**
	 * Saves the permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function save( $post , $user )
	{
		$value 	= isset( $post[ $this->inputName ] ) && !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// There could be possibility that the user removes their permalink so
		// we should not check for empty value here.

		$table 	= Foundry::table( 'Users' );
		$table->load( array( 'user_id' => $user->id ) );

		$table->permalink	= $value;
		$table->store();
	}

	/**
	 * Once the registration is stored, we need to update the user's `permalink` column
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterAfterSave( &$post , $user )
	{
		return $this->save( $post , $user );
	}

	/**
	 * Saves the permalink after their profile is edited.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditAfterSave( &$post , $user )
	{
		return $this->save( $post , $user );
	}

	/**
	 * Performs validation for the gender field.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function validate( $post , $user = null )
	{
		$key 	= $this->inputName;

		// Get the current value
		$value 	= isset( $post[ $key ] ) ? $post[ $key ] : '';

		if( !$this->isRequired() && empty( $value ) )
		{
			return true;
		}

		// Catch for errors if this is a required field.
		if( $this->isRequired() && empty( $value ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_PERMALINK_REQUIRED' ) );

			return false;
		}

		if( $this->params->get( 'max' ) > 0 && JString::strlen( $value ) > $this->params->get( 'max' ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_PERMALINK_EXCEEDED_MAX_LENGTH' ) );
			return false;
		}

		// Determine the current user that is being edited
		$current 	= '';

		if( $user )
		{
			$current 	= $user->id;
		}

		if( $current )
		{
			$user 	= Foundry::user( $current );

			// If the permalink is the same, just return true.
			if( $user->permalink == $value )
			{
				return true;
			}
		}

		if( SocialFieldsUserPermalinkHelper::exists( $value ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_PERMALINK_NOT_AVAILABLE' ) );

			return false;
		}

		if( !SocialFieldsUserPermalinkHelper::valid( $value, $this->params ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_PERMALINK_INVALID_PERMALINK' ) );

			return false;
		}

		return true;
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterValidate( &$post , SocialTableRegistration &$registration )
	{
		$state 	= $this->validate( $post );

		return $state;
	}

	/**
	 * Performs validation when a user updates their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditValidate( &$post , $user )
	{
		$state 	= $this->validate( $post , $user );

		return $state;
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
	public function onRegister( &$post, &$registration )
	{
		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// Detect if there's any errors.
		$error 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'		, $error );
		$this->set( 'value'		, $this->escape( $value ) );

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
		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : $this->value;

		$error = $this->getError( $errors );

		$this->set( 'value', $this->escape( $value ) );
		$this->set( 'error', $error );


		return $this->display();
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}
}
