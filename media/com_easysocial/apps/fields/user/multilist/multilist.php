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
 * Field application for Multilist
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserMultilist extends SocialFieldItem
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
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegister( &$post , &$registration )
	{
		// Get list of child options
		$options = $this->params->get( 'items' );

		// Current selected value.
		$selected 	= array();

		// If the value exists in the post data, it means that the user had previously set some values.
		if( empty( $post[ $this->inputName ] ) )
		{
			if( !empty( $options ) )
			{
				foreach( $options as $id => $option )
				{
					if( !empty( $option->default ) )
					{
						$selected[] = $option->value;
					}
				}
			}
		}
		else
		{
			$selected 	= Foundry::makeObject( $post[ $this->inputName ] );
		}

		// Detect if there's any errors.
		$error 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'		, $error );

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

		$selected = array();

		if( empty( $this->value ) )
		{
			if( !empty( $options ) )
			{
				foreach( $options as $id => $option )
				{
					if( !empty( $option->default ) )
					{
						$selected[] = $option->value;
					}
				}
			}
		}
		else
		{
			$selected = Foundry::makeObject( $this->value );
		}

		// If the value exists in the post data, it means that the user had previously set some values.
		if( !empty( $post[ $this->inputName ] ) )
		{
			$selected 	= Foundry::makeObject( $post[ $this->inputName ] );
		}

		$error = $this->getError( $errors );

		$this->set( 'error', $error );
		$this->set( 'selected', $selected );
		$this->set( 'options', $options );

		return $this->display();
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onSample()
	{
		$options = $this->params->get( 'items' );

		$selected = array();

		if( !empty( $options ) )
		{
			foreach( $options as $id => $option )
			{
				if( !empty( $option->default ) )
				{
					$selected[] = $option->value;
				}
			}
		}

		$this->set( 'selected', $selected );
		$this->set( 'options', $options );

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
