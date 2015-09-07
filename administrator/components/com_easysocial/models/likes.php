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
 * Model for likes.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class EasySocialModelLikes extends EasySocialModel
{

	static $_likes = array();


	/**
	 * Class construct happens here.
	 *
	 * @since	1.0
	 * @access	public
	 */
	function __construct()
	{
		parent::__construct( 'likes' );
	}

	private function _getLikesCount( $uid, $type )
	{
		static $counts 	= array();

		$key 	= $uid . $type;

		if( !isset( $counts[ $key ] ) )
		{
			$db		= Foundry::db();
			$sql	= $db->sql();

			$sql->select( '#__social_likes' )
				->column( '1', '', 'count', true )
				->where( 'type', $type )
				->where( 'uid', $uid );

			$db->setQuery( $sql );
			$cnt   = $db->loadResult();

			$counts[ $key ]	= $cnt;
		}

		return $counts[ $key ];
	}

	public function setStreamLikesBatch( $data )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		//var_dump( $data );

		$streamModel = Foundry::model( 'Stream' );

		$dataset = array();
		foreach( $data as $item )
		{
			$relatedData = $streamModel->getBatchRalatedItem( $item->id );

			if( !$relatedData )
				continue;

			$element 	= $item->context_type;

			$streamItem = $relatedData[0];

			$uid 		= $streamItem->context_id;

			if( empty( $uid ) )
				continue;

			if( $element == 'story' || $element == 'links' )
			{
				$uid = $streamItem->uid;
			}

			$key = $uid . '.' . $element . '.' . SOCIAL_APPS_GROUP_USER;

			if( ! isset( self::$_likes[ $key ] ) )
			{
				$dataset[ $element ][] = $uid;
			}
		}

		// lets build the sql now.
		if( $dataset )
		{

			$mainSQL = '';
			foreach( $dataset as $element => $uids )
			{

				if( empty( $uids ) )
					continue;

				$ids = implode( ',', $uids );
				$element = $element . '.' . SOCIAL_APPS_GROUP_USER;

				foreach( $uids as $uid )
				{
					$key = $uid . '.' . $element;
					self::$_likes[ $key ] = array();
				}

				$query = 'select * from `#__social_likes` where `uid` IN (' . $ids . ')';
				$query .= ' and `type` = ' . $db->Quote( $element );

				$mainSQL .= ( empty( $mainSQL ) ) ? $query : ' UNION ' . $query;

			}

			if( $mainSQL )
			{
				$sql->raw( $mainSQL );
				$db->setQuery( $sql );

				$result = $db->loadObjectList();

				if( $result )
				{
					foreach( $result as $rItem )
					{
						$key = $rItem->uid . '.' . $rItem->type;

						$like 		= Foundry::table( 'Likes' );
						$like->bind( $rItem );

						self::$_likes[ $key ][] = $like;
					}
				}
			}

		}


	}

	public function setLikeItem( $key, $likeObj )
	{
		// update likes static variable
		$array 		= ( isset( self::$_likes[ $key ] ) ) ? self::$_likes[ $key ] : array() ;
		$array[] 	= $likeObj;
		self::$_likes[ $key ] = $array;
	}

	/**
	 * Removes a like data from the cache
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function removeLikeItem( $key, $userId )
	{
		$array 		= self::$_likes[ $key ];

		$new        = array();

		foreach( $array as $arr )
		{
			if( $arr->created_by != $userId )
			{
				$new[] = $arr;
			}
		}

		self::$_likes[ $key ] = $new;
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
	public function delete( $uid , $type )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->delete( '#__social_likes' );
		$sql->where( 'uid' , $uid );
		$sql->where( 'type' , $type );

		$db->setQuery( $sql );

		$db->Query();

		return true;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLikeStats( $dates , $userId )
	{
		$db 	= Foundry::db();
		$likes	= array();

		foreach( $dates as $date )
		{
			// Registration date should be Y, n, j
			$date	= Foundry::date( $date )->format( 'Y-m-d' );

			$query 		= array();
			$query[] 	= 'SELECT `a`.`id`, COUNT( `a`.`id`) AS `cnt` FROM `#__social_likes` AS a';
			$query[]	= 'WHERE `a`.`created_by`=' . $db->Quote( $userId );
			$query[]	= 'AND DATE_FORMAT( `a`.`created`, GET_FORMAT( DATE , "ISO") ) = ' . $db->Quote( $date );

			$query 		= implode( ' ' , $query );
			$sql		= $db->sql();
			$sql->raw( $query );

			$db->setQuery( $sql );

			$items				= $db->loadObjectList();

			// There is nothing on this date.
			if( !$items )
			{
				$likes[]	= 0;
				continue;
			}

			foreach( $items as $item )
			{
				$likes[]	= $item->cnt;
			}
		}

		// Reset the index.
		$likes 	= array_values( $likes );

		return $likes;
	}

	/**
	 * $uuid - the unique id of the liked item
	 * $uType - the item type being liked - stream type (status, groups, photos ), comment etc.
	 *
	 * return - int
	 */

	public function getLikesCount( $uuid, $uType )
	{
		//$likeCount = $this->_getLikesCount($uuid, $uType);

		$likes 		= $this->getLikeData( $uuid, $uType );
		$likeCount  = count( $likes );

		return ( empty( $likeCount ) ) ? 0 : $likeCount;
	}

	public function getLikesFromArray( $keys , $group = SOCIAL_APPS_GROUP_USER )
	{
		$db 		= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_likes' );
		$query[]	= 'WHERE';

		for( $i = 0; $i < count( $keys ); $i++ )
		{
			$key 		= $keys[ $i ];

			$query[]	= '(';
			$query[]	= $db->nameQuote( 'uid' ) . '=' . $db->Quote( $key->uid );
			$query[]	= 'AND';
			$query[]	= $db->nameQuote( 'type' ) . '=' . $db->Quote( $key->type . '.' . $group );
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

	private function getLikeData( $id , $type )
	{
		// Build the index for the like
		$key	= $id . '.' . $type;

		if( !isset( self::$_likes[ $key ] ) )
		{
			$db			= Foundry::db();
			$sql 		= $db->sql();

			$sql->select( '#__social_likes' );
			$sql->where( 'uid' , $id );
			$sql->where( 'type' , $type );

			$db->setQuery( $sql );

			$result 	= $db->loadObjectList();

			// Initialize the items at index
			self::$_likes[ $key ] = array();

			if( $result )
			{
				// Pre-load the users for the liked items
				foreach( $result as $row )
				{
					$like 		= Foundry::table( 'Likes' );
					$like->bind( $row );

					self::$_likes[ $key ][] = $like;
				}
			}
		}

		$result = self::$_likes[ $key ];
		$result = is_array( $result ) ? $result : array( $result );

		return $result;
	}



	/**
	 * Retrieves likes for a particular item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The unique type.group
	 * @return
	 */
	public function getLikes( $id , $type )
	{
		$likes = $this->getLikeData( $id, $type );
		return $likes;
	}

	/**
	 * Retrieves user ids who liked the item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The unique type.group
	 * @return  array   userid
	 */
	public function getLikerIds( $id , $type, $exclude = array() )
	{
		$likes = $this->getLikeData( $id, $type );

		$likers = array();
		if( $likes )
		{
			foreach( $likes as $like )
			{
				if( $exclude && !in_array( $like->created_by, $exclude ) )
				{
					$likers[] = $like->created_by;
				}
				else
				{
					$likers[] = $like->created_by;
				}
			}
		}

		return $likers;
	}

	/**
	 * Determines if a user has already liked an item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The unique type.
	 * @param	int		The user's id.
	 * @return
	 */
	public function hasLiked( $id , $type , $userId )
	{
		$likes = $this->getLikeData( $id, $type );

		if( $likes )
		{
			foreach( $likes as $like )
			{
				if( $like->created_by == $userId )
				{
					return true;
				}
			}
		}

		return false;

	}

	/**
	 * Adds the necessary data when a user likes an item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The unique type.
	 * @param	int		The user's id.
	 * @return	boolean	True if success, false otherwise.
	 */
	public function like( $id , $type , $userId )
	{
		$likes 	= Foundry::table( 'Likes' );

		$likes->uid 		= $id;
		$likes->type 		= $type;
		$likes->created_by	= $userId;

		$state 		= $likes->store();

		// If there's an error storing, log this down.
		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'LIKES: Unable to like item because of the error ' . $likes->getError() );

			// Set the error to the model.
			$this->setError( $table->getError() );
		}

		//update like static variable
		$key 		= $id . '.' . $type;
		$this->setLikeItem( $key, $likes );

		return $state;
	}

	/**
	 * Removes the necessary data when a user unlike an item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The unique type.
	 * @param	int		The user's id.
	 * @return	boolean	True if success, false otherwise.
	 */
	public function unlike( $id , $type , $userId )
	{
		$likes 	= Foundry::table( 'Likes' );

		// Test if this even exists
		$state 	= $likes->load( array( 'uid' => $id , 'type' => $type , 'created_by' => $userId ) );

		if( !$state )
		{
			return false;
		}

		$state 	= $likes->delete();

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'LIKES: Unable to delete item because of the error ' . $likes->getError() );

			// Set the error to the model.
			$this->setError( $table->getError() );
		}

		//update like static variable
		$key 		= $id . '.' . $type;
		$this->removeLikeItem( $key, $userId );

		return $state;
	}
}
