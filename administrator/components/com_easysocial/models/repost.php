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

/**
 * Model for Shares.
 *
 * @author	Sam <sam@stackideas.com>
 * @since	1.0
 */
class EasySocialModelRepost extends EasySocialModel
{

	static $_reposts = array();

	/**
	 * Class construct happens here.
	 *
	 * @since	1.0
	 * @access	public
	 */
	function __construct()
	{
		parent::__construct( 'repost' );
	}

	public function getSharesFromArray( $keys , $group = SOCIAL_APPS_GROUP_USER )
	{
		$db 		= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_shares' );
		$query[]	= 'WHERE';

		for( $i = 0; $i < count( $keys ); $i++ )
		{
			$key 		= $keys[ $i ];

			$query[]	= '(';
			$query[]	= $db->nameQuote( 'uid' ) . '=' . $db->Quote( $key->uid );
			$query[]	= 'AND';
			$query[]	= $db->nameQuote( 'element' ) . '=' . $db->Quote( $key->type . '.' . $group );
			$query[]	= ')';

			if( next( $keys ) !== false )
			{
				$query[]	= 'OR';
			}
		}

		$query 	= implode( ' ' , $query );

		$db->setQuery( $query );

		$likes	= $db->loadObjectList();

		return $likes;
	}


	public function setStreamRepostBatch( $data )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$dataset = array();
		foreach( $data as $item )
		{
			$element 	= $item->context_type;

			if( $element != 'shares' )
			{
				$element	= 'stream';
				$uid 		= $item->id;

				$key = $uid . '.' . $element . '.' . SOCIAL_APPS_GROUP_USER;
				if( ! isset( self::$_reposts[ $key ] ) )
				{
					$dataset[ $element ][] = $uid;
				}
			}
		}

		// lets build the sql now.
		if( $dataset )
		{

			$mainSQL = '';
			foreach( $dataset as $element => $uids )
			{

				$ids = implode( ',', $uids );
				$element = $element . '.' . SOCIAL_APPS_GROUP_USER;

				foreach( $uids as $uid )
				{
					$key = $uid . '.' . $element;
					self::$_reposts[ $key ] = array();
				}

				$query = 'select `uid`, `element`, `user_id` from `#__social_shares` where `uid` IN (' . $ids . ')';
				$query .= ' and `element` = ' . $db->Quote( $element );

				$mainSQL .= ( empty( $mainSQL ) ) ? $query : ' UNION ' . $query;

			}

			$sql->raw( $mainSQL );
			$db->setQuery( $sql );

			$result = $db->loadObjectList();

			if( $result )
			{
				foreach( $result as $rItem )
				{
					$key = $rItem->uid . '.' . $rItem->element;
					self::$_reposts[ $key ][] = $rItem;
				}
			}

		}


	}

	private function getRepostData( $uid, $element )
	{
		$key 	= $uid . '.' . $element;

		if( ! isset( self::$_reposts[ $key ] ) )
		{
			$db 	= Foundry::db();
			$sql 	= $db->sql();

			$query = 'select `uid`, `element`, `user_id` from `#__social_shares` where `uid` = ' . $db->Quote( $uid );
			$query .= ' and `element` = ' . $db->Quote( $element );

			$sql->raw( $query );
			$db->setQuery( $query );

			$result = $db->loadObjectList();

			if( $result )
			{
				self::$_reposts[ $key ] = $result;
			}
			else
			{
				self::$_reposts[ $key ] = array();
			}

		}

		return self::$_reposts[ $key ];
	}




	private function _getRepostCount( $uid, $element )
	{
		$key 	= $uid . '.' . $element;
		$data   = $this->getRepostData( $uid, $element );

		return count( $data );
	}

	/**
	 * Delete likes related to an object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @param   string type.groups
	 * @return
	 */
	public function add( $uid , $element, $userId, $content = null )
	{
		$table = Foundry::table( 'Share' );

		$table->load( array( 'uid' => $uid, 'element' => $element, 'user_id' => $userId ) );

		if( $table->id )
		{
			// already shared before. js return true.
			return true;
		}

		$table->uid 	 = $uid;
		$table->element  = $element;
		$table->user_id  = $userId;
		$table->content  = $content;

		$state = $table->store();

		if( $state )
		{
			// update repost static variable
			$key 		= $uid . '.' . $element;
			$array 		= isset( self::$_reposts[ $key ] ) ? self::$_reposts[ $key ] : array() ;
			$array[] 	= $table;
			self::$_reposts[ $key ] = $array;

			return $table;
		}

		return false;
	}

	/**
	 * Delete likes related to an object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @param   string type.groups
	 * @return
	 */
	public function delete( $uid , $element, $userId  )
	{
		$table = Foundry::table( 'Share' );

		$table->load( array( 'uid' => $uid, 'element' => $element, 'user_id' => $userId ) );

		if( empty( $table->id ) )
		{
			return false;
		}

		$state =  $table->delete();

		if( $state )
		{
			// update repost static variable
			$key 		= $uid . '.' . $element;
			$array 		= self::$_reposts[ $key ];

			$new        = array();
			foreach( $array as $arr )
			{
				if( $arr->user_id != $userId )
				{
					$new = $arr;
				}
			}

			self::$_reposts[ $key ] = $new;
		}

		return $state;
	}

	public function getCount( $uid, $element )
	{
		$count = $this->_getRepostCount($uid, $element);
		return ( empty( $count ) ) ? 0 : $count;
	}


	/**
	 *	$type - return type. user ids or user objects
	 */

	public function getRepostUsers( $uid, $element, $idOnly = true )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_shares' )
			->column( 'user_id', '')
			->where( 'uid', $uid )
			->where( 'element', $element );

		$db->setQuery( $sql );

		$result 	= $db->loadColumn();

		if( $idOnly )
		{
			return $result;
		}

		$users = array();

		if( count( $result ) > 0 )
		{
			// preload user lists
			Foundry::user( $result );

			foreach( $result as $id )
			{
				$users[] = Foundry::user( $id );
			}
		}

		return $users;
	}

}
