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

// Load triggers for fields.
Foundry::import( 'admin:/includes/fields/triggers' );

// Load apps dependencies.
Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Responsible to manage the field items including
 * event triggers.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFields
{
	/**
	 * The triggerer object for fields.
	 * @var	SocialFieldTriggers
	 */
	private $triggerer	= null;

	static 	$_apps 		= array();

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   $id     int/Array     Optional parameter
	 * @return  SocialUser   The person object.
	 */
	public static function getInstance()
	{
		static $obj = null;

		if( is_null( $obj ) )
		{
			$obj 	= new self();
		}

		return $obj;
	}

	/**
	 * Triggers specific for custom fields. This needs to be triggered differently.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The event trigger name.
	 * @param	string	The group of the trigger. (E.g: user, groups)
	 * @param	Array	An array of SocialField objects.
	 * @param	Array	An array of data for the fields.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function trigger( $event , $group , &$fields , &$data = array() )
	{
		// If there's no fields to load, we shouldn't be doing anything at all.
		if( empty( $fields ) )
		{
			return false;
		}
		// Initialize adapter if necessary.
		if( is_null( $this->triggerer ) )
		{
			// Create the triggers
			$this->triggerer	= new SocialFieldTriggers();
		}

		$exists  = method_exists( $this->triggerer , $event );

		if( !$exists )
		{
			return false;
		}

		// Set the event name for element references
		$this->triggerer->setEvent( $event );

		// We shouldn't load all the apps because we need to selectively load the apps.
		return $this->triggerer->$event( $group , $fields , $data );
	}

	/**
	 * Renders field widgets
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function renderWidgets( $group , $view , $position , $args )
	{
		// Get the app that uses the unique key.
		$model 		= Foundry::model( 'Fields' );

		// Get the unique key from the arguments
		$key 		= $args[ 0 ];

		// Get the user from the arguments
		$user 		= $args[ 1 ];

		$options 	= array( 'key' => $key , 'profile_id' => $user->profile_id , 'data' => true , 'dataId' => $user->id ,'dataType' => SOCIAL_TYPE_USER );

		// There should only be 1 field that is tied to a single unique key at all point of time.
		$fields 	= $model->getCustomFields( $options );

		if( !isset( $fields[ 0 ] ) )
		{
			return false;
		}

		$field 		= $fields[ 0 ];

		// Initialize default contents
		$contents 	= '';

		// Build the path to the field.
		$file 		= SOCIAL_FIELDS . '/' . $group . '/' . $field->element . '/widgets/' . $view . '/view.html.php';

		$exists 	= JFile::exists( $file );

		if( !JFile::exists( $file ) )
		{
			return;
		}

		require_once( $file );

		$className 	= ucfirst( $field->element ) . 'FieldWidgets' . ucfirst( $view );

		// Check if the class exists in this context.
		if( !class_exists( $className ) )
		{
			continue;
		}

		$widgetObj 	= new $className();

		// Check if the position exists as a method.
		$exists 	= method_exists( $widgetObj , $position );

		if( !$exists )
		{
			return;
		}

		// Send the field as argument
		$args[]		= $field;

		ob_start();
		call_user_func_array( array( $widgetObj , $position ) , $args );
		$output 	= ob_get_contents();
		ob_end_clean();

		$contents .= $output;

		// If nothing to display, just return false.
		if( empty( $contents ) )
		{
			return false;
		}

		// We need to wrap the app contents with our own wrapper.
		$theme 		= Foundry::themes();
		$theme->set( 'contents' , $contents );
		$contents	= $theme->output( 'site/apps/default.field.widget.' . strtolower( $view ) );

		return $contents;
	}

	/**
	 * Retrieves a value from a particular custom field given the unique key
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The field's unique element
	 * @param	string	The field's group
	 * @return
	 */
	public function getValue( SocialTableField $field , $group )
	{
		// Renders a field to retrieve the value
		$file 	= SOCIAL_APPS . '/' . SOCIAL_APPS_TYPE_FIELDS . '/' . $group . '/' . $field->element . '/' . $field->element . '.php';

		jimport( 'joomla.filesystem.file' );
		if( !JFile::exists( $file ) )
		{
			return false;
		}

		require_once( $file );

		$className 	= 'SocialFields' . ucfirst( $group ) . ucfirst( $field->element );

		if( !class_exists( $className ) )
		{
			return false;
		}

		$params 	= Foundry::fields()->getFieldConfigValues( $field );
		$options 	= array(
								'params'		=> $params,
								'element'		=> $field->element,
								'group'			=> $group,
								'field'			=> $field
							);

		$fieldApp		= new $className( $options );

		// Determine if the field app has the 'getValue' method.
		if( method_exists( $fieldApp , 'getValue' ) )
		{
			// Get the value.
			$value 		= $fieldApp->getValue( $field->unique_key );			
		}
		else
		{
			$value 		= $fieldApp->getFieldData();
		}

		// @trigger onGetValue
		// Trigger onGetValue so field apps can manipulate the value.
		$fieldsModel 	= Foundry::model( 'Fields' );
		$fields			= $fieldsModel->getCustomFields();

		$args 		= array( $value , $field->unique_key );

		$value 		= Foundry::fields()->trigger( 'onGetValue' , $group , $fields , $args );

		return $value;
	}

	/**
	 * Get the default manifest from defaults/fields.json
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Object	The default manifest object
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com
	 */
	public function getDefaultManifest()
	{
		static $manifest = null;

		if( empty( $manifest ) )
		{
			$path		= SOCIAL_CONFIG_DEFAULTS . '/fields.json';
			$raw		= JFile::read( $path );

			$manifest	= Foundry::json()->decode( $raw );
		}

		return $manifest;
	}

	/**
	 * Get the default data from the manifest file of the core apps.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of applications
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCoreManifest( $fieldGroup = SOCIAL_FIELDS_GROUP_USER , $apps )
	{
		// Ensure that it's an array.
		$apps 	= Foundry::makeArray( $apps );

		// If apps is empty, ignore.
		if( !$apps )
		{
			return false;
		}

		// Default value
		$fields 	= array();

		// Lets go through the list of apps that are core.
		foreach( $apps as $app )
		{
			// Get the full default configuration
			$config = $this->getFieldConfigParameters( $app->id );

			// Initialise an object that should stores only the default value
			$obj = new stdClass();

			// Manually extract the default values
			foreach( $config as $name => $fields )
			{
				if( property_exists( $fields, 'default' ) )
				{
					$obj->$name = $fields->default;
				}
			}

			// We need to set the application id here.
			$obj->app_id	= $app->id;

			// Add them to the fields list.
			$fields[]		= $obj;
		}

		return $fields;
	}

	private function loadAppData( $appId )
	{
		if( count( self::$_apps ) == 0 )
		{
			// lets load all apps.
			$model		= Foundry::model( 'Apps' );
			$options	= array( 'type' => SOCIAL_APPS_TYPE_FIELDS );

			$apps		= $model->setLimit(0)->getApps( $options );

			if( $apps )
			{
				foreach( $apps as $app )
				{
					self::$_apps[ $app->id ] = $app;
				}
			}
		}

		return self::$_apps[ $appId ];
	}

	/**
	 * Retrieves a field configuration.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The key to #__social_apps
	 * @param	int		Optional field id. If there's no field id, we assume that it's a new field being added to the form.
	 * @param	bool	If true, method will return a json string instead of an object.
	 *
	 * @return	object	The field configuration
	 */
	public function getFieldConfigParameters( $appId, $groupTabs = false, $jsonString = false )
	{
		// Note: We put this function here instead of field table because sometimes a field might not have a field id to load the parameters

		static $configParams = array();

		if( empty( $configParams[$appId] ) )
		{
			// Load json library
			$json 	= Foundry::json();

			$app	= $this->loadAppData( $appId );
			$config = $app->getManifest();

			if( $config === false )
			{
				$config = new stdClass();
			}

			// Get the default core parameters
			$defaults	= $this->getDefaultManifest();

			// Manually perform a deep array merge to carry the defaults over to the config object
			foreach( $defaults as $name => $params )
			{
				if( property_exists( $config, $name ) )
				{
					if( is_bool( $config->$name ) )
					{
						$params = $config->$name;
					}
					else
					{
						$params = (object) array_merge( (array) $params, (array) $config->$name );
					}
				}

				$config->$name = $params;
			}

			// Translate the languages in the configuration
			$this->loadLanguage( $app->group, $app->element );

			foreach( $config as $name => &$field )
			{
				$this->translateConfigParams( $field );

				if( isset( $field->subfield ) )
				{
					foreach( $field->subfields as $subname => &$subfield )
					{
						$this->translateConfigParams( $subfield );
					}
				}
			}

			$configParams[$appId] = $config;
		}

		// Make a clone to prevent pass by reference
		$data = clone $configParams[$appId];

		if( $groupTabs )
		{
			$groupedConfig = new stdClass();

			foreach( $data as $key => $value )
			{
				if( !is_bool( $value ) )
				{
					// This will enforce group to be either basic or advance
					// $type = property_exists( $value, 'group' ) && $value->group == 'advance' ? 'advance' : 'basic';

					// This will allow any group
					$type = property_exists( $value, 'group' ) ? $value->group : 'basic';

					if( !property_exists( $groupedConfig, $type ) )
					{
						$groupedConfig->$type = new stdClass();
					}

					$groupedConfig->$type->title = JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_TAB_' . strtoupper( $type ) );

					if( !property_exists( $groupedConfig->$type, 'fields' ) )
					{
						$groupedConfig->$type->fields = new stdClass();
					}

					$groupedConfig->$type->fields->$key = $value;
				}
			}

			$data = $groupedConfig;
		}

		if( $jsonString )
		{
			return $json->encode( $data );
		}

		return $data;
	}

	/**
	 * Retrieves a field configuration.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The key to #__social_apps
	 * @param	int			Optional field id. If there's no field id, we assume that it's a new field being added to the form.
	 * @return	JRegistry	Registry of the field configuration
	 */
	public function getFieldConfigValues( $appId, $fieldId = 0 )
	{
		$field = null;

		// If first parameter is object, we assume caller pass in field table
		if( is_object( $appId ) )
		{
			// Reassign appid accordingly
			$field = $appId;
			$appId = $field->app_id;
		}

		$defaults	= $this->getFieldConfigParameters( $appId );

		// If the first parameter is appId, then $field should be null by now, and if second parameter is valid, then we load the table
		if( empty( $field ) && !empty( $fieldId ) )
		{
			$field = Foundry::table( 'field' );
			$field->load( $fieldId );
		}

		// Initialise a registry first
		$params		= Foundry::registry();

		// If $field is still empty then we shouldn't get the field parameters
		if( !empty( $field ) )
		{
			// Get the params from the table
			$params		= $field->getParams();

			// Get the choices
			$choices 	= $field->getOptions();

			// Manually merge in the choices into the parameter object
			foreach( $choices as $choice => $values )
			{
				$params->set( $choice, $values );
			}
		}

		// This is to get the default values of the params and
		// merge it in as the value if the value does not exist yet
		foreach( $defaults as $name => $obj )
		{
			// Check if this name exists in the params
			if( !$params->exists( $name ) )
			{
				$default = '';

				if( is_bool( $obj ) )
				{
					$default = $obj;
				}

				if( isset( $obj->default ) )
				{
					$default = $obj->default;
				}

				$params->set( $name, $default );
			}

			if( isset( $obj->subfields ) )
			{
				foreach( $obj->subfields as $subname => $subfield )
				{
					if( !$params->exists( $name . '_' . $subname ) )
					{
						$default = isset( $subfield->default ) ? $subfield->default : '';

						$params->set( $name . '_' . $subname, $default );
					}
				}
			}
		}

		return $params;
	}

	public function getConfigHtml( $appid, $fieldid = 0 )
	{
		// Get app title
		$app = Foundry::table( 'app' );
		$app->load( $appid );
		$title = $app->title;

		// Get config parameters
		$params = $this->getFieldConfigParameters( $appid, true );

		// Get config values
		$values = $this->getFieldConfigValues( $appid, $fieldid );

		foreach( $params as $tab => &$data )
		{
			foreach( $data->fields as $name => &$field )
			{
				// Normalize the types here
				$this->normalizeConfigType( $field );

				// Normalize the subfield types too
				if( isset( $field->subfields ) )
				{
					foreach( $field->subfields as $subname => $subfield )
					{
						$this->normalizeConfigType( $subfield );
					}
				}

				// Check the values for this name
				if( !$values->exists( $name ) )
				{
					$values->set( $name, '' );
				}
			}
		}

		$theme = Foundry::themes();

		$theme->set( 'title', $title );
		$theme->set( 'params', $params );
		$theme->set( 'values', $values );
		$theme->set( 'tabs', array( 'basic', 'core', 'view', 'advance' ) );

		return $theme->output( 'admin/profiles/form.fields.config' );
	}

	private function normalizeConfigType( &$field )
	{
		$type = 'boolean';

		if( isset( $field->type ) )
		{
			switch( $field->type ) {
				case 'input':
				case 'text':
					$type = 'input';
				break;

				case 'dropdown':
				case 'list':
				case 'select':
					$type = 'dropdown';
				break;

				case 'editors':
				case 'checkbox':
				case 'radio':
				case 'textarea':
				case 'choices':
					$type = $field->type;
				break;

				default:
					$type = 'boolean';
				break;
			}
		}

		$field->type = $type;
	}

	/*
	 * Returns html formatted data for validations
	 *
	 * @param 	string 	$element 	The field element.
	 * @return 	string 	HTML formatted values.
	 */
	public function renderValidations( $element )
	{
		$path	= SOCIAL_MEDIA . DS . SOCIAL_APPS_TYPE_FIELDS . DS . strtolower( $element ) . DS . 'tmpl' . DS . 'params.xml';

		if( !Foundry::get( 'Files' )->exists( $path ) )
		{
			return false;
		}

		$parser	= JFactory::getXMLParser( 'Simple' );
		$parser->loadFile( $path );

		if( !$parser->document->getElementByPath( 'validations' ) )
		{
			return false;
		}

		$validations	= $parser->document->getElementByPath( 'validations' )->children();

		return Foundry::get( 'Themes' )->set( 'validations' , $validations )->output( 'admin.profiles.fields_validation' );
	}

	/**
	 * Loads a specific language file given the field's element and group.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The group's name.
	 * @param	string		The element's name.
	 * @return
	 */
	public function loadLanguage( $group , $element )
	{
		$lang 		= JFactory::getLanguage();
		$file 		= 'plg_fields_' . $group . '_' . $element;

		// Load the language file.
		$lang->load( $file , JPATH_ROOT . '/administrator' );
	}

	private function translateConfigParams( &$field )
	{
		// Only try to JText the label field if it exists.
		if( isset( $field->label ) )
		{
			$field->label	= JText::_( $field->label );
		}

		// Only try to JText the tooltip field if it exists.
		if( isset( $field->tooltip ) )
		{
			$field->tooltip	= JText::_( $field->tooltip );
		}

		// Only try to JText the default value if default exist and it is a string
		if( isset( $field->default ) && is_string( $field->default ) )
		{
			$field->default = JText::_( $field->default );
		}

		// If there are options set, we need to jtext them as well.
		if( isset( $field->option ) )
		{
			$field->option 	= Foundry::makeArray( $field->option );

			foreach( $field->option as &$option )
			{
				$option->label 	= JText::_( $option->label );
			}
		}
	}
}
