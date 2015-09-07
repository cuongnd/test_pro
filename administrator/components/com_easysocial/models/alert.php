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

Foundry::import( 'admin:/includes/model' );

class EasySocialModelAlert extends EasySocialModel
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	function __construct( $config = array() )
	{
		parent::__construct( 'alert' , $config );
	}

	/**
	 * Given a path to the file, install the points.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The path to the .points file.
	 * @return	bool		True if success false otherwise.
	 */
	public function install( $file )
	{
		// Import platform's file library.
		jimport( 'joomla.filesystem.file' );

		// Convert the contents to an object
		$alerts 	= Foundry::makeObject( $file );
		$result 	= array();

		if( $alerts )
		{
			foreach( $alerts as $alert )
			{
				$table 		= Foundry::table( 'Alert' );
				$exists		= $table->load( array( 'element' => $alert->element , 'rule' => $alert->rule ) );

				if( !$exists )
				{
					$table->element		= $alert->element;
					$table->rule 		= $alert->rule;
					$table->created 	= Foundry::date()->toSql();
					$table->app 		= isset( $alert->app ) ? $alert->app : false;
					$table->field 		= isset( $alert->field ) ? $alert->field : false;
					$table->group 		= isset( $alert->group ) ? $alert->group : false;
					$table->extension 	= isset( $alert->extension ) ? $alert->extension : false;

					if( !isset( $alert->value ) )
					{
						$table->email 	= true;
						$table->system 	= true;
					}
					else
					{
						$table->email 	= $alert->value->email;
						$table->system 	= $alert->value->system;
					}

					if( !isset( $alert->core ) && !isset( $alert->app ) )
					{
						$table->core 	= true;
					}
					else
					{
						$table->core	= isset( $alert->core ) ? $alert->core : 0;
						$table->app 	= isset( $alert->app ) ? $alert->app : 0;
					}

					$result[] = $table->store();
				}
			}
		}

		return $result;
	}

	/**
	 * Scans through the given path and see if there are any *.points file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The path type. E.g: components , plugins, apps , modules
	 * @return
	 */
	public function scan( $path )
	{
		jimport( 'joomla.filesystem.folder' );

		$files 	= array();

		if( $path == 'admin' || $path == 'components' )
		{
			$directory	= JPATH_ROOT . '/administrator/components';
		}

		if( $path == 'site' )
		{
			$directory	= JPATH_ROOT . '/components';
		}

		if( $path == 'apps' )
		{
			$directory 	= SOCIAL_APPS;
		}

		if( $path == 'fields' )
		{
			$directory 	= SOCIAL_FIELDS;
		}

		if( $path == 'plugins' )
		{
			$directory 	= JPATH_ROOT . '/plugins';
		}

		if( $path == 'modules' )
		{
			$directory	 = JPATH_ROOT . '/modules';
		}

		$files 		= JFolder::files( $directory , '.alert$' , true , true );

		return $files;
	}

	/**
	 * Retrieve a list of alert rules from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options
	 * @return	Array	An array of SocialBadgeTable objects.
	 */
	public function getItems( $options = array() )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_alert' );

		// Check for search
		$search 	= $this->getState( 'search' );

		if( $search )
		{
			$sql->where( 'title' , '%' . $search . '%' , 'LIKE' );
		}

		// Check for ordering
		$ordering 	= $this->getState( 'ordering' );

		if( $ordering )
		{
			$direction	 = $this->getState( 'direction' ) ? $this->getState( 'direction' ) : 'DESC';

			$sql->order( $ordering , $direction );
		}

		$limit 	= $this->getState( 'limit' );

		if( $limit != 0 )
		{
			// Set the total number of items.
			$this->setTotal( $sql->getSql() , true );

			$result 	= $this->getData( $sql->getSql() );
		}
		else
		{
			$db->setQuery( $sql );
			$result 	= $db->loadObjectList();
		}

		if( !$result )
		{
			return $result;
		}

		$alerts 	= array();

		foreach( $result as $row )
		{
			$alert 	= Foundry::table( 'Alert' );
			$alert->bind( $row );

			$alerts[]	= $alert;
		}

		return $alerts;
	}

	public function getRules( $element )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert' );
		$sql->where( 'element', $element );

		$db->setQuery( $sql );
		$result = $db->loadObjectList();

		$alerts = array();

		foreach( $result as $row )
		{
			$table = Foundry::table( 'alert' );
			$table->bind( $row );

			$alerts[] = $table;
		}

		return $alerts;
	}

	public function getCoreRules()
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert' );
		$sql->where( 'core', 1 );

		$db->setQuery( $sql );
		$result = $db->loadObjectList();

		$alerts = array();

		foreach( $result as $row )
		{
			$table = Foundry::table( 'alert' );
			$table->bind( $row );

			$alerts[] = $table;
		}

		return $alerts;
	}

	public function getElements()
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert' );
		$sql->column( 'element', 'element', 'distinct' );
		$sql->column( 'core' );
		$sql->order( 'core', 'desc' );
		$sql->order( 'element' );

		$db->setQuery( $sql );

		return $db->loadObjectList();
	}

	public function getUsers( $element, $rule )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert_map', 'a' );
		$sql->column( 'a.user_id' );
		$sql->column( 'a.email' );
		$sql->column( 'a.system' );

		$sql->leftjoin( '#__social_alert', 'b' );
		$sql->on( 'b.id', 'a.alert_id' );

		$sql->where( 'b.element', $element );
		$sql->where( 'b.rule', $rule );

		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		return $result;
	}

	public function getCoreUserSettings( $uid, $options = array() )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert', 'a' )
			->column( 'a.id' )
			->column( 'a.element' )
			->column( 'a.rule' )
			->column( 'a.core' )
			->column( 'a.extension' )
			->column( 'a.created' )
			->column( 'a.app' )
			->column( 'a.group' )
			->column( 'a.field' )
			->column( 'a.email' )
			->column( 'a.system' )
			->column( 'b.email', 'user_email' )
			->column( 'b.system', 'user_system' )
			->leftjoin( '#__social_alert_map', 'b' )
			->on( 'a.id', 'b.alert_id' )
			->on( 'b.user_id', $uid )
			->where( 'a.core', 1 )
			->where( 'a.app', 0 )
			->order( 'a.element' );

		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		foreach( $result as &$row )
		{
			$this->mergeUserSettings( $row );
		}

		return $result;
	}

	public function getAppsUserSettings( $uid, $options = array() )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert', 'a' )
			->column( 'a.id' )
			->column( 'a.element' )
			->column( 'a.rule' )
			->column( 'a.core' )
			->column( 'a.extension' )
			->column( 'a.created' )
			->column( 'a.app' )
			->column( 'a.field' )
			->column( 'a.group' )
			->column( 'a.email' )
			->column( 'a.system' )
			->column( 'b.email', 'user_email' )
			->column( 'b.system', 'user_system' )
			->leftjoin( '#__social_alert_map', 'b' )
			->on( 'a.id', 'b.alert_id' )
			->on( 'b.user_id', $uid )
			->leftjoin( '#__social_apps', 'c' )
			->on( 'c.element', 'a.element' )
			->on( 'c.element', 'a.element' )
			->leftjoin( '#__social_apps_map', 'd' )
			->on( 'd.app_id', 'c.id' )
			->where( 'c.type', SOCIAL_APPS_TYPE_APPS )
			->where( 'c.group', SOCIAL_APPS_GROUP_USER )
			->where( 'd.uid', $uid )
			->where( 'a.app', SOCIAL_STATE_PUBLISHED );

		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		foreach( $result as &$row )
		{
			$this->mergeUserSettings( $row );
		}

		return $result;
	}

	public function getFieldUserSettings( $uid, $options = array() )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$sql->select( '#__social_alert', 'a' )
			->column( 'a.id' )
			->column( 'a.element' )
			->column( 'a.rule' )
			->column( 'a.core' )
			->column( 'a.extension' )
			->column( 'a.created' )
			->column( 'a.group' )
			->column( 'a.app' )
			->column( 'a.field' )
			->column( 'a.group' )
			->column( 'a.email' )
			->column( 'a.system' )
			->column( 'b.email', 'user_email' )
			->column( 'b.system', 'user_system' )
			->leftjoin( '#__social_alert_map', 'b' )
			->on( 'a.id', 'b.alert_id' )
			->on( 'b.user_id', $uid )
			->leftjoin( '#__social_apps', 'c' )
			->on( 'a.element', 'c.element' )
			->on( 'a.group', 'c.group' )
			->leftjoin( '#__social_profiles_maps', 'd' )
			->on( 'd.user_id', $uid )
			->leftjoin( '#__social_fields_steps', 'e' )
			->on( 'e.uid', 'd.profile_id' )
			->on( 'e.type', SOCIAL_TYPE_PROFILES )
			->where( 'c.type', SOCIAL_APPS_TYPE_FIELDS )
			->where( 'c.group', SOCIAL_APPS_GROUP_USER )
			->where( 'a.field', SOCIAL_STATE_PUBLISHED )
			->group( 'a.element' )
			->group( 'a.rule' );

		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		foreach( $result as &$row )
		{
			$this->mergeUserSettings( $row );
		}

		return $result;
	}

	private function mergeUserSettings( &$row )
	{
		if( !is_null( $row->user_email ) && $row->email >= 0 )
		{
			$row->email = $row->user_email;
		}

		unset( $row->user_email );

		if( !is_null( $row->user_system ) && $row->system >= 0 )
		{
			$row->system = $row->user_system;
		}

		unset( $row->user_system );

		return $row;
	}

	public function getNotificationSetting( $userId, $element = '' )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert', 'a' );
		$sql->column( 'a.id' );
		$sql->column( 'a.element' );
		$sql->column( 'a.rule' );
		$sql->column( 'a.core' );
		$sql->column( 'a.extension' );
		$sql->column( 'a.app' );
		$sql->column( 'b.email' );
		$sql->column( 'b.system' );
		$sql->column( 'b.user_id' );
		$sql->leftjoin( '#__social_alert_map', 'b' );
		$sql->on( 'a.id', 'b.alert_id' );
		$sql->where( 'b.user_id', $userId );

		if( !empty( $element ) )
		{
			$sql->where( 'a.element', $element );
		}

		$sql->order( 'a.core', 'desc' );
		$sql->order( 'a.element' );

		$db->setQuery( $sql );
		$result = $db->loadObjectList();

		$alerts = array();
		$groups = array();
		if( count( $result ) > 0 )
		{
			foreach( $result as $item )
			{
				$title = array();
				$title[] = $item->app > 0 ? 'APP_NOTIFICATION' : 'COM_EASYSOCIAL_PROFILE_NOTIFICATION';
				$title[] = 'SETTINGS';
				$title[] = $item->element;
				$title[] = str_replace( '.', '_', $item->rule );

				$item->title = JText::_( strtoupper( implode( '_', $title ) ) );

				if( empty( $alerts[$item->id] ) )
				{
					$alert = Foundry::table( 'alert' );
					$alert->load( $item->id );
					$alerts[$item->id] = $alert;
				}

				$app = $alerts[$item->id]->getApp();
				$title = $app ? $app->title : ucfirst( $item->element );

				$groups[$item->element]['alert'] = $alerts[$item->id];
				$groups[$item->element]['title'] = $title;
				$groups[$item->element]['rules'][] = $item;
			}
		}

		return $groups;
	}
}
