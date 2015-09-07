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

// Import dependencies.
Foundry::import( 'admin:/includes/apps/dependencies' );

/**
 *
 * Handles applications installed on the site.
 *
 * @since	1.0
 * @access	public
 *
 */
class SocialApps
{
	/**
	 * Static variable for caching.
	 * @var	SocialApps
	 */
	private static $instance = null;

	// Store apps locally.
	private $apps 	= array();

	/**
	 * Object initialisation for the class. Apps should be initialized using
	 * Foundry::getInstance( 'Apps' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialApps	The SocialApps object.
	 */
	public static function getInstance()
	{
		if( !self::$instance )
		{
			self::$instance	= new self();
		}

		return self::$instance;
	}

	/**
	 * Loads all app language files.
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function loadAllLanguages()
	{
		jimport( 'joomla.filesystem.folder' );

		// Get list of apps that should be loaded.
		$model 		= Foundry::model( 'Apps' );

		// @TODO: MUST FIX THIS TO NOT USE ANY LIMITS
		$apps		= $model->setLimit( 10000 )->getApps( array( 'type' => 'apps' , 'state' => SOCIAL_STATE_PUBLISHED ) );

		if( !$apps )
		{
			return;
		}
		
		foreach( $apps as $app )
		{
			$this->loadAppLanguage( $app );
		}
	}

	/**
	 * Loads all app language files.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object $app	The app object
	 */
	public function loadAppLanguage( $app )
	{
		Foundry::language()->load( 'plg_app_' . $app->group . '_' . $app->element , SOCIAL_JOOMLA_ADMIN );
	}

	/**
	 * Load a list of applications.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The type of applications to load. (E.g: users , groups )
	 * @param	Array 		An array of application that we should load. (E.g: joomla_username, joomla_password)
	 * @param
	 */
	public function load( $group , $inclusion = array() )
	{
		static $loaded	= array();

		// Singleton pattern where we should only load necessary items.
		if( !isset( $loaded[ $group ] ) )
		{
			// Get a list of applications that should be rendered for this app type.
			$model 		= Foundry::model( 'Apps' );

			// Get a list of apps
			$options 	= array( 'type' => 'apps' , 'group' => $group , 'state' => SOCIAL_STATE_PUBLISHED );
			$apps 		= $model->getApps( $options );

			if( $apps )
			{
				foreach( $apps as $app )
				{
					$this->loadApp( $app );
				}

				$this->apps[ $group ] = $apps;

				$loaded[ $group ]	= true;
			}
			else
			{
				$loaded[ $group ]	= false;
			}
		}

		return $loaded[ $group ];
	}

	/**
	 * Responsible to render the widget on specific profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function renderWidgets( $group , $view , $position , $args = array() )
	{
		// Get a list of apps that has widget layout.
		$model 		= Foundry::model( 'Apps' );

		// Get the user
		$user 		= isset( $args[ 0 ] ) ? $args[ 0 ] : Foundry::user();

		$options 	= array( 'uid' => $user->id , 'key' => $group , 'widget' => SOCIAL_STATE_PUBLISHED , 'group' => $group , 'limit' => null );

		$apps		= $model->getApps( $options );

		if( !$apps )
		{
			return false;
		}

		// Set the initial path of the apps
		$folder 	= SOCIAL_APPS . '/' . $group;

		// Initialize default contents
		$contents 	= '';

		// Go through each of these apps that are widgetable and see if there is a .widget file.
		foreach( $apps as $app )
		{
			// Check if the widget folder exists for this view.
			$file 	= $folder . '/' . $app->element . '/widgets/' . $view . '/view.html.php';

			if( !JFile::exists( $file ) )
			{
				continue;
			}

			require_once( $file );

			$className 	= ucfirst( $app->element ) . 'Widgets' . ucfirst( $view );

			// Check if the class exists in this context.
			if( !class_exists( $className ) )
			{
				continue;
			}

			$widgetObj	= new $className( $app , $view );

			// Check if the method exists in this context.
			if( !method_exists( $widgetObj , $position ) )
			{
				continue;
			}

			// Load language for this app
			$this->loadAppLanguage( $app );

			ob_start();
			call_user_func_array( array( $widgetObj , $position ) , $args );
			$output 	= ob_get_contents();
			ob_end_clean();

			$contents .= $output;
		}

		// If nothing to display, just return false.
		if( empty( $contents ) )
		{
			return false;
		}

		// We need to wrap the app contents with our own wrapper.
		$theme 		= Foundry::themes();
		$theme->set( 'contents' , $contents );
		$contents	= $theme->output( 'site/apps/default.widget.' . strtolower( $view ) );

		return $contents;
	}

	/**
	 * Responsible to render an application's contents.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string			The view to render.
	 * @param	SocialTableApp	The application table object.
	 * @param	Array			An array of key value pairs to pass to the view as arguments.
	 * @param
	 */
	public function renderView( $viewType , $viewName , SocialTableApp $app , $args = array() )
	{
		// If application id is not provided, stop execution here.
		if( !$app->id )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'APPS: Invalid application id [' . $app->id . '] provided.' );
			return JText::_( 'COM_EASYSOCIAL_APPS_INVALID_ID_PROVIDED' );
		}

		// Construct the apps path.
		$path 	= SOCIAL_APPS . '/' . $app->group . '/' . $app->element;

		// Construct the relative file path based on the current view request.
		$file 	= 'views/' . $viewName . '/view.html.php';

		// Construct the absolute path now.
		$absolutePath 	= $path . '/' . $file;

		// Check if the view really exists.
		jimport( 'joomla.filesystem.file' );

		if( !JFile::exists( $absolutePath ) )
		{
			return JText::sprintf( 'COM_EASYSOCIAL_APPS_VIEW_DOES_NOT_EXIST' , $viewName );
		}

		require_once( $absolutePath );

		// Construct the class name for this view.
		$className 	= ucfirst( $app->element ) . 'View' . ucfirst( $viewName );

		if( !class_exists( $className ) )
		{
			return JText::sprintf( 'COM_EASYSOCIAL_APPS_CLASS_DOES_NOT_EXIST' , $className );
		}

		// Load the language file for the app.
		$namespace 	= 'plg_app_' . $app->group . '_' . $app->element;

		Foundry::language()->load( $namespace , SOCIAL_JOOMLA_ADMIN );

		// Instantiate the new class since we need to render it.
		$viewObj 	= new $className( $app , $viewName );

		// Get the contents.
		ob_start();
		call_user_func_array( array( $viewObj , 'display' ) , $args );
		$contents 	= ob_get_contents();
		ob_end_clean();

		// We need to wrap the app contents with our own wrapper.
		$theme 		= Foundry::themes();
		$theme->set( 'contents' , $contents );
		$contents	= $theme->output( 'site/apps/default.' . strtolower( $viewType ) . '.wrapper' );

		return $contents;
	}

	/**
	 * Responsible to attach the application into the SocialDispatcher object.
	 * In short, it does the requiring of files here.
	 *
	 * @since	1.0
	 * @access	private
	 * @param	SocialTableApp	The application ORM.
	 * @return	bool
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	private function loadApp( SocialTableApp &$app )
	{
		static $loadedApps	= array();

		// Application type and element should always be in lowercase.
		$group 		= strtolower( $app->group );
		$element	= strtolower( $app->element );

		if( !isset( $loadedApps[ $group ][ $element ] ) )
		{
			// Get dispatcher object.
			$dispatcher	= Foundry::getInstance( 'Dispatcher' );

			// Application trigger file paths.
			$filePath 	= SOCIAL_APPS . '/' . $group . '/' . $element . '/' . $element . '.php';

			// If file doesn't exist, skip the entire block.
			if( !JFile::exists( $filePath ) )
			{
				$loadedApps[ $group ][ $element ]	= false;

				return $loadedApps[ $group ][ $element ];
			}

			// Assuming that the file exists here (It should)
			require_once( $filePath );

			$className		= 'Social' . ucfirst( $group ) . 'App' . ucfirst( $element );

			// Load the language file for the app.
			Foundry::language()->loadApp( $group , $element );
			
			// If the class doesn't exist in this context,
			// the application might be using a different class. Ignore this.
			if( !class_exists( $className ) )
			{
				//@TODO: Error logging
				$loadedApps[ $group ][ $element ]	= false;
				return $loadedApps[ $group ][ $element ];
			}

			$appObj		= new $className();
			$appObj->group		= $group;
			$appObj->element	= $app->element;

			// Attach the application into the observer list.
			$dispatcher->attach( $group , $app->element , $appObj );

			// Add a state for this because we know it has already been loaded.
			$loadedApps[ $group ][ $element ]	= true;
		}

		return $loadedApps[ $group ][ $element ];
	}

	public function getCallable( $namespace )
	{
		$path = '';

		$class = null;

		$className = '';

		$parts = explode( '/', $namespace );

		$location = array_shift( $parts );

		$method = array_pop( $parts );

		if( $location == 'site' || $location == 'admin' )
		{
			list( $type, $file ) = $parts;

			$path = $location == 'admin' ? SOCIAL_ADMIN : SOCIAL_SITE;

			$path .=  '/' . $type . '/' . $file;

			switch( $type )
			{
				case 'controllers':
				case 'models':
					$path .= '.php';
				break;
				case 'views':
					$path .= '/view.html.php';
				break;
			}

			$className = 'EasySocial' . ucfirst( rtrim( $type, 's' ) ) . ucfirst( $file );
		}

		if( $location == 'apps' )
		{
			list( $group, $element, $type, $file ) = $parts;

			$path = SOCIAL_APPS . '/' . $group . '/' . $element . '/' . $type . '/';

			switch( $type )
			{
				case 'controllers':
				case 'models':
					$path .= $file . '.php';
				break;
				case 'views':
					$path .=  'view.html.php';
			}

			$className = ucfirst( $element ) . ucfirst( trim( $type, 's' ) ) . ucfirst( $file );
		}

		if( $location == 'fields' )
		{
			list( $group, $element ) = $parts;

			$path = SOCIAL_FIELDS . '/' . $group . '/' . $element . '/' . $element . '.php';

			$className = 'SocialFields' . ucfirst( $group ) . ucfirst( $element );
		}

		if( !JFile::exists( $path ) )
		{
			return false;
		}

		include_once( $path );

		if( !class_exists( $className ) )
		{
			return false;
		}

		if( $location == 'admin' || $location == 'site' )
		{
			$class = new $className();
		}

		if( $location == 'apps' )
		{
			$class = new $className( $parts[0], $parts[1] );
		}

		if( $location == 'fields' )
		{
			$config = array( 'group' => $parts[0], 'element' => $parts[1] );

			$class = new $className( $config );
		}

		$callable = array( $class, $method );

		if( !is_callable( $callable ) )
		{
			return false;
		}


		return $callable;
	}

	/**
	 * Determines if the app should appear on the app listings
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function hasAppListing( SocialTableApp $table , $view )
	{
		$file 	= SOCIAL_APPS . '/' . $table->group . '/' . $table->element . '/' . $table->element . '.php';
	
		jimport( 'joomla.filesystem.file' );

		if( !JFile::exists( $file ) )
		{
			return true;
		}

		require_once( $file );

		$appClass 	= 'Social' . ucfirst( $table->group ) . 'App' . ucfirst( $table->element );

		if( !class_exists( $appClass ) )
		{
			return true;
		}
		
		$app 			= new $appClass();
		$app->element	= $table->element;
		$app->group 	= $table->group;
		
		if( !method_exists( $app , 'appListing' ) )
		{
			return true;
		}

		$appear 	= $app->appListing( $view );


		return $appear;
	}
}
