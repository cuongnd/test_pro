<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Jason Rey <jasonrey@stackideas.com>
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

class EasySocialModelComments extends EasySocialModel
{
	public $table 	= '#__social_comments';
	static $_counts = array();

	function __construct()
	{
		parent::__construct( 'comments' );
	}

	public function getComments( $options = array() )
	{
		// Available options
		// element
		// uid
		// start
		// limit
		// order
		// direction

		// Define the default parameters
		$defaults = array(
			'element'	=> '',
			'uid'		=> 0,
			'start'		=> 0,
			'limit'		=> 5,
			'order'		=> 'created',
			'direction'	=> 'asc',
			'commentid'	=> 0
		);

		$options	= array_merge($defaults, $options);

		foreach ($options as $key => $value)
		{
			if( !array_key_exists($key, $defaults) )
			{
				unset($options[$key]);
			}
		}

		$db		= Foundry::db();

		$sql	= $db->sql();

		// SELECT
		$sql->select( $this->table );

		// WHERE
		if( isset( $options['element'] ) )
		{
			$sql->where( 'element', $options['element'] );
		}

		if( isset( $options['uid'] ) )
		{
			$sql->where( 'uid', $options['uid'] );
		}

		if( isset( $options['commentid'] ) )
		{
			$sql->where( 'id', $options['commentid' ], '>=' );
		}

		// ORDER
		$sql->order( $options[ 'order' ] , $options[ 'direction' ] );

		// LIMIT
		if( !empty( $options['limit'] ) )
		{
			$sql->limit( $options[ 'start' ] , $options[ 'limit' ] );
		}

		$db->setQuery( $sql );

		$comments	= $db->loadObjectList();

		if( $comments === false )
		{
			Foundry::logError( __FILE__, __LINE__, 'Unable to get comments' );
			return false;
		}

		$tables		= array();
		foreach( $comments as $comment )
		{
			$table = Foundry::table( 'comments' );
			$table->bind( $comment );
			$tables[] = $table;
		}

		return $tables;
	}

	/**
	 * Retrieves the comment statistics for a particular poster
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of dates to search for
	 * @param	int		The user id to look up for
	 * @return
	 */
	public function getCommentStats( $dates , $userId )
	{
		$db 		= Foundry::db();
		$comments	= array();

		foreach( $dates as $date )
		{
			// Registration date should be Y, n, j
			$date	= Foundry::date( $date )->format( 'Y-m-d' );

			$query 		= array();
			$query[] 	= 'SELECT `a`.`id`, COUNT( `a`.`id`) AS `cnt` FROM `#__social_comments` AS a';
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
				$comments[]	= 0;
				continue;
			}

			foreach( $items as $item )
			{
				$comments[]	= $item->cnt;
			}
		}

		// Reset the index.
		$comments 	= array_values( $comments );

		return $comments;
	}

	public function getCommentCount( $options = array() )
	{
		$key = '';
		if( isset( $options['element'] ) && isset( $options['uid'] ) )
		{
			// lets try to get the count from the static variable.
			$key = $options['uid'] . '.' . $options['element'];
			if( isset( self::$_counts[ $key ] ) )
			{
				return self::$_counts[ $key ];
			}
		}

		$db		= Foundry::db();
		$sql	= $db->sql();

		// SELECT
		$sql->select( $this->table );

		// WHERE
		if( isset( $options['element'] ) )
		{
			$sql->where( 'element', $options['element'] );
		}

		if( isset( $options['uid'] ) )
		{
			$sql->where( 'uid', $options['uid'] );
		}

		$db->setQuery( $sql->getTotalSql() );

		$count = $db->loadResult();

		//lets save into static variable for later reference.
		if( $key )
		{
			self::$_counts[ $key ] = $count;
		}

		return $count;
	}

	public function deleteCommentBlock( $uid, $element )
	{
		$db		= Foundry::db();

		$sql	= $db->sql();

		$sql->delete( $this->table )
			->where( 'element', $element )
			->where( 'uid', $uid );

		$db->setQuery( $sql );
		return $db->query();
	}

	public function getParticipants( $uid, $element )
	{
		$db		= Foundry::db();

		$sql	= $db->sql();

		$sql->select( $this->table )
			->column( 'DISTINCT(`created_by`)' )
			->where( 'uid', $uid )
			->where( 'element', $element );

		$db->setQuery( $sql );

		$result = $db->loadColumn();

		return $result;
	}


	public function setStreamCommentCountBatch( $data )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		// Retrieve the stream model
		$model 	= Foundry::model( 'Stream' );

		$dataset = array();

		// Go through each of the items
		foreach( $data as $item )
		{
			// Get related items
			$related 	= $model->getBatchRalatedItem( $item->id );
			if( !$related )
			{
				continue;
			}


			// Get the element
			$element 	= $item->context_type;


			$streamItem = $related[ 0 ];
			$uid 		= $streamItem->context_id;

			if( $element == 'photos' && count( $related ) > 1 )
			{
				if( $streamItem->target_id )
				{
					$element	= 'albums';
					$uid 		= $streamItem->target_id;
				}
			}

			if( $element == 'story' || $element == 'links' )
			{
				$uid = $streamItem->uid;
			}

			// If there's no context_id, skip this.
			if( !$uid )
			{
				continue;
			}

			$key = $uid . '.' . $element . '.' . SOCIAL_APPS_GROUP_USER;

			if( ! isset( self::$_counts[ $key ] ) )
			{
				$dataset[ $element ][] = $uid;
			}
		}

		// lets build the sql now.
		if( $dataset )
		{

			$unionSQL = '';
			foreach( $dataset as $element => $uids )
			{
				$ids		= implode( ',', $uids );
				$element	= $element . '.' . SOCIAL_APPS_GROUP_USER;

				foreach( $uids as $uid )
				{
					$key = $uid . '.' . $element;
					self::$_counts[ $key ] = 0;
				}

				$query = 'select `id`,`element`,`uid` from `#__social_comments`';
				$query .= ' where `element` = ' . $db->Quote( $element );
				$query .= ' and `uid` IN (' . $ids . ')';

				$unionSQL .= ( empty( $unionSQL ) ) ? $query : ' UNION ' . $query;

			}

			$query = 'select count(1) as `cnt`, x.`uid`, x.`element` from (' . $unionSQL . ') as x group by x.`element`, x.`uid`';

			$sql->raw( $query );
			$db->setQuery( $sql );

			$result = $db->loadObjectList();

			if( $result )
			{
				foreach( $result as $rItem )
				{
					$key = $rItem->uid . '.' . $rItem->element;
					self::$_counts[ $key ] = $rItem->cnt;
				}
			}

		}


	}


}
