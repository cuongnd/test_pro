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
 * Field application for Textbox
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserTextbox extends SocialFieldItem
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
	public function onRegister( &$post , &$registration )
	{
		// Get the value
		$value 	= !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : JText::_( $this->params->get( 'default' ), true );

		// Set value
		$this->set( 'value' , $this->escape( $value ) );

		// Set errors
		$error = $registration->getErrors( $this->inputName );

		$this->set( 'error' , $error );

		return $this->display();
	}

	/**
	 * Validates the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	bool	State of the validation
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterValidate( &$post )
	{
		$value = !empty( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		return $this->validateInput( $value );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post )
	{
		// use isset instead of !empty because we do not even wan empty string or false value here
		if( $this->params->get( 'readonly' ) && isset( $post[ $this->inputName ] ) )
		{
			unset( $post[ $this->inputName ] );
		}

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
		$value 	= $this->value;

		if( !$value )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		// Push variables into theme.
		$this->set( 'value' , $this->escape( $value ) );

		return $this->display();
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
	public function onEdit( &$post, &$user, $errors )
	{
		// Get the value.
		$value 	= !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : $this->value;

		// Get the error.
		$error = $this->getError( $errors );

		// Set the value.
		$this->set( 'value'	, $this->escape( $value ) );
		$this->set( 'error'	, $error );

		return $this->display();
	}

	/**
	 * Validates the field input for user when they edit their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	bool	State of the validation
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		$value = !empty( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		return $this->validateInput( $value );
	}

	/**
	 * Executes before a user's edit is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialUser	The user object.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditBeforeSave( &$post )
	{
		// use isset instead of !empty because we do not even wan empty string or false value here
		if( $this->params->get( 'readonly' ) && isset( $post[ $this->inputName ] ) )
		{
			unset( $post[ $this->inputName ] );
		}

		return true;
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}

	/**
	 * General validation function
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	Value of the string to validate
	 * @return	bool	State of the validation
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 */
	private function validateInput( $value )
	{
		if( $this->isRequired() && empty( $value ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_REQUIRED' ) );
		}

		if( !empty( $value ) && $this->params->get( 'min' ) > 0 && JString::strlen( $value ) < $this->params->get( 'min' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_SHORT' ) );
		}

		if( $this->params->get( 'max' ) > 0 && JString::strlen( $value ) > $this->params->get( 'max' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_LONG' ) );
		}

		if( $this->params->get( 'regex_validate' ) )
		{
			$format = $this->params->get( 'regex_format' );
			$modifier = $this->params->get( 'regex_modifier' );

			$pattern = '/' . $format . '/' . $modifier;

			$result = preg_match( $pattern, $value );

			if( empty( $result ) )
			{
				return $this->setError( JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_INVALID_FORMAT' ) );
			}
		}

		return true;
	}
}
