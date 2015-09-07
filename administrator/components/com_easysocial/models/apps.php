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

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

class EasySocialModelApps extends EasySocialModel
{
	private $data			= null;
	protected $pagination	= null;

	protected $limitstart 	= null;
	protected $limit 		= null;

	function __construct( $config = array() )
	{
		parent::__construct( 'apps' , $config );
	}

	/**
	 * Removes app from the `#__social_apps_map` table
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The application id
	 * @return
	 */
	public function removeUserApp( $id )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_apps_map' );
		$sql->where( 'app_id' , $id );

		$db->setQuery( $sql );
		$state	= $db->Query();

		return $state;
	}

	/**
	 * Initializes all the generic states from the form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	protected function initStates()
	{
		$state 	= $this->getUserStateFromRequest( 'state' , 'all' );
		$filter	= $this->getUserStateFromRequest( 'filter' , 'all' );

		$this->setState( 'filter'	, $filter );
		$this->setState( 'state' 	, $state );

		parent::initStates();
	}

	/**
	 * Deletes existing views for specific app id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 		The application id.
	 * @return	boolean		True if success false otherwise.
	 */
	public function deleteExistingViews( $appId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->delete( '#__social_apps_views' );
		$sql->where( 'app_id', $appId );

		$db->setQuery( $sql );

		$state 		= $db->Query();

		return $state;
	}

	/**
	 * Deletes discovered items
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteDiscovered()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->delete( '#__social_apps' );
		$sql->where( 'state', SOCIAL_APP_STATE_DISCOVERED );

		$db->setQuery( $sql );

		$state 		= $db->Query();

		return $state;
	}

	/**
	 * Discover new applications on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function discover()
	{
		$paths	= array( SOCIAL_APPS . '/user' , SOCIAL_FIELDS . '/user' );
		$total 	= 0;

		// Go through each of the folders and look for any app folders.
		foreach( $paths as $path )
		{
			$folders 	= JFolder::folders( $path , '.' , false , true );

			foreach( $folders as $folder )
			{
				// Load the installer and pass in the folder
				$installer	= Foundry::get( 'Installer' );
				$installer->load( $folder );

				$state		= $installer->discover();

				if( $state )
				{
					$total	+= 1;
				}
			}
		}

		return $total;
	}


	/**
	 * Determines if the app has been installed by the provided user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The app's id.
	 * @param	int		The user's id.
	 * @return	bool	Result
	 */
	public function isAppInstalled( $element , $group , $type )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_apps' );
		$sql->column( 'COUNT(1)' , 'count' );
		$sql->where( 'element', $element );
		$sql->where( 'group', $group );
		$sql->where( 'type', $type );

		$db->setQuery( $sql );

		$installed 	= (bool) $db->loadResult();

		return $installed;
	}

	/**
	 * Determines if the app has been installed by the provided user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The app's id.
	 * @param	int		The user's id.
	 * @return	bool	Result
	 */
	public function isInstalled( $appId, $userId = null )
	{
		if( empty( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_apps_map' );
		$sql->where( 'app_id', $appId );
		$sql->where( 'uid', $userId );

		$db->setQuery( $sql->getTotalSql() );
		$installed 	= (bool) $db->loadResult();

		return $installed;
	}

	/**
	 * Retrieve a list of applications that is installed on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of configuration.
	 * @return	Array	An array of application object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getItemsWithState( $options = array() )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_apps' );

		// Determine if we should only fetch apps that are widgets
		$widget 	= isset( $options[ 'widget' ] ) ? $options[ 'widget' ] : false;

		if( $widget )
		{
			$sql->where( 'widget' , SOCIAL_STATE_PUBLISHED );
		}

		// Depending on type of apps.
		$filter		= $this->getState( 'filter' );

		if( $filter && $filter != 'all' )
		{
			$sql->where( 'type', $filter );
		}

		// Search filter
		$search 	= $this->getState( 'search' );

		if( $search )
		{
			$sql->where( 'title' , '%' . $search . '%' , 'LIKE' );
		}

		// Depending on group of apps.
		$group 		= isset( $options[ 'group' ] ) ? $options[ 'group' ] : '';

		if( $group )
		{
			$sql->where( 'group', $group );
		}

		// Discover apps
		$discover 	= isset( $options[ 'discover' ] ) ? $options[ 'discover' ] : '';

		if( $discover )
		{
			$sql->where( 'state', SOCIAL_APP_STATE_DISCOVERED );
		}
		else
		{
			// State filters
			$state 		= $this->getState( 'state' );

			if( $state !== '' && $state != 'all' )
			{
				$sql->where( 'state', $state );
			}

			$sql->where( '(' );
			$sql->where( 'state' , SOCIAL_STATE_PUBLISHED , '=' , 'OR' );
			$sql->where( 'state' , SOCIAL_STATE_UNPUBLISHED  , '=' , 'OR' );
			$sql->where( ')' );

			$sql->where( 'state', SOCIAL_APP_STATE_DISCOVERED , '!=' );
		}

		// Check for ordering
		$ordering 	= $this->getState( 'ordering' );

		if( $ordering )
		{
			$direction	 = $this->getState( 'direction' ) ? $this->getState( 'direction' ) : 'DESC';

			$sql->order( $ordering , $direction );
		}

		// Set the total
		$this->setTotal( $sql->getTotalSql() );

		// Get the result using parent's helper
		$result		= $this->getData( $sql->getSql() );

		if( !$result )
		{
			return $result;
		}

		$apps 	= array();

		foreach( $result as $row )
		{
			$appTable 	= Foundry::table( 'App' );
			$appTable->bind( $row );

			$apps[]		= $appTable;
		}

		return $apps;
	}


	/**
	 * Retrieve a list of applications that is installed on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of configuration.
	 * @return	Array	An array of application object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getItems( $options = array() )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_apps' );

		// Determine if we should only fetch apps that are widgets
		$widget 	= isset( $options[ 'widget' ] ) ? $options[ 'widget' ] : false;

		if( $widget )
		{
			$sql->where( 'widget' , SOCIAL_STATE_PUBLISHED );
		}

		// Depending on group of apps.
		$group 		= isset( $options[ 'group' ] ) ? $options[ 'group' ] : '';

		if( $group )
		{
			$sql->where( 'group', $group );
		}

		$db->setQuery( $sql );
		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$apps 	= array();

		foreach( $result as $row )
		{
			$appTable 	= Foundry::table( 'App' );
			$appTable->bind( $row );

			$apps[]		= $appTable;
		}

		return $apps;
	}

	/**
	 * Retrieve a list of SocialTableAppViews for an app.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The app's id.
	 * @return
	 */
	public function getViews( $appId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_apps_views' );
		$sql->where( 'app_id' , $appId );

		$db->setQuery( $sql );

		$rows 	= $db->loadObjectList();

		if( !$rows )
		{
			return false;
		}

		$views 	= array();

		foreach( $rows as $row )
		{
			$view 		= Foundry::table( 'AppView' );
			$view->bind( $row );

			$views[]	= $view;
		}

		return $views;
	}

	public function getElement( $type , $element , $lookup )
	{
		$path		= SOCIAL_MEDIA . DS . constant( 'SOCIAL_APPS_' . strtoupper( $type ) ) . DS . $element . DS . $element . '.xml';
		$data		= JText::_( 'Unknown' );
		$xml        = Foundry::get( 'Parser' )->read( $path );

		if( isset( $xml->{$lookup} ) )
		{
			$data   = $xml->{$lookup};
		}
		return $data;
	}

	/**
	 * Get's a list of folder and determines if the folder is writable.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Array	An array of stdClass objects.
	 *
	 */
	public function getDirectoryPermissions()
	{
		$jConfig 		= Foundry::jconfig();

		// Get a list of folders.
		$folders 		= array(
								$jConfig->getValue( 'tmp_path' ),
								SOCIAL_MEDIA,
								SOCIAL_MEDIA . '/fields',
								SOCIAL_MEDIA . '/applications'
							);

		$directories	= array();

		foreach( $folders as $folder )
		{
			$obj 			= new stdClass();
			$obj->path		= $folder;
			$obj->writable	= is_writable( $folder );

			$directories[]	= $obj;
		}

		return $directories;
	}

	/**
	 * Returns a list of field type applications that are installed and published.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options.
	 * @return	Array	An array of SocialTableField item.
	 */
	public function getApps( $options = array() , $debug = false )
	{
		$db 		= Foundry::db();

		$sql		= $db->sql();

		$sql->select( '#__social_apps', 'a' );
		$sql->column( 'a.*' );

		// If uid / key is passed in, we need to only fetch apps that are related to the uid / key.
		$uid 		= isset( $options[ 'uid' ] ) ? $options[ 'uid' ] : null;
		$key 		= isset( $options[ 'key' ] ) ? $options[ 'key' ] : null;

		if( !is_null( $uid ) && !is_null( $key ) )
		{
			$sql->join( '#__social_apps_map', 'b' );
			$sql->on( 'b.app_id', 'a.id' );
			$sql->on( 'b.uid', $uid );
			$sql->on( 'b.type', $key );

			$sql->where( 'a.state' , SOCIAL_STATE_PUBLISHED );
		}

		// Test if 'view' is provided. If view is provided, we only want to fetch apps for these views.
		$view 		= isset( $options[ 'view' ] ) ? $options[ 'view' ] : null;

		if( !is_null( $view ) )
		{
			$sql->innerjoin( '#__social_apps_views', 'c' );
			$sql->on( 'c.app_id', 'a.id' );
			$sql->on( 'c.view', $view );
		}

		// If type filter is provided, we need to filter the type.
		$type 		= isset( $options[ 'type' ] ) ? $options[ 'type' ] : null;

		if( !is_null( $type ) )
		{
			$sql->where( 'a.type', $type );
		}

		// If group filter is provided, we need to filter apps by group.
		$group 		= isset( $options[ 'group' ] ) ? $options[ 'group' ] : null;

		if( !is_null( $group ) )
		{
			$sql->where( 'a.group', $group );
		}

		// Detect if we should only pull apps that are installable
		$installable 	= isset( $options[ 'installable' ] ) ? $options[ 'installable' ] : null;

		if( !is_null( $installable ) )
		{
			$sql->where( '(' , '' , '' , 'AND' );
			$sql->where( 'a.installable' , $installable , '=' , 'AND' );
			$sql->where( 'a.default' , SOCIAL_STATE_PUBLISHED , '!=' , 'AND' );
			$sql->where( ')' );

			$sql->where( 'a.state' , SOCIAL_STATE_PUBLISHED );
		}

		// Check for widgets
		$widgets 	= isset( $options[ 'widget' ] ) ? $options[ 'widget' ] : null;

		if( $widgets )
		{
			$sql->where( 'a.widget' , $widgets );
		}

		// Check for core app
		$core		= isset( $options['core'] ) ? $options['core'] : null;

		if( !is_null( $core ) )
		{
			$sql->where( 'a.core', $core );
		}

		if( !is_null( $uid ) && !is_null( $key ) )
		{
			$sql->where( '(' , '' , '' , 'AND' );
			$sql->where( 'a.default' , SOCIAL_STATE_PUBLISHED , '=' , 'OR' );
			$sql->where( 'b.id' , null , 'IS NOT' , 'OR' );
			$sql->where( ')' );
		}


		if( !$uid && !$key && is_null( $installable) )
		{
			$sql->where( 'a.default' , SOCIAL_STATE_PUBLISHED , '=' , 'OR' );
		}

		$sort		= isset( $options['sort'] ) ? $options['sort'] : null;
		$order		= isset( $options['order'] ) ? $options['order'] : 'asc';

		if( !is_null( $sort ) )
		{
			$sql->order( $sort, $order );
		}

		// Set the total query.
		$this->setTotal( $sql->getTotalSql() );

		// For debugging purposes only.
		if( $debug )
		{
			echo $sql->debug();
			exit;
		}

		// echo $sql;

		// Get data
		$result 	= $this->getData( $sql->getSql(), false );

		if( !$result )
		{
			return false;
		}

		$apps 	= array();

		foreach( $result as $row )
		{
			$app 		= Foundry::table( 'App' );
			$app->bind( $row );

			// Check if the apps should really have such view
			if( isset( $options[ 'view' ] ) )
			{
				if( $app->appListing( $options[ 'view' ] ) )
				{
					$apps[]	= $app;
				}
			}
			else
			{
				$apps[]		= $app;
			}
		}

		return $apps;
	}


	/**
	 * Returns a list of user type applications that are installed and published.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array	An array of SocialTableApps item.
	 */
	public function getUserApps( $userId, $view = '' )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_apps', 'a' );
		$sql->column( 'a.*' );
		$sql->innerjoin( '#__social_apps_map', 'b' );
		$sql->on( 'b.app_id', 'a.id' );
		$sql->on( 'b.uid', $userId );
		$sql->on( 'b.type', SOCIAL_APPS_GROUP_USER );

		// Test if 'view' is provided. If view is provided, we only want to fetch apps for these views.
		if( $view )
		{
			$sql->innerjoin( '#__social_apps_views', 'c' );
			$sql->on( 'c.app_id', 'a.id' );
			$sql->on( 'c.view', $view );
		}

		$sql->where( 'a.state', SOCIAL_STATE_PUBLISHED );
		$sql->where( 'a.type', SOCIAL_APPS_TYPE_APPS );
		$sql->where( 'a.group', SOCIAL_APPS_GROUP_USER );

		// Set the total query.
		$this->setTotal( $sql->getTotalSql() );

		// Get data
		$result 	= $this->getData( $sql->getSql(), false );

		if( !$result )
		{
			return false;
		}

		$apps 	= array();

		foreach( $result as $row )
		{
			$app 		= Foundry::table( 'App' );
			$app->bind( $row );

			$apps[]		= $app;
		}

		return $apps;
	}



	/**
	 * Retrieve a list of core apps from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getDefaultApps( $config = array() )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_apps', 'a' );
		$sql->column( 'a.*' );

		$sql->where( '(' );
		$sql->where( 'a.core', '1' );
		$sql->where( 'a.default', '1', '=', 'or' );
		$sql->where( ')' );
		$sql->where( 'a.state', SOCIAL_STATE_PUBLISHED );

		// If caller wants only specific type of apps.
		if( isset( $config[ 'type' ] ) )
		{
			$sql->where( 'a.type', $config[ 'type' ] );
		}

		$db->setQuery( $sql );

		$fields	= $db->loadObjectList();

		return $fields;
	}

	/**
	 * Returns a list of tending apps from the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getTrendingApps( $options = array() )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_apps', 'a' );
		$sql->column( 'a.*' );

		$sql->leftjoin( '#__social_apps_map', 'b' );
		$sql->on( 'a.id', 'b.app_id' );

		$sql->where( 'a.state', SOCIAL_STATE_PUBLISHED );

		if( isset( $options['type'] ) )
		{
			$sql->where( 'a.type', $options['type'] );
		}

		if( isset( $options['timefrom'] ) )
		{
			$sql->where( 'b.created', Foundry::date( $options['timefrom'] )->toSql(), '>=' );
		}

		if( isset( $options['timeto'] ) )
		{
			$sql->where( 'b.created', Foundry::date( $options['timeto'] )->toSql(), '<=' );
		}

		// Determines if caller wants to only display the installable apps
		$installable 	= isset( $options[ 'installable' ] ) ? $options[ 'installable' ] : '';


		if( $installable )
		{
			$sql->where( '(' , '' , '' , 'AND' );
			$sql->where( 'a.installable' , $installable , '=' , 'AND' );
			$sql->where( 'a.default' , SOCIAL_STATE_PUBLISHED , '!=' , 'AND' );
			$sql->where( ')' );

			$sql->where( 'a.state' , SOCIAL_STATE_PUBLISHED );
		}

		$sql->group( 'a.id' );
		$sql->order( 'b.app_id', 'desc', 'count' );

		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		$apps = array();

		foreach( $result as $row )
		{
			$app 		= Foundry::table( 'App' );
			$app->bind( $row );

			$apps[]		= $app;
		}

		return $apps;
	}
}
