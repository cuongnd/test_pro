<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );


require_once( dirname( __FILE__ ) . '/controller.php' );

class EasySocialControllerInstallation extends EasySocialSetupController
{
	/**
	 * Retrieves the main menu item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getMainMenuType()
	{
		require_once( JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php' );

		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__menu' );
		$sql->column( 'menutype' );
		$sql->where( 'home' , '1' );

		$db->setQuery( $sql );
		$menuType	= $db->loadResult();

		return $menuType;
	}

	/**
	 * Install default custom profiles and fields
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installProfiles()
	{
		// Include foundry framework
		$this->foundry();

		// Get the temporary path from the server.
		$tmpPath 		= JRequest::getVar( 'path' );

		// There should be a queries.zip archive in the archive.
		$archivePath 	= $tmpPath . '/fields.zip';

		// Where the badges should reside after extraction
		$path 			= $tmpPath . '/fields';

		// Extract badges
		$state			= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			 $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_FIELDS' ) , false ) );
			 exit;
		}

		// Get list of fields
		$fields 	= JFolder::folders( $path , '.' , false , true );
		$total 		= 0;
		$results 	= array();

		if( $fields )
		{
			foreach( $fields as $field )
			{
				// Install the field app
				$results[]	= $this->installField( $field );

				// Create the field for the custom profile.
				$total		+= 1;
			}
		}

		// Create the default custom profile first.
		$results[]			= $this->createCustomProfile();

		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= '';

		foreach( $results as $obj )
		{
			$class 	= $obj->state ? 'success' : 'error';

			$result->message 	.= '<div class="text-' . $class . '">' . $obj->message . '</div>';
		}


		return $this->output( $result );
	}

	/**
	 * Creates the default custom profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createCustomProfile()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_profiles' );
		$sql->column( 'id' );
		$sql->limit( 0 , 1 );

		$db->setQuery( $sql );
		$id 	= $db->loadResult();

		// We don't have to do anything since there's already a default profile
		if( $id )
		{
			// Store the default profile for Facebook
			$this->updateConfig( 'oauth.facebook.registration.profile' , $id );

			$result 	= $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_CREATE_DEFAULT_PROFILE_EXISTS' ) , true );

			return $result;
		}

		// If it doesn't exist, we'll have to create it.
		$profile 				= Foundry::table( 'Profile' );
		$profile->title 		= JText::_( 'COM_EASYSOCIAL_INSTALLATION_DEFAULT_PROFILE_TITLE' );
		$profile->description	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_DEFAULT_PROFILE_DESC' );

		// Get the default user group that the site is configured and select this group as the default for this profile.
		$usersConfig 			= JComponentHelper::getParams( 'com_users' );
		$group 					= array( $usersConfig->get( 'new_usertype' ) );

		// Set the group for this default profile
		$profile->gid 			= Foundry::json()->encode( $group );

		$profile->default 		= 1;
		$profile->state 		= SOCIAL_STATE_PUBLISHED;

		// Set the default params for profile
		$params 	= Foundry::registry();
		$params->set( 'delete_account' , 0 );
		$params->set( 'theme' , 'wireframe' );
		$params->set( 'registration' , 'approvals' );
		$profile->params 		= $params->toString();

		// Try to save the profile.
		$state 	= $profile->store();

		if( !$state )
		{
			$result 	= $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_CREATE_DEFAULT_PROFILE' ) , false );

			return $result;
		}

		$this->updateConfig( 'oauth.facebook.registration.profile' , $profile->id );

		$result 	= $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_SUCCESS_CREATE_DEFAULT_PROFILE' ) , true );

		return $result;
	}

	/**
	 * Saves a configuration item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The key to save
	 * @param	mixed	The data to save
	 * @return
	 */
	public function updateConfig( $key , $value )
	{
		$this->foundry();

		$config 	= Foundry::config();
		$config->set( $key , $value );

		$jsonString 	= $config->toString();

		$configTable 	= Foundry::table( 'Config' );

		if( !$configTable->load( 'site' ) )
		{
			$configTable->type 	= 'site';
		}

		$configTable->set( 'value' , $jsonString );
		$configTable->store();
	}

	/**
	 * Installs a single custom field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installField( $path )
	{
		// Retrieve the installer library.
		$installer	= Foundry::get( 'Installer' );

		// Get the element
		$element 	= basename( $path );

		// Try to load the installation from path.
		$state 		= $installer->load( $path );

		// If there's an error, we need to log it down.
		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'APPS: Unable to install apps from directory ' . $path . ' because of the error ' . $installer->getError() );

			$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_FIELD_ERROR_LOADING_FIELD' , ucfirst( $element ) ) , false );

			return $result;
		}

		// Let's try to install it now.
		$app 	= $installer->install();

		// If there's an error installing, log this down.
		if( $app === false )
		{
			$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_FIELD_ERROR_INSTALLING_FIELD' , ucfirst( $element ) ) , false );

			return $result;
		}

		// Ensure that the app is published
		$app->state 	= SOCIAL_STATE_PUBLISHED;
		$app->store();

		$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_FIELD_SUCCESS_INSTALLING_FIELD' , ucfirst( $element ) ) , true );

		return $result;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createMenu()
	{
		// Include foundry framework
		$this->foundry();

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__extensions' , 'id' );
		$sql->where( 'element' , 'com_easysocial' );

		$db->setQuery( $sql );

		// Get the extension id
		$extensionId 	= $db->loadResult();

		// Get the main menu that is used on the site.
		$menuType			= $this->getMainMenuType();

		if( !$menuType )
		{
			return false;
		}

		$sql 	= $db->sql();

		$sql->select( '#__menu' );
		$sql->column( 'COUNT(1)' );
		$sql->where( 'link' , '%index.php?option=com_easysocial%' , 'LIKE' );
		$sql->where( 'type'	, 'component' );
		$sql->where( 'client_id'	, 0 );

		$db->setQuery( $sql );

		$exists	= $db->loadResult();

		if( $exists )
		{
			// we need to update all easysocial menu item with this new component id.
			$query = 'update `#__menu` set component_id = ' . $db->Quote( $extensionId );
			$query .= ' where `link` like ' . $db->Quote( '%index.php?option=com_easysocial%' );
			$query .= ' and `type` = ' . $db->Quote( 'component' );
			$query .= ' and `client_id` = ' . $db->Quote( '0' );

			$sql->clear();
			$sql->raw( $query );
			$db->setQuery( $sql );
			$db->query();

			return $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_SITE_MENU_UPDATED' ) , true );
		}

		$menu 					= JTable::getInstance( 'Menu' );
		$menu->menuType 		= $menuType;
		$menu->title 			= JText::_( 'COM_EASYSOCIAL_INSTALLATION_DEFAULT_MENU' );
		$menu->alias 			= 'community';
		$menu->path 			= 'easysocial';
		$menu->link 			= 'index.php?option=com_easysocial&view=unity';
		$menu->type 			= 'component';
		$menu->published 		= 1;
		$menu->parent_id 		= 1;
		$menu->component_id 	= $extensionId;
		$menu->client_id 		= 0;
		$menu->language 		= '*';

		$menu->setLocation( '1' , 'last-child' );

		$state 	= $menu->store();

		// @TODO: Assign modules to unity menu
		$this->installModulesMenu( $menu->id );

		$menu 					= JTable::getInstance( 'Menu' );
		$menu->menuType 		= $menuType;
		$menu->title 			= JText::_( 'COM_EASYSOCIAL_INSTALLATION_DEFAULT_MENU_DASHBOARD' );
		$menu->alias 			= 'community';
		$menu->path 			= 'easysocial';
		$menu->link 			= 'index.php?option=com_easysocial&view=dashboard';
		$menu->type 			= 'component';
		$menu->published 		= 1;
		$menu->parent_id 		= 1;
		$menu->component_id 	= $extensionId;
		$menu->client_id 		= 0;
		$menu->language 		= '*';

		$menu->setLocation( '1' , 'last-child' );

		$state 	= $menu->store();

		return $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_SITE_MENU_CREATED' ) , true );
	}


	/**
	 * install module and assign to unity view
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function installModulesMenu( $unityMenuId = null )
	{
		// Include foundry framework
		$this->foundry();

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$modulesToInstall = array();

		// register modules here.

		// online user
		$modSetting = new stdClass();
		$modSetting->title 		= 'Online Users';
		$modSetting->name 		= 'mod_easysocial_users';
		$modSetting->position 	= 'es-unity-sidebar-top';
		$modSetting->config 	= array('filter' 	=> 'online',
										'total' 	=> '5',
										'ordering' 	=> 'name',
										'direction' => 'asc' );
		$modulesToInstall[] 	= $modSetting;

		// Recent user
		$modSetting = new stdClass();
		$modSetting->title 		= 'Recent Users';
		$modSetting->name 		= 'mod_easysocial_users';
		$modSetting->position 	= 'es-unity-sidebar-top';
		$modSetting->config 	= array('filter' 	=> 'recent',
										'total' 	=> '5',
										'ordering' 	=> 'registerDate',
										'direction' => 'desc' );
		$modulesToInstall[] 	= $modSetting;

		// Recent albums
		$modSetting = new stdClass();
		$modSetting->title 		= 'Recent Albums';
		$modSetting->name 		= 'mod_easysocial_albums';
		$modSetting->position 	= 'es-unity-sidebar-bottom';
		$modSetting->config 	= array();
		$modulesToInstall[] 	= $modSetting;

		// leaderboard
		$modSetting = new stdClass();
		$modSetting->title 		= 'Leaderboard';
		$modSetting->name 		= 'mod_easysocial_leaderboard';
		$modSetting->position 	= 'es-unity-sidebar-bottom';
		$modSetting->config 	= array('total' => '5');
		$modulesToInstall[] 	= $modSetting;


		// real work here.
		foreach( $modulesToInstall as $module )
		{
			$jMod	= JTable::getInstance( 'Module' );

			$jMod->title 		= $module->title;
			$jMod->ordering 	= $this->getModuleOrdering( $module->position );
			$jMod->position 	= $module->position;
			$jMod->published 	= 1;
			$jMod->module 		= $module->name;
			$jMod->access 		= 1;

			if( $module->config )
			{
				$jMod->params 		= Foundry::json()->encode( $module->config );
			}
			else
			{
				$jMod->params 		= '';
			}

			$jMod->client_id 	= 0;
			$jMod->language 	= '*';

			$state = $jMod->store();

			if( $state && $unityMenuId )
			{
				// lets add into module menu.
				$modMenu = new stdClass();
				$modMenu->moduleid 	= $jMod->id;
				$modMenu->menuid 	= $unityMenuId;

				$state	= $db->insertObject( '#__modules_menu' , $modMenu );
			}

		}

	}


	/**
	 * get ordering based on the module position.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getModuleOrdering( $position )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$query = 'select `ordering` from `#__modules` where `position` = ' . $db->Quote( $position );
		$query .= ' order by `ordering` desc limit 1';
		$sql->raw( $query );

		$db->setQuery( $sql );

		$result = $db->loadResult();

		return ( $result ) ? $result + 1 : 1;

	}



	/**
	 * Post installation process
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function installPost()
	{
		$results	= array();

		$apiKey 	= JRequest::getVar( 'apikey' , '' );

		$this->updateConfig( 'general.key' , $apiKey );

		// Setup site menu.
		$results[]	= $this->createMenu( 'site' );

		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= '';

		foreach( $results as $obj )
		{
			$class 	= $obj->state ? 'success' : 'error';

			$result->message 	.= '<div class="text-' . $class . '">' . $obj->message . '</div>';
		}

		// Cleanup temporary files from the tmp folder
		$tmp 		= dirname( dirname( __FILE__ ) ) . '/tmp';
		$folders	= JFolder::folders( $tmp , '.' , false , true );

		if( $folders )
		{
			foreach( $folders as $folder )
			{
				// Try to delete the folder
				@JFolder::delete( $folder );
			}
		}

		$this->output( $result );
	}

	/**
	 * Install alert rules
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function installAlerts()
	{
		// Get the path to the defaults folder
		$path 			= JPATH_ADMINISTRATOR . '/components/com_easysocial/defaults/alerts';

		// Include foundry framework
		$this->foundry();

		// Retrieve the privacy model to scan for the path
		$model 	= Foundry::model( 'Alert' );

		// Scan and install privacy
		$total 	= 0;
		$files 	= JFolder::files( $path , '.alert' , false , true );

		if( $files )
		{
			foreach( $files as $file )
			{
				$model->install( $file );
				$total 	+= 1;
			}
		}

		return $this->output( $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ALERT_SUCCESS' , $total ) , true ) );
	}

	/**
	 * Install privacy items.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installPrivacy()
	{
		// Get the temporary path from the server.
		$tmpPath 		= JRequest::getVar( 'path' );

		// There should be a queries.zip archive in the archive.
		$archivePath 	= $tmpPath . '/privacy.zip';

		// Where the badges should reside after extraction
		$path 			= $tmpPath . '/privacy';

		// Extract badges
		$state 	= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			return $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_PRIVACY' ) , false ) );
		}

		// Include foundry framework
		$this->foundry();

		// Retrieve the privacy model to scan for the path
		$model 	= Foundry::model( 'Privacy' );

		// Scan and install privacy
		$totalPrivacy 	= 0;
		$files 			= JFolder::files( $path , '.privacy' , false , true );

		if( $files )
		{
			foreach( $files as $file )
			{
				$model->install( $file );
				$totalPrivacy 	+= 1;
			}
		}

		return $this->output( $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_PRIVACY_SUCCESS' , $totalPrivacy ) , true ) );
	}

	/**
	 * Install points on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installPoints()
	{
		// Get the temporary path from the server.
		$tmpPath 		= JRequest::getVar( 'path' );

		// There should be a queries.zip archive in the archive.
		$archivePath 	= $tmpPath . '/points.zip';

		// Where the badges should reside after extraction
		$path 			= $tmpPath . '/points';

		// Extract badges
		$state 	= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			return $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_POINTS' ) , false ) );
		}

		// Include foundry framework
		$this->foundry();

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Points' );

		// Scan and install badges
		$points = JFolder::files( $path , '.points' , true , true );

		$totalPoints 	= 0;

		if( $points )
		{
			foreach( $points as $point )
			{
				$model->install( $point );

				$totalPoints 	+= 1;
			}
		}

		return $this->output( $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_POINTS_SUCCESS' , $totalPoints ) , true ) );
	}

	/**
	 * Installation of plugins on the site
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function installPlugins()
	{
		// We need the foundry library here
		$this->foundry();

		// Get the path to the current installer archive
		$tmpPath 		= JRequest::getVar( 'path' );

		// Path to the archive
		$archivePath 	= $tmpPath . '/plugins.zip';

		// Where should the archive be extrated to
		$path 			= $tmpPath . '/plugins';

		$state 			= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			return $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_PLUGINS' ) , false ) );
		}

		// Get a list of apps we should install.
		$groups 	= JFolder::folders( $path , '.' , false , true );

		// Get Joomla's installer instance
		$installer = JInstaller::getInstance();

		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= '';

		foreach( $groups as $group )
		{
			// Now we find the plugin info
			$plugins 	= JFolder::folders( $group , '.' , false , true );
			$groupName 	= basename( $group );
			$groupName 	= ucfirst( $groupName );

			foreach( $plugins as $pluginPath )
			{
				$pluginName = basename( $pluginPath );
				$pluginName = ucfirst( $pluginName );

				// Allow overwriting existing plugins
				$installer->setOverwrite( true );
				$state 		= $installer->install( $pluginPath );

				// Load the plugin and ensure that it's published
				if( $state )
				{
					$plugin 	= JTable::getInstance( 'extension' );
					$plugin->load( array( 'folder' => strtolower( $groupName ) , 'element' => strtolower( $pluginName ) ) );

					$plugin->state 		= true;
					$plugin->enabled	= true;
					$plugin->store();
				}

				$message 	= $state ? JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_SUCCESS_PLUGIN' , $groupName , $pluginName ) : JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ERROR_PLUGIN' , $groupName , $pluginName );

				$class 		= $state ? 'success' : 'error';

				$result->message 	.= '<div class="text-' . $class . '">' . $message . '</div>';
			}
		}

		return $this->output( $result );
	}

	/**
	 * Installation of modules on the site
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function installModules()
	{
		// We need the foundry library here
		$this->foundry();

		// Get the path to the current installer archive
		$tmpPath 		= JRequest::getVar( 'path' );

		// Path to the archive
		$archivePath 	= $tmpPath . '/modules.zip';

		if( !JFile::exists( $archivePath ) )
		{
			return $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_NO_MODULES_AVAILABLE' ) , true ) );
		}
		// Where should the archive be extrated to
		$path 			= $tmpPath . '/modules';

		$state 			= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			return $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_MODULES' ) , false ) );
		}

		// Get a list of apps we should install.
		$modules 	= JFolder::folders( $path , '.' , false , true );


		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= '';

		foreach( $modules as $module )
		{
			$moduleName 	= basename( $module );

			// Get Joomla's installer instance
			$installer 	= new JInstaller();

			// Allow overwriting existing plugins
			$installer->setOverwrite( true );
			$state 		= $installer->install( $module );

			if( $state )
			{
				$db = Foundry::db();
				$sql = $db->sql();

				$query = 'update `#__extensions` set `access` = 1';
				$query .= ' where `type` = ' . $db->Quote( 'module' );
				$query .= ' and `element` = ' . $db->Quote( $moduleName );
				$query .= ' and `access` = ' . $db->Quote( '0' );

				$sql->clear();
				$sql->raw( $query );
				$db->setQuery( $sql );
				$db->query();

				// we need to check if this module record already exists in module_menu or not. if not, lets create one for this module.
				$query = 'select a.`id`, b.`moduleid` from #__modules as a';
				$query .= ' left join `#__modules_menu` as b on a.`id` = b.`moduleid`';
				$query .= ' where a.`module` = ' . $db->Quote( $moduleName );
				$query .= ' and b.`moduleid` is null';

				$sql->clear();
				$sql->raw( $query );
				$db->setQuery( $sql );

				$results = $db->loadObjectList();

				if( $results )
				{
					foreach( $results as $item )
					{
						// lets add into module menu.
						$modMenu = new stdClass();
						$modMenu->moduleid 	= $item->id;
						$modMenu->menuid 	= 0;

						$db->insertObject( '#__modules_menu' , $modMenu );
					}
				}

			}

			$message 	= $state ? JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_SUCCESS_MODULE' , $moduleName ) : JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ERROR_MODULE' , $moduleName );

			$class 		= $state ? 'success' : 'error';

			$result->message 	.= '<div class="text-' . $class . '">' . $message . '</div>';
		}

		return $this->output( $result );
	}

	/**
	 * Install badges on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installBadges()
	{
		// Get the temporary path from the server.
		$tmpPath 		= JRequest::getVar( 'path' );

		// There should be a queries.zip archive in the archive.
		$archivePath 	= $tmpPath . '/badges.zip';

		// Where the badges should reside after extraction
		$path 			= $tmpPath . '/badges';

		// Extract badges
		$state 	= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			return $this->output( $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_BADGES' ) , false ) );
		}

		// Include foundry framework
		$this->foundry();

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Badges' );

		// Scan and install badges
		$badges = JFolder::files( $path , '.badge' , true , true );

		$totalBadges 	= 0;

		if( $badges )
		{
			foreach( $badges as $badge )
			{
				$model->install( $badge );

				$totalBadges 	+= 1;
			}
		}

		return $this->output( $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_BADGES_SUCCESS' , $totalBadges ) , true ) );
	}

	/**
	 * Performs the installation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function install()
	{
		$item 	= JRequest::getWord( 'item' , '' );

		$method	= 'install' . ucfirst( $item );

		$this->$method();
	}

	/**
	 * Responsible to install apps
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installApps()
	{
		// Get the group of apps to install.
		$group	 = JRequest::getVar( 'group' );

		// Get the temporary path to the archive
		$tmpPath 		= JRequest::getVar( 'path' );

		// Get the archive path
		$archivePath 	= $tmpPath . '/' . $group . 'apps.zip';

		// Where the extracted items should reside.
		$path 			= $tmpPath . '/' . $group . 'apps';

		// Detect if the target folder exists
		$target		= JPATH_ROOT . '/media/com_easysocial/apps/' . $group;

		// Try to extract the archive first
		$state 		= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_APPS' , $group );

			return $this->output( $result );
		}

		// If the apps folder does not exist, create it first.
		if( !JFolder::exists( $target ) )
		{
			$state 	= JFolder::create( $target );

			if( !$state )
			{
				$result 			= new stdClass();
				$result->state 		= false;
				$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ERROR_CREATE_APPS_FOLDER' , $target );

				return $this->output( $result );
			}
		}

		// Get a list of apps within this folder.
		$apps 		= JFolder::folders( $path , '.' , false , true );

		$totalApps 	= 0;

		// If there are no apps to install, just silently continue
		if( !$apps )
		{
			$result 			= new stdClass();
			$result->state 		= true;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_APPS_NO_APPS' );

			return $this->output( $result );
		}

		$results	= array();

		// Go through the list of apps on the site and try to install them.
		foreach( $apps as $app )
		{
			$results[]	= $this->installApp( $app , $target );

			$totalApps 	+= 1;
		}

		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= '';

		foreach( $results as $obj )
		{
			$class 	= $obj->state ? 'success' : 'error';

			$result->message 	.= '<div class="text-' . $class . '">' . $obj->message . '</div>';
		}

		return $this->output( $result );
	}

	/**
	 * Installs Single Application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installApp( $appArchivePath , $target )
	{

		// Get the element of the app
		$element 	= basename( $appArchivePath );
		$element 	= str_ireplace( '.zip' , '' , $element );

		// // @debug
		// // 5128 Debug purposes. Remove this when not debugging!
		// $result 			= new stdClass();
		// $result->state		= true;
		// $result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_APPS_INSTALLED_APP_SUCCESS' , $element );

		// return $result;

		// Get the installation source folder.
		$path 		= dirname( $appArchivePath ) . '/' . $element;

		// Include core library
		require_once( JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php' );

		// Get installer library
		$installer 	= Foundry::get( 'Installer' );

		// Try to load the installation from path.
		$state 		= $installer->load( $path );

		// Try to load and see if the previous app already has a record
		$oldApp 	= Foundry::table( 'App' );
		$appExists	= $oldApp->load( array( 'type' => SOCIAL_TYPE_APPS , 'element' => $element ) );

		// If there's an error with this app, we should silently continue
		if( !$state )
		{
			$result 			= new stdClass();
			$result->state		= false;
			$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ERROR_LOADING_APP' , $element );

			return $result;
		}

		// Let's try to install the app.
		$app 	= $installer->install();

		// If there's an error with this app, we should silently continue
		if( $app === false )
		{
			$result 			= new stdClass();
			$result->state		= false;
			$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_ERROR_INSTALLING_APP' , $element );

			return $result;
		}

		$app->state			= $appExists ? $oldApp->state : SOCIAL_STATE_PUBLISHED;
		$app->store();

		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_APPS_INSTALLED_APP_SUCCESS' , $element );

		return $result;
	}

	/**
	 * Responsible to copy the necessary files over.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installCopy()
	{
		$type 			= JRequest::getVar( 'type' , '' );

		// Get the temporary path from the server.
		$tmpPath 		= JRequest::getVar( 'path' );

		// Get the path to the zip file
		$archivePath 	= $tmpPath . '/' . $type . '.zip';

		// Where the extracted items should reside
		$path 		= $tmpPath . '/' . $type;

		// Uncomment this to debug.
		// Debugging purposes only so that it will not overwrite our files that we are trying to debug in the respective area.
		// return $this->output( $this->getResultObj( 'ok' , true )  );

		// Extract the admin folder
		$state 		= JArchive::extract( $archivePath , $path );

		if( !$state )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_COPY_ERROR_UNABLE_EXTRACT' , $type );

			return $this->output( $result );
		}

		// Look for files in this path
		$files 		= JFolder::files( $path , '.' , false , true );

		// Look for folders in this path
		$folders	= JFolder::folders( $path , '.' , false , true );

		// Construct the target path first.
		switch( $type )
		{
			case 'admin':
				$target		= JPATH_ROOT . '/administrator/components/com_easysocial';
				break;
			case 'site' :
				$target 	= JPATH_ROOT . '/components/com_easysocial';
				break;

			case 'languages':

					$adminPath 	= JPATH_ADMINISTRATOR . '/language/en-GB/';
					$sitePath 	= JPATH_ROOT . '/language/en-GB/';

					// Copy admin files over
					JFile::copy( $archivePath . '/en-GB.com_easysocial.ini' , $adminPath . '/en-GB.com_easysocial.ini' , '' , true );
					JFile::copy( $archivePath . '/en-GB.com_easysocial.sys.ini' , $adminPath . '/en-GB.com_easysocial.sys.ini' , '' , true );

					// Copy site files over
					JFile::copy( $archivePath . '/en-GB.com_easysocial.ini' , $sitePath , '' , true );

					$result 		= new stdClass();
					$result->state	= true;
					
					$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_LANGUAGES_UPDATED' );
					return $this->output( $result );
					
				break;

			case 'media':
				$target 	= JPATH_ROOT . '/media/com_easysocial';
				break;
			case 'foundry':

				// Should we be overwriting the foundry folder.
				$overwrite 	= false;

				// Check the current version of Foundry installed and determine if we should overwrite foundry.
				$foundryVersion 			= '3.1';
				$currentFoundryVersion 		= JPATH_ROOT . '/media/foundry/' . $foundryVersion . '/version';
				$exists 					= JFile::exists( $currentFoundryVersion );

				if( !$exists )
				{
					$target 	= $this->makeFoundryFolders( $foundryVersion );
				}
				else
				{
					// If foundry exists, do a version compare and see if we should overwrite.
					$target 					= JPATH_ROOT . '/media/foundry/' . $foundryVersion;

					// Get the current foundry version
					$currentFoundryVersion 		= JFile::read( $currentFoundryVersion );

					// Get the incoming version
					$incomingFoundryVersion 	= JFile::read( $path . '/version' );

					$requiresUpdating 			= version_compare( $currentFoundryVersion , $incomingFoundryVersion );

					if( $requiresUpdating <= 0 )
					{
						JFolder::copy( $path , $target , '' , true );

						$result 		= new stdClass();
						$result->state	= true;

						$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_COPY_OVERWRITE_FOUNDRY_FILES_SUCCESS' , $incomingFoundryVersion );
						return $this->output( $result );
					}

					// Otherwise, there's nothing to do here.
					$result 		= new stdClass();
					$result->state	= true;

					$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_FOUNDRY_NO_CHANGES' , $incomingFoundryVersion );
					return $this->output( $result );
				}


				break;
		}

		// Ensure that the target folder exists
		if( !JFolder::exists( $target ) )
		{
			JFolder::create( $target );
		}

		// Scan for files in the folder
		$totalFiles 	= 0;

		foreach( $files as $file )
		{
			$name 		= basename( $file );
			$targetFile	= $target . '/' . $name;

			JFile::copy( $file , $targetFile );

			$totalFiles 	+=1;
		}

		// Scan for folders in this folder
		$totalFolders 	= 0;
		foreach( $folders as $folder )
		{
			$name 			= basename( $folder );
			$targetFolder	= $target . '/' . $name;

			// Try to copy the folder over
			JFolder::copy( $folder , $targetFolder , '' , true );

			$totalFolders 	+= 1;
		}

		$result 		= new stdClass();
		$result->state	= true;

		$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_COPY_FILES_SUCCESS' , $totalFiles, $totalFolders );

		return $this->output( $result );
	}

	/**
	 * Create foundry folders given the current version
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function makeFoundryFolders( $version )
	{
		$version 		= explode( '.' , $version );
		$majorVersion	= $version[ 0 ] . '.' . $version[ 1 ];
		$path 			= JPATH_ROOT . '/media/foundry/' . $majorVersion;
		$state 			= true;

		if( !JFolder::exists( $path ) )
		{
			$state = JFolder::create( $path );

			if( !$state )
			{
				$result 			= new stdClass();
				$result->state		= false;
				$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_UNABLE_TO_CREATE_FOUNDRY_FOLDER' , $path );

				return $this->output( $result );
			}
		}

		return $path;
	}

	/**
	 * Perform installation of SQL queries
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function installSQL()
	{
		// Get the temporary path from the server.
		$tmpPath 	= JRequest::getVar( 'path' );

		// There should be a queries.zip archive in the archive.
		$tmpQueriesPath 	= $tmpPath . '/queries.zip';

		// Extract the queries
		$path 				= $tmpPath . '/queries';

		// Check if this folder exists.
		if( JFolder::exists( $path ) )
		{
			JFolder::delete( $path );
		}

		$state 	= JArchive::extract( $tmpQueriesPath , $path );

		if( !$state )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_UNABLE_EXTRACT_QUERIES' );

			return $this->output( $result );
		}

		// Get the list of files in the folder.
		$queryFiles 	= JFolder::files( $path , '.' , true , true );

		// When there are no queries file, we should just display a proper warning instead of exit
		if( !$queryFiles )
		{
			$result 			= new stdClass();
			$result->state 		= true;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EMPTY_QUERIES_FOLDER' );

			return $this->output( $result );
		}

		$db 		= JFactory::getDBO();
		$total 		= 0;

		foreach( $queryFiles as $file )
		{
			$contents 	= JFile::read( $file );

			$queries	= JInstallerHelper::splitSql( $contents );


			foreach( $queries as $query )
			{
				$query 	= trim( $query );

				if( !empty( $query ) )
				{
					$db->setQuery( $query );

					$db->execute();
				}

			}

			$total 	+= 1;
		}

		$result 			= new stdClass();
		$result->state		= true;
		$result->message	= JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_SQL_EXECUTED_SUCCESS' , $total );

		return $this->output( $result );
	}

	/**
	 * Downloads the file from the server
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function download()
	{
		// Check the api key from the request
		$apiKey 	= JRequest::getVar( 'apikey' , '' );
		$license	= JRequest::getVar( 'license' , '' );

		// If the user is updating, we always need to get the latest version.
		$update 	= JRequest::getBool( 'update' , false );

		// Get information about the current release.
		$info 		= $this->getInfo( $update );

		if( !$info )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_REQUEST_INFO' );

			$this->output( $result );
			exit;
		}

		if( isset( $info->error ) && $info->error == 408 )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= $info->message;

			$this->output( $result );
			exit;
		}

		// Download the component installer.
		$storage 	= $this->getDownloadFile( $info , $apiKey , $license );

		if( $storage === false )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_DOWNLOADING_INSTALLER' );

			$this->output( $result );
			exit;
		}

		// Get the md5 hash of the stored file
		$hash 		= md5_file( $storage );

		// Check if the md5 check sum matches the one provided from the server.
		if( !in_array( $hash , $info->md5 ) )
		{
			$result 	= new stdClass();
			$result->state 		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_MD5_CHECKSUM' );

			$this->output( $result );
			exit;
		}

		// Extract files here.
		$tmp 		= ES_TMP . '/com_easysocial_v' . $info->version;

		if( JFolder::exists( $tmp ) )
		{
			JFolder::delete( $tmp );
		}

		// Try to extract the files
		$state 		= JArchive::extract( $storage , $tmp );

		if( !$state )
		{
			$result 			= new stdClass();
			$result->state		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_ERRORS' );

			$this->output( $result );
			exit;
		}

		$result 	= new stdClass();

		$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ARCHIVE_DOWNLOADED_SUCCESS' );
		$result->state 		= $state;
		$result->path 		= $tmp;

		header('Content-type: text/x-json; UTF-8');
		echo json_encode( $result );
		exit;
	}

	/**
	 * For users who uploaded the installer and needs a manual extraction
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function extract()
	{
		// Check the api key from the request
		$apiKey 	= JRequest::getVar( 'apikey' , '' );

		// Get the package
		$package 	= JRequest::getVar( 'package' , '' );

		// Get information about the current release.
		$info 		= $this->getInfo();

		$storage 	= ES_PACKAGES . '/' . $package;
		$exists 	= JFile::exists( $storage );

		// Test if package really exists
		if( !$exists )
		{
			$result 			= new stdClass();
			$result->state 		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_PACKAGE_DOESNT_EXIST' );

			$this->output( $result );
			exit;
		}

		// Extract files here.
		$tmp 		= ES_TMP . '/com_easysocial_v' . $info->version;

		if( JFolder::exists( $tmp ) )
		{
			JFolder::delete( $tmp );
		}

		// Try to extract the files
		$state 		= JArchive::extract( $storage , $tmp );

		if( !$state )
		{
			$result 			= new stdClass();
			$result->state		= false;
			$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_EXTRACT_ERRORS' );

			$this->output( $result );
			exit;
		}

		$result 	= new stdClass();

		$result->message	= JText::_( 'COM_EASYSOCIAL_INSTALLATION_EXTRACT_SUCCESS' );
		$result->state 		= $state;
		$result->path 		= $tmp;

		header('Content-type: text/x-json; UTF-8');
		echo json_encode( $result );
		exit;
	}

	/**
	 * Executes the file download from the server.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object 	The manifest data from server.
	 * @param	string	The user's api key.
	 * @param	string	The license key to use for this installation
	 * @return	mixed	false if download failed or path to the file if success.
	 */
	public function getDownloadFile( $info , $apikey , $license )
	{
		// Request the server to download the file.
		$url 	= $info->install;

		// Get the latest version
		$ch 	= curl_init( $info->install );

		// We need to pass the api keys to the server
		curl_setopt( $ch , CURLOPT_POST , true );
		curl_setopt( $ch , CURLOPT_POSTFIELDS , 'extension=easysocial&apikey=' . $apikey . '&license=' . $license . '&version=' . $info->version );

		// We don't want the output immediately.
		curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );

		// Set a large timeout incase the server fails to download in time.
		curl_setopt( $ch , CURLOPT_TIMEOUT , 30000 );

		// Get the response of the server
		$result 	= curl_exec( $ch );

		// Close the connection
		curl_close( $ch );

		// Set the storage page
		$storage	= ES_PACKAGES . '/easysocial_v' . $info->version . '_component.zip';

		// Delete zip archive if it already exists.
		if( JFile::exists( $storage ) )
		{
			JFile::delete( $storage );
		}

		// Debug md5
		// $result 	= $result . 'somedebugcontents';

		$state		= JFile::write( $storage , $result );

		if( !$state )
		{
			return false;
		}

		return $storage;
	}
}



