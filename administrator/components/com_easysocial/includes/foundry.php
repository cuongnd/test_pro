<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// @task: Load interfaces
require_once( dirname( __FILE__ ) . '/dependencies.php' );

class Foundry
{
	/**
	 * Stores all the models that are initialized.
	 * @var Array
	 */
	static private $models 			= array();

	/**
	 * Stores all the views that are initialized.
	 * @var Array
	 */
	static private $views 			= array();

	/**
	 * Checks if Foundry folder really exists on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function exists()
	{
		// Check if foundry folder exists since we require it.
		$path	= SOCIAL_FOUNDRY;

		jimport( 'joomla.filesystem.folder' );

		if( !JFolder::exists( $path ) )
		{
			return false;
		}

		return true;
	}

	/**
	 * Singleton for every other classes. It is responsible to return whatever
	 * necessary to perform a proper chaining
	 *
	 * @param	string	$item		Defines what item this method should load
	 * @param	boolean	$forceNew	Tells method whether it is necessary to create a new copy of the object.
	 **/
	public static function getInstance( $item = '' )
	{
		static $objects	= array();

		// We always want lowercased items.
		$item				= strtolower( $item );

		$path				= SOCIAL_LIB . '/' . $item . '/' . $item . '.php';
		$objects[ $item ]	= false;

		// We shouldn't add file checks here because it greatly slows down the script.
		// The caller should know what's it doing.
		include_once( $path );
		$class				= 'Social' . ucfirst( $item );

		if( class_exists( $class ) )
		{
			$args	= func_get_args();

			if( isset( $args[0] ) )
			{
				unset( $args[ 0 ] );
			}

			$args 	= array_values( $args );

			if( method_exists( $class , 'getInstance') )
			{
				$objects[ $item ]	= call_user_func_array( $class . '::getInstance' , $args );
			}
		}

		return $objects[ $item ];
	}

	/**
	 * Loads a library
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function load( $library )
	{
		// We always want lowercased items.
		$library	= JString::strtolower( $library );
		$obj		= false;

		$path 		= SOCIAL_LIB . '/' . $library . '/' . $library . '.php';

		include_once( $path );
	}

	/**
	 * This is a simple wrapper method to access a particular library in EasySocial. This method will always
	 * instantiate a new class based on the given class name.
	 *
	 * @param	string	$item		Defines what item this method should load
	 **/
	public static function get( $lib = '' )
	{
		// Try to load up the library
		self::load( $lib );

		$class			= 'Social' . ucfirst( $lib );

		if( !class_exists( $class ) )
		{
			return false;
		}

		$args	= func_get_args();

		// Remove the first argument because we know the first argument is always the library.
		if( isset( $args[0] ) )
		{
			unset( $args[ 0 ] );
		}

		return Foundry::factory( $class , $args );

		// @task: If object at this point of time is still a boolean, then we should just return false.
		return false;
	}

	/**
	 * Creates a new object given the class.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function factory( $class , $args = array() )
	{
		// Reset the indexes
		$args 		= array_values( $args );
		$numArgs	= count($args);

		// It's too bad that we have to write these cods but it's much faster compared to call_user_func_array
		if($numArgs < 1)
		{
			return new $class();
		}

		if($numArgs === 1)
		{
			return new $class($args[0]);
		}

		if($numArgs === 2)
		{
			return new $class($args[0], $args[1]);
		}

		if($numArgs === 3 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] );
		}

		if($numArgs === 4 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] );
		}

		if($numArgs === 5 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] );
		}

		if($numArgs === 6 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] );
		}

		if($numArgs === 7 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] , $args[ 6 ] );
		}

		if($numArgs === 8 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] , $args[ 6 ] , $args[ 7 ]);
		}

		return call_user_func_array($fn, $args);
	}

	/**
	 * Single point of entry for static calls.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The class name without prefix. E.g: (Themes)
	 * @param	string	The method name
	 * @param	Array	An array of arguments.
	 * @return
	 */
	public static function call( $className , $method , $args = array() )
	{
		// We always want lowercased items.
		$item			= JString::strtolower( $className );
		$obj			= false;

		$path			= SOCIAL_LIB . '/' . $item . '/' . $item . '.php';

		if( !JFile::exists( $path ) )
		{
			return false;
		}

		require_once( $path );

		$class			= 'Social' . ucfirst( $className );

		if( !class_exists( $class ) )
		{
			JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

			return JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_MODEL_NOT_FOUND' , $class ) );
		}

		if( !method_exists( $class , $method ) )
		{
			JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

			return JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_MODEL_NOT_FOUND' , $class ) );
		}

		// Ensure that $args is an array.
		$args 	= Foundry::makeArray( $args );

		return call_user_func_array( array( $class , $method ) , $args );
	}

	/**
	 * An alias to Foundry::getInstance( 'Config' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $config 	= Foundry::config();
	 * echo $config->get( 'some.value' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function config( $key = 'site' )
	{
		return Foundry::getInstance( 'Config' , $key );
	}

	/**
	 * An alias to Foundry::getInstance( 'Config' , 'joomla' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $config 	= Foundry::jconfig();
	 * echo $config->getValue( 'some.value' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function jconfig()
	{
		return Foundry::getInstance( 'Config' , 'joomla' );
	}

	/**
	 * An alias to Foundry::getInstance( 'Config' , 'joomla' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $config 	= Foundry::jconfig();
	 * echo $config->getValue( 'some.value' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function storage( $type = 'joomla' )
	{
		return Foundry::get( 'Storage' , $type );
	}

	/**
	 * An alias to Foundry::getInstance( 'Config' , 'joomla' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $config 	= Foundry::jconfig();
	 * echo $config->getValue( 'some.value' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function fields()
	{
		return Foundry::getInstance( 'Fields' );
	}

	/**
	 * An alias to Foundry::getInstance( 'Router' , 'profile' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $config 	= Foundry::jconfig();
	 * echo $config->getValue( 'some.value' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function router( $view )
	{
		return Foundry::getInstance( 'Router' , $view );
	}

	/**
	 * An alias to Foundry::get( 'Connector' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $connector 	= Foundry::connector();
	 * $connector->addUrl( 'http://stackideas.com' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function connector()
	{
		return Foundry::get( 'Connector' );
	}

	/**
	 * An alias to Foundry::get( 'Assets' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $assets 	= Foundry::assets();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function assets()
	{
		return Foundry::get( 'Assets' );
	}

	/**
	 * An alias to Foundry::getInstance( 'Mailer' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $mailer 	= Foundry::mailer();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function mailer()
	{
		return Foundry::get( 'Mailer' );
	}

	/**
	 * An alias to Foundry::get( 'Migrators' )
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $mailer 	= Foundry::mailer();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function migrators( $extension )
	{
		return Foundry::get( 'Migrators' , $extension );
	}

	/**
	 * Helper for checking valid tokens
	 *
	 * Example:
	 * <code>
	 * <?php
	 * Foundry::checkToken();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialTableConfig	Configuration object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function checkToken()
	{
		JRequest::checkToken( 'request' ) or die( 'Invalid Token' );
	}


	/**
	 * Includes a file given a particular namespace in POSIX format.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$file		Eg: admin:/includes/model will include /administrator/components/com_easysocial/includes/model.php
	 * @return	boolean				True on success false otherwise
	 */
	public static function import( $namespace )
	{
		static $locations	= array();

		if( !isset( $locations[ $namespace ] ) )
		{
			// Explode the parts to know exactly what to lookup for
			$parts		= explode( ':' , $namespace );

			// Non POSIX standard.
			if( count( $parts ) <= 1 )
			{
				return false;
			}

			$base 		= $parts[ 0 ];

			switch( $base )
			{
				case 'admin':
					$basePath	= SOCIAL_ADMIN;
				break;
				case 'themes':
					$basePath	= SOCIAL_THEMES;
				break;
				case 'apps':
					$basePath	= SOCIAL_APPS;
				break;
				case 'fields':
					$basePath	= SOCIAL_FIELDS;
				break;
				case 'site':
				default:
					$basePath	= SOCIAL_SITE;
				break;
			}

			// Replace / with proper directory structure.
			$path 		= str_ireplace( '/' , DIRECTORY_SEPARATOR , $parts[ 1 ] );

			// Get the absolute path now.
			$path 		= $basePath . $path . '.php';

			// Include the file now.
			include_once( $path );

			$locations[ $namespace ]	= true;
		}

		return true;
	}

	/**
	 * Alias for Foundry::get( 'Form' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialStream 	The stream library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function form()
	{
		return Foundry::get( __FUNCTION__ );
	}

	/**
	 * Alias for Foundry::getInstance( 'Sql' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialStream 	The stream library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function sql()
	{
		return Foundry::get( __FUNCTION__ );
	}

	/**
	 * Alias for Foundry::getInstance( 'Stream' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialStream 	The stream library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function stream()
	{
		return Foundry::get( 'Stream' );
	}

	/**
	 * Alias for Foundry::getInstance( 'Apps' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax 	The ajax library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function apps()
	{
		return Foundry::getInstance( 'apps' );
	}

	/**
	 * Alias for Foundry::getInstance( 'Dispatcher' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax 	The ajax library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function dispatcher()
	{
		return Foundry::getInstance( 'Dispatcher' );
	}

	/**
	 * Alias for Foundry::get( 'Uploader' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax 	The ajax library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function uploader( $options=array() )
	{
		return Foundry::get( 'Uploader', $options );
	}

	/**
	 * Alias for Foundry::get( 'Themes' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax 	The ajax library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function themes()
	{
		return Foundry::get( 'Themes' );
	}

	/**
	 * Alias for Foundry::getInstance( 'Ajax' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax 	The ajax library.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function ajax()
	{
		return Foundry::getInstance( 'Ajax' );
	}

	/**
	 * Intelligent method to determine if the string uses plural or singular.
	 *
	 * @param	string		$string		The language string
	 * @param	integer	 	$count		Use 0 for singular
	 * @param	boolean		$useCount	True for counting string
	 *
	 * @return	string
	 */
	public static function text( $string, $count , $useCount = true )
	{
		$count 		= (int) $count;

		// @TODO: Make singular and plural configurable.
		if( $count <= 1 )
		{
			$string 	.= '_SINGULAR';
		}

		if( $count > 1 )
		{
			$string 	.= '_PLURAL';
		}

		if( $useCount )
		{
			return JText::sprintf( $string , $count );
		}


		return JText::_( $string );
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
	public static function table( $name , $prefix = 'SocialTable' )
	{
		$table	= SocialTable::getInstance( $name , $prefix );


		return $table;
	}

	/**
	 * Retrieves the view object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The view's name.
	 * @param	bool	True for back end , false for front end.
	 */
	public static function view( $name , $backend = true )
	{
		$className 	= 'EasySocialView' . ucfirst( $name );

		if( !isset( self::$views[ $className ] ) || ( !self::$views[ $className ] instanceof EasySocialView ) )
		{
			if( !class_exists( $className ) )
			{
				$path		= $backend ? SOCIAL_ADMIN : SOCIAL_SITE;
				$doc		= JFactory::getDocument();
				$path   	.= '/views/' . strtolower( $name ) . '/view.' . $doc->getType() . '.php';

				if( !JFile::exists( $path ) )
				{
					// Add error logging for views that cannot be found.
					Foundry::logError( __FILE__ , __LINE__ , 'VIEW: Unable to locate the view file, ' . $path );
					return false;
				}

				// Include the view
				require_once( $path );
			}

			if( !class_exists( $className ) )
			{
				JError::raiseError( 500 , JText::sprintf( 'View class not found: %1s' , $className ) );
				return false;
			}
			self::$views[ $className ]	= new $className( array() );
		}

		return self::$views[ $className ];
	}

	/**
	 * Retrieves a model object.
	 *
	 * @since 	1.0
	 * @access	public
	 * @param 	string 	$modelName 	The name of the model.
	 **/
	public static function model( $name , $config = array() )
	{
		$cacheId 	= !empty( $config ) ? md5( $name . implode( $config ) ) : md5( $name );

		if( !isset( self::$models[ $cacheId ] ) )
		{
			$className	= 'EasySocialModel' . ucfirst( $name );

			if( !class_exists( $className ) )
			{
				jimport( 'joomla.application.component.model' );

				// Include the model file. This is much quicker than doing JLoader::import
				$path 	= SOCIAL_MODELS . '/' . strtolower( $name ) . '.php';
				require_once( $path );
			}

			// If the class still doesn't exist, let's just throw an error here.
			if( !class_exists( $className ) )
			{
				JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

				return JError::raiseError( 500 , JText::sprintf( 'COM_EASYSOCIAL_MODEL_NOT_FOUND' , $className ) );
			}

			$model 	= new $className( $config );

			self::$models[ $cacheId ]	= $model;
		}

		return self::$models[ $cacheId ];
	}

	/**
	 * This should be triggered when certain pages are not found in the system.
	 * Particularly when certain id does not exist on the system.
	 *
	 */
	public static function show404()
	{
		// @TODO: Log some errors here.
		echo 'some errors here';
	}

	/**
	 * Shows a layout that the user has no access to the particular item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function showNoAccess( $message )
	{
		echo $message;
	}

	/**
	 * Sets some callback data into the current session
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function setCallback( $data )
	{
		$session		= JFactory::getSession();

		// Serialize the callback data.
		$data 			= serialize( $data );

		// Store the profile type id into the session.
		$session->set( 'easysocial.callback' , $data , SOCIAL_SESSION_NAMESPACE );
	}

	/**
	 * Retrieves stored callback data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getCallback()
	{
		$session 		= JFactory::getSession();
		$data 			= $session->get( 'easysocial.callback' , '' , SOCIAL_SESSION_NAMESPACE );

		$data 			= unserialize( $data );

		// Clear off the session once it's been picked up.
		$session->clear( 'easysocial.callback' , SOCIAL_SESSION_NAMESPACE );

		return $data;
	}

	/**
	 * Renders a login page if necessary. If this is called via an ajax method, it will trigger a dialog instead.
	 *
	 * @access	public
	 * @param	null
	 * @return	string	Contents.
	 */
	public static function requireLogin()
	{
		$document	= JFactory::getDocument();

		$my 		= Foundry::user();

		if( $my->id > 0 )
		{
			return true;
		}

		switch( $document->getType() )
		{
			case 'html':
				// Do some redirects here?
				$info 		= Foundry::info();

				$message			= new stdClass();
				$message->message 	= JText::_( 'COM_EASYSOCIAL_PLEASE_LOGIN_FIRST' );
				$message->type 		= SOCIAL_MSG_INFO;

				$info->set( $message );

				// Get the application framework.
				$app		= JFactory::getApplication();

				// Get the current URI.
				$callback 	= FRoute::current();

				Foundry::setCallback( $callback );

				$url 		= FRoute::login( array() , false );

				$app->redirect( $url );
				$app->close();

			break;
			case 'ajax':

				$ajax 	= Foundry::ajax();
				$ajax->script( 'EasySocial.login();' );

				return $ajax->send();
			break;
		}
	}

	/**
	 * Converts an argument into an array.
	 *
	 * @since	1.0
	 * @param	mixed	An object or string.
	 * @param	string	If a delimeter is provided for string, use that as delimeter when exploding.
	 * @return	Array	Converted into an array.
	 */
	public static function makeArray( $item , $delimeter = null )
	{
		// If this is already an array, we don't need to do anything here.
		if( is_array( $item ) )
		{
			return $item;
		}

		// Test if source is an object.
		if( is_object( $item ) )
		{
			return JArrayHelper::fromObject( $item );
		}

		if( is_integer( $item ) )
		{
			return array( $item );
		}

		// Test if source is a string.
		if( is_string( $item ) )
		{
			if( $item == '' )
			{
				return array();
			}

			// Test for comma separated values.
			if( !is_null( $delimeter ) && stristr( $item , $delimeter) !== false )
			{
				$data 	= explode( $delimeter , $item );

				return $data;
			}

			return array( $item );
		}

		return false;
	}

	/**
	 * Converts an argument into an array.
	 *
	 * @since	1.0
	 * @param	mixed	$item		An object or string.
	 * @return	Array	$result		Converted into an array.
	 */
	public static function makeObject( $item )
	{
		// If this is already an object, skip this
		if( is_object( $item ) )
		{
			return $item;
		}

		if( is_array( $item ) )
		{
			return (object) $item;
		}

		if( strlen( $item ) < 4000 && is_file( $item ) )
		{
			jimport( 'joomla.filesystem.file' );

			$item	= JFile::read( $item );
		}

		// Test if source is a string.
		if( is_string( $item ) )
		{
			// Trim the string first
			$item = trim( $item );

			$json 	= Foundry::json();
			$obj 	= $json->decode( $item );

			if( !is_null( $obj ) )
			{
				return $obj;
			}

			$obj 	= new stdClass();

			return $obj;
		}

		return false;
	}

	/**
	 * Converts an array to string
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function makeString( $val , $join = '' )
	{
		if( is_string( $val ) )
		{
			return $val;
		}

		return implode( $join , $val );
	}

	/**
	 * Converts an argument into a json string. If argument is a string, it wouldn't be processed.
	 *
	 * @since	1.0
	 * @param	mixed	An object or array.
	 * @return	string	Converted into a json string.
	 */
	public static function makeJSON( $item )
	{
		if( is_string( $item ) )
		{
			return $item;
		}

		$json 	= Foundry::json();

		$data 	= $json->encode( $item );

		return $data;
	}

	/**
	 * Parses a csv file to array of data
	 *
	 * @since	1.0.1
	 * @param	string	Filename to parse
	 * @return	Array	Arrays of the data
	 */
	public static function parseCSV( $file, $firstRowName = true, $firstColumnKey = true )
	{
		if( !JFile::exists( $file ) )
		{
			return array();
		}

		$handle = fopen( $file, 'r' );

		$line = 0;

		$columns = array();

		$data = array();

		while( ( $row = fgetcsv( $handle ) ) !== false )
		{
			if( $firstRowName && $line === 0 )
			{
				$columns = $row;
			}
			else
			{
				$tmp = array();

				if( $firstRowName )
				{
					foreach( $row as $i => $v )
					{
						$tmp[$columns[$i]] = $v;
					}
				}
				else
				{
					$tmp = $row;
				}

				if( $firstColumnKey )
				{
					if( $firstRowName )
					{
						$data[$tmp[$columns[0]]] = $tmp;
					}
					else
					{
						$data[$tmp[0]] = $tmp;
					}
				}
				else
				{
					$data[] = $tmp;
				}
			}

			$line++;
		}

		fclose( $handle );

		return $data;
	}

	/**
	 * Resolve a given POSIX path.
	 *
	 * <code>
	 * <?php
	 * // This would translate to administrator/components/com_easysocial/themes/CURRENT_THEME/users/default.php
	 * Foundry::resolve( 'themes:/admin/users/default' );
	 *
	 * // This would translate to components/com_easysocial/themes/CURRENT_THEME/dashboard/default.php
	 * Foundry::resolve( 'themes:/site/dashboard/default' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The posix path to lookup for.
	 * @return	string		The translated path
	 */
	public static function resolve( $path )
	{
		if ( strpos($path, ':/')===false )
		{
			return false;
		}

		$parts = explode( ':/' , $path );

		// Get the protocol.
		$protocol 	= $parts[ 0 ];

		// Get the real path.
		$path 		= $parts[ 1 ];

		switch( $protocol )
		{
			case 'modules':

				return Foundry::call( 'Modules' , 'resolve' , $path );

				break;
			case 'themes':
				return Foundry::call( 'Themes' , 'resolve' , $path );
				break;

			case 'ajax':
				return Foundry::call( 'Ajax' , 'resolveNamespace' , $path );
				break;

			case 'emails':
				return Foundry::call( 'Mailer' , 'resolve' , $path );
				break;

			case 'admin':
			case 'apps':
			case 'fields':
			case 'site':
				$basePath	= constant("SOCIAL_" . strtoupper($protocol));
				$path		= str_ireplace( '/' , DIRECTORY_SEPARATOR , $path );
				return $basePath . DIRECTORY_SEPARATOR . $path;
				break;
		}

		return false;
	}

	/**
	 * Alias for Foundry::getInstance( 'Page' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialPage
	 */
	public static function page()
	{
		return Foundry::getInstance( 'Page' );
	}

	/**
	 * Alias for Foundry::getInstance( 'Document' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialDocument
	 */
	public static function document()
	{
		return Foundry::getInstance( 'Document' );
	}

	/**
	 * Alias for Foundry::get( 'Subscriptions' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target's id.
	 * @param	string	The target's type.
	 * @param	string	The extension name.
	 * @return	SocialPrivacy
	 */
	public static function subscriptions()
	{
		return Foundry::get( 'Subscriptions' );
	}

	/**
	 * Alias for Foundry::get( 'Cron' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialCron
	 */
	public static function cron()
	{
		return Foundry::get( 'Cron' );
	}

	/**
	 * Alias for Foundry::getInstance( 'Profiler' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target's id.
	 * @param	string	The target's type.
	 * @param	string	The extension name.
	 * @return	SocialPrivacy
	 */
	public static function profiler()
	{
		return Foundry::getInstance( 'Profiler' );
	}

	/**
	 * Alias for Foundry::get( 'DB' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target's id.
	 * @param	string	The target's type.
	 * @param	string	The extension name.
	 * @return	SocialPrivacy
	 */
	public static function privacy( $target = '' , $type = SOCIAL_TYPE_USER )
	{
		return Foundry::get( 'Privacy' , $target , $type );
	}


	/**
	 * Retrieves a token generated by the platform.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public static function token()
	{
		$version 	= Foundry::getInstance( 'Version' );

		if( $version->getVersion() >= '3.0' )
		{
			return JFactory::getSession()->getFormToken();
		}

		return JUtility::getToken();
	}
	/**
	 * Detects if the folder exist based on the path given. If it doesn't exist, create it.
	 *
	 * @since	1.0
	 * @param	string	$path		The path to the folder.
	 * @return	boolean				True if exists (after creation or before creation) and false otherwise.
	 */
	public static function makeFolder( $path )
	{
		jimport( 'joomla.filesystem.folder' );

		// If folder exists, we don't need to do anything
		if( JFolder::exists( $path ) )
		{
			return true;
		}

		// Folder doesn't exist, let's try to create it.
		if( JFolder::create( $path ) )
		{
			Foundry::copyIndex( $path );
			return true;
		}

		return false;
	}

	/**
	 * Cleans a given string and replaces all /\ with proper directory structure DIRECTORY_SEPARATOR and removes any trailing or leading /
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The file / folder name.
	 * @return	string	Cleaned file / folder name.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function language()
	{
		$lang 	= Foundry::get( 'Language' );

		return $lang;
	}

	/**
	 * Cleans a given string and replaces all /\ with proper directory structure DIRECTORY_SEPARATOR and removes any trailing or leading /
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The file / folder name.
	 * @return	string	Cleaned file / folder name.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function cleanPath( $value )
	{
		$value	= ltrim( $value , '\/' );
		$value	= rtrim( $value , '\/' );
		$value 	= str_ireplace( array( '\\' ,'/' ) , '/' , $value );

		return $value;
	}

	/**
	 * Alias for Foundry::get( 'DB' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialDB		The database layer object.
	 */
	public static function db()
	{
		return Foundry::getInstance( 'DB' );
	}

	/**
	 * Alias for Foundry::get( 'Date' );
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function date( $current = 'now' , $withoffset = true )
	{
		if( is_object( $current ) && get_class( $current ) == 'SocialDate' )
		{
			return $current;
		}

		return Foundry::get( 'Date' , $current , $withoffset );
	}

	/**
	 * Alias for Foundry::get( 'User' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialUser		The user's object
	 */
	public static function user( $ids = null , $debug = false )
	{
		// $args 	= func_get_args();

		// if( !$args )
		// {
		// 	return Foundry::get( 'User' );
		// }

		// if( count( $args ) == 1 )
		// {
		// 	$id 	= $args[ 0 ];

		// 	return Foundry::get( 'User' , $id );
		// }

		// $ids 	= $args[ 0 ];
		// $debug 	= $args[ 1 ];

		// return Foundry::get( 'User' , $ids , $debug );

		// Load the user library
		self::load( 'user' );

		return SocialUser::factory( $ids , $debug );
		// return SocialUser::factory( $ids , $debug );
	}

	/**
	 * Alias for Foundry::get( 'User' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialUser		The user's object
	 */
	public static function version()
	{
		$version	= Foundry::getInstance( 'Version' );

		return $version;
	}

	/**
	 * Generates a blank index.html file into a specific target location.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The target location
	 * @return	bool	True if success, false otherwise.
	 */
	public static function copyIndex( $targetLocation )
	{
		$defaultLocation 	= SOCIAL_SITE . '/index.html';
		$targetLocation		= $targetLocation . '/index.html';

		jimport( 'joomla.filesystem.file' );

		// Copy the file over.
		return JFile::copy( $defaultLocation , $targetLocation );
	}

	/**
	 * Alias to Foundry::getInstance( 'Notification' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialNotification		The notification library.
	 */
	public static function notification()
	{
		return Foundry::getInstance( 'Notification' );
	}

	/**
	 * Alias to Foundry::getInstance( 'Badges' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialPoints	The points library
	 */
	public static function badges()
	{
		return Foundry::getInstance( 'Badges' );
	}

	/**
	 * Alias to Foundry::getInstance( 'Points' );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialPoints	The points library
	 */
	public static function points()
	{
		return Foundry::getInstance( 'Points' );
	}

	/**
	 * Alias method to load JSON library
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function json()
	{
		$lib 	= Foundry::getInstance( 'JSON' );

		return $lib;
	}

	/**
	 * Alias method to load info library
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function info()
	{
		$info	= Foundry::getInstance( 'Info' );

		return $info;
	}

	/**
	 * Shorthand method to check version
	 *
	 * @since	1.0
	 * @access	public
	 * @return	boolean
	 */
	public static function isJoomla30()
	{
		$version	= Foundry::getInstance( 'version' );
		return $version->getVersion() >= '3.0';
	}
	public static function isJoomla25()
	{
		$version	= Foundry::getInstance( 'version' );
		return $version->getVersion() >= '1.6' && $version->getVersion() <= '2.5';
	}
	public static function isJoomla15()
	{
		$version	= Foundry::getInstance( 'version' );
		return $version->getVersion() <= '1.5';
	}

	/**
	 * Generates a hash on a string.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The string to be hashed.
	 */
	public static function getHash( $str )
	{
		if( Foundry::isJoomla30() )
		{
			return JApplication::getHash( $str );
		}


		return JUtility::getHash( $str );
	}

	/**
	 * Stores error information in the database.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The file name. Normally __FILE__
	 * @param	string	The line number. Normally __LINE__
	 * @param	string	The actual message to log.
	 * @return
	 */
	public static function logError( $file , $line , $message = '' )
	{
		$config 	= Foundry::config();

		if( !$config->get( 'general.logger' ) )
		{
			return false;
		}

		$logger 	= Foundry::table( 'Logger' );

		$logger->file 		= $file;
		$logger->line 		= $line;
		$logger->message	= $message;

		$logger->store();
	}

	/**
	 * Alternative to var_dump as we need to add some code beautifier.
	 *
	 */
	public static function dump()
	{
		$args 	= func_get_args();
		$html 	= JFactory::getDocument()->getType();

		if( $type == 'html' )
		{
			echo '<pre>';
		}

		foreach( $args as $arg )
		{
			var_dump( $arg );
		}

		if( $type == 'html' )
		{
			echo '</pre>';
		}

		exit;
	}

	public static function log( $var, $source = true, $setSource = null )
	{
		static $logsource = null;

		if( is_bool( $setSource ) )
		{
			$logsource = $setSource;
		}

		if( is_bool( $logsource ) && $logsource === false )
		{
			$source = false;
		}

		$debugroot = SOCIAL_LIB . '/debug/';

		$callers = debug_backtrace();
		$func = isset( $callers[1]['func'] ) ? $callers[1]['func'] : '';
		$line = isset( $callers[1]['line'] ) ? $callers[1]['line'] : '';
		$file = isset( $callers[1]['file'] ) ? $callers[1]['file'] : '';

		// This is to free up memory space because debug_backtrace is a large array
		$callers = null;
		unset( $callers );

		if( JFile::exists( $debugroot . 'fb.php' ) && JFile::exists( $debugroot . 'FirePHP.class.php' ) )
		{
			include_once( $debugroot . 'fb.php' );

			if( $source ) {
				fb( $func . ':' . $line . ' [' . $file . ']' );
			}

			fb( $var );
		}

		if( JFile::exists( $debugroot . 'chromephp.php' ) )
		{
			include_once( $debugroot . 'chromephp.php' );

			if( $source ) {
				ChromePhp::log( $func . ':' . $line . ' [' . $file . ']' );
			}

			ChromePhp::log( $var );
		}
	}

	/**
	 * Alias for Foundry::get( 'Image' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target's id.
	 * @param	string	The target's type.
	 * @param	string	The extension name.
	 * @return	SocialPrivacy
	 */
	public static function image()
	{
		return Foundry::get( 'Image' );
	}

	/**
	 * Alias for Foundry::get( 'Image' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target's id.
	 * @param	string	The target's type.
	 * @param	string	The extension name.
	 * @return	SocialPrivacy
	 */
	public static function avatar( SocialImage $image , $id = null , $type = null )
	{
		return Foundry::get( 'Avatar' , $image , $id , $type );
	}

	public static function albums( $id=null )
	{
		return Foundry::get( 'Albums' , $id );
	}

	public static function photo( $id=null )
	{
		return Foundry::get( 'Photo' , $id );
	}

	public static function exception( $message='' , $type=SOCIAL_MSG_ERROR )
	{
		return Foundry::get( 'Exception' , $message , $type );
	}

	public static function math()
	{
		return Foundry::getInstance( 'Math' );
	}

	/**
	 * Alias for Foundry::get( 'Reports' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialReports
	 */
	public static function reports()
	{
		return Foundry::get( 'Reports' );
	}

	/**
	 * Alias for Foundry::get( 'Location' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialReports
	 */
	public static function location()
	{
		return Foundry::get( 'Location' );
	}

	/**
	 * Alias for Foundry::get( 'Access' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialReports
	 */
	public static function access( $userId = null, $type = SOCIAL_TYPE_USER )
	{
		return Foundry::get( 'Access' , $userId , $type );
	}

	/**
	 * Alias for Foundry::getInstance( 'Opengraph' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialOpengraph
	 */
	public static function opengraph()
	{
		return Foundry::getInstance( 'Opengraph' );
	}

	/**
	 * Alias for Foundry::getInstance( 'OAuth' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target's id.
	 * @param	string	The target's type.
	 * @param	string	The extension name.
	 * @return	SocialOauth
	 */
	public static function oauth( $client = '' , $callback = '' )
	{
		return Foundry::getInstance( 'OAuth' , $client , $callback );
	}

	// /**
	//  * Creates a new object given the class.
	//  *
	//  * @since	1.0
	//  * @access	public
	//  * @param	string
	//  * @return
	//  */
	// public function factory( $class , $args = array() )
	// {
	// 	// Reset the indexes
	// 	$args 		= array_values( $args );
	// 	$numArgs	= count($args);

	// 	// It's too bad that we have to write these cods but it's much faster compared to call_user_func_array
	// 	if($numArgs < 1)
	// 	{
	// 		return new $class();
	// 	}

	// 	if($numArgs === 1)
	// 	{
	// 		return new $class($args[0]);
	// 	}

	// 	if($numArgs === 2)
	// 	{
	// 		return new $class($args[0], $args[1]);
	// 	}

	// 	if($numArgs === 3 )
	// 	{
	// 		return new $class($args[0], $args[1] , $args[ 2 ] );
	// 	}

	// 	if($numArgs === 4 )
	// 	{
	// 		return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] );
	// 	}

	// 	if($numArgs === 5 )
	// 	{
	// 		return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] );
	// 	}

	// 	if($numArgs === 6 )
	// 	{
	// 		return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] );
	// 	}

	// 	if($numArgs === 7 )
	// 	{
	// 		return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[]);
	// 	}

	// 	if($numArgs === 8 )
	// 	{
	// 		return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] );
	// 	}

	// 	return call_user_func_array($fn, $args);
	// }

	public static function callFunc( $obj , $fn , array $args = array() )
	{
		$numArgs = count($args);

		if($numArgs < 1)
		{
			return $obj->$fn();
		}

		if($numArgs === 1)
		{
			return $obj->$fn($args[0]);
		}

		if($numArgs === 2)
		{
			return $obj->$fn($args[0], $args[1]);
		}

		if($numArgs === 3 )
		{
			return $obj->$fn($args[0], $args[1] , $args[ 2 ] );
		}

		if($numArgs === 4 )
		{
			return $obj->$fn($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] );
		}

		if($numArgs === 5 )
		{
			return $obj->$fn($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] );
		}

		return call_user_func_array($fn, $args);
	}

	/**
	 * Alias for Foundry::get( 'String' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialString
	 */
	public static function string()
	{
		return Foundry::get( 'String' );
	}

	/**
	 * Alias for Foundry::get( 'Likes' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The type.
	 * @return	SocialStory
	 */
	public static function likes( $uid = null , $type = null, $group = SOCIAL_APPS_GROUP_USER )
	{
		return Foundry::get( 'Likes' , $uid , $type, $group );
	}

	/**
	 * Alias for Foundry::get( 'Story' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The type.
	 * @return	SocialStory
	 */
	public static function story( $type = '' )
	{
		return Foundry::get( 'Story' , $type );
	}

	/**
	 * Alias for Foundry::get( 'Registry' )
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The raw string.
	 * @return	SocialRegistry
	 */
	public static function registry( $raw = '' )
	{
		return Foundry::get( 'Registry' , $raw );
	}

	/**
	 * Alias for Foundry::getInstance( 'Modules' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialComments
	 */
	public static function modules( $name )
	{
		$modules 	= Foundry::get( 'Modules' , $name );

		return $modules;
	}

	/**
	 * Alias for Foundry::getInstance( 'Comments' )
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialComments
	 */
	public static function comments( $uid = null, $element = null, $group = SOCIAL_APPS_GROUP_USER, $options = array() )
	{
		$comments = Foundry::getInstance( 'Comments' );

		if( !is_null( $uid ) && !is_null( $element ) )
		{
			return $comments->load( $uid, $element, $group, $options );
		}

		return $comments;
	}

	public static function alert( $element = null, $rulename = null )
	{
		$alert = Foundry::getInstance( 'alert' );

		if( is_null( $element ) )
		{
			return $alert;
		}

		$registry = $alert->getRegistry( $element );

		if( is_null( $rulename ) )
		{
			return $registry;
		}

		return $registry->getRule( $rulename );
	}

	/**
	 * Shorthand to send out notification
	 *
	 * Foundry::notify( 'element.rulename', array( 1, 2, 3 ) );
	 *
	 * @since	1.0
	 * @access	public
	 * @return	boolean		State of sending the notification
	 */
	public static function notify( $rule, $participants, $emailOptions = array(), $systemOptions = array() )
	{
		$segments = explode( '.', $rule );

		$element = array_shift( $segments );

		$rulename = implode( '.', $segments );

		$alert = Foundry::alert( $element, $rulename );

		if( !$alert )
		{
			return false;
		}

		return $alert->send( $participants, $emailOptions, $systemOptions );
	}

	/**
	 * Retrieves the current version of EasySocial installed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getLocalVersion()
	{
		$file 	= SOCIAL_ADMIN . '/easysocial.xml';

		$parser = Foundry::get( 'Parser' );
		$parser->load( $file );

		$version	= $parser->xpath( 'version' );
		$version 	= (string) $version[0];

		return $version;
	}

	/**
	 * Retrieves the latest version of EasySocial from the server
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getOnlineVersion()
	{
		$connector 	= Foundry::get( 'Connector' );
		$connector->addUrl( SOCIAL_SERVICE_NEWS );
		$connector->connect();

		$contents	= $connector->getResult( SOCIAL_SERVICE_NEWS );

		$obj 		= Foundry::makeObject( $contents );

		return $obj->version;
	}

	public static function getEnvironment()
	{
		$config = Foundry::getInstance( 'Configuration' );
		return $config->environment;
	}

	public static function getMode()
	{
		$config = Foundry::getInstance( 'Configuration' );
		return $config->mode;
	}

	/**
	 * Loads the less library
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function less()
	{
		$less 	= Foundry::get( 'Less' );

		return $less;
	}

	/**
	 * Synchronizes the database table columns
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function syncDB( $from = '' )
	{
		$db		= Foundry::db();

		return $db->sync( $from );
	}

	/**
	 * Retrieves the base URL of the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getBaseUrl()
	{
		$baseUrl	= rtrim( JURI::root() , '/' ) . '/index.php?option=com_easysocial';


		$app	    = JFactory::getApplication();
		$config 	= Foundry::config();
		$uri		= JFactory::getURI();
		$language	= $uri->getVar( 'lang' , 'none' );
		$router		= $app->getRouter();
		$baseUrl	= rtrim( JURI::base() , '/' ) . '/index.php?option=com_easysocial&lang=' . $language;

		$itemId 	= JRequest::getVar( 'Itemid' ) ? '&Itemid=' . JRequest::getVar( 'Itemid' ) : '';

		if( $router->getMode() == JROUTER_MODE_SEF && JPluginHelper::isEnabled( "system" , "languagefilter" ) )
		{
			$rewrite	= $config->get('sef_rewrite');
			$base		= str_ireplace( JURI::root( true ) , '' , $uri->getPath() );
			$path		=  $rewrite ? $base : JString::substr( $base , 10 );
			$path		= JString::trim( $path , '/' );
			$parts		= explode( '/' , $path );

			if( $parts )
			{
				// First segment will always be the language filter.
				$language	= reset( $parts );
			}
			else
			{
				$language	= 'none';
			}

			if( $rewrite )
			{
				$baseUrl		= rtrim( JURI::base() , '/' ) . '/' . $language . '/?option=com_easysocial';
				$language	= 'none';
			}
			else
			{
				$baseUrl		= rtrim( JURI::base() , '/' ) . '/index.php/' . $language . '/?option=com_easysocial';
			}
		}

		return $baseUrl . $itemId;
	}
}
