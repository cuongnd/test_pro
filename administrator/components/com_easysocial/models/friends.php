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

/**
 * Object mapping for lists.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class EasySocialModelFriends extends EasySocialModel
{
	private $data			= null;

	function __construct()
	{
		parent::__construct( 'friends' );
	}

	public function getSuggestedFriends( $userId = null, $limit = '0', $countOnly = false )
	{
		$db = Foundry::db();

		$user 	= Foundry::user( $userId );
		$result = array();
		$total  = 0;

		// $commonQuery = 'select if(a.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ', a.' . $db->nameQuote( 'target_id' ) . ', a.' . $db->nameQuote( 'actor_id' ) . ') AS ' . $db->nameQuote( 'friend_id' );
		// $commonQuery .= ' FROM ' . $db->nameQuote( '#__social_friends' ) . ' as a WHERE ( a.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ' or a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $user->id ) . ')';

		// constructing all friend connection query.
		$allFiendsConnection = 'select if(a.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ', a.' . $db->nameQuote( 'target_id' ) . ', a.' . $db->nameQuote( 'actor_id' ) . ') AS ' . $db->nameQuote( 'friend_id' );
		$allFiendsConnection .= ' FROM ' . $db->nameQuote( '#__social_friends' ) . ' as a';
		$allFiendsConnection .= ' WHERE (';
		$allFiendsConnection .= ' ( a.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ' and a.' . $db->nameQuote( 'state' ) . ' != ' . $db->Quote( SOCIAL_FRIENDS_STATE_REJECTED ) . ')';
		$allFiendsConnection .= ' OR ';
		$allFiendsConnection .= ' ( a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $user->id ) . ' and a.' . $db->nameQuote( 'state' ) . ' != ' . $db->Quote( SOCIAL_FRIENDS_STATE_REJECTED ) . ')' ;
		$allFiendsConnection .= ')';

		// now for the common query, we only want approved friends.
		$commonQuery = 'select if(a.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ', a.' . $db->nameQuote( 'target_id' ) . ', a.' . $db->nameQuote( 'actor_id' ) . ') AS ' . $db->nameQuote( 'friend_id' );
		$commonQuery .= ' FROM ' . $db->nameQuote( '#__social_friends' ) . ' as a ';
		$commonQuery .= ' WHERE (';
		$commonQuery .= ' ( a.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ' and a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS ) . ')';
		$commonQuery .= ' OR ';
		$commonQuery .= ' ( a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $user->id ) . ' and a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS ) . ')' ;
		$commonQuery .= ')';

		// retrieve friends of friends, who isn't your friend yet.
		$query = '';



		$query = 'select *';

		$query .= ' from';
		$query .= '	 (';
		$query .= '		select if( b.' . $db->nameQuote( 'actor_id' ) . ' = fwm.' . $db->nameQuote( 'friend_id' ) . ', b.' . $db->nameQuote( 'target_id' ) . ', b.' . $db->nameQuote( 'actor_id' ) . ') as ' . $db->nameQuote( 'ffriend_id' ) . ',';
		$query .= '			count(1) as ' . $db->nameQuote( 'score' );
		$query .= '		from';
		$query .= '			( ' . $commonQuery . ' ) as fwm';
		$query .= '		inner join';
		$query .= '			'  . $db->nameQuote( '#__social_friends') . ' as b';
		$query .= '				on ( b.' . $db->nameQuote( 'actor_id' ) . ' = fwm.' . $db->nameQuote( 'friend_id' ) . ' or b.' . $db->nameQuote( 'target_id' ) . ' = fwm.' . $db->nameQuote( 'friend_id') .')';
		$query .= '				and b.' . $db->nameQuote( 'actor_id' ) . ' != ' . $db->Quote( $user->id ) . ' and b.' . $db->nameQuote( 'target_id' ) . ' != ' . $db->Quote( $user->id );
		$query .= ' 	where b.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query .= '		group by ' . $db->nameQuote( 'ffriend_id' );
		$query .= '	) as tfs';


		$query .= ' INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu';
		$query .= ' ON uu.' . $db->nameQuote( 'id' ) . ' = tfs.' . $db->nameQuote( 'ffriend_id' ) ;
		$query .= ' AND uu.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( '0' );


		$query .= ' where ' . $db->nameQuote( 'ffriend_id' );
		$query .= ' not in (';
		$query .= '  	' . $allFiendsConnection;
		$query .= '  )';

		$query .= ' order by ' . $db->nameQuote( 'score' ) . ' desc';

		if( !empty( $limit ) && $limit > 0 )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $query , true );
			$total = $this->getTotal();

			// Get the list of users
			$result 	= $this->getData( $query );
		}
		else
		{
			$db->setQuery( $query );
			$result 	= $db->loadObjectList();

			$total = count( $result );
		}

		$runTrigger = true;
		if( $limit && $total >= $limit )
		{
			$runTrigger = false;
		}

		// now we trigger custom fields to search users which has the similar
		// data.
		$fieldsLib		= Foundry::fields();
		$fieldModel  	= Foundry::model( 'Fields' );
		$fieldsResult 	= array();


		if( $runTrigger )
		{

			$fieldSQL = 'select a.*, b.' . $db->nameQuote( 'type' ) . ', b.' . $db->nameQuote( 'element' ) . ', b.' . $db->nameQuote( 'group' );
			$fieldSQL .= ', c.' . $db->nameQuote( 'uid' ) . ' as ' . $db->nameQuote( 'profile_id' );
			$fieldSQL .= ' FROM ' . $db->nameQuote( '#__social_fields' ) . ' as a';
			$fieldSQL .= ' INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' as b';
			$fieldSQL .= ' ON a.app_id = b.id';
			$fieldSQL .= ' LEFT JOIN ' . $db->nameQuote( '#__social_fields_steps' ) . ' as c';
			$fieldSQL .= ' ON a.step_id = c.id';
			$fieldSQL .= ' where a.' . $db->nameQuote( 'friend_suggest' ) . ' = ' . $db->Quote( '1' );
			$db->setQuery( $fieldSQL );

			$fields = $db->loadObjectList();
			if( count( $fields ) > 0 )
			{
				foreach( $fields as $item )
				{

					$field 	= Foundry::table( 'Field' );
					$field->bind( $item );

					$field->profile_id 	= $item->profile_id;
					$field->data 		= isset( $item->data ) ? $item->data : '';

					$userFieldData = $fieldModel->getCustomFieldsValue( $field->id, $user->id, SOCIAL_FIELDS_GROUP_USER );

					$args 			= array( $user, $userFieldData );
					$f 				= array( &$field );

					$dataResult 	= $fieldsLib->trigger( 'onFriendSuggestSearch' , SOCIAL_FIELDS_GROUP_USER , $f , $args );
					$fieldsResult 	= array_merge( $fieldsResult, $dataResult );
				}
			}

		}

		$tmpResult = array_merge( $result, $fieldsResult );

		//reset $result
		$result = array();

		foreach( $tmpResult as $tmpItem )
		{
			if(! array_key_exists( $tmpItem->ffriend_id , $result ) )
			{
				$result[ $tmpItem->ffriend_id ] = $tmpItem;
			}
		}


		if( $countOnly )
		{
			return count( $result );
		}



		$friends 	= array();

		if( $result )
		{
			//preload users.
			$tmp = array();
			foreach( $result as $item )
			{
				$tmp[] = $item->ffriend_id;
			}
			Foundry::user( $tmp );

			// getting the result.
			foreach( $result as $item )
			{
				$obj = new stdClass();

				$obj->friend = Foundry::user( $item->ffriend_id );
				$obj->count  = $item->score;

				$friends[] = $obj;
			}
		}

		return $friends;
	}

	public function arrayObjectUnique( $array, $keep_key_assoc = false)
	{
	    $duplicate_keys = array();
	    $tmp         = array();

	    foreach ($array as $key=>$val)
	    {
	        // convert objects to arrays, in_array() does not support objects
	        if (is_object($val))
	            $val = (array)$val;

	        if (!in_array($val, $tmp))
	            $tmp[] = $val;
	        else
	            $duplicate_keys[] = $key;
	    }

	    foreach ($duplicate_keys as $key)
	        unset($array[$key]);

	    return $keep_key_assoc ? $array : array_values($array);
	}

	/**
	 * Get mutuals user ids.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	int		The source user id.
	 * @param	int		The target user id.
	 * @return	array   user id.
	 */
	public function getMutualFriends( $source, $target, $limit = 0 )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$query = 'select z.`afriend` from';
		$query .= $this->buildMutualFriendQueryTableAlias( $source, $target );


		$rows = '';
		if( $limit )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $query , true );

			// Get the list of users
			$rows 	= $this->getData( $query );
		}
		else
		{
			$db->setQuery( $query );
			$rows 	= $db->loadObjectList();
		}


		$friends	= array();
		if( $rows )
		{
			$tmpIds = array();
			foreach( $rows as $row )
			{
				$tmpIds[] = $row->afriend;
			}

			// preload user
			Foundry::user( $tmpIds );

			foreach( $rows as $row )
			{
				$friends[]	= Foundry::user( $row->afriend );
			}
		}

		return $friends;
	}

	public function getMutualFriendCount( $source, $target )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$query = 'select count(1) from';
		$query .= $this->buildMutualFriendQueryTableAlias( $source, $target );

		$sql->raw( $query );

		$db->setQuery( $sql );

		$result = $db->loadResult();

		return ( empty($result) ) ? '0' : $result;
	}


	private function buildMutualFriendQueryTableAlias( $source, $target )
	{
		$db = Foundry::db();

		$query = '	(select if(a.`actor_id` = ' . $db->Quote( $source ) . ', a.`target_id`, a.`actor_id` ) as `afriend`';
		$query .= '		from `#__social_friends` as a where ( (a.`actor_id` = ' . $db->Quote( $source ) . ' and a.`state` = 1) OR (a.`target_id` = ' . $db->Quote( $source ) . 'and a.`state` = 1) ) ) as z';
		$query .= ' inner join ';
		$query .= '	(select if( a.`actor_id` = ' . $db->Quote( $target ) . ', a.`target_id`, a.`actor_id` ) as `afriend`';
		$query .= ' 	from `#__social_friends` as a where ( (a.`actor_id` = ' . $db->Quote( $target ) . ' and a.`state` = 1) OR ( a.`target_id` = ' . $db->Quote( $target ) . ' and a.`state` = 1 ) ) ) as x';
		$query .= ' on z.`afriend` = x.`afriend`';
		$query .= ' inner join `#__users` u on z.`afriend` = u.`id` and u.`block` = ' . $db->Quote( '0' );

		//debug code;
		//$query = ' (select id as afriend from #__users) as z';

		return $query;
	}



	/**
	 * Determines if the target is a friends of friend with the source.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	int		The target user id.
	 * @param	int		The source user id.
	 * @return	bool	True if is a 2nd level friend.
	 */
	public function isFriendsOfFriends( $target , $source )
	{
		$db 	= Foundry::db();

		$query		= array();

		$query[]	= 'SELECT b.' . $db->nameQuote( 'target_id' ) . ' AS ' . $db->nameQuote( 'id' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_friends' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'target_id' ) . ' = b.' . $db->nameQuote( 'actor_id' );
		$query[]	= 'AND a.' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $target );
		$query[]	= 'AND b.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $source );
		$query[]	= 'UNION';

		$query[]	= 'SELECT b.' . $db->nameQuote( 'actor_id' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_friends' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'actor_id' ) . ' = b.' . $db->nameQuote( 'target_id' );
		$query[]	= 'AND a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $target );
		$query[]	= 'AND b.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $source );

		$query[]	= 'UNION';
		$query[]	= 'SELECT ' . $db->nameQuote( 'id' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' );
		$query[]	= 'WHERE(';
		$query[]	= $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $target );
		$query[]	= 'AND ' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $source );
		$query[]	= 'OR ' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $source );
		$query[]	= 'AND ' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $target );
		$query[]	= ')';

		// $query = '(select `target_id` as `id` from `#__social_friends` as a1';
		// $query .= ' where ( exists (select aa1.`actor_id` from `#__social_friends` as aa1 where a1.`actor_id` = aa1.`actor_id` and aa1.`target_id` = ' . $db->Quote( $this->target ) . ')';
		// $query .= ' or exists (select aa1.`target_id` from `#__social_friends` as aa1 where a1.`actor_id` = aa1.`target_id` and aa1.`actor_id` = ' . $db->Quote( $this->target ) . ') )';
		// $query .= ' and a1.`target_id` = ' . $db->Quote( $userId ) . ')';
		// $query .= ' union ';
		// $query .= ' (select `actor_id` as `id` from `#__social_friends` as a2';
		// $query .= '  where ( exists (select aa2.`actor_id` from `#__social_friends` as aa2 where a2.`target_id` = aa2.`actor_id` and aa2.`target_id` = ' . $db->Quote( $this->target ) . ')';
		// $query .= '  or exists (select aa2.`target_id` from `#__social_friends` as aa2 where a2.`target_id` = aa2.`target_id` and aa2.`actor_id` = ' . $db->Quote( $this->target ) . ') )';
		// $query .= '  and a2.`actor_id` = ' . $db->Quote( $userId ) . ')';
		// $query .= '  union ';
		// $query .= ' (select `id` from `#__users` as a3';
		// $query .= '  where ( exists (select aa3.`actor_id` from `#__social_friends` as aa3 where a3.`id` = aa3.`actor_id` and aa3.`target_id` = ' . $db->Quote( $this->target ) . ')';
		// $query .= '  or exists (select aa3.`target_id` from `#__social_friends` as aa3 where a3.`id` = aa3.`target_id` and aa3.`actor_id` = ' . $db->Quote( $this->target ) . ') )';
		// $query .= '  and a3.`id` = ' . $db->Quote( $userId ) . ')';

		$db->setQuery( $query );
		$result = $db->loadResult();

		return !empty( $result );
	}

	/**
	 * Determines if the provided id's are friends.
	 *
	 * @since	1.0
	 * @access	public
	 * @param 	int 	$source		The source user id.
	 * @param	int 	$target		The target user id.
	 * @return	boolean				True if they are friends, false otherwise.
	 */
	public function isFriends( $source , $target , $state = SOCIAL_FRIENDS_STATE_FRIENDS )
	{
		$db 		= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT COUNT(1)';
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' );
		$query[]	= 'WHERE';
		$query[]	= '( ' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $source ) . ' AND ' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $target ) . ' AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( $state ) . ' )';
		$query[]	= 'OR';
		$query[]	= '( ' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $target ) . ' AND ' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $source ) . ' AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( $state ) . ')';

		// Debug
		// $query 	= implode( ' ' , $query );
		// echo str_ireplace( '#__' , 'jos_' , $query );exit;

		// Glue back query.
		$db->setQuery( $query );

		$result 	= (bool) $db->loadResult();

		return $result;
	}

	/**
	 * Determines if the provided id's are friends.
	 *
	 * @since	1.0
	 * @access	public
	 * @param 	int 	$source		The source user id.
	 * @param	int 	$target		The target user id.
	 * @return	boolean				True if they are friends, false otherwise.
	 */
	// public function getState( $source , $target )
	// {
	// 	$db 	= Foundry::db();

	// 	$query 		= array();

	// 	$query[]	= 'SELECT ' . $db->nameQuote( 'state' );
	// 	$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' );
	// 	$query[]	= 'WHERE';
	// 	$query[]	= '( ' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $source ) . ' OR ' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $target ) . ' )';
	// 	$query[]	= 'AND';
	// 	$query[]	= '( ' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $target ) . ' OR ' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $source ) . ')';

	// 	$db->setQuery( $query );
	// 	$state 		= $db->loadResult();

	// 	return $state;
	// }

	/**
	 * Determines if there is an existing request from source user to targeted user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param 	int 	$source		The source user id.
	 * @param	int 	$target		The target user id.
	 * @return	boolean				True if they are friends, false otherwise.
	 */
	public function isPendingFriends( $source , $target )
	{
		$db 	= Foundry::db();

		$query 	= 'SELECT COUNT(1)';
		$query	.= ' FROM ' . $db->nameQuote( '#__social_friends' );
		$query	.= ' WHERE';
		$query 	.= ' (' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $source ) . ' OR ' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $target ) . ' )';
		$query 	.= ' AND';
		$query	.= ' (' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $target ) . ' OR ' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $source ) . ' )';
		$query	.= ' AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_PENDING );

		$db->setQuery( $query );
		$result 	= (bool) $db->loadResult();

		return $result;
	}

	/**
	 * Get a list of online friends
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getOnlineFriends( $id )
	{
		$db 	= Foundry::db();
		$query 	= array();

		$query[]	= 'SELECT * FROM(';

		$query[]	= 'SELECT a.*, IF(a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id ) . ', a.' . $db->nameQuote( 'actor_id' ) . ', a.' . $db->nameQuote( 'target_id' ) . ') as ' . $db->nameQuote( 'friendid' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' ) . ' AS a';

		$query[] 	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu';
		$query[] 	= 'ON uu.' . $db->nameQuote( 'id' ) . ' = if( a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $id ) . ', a.' . $db->nameQuote( 'actor_id' ) . ', a.' . $db->nameQuote( 'target_id' ) . ')';
		$query[] 	= 'AND uu.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( '0' );

		$query[]	= 'WHERE (';
		$query[]	= 'a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id ) . ' OR a.' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $id );
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query[]	= ')';

		$query[]	= ') AS ' . $db->nameQuote( 'onlinefriend' );
		$query[]	= 'WHERE EXISTS( SELECT ' . $db->nameQuote( 'userid' ) . ' FROM ' . $db->nameQuote( '#__session' ) . ' AS s WHERE s.' . $db->nameQuote( 'userid' ) . '= onlinefriend.' . $db->nameQuote( 'friendid' ) . ')';

		$query		= implode( ' ' , $query );

		$db->setQuery( $query );

		$rows	= $db->loadObjectList();

		if( !$rows )
		{
			return false;
		}

		$friends	= array();

		foreach( $rows as $row )
		{
			if( $row->actor_id != $id )
			{
				$friends[]	= Foundry::user( $row->actor_id );
			}

			if( $row->target_id != $id )
			{
				$friends[]	= Foundry::user( $row->target_id );
			}
		}

		return $friends;
	}

	/**
	 * Retrieves a list of friends
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The user's id
	 * @param	Array	An array of options. state - SOCIAL_FRIENDS_STATE_PENDING or SOCIAL_FRIENDS_STATE_FRIENDS
	 *
	 * @return	Array
	 */
	public function getFriends( $id , $options = array() )
	{
		$db			= Foundry::db();
		$sql 		= $db->sql();

		$query[]	= 'SELECT a.*, if( a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $id ) . ', a.' . $db->nameQuote( 'actor_id' ) . ', a.' . $db->nameQuote( 'target_id' ) . ') AS friendid';
		$query[] 	= 'FROM ' . $db->nameQuote( '#__social_friends' ) . ' AS a';

		$query[] 	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu';
		$query[] 	= 'ON uu.' . $db->nameQuote( 'id' ) . ' = if( a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $id ) . ', a.' . $db->nameQuote( 'actor_id' ) . ', a.' . $db->nameQuote( 'target_id' ) . ')';
		$query[] 	= 'AND uu.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( '0' );

		// Check if the caller wants to filter friends by list.
		$listId 	= isset( $options[ 'list_id' ] ) ? $options[ 'list_id' ] : null;

		if( !is_null( $listId ) )
		{
			$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_lists_maps' ) . ' AS b';
			$query[]	= 'ON (';
			$query[]	= 'a.' . $db->nameQuote( 'target_id' ) . '= b.' . $db->nameQuote( 'target_id' );
			$query[]	= 'OR';
			$query[]	= 'a.' . $db->nameQuote( 'actor_id' ) . '= b.' . $db->nameQuote( 'target_id' );
			$query[]	= ')';
			$query[]	= 'AND b.' . $db->nameQuote( 'target_type' ) . '=' . $db->Quote( SOCIAL_TYPE_USER );
			$query[]	= 'AND b.' . $db->nameQuote( 'list_id' ) . '=' . $db->Quote( $listId );
		}


		$query[]	= 'WHERE 1';

		// Check if state is passed in.
		$state 		= isset( $options[ 'state' ] ) ? $options[ 'state' ] : SOCIAL_FRIENDS_STATE_FRIENDS;
		$isRequest  = isset( $options[ 'isRequest' ] ) ? $options[ 'isRequest' ] : false;
		$limit		= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : false;

		// Add filtering by state.
		if( !is_null( $state ) && $state == SOCIAL_FRIENDS_STATE_PENDING && !$isRequest )
		{
			$query[]	= 'AND a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id );
			$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_PENDING ) ;
		}
		else if( !is_null( $state ) && $state == SOCIAL_FRIENDS_STATE_PENDING && $isRequest )
		{
			$query[]	= 'AND a.' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $id );
			$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_PENDING );
		}
		else
		{
			$query[]	= 'AND';
			$query[]	= '(a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id );
			$query[]	= 'AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS ) . ')';
			$query[]	= 'OR';
			$query[]	= '(a.' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $id );
			$query[]	= 'AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS ) . ')';
		}

		// Glue back query.
		$query 	= implode( ' ' , $query );

		if( $limit != 0 )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $query , true );

			// Get the list of users
			$rows 	= $this->getData( $query );
		}
		else
		{

			$db->setQuery( $query );
			$rows 	= $db->loadObjectList();
		}

		if( !$rows )
		{
			return false;
		}

		$friends	= array();

		$idONLY 	= isset( $options[ 'idonly' ] ) ? true : false;

		foreach( $rows as $row )
		{
			if( $row->actor_id != $id )
			{
				$friends[]	= ( $idONLY ) ? $row->actor_id : Foundry::user( $row->actor_id );
			}

			if( $row->target_id != $id )
			{
				$friends[]	= ( $idONLY ) ? $row->target_id : Foundry::user( $row->target_id );
			}
		}

		return $friends;
	}

	/**
	 * Retrieves a list of friends that are in pending approval state.
	 *
	 * Example:
	 *
	 * <code>
	 * <?php
	 * $my 		= Foundry::user();
	 * $model 	= Foundry::model( 'Friends' );
	 *
	 * // Returns a list of friends that are pending my approval.
	 * $model->getPendingRequests( $my->id );
	 * ?>
	 * </code>
	 *
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 		The user's id
	 *
	 * @return	Array
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getPendingRequests( $id )
	{
		$db			= Foundry::db();

		$query 		= array();
		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_friends' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id );
		$query[]	= 'AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_PENDING );

		// Glue query back.
		$query 		= implode( ' ' , $query );

		// Get the total number of records before applying any pagination.
		$this->total		= $this->getTotal( $query );

		$db->setQuery( $query );

		$rows		= $db->loadObjectList();

		if( !$rows )
		{
			return false;
		}

		$friends	= array();

		foreach( $rows as $row )
		{
			$friend		= Foundry::table( 'Friend' );
			$friend->bind( $row );

			$friends[]	= $friend;
		}

		return $friends;
	}

	/**
	 * Retrieves total number of friends a user has.
	 *
	 * @access	public
	 * @param	Array	$options An array of options.
	 *
	 * @return	int		Total friends count.
	 **/
	public function getFriendsCount( $userId )
	{
		$db			= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__social_friends' );
		$query[]	= 'WHERE';
		$query[]	= '( (';
		$query[]	= $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $userId );
		$query[]	= 'AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query[]	= ') OR (';
		$query[]	= $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $userId );
		$query[]	= 'AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query[]	= ') )';

		// Glue back the query.
		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$total 		= $db->loadResult();

		return $total;
	}

	/**
	 * Returns the total number of friend requests a user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 		The user's id.
	 *
	 * @return	int		The total number of requests.
	 */
	public function getTotalRequests( $id )
	{
		$db 	= Foundry::db();

		$query 	= 'SELECT COUNT(1) FROM';
		$query	.= ' ' . $db->nameQuote( '#__social_friends' ) . ' as a ';

		$query	.= ' INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu';
		$query 	.= ' ON uu.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'actor_id' );
		$query 	.= ' AND uu.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( '0' );

		$query 	.= ' WHERE a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id );
		$query 	.= ' AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_PENDING );

		$db->setQuery( $query );
		$count 	= $db->loadResult();

		return $count;
	}

	/**
	 * Returns the total number of friend a user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 		The user's id.
	 *
	 * @return	int		The total number of requests.
	 */
	public function getTotalFriends( $id )
	{
		$db 	= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT COUNT(1)';
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' ) . ' AS a';

		$query[] 	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu';
		$query[] 	= 'ON uu.' . $db->nameQuote( 'id' ) . ' = if( a.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $id ) . ', a.' . $db->nameQuote( 'actor_id' ) . ', a.' . $db->nameQuote( 'target_id' ) . ')';
		$query[] 	= 'AND uu.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( '0' );


		$query[]	= 'WHERE';
		$query[]	= '( (';
		$query[]	= 'a.' . $db->nameQuote( 'target_id' ) . '=' . $db->Quote( $id );
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query[]	= ') OR (';
		$query[]	= 'a.' . $db->nameQuote( 'actor_id' ) . '=' . $db->Quote( $id );
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query[]	= ') )';

		$db->setQuery( $query );
		$count 	= (int) $db->loadResult();

		return $count;
	}


	/**
	 * Returns the total number of friend request a user made.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 		The user's id.
	 *
	 * @return	int		The total number of requests.
	 */
	public function getTotalRequestSent( $id )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_friends', 'a' );

		$sql->join( '#__users' , 'uu' , 'INNER' );
		$sql->on( 'uu.id', 'a.target_id' );

		$sql->where( 'a.actor_id', $id );
		$sql->where( 'a.state', SOCIAL_FRIENDS_STATE_PENDING );

		$sql->where( 'uu.block', '0' );


		$db->setQuery( $sql->getTotalSql() );

		$count 	= (int) $db->loadResult();

		return $count;
	}



	/**
	 * Returns the total number of friend a user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 		The user's id.
	 *
	 * @return	int		The total number of requests.
	 */
	public function getTotalPendingFriends( $id )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_friends', 'a' );

		$sql->join( '#__users' , 'uu' , 'INNER' );
		$sql->on( 'uu.id', 'a.actor_id' );

		$sql->where( 'a.target_id', $id );
		$sql->where( 'a.state', SOCIAL_FRIENDS_STATE_PENDING );

		$sql->where( 'uu.block', '0' );

		$db->setQuery( $sql->getTotalSql() );

		$count 	= (int) $db->loadResult();

		return $count;
	}

	/**
	 * Cancels a friend request.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The friend's record id.
	 */
	public function cancel( $id )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_friends' );
		$sql->where( 'id' , $id );

		$db->setQuery( $sql );

		$db->Query();

		return true;
	}

	/**
	 * Searches for a user's friend.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The searcher's id.
	 * @param	string	The search term.
	 * @param	string	The search type. Whether to search for username or name.
	 * @param	Array	An array of options (term - the word to search for , exclude - excluded users, privacy rules)
	 * @return 	Array	An array of SocialUser objects.
	 */
	public function search( $id , $term , $type , $options = array() )
	{
		$db = Foundry::db();

		$includeMe = ( isset( $options['includeme'] ) ) ? $options['includeme'] : null;

		$query = array();

		$query[] = 'SELECT b.' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__users' ) . ' AS b';
		$query[] = 'inner join (';
		$query[] = '	select if( f.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $id ) . ', f.' . $db->nameQuote( 'target_id' ) . ', f.' . $db->nameQuote( 'actor_id' ) . ' ) AS ' . $db->nameQuote( 'friend' );
		$query[] = '		from ' . $db->nameQuote( '#__social_friends' ) . ' as f';
		$query[] = ' 			where ( ( f.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $id ) . ' and f.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS ) . ')';
		$query[] = '					OR';
		$query[] = ' 				  ( f.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $id ) . ' and f.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS ) . ' ) )';

		if( $includeMe )
		{
			$query[] = ' UNION select ' . $id . ' AS ' . $db->nameQuote( 'friend' );
		}

		$query[] = ') as z';
		$query[] = 'ON b.' . $db->nameQuote( 'id' ) . ' = z.' . $db->nameQuote( 'friend' );

		$query[] = 'where 1 = 1';

		if(! $includeMe )
		{
			$query[] = 'and b.' . $db->nameQuote( 'id' ) . ' != ' . $db->Quote( $id );
		}

		if( $type == SOCIAL_FRIENDS_SEARCH_NAME || $type == SOCIAL_FRIENDS_SEARCH_REALNAME )
		{
			$query[]	= 'AND b.' . $db->nameQuote( 'name' ) . ' LIKE ' . $db->Quote( '%' . $term . '%' );
		}

		if( $type == SOCIAL_FRIENDS_SEARCH_USERNAME )
		{
			$query[]	= 'AND b.' . $db->nameQuote( 'username' ) . ' LIKE ' . $db->Quote( '%' . $term . '%' );
		}

			// Searched user must be valid user.
		$query[]	= 'AND b.' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );

		if( isset( $options['exclude'] ) && $options['exclude'] )
		{
			$excludeIds = '';
			if(! is_array( $options['exclude'] ) )
			{
				$options['exclude'] = explode( ',', $options['exclude'] );
			}

			foreach( $options['exclude']  as $id )
			{
				$excludeIds .= ( empty( $excludeIds ) ) ? $db->Quote( $id ) : ', ' . $db->Quote( $id );
			}

			$query[]	= 'AND b.' . $db->nameQuote( 'id' ) . ' NOT IN ( ' . $excludeIds . ')';

		}

		// Glue back query.
		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$result 	= $db->loadColumn();

		if( !$result )
		{
			return false;
		}

		if( isset( $options['privacy'] ) )
		{
			$my = Foundry::user();

			$privacyLib  = $my->getPrivacy();

			$privacyRule = $options['privacy'];

			$finalResult = array();

			foreach( $result as $rs )
			{
				$addItem = $privacyLib->validate( $privacyRule, $rs );

				if( $addItem )
				{
					$finalResult[] = $rs;
				}
			}

			$result = $finalResult;

		}

		$friends 	= Foundry::user( $result );

		return $friends;
	}



	public function searchOld( $id , $term , $type , $options = array() )
	{
		$db			= Foundry::db();
		$query 		= array();
		$query[]	= 'SELECT b.' . $db->nameQuote( 'id' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_friends' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b';
		$query[]	= 'ON(';
		$query[]	= 'b.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'actor_id' );
		$query[]	= 'OR';
		$query[]	= 'b.' . $db->nameQuote( 'id' ) . ' = a.' . $db->nameQuote( 'target_id' );
		$query[]	= ')';

		// The user must really be their friend.
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );

		// If we are searching for friend, we dont want to search ourself.
		$query[]	= 'AND b.' . $db->nameQuote( 'id' ) . '!=' . $db->Quote( $id );

		if( $type == SOCIAL_FRIENDS_SEARCH_NAME )
		{
			$query[]	= 'WHERE b.' . $db->nameQuote( 'name' ) . ' LIKE ' . $db->Quote( '%' . $term . '%' );
		}

		if( $type == SOCIAL_FRIENDS_SEARCH_USERNAME )
		{
			$query[]	= 'WHERE b.' . $db->nameQuote( 'username' ) . ' LIKE ' . $db->Quote( '%' . $term . '%' );
		}

		// Searched user must be valid user.
		$query[]	= 'AND b.' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );

		// @TODO: Only fetch users who has privacy to be searched?

		// Glue back query.
		$query 		= implode( ' ' , $query );

		echo $query;exit;


		$db->setQuery( $query );

		$result 	= $db->loadColumn();

		if( !$result )
		{
			return false;
		}

		$friends 	= Foundry::user( $result );

		return $friends;
	}
}
