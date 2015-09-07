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

class EasySocialModelUsers extends EasySocialModel
{
	private $data			= null;
    static $_cache = null;
    static $_query_cache = null;
	public function __construct( $config = array() )
	{
		parent::__construct( 'users' , $config );
	}

	/**
	 * Populates the state
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function initStates()
	{
		$profile 	= $this->getUserStateFromRequest( 'profile' );
		$group 		= $this->getUserStateFromRequest( 'group' );
		$published	= $this->getUserStateFromRequest( 'published' , 'all' );

		$this->setState( 'published' , $published );
		$this->setState( 'group'	, $group );
		$this->setState( 'profile'	, $profile );

		parent::initStates();
	}

	/**
	 * Determines if the user exists in #__social_users
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function metaExists( $id )
	{
        $db 	= Foundry::db();
        $sql	= $db->sql();

        $sql->select( '#__social_users' );
        $sql->column( 'COUNT(1)' , 'count' );
        $sql->where( 'user_id' , $id );

        $db->setQuery( $sql );

        $exists	= $db->loadResult() > 0 ? true : false;

        return $exists;
	}

	/**
	 * Creates a new user meta
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createMeta( $id )
	{
		$db 			= Foundry::db();
		$obj 			= new stdClass();
		$obj->user_id 	= $id;

		// If user is created on the site but doesn't have a record, we should treat it as published.
		$obj->state  	= SOCIAL_STATE_PUBLISHED;

		return $db->insertObject( '#__social_users' , $obj );
	}

	/**
	 * Search a username given the email
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The email address
	 * @return
	 */
	public function getUsernameByEmail( $email )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__users' , 'username' );
		$sql->column( 'username' );
		$sql->where( 'email' , $email );

		$db->setQuery( $sql );

		$username 	= $db->loadResult();

		return $username;
	}

	/**
	 * Assigns user to a particular user group
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id
	 * @param	int		The group's id
	 * @return
	 */
	public function assignToGroup( $id , $gid )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		// Check if the user is already assigned to this group
		$sql->select( '#__user_usergroup_map' );
		$sql->column( 'COUNT(1)' );
		$sql->where( 'group_id' , $gid );
		$sql->where( 'user_id'	, $id );

		$db->setQuery( $sql );

		$exists 	= $db->loadResult();

		if( !$exists )
		{
			$sql->clear();
			$sql->insert( '#__user_usergroup_map' );
			$sql->values( 'user_id' , $id );
			$sql->values( 'group_id' , $gid );

			$db->setQuery( $sql );
			$db->Query();
		}

	}

	/**
	 * Retrieve a user group from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getUserGroup( $id )
	{
		$db 		= Foundry::db();

		$sql 		= $db->sql();

		$sql->select( '#__usergroups' );
		$sql->where( 'id' , $id );

		$db->setQuery( $sql );

		$result 	= $db->loadObject();

		if( !$result )
		{
			return $result;
		}

		$sql->clear();

		$sql->select( '#__user_usergroup_map' );
		$sql->where( 'group_id' , $id );

		$db->setQuery( $sql->getTotalSql() );

		$result->total 	= $db->loadResult();

		return $result;
	}

	/**
	 * Retrieve a list of user groups from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getUserGroups()
	{
		$db		= Foundry::db();

		$sql	= $db->sql();

		$sql->select( '#__usergroups', 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.id', 'level', 'count distinct' );
		$sql->join( '#__usergroups' , 'b' );
		$sql->on( 'a.lft', 'b.lft', '>' );
		$sql->on( 'a.rgt', 'b.rgt', '<' );
		$sql->group( 'a.id' , 'a.title' , 'a.lft' , 'a.rgt' , 'a.parent_id' );
		$sql->order( 'a.lft' , 'ASC' );

		$db->setQuery( $sql );

		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		foreach( $result as &$row )
		{
			$sql->clear();

			$sql->select( '#__user_usergroup_map' );
			$sql->where( 'group_id' , $row->id );

			$db->setQuery( $sql->getTotalSql() );

			$row->total 	= $db->loadResult();
		}

		return $result;
	}


	/**
	 * Retrieves a list of apps for the user's dashboard.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique user id.
	 * @return
	 */
	public function getDashboardApps( $userId )
	{
		$model 		= Foundry::model( 'Apps' );
		$options	= array( 'uid' => $userId , 'key' => SOCIAL_TYPE_USER );
		$apps 		= $model->getApps( $options );

		// If there's nothing to process, just exit block.
		if( !$apps )
		{
			return $apps;
		}

		// Format the result as we only want to
		// return the caller apps that should appear on dashboard.
		$result 	= array();

		foreach( $apps as $app )
		{
			if( $app->hasDashboard() )
			{
				$result[]	= $app;
			}
		}

		return $result;
	}

	/**
	 * Retrieves a list of data for a type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique item id.
	 * @param	string	The unique item type.
	 */
	public function initUserData( $id )
	{
		$fieldsModel 	= Foundry::model( 'Fields' );
		$data 			= $fieldsModel->getFieldsData( $id , SOCIAL_TYPE_USER );

		// We need to attach all positions for this field
		$fields	= array();

		if( !$data )
		{
			return false;
		}

		foreach( $data as &$row )
		{
			$fields[ $row->unique_key ]	= $row;
		}

		return $fields;
	}

	/**
	 * Retrieves a list of user data based on the given ids.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array 	$ids	An array of ids.
	 * @return
	 */
	public function getUsersMeta( $ids = array() )
	{
		static $const = array();

		$loaded = array();
		$new    = array();

		if( !empty( $ids ) )
		{
			foreach( $ids as $id )
			{
				if( is_numeric( $id ) )
				{
					if( isset( $const[ $id ] ) )
					{
						$loaded[] = $const[ $id ];
					}
					else
					{
						$new[] = $id;
					}
				}
			}
		}

		if( $new )
		{
			foreach( $new as $id )
			{
				$const[ $id ] = false;
			}

			$db		= Foundry::db();
			$sql 	= $db->sql();

			$sql->select( '#__users' , 'a' );
			$sql->column( 'a.*' );
			$sql->column( 'b.small' );
			$sql->column( 'b.medium' );
			$sql->column( 'b.large' );
			$sql->column( 'b.square' );
			$sql->column( 'b.avatar_id' );
			$sql->column( 'b.photo_id' );
			$sql->column( 'b.storage' , 'avatarStorage' );
			$sql->column( 'd.profile_id' );
			$sql->column( 'e.state' );
			$sql->column( 'e.type' );
			$sql->column( 'e.alias' );
			$sql->column( 'e.permalink' );
			$sql->column( 'f.id' , 'cover_id' );
			$sql->column( 'f.uid' , 'cover_uid' );
			$sql->column( 'f.type' , 'cover_type' );
			$sql->column( 'f.photo_id' , 'cover_photo_id' );
			$sql->column( 'f.cover_id'	, 'cover_cover_id' );
			$sql->column( 'f.x' , 'cover_x' );
			$sql->column( 'f.y' , 'cover_y' );
			$sql->column( 'f.modified' , 'cover_modified' );
			$sql->column( 'g.points' , 'points' , 'sum' );
			$sql->join( '#__social_avatars' , 'b' );
			$sql->on( 'b.uid' , 'a.id' );
			$sql->on( 'b.type' , SOCIAL_TYPE_USER );
			$sql->join( '#__social_profiles_maps' , 'd' );
			$sql->on( 'd.user_id' , 'a.id' );
			$sql->join( '#__social_users' , 'e' );
			$sql->on( 'e.user_id' , 'a.id' );
			$sql->join( '#__social_covers' , 'f' );
			$sql->on( 'f.uid' , 'a.id' );
			$sql->on( 'f.type' , SOCIAL_TYPE_USER );

			$sql->join( '#__social_points_history' , 'g' );
			$sql->on( 'g.user_id' , 'a.id' );

			if( count( $new ) > 1 )
			{
				$sql->where( 'a.id' , $new , 'IN' );
				$sql->group( 'a.id' );
			}
			else
			{
				$sql->where( 'a.id' , $new[0]);
			}



			// Debugging mode
			//echo $sql->debug();

			$db->setQuery( $sql );
            $cache = JFactory::getCache('_system', 'callback');
            $users=$cache->get(md5($sql));

            if(!$users)
            {
                $users	= $db->loadObjectList();
                $cache->store($users,md5($sql));
            }
			if( $users )
			{
				foreach( $users as $user )
				{
					$loaded[] 			= $user;
					$const[ $user->id ] = $user;
				}
			}
		}

		$return = array();
		if( $loaded )
		{
			foreach( $loaded as $user )
			{
				if( isset( $user->id ) )
				{
					$return[] = $user;
				}
			}
		}

		return $return;
	}

	/**
	 * Retrieves a list of super administrator's on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	Array
	 */
	public function getSiteAdmins()
	{
		$db 		= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT a.' . $db->nameQuote( 'id' ) . ', a.' . $db->nameQuote( 'title' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__usergroups' ) . ' AS a';
		$query[]	= 'LEFT JOIN ' . $db->nameQuote( '#__usergroups' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'lft' ) . ' > b.' . $db->nameQuote( 'lft' );
		$query[]	= 'AND a.' . $db->nameQuote( 'rgt' ) . ' < b.' . $db->nameQuote( 'rgt' );
		$query[]	= 'GROUP BY a.' . $db->nameQuote( 'id' );
		$query[]	= 'ORDER BY a.' . $db->nameQuote( 'lft' ) . ' ASC';

		$db->setQuery( $query );
		$result 	= $db->loadObjectList();

		// Get list of super admin groups.
		$superAdminGroups 	= array();

		foreach( $result as $group )
		{
			if( JAccess::checkGroup( $group->id , 'core.admin' ) )
			{
				$superAdminGroups[]	= $group;
			}
		}

		$superAdmins 	= array();

		foreach( $superAdminGroups as $superAdminGroup )
		{
			$users	= JAccess::getUsersByGroup( $superAdminGroup->id );

			foreach( $users as $id )
			{
				$user 	 =	Foundry::user( $id );

				if( $user->id )
				{
					$superAdmins[]	= $user;
				}
			}
		}

		return $superAdmins;
	}

	/**
	 * Approves a user's registration application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function approve( $id )
	{
		$user 	= Foundry::user( $id );

		return $user->approve();
	}

	/**
	 * Retrieves a list of online users from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getOnlineUsers()
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		// Get the session life time so we can know who is really online.
		$jConfig 	= Foundry::jConfig();
		$lifespan 	= $jConfig->getValue( 'lifetime' );
		$online 	= time() - ( $lifespan * 60 );

		$sql->select( '#__session' , 'a' );
		$sql->column( 'b.id' );
		$sql->join( '#__users' , 'b' , 'INNER' );
		$sql->on( 'a.userid' , 'b.id' );
		$sql->where( 'a.time' , $online , '>=' );
		$sql->where( 'b.block' , 0 );
		$sql->group( 'a.userid' );

		$db->setQuery( $sql );

		$result 	= $db->loadColumn();

		if( !$result )
		{
			return array();
		}

		$users	= Foundry::user( $result );

		return $users;
	}

	/**
	 * Retrieves the total number of users on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalUsers()
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__users' );

		$db->setQuery( $sql->getTotalSql() );

		$total 		= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves the total number of pending users on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	int		Total number of users
	 */
	public function getTotalPending()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_users' , 'a' );
		$sql->column( 'COUNT(1)' , 'count' );
		$sql->join( '#__users' , 'b' );
		$sql->on( 'b.id' , 'a.user_id' );
		$sql->where( 'a.state' , SOCIAL_REGISTER_APPROVALS );

		$db->setQuery( $sql );

		$total 	= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves the total number of pending users form the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPendingUsersCount()
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__users' , 'a' );
		$sql->column( 'COUNT(1)' , 'count' );
		$sql->join( '#__social_users' , 'b' , 'INNER' );
		$sql->on( 'a.id' , 'b.user_id' );
		$sql->where( 'b.state' , SOCIAL_REGISTER_APPROVALS );
		$db->setQuery( $sql );

		$total 		= (int) $db->loadResult();

		return $total;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPendingUsers()
	{
		$db 		= Foundry::db();
		$query 		= array();
		$query[]	= 'SELECT a.* FROM ' . $db->nameQuote( '#__users' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_users' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'user_id' );
		$query[]	= 'WHERE b.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_REGISTER_APPROVALS );
		$query[]	= 'ORDER BY a.' . $db->nameQuote( 'registerDate' );

		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$result 		= $db->loadObjectList();

		if( !$result )
		{
			return false;
		}

		// Prepare the user object.
		$users 	= array();

		foreach( $result as $row )
		{
			$user 	= Foundry::user( $row->id );

			$users[]	= $user;
		}

		return $users;
	}


	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalOnlineUsers()
	{
		$db 		= Foundry::db();
		$query 		= $db->sql();

		// Get the session life time so we can know who is really online.
		$jConfig 	= Foundry::jConfig();
		$lifespan 	= $jConfig->getValue( 'lifetime' );
		$online 	= time() - ( $lifespan * 60 );

		$query 		= array();
		$query[]	= 'SELECT COUNT( DISTINCT( a.' . $db->nameQuote( 'userid' ) . ' ) ) FROM ' . $db->nameQuote( '#__session' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'userid' ) . ' = b.' . $db->nameQuote( 'id' );
		$query[]	= 'WHERE a.' . $db->nameQuote( 'time' ) . '>=' . $db->Quote( $online );
		$query[]	= 'AND b.' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );
		$query[]	= 'GROUP BY a.' . $db->nameQuote( 'userid' );

		$db->setQuery( $query );

		$total 		= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves a list of user data based on the given ids.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array 	$ids	An array of ids.
	 * @return
	 */
	public function getUsersWithState( $options = array() )
	{
		$db		= Foundry::db();

		$sql 	= $db->sql();

		$sql->select( '#__users' , 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.type' );
		$sql->column( 'p.points' , 'points' , 'sum' );

		// Join with points table.
		$sql->join('#__social_points_history', 'p');
		$sql->on('p.user_id', 'a.id');
		$sql->group('a.id');

		// $sql->join( '#__social_users' , 'b' , 'INNER' );
		$sql->join( '#__social_users' , 'b' );
		$sql->on( 'a.id' , 'b.user_id' );

		// Determines if there's a group filter.
		$group	= $this->getState( 'group' );

		if( $group && $group != -1 )
		{
			$sql->join( '#__user_usergroup_map' , 'c' );
			$sql->on( 'a.id' , 'c.user_id' );

			$sql->where( 'c.group_id' , $group );
		}

		// Join with the social profiles table
		$sql->join( '#__social_profiles_maps' , 'e' );
		$sql->on( 'e.user_id' , 'a.id' );

		// Determines if there's a search filter.
		$search = $this->getState( 'search' );

		if( $search )
		{
			$sql->where( '(' );
			$sql->where( 'name' , '%' . $search . '%' , 'LIKE' , 'OR' );
			$sql->where( 'username' , '%' . $search . '%' , 'LIKE' , 'OR' );
			$sql->where( 'email' , '%' . $search . '%' , 'LIKE' , 'OR');
			$sql->where( ')' );
		}

		// Determines if registration state
		$registrationState 	= isset($options[ 'state' ] ) ? $options[ 'state' ] : '';

		if( $registrationState )
		{
			$sql->where( 'b.state' , $registrationState );
		}

		// Determines if state filter is provided
		$state	= $this->getState( 'published' );

		if( $state != 'all' && !is_null( $state ) )
		{
			$state	= $state == 1 ? SOCIAL_JOOMLA_USER_UNBLOCKED : SOCIAL_JOOMLA_USER_BLOCKED;

			$sql->where( 'a.block' , $state );
		}

		// Determines if we want to filter by logged in users.
		$login 	= isset( $options[ 'login' ] ) ? $options[ 'login' ] : '';

		if( $login )
		{
			$tmp	 = 'EXISTS( SELECT ' . $db->nameQuote( 'userid' ) . ' FROM ' . $db->nameQuote( '#__session' ) . ' AS f WHERE ' . $db->nameQuote( 'userid' ) . ' = a.' . $db->nameQuote( 'id' ) . ')';

			$sql->exists( $tmp );
		}

		$picture 	= isset( $options[ 'picture' ] ) ? $options[ 'picture' ] : '';

		// Determines if we should only pick users with picture
		if( $picture )
		{
			$sql->join( '#__social_avatars' , 'g' );
			$sql->on( 'a.id' , 'g.uid' );

			$sql->where( 'g.small' , '' , '!=' );
		}


		// Determines if there's filter by profile id.
		$profile 		= $this->getState( 'profile' );

		if( $profile && $profile != -1 && $profile != -2 )
		{
			$sql->where( 'e.profile_id' , $profile );
		}
		else if( $profile == -2 )
		{
			$sql->isnull( 'e.profile_id');
		}

		// Determines if we have an exclusion list.
		$exclusions 	= isset( $options[ 'exclusion' ] ) ? $options[ 'exclusion' ] : '';

		if( $exclusions )
		{
			// Ensure that it's in an array
			$exclusions 	= Foundry::makeArray( $exclusions );
			$sql->where( 'a.id' , implode( ',' , $exclusions ) , 'NOT IN' );
		}

		// Determines if we need to order the items by column.
		$ordering 	= isset($options[ 'ordering' ] ) ? $options[ 'ordering' ] : '';

		// Ordering based on caller
		if( $ordering )
		{
			$direction 	= isset( $options[ 'direction' ] ) ? $options[ 'direction' ] : '';

			$sql->order( $ordering , $direction );
		}

		// Column ordering
		$ordering 	= $this->getState( 'ordering' , $ordering );

		if( $ordering )
		{
			$direction 	= $this->getState( 'direction' );

			$sql->order( $ordering , $direction );
		}

		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : '';

		$limitState  = $this->getState( 'limit' );


		if( $limit != 0 || $limitState )
		{
			if( $limit )
			{
				$sql->limit( 0 , $limit );
			}

			// Set the total number of items.
			$this->setTotal( $sql->getSql() , true );

			// Get the list of users
			$users 	= $this->getData( $sql->getSql() );
		}
		else
		{
			$db->setQuery( $sql );
			$users 	= $db->loadObjectList();
		}


		return $users;
	}

	/**
	 * Determines if the alias exists
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function aliasExists( $alias , $exceptUserId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_users' );
		$sql->column( 'COUNT(1)' , 'total' );
		$sql->where( 'alias' , $alias );
		$sql->where( 'user_id' , $exceptUserId , '!=' );

		$db->setQuery( $sql );
		$exists	= $db->loadResult() >= 1 ? true : false;

		return $exists;
	}

	/**
	 * Retrieve's user id based on the alias
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserIdFromAlias( $permalink )
	{
		static $loaded 	= array();

		if( !isset( $loaded[ $permalink ] ) )
		{
			$config 	= Foundry::config();

			// We need to know which column should we be checking against.
			if( $config->get( 'users.aliasName' ) == 'realname' )
			{
				$id 	= $permalink;

				if( strpos( $permalink , ':' ) !== false )
				{
					$parts 	= explode( ':' , $permalink , 2 );

					$id 	= $parts[ 0 ];
				}

				$loaded[ $permalink ]	= $id;

				return $loaded[ $permalink ];
			}

			// Get the user form permalink field
			$id 		= $this->getUserFromPermalink( $permalink );

			if( $id )
			{
				$loaded[ $permalink ]	= $id;

				return $loaded[ $permalink ];
			}

			// If it reaches here, we know then that the alias is using username
			// First we need to replace : with -
			$tmp	= str_replace( ':', '-', $permalink );
			$id 	= $this->getUserIdWithUsernamePermalink( $tmp );

			// If we still can't find '-' try '_' now.
			if( !$id )
			{
				$tmp 	= str_replace( ':' , '_' , $permalink );
				$id 	= $this->getUserIdWithUsernamePermalink( $tmp );
			}

			// If we still can't find '_' , we replace it with spaces
			if( !$id )
			{
				$tmp 	= str_replace( ':' , ' ' , $permalink );
				$id 	= $this->getUserIdWithUsernamePermalink( $tmp );
			}

			$loaded[ $permalink ] 	= $id;
		}

		return $loaded[ $permalink ];
	}

	/**
	 * Determines if the permalink is a valid permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isValidUserPermalink( $permalink )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_users' );
		$sql->column( 'COUNT(1)' );
		$sql->where( 'permalink' , $permalink );

		$db->setQuery( $sql );

		$exists	= $db->loadResult() > 0 ? true : false;

		return $exists;
	}

	/**
	 * Retrieve user's id given the username permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The username permalink
	 * @return
	 */
	public function getUserIdWithUsernamePermalink( $permalink )
	{
		$db 	= Foundry::db();

		$sql 	= $db->sql();

		$sql->select( '#__users' );
		$sql->column( 'id' );
		$sql->where( 'LOWER( `username` )' , $permalink );

		$db->setQuery( $sql );

		$id 	= $db->loadResult();

		return $id;
	}

	/**
	 * Retrieve a user with the given permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserFromAlias( $alias )
	{
		$db 	= Foundry::db();

		$sql 	= $db->sql();

		$sql->select( '#__social_users' );
		$sql->column( 'user_id' );
		$sql->where( 'alias' , $alias , '=' );

		$db->setQuery( $sql );

		$id 	= (int) $db->loadResult();

		return $id;
	}

	/**
	 * Retrieve a user with the given permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserFromPermalink( $permalink )
	{
		$db 	= Foundry::db();

		$sql 	= $db->sql();

		$variant 	= str_ireplace( ':', '-' , $permalink );
		$underscore = str_ireplace( ':' , '_' , $permalink );

		$sql->select( '#__social_users' );
		$sql->column( 'user_id' );
		$sql->where( 'permalink' , $permalink , '=' , 'OR' );
		$sql->where( 'permalink' , $variant , '=' , 'OR' );
		$sql->where( 'permalink' , $underscore , '=' , 'OR' );
		$sql->where( 'LOWER(`permalink`)' , $permalink , '=' , 'OR' );
		$sql->where( 'LOWER(`permalink`)' , $variant , '=' , 'OR' );
		$sql->where( 'LOWER(`permalink`)' , $underscore , '=' , 'OR' );


		$db->setQuery( $sql );

		$id 	= (int) $db->loadResult();

		return $id;
	}

	/**
	 * Retrieves a list of user data based on the given ids.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array 	$ids	An array of ids.
	 * @return
	 */
	public function getUsers( $options = array() )
	{
		$db		= Foundry::db();

		$sql 	= $db->sql();

		$sql->select( '#__users' , 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.type' );
		$sql->column( 'd.points' , 'points' , 'sum' );
		$sql->join( '#__social_users' , 'b' , 'INNER' );
		$sql->on( 'a.id' , 'b.user_id' );

		// Join with the points table to retrieve user's points
		$sql->join( '#__social_points_history' , 'd' );
		$sql->on( 'd.user_id' , 'a.id' );

		// Join with the social profiles table
		$sql->join( '#__social_profiles_maps' , 'e' );
		$sql->on( 'e.user_id' , 'a.id' );

		// Determines if registration state
		$registrationState 	= isset($options[ 'state' ] ) ? $options[ 'state' ] : '';

		if( $registrationState )
		{
			$sql->where( 'b.state' , $registrationState );
		}

		// Determines if we should display admin's on this list.
		$includeAdmin 	= isset( $options[ 'includeAdmin' ] ) ? $options[ 'includeAdmin' ] : null;

		// If caller doesn't want to include admin, we need to set the ignore list.
		if( $includeAdmin === false )
		{
			// Get a list of site administrators from the site.
			$admins 	= $this->getSiteAdmins();

			if( $admins )
			{
				$ids	= array();

				foreach( $admins as $admin )
				{
					$ids[] 	= $admin->id;
				}

				$sql->where( 'a.id' , $ids , 'NOT IN' );
			}
		}

		// Determines if state filter is provided
		$state 	= isset( $options[ 'published' ] ) ? $options[ 'published' ] : '';

		if( $state !== '' )
		{
			$state	= $state == 1 ? SOCIAL_JOOMLA_USER_UNBLOCKED : SOCIAL_JOOMLA_USER_BLOCKED;

			$sql->where( 'a.block' , $state );
		}

		// Determines if we want to filter by logged in users.
		$login 	= isset( $options[ 'login' ] ) ? $options[ 'login' ] : '';

		if( $login )
		{
			// Determine if only to fetch front end
			$frontend	= isset( $options[ 'frontend' ] ) ? $options[ 'frontend' ] : '';

			$tmp	 	= 'EXISTS( ';
			$tmp	 	.= 'SELECT ' . $db->nameQuote( 'userid' ) . ' FROM ' . $db->nameQuote( '#__session' ) . ' AS f WHERE ' . $db->nameQuote( 'userid' ) . ' = a.' . $db->nameQuote( 'id' );

			if( $frontend )
			{
				$tmp 	.= ' AND `client_id` = ' . $db->Quote( 0 );
			}


			$tmp 		.= ')';

			$sql->exists( $tmp );
		}

		$picture 	= isset( $options[ 'picture' ] ) ? $options[ 'picture' ] : '';

		// Determines if we should only pick users with picture
		if( $picture )
		{
			$sql->innerjoin( '#__social_avatars' , 'g' );
			$sql->on( 'a.id' , 'g.uid' );
			$sql->on( 'g.small' , '' , '!=' );

			$sql->innerjoin( '#__social_photos_meta' , 'pm' );
			$sql->on( 'g.photo_id' , 'pm.photo_id' );

			$sql->on( 'pm.group' , 'path');
			$sql->on( 'pm.property' , 'stock');
		}

		// Determines if there's filter by profile id.
		$profile 	= isset( $options[ 'profile' ] ) ? $options[ 'profile' ] : '';

		if( $profile && $profile != -1 )
		{
			$sql->where( 'e.profile_id' , $profile );
		}

		// Determines if we have an exclusion list.
		$exclusions 	= isset( $options[ 'exclusion' ] ) ? $options[ 'exclusion' ] : '';

		if( $exclusions )
		{
			// Ensure that it's in an array
			$exclusions 	= Foundry::makeArray( $exclusions );
			$sql->where( 'a.id' , implode( ',' , $exclusions ) , 'NOT IN' );
		}

		// Determines if we need to order the items by column.
		$ordering 	= isset($options[ 'ordering' ] ) ? $options[ 'ordering' ] : '';

		// Ordering based on caller
		if( $ordering )
		{
			$direction 	= isset( $options[ 'direction' ] ) ? $options[ 'direction' ] : '';

			$sql->order( $ordering , $direction );
		}

		// Group items by id since the points history may generate duplicate records.
		$sql->group( 'a.id' );

		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : '';


		if( $limit != 0 )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $sql->getSql() , true );

			// Get the list of users
			$users 	= $this->getData( $sql->getSql() );

		}
		else
		{

			$db->setQuery( $sql );
			$users 	= $db->loadObjectList();
		}


		return $users;
	}

	/**
	 * Determines whether the current user is active or not.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	boolean		True if online, false otherwise.
	 */
	public function isOnline( $id )
	{
		$db		= Foundry::db();

		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__session' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $id );

		$db->setQuery( $query );

		$online	= $db->loadResult() > 0;

		return $online;
	}

	/**
	 * Perform necessary logics when a user is deleted
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete( $id )
	{
		// Delete profile mapping
		$this->deleteProfile( $id );

		// Delete form #__social_oauth
		$this->deleteOAuth( $id );

		// Delete user stream item
		$this->deleteStream( $id );

		// Delete user photos
		$this->deletePhotos( $id );

		return true;
	}

	/**
	 * Deletes the user profile data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteProfile( $userId )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		// Delete profile mapping of the user
		$sql->delete( '#__social_profiles_maps' );
		$sql->where( 'user_id' , $userId );
		$db->setQuery( $sql );
		$db->Query();

		// Delete user custom fields.
		$sql->clear();
		$sql->delete( '#__social_fields_data' );
		$sql->where( 'uid' , $userId );
		$sql->where( 'type' , SOCIAL_TYPE_USER );
		$db->setQuery( $sql );
		$db->Query();

		// Delete #__social_users
		$sql->clear();
		$sql->delete( '#__social_users' );
		$sql->where( 'user_id' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Delete user photos
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deletePhotos( $userId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		// Delete user albums
		$sql->clear();
		$sql->select( '#__social_albums' );
		$sql->where( 'uid' , $userId );
		$sql->where( 'type' , SOCIAL_TYPE_USER );
		$db->setQuery( $sql );

		$albums	= $db->loadObjectList();

		if( $albums )
		{
			foreach( $albums as $row )
			{
				$album	= Foundry::table( 'Album' );
				$album->load( $row->id );

				$album->delete();
			}
		}

		return true;
	}

	/**
	 * Delete user's cover
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteCover( $userId )
	{
		$cover 	= Foundry::table( 'Cover' );
		$cover->load( $userId , SOCIAL_TYPE_USER );

		return $cover->delete();
	}

	/**
	 * Delete user's avatar
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteAvatar( $userId )
	{
		$avatar 	= Foundry::table( 'Avatar' );
		$avatar->load( $userId , SOCIAL_TYPE_USER );

		return $avatar->delete();
	}

	/**
	 * Deletes the conversations
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteConversations( $userId )
	{
		// Delete conversations

		// Delete conversation participants
	}

	/**
	 * Deletes user likes
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteLikes( $userId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_likes' );
		$sql->where( 'created_by' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Deletes user comments
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteComments( $userId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_comments' );
		$sql->where( 'created_by' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Deletes the user point relations
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deletePoints( $userId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_points_history' );
		$sql->where( 'user_id' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Deletes the user friend relations
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteFriends( $userId )
	{
		// Delete friend list
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_lists' );
		$sql->where( 'user_id' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		$sql->clear();

		// Delete friends
		$sql->delete( '#__social_friends' );
		$sql->where( 'actor_id' , $userId );
		$sql->where( 'target_id' , $userId, '=', 'or' );

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Deletes the user point relations
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteLabels( $userId )
	{
		// Delete labels
		$db		= Foundry::db();
		$sql	= $db->sql();

		$query->delete( '#__social_labels' );
		$sql->where( 'created_by' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		return true;
	}


	/**
	 * Deletes stream of a user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteStream( $userId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_stream' );
		$sql->where( 'actor_id' , $userId );
		$sql->where( 'actor_type' , SOCIAL_TYPE_USER );

		$db->setQuery( $sql );

		$streams 	= $db->loadObjectList();

		// Delete all stream relations
		foreach( $streams as $stream )
		{
			$sql->clear();

			$sql->delete( '#__social_stream_item' );
			$sql->where( 'uid' , $stream->id );

			$db->setQuery( $sql );
			$db->Query();

			// Delete the stream item.
			$sql->clear();
			$sql->delete( '#__social_stream' );
			$sql->where( 'id' , $stream->id );

			$db->setQuery( $sql );
			$db->query();
		}

		// Delete any hidden stream by the user.
		$sql->clear();

		$sql->delete( '#__social_stream_hide' );
		$sql->where( 'user_id' , $userId );
		$db->setQuery( $sql );

		$db->Query();

		return true;
	}

	/**
	 * Retrieves the user's id
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The key to lookup for
	 * @param	string	The value for the key
	 * @return
	 */
	public function getUserId( $key , $value )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__users' );
		$sql->column( 'id' );
		$sql->where( $key , $value );

		$db->setQuery( $sql );

		$id 	= $db->loadResult();
		return $id;
	}

	/**
	 * Method to check if user reset limit has been exceeded within the allowed time period.
	 *
	 * @param   JUser  the user doing the password reset
	 *
	 * @return  boolean true if user can do the reset, false if limit exceeded
	 *
	 * @since    2.5
	 */
	public function checkResetLimit($user)
	{
		$params = JFactory::getApplication()->getParams();
		$maxCount = (int) $params->get('reset_count');
		$resetHours = (int) $params->get('reset_time');
		$result = true;

		$lastResetTime = strtotime($user->lastResetTime) ? strtotime($user->lastResetTime) : 0;
		$hoursSinceLastReset = (strtotime(JFactory::getDate()->toSql()) - $lastResetTime) / 3600;

		// If it's been long enough, start a new reset count
		if ($hoursSinceLastReset > $resetHours)
		{
			$user->lastResetTime = JFactory::getDate()->toSql();
			$user->resetCount = 1;
		}

		// If we are under the max count, just increment the counter
		elseif ($user->resetCount < $maxCount)
		{
			$user->resetCount;
		}

		// At this point, we know we have exceeded the maximum resets for the time period
		else
		{
			$result = false;
		}
		return $result;
	}

	/**
	 * Reset password confirmation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The user's username
	 * @param	string	The verification code
	 * @return
	 */
	public function verifyResetPassword( $username , $code )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__users' );
		$sql->column( 'activation' );
		$sql->column( 'id' );
		$sql->column( 'block' );
		$sql->where( 'username' , $username );

		$db->setQuery( $sql );

		$obj 	= $db->loadObject();

		if( !$obj )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_NO_SUCH_USER_WITH_EMAIL' ) );
			return false;
		}

		// Split the crypt and salt
		$parts 	= explode( ':' , $obj->activation );
		$crypt	= $parts[ 0 ];

		if( !isset( $parts[ 1 ] ) )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_NO_SUCH_USER_WITH_EMAIL' ) );
			return false;
		}

		$salt 	= $parts[ 1 ];
		// Manually pass in crypt type as md5-hex because when we generate the activation token, it is crypted with crypt-md5, and due to Joomla 3.2 using bcrypt by default, this part fails. We revert back to Joomla 3.0's default crypt format, which is md5-hex.
		$test	= JUserHelper::getCryptedPassword( $code , $salt, 'md5-hex' );

		if( $crypt != $test )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_INVALID_CODE' ) );
			return false;
		}

		// Ensure that the user account is not blocked
		if( $obj->block )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_USER_BLOCKED' ) );
			return false;
		}

		// Push the user data into the session.
		$app = JFactory::getApplication();
		$app->setUserState( 'com_users.reset.token'	, $crypt . ':' . $salt);
		$app->setUserState( 'com_users.reset.user'	, $obj->id );

		return true;
	}

	/**
	 * Resets the user's password
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The password
	 * @param	string	The reconfirm password
	 * @return
	 */
	public function resetPassword( $password , $password2 )
	{
		// Get the token and user id from the confirmation process.
		$app		= JFactory::getApplication();
		$token		= $app->getUserState( 'com_users.reset.token' , null );
		$userId		= $app->getUserState( 'com_users.reset.user' , null );

		// Check for the token and the user's id.
		if( !$token || !$userId )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_TOKENS_MISSING' ) );
			return false;
		}

		// Retrieve the user object
		$user = JUser::getInstance( $userId );

		// Check for a user and that the tokens match.
		if( empty($user) || $user->activation !== $token )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_NO_SUCH_USER' ) );
			return false;
		}

		// Ensure that the user account is not blocked
		if( $obj->block )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_USER_BLOCKED' ) );
			return false;
		}

		// Generates the new password hash
		$salt 		= JUserHelper::genRandomPassword( 32 );
		$crypted	= JUserHelper::getCryptedPassword( $password , $salt );
		$password	= $crypted . ':' . $salt;

		// Update user's object
		$user->password 	= $password;

		// Reset the activation
		$user->activation	= '';

		// Set the clear password
		$user->password_clear	= $password2;

		// Save the user to the database.
		if( !$user->save( true ) )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_SAVE_ERROR' ) );
			return false;
		}

		// Flush the user data from the session.
		$app->setUserState('com_users.reset.token', null);
		$app->setUserState('com_users.reset.user', null);

		return true;
	}

	/**
	 * Remind password
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The email address of the user.
	 * @return
	 */
	public function remindPassword( $email )
	{
		$id 	= $this->getUserId( 'email' , $email );

		if( !$id )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_NO_SUCH_USER_WITH_EMAIL' ) );
			return false;
		}

		$user	= Foundry::user( $id );

		// Ensure that the user is not blocked
		if( $user->block )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_USER_BLOCKED' ) );
			return false;
		}

		// Super administrator is not allowed to reset passwords.
		if( $user->authorise( 'core.admin' ) )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_SUPER_ADMIN' ) );
			return false;
		}

		// Make sure the user has not exceeded the reset limit
		if (!$this->checkResetLimit($user))
		{
			$resetLimit 	= (int) JFactory::getApplication()->getParams()->get( 'reset_time' );
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_EXCEEDED' , $resetLimit ) );
			return false;
		}

		// Set the confirmation token.
		$token			= JApplication::getHash(JUserHelper::genRandomPassword());
		$salt			= JUserHelper::getSalt('crypt-md5');
		$hashedToken	= md5($token . $salt) . ':' . $salt;

		// Set the new activation
		$user->activation	= $hashedToken;

		// Save the user to the database.
		if( !$user->save(true) )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_SAVE_ERROR' ) );
			return false;
		}

		// Get the application data.
		$jConfig 	= Foundry::jConfig();

		// Push arguments to template variables so users can use these arguments
		$params 	= array(
								'site'			=> $jConfig->getValue( 'sitename' ),
								'username'		=> $user->username,
								'name'			=> $user->getName(),
								'id'			=> $user->id,
								'avatar'		=> $user->getAvatar( SOCIAL_AVATAR_LARGE ),
								'profileLink'	=> $user->getPermalink(),
								'email'			=> $email,
								'token'			=> $token
						);

		// Get the email title.
		$title 			= JText::_( 'COM_EASYSOCIAL_EMAILS_REMIND_PASSWORD_TITLE' );

		// Immediately send out emails
		$mailer 		= Foundry::mailer();

		// Get the email template.
		$mailTemplate	= $mailer->getTemplate();

		// Set recipient
		$mailTemplate->setRecipient( $user->name , $user->email );

		// Set title
		$mailTemplate->setTitle( $title );

		// Set the contents
		$mailTemplate->setTemplate( 'site/user/remind.password' , $params );

		// Set the priority. We need it to be sent out immediately since this is user registrations.
		$mailTemplate->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

		// Try to send out email now.
		$state 		= $mailer->create( $mailTemplate );

		return $state;
	}

	/**
	 * Remind username
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function remindUsername( $email )
	{
		// Load backend language file.
		Foundry::language()->load( 'com_easysocial' , JPATH_ADMINISTRATOR );

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		// Check if such email exists
		$sql->select( '#__users' );
		$sql->where( 'email' , $email );

		$db->setQuery( $sql );

		$row	= $db->loadObject();

		if( !$row )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_NO_SUCH_USER_WITH_EMAIL' ) );
			return false;
		}

		// Ensure that the user is not blocked
		if( $row->block )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_USERS_USER_BLOCKED' ) );
			return false;
		}

		$user 		= Foundry::user( $row->id );

		// Get the application data.
		$jConfig 	= Foundry::jConfig();

		// Push arguments to template variables so users can use these arguments
		$params 	= array(
								'site'			=> $jConfig->getValue( 'sitename' ),
								'username'		=> $row->username,
								'name'			=> $user->getName(),
								'id'			=> $user->id,
								'avatar'		=> $user->getAvatar( SOCIAL_AVATAR_LARGE ),
								'profileLink'	=> $user->getPermalink(),
								'email'			=> $email
						);

		// Get the email title.
		$title 		= JText::sprintf( 'COM_EASYSOCIAL_EMAILS_REMIND_USERNAME_TITLE' , $jConfig->getValue( 'sitename' ) );

		// Immediately send out emails
		$mailer 	= Foundry::mailer();

		// Get the email template.
		$mailTemplate	= $mailer->getTemplate();

		// Set recipient
		$mailTemplate->setRecipient( $user->name , $user->email );

		// Set title
		$mailTemplate->setTitle( $title );

		// Set the contents
		$mailTemplate->setTemplate( 'site/user/remind.username' , $params );

		// Set the priority. We need it to be sent out immediately since this is user registrations.
		$mailTemplate->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

		// Try to send out email now.
		$state 		= $mailer->create( $mailTemplate );

		return $state;
	}


	/**
	 * Delete any oauth related data here
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteOAuth( $userId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		// Get the correct oauth id first.
		$sql->select( '#__social_oauth' );
		$sql->where( 'uid' , $userId );
		$sql->where( 'type' , SOCIAL_TYPE_USER );

		$db->setQuery( $sql );

		$oauthId	= $db->loadResult();

		if( $oauthId )
		{
			$sql->delete( '#__social_oauth' );
			$sql->where( 'uid' , $userId );
			$sql->where( 'type' , SOCIAL_TYPE_USER );
			$db->setQuery( $sql );
			$db->Query();

			$sql->clear();

			// Delete oauth histories as well
			$sql->delete( '#__social_oauth_history' );
			$sql->where( 'oauth_id' , $oauthId );
		}

		return true;
	}

	/**
	 * Creates a user in the system
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Users' );
	 * $model->create( $username , $email , $password );
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableRegistration		The registration object.
	 * @return	int		The last sequence for the profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function create( $data , SocialUser $user , SocialTableProfile $profile )
	{
		// Get a list of user groups this profile is assigned to
		$json 		= Foundry::json();
		$groups 	= $json->decode( $profile->gid );

		// Need to bind the groups under the `gid` column from Joomla.
		$data[ 'gid' ]  = $groups;

		// Bind the posted data
		$user->bind( $data , SOCIAL_POSTED_DATA );

		// Detect the profile type's registration type.
		$type 	= $profile->getRegistrationType();

		// We need to generate an activation code for the user.
		if( $type == 'verify' )
		{
			$user->activation 	= Foundry::getHash( JUserHelper::genRandomPassword() );
		}

		// If the registration type requires approval or requires verification, the user account need to be blocked first.
		if( $type == 'approvals' || $type == 'verify')
		{
			$user->block 	= 1;
		}

		// Get registration type and set the user's state accordingly.
		$user->set( 'state' , constant( 'SOCIAL_REGISTER_' . strtoupper( $type ) ) );

		// Save the user object
		$state 		= $user->save();

		// If there's a problem saving the user object, set error message.
		if( !$state )
		{
			$this->setError( $user->getError() );
			return false;
		}

		// Set the user with proper `profile_id`
		$user->profile_id 	= $profile->id;

		// Once the user is saved successfully, add them into the profile mapping.
		$profile->addUser( $user->id );

		return $user;
	}


	public function setUserGroupsBatch( $ids )
	{
		// Get our version info
		$version 	= Foundry::version();
		// Initialize helper object.
		$className 		= 'SocialUserHelper' . ucfirst( $version->getCodeName() );
		// Get the path to the helper file.
		$file 			= SOCIAL_LIB . '/user/helpers/' . $version->getCodeName() . '.php';
		require_once( $file );

		call_user_func_array( array( $className , 'setUserGroupsBatch' ) , array( $ids ) );
	}

}
