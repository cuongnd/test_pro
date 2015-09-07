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
 * All apps should always inherit from the SocialAppItem class
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
abstract class SocialAppItem
{
	protected $theme	= null;

	public $element = null;
	public $group   = null;
	
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		// Load default items for the app
		if( $this->element && $this->group )
		{
			Foundry::language()->loadApp( $this->group , $this->element );
		}
		
		// Initialize the theme object for the current app.
		$this->theme	= Foundry::themes();
	}

	/**
	 * Executes when a trigger is called.
	 *
	 * @since	1.0
	 * @param	string	The event name.
	 * @param	Array	An array of arguments
	 * @access	public
	 */
	public final function update( $eventName , &$args )
	{
		$paths 	= array();

		$paths[ 'tables' ]	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element . '/tables';

		$this->paths 		= $paths;
		
		if( method_exists( $this , $eventName ) )
		{
			return call_user_func_array( array( $this , $eventName ) , $args );
		}

		return false;
	}

	/**
	 * Retrieves a JTable object. This simplifies the caller from manually adding include path all the time.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$name		The table's name without the prefix.
	 * @param	string	$prefix		Optional prefixed table name.
	 *
	 * @return	JTable				The JTable object.
	 */
	public function getTable( $name , $prefix = '' )
	{
		JTable::addIncludePath( $this->paths[ 'tables' ] );

		$prefix	= empty( $prefix ) ? ucfirst( $this->element ) . 'Table' : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	/**
	 * Responsible to help apps to output theme files.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function display( $file )
	{
		// Since this is a field item, we always want to prefix with the standard POSIX format.
		$namespace 	= 'themes:/apps/' . $this->group . '/' . $this->element . '/' . $file;

		return $this->theme->output( $namespace );
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
		$this->theme->set( $key , $var );
	}

	/**
	 * Retrieves the app table row
	 *
	 * @since	1.0
	 * @access	public
	 * @return	
	 */
	public function getApp()
	{
		$app 	= Foundry::table( 'App' );
		$app->load( array( 'element' => $this->element , 'group' => $this->group ) );

		return $app;
	}

	/**
	 * Retrieves the params for this app
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	SocialRegistry
	 */
	public function getParams()
	{
		static $params 	= array();

		$key 	= $this->element . $this->group;

		if( !isset( $params[ $key ] ) )
		{
			$app 		= $this->getApp();
			$registry	= $app->getParams();
			
			$params[ $key ]	= $registry;

		}

		return $params[ $key ];
	}
}

/**
 * Main class file that should be extended by application views.
 *
 * @since	1.0
 * @access	public
 */
class SocialAppsController
{
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
	 * Stores a list of default paths for this app.
	 * @var	Array
	 */
	protected $paths 	= array();

	/**
	 * The app's group.
	 * @var string
	 */
	protected $group 	= null;

	/**
	 * The app's element name.
	 * @var string
	 */
	protected $element 	= null;

	public function __construct( $appGroup , $appElement )
	{
		$this->element 	= $appElement;
		$this->group 	= $appGroup;

		// Load language file for this
		Foundry::language()->loadApp( $appGroup , $appElement );

		$this->paths 	= array(
								'models'	=> SOCIAL_APPS . '/' . $appGroup . '/' . $appElement . '/models',
								'tables'	=> SOCIAL_APPS . '/' . $appGroup . '/' . $appElement . '/tables',
								'views'		=> SOCIAL_APPS . '/' . $appGroup . '/' . $appElement . '/views',
								'config'	=> SOCIAL_APPS . '/' . $appGroup . '/' . $appElement . '/config',
							);
	}

	/**
	 * Retrieves a JTable object. This simplifies the caller from manually adding include path all the time.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$name		The table's name without the prefix.
	 * @param	string	$prefix		Optional prefixed table name.
	 *
	 * @return	JTable				The JTable object.
	 */
	public function getTable( $name , $prefix = '' )
	{
		JTable::addIncludePath( $this->paths[ 'tables' ] );

		$prefix	= empty( $prefix ) ? ucfirst( $this->element ) . 'Table' : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	/**
	 * Retrieves the app object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getApp()
	{
		$app 	= Foundry::table( 'App' );
		$app->load( array( 'element' => $this->element , 'group' => $this->group ) );

		return $app;
	}

	/**
	 * Helper function to assist child classes to retrieve a model object.
	 *
	 * @since 	1.0
	 * @access	public
	 * @param 	string 	$modelName 	The name of the model.
	 **/
	public function getModel( $name )
	{
		if( !isset( $this->models[ $name ] ) )
		{
			$className	= $name . 'Model';

			if( !class_exists( $className ) )
			{
				jimport( 'joomla.application.component.model' );

				// @TODO: Properly test if the file exists before including it.
				JLoader::import( strtolower( $name ) , $this->paths[ 'models' ] );
			}

			// If the class still doesn't exist, let's just throw an error here.
			if( !class_exists( $className ) )
			{
				JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

				return JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_MODEL_NOT_FOUND' , $className ) );
			}

			$model 					= new $className( $name );
			$this->models[ $name ]	= $model;
		}

		return $this->models[ $name ];
	}
}


/**
 * Main class file that should be extended by application views.
 *
 * @since	1.0
 * @access	public
 */
class SocialAppsView
{
	// Stores a list of already initiated models.
	protected $models 	= array();

	// Stores a list of default paths for this app.
	protected $paths 	= array();

	/**
	 * The current view's name.
	 * @var	string
	 */
	protected $viewName	= '';

	public function __construct( SocialTableApp $app , $viewName )
	{
		// The ORM for the app.
		$this->app 		= $app;

		// Set the view's name.
		$this->viewName	= $viewName;

		$this->paths 	= array(
								'models'	=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/models',
								'tables'	=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/tables',
								'views'		=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/views',
								'config'	=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/config',
							);


		// Allow themes to be available to the caller.
		$this->theme 	= Foundry::themes();

		// Load app's language file.
		Foundry::language()->loadApp( $app->group , $app->element );
		
		// Allow app to be available in the theme.
		$this->set( 'app'	, $app );
	}

	/**
	 * Retrieves a JTable object. This simplifies the caller from manually adding include path all the time.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$name		The table's name without the prefix.
	 * @param	string	$prefix		Optional prefixed table name.
	 *
	 * @return	JTable				The JTable object.
	 */
	public function getTable( $name , $prefix = '' )
	{
		JTable::addIncludePath( $this->paths[ 'tables' ] );

		$prefix	= empty( $prefix ) ? ucfirst( $this->app->element ) . 'Table' : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	/**
	 * Helper function to assist child classes to retrieve a model object.
	 *
	 * @since 	1.0
	 * @access	public
	 * @param 	string 	$modelName 	The name of the model.
	 **/
	public function getModel( $name )
	{
		if( !isset( $this->models[ $name ] ) )
		{
			$className	= $name . 'Model';

			if( !class_exists( $className ) )
			{
				jimport( 'joomla.application.component.model' );

				// @TODO: Properly test if the file exists before including it.
				JLoader::import( strtolower( $name ) , $this->paths[ 'models' ] );
			}

			// If the class still doesn't exist, let's just throw an error here.
			if( !class_exists( $className ) )
			{
				JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

				return JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_MODEL_NOT_FOUND' , $className ) );
			}

			$model 					= new $className( $name );
			$this->models[ $name ]	= $model;
		}

		return $this->models[ $name ];
	}

	/**
	 * Retrieves the user params
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getUserParams( $userId )
	{
		$map 	= Foundry::table( 'AppsMap' );
		$map->load( array( 'app_id' => $this->app->id , 'uid' => $userId ) );

		$registry	= Foundry::registry( $map->params );

		return $registry;
	}
	
	/**
	 * Allows overriden objects to redirect the current request only when in html mode.
	 *
	 * @access	public
	 * @param	string	$uri 	The raw uri string.
	 * @param	boolean	$route	Whether or not the uri should be routed
	 */
	public function redirect( $uri , $route = true )
	{
		if( $route )
		{
			// Since redirects does not matter of the xhtml codes, we can just ignore this.
			$uri    = FRoute::_( $uri , false );
		}

		$this->app->redirect( $uri );
		$this->app->close();
	}

	/**
	 * Main method to help caller to display contents from their theme files.
	 * The method automatically searches for {%APP_NAME%/themes/%CURRENT_THEME%/%FILE_NAME%}
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The template file name.
	 * @return	
	 */
	public function display( $tpl = null , $docType = null )
	{
		$format		= JRequest::getWord( 'format' , 'html' );

		// Since the $tpl now only contains the name of the file, we need to be smart enough to determine the full location.
		$template 	= 'themes:/apps/' . $this->app->group . '/' . $this->app->element . '/' . $tpl;

		return $this->theme->output( $template );
	}

	public function set( $key , $value = null )
	{
		return $this->theme->set( $key , $value );
	}
}

/**
 * Main class file that should be extended by application widgets.
 *
 * @since	1.0
 * @access	public
 */
class SocialAppsWidgets
{
	// Stores a list of already initiated models.
	protected $models 	= array();

	// Stores a list of default paths for this app.
	protected $paths 	= array();


	/**
	 * The current view's name.
	 * @var	string
	 */
	protected $viewName	= '';

	public function __construct( SocialTableApp $app , $viewName )
	{
		// The ORM for the app.
		$this->app 		= $app;

		// Set the view's name.
		$this->viewName	= $viewName;

		$this->paths 	= array(
								'models'	=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/models',
								'tables'	=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/tables',
								'views'		=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/views',
								'config'	=> SOCIAL_APPS . '/' . $app->group . '/' . $app->element . '/config',
							);


		// Allow themes to be available to the caller.
		$this->theme 	= Foundry::themes();

		// Allow app to be available in the theme.
		$this->set( 'app'	, $app );
	}

	/**
	 * Retrieves a JTable object. This simplifies the caller from manually adding include path all the time.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$name		The table's name without the prefix.
	 * @param	string	$prefix		Optional prefixed table name.
	 *
	 * @return	JTable				The JTable object.
	 */
	public function getTable( $name , $prefix = '' )
	{
		JTable::addIncludePath( $this->paths[ 'tables' ] );

		$prefix	= empty( $prefix ) ? ucfirst( $this->app->element ) . 'Table' : $prefix;

		$table	= JTable::getInstance( $name , $prefix );

		return $table;
	}

	/**
	 * Helper function to assist child classes to retrieve a model object.
	 *
	 * @since 	1.0
	 * @access	public
	 * @param 	string 	$modelName 	The name of the model.
	 **/
	public function getModel( $name )
	{
		if( !isset( $this->models[ $name ] ) )
		{
			$className	= $name . 'Model';

			if( !class_exists( $className ) )
			{
				jimport( 'joomla.application.component.model' );

				// @TODO: Properly test if the file exists before including it.
				JLoader::import( strtolower( $name ) , $this->paths[ 'models' ] );
			}

			// If the class still doesn't exist, let's just throw an error here.
			if( !class_exists( $className ) )
			{
				JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

				return JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_MODEL_NOT_FOUND' , $className ) );
			}

			$model 					= new $className( $name );
			$this->models[ $name ]	= $model;
		}

		return $this->models[ $name ];
	}

	/**
	 * Allows overriden objects to redirect the current request only when in html mode.
	 *
	 * @access	public
	 * @param	string	$uri 	The raw uri string.
	 * @param	boolean	$route	Whether or not the uri should be routed
	 */
	public function redirect( $uri , $route = true )
	{
		if( $route )
		{
			// Since redirects does not matter of the xhtml codes, we can just ignore this.
			$uri    = FRoute::_( $uri , false );
		}

		$this->app->redirect( $uri );
		$this->app->close();
	}

	/**
	 * Main method to help caller to display contents from their theme files.
	 * The method automatically searches for {%APP_NAME%/themes/%CURRENT_THEME%/%FILE_NAME%}
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The template file name.
	 * @return	
	 */
	public function display( $tpl = null , $docType = null )
	{
		$format		= JRequest::getWord( 'format' , 'html' );

		// Since the $tpl now only contains the name of the file, we need to be smart enough to determine the full location.
		$template 	= 'themes:/apps/' . $this->app->group . '/' . $this->app->element . '/' . $tpl;

		return $this->theme->output( $template );
	}

	/**
	 * Retrieves the user params
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getUserParams( $userId )
	{
		$map 	= Foundry::table( 'AppsMap' );
		$map->load( array( 'app_id' => $this->app->id , 'uid' => $userId ) );

		$registry	= Foundry::registry( $map->params );

		return $registry;
	}

	public function set( $key , $value = null )
	{
		return $this->theme->set( $key , $value );
	}
}