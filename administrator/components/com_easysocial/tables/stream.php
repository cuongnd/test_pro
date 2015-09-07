<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( 'JPATH_BASE' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/tables/table' );

class SocialTableStream extends SocialTable
{
	public $id				= null;
	public $actor_id		= null;
	public $actor_type		= null;
	public $alias			= null;
	public $created			= null;
	public $modified		= null;
	public $title         	= null;
	public $content         = null;
	public $sitewide		= null;
	public $target_id 		= null;
	public $context_type 	= null;
	public $stream_type 	= null;
	public $with 			= null;
	public $location_id 	= null;
	public $ispublic 		= null;
	public $params 			= null;

	static $_streams 		= array();

	public function __construct( $db )
	{
		parent::__construct('#__social_stream', 'id', $db);
	}


	/**
	 * Overrides parent's load implementation
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function load( $keys = null, $reset = true )
	{
		if( is_array( $keys ) )
		{
			return parent::load( $keys, $reset );
		}

		if(! isset( self::$_streams[ $keys ] ) )
		{
			$state = parent::load( $keys );
			self::$_streams[ $keys ] = $this;
			return $state;
		}

		return parent::bind( self::$_streams[ $keys ] );
	}

	public function loadByBatch( $ids )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$streamIds = array();

		foreach( $ids as $pid )
		{
			if(! isset( self::$_streams[$pid] ) )
			{
				$streamIds[] = $pid;
			}
		}

		if( $streamIds )
		{
			foreach( $streamIds as $pid )
			{
				self::$_streams[$pid] = false;
			}

			$query = '';
			$idSegments = array_chunk( $streamIds, 5 );
			//$idSegments = array_chunk( $streamIds, count($streamIds) );

			for( $i = 0; $i < count( $idSegments ); $i++ )
			{
				$segment    = $idSegments[$i];

				$ids = implode( ',', $segment );
				$query .= 'select * from `#__social_stream` where `id` IN ( ' . $ids . ')';

				if( ($i + 1)  < count( $idSegments ) )
				{
					$query .= ' UNION ';
				}

			}


			$sql->raw( $query );
			$db->setQuery( $sql );

			$results = $db->loadObjectList();

			if( $results )
			{
				foreach( $results as $row )
				{
					self::$_streams[$row->id] = $row;
				}
			}
		}

	}

	public function store( $updateNulls = false )
	{
		if( is_null( $this->modified) )
		{
			$date 			= Foundry::date();
			$this->modified	= $date->toMySQL();
		}
		return parent::store();
	}

	public function toJSON()
	{
		return array('id' => $this->id ,
					 'actor_id' => $this->actor_id ,
					 'actor_type' => $this->actor_type,
					 'alias' => $this->alias,
					 'created' => $this->created,
					 'modified' => $this->modified,
					 'title' => $this->title,
					 'content' => $this->content,
					 'sitewide' => $this->sitewide,
					 'target_id' => $this->target_id,
					 'location_id' => $htis->location_id,
					 'ispublic'	=> $this->ispublic,
					 'params'	=> $this->params
		 );
	}

	/**
	 * Get the uid association to this stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUID()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();
		$sql->select( '#__social_stream_item' , 'a' );
		$sql->column( 'a.id' );
		$sql->where( 'a.uid' , $this->id );

		$db->setQuery( $sql );

		$id 	= $db->loadResult();

		return $id;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function loadByUID( $uid )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();
		$sql->select( '#__social_stream' , 'a' );
		$sql->column( 'a.*' );
		$sql->join( '#__social_stream_item' , 'b' );
		$sql->on( 'b.uid' , 'a.id' );
		$sql->where( 'b.id' , $uid );

		$db->setQuery( $sql );

		$obj	= $db->loadObject();

		return parent::bind( $obj );
	}

	/**
	 * Returns the stream's permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Determines if the output should be xhtml encoded.
	 * @return	string	The url
	 */
	public function getPermalink( $xhtml = true )
	{
		return FRoute::stream( array( 'id' => $this->id , 'layout' => 'item' ) , $xhtml );
	}

	/**
	 * Checks if the provided user is allowed to hide this item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function hideable( $id = null )
	{
		$isOwner 	= $this->isOwner( $id );
		$isAdmin	= $this->isAdmin( $id );

		if( $isOwner || $isAdmin )
		{
			return true;
		}

		return false;
	}


	/**
	 * Checks if the provided user is the owner of this item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isAdmin( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $user->isSiteAdmin() )
		{
			return true;
		}

		return false;
	}

	/**
	 * Checks if the provided user is the owner of this item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isOwner( $id = null )
	{
		$user 	= Foundry::user( $id );

		if( $this->actor_id == $user->id )
		{
			return true;
		}

		return false;
	}

	/**
	 * delete this stream and its associated stream_items
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */

	public function delete( $pk = null )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$query = 'delete from `#__social_stream_item` where `uid` = ' . $db->Quote( $this->id );
		$sql->raw( $query );

		$db->setQuery( $sql );
		$db->query();

		return parent::delete();
	}



}
