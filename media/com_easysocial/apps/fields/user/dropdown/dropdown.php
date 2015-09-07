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
 * Field application for Drop down
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserDropdown extends SocialFieldItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $config = array() )
	{
		parent::__construct( $config );
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
		// Current selected value.
		$selected 	= '';

		// If the value exists in the post data, it means that the user had previously set some values.
		if( isset( $post[ $this->inputName ] ) && !empty( $post[ $this->inputName ] ) )
		{
			$selected 	= $post[ $this->inputName ];
		}

		// Get list of child options
		$options = $this->params->get( 'items' );

		if( empty( $options ) )
		{
			return;
		}

		// Detect if there's any errors.
		$errors 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'		, $errors );

		// Set the default value.
		$this->set( 'selected'	, $selected );

		// Set options
		$this->set( 'options'	, $options );

		// Display the output.
		return $this->display();
	}

	/**
	 * Displays the field input for user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$options = $this->params->get( 'items' );

		$selected = '';

		if( !empty( $options ) )
		{
			foreach( $options as $id => $option )
			{
				if( !empty( $option->default ) )
				{
					$selected = $option->value;
				}
			}
		}

		// If this field have value, then we use from value
		if( !empty( $this->value ) )
		{
			$selected = $this->value;
		}

		// If the value exists in the post data, it means that the user had previously set some values.
		if( !empty( $post[ $this->inputName ] ) )
		{
			$selected 	= $post[ $this->inputName ];
		}

		$error = $this->getError( $errors );

		$this->set( 'error', $error );
		$this->set( 'selected', $selected );
		$this->set( 'options', $options );

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
		$value		= $this->value;

		if( !$value )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$option = Foundry::table('fieldoptions');
		$option->load( array( 'parent_id' => $this->field->id, 'key' => 'items', 'value' => $value ) );

		// Push variables into theme.
		$this->set( 'option', $option );
		$this->set( 'value' , $value );

		return $this->display( 'display' );
	}

	/**
	 * return formated string from the fields value
	 *
	 * @since	1.0
	 * @access	public
	 * @param	userfielddata
	 * @return	array array of objects with two attribute, ffriend_id, score
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onIndexer( $userFieldData )
	{
		if(! $this->field->searchable )
			return false;

		$content = trim( $userFieldData );

		if( $content )
			return $content;
		else
			return false;
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
		$options = $this->params->get( 'items' );

		$selected = '';

		if( !empty( $options ) )
		{
			foreach( $options as $id => $option )
			{
				if( !empty( $option->default ) )
				{
					$selected = $option->value;
				}
			}
		}

		$this->set( 'selected', $selected );
		$this->set( 'options', $options );

		return $this->display();
	}
}
