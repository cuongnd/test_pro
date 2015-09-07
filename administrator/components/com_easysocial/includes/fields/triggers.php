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

// Include abstract class so that it would be visible to the fields.
Foundry::import( 'admin:/includes/fields/dependencies' );

/**
 * Stores a list of triggers and it's execution.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldTriggers
{
	/**
	 * Dispatcher object.
	 * @var SocialDispatcher
	 */
	private $dispatcher 	= null;

	/**
	 * JSON library.
	 * @var SocialJSON
	 */
	private $json	= null;

	/**
	 * Stores a list of field items that are already loaded.
	 * @var	Array
	 */
	private $loaded			= null;

	/**
	 * Stores the current event
	 * @var String
	 */
	private $event = null;

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		// Initialize the dispatcher object.
		$this->dispatcher	= Foundry::getInstance( 'Dispatcher' );

		// Helper json library.
		$this->json 		= Foundry::json();
	}

	/**
	 * Responsible to attach the list of field apps into the dispatcher object.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function load( $fieldGroup , &$fields )
	{
		// If there is no fields, there's no point running them at all.
		if( !$fields )
		{
			return false;
		}

		// Final result
		$finalItems 	= array();

		// Go through each of the fields.
		foreach( $fields as $field )
		{
			// Set the key.
			$element 	= $field->element;

			// Load the language file for this field.
			$languageElement 	= 'plg_fields_' . $fieldGroup . '_' . $element;
			JFactory::getLanguage()->load( $languageElement , JPATH_ROOT . '/administrator' );

			// If field is already loaded, ignore and continue.
			if( isset( $this->loaded[ $element ] ) && $this->loaded[ $element ] !== false )
			{
				// If he field has already been loaded, add them to the final items.
				$finalItems[]	= $this->loaded[ $element ];
				continue;
			}

			// Get the file path
			$filePath 	= SOCIAL_APPS . '/' . SOCIAL_APPS_TYPE_FIELDS . '/' . $fieldGroup . '/' . $element . '/' . $element . '.php';

			// If file doesn't exist, ignore this
			if( !JFile::exists( $filePath ) )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: file ' . $filePath . ' not found' );

				$this->loaded[ $element ]	= false;
				continue;
			}

			// Include the fields file.
			include_once( $filePath );

			// Build the class name.
			$className 	= 'SocialFields' . ucfirst( $fieldGroup ) . ucfirst( $field->element );

			// If the class doesn't exist in this context, skip the whole loading.
			if( !class_exists( $className ) )
			{
				// Log error when the class does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field element class not found, ' . $className );

				$this->loaded[ $element ]	= false;
				continue;
			}

			// Initialize configuration.
			$config 	= array( 'element' => $field->element , 'group' => $fieldGroup );

			// Instantiate the new object here.
			$fieldObj 	= new $className( $config );

			// If the class is not part of our package, skip this.
			if( !( $fieldObj instanceof SocialFieldItem ) )
			{
				// Log error when the class does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field element class ' . $className . ' not a descendent of SocialFieldItem');

				// @TODO: Log error when class is not part of the package.
				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// Add this to the property so we know that it wouldn't get executed again.
			$this->loaded[ $field->element ]	= $fieldObj;

			// Assign the field object to the final items.
			$finalItems[]	= $fieldObj;
		}

		return $finalItems;

		// // Only people has this app.
		// Foundry::getInstance( 'Apps' )->load( SOCIAL_APPS_TYPE_FIELDS );

	}

	/**
	 * Set the triggered event name
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The triggered event name
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function setEvent( $event )
	{
		$this->event = $event;
	}

	/**
	 * Get the triggered event name
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The triggered event name
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * Event triggered during registration.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onGetValue( $fieldGroup , $fields , $data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= isset( $data[ 0 ] ) ? $data[ 0 ] : '';

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			if( method_exists( $fieldApp , __FUNCTION__ ) )
			{
				// Now, let's call the field to do it's job.
				$val	= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

				if( !is_null( $val ) || !empty( $val ) )
				{
					$result 	= $val;
				}
			}
		}

		return $result;
	}

	/**
	 * Event triggered during registration.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegister( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				// Log error when the class method does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field element class ' . $field->element . ' does not have the method ' . __FUNCTION__ );

				$field->output 	= '';
			}
			else
			{
				// Now, let's call the field to do it's job.
				// Need to capture any outputs being done by echo's too.
				ob_start();
				call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

				$contents 	= ob_get_contents();
				ob_end_clean();

				$field->output	= $contents;
			}

			// Reset the contents variable.
			// unset( $contents );
		}

		return $fields;
	}

	/**
	 * Event triggered when validating values of custom fields during registration.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterValidate( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return true;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// If there's no onRegisterValidate, we assume to bypass this.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				continue;
			}

			// Trigger onRegisterValidate
			call_user_func_array( array( $fieldApp , 'onRegisterValidate' ) , $data );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.
			$errors 		= $fieldApp->hasError();

			if( $errors )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]	= $fieldApp->getError();
			}
		}


		return $result;
	}

	/**
	 * Event triggered before a field is saved during registration mode.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterBeforeSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$value 		= true;
			}
			else
			{
				$value 			= call_user_func_array( array( $fieldApp , 'onRegisterBeforeSave' ) , $data );
			}

			if( !is_null( $value ) && $value !== true )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]   = $value;
			}
		}

		return $result;
	}

	/**
	 * Event triggered after a field is saved during registration mode.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterAfterSave( $fieldGroup , $fields, &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$result[]		= true;
			}
			else
			{
				$result[] 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}
		}

		return $result;
	}

	/**
	 * Event triggered after a field is saved during registration mode.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterAfterSaveFields( $fieldGroup , $fields, &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$result[]		= true;
			}
			else
			{
				$result[] 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}
		}

		return $result;
	}

	/**
	 * Event triggered before a field is saved during registration mode.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterOAuthBeforeSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$value 		= true;
			}
			else
			{
				$value 			= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}

			if( !is_null( $value ) && $value !== true )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]   = $value;
			}
		}

		return $result;
	}

	/**
	 * Event triggered before a field is saved during registration mode.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterOAuthAfterSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$value 		= true;
			}
			else
			{
				$value 			= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}

			if( !is_null( $value ) && $value !== true )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]   = $value;
			}
		}

		return $result;
	}

	/**
	 * Event triggered when the fields are being edited.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEdit( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'value'			=> $field->data,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				// Log error when the class method does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field element class ' . $field->element . ' does not have the method ' . __FUNCTION__ );

				$field->output 	= '';
			}
			else
			{
				// Now, let's call the field to do it's job.
				// Need to capture any outputs being done by echo's too.
				ob_start();
				call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

				$contents 	= ob_get_contents();
				ob_end_clean();

				$field->output	= $contents;
			}

			// Reset the contents variable.
			// unset( $contents );
		}

		return $fields;
	}

	/**
	 * Event triggered when validating values of custom fields when a user saves their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditValidate( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return true;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// If there's no onEditValidate, we assume to bypass this.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				continue;
			}

			// Trigger onEditValidate
			call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.
			$errors 		= $fieldApp->hasError();

			if( $errors )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]	= $fieldApp->getError();
			}
		}


		return $result;
	}

	/**
	 * Event triggered before a field is saved during editing mode.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditBeforeSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$value 		= true;
			}
			else
			{
				$value 			= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}

			if( !is_null( $value ) && $value !== true )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]   = $value;
			}
		}

		return $result;
	}

	/**
	 * Event triggered after a user data is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditAfterSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$result[]		= true;
			}
			else
			{
				$result[] 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}
		}

		return $result;
	}

	/**
	 * Event triggered after a user data is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditAfterSaveFields( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$result[]		= true;
			}
			else
			{
				$result[] 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
			}
		}

		return $result;
	}

	/**
	 * Event triggered when the fields are being edited.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onAdminEdit( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			// AdminEdit should fallback to Edit
			$method = __FUNCTION__;
			if( !method_exists( $fieldApp, $method ) )
			{
				$method = str_replace( 'Admin', '', __FUNCTION__ );
			}

			// We need to re-set the event here because some other field might set it to edit, and causing the subsequent field to not have the correct event
			$this->setEvent( $method );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'value'			=> $field->data,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			$fieldApp->init( $properties );

			if( !method_exists( $fieldApp , $method ) )
			{
				// Log error when the class method does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field element class ' . $field->element . ' does not have the method ' . $method );

				$field->output 	= '';
			}
			else
			{
				// Now, let's call the field to do it's job.
				// Need to capture any outputs being done by echo's too.
				ob_start();
				call_user_func_array( array( $fieldApp , $method ) , $data );

				$contents 	= ob_get_contents();
				ob_end_clean();

				$field->output	= $contents;
			}

			// Reset the contents variable.
			// unset( $contents );
		}

		return $fields;
	}

	/**
	 * Event triggered when validating values of custom fields when a user saves their profile in the backend.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onAdminEditValidate( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return true;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			// AdminEdit should fallback to Edit
			$method = __FUNCTION__;
			if( !method_exists( $fieldApp, $method ) )
			{
				$method = str_replace( 'Admin', '', __FUNCTION__ );
			}

			// We need to re-set the event here because some other field might set it to edit, and causing the subsequent field to not have the correct event
			$this->setEvent( $method );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			$fieldApp->init( $properties );

			// If there's no onAdminEditValidate, we assume to bypass this.
			if( !method_exists( $fieldApp , $method ) )
			{
				continue;
			}

			// Trigger onAdminEditValidate
			call_user_func_array( array( $fieldApp , $method ) , $data );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.
			$errors 		= $fieldApp->hasError();

			if( $errors )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]	= $fieldApp->getError();
			}
		}


		return $result;
	}

	/**
	 * Event triggered before a field is saved during editing mode in backend.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onAdminEditBeforeSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			// AdminEdit should fallback to Edit
			$method = __FUNCTION__;
			if( !method_exists( $fieldApp, $method ) )
			{
				$method = str_replace( 'Admin', '', __FUNCTION__ );
			}

			// We need to re-set the event here because some other field might set it to edit, and causing the subsequent field to not have the correct event
			$this->setEvent( $method );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , $method ) )
			{
				$value 		= true;
			}
			else
			{
				$value 			= call_user_func_array( array( $fieldApp , $method ) , $data );
			}

			if( !is_null( $value ) && $value !== true )
			{
				$result[ SOCIAL_FIELDS_PREFIX . $field->id ]   = $value;
			}
		}

		return $result;
	}

	/**
	 * Event triggered after a user data is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onAdminEditAfterSave( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			// AdminEdit should fallback to Edit
			$method = __FUNCTION__;
			if( !method_exists( $fieldApp, $method ) )
			{
				$method = str_replace( 'Admin', '', __FUNCTION__ );
			}

			// We need to re-set the event here because some other field might set it to edit, and causing the subsequent field to not have the correct event
			$this->setEvent( $method );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , $method ) )
			{
				$result[]		= true;
			}
			else
			{
				$result[] 		= call_user_func_array( array( $fieldApp , $method ) , $data );
			}
		}

		return $result;
	}

	/**
	 * Event triggered after a user data is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onAdminEditAfterSaveFields( $fieldGroup , $fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Add some logging here.
				continue;
			}

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			// AdminEdit should fallback to Edit
			$method = __FUNCTION__;
			if( !method_exists( $fieldApp, $method ) )
			{
				$method = str_replace( 'Admin', '', __FUNCTION__ );
			}

			// We need to re-set the event here because some other field might set it to edit, and causing the subsequent field to not have the correct event
			$this->setEvent( $method );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			$fieldApp->init( $properties );

			// This trigger expects a boolean value from the field.
			// Only process fields that has errors. Otherwise we assume that the validation was successful.

			// If method does not exist, we just assume that there's no errors.
			if( !method_exists( $fieldApp , $method ) )
			{
				$result[]		= true;
			}
			else
			{
				$result[] 		= call_user_func_array( array( $fieldApp , $method ) , $data );
			}
		}

		return $result;
	}

	/**
	 * Event triggered when the fields are being displayed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onDisplay( $fieldGroup , &$fields , &$data )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'value'			=> $field->data,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id,
				'user'			=> $data[0] // The first value of $data is always user
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				// Log error when the class method does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field element class ' . $field->element . ' does not have the method ' . __FUNCTION__ );

				$field->output 	= '';
			}
			else
			{
				// Now, let's call the field to do it's job.
				// Need to capture any outputs being done by echo's too.
				ob_start();
				call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

				$contents 	= ob_get_contents();
				ob_end_clean();

				$field->output	= $contents;
			}

			// Reset the contents variable.
			// unset( $contents );
		}

		return $fields;
	}

	/**
	 * Executes when the field is triggered before the cron service
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 */
	public function onCronExecute( $fieldGroup , &$fields, $data = array() )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		// Return data.
		$contents 		= array();

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field ' . $field->element . ' not found' );

				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// If this is a new field that may be dragged to the viewport,
			// we don't know about the field yet since it hasn't been saved yet.
			if( !$field->id )
			{
				$field->id 	= 0;
			}

			$fieldId		= $field->id;
			$appId 			= $field->app_id;

			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];

			if( method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$fieldApp->init( $properties );

				ob_start();

				$method 	= __FUNCTION__;

				$fieldApp->$method( $data , $field->getElement() );
				$contents[] 	= ob_get_contents();
				ob_end_clean();
			}
		}

		return $contents;
	}

	/**
	 * Executes when the field is triggered before the cron service
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 */
	public function onRenderDashboardSidebar( $fieldGroup , &$fields, $data = array() )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		// Return data.
		$sections 		= array( 'top' => array() , 'middle' => array() , 'bottom' => array() );

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field ' . $field->element . ' not found' );

				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// If this is a new field that may be dragged to the viewport,
			// we don't know about the field yet since it hasn't been saved yet.
			if( !$field->id )
			{
				$field->id 	= 0;
			}

			$fieldId	= $field->id;
			$appId 		= $field->app_id;

			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			if( method_exists( $fieldApp , __FUNCTION__ ) )
			{
				ob_start();

				$method 	= __FUNCTION__;

				$fieldApp->$method( $sections , $field->getElement() );

				ob_end_clean();
			}
		}

		return $sections;
	}


	/**
	 * Event triggered when the fields are searchable and to be indexed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function onIndexer( $fieldGroup , &$fields , &$data )
	{

		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );


		$result 	= array();

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'value'			=> $field->data,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );


			// If method does not exist, we just assume that there's no errors.
			if( method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$rdata 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

				if( $rdata !== false && $rdata)
				{
					$rdata = trim( $rdata );
					if( $rdata )
						$result[] 		= $rdata;
				}
			}

			// Reset the contents variable.
			// unset( $contents );
		}

		return $result;
	}


	/**
	 * Event triggered when the fields being search and to verify the.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function onIndexerSearch( $fieldGroup , &$fields , &$data )
	{

		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );


		$result 	= array();

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'value'			=> $field->data,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );


			// If method does not exist, we just assume that there's no errors.
			if( method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$rdata 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );

				if( $rdata !== false && $rdata)
				{
					$rdata = trim( $rdata );
					if( $rdata )
						$result[] 		= $rdata;
				}
			}

			// Reset the contents variable.
			// unset( $contents );
		}

		return $result;
	}


	/**
	 * Event triggered when the fields are used for friend suggest search.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function onFriendSuggestSearch( $fieldGroup , &$fields , &$data )
	{

		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );


		$result 	= array();

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// Add some logging here.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Class ' . $field->element . ' not found' );

				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'value'			=> $field->data,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );


			// If method does not exist, we just assume that there's no errors.
			if( method_exists( $fieldApp , __FUNCTION__ ) )
			{
				$rdata 		= call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
				if( $rdata !== false && count( $rdata ) > 0 )
				{
					$result 		= array_merge( $result, $rdata );
				}
			}


			// Reset the contents variable.
			// unset( $contents );
		}

		return $result;
	}

	/**
	 * Executes when the field is being retrieved for the back end.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The group of field types that should be triggered.
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	The arguments to pass in to the trigger.
	 * @return	Mixed	Either boolean or the array of SocialField objects.
	 */
	public function onSample( $fieldGroup , &$fields, $data = array() )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return false;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		foreach( $fields as &$field )
		{
			// Always initialize blank output first.
			$field->output 	= '';

			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field ' . $field->element . ' not found' );

				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// If this is a new field that may be dragged to the viewport,
			// we don't know about the field yet since it hasn't been saved yet.
			if( !$field->id )
			{
				$field->id 	= 0;
			}

			$fieldId	= $field->id;
			$appId 		= $field->app_id;

			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			if( !method_exists( $fieldApp , 'onSample' ) )
			{
				$contents 	= JText::_( 'Field application did not specify a renderer for onSample.' );
			}
			else
			{
				// Now, let's call the field to do it's job.
				// Need to capture any outputs being done by echo's too.

				$isNew 			= !$fieldId ? true : false;

				ob_start();
				$fieldApp->onSample( $data , $field->getElement() , $isNew );
				$contents 	= ob_get_contents();
				ob_end_clean();
			}

			$field->output	= $contents;

			// Reset the contents variable.
			unset( $contents );
		}

		return $fields;
	}

	public function onOAuthGetMetaFields( $fieldGroup , &$fields, $data = array() )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return true;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field ' . $field->element . ' not found' );

				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// If there's no onRegisterValidate, we assume to bypass this.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				continue;
			}

			// Trigger onRegisterValidate
			call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
		}

		return true;
	}

	public function onOAuthGetUserPermission( $fieldGroup , &$fields, $data = array() )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return true;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field ' . $field->element . ' not found' );

				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// If there's no onRegisterValidate, we assume to bypass this.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				continue;
			}

			// Trigger onRegisterValidate
			call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
		}

		return true;
	}

	public function onOAuthGetUserMeta( $fieldGroup , &$fields, $data = array() )
	{
		// There's nothing to be triggered, just return false.
		if( !$fields )
		{
			return true;
		}

		// Load up the fields.
		$this->load( $fieldGroup , $fields );

		$result 	= array();

		// Loop through each of the fields.
		foreach( $fields as &$field )
		{
			// If it's not initialized or if it's a false value, just ignore.
			if( !isset( $this->loaded[ $field->element ] ) || $this->loaded[ $field->element] === false )
			{
				// @TODO: Log error when the file of the field does not exist.
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Field ' . $field->element . ' not found' );

				$this->loaded[ $field->element ]	= false;
				continue;
			}

			// Get a registry object for the parameters.
			$params			= Foundry::fields()->getFieldConfigValues( $field );

			$properties 	= array(
				'event'			=> $this->event,
				'params'		=> $params,
				'element'		=> $field->element,
				'group'			=> $fieldGroup,
				'field'			=> $field,
				'inputName'		=> SOCIAL_FIELDS_PREFIX . $field->id,
				'profileId'		=> $field->profile_id
			);

			// Initialize the field with the respective properties.
			$fieldApp 		= $this->loaded[ $field->element ];
			$fieldApp->init( $properties );

			// If there's no onRegisterValidate, we assume to bypass this.
			if( !method_exists( $fieldApp , __FUNCTION__ ) )
			{
				continue;
			}

			// Trigger onRegisterValidate
			call_user_func_array( array( $fieldApp , __FUNCTION__ ) , $data );
		}

		return true;
	}
}

