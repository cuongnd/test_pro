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


/**
 * Child classes must inherit these otherwise
 * fields would not work correctly.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
abstract class SocialFieldItem
{
	/**
	 * Holds the object relation mapping for the field item.
	 * @var	SocialTableField
	 */
	protected $field 	= null;

	/**
	 * Holds the field group name.
	 * @var	string
	 */
	protected $group	= null;

	/**
	 * Holds the field element name.
	 * @var	string
	 */
	protected $element	= null;

	/**
	 * Holds the field parameter object.
	 * @var	JParameter
	 */
	public $params		= null;

	/**
	 * Holds the field configuration object.
	 * @var	JParameter
	 */
	public $config		= null;

	/**
	 * Holds the SocialThemes object.
	 * @var	SocialThemes
	 */
	public $theme		= null;

	/**
	 * Determines if this field has an error.
	 * @var	bool
	 */
	public $hasErrors	= false;

	/**
	 * Stores any error messages.
	 * @var	string
	 */
	public $error 		= null;

	/**
	 * Stores a list of already initiated models.
	 * @var	Array
	 */
	protected $models 	= array();

	/**
	 * Stores a list of already initiated views.
	 * @var	Array
	 */
	protected $views 	= array();

	/**
	 * Stores the state of tables being loaded
	 * @var	bool
	 */
	protected $tables = false;

	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $config = array() )
	{
		$this->init( $config );

		// Load field's language file.
		$key 	= 'plg_fields_' . $this->group . '_' . $this->element;

		Foundry::language()->load( $key , JPATH_ADMINISTRATOR );

		// Construct params.
		$this->params 		= $this->getParams();
	}

	/**
	 * Generic method to retrieve data of a field
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string
	 */
	public function getFieldData()
	{
		return $this->field->data;
	}

	/**
	 * Get the params from the field.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getParams()
	{
		// Default params
		$params 	= Foundry::registry();

		if( isset( $this->field ) )
		{
			$params 	= $this->field->getParams();
		}

		return $params;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	final public function init( $properties = array() )
	{
		if( empty( $properties ) )
		{
			return;
		}

		foreach( $properties as $key => $val )
		{
			// Make this variable available in property scope.
			$this->$key 	= $val;

			// Make this variable available in theme scope.
			$this->set( $key , $val );
		}
	}

	/*
	 * Method will be invoked when viewing a particular
	 * node on the site.
	 */
	public function onDisplay( $user )
	{
	}

	/*
	 * Method will be invoked when an export is executed.
	 * Child must return the appropriate values to be exported.
	 */
	public function onExport()
	{
	}

	/*
	 * Method will be invoked when an import is executed.
	 * Child must return the appropriate values to be exported.
	 */
	public function onImport()
	{
	}

	/*
	 * Retrieves a list of child options for the custom field (Optional)
	 *
	 * @param 	null
	 * @return 	Array 	An array of SocialTableFieldOptions items.
	 */
	final public function getOptions( $key = null )
	{
		return $this->field->getOptions( $key );
	}

	/**
	 * Responsible to help fields to output theme files.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	final public function display( $templateFile = null )
	{
		// Initialize the theme object for the current app.
		if( !$this->theme )
		{
			$this->theme	= Foundry::get( 'Themes' );
		}

		// If templateFile is null, then we generate it based on the event that is triggered
		if( is_null( $templateFile ) && !empty( $this->event ) )
		{
			$templateFile = strtolower( substr( $this->event, 2 ) );
		}

		// 1. Check if theme field exist in field's tmpl
		// 2. Fallback to provided template in site themes

		$namespace = '';

		// Field template file
		$fieldFile 	= 'fields/' . $this->group . '/' . $this->element . '/' . $templateFile;

		// Site general template file
		$siteFile	= 'site/fields/' . $templateFile;

		if( JFile::exists( $this->theme->getTemplate( $fieldFile )->file ) )
		{
			$namespace = $fieldFile;
		}
		elseif( JFile::exists( $this->theme->getTemplate( $siteFile )->file ) )
		{
			// If fallback site themes is used, then we futher check if there is subthemes
			// 1. fields/group/element/template_content
			// 2. fields/group/element/content
			// 3. site/fields/content

			$namespace = $siteFile;

			$subNamespace = '';

			// Field sub content file specific to event
			$subFieldFile = 'fields/' . $this->group . '/' . $this->element . '/' . $templateFile . '_content';

			// Field generic content file
			$subFieldContent = 'fields/' . $this->group . '/' . $this->element . '/content';

			// Fallback content file from site themes
			$subSiteContent = 'site/fields/content';

			if( JFile::exists( $this->theme->getTemplate( $subFieldFile )->file ) )
			{
				$subNamespace = $subFieldFile;
			}
			elseif( JFile::exists( $this->theme->getTemplate( $subFieldContent )->file ) )
			{
				$subNamespace = $subFieldContent;
			}
			else
			{
				$subNamespace = $subSiteContent;
			}

			$this->theme->set( 'subNamespace', $subNamespace );
		}
		else
		{
			echo JText::_( 'COM_EASYSOCIAL_FIELDS_NO_THEME_FILE_FOUND' ) . ': ' . $fieldFile;
			return;
		}

		echo $this->theme->output( $namespace );
	}

	/**
	 * Set the field with some errors
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The error message
	 * @return
	 */
	public function setError( $message = null )
	{
		// Set the field to report an error.
		$this->hasErrors 	= true;

		// Set the error message.
		$this->error 		= $message;
	}

	/**
	 * Helper method to determine if this field is required.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool
	 */
	public function isRequired()
	{
		return $this->field->isRequired();
	}

	/**
	 * Determines if there's an error in this field.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function hasError()
	{
		return (bool) $this->hasErrors;
	}

	/**
	 * Get error message
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string
	 */
	public function getError( $errors = null )
	{
		// If array of errors is passed in, we search the error from this array instead

		if( !is_null( $errors ) )
		{
			return !empty( $errors[$this->inputName] ) ? $errors[$this->inputName] : false;
		}

		return $this->error;
	}

	/**
	 * Sets a variable to the theme object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function set( $key , $var )
	{
		if( !$this->theme )
		{
			$this->theme	= Foundry::get( 'Themes' );
		}

	    $this->theme->set( $key , $var );
	}

	public function model( $name = null )
	{
		if( empty( $name ) )
		{
			$name = $this->element;
		}

		if( !isset( $this->models[$name] ) )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/models';

			$classname = 'SocialFieldModel' . ucfirst( $this->group ) . ucfirst( $name );

			if( !class_exists( $classname ) )
			{
				if( !JFile::exists( $base . '/' . $name . '.php' ) )
				{
					return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_MODEL_DOES_NOT_EXIST', $name ) );
				}

				require_once( $base . '/' . $name . '.php' );
			}

			if( !class_exists( $classname ) )
			{
				return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_CLASS_DOES_NOT_EXIST', $classname ) );
			}

			$model = new $classname( $this->group, $this->element );

			$this->models[$name] = $model;
		}

		return $this->models[$name];
	}

	public function table( $name = null, $prefix = '' )
	{
		if( !$this->tables )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/tables';

			JTable::addIncludePath( $base );

			$this->tables = true;
		}

		if( empty( $name ) )
		{
			$name = $this->element;
		}

		$prefix	= empty( $prefix ) ? 'SocialFieldTable' . ucfirst( $this->group ) : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	public function view( $name = null )
	{
		if( empty( $name ) )
		{
			$name = $this->element;
		}

		if( !isset( $this->views[$name] ) )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/views';

			$classname = 'SocialFieldView' . ucfirst( $this->group ) . ucfirst( $name );

			if( !class_exists( $classname ) )
			{
				if( !JFile::exists( $base . '/' . $name . '.php' ) )
				{
					return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_VIEW_DOES_NOT_EXIST', $name ) );
				}

				require_once( $base . '/' . $name . '.php' );
			}

			if( !class_exists( $classname ) )
			{
				return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_CLASS_DOES_NOT_EXIST', $classname ) );
			}

			$view = new $classname( $this->group, $this->element );

			$this->views[$name] = $view;
		}

		return $this->views[$name];
	}

	public function allowedPrivacy( $user )
	{
		$my = Foundry::user();
		$lib = Foundry::privacy( $my->id );

		$result = $lib->validate( 'core.view', $this->field->id, SOCIAL_TYPE_FIELD, $user->id );

		return $result;
	}

	public function escape( $text )
	{
		return $this->theme->html( 'string.escape', $text );
	}
}

Foundry::import( 'admin:/includes/model' );

class SocialFieldModel extends EasySocialModel
{
	protected $group = null;
	protected $element = null;

	private $models = array();

	private $views = array();

	private $tables = null;

	public function __construct( $group, $element )
	{
		$this->group = $group;
		$this->element = $element;
	}

	public function model( $name = null )
	{
		if( empty( $name ) )
		{
			$name = $this->element;
		}

		if( !isset( $this->models[$name] ) )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/models';

			$classname = 'SocialFieldModel' . ucfirst( $this->group ) . ucfirst( $name );

			if( !class_exists( $classname ) )
			{
				if( !JFile::exists( $base . '/' . $name . '.php' ) )
				{
					return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_MODEL_DOES_NOT_EXIST', $name ) );
				}

				require_once( $base . '/' . $name . '.php' );
			}

			if( !class_exists( $classname ) )
			{
				return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_CLASS_DOES_NOT_EXIST', $classname ) );
			}

			$model = new $classname( $this->group, $this->element );

			$this->models[$name] = $model;
		}

		return $this->models[$name];
	}

	public function table( $name = null, $prefix = '' )
	{
		if( !$this->tables )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/tables';

			JTable::addIncludePath( $base );

			$this->tables = true;
		}

		if( empty( $name ) )
		{
			$name = $this->element;
		}

		$prefix	= empty( $prefix ) ? 'SocialFieldTable' . ucfirst( $this->group ) : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	public function view( $name = null )
	{
		if( empty( $name ) )
		{
			$name = $this->element;
		}

		if( !isset( $this->views[$name] ) )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/views';

			$classname = 'SocialFieldView' . ucfirst( $this->group ) . ucfirst( $name );

			if( !class_exists( $classname ) )
			{
				if( !JFile::exists( $base . '/' . $name . '.php' ) )
				{
					return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_VIEW_DOES_NOT_EXIST', $name ) );
				}

				require_once( $base . '/' . $name . '.php' );
			}

			if( !class_exists( $classname ) )
			{
				return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_CLASS_DOES_NOT_EXIST', $classname ) );
			}

			$view = new $classname( $this->group, $this->element );

			$this->views[$name] = $view;
		}

		return $this->views[$name];
	}
}

class SocialFieldView
{
	protected $field = null;

	protected $group = null;
	protected $element = null;

	private $theme = null;

	private $models = array();

	private $views = array();

	private $tables = null;

	public $params = null;
	public $inputName = null;

	public function __construct( $group, $element )
	{
		$this->group = $group;
		$this->element = $element;

		$this->theme = Foundry::themes();
	}

	public function init( $field )
	{
		$this->field = $field;

		$this->params = Foundry::fields()->getFieldConfigValues( $field );

		$this->inputName = SOCIAL_FIELDS_PREFIX . $field->id;
	}

	public function model( $name = null )
	{
		if( empty( $name ) )
		{
			$name = $this->element;
		}

		if( !isset( $this->models[$name] ) )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/models';

			$classname = 'SocialFieldModel' . ucfirst( $this->group ) . ucfirst( $name );

			if( !class_exists( $classname ) )
			{
				if( !JFile::exists( $base . '/' . $name . '.php' ) )
				{
					return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_MODEL_DOES_NOT_EXIST', $name ) );
				}

				require_once( $base . '/' . $name . '.php' );
			}

			if( !class_exists( $classname ) )
			{
				return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_CLASS_DOES_NOT_EXIST', $classname ) );
			}

			$model = new $classname( $this->group, $this->element );

			$this->models[$name] = $model;
		}

		return $this->models[$name];
	}

	public function table( $name = null, $prefix = '' )
	{
		if( !$this->tables )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/tables';

			JTable::addIncludePath( $base );

			$this->tables = true;
		}

		if( empty( $name ) )
		{
			$name = $this->element;
		}

		$prefix	= empty( $prefix ) ? 'SocialFieldTable' . ucfirst( $this->group ) : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	public function view( $name = null )
	{
		if( empty( $name ) )
		{
			$name = $this->element;
		}

		if( !isset( $this->views[$name] ) )
		{
			$base = SOCIAL_FIELDS . '/' . $this->group . '/' . $this->element . '/views';

			$classname = 'SocialFieldView' . ucfirst( $this->group ) . ucfirst( $name );

			if( !class_exists( $classname ) )
			{
				if( !JFile::exists( $base . '/' . $name . '.php' ) )
				{
					return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_VIEW_DOES_NOT_EXIST', $name ) );
				}

				require_once( $base . '/' . $name . '.php' );
			}

			if( !class_exists( $classname ) )
			{
				return JError::raiseError( 500, JText::sprintf( 'COM_EASYSOCIAL_FIELDS_CLASS_DOES_NOT_EXIST', $classname ) );
			}

			$view = new $classname( $this->group, $this->element );

			$this->views[$name] = $view;
		}

		return $this->views[$name];
	}

	public function set( $name, $value )
	{
		return $this->theme->set( $name, $value );
	}

	public function display( $name = 'default' )
	{
		$path 	= 'fields/' . $this->group . '/' . $this->element . '/' . $name;

		return $this->theme->output( $path );
	}

	public function redirect( $uri )
	{
		static $app = null;

		if( empty( $app ) )
		{
			$app = JFactory::getApplication();
		}

		$app->redirect( $uri );
		$app->close();
	}
}
