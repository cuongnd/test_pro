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
 * Field application for Gender
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserGender extends SocialFieldItem
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
	 * Returns a presentable value to the caller.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getValue( $key )
	{
		$value 	= JText::_( 'PLG_FIELDS_GENDER_VALUE_THEIR' );

		if( $this->field->data == 2 )
		{
			$value 	= JText::_( 'PLG_FIELDS_GENDER_VALUE_HER' );
		}

		if( $this->field->data == 1 )
		{
			$value 	= JText::_( 'PLG_FIELDS_GENDER_VALUE_HIS' );
		}

		return $value;
	}

	/**
	 * Performs validation for the gender field.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function validate( $post , $key = null )
	{
		$key 	= is_null( $key ) ? $this->inputName : $key;

		// Get the current value
		$value 	= isset( $post[ $key ] ) ? $post[ $key ] : '';

		// Catch for errors if this is a required field.
		if( $this->isRequired() && empty( $value ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_GENDER_VALIDATION_GENDER_REQUIRED' ) );

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
	public function onEditValidate( &$post )
	{
		$state 	= $this->validate( $post );

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
		// Get the default value.
		$value 		= '';

		// If the value exists in the post data, it means that the user had previously set some values.
		if( isset( $post[ $this->inputName ] ) && !empty( $post[ $this->inputName ] ) )
		{
			$value 	= $post[ $this->inputName ];
		}

		// Detect if there's any errors.
		$error 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'		, $error );
		$this->set( 'value'		, $value );

		return $this->display();
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
		$value 	= $this->value;

		if( empty( $value ) )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		// Push variables into theme.
		$this->set( 'value'	, $value );

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
		$value 	= $this->value;

		$error = $this->getError( $errors );

		$this->set( 'value', $value );
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

	public function onRegisterOAuthBeforeSave( &$post, $client )
	{
		if( empty( $post['gender'] ) )
		{
			return;
		}

		$post[$this->inputName] = 0;

		if( $post['gender'] === 'male' )
		{
			$post[$this->inputName] = 1;
		}

		if( $post['gender'] === 'female' )
		{
			$post[$this->inputName] = 2;
		}
	}

	public function onOAuthGetMetaFields( &$fields )
	{
		$fields[] = 'gender';
	}
}
