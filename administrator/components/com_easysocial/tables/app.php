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

Foundry::import( 'admin:/tables/table' );

/**
 * Object mapping for `#__social_apps` table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableApp extends SocialTable
{
	/**
	 * The unique id of the application
	 * @var int
	 */
	public $id			= null;

	/**
	 * The type of the application. E.g: fields, applications
	 * @var string
	 */
	public $type		= null;

	/**
	 * Determines if the application is a core application.
	 * @var int
	 */
	public $core		= null;

	/**
	 * Determines if the application is only used for processing only.
	 * @var int
	 */
	public $system		= null;

	/**
	 * Determines if the application is a unique application.
	 * @var int
	 */
	public $unique		= null;

	/**
	 * The unique element of the application.
	 * @var string
	 */
	public $element		= null;

	/**
	 * The group type of the application. E.g: people, groups , events etc.
	 * @var string
	 */
	public $group 		= null;

	/**
	 * The title of the application
	 * @var string
	 */
	public $title		= null;

	/**
	 * The permalink of the application
	 * @var string
	 */
	public $alias		= null;

	/**
	 * The state of the application
	 * @var int
	 */
	public $state		= null;

	/**
	 * The user visibility of the application
	 * @var int
	 */
	public $visible		= null;

	/**
	 * The creation date time.
	 * @var datetime
	 */
	public $created		= null;

	/**
	 * The ordering of the application
	 * @var int
	 */
	public $ordering	= null;

	/**
	 * Custom parameters for the application
	 * @var string
	 */
	public $params		= null;

	/**
	 * The version number of the application.
	 * @var string
	 */
	public $version			= null;

	/**
	 * The author of the application
	 * @var string
	 */
	public $author			= null;

	/**
	 * Determines if this app plans to load as widgets
	 * @var string
	 */
	public $widget			= null;

	/**
	 * Determines if this app would be installable
	 * @var string
	 */
	public $installable		= null;

	/**
	 * Determines if this app would be installable
	 * @var string
	 */
	public $default		= null;

	/**
	 * Used for caching internally.
	 * @var Array
	 */
	public $layouts 		= null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_apps' , 'id' , $db );
	}

	// public function load( $id )
	// {

	// 	$callers=debug_backtrace();
	// 	echo $callers[1]['function'];
	// 	echo '<br>';

	// 	return parent::load( $id );
	// }

	/**
	 * Loads the application given the `element`, `type` and `group`.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The unique element name. (E.g: notes )
	 * @param	string	The group of the application. (E.g: people or group)
	 * @param	string	The unique type of the app. (E.g: apps or fields )
	 *
	 * @return	bool	True on success false otherwise
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function loadByElement( $element , $group , $type )
	{
		$db 	= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl );
		$query[]	= 'WHERE ' . $db->nameQuote( 'element' ) . '=' . $db->Quote( $element );
		$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );
		$query[]	= 'AND ' . $db->nameQuote( 'group' ) . '=' . $db->Quote( $group );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$result	= $db->loadObject();

		if( !$result )
		{
			return false;
		}

		return parent::bind( $result );
	}

	/**
	 * Loads an application by group
	 *
	 * @param	string	$element	The element to look for.
	 * @return	boolean	True on success false otherwise
	 */
	public function loadByGroup( $group , $element )
	{
		$db 	= Foundry::db();

		$query	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl )
				. ' WHERE ' . $db->nameQuote( 'element' ) . '=' . $db->Quote( $element )
				. ' AND ' . $db->nameQuote( 'group' ) . '=' . $db->Quote( $group );
		$db->setQuery( $query );

		$result	= $db->loadObject();

		if (!$result)
		{
			return false;
		}

		return parent::bind( $result );
	}

	/**
	 * Loads the app's css file.
	 *
	 * @param	string	$element	The element to look for.
	 * @return	boolean	True on success false otherwise
	 */
	public function loadCss()
	{
		$doc 	= JFactory::getDocument();

		$file 	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element . '/assets/styles/style.css';

		jimport( 'joomla.filessytem.file' );

		if( JFile::exists( $file ) )
		{
			$doc->addStyleSheet( rtrim( JURI::root() , '/' ) . '/media/com_easysocial/apps/' . $this->group . '/' . $this->element . '/assets/styles/style.css' );
		}
	}

	/**
	 * Determines if this app has user settings.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	boolean	True if app has user settings.
	 */
	public function hasUserSettings()
	{
		$file 	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element . '/config/user.json';

		jimport( 'joomla.filesystem.file' );
		$exists	= JFile::exists( $file );

		return $exists;
	}

	/**
	 * Determines if the app should appear in the app listing
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function appListing( $view )
	{
		return Foundry::apps()->hasAppListing( $this , $view );
	}

	/**
	 * Determines if the current application has a bookmark view.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 *
	 * @return	boolean		True if app contains a bookmark, false otherwise.
	 */
	public function hasDashboard()
	{
		// Ensure that they are all in lowercase
		$group 		= strtolower( $this->group );
		$element	= strtolower( $this->element );

		// Build the path
		$path 		= SOCIAL_APPS . '/' . $group . '/' . $element . '/views/dashboard/view.html.php';

		jimport( 'joomla.filesystem.file' );

		$state 		= JFile::exists( $path );

		return $state;
	}

	/**
	 * Retrieve the local version of the application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	The application version.
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Retrieves description of the app for the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string
	 */
	public function getUserDesc()
	{
		Foundry::language()->loadApp( $this->group , $this->element );

		$text 	= 'APP_' . strtoupper( $this->element ) . '_' . strtoupper( $this->group ) . '_DESC_USER';

		return JText::_( $text );
	}

	/**
	 * Gets the application meta data from the manifest file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialAppMeta
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getMeta()
	{
		if( $this->type == 'fields' )
		{
			Foundry::language()->loadField( $this->group , $this->element );

			$manifestFile 	= SOCIAL_APPS . '/' . $this->type . '/' . $this->group . '/' . $this->element . '/' . $this->element . '.xml';
		}

		if( $this->type == 'apps' )
		{
			// Load the language string.
			Foundry::language()->loadApp( $this->group , $this->element );

			$manifestFile 	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element . '/' . $this->element . '.xml';
		}

		$meta 	= new SocialAppMeta( $manifestFile );

		return $meta;
	}

	/**
	 * Deletes any views that are related to the current view.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	boolean		True if success.
	 */
	public function deleteExistingViews()
	{
		$model 	= Foundry::model( 'Apps' );
		$state 	= $model->deleteExistingViews( $this->id );

		return $state;
	}

	/**
	 * Get's the application type in text.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	The string value for the application type.
	 */
	public function getTypeString()
	{
		$languageString 	= 'COM_EASYSOCIAL_APPS_TYPE_' . JString::strtoupper( $this->type );

		return JText::_( $languageString );
	}

	/**
	 * Gets a list of views that are assigned to this app.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function getViews( $viewName = '' )
	{
		static $views 	= array();

		if( !isset( $views[ $this->id ] ) )
		{
			$model 		= Foundry::model( 'Apps' );
			$appViews 	= $model->getViews( $this->id );

			$views[ $this->id ]	= array();

			if( $appViews )
			{
				foreach( $appViews as &$view )
				{
					$view->title 		= trim( $view->title );
					$view->description	= trim( $view->description );

					$views[ $this->id ][ $view->view ]	= $view;
				}
			}
		}

		return $views[ $this->id ][ $viewName ];
	}

	/**
	 * Gets a list of available layouts for this app.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function getLayout( $layout = '' )
	{
		static $layouts 	= array();

		if( $this->type !== SOCIAL_APPS_TYPE_APPS )
		{
			return false;
		}

		if( !$layouts[ $this->id ] )
		{
			// Build the path to the layouts file.
			$path 	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element . '/config/layouts.json';

			jimport( 'joomla.filesystem.file' );

			if( !JFile::exists( $path ) )
			{
				return false;
			}

			$contents 	= JFile::read( $path );
			$result		= Foundry::json()->decode( $contents );

			// Let's re-organize these layouts.
			$layouts 	= array();

			foreach( $result as $item )
			{
				$layouts[ $item->view ]	= $item;
			}

			$this->layouts 	= $layouts;
		}

		return $this->layouts;
	}

	/**
	 * Determines if the current app has already been installed by the user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		User id
	 * @return	bool	Result
	 */
	public function isInstalled( $userId = null )
	{
		if( empty( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$model 		= Foundry::model( 'Apps' );
		$installed	= $model->isInstalled( $this->id, $userId );

		return $installed;
	}

	/**
	 * Installs the app
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		User id
	 * @return	bool	Result
	 */
	public function install( $userId = null )
	{
		$user 	= Foundry::user( $userId );
		$userId = $user->id;

		$config = Foundry::config();
		$map	= Foundry::table( 'appsmap' );

		$map->app_id = $this->id;
		$map->uid = $userId;
		$map->type = SOCIAL_APPS_GROUP_USER;
		$map->created = Foundry::date()->toSql();

		$state = $map->store();

		if( !$state )
		{
			Foundry::logError( __FILE__, __LINE__, $map->getError() );
			return false;
		}

		// @badge: apps.install
		// Assign a badge to the user when they install apps.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'apps.install' , $userId , JText::_( 'COM_EASYSOCIAL_APPS_BADGE_INSTALLED' ) );

		// Give points to the author when installing apps
		$points 	= Foundry::points();
		$points->assign( 'apps.install' , 'com_easysocial' , $userId );

		// If configured to publish on the stream, share this to the world.
		if( $config->get( 'apps.stream.add' ) )
		{
			// lets add a stream item here.
			$stream 	= Foundry::stream();
			$template 	= $stream->getTemplate();

			$template->setActor( $user->id, SOCIAL_TYPE_USER );
			$template->setContext( $this->id, SOCIAL_TYPE_APPS );
			$template->setVerb( 'install' );
			$template->setType( SOCIAL_STREAM_DISPLAY_MINI );
			$template->setAggregate( false );

			$template->setPublicStream( 'core.view' );


			$stream->add( $template );
		}

		return true;
	}

	/**
	 * Allows caller to uninstall this app from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function uninstall()
	{
		$path 	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element;

		// Check if the folder exists.
		if( !JFolder::exists( $path ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'APPS: Removal of app folder failed because the folder ' . $path . ' does not exist.' );
		}
		else
		{
			// Try to delete the folder
			$state 	= JFolder::delete( $path );

			if( !$state )
			{
				Foundry::logError( __FILE__ , __LINE__ , 'APPS: Removal of app folder ' . $path . ' failed because of permission issues.' );
			}
		}

		// Delete app views
		$model	= Foundry::model( 'Apps' );
		$model->deleteExistingViews( $this->id );

		// Just delete this record from the database.
		$state 	= $this->delete();

		// Remove the stream item as well.
		Foundry::stream()->delete( $this->id , SOCIAL_TYPE_APPS );

		return $state;
	}

	/**
	 * Allows the caller to uninstall the app from the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function uninstallUserApp( $userId = null )
	{
		$config 	= Foundry::config();
		$user 		= Foundry::user( $userId );
		$userId 	= $user->id;

		// Delete user mapping
		$map		= Foundry::table( 'appsmap' );
		$map->load( array( 'app_id' => $this->id , 'uid' => $userId ) );

		$state 		= $map->delete();

		// Give points to the author when uninstalling apps
		if( $state )
		{
			$points 	= Foundry::points();
			$points->assign( 'apps.uninstall' , 'com_easysocial' , $userId );
		}

		// Delete any stream that's related to the user installing this app
		$stream	= Foundry::stream();
		$stream->delete( $this->id , SOCIAL_TYPE_APPS , $userId );

		return $state;
	}

	/**
	 * Get's the icon's absolute path.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The size to load. (E.g: favicon, small , medium , cover )
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getIcon( $size = 'small' )
	{
		$path 	= SOCIAL_APPS . '/' . $this->group . '/' . $this->element . '/assets/icons/' . $size . '.png';

		// If there's no icon provided for the app, we load our own default icons.
		$default 	= SOCIAL_DEFAULTS_URI . '/apps/' . $size . '.png';

		if( JFile::exists( $path ) )
		{
			return SOCIAL_APPS_URI . '/' . $this->group . '/' . $this->element . '/assets/icons/' . $size . '.png';
		}

		return $default;
	}

	/**
	 * Retrieve user params
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserParams( $id = null )
	{
		$user 	= Foundry::user( $id );

		$map 	= Foundry::table( 'AppsMap' );
		$map->load( array( 'uid' => $user->id , 'app_id' => $this->id ) );

		$params 	= Foundry::registry( $map->params );

		return $params;
	}

	/**
	 * Retrieve user params
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getParams()
	{
		$params 	= Foundry::registry( $this->params );

		return $params;
	}

	/**
	 * Render's application parameters form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	HTML codes.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function renderForm( $type = 'form' , $params = null , $prefix = '' )
	{
		// Get the manifest path.
		$file		= $this->getManifestPath( $type );

		if( $file === false )
		{
			return false;
		}

		$registry = Foundry::makeObject( $file );

		// Check for custom callbacks
		foreach( $registry as &$section )
		{
			foreach( $section->fields as &$field )
			{
				if( isset( $field->callback ) )
				{
					$callable = Foundry::apps()->getCallable( $field->callback );

					if( !$callable )
					{
						continue;
					}

					$field->options = call_user_func_array( $callable, array( $this ) );
				}
			}
		}

		// Get the parameter object.
		$form 		= Foundry::get( 'Form' );
		$form->load( $registry );

		if( $params )
		{
			$form->bind( $params );
		}
		else
		{
			// Bind the stored data with the params.
			$form->bind( $this->params );
		}

		// Get the HTML output.
		return $form->render( false , false , '' , $prefix );
	}

	/**
	 * Returns the path of the manifest file for this application.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The path to the manifest file.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getManifestPath( $type = 'config', $extension = 'json' )
	{
		$name	= strtolower( $type === '' ? $this->element : $type );

		$path 	= SOCIAL_APPS;

		if( $this->type == 'fields' )
		{
			$path 	= SOCIAL_FIELDS;
		}

		$path 	= $path . '/' . $this->group . '/' . $this->element . '/config/' . $type . '.' . $extension;

		jimport( 'joomla.filesystem.file' );

		if( JFile::exists( $path ) )
		{
			return $path;
		}

		return false;
	}

	/**
	 * Returns the manifest of the application
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The type of the manifest.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getManifest( $type = 'config', $extension = 'json' )
	{
		// Get the path to the manifest file.
		$path = $this->getManifestPath( $type, $extension );

		if( $path === false )
		{
			return false;
		}

		// Load json library
		$json 	= Foundry::json();

		// Get the json contents from the path.
		$raw 	= JFile::read( $path );

		// Let's decode the object.
		$obj 	= $json->decode( $raw );

		return $obj;
	}

	public function getElement()
	{
		return $this->element;
	}

	public function installAlerts()
	{
		$rules = $this->getManifest( $this->element, 'alert' );

		if( !$rules )
		{
			return false;
		}

		$alert = Foundry::alert( $this->element );

		$options = array( 'core' => $this->core, 'app' => 1 );

		foreach( $rules as $rulename => $values )
		{
			$alert->register( $rulename, $values->email, $values->system, $options );
		}

		return true;
	}

	public function isCore()
	{
		return (bool) $this->core;
	}

	/**
	 * Returns the alias of the app
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlias()
	{
		$alias 	= $this->id . ':' . $this->alias;

		return $alias;
	}

	/**
	 * Gets the user permalink for the app
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserPermalink( $userAlias )
	{
		// Get the profile view
		$view	= $this->getViews( 'profile' );
		$type 	= $view->type;

		// The app is embedded on the page
		if( $type == 'embed' )
		{
			$url 	= FRoute::profile( array( 'id' => $userAlias , 'appId' => $this->getAlias() ) );

			return $url;
		}

		// If it's a canvas view
		$url 	= FRoute::apps( array( 'id' => $this->getAlias() , 'layout' => 'canvas' , 'userid' => $userAlias ) );


		return $url;
	}

	/**
	 * Retrieves the canvas url
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCanvasUrl( $options = array() , $xhtml = true )
	{
		$default	= array( 'layout' => 'canvas' , 'id' => $this->getAlias() );
		$options	= array_merge( $default , $options );

		$url 		= FRoute::apps( $options , $xhtml );

		return $url;
	}

	/**
	 * Determines if the app is accessible by the provided user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id
	 * @return
	 */
	public function accessible( $id )
	{
		if( !$this->isInstalled( $id ) && !$this->default )
		{
			return false;
		}

		return true;
	}

	public function getAppClass()
	{
		$root = $this->type == 'apps' ? SOCIAL_APPS : SOCIAL_FIELDS;
		$classType = $this->type == 'apps' ? 'App' : 'Fields';

		$path = $root . '/' . $this->group . '/' . $this->element . '/' . $this->element . '.php';

		if( !JFile::exists( $path ) )
		{
			return false;
		}

		include_once( $path );

		$className = 'Social' . ucfirst( $this->group ) . $classType . ucfirst( $this->element );

		$args = array( 'group' => $this->group, 'element' => $this->element );

		$class = new $className( $args );

		return $class;
	}
}

/**
 * Meta for apps
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialAppMeta
{
	public $author		= null;
	public $url 		= null;
	public $created		= null;
	public $version 	= null;
	public $desc 		= null;

	/**
	 * This is the parser.
	 * @var SocialParser
	 */
	private $parser		= null;

	public function __construct( $path )
	{
		$parser 	= Foundry::get( 'Parser' );
		$parser->load( $path );

		$this->parser 	= $parser;

		// Initialize variables.
		$this->init();
	}

	/**
	 * Initializes all the properties.
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function init()
	{
		// @TODO: Dynamically load all the methods that begins with "set"
		$this->setAuthor();
		$this->setURL();
		$this->setCreated();
		$this->setVersion();
		$this->setDescription();
	}

	/**
	 * Sets the app description
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function setDescription()
	{
		$desc 	= $this->parser->xpath( 'description' );

		if( !empty( $desc ) )
		{
			$this->desc 	= (string) $desc[0];
			$this->desc 	= trim( $this->desc );

			// Remove trailing whitespaces.
			$this->desc 	= JText::_( $this->desc );
		}
	}

	/**
	 * Sets the author name.
	 *
	 * @since	1.0
	 * @access	public
	 * @author 	Mark Lee <mark@stackideas.com>
	 */
	private function setAuthor()
	{
		$author		= $this->parser->xpath( 'author' );

		if( !empty( $author ) )
		{
			$this->author 	= (string) $author[ 0 ];
		}
	}

	/**
	 * Sets the author's url.
	 *
	 * @since	1.0
	 * @access	public
	 * @author 	Mark Lee <mark@stackideas.com>
	 */
	private function setURL()
	{
		$url		= $this->parser->xpath( 'url' );

		if( !empty( $url ) )
		{
			$this->url 	= (string) $url[ 0 ];
		}
	}

	/**
	 * Sets the creation date
	 *
	 * @since	1.0
	 * @access	public
	 * @author 	Mark Lee <mark@stackideas.com>
	 */
	private function setCreated()
	{
		$created		= $this->parser->xpath( 'created' );

		if( !empty( $created ) )
		{
			$this->created 	= (string) $created[ 0 ];
		}
	}

	/**
	 * Sets the version
	 *
	 * @since	1.0
	 * @access	public
	 * @author 	Mark Lee <mark@stackideas.com>
	 */
	private function setVersion()
	{
		$version		= $this->parser->xpath( 'version' );

		if( !empty( $version ) )
		{
			$this->version 	= (string) $version[ 0 ];
		}
	}
}
