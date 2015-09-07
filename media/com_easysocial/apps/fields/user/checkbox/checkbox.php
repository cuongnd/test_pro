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
 * Field application for Checkbox
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserCheckbox extends SocialFieldItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
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
		// Load json library
		$json 		= Foundry::json();

		// Selected value
		$selected	= array();

		// Test if the user had tried to submit any values.
		if( !empty( $post[ $this->inputName ] ) )
		{
			$selected 	= $json->decode( $post[ $this->inputName ] );
		}

		// Get a list of options for this field.
		$options 	= $this->field->getOptions( 'items' );

		// If there's no options, we shouldn't even be showing this field.
		if( empty( $options ) )
		{
			return;
		}

		// Detect if there's any errors.
		$error 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'		, $error );
		$this->set( 'selected'	, $selected );
		$this->set( 'options' 	, $options );

		// Display the output.
		return $this->display();
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
	public function onRegisterValidate( &$post, SocialTableRegistration &$registration )
	{
		// Selected value
		$value 		= array();

		// Test for values here.
		if( !empty( $post[ $this->inputName ] ) )
		{
			$json 	= Foundry::json();
			$value 	= $json->decode( $post[ $this->inputName ] );
		}

		// If this is required, check for the value.
		if( $this->isRequired() && empty( $value ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_CHECKBOX_CHECK_AT_LEAST_ONE_ITEM' ) );
		}
	}

	/**
	 * Displays the field input for user when they edit their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @param	Array			The post data.
	 * @param	Array			The error data.
	 * @return	string			The html string of the field
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$options = $this->field->getOptions( 'items' );

		$selected = array();

		$json = Foundry::json();

		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : $this->value;

		$selected = $json->decode( $value );

		if( is_null( $selected ) || $selected === '' )
		{
			$selected 	= array();
		}

		$error = $this->getError( $errors );

		// Set the value.
		$this->set( 'options', $options );
		$this->set( 'error', $error );
		$this->set( 'selected', $selected );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the edit form.
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
		// Selected value
		$value 		= array();

		// Test for values here.
		if( !empty( $post[ $this->inputName ] ) )
		{
			$json 	= Foundry::json();
			$value 	= $json->decode( $post[ $this->inputName ] );
		}

		// If this is required, check for the value.
		if( $this->isRequired() && empty( $value ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_CHECKBOX_CHECK_AT_LEAST_ONE_ITEM' ) );
		}
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
	public function onSample( $data, $element, $isNew )
	{
		return $this->display();
	}

	public function onDisplay( $user )
	{
		$value		= $this->value;

		if( !$value )
		{
			return;
		}

		$value = Foundry::makeObject( $value );

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$options = array();

		foreach( $value as $v )
		{
			$option = Foundry::table( 'fieldoptions' );
			$option->load( array( 'parent_id' => $this->field->id, 'key' => 'items', 'value' => $v ) );

			$options[] = $option;
		}

		$this->set( 'options', $options );

		return $this->display( 'display' );
	}
}
