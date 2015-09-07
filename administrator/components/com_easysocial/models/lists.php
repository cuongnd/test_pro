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

class EasySocialModelLists extends EasySocialModel
{
	private $data			= null;

	function __construct()
	{
		parent::__construct( 'lists' );
	}

	/**
	 * Sets a list as the default list
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	Determines the state.
	 */
	public function setDefault( $id , $userId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		// Update all existing lists to not default.
		$sql->update( '#__social_lists' );
		$sql->set( 'default' , 0 );
		$sql->where( 'user_id' , $userId );

		$db->setQuery( $sql );
		$db->Query();

		// Reset the sql pointer
		$sql->clear();

		$sql->update( '#__social_lists' );
		$sql->set( 'default' , 1 );
		$sql->where( 'id' , $id );
		$sql->where( 'user_id' , $userId );
		$db->setQuery( $sql );
		$db->Query();

		return true;
	}

	/**
	 * Retrieves a list of friend list for a specific node id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options.
	 * @return
	 */
	public function getLists( $options = array() )
	{
		$db 		= Foundry::db();
		$query 		= array();

		$query[]	= 'SELECT a.* FROM ' . $db->nameQuote( '#__social_lists' ) . ' AS a';

		// Determine if we should show empty lists
		$showEmpty 	= isset( $options[ 'showEmpty' ] ) ? $options[ 'showEmpty' ] : null;

		if( $showEmpty == '0' )
		{
			$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_lists_maps' ) . ' AS b';
			$query[]	= 'ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'list_id' );

			$query[] 	= 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu';
			$query[] 	= 'ON uu.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'target_id' );
			$query[] 	= 'AND uu.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( '0' );
		}

		$query[]	= 'WHERE 1';

		// Check if caller wants to filter by state.
		$state 		= isset( $options[ 'state' ] ) ? $options[ 'state' ] : null;

		if( !is_null( $state ) )
		{
			// Ensure that it's an array
			$state 	= Foundry::makeArray( $state );

			$query[]	= ' AND (';

			for( $i = 0; $i < count( $state ); $i++)
			{
				$query[]	= 'a.' . $db->nameQuote( 'state' ) .'=' . $db->Quote( $state[ $i ] );

				if( next( $state ) !== false )
				{
					$query[]	= ' OR ';
				}
			}

			$query[]	= ')';
		}

		// Check if the caller wants to filter by user id.
		$user 		= isset( $options[ 'user_id' ] ) ? $options[ 'user_id' ] : null;

		if( !is_null( $user ) )
		{
			$query[]	= 'AND a.' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $user );
		}


		// Glue query back.
		$query 		= implode( ' ' , $query );

		// Set limit
		$countQuery	= str_ireplace( 'SELECT a.*', 'SELECT COUNT(1)', $query );

		$this->setTotal( $countQuery );

		// Get the data.
		$result		= $this->getData( $query );

		if( !$result )
		{
			return false;
		}

		$lists	= array();

		foreach( $result as $row )
		{
			$list	= Foundry::table( 'List' );
			$list->bind( $row );

			$lists[]	= $list;
		}

		return $lists;
	}

	/**
	 * Retrieves the list of members from this friend list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The unique list id.
	 * @return
	 */
	public function getMembers( $id , $idOnly = false )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_lists_maps' , 'a' );

		if( $idOnly )
		{
			$sql->column( 'a.target_id' );
		}

		$sql->join( '#__users' , 'uu' , 'INNER' );
		$sql->on( 'a.target_id' , 'uu.id' );

		// Check if the user is really still their friends or not.
		$sql->join( '#__social_friends' , 'b' , 'INNER' );
		$sql->on( 'a.target_id' , 'b.target_id' , '=' , 'OR' );
		$sql->on( 'a.target_id' , 'b.actor_id' , '=' , 'OR' );

		$sql->where( 'a.list_id' , $id );

		$sql->where( 'b.state' , SOCIAL_FRIENDS_STATE_FRIENDS );

		$sql->where( 'uu.block' , '0' );

		$db->setQuery( $sql );

		if( $idOnly )
		{
			$items 	= $db->loadColumn();
		}
		else
		{
			$items 	= $db->loadObjectList();
		}

		if( !$items )
		{
			return $items;
		}

		return $items;
	}

	/**
	 * Retrieves the total number of friends list a user has
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalLists( $userId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_lists' );
		$sql->column( 'COUNT(1)' , 'total' );
		$sql->where( 'user_id' , $userId );

		$db->setQuery( $sql );

		$total 	= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves the total number of members in a specific list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique list id.
	 * @return	int 	The total number of items.
	 */
	public function getCount( $id )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_lists_maps' , 'a' );
		$sql->column( 'COUNT(1)' );

		// Should only fetch from specific list
		$sql->where( 'list_id' , $id );


		$sql->join( '#__users' , 'uu' , 'INNER' );
		$sql->on( 'a.target_id' , 'uu.id' );


		$sql->join( '#__social_lists' , 'b' , 'INNER' );
		$sql->on( 'a.list_id' , 'b.id' );


		// Ensure that the user is really their friend
		$sql->join( '#__social_friends' , 'c' , 'INNER' );
		$sql->on( '(' );
		$sql->on( 'c.target_id' , 'a.target_id' );
		$sql->on( 'c.actor_id' , 'b.user_id' );
		$sql->on( ')' );
		$sql->on( '(' , '' , '' , 'OR' );
		$sql->on( 'c.actor_id' , 'a.target_id' );
		$sql->on( 'c.target_id' , 'b.user_id' );
		$sql->on( ')' );

		$sql->where( 'c.state' , SOCIAL_FRIENDS_STATE_FRIENDS );

		$sql->where( 'uu.block' , '0' );


		$db->setQuery( $sql );

		$total 		= (int) $db->loadResult();

		return $total;
	}

	/**
	 * Deletes the list mapping between the list and the users.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	$id 	The list's id.
	 * @return	boolean			True if success, false otherwise.
	 */
	public function deleteMapping( $id )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_lists_maps' );
		$sql->where( 'list_id' , $id );

		$db->setQuery( $sql );

		$state	= $db->Query();

		return $state;
	}
}
