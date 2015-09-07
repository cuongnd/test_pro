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

// Include main model file.
Foundry::import( 'admin:/includes/model' );

class EasySocialModelStream extends EasySocialModel
{
	private $data			= null;
	private $nextdate		= null;
	private $enddate 		= null;
	private $paginationdate = null;

	//used in queries optmisation.
	static $_relateditems 	= array();
	static $_activitylogs 	= array();
	static $_tagging 		= array();


	function __construct()
	{
		parent::__construct( 'stream' );
	}

	public function hide($ids, $userId)
	{
		if( empty($ids) )
			return false;

		if(! is_array($ids) )
		{
			$ids = array( $ids );
		}


		$db = Foundry::db();

		foreach($ids as $cid)
		{
			$tbl = Foundry::table('StreamHide');
			$tbl->user_id 	= $userId;
			$tbl->uid 		= $cid;
			$tbl->type 		= SOCIAL_STREAM_HIDE_TYPE_STREAM;


			if( ! $tbl->store() )
			{
				return false;
			}
			else
			{
				//since this stream might consist of several activity logs, then we will need to 'hide' them all as well.
				$query = 'select ' . $db->nameQuote( 'id' ) . ' from ' . $db->nameQuote( '#__social_stream_item' ) . ' where ' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $cid );
				$db->setQuery( $query );

				$items = $db->loadObjectList();

				if( count( $items ) > 0 )
				{
					foreach( $items as $item )
					{
						$tbl = Foundry::table( 'StreamHide' );
						$tbl->uid 		= $item->id;
						$tbl->user_id 	= $userId;
						$tbl->type 		= SOCIAL_STREAM_HIDE_TYPE_ACTIVITY;
						$tbl->store();
					}

				}
			}
		}

		return true;
	}

	public function hideapp( $context, $userId)
	{
		if( empty($context) )
			return false;


		$db = Foundry::db();


		$tbl = Foundry::table('StreamHide');
		$tbl->user_id 	= $userId;
		$tbl->uid 		= '0';
		$tbl->type 		= '';
		$tbl->context 	= $context;

		if( ! $tbl->store() )
		{
			return false;
		}

		return true;
	}

	/**
	 * Retrieves the past 7 days statistics for all postings by specific user
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Array
	 */
	public function getPostStats( $dates , $userId )
	{
		$db 	= Foundry::db();
		$posts	= array();

		foreach( $dates as $date )
		{
			// Registration date should be Y, n, j
			$date	= Foundry::date( $date )->format( 'Y-m-d' );

			$query 		= array();
			$query[] 	= 'SELECT `a`.`id`, COUNT( `a`.`id`) AS `cnt` FROM `#__social_stream` AS a';
			$query[]	= 'WHERE `a`.`actor_id`=' . $db->Quote( $userId );
			$query[]	= 'AND `a`.`actor_type`=' . $db->Quote( SOCIAL_TYPE_USER );
			$query[]	= 'AND DATE_FORMAT( `a`.`created`, GET_FORMAT( DATE , "ISO") ) = ' . $db->Quote( $date );

			$query 		= implode( ' ' , $query );
			$sql		= $db->sql();
			$sql->raw( $query );

			$db->setQuery( $sql );

			$items				= $db->loadObjectList();

			// There is nothing on this date.
			if( !$items )
			{
				$posts[]	= 0;
				continue;
			}

			foreach( $items as $item )
			{
				$posts[]	= $item->cnt;
			}
		}

		// Reset the index.
		$posts 	= array_values( $posts );

		return $posts;
	}

	public function unhideapp( $context, $userId )
	{
		if( empty( $context ) )
			return false;


		$db = Foundry::db();

		$delQuery = 'delete from ' . $db->nameQuote( '#__social_stream_hide' ) . ' where ' . $db->nameQuote( 'context' ) . ' = ' . $db->Quote( $context );
		$delQuery .= ' and ' . $db->nameQuote( 'user_id' ) . ' = ' . $db->Quote( $userId );

		$db->setQuery( $delQuery );
		$db->query();

		return true;
	}

	/**
	 * Deletes stream items given the context type and context id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 		The unique context id.
	 * @param	string		The unique context type.
	 * @return	boolean
	 */
	public function delete( $contextId , $contextType , $actorId = '' )
	{
		$db		= Foundry::db();

		// Get a list of items from the item table first.
		$sql	= $db->sql();

		$sql->select( '#__social_stream_item' );
		$sql->where( 'context_id' , $contextId );
		$sql->where( 'context_type', $contextType );

		if( $actorId )
		{
			$sql->where( 'actor_id' , $actorId );
		}

		$db->setQuery( $sql );

		$items 		= $db->loadObjectList();

		if( !$items )
		{
			$this->setError( JText::sprintf( 'There is no items matching the context type of %1s and context id of %2s.' , $contextType , $contextId ) );
			return false;
		}


		// Delete from #__social_stream_item
		$sql->clear();
		$sql->delete( '#__social_stream_item' );
		$sql->where( 'context_id' , $contextId );
		$sql->where( 'context_type' , $contextType );

		if( $actorId )
		{
			$sql->where( 'actor_id' , $actorId );
		}

		$db->setQuery( $sql );
		$db->Query();

		// lets check if the UID has more than one item or not. If yes, then we shouldn't
		// delete the master record.

		foreach( $items as $item )
		{
			// Delete from #__social_stream
			$sql->clear();

			$sql->select( '#__social_stream_item' );
			$sql->column( 'count(1)', 'cnt');
			$sql->where( 'uid' , $item->uid );

			$db->setQuery( $sql );
			$cnt = $db->loadResult();

			if( $cnt <= 0 )
			{
				$sql->clear();
				$sql->delete( '#__social_stream' );
				$sql->where( 'id' , $item->uid );

				$db->setQuery( $sql );
				$db->Query();
			}
		}

		return true;
	}

	public function unhide($ids, $userId)
	{
		if( empty($ids) )
			return false;

		if(! is_array($ids) )
		{
			$ids = array( $ids );
		}


		$db = Foundry::db();

		foreach($ids as $cid)
		{
			$delQuery = 'delete from ' . $db->nameQuote( '#__social_stream_hide' ) . ' where ' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $cid );
			$delQuery .= ' and ' . $db->nameQuote( 'user_id' ) . ' = ' . $db->Quote( $userId );
			$delQuery .= ' and ' . $db->nameQuote( 'type' ) . ' = ' . $db->Quote( SOCIAL_STREAM_HIDE_TYPE_STREAM );

			$db->setQuery( $delQuery );
			$db->query();


			//since this stream might consist of several activity logs, then we will need to 'hide' them all as well.
			$query = 'select ' . $db->nameQuote( 'id' ) . ' from ' . $db->nameQuote( '#__social_stream_item' ) . ' where ' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $cid );
			$db->setQuery( $query );

			$items = $db->loadObjectList();

			if( count( $items ) > 0 )
			{
				$itemIds = array();
				foreach( $items as $item )
				{
					$itemIds[] = $item->id;
				}

				$strIds = implode( ',', $itemIds);

				$delQuery = 'delete from ' . $db->nameQuote( '#__social_stream_hide' ) . ' where ' . $db->nameQuote( 'uid' ) . ' IN (' . $strIds . ')';
				$delQuery .= ' and ' . $db->nameQuote( 'user_id' ) . ' = ' . $db->Quote( $userId );
				$delQuery .= ' and ' . $db->nameQuote( 'type' ) . ' = ' . $db->Quote( SOCIAL_STREAM_HIDE_TYPE_ACTIVITY );

				$db->setQuery( $delQuery );
				$db->query();

			}
		}

		return true;
	}

	public function getItems()
	{
		$search 		= Foundry::get( 'Themes' )->getUserStateFromRequest( 'com_easysocal.stream.search', 'search', '', 'string' );
		$actor_type 	= Foundry::get( 'Themes' )->getUserStateFromRequest( 'com_easysocal.stream.actor_type', 'actor_type', '', 'string' );
		$context_type 	= Foundry::get( 'Themes' )->getUserStateFromRequest( 'com_easysocal.stream.context_type', 'context_type', '', 'string' );

		$db = Foundry::db();
		$where  = array();
		//if( !empty( $search ) )
		//	$where[]    = ' a.`actor_id` = ' . $this->_db->Quote( $search );

		if( !empty($actor_type) )
			$where[]    = 'a.' . $db->nameQuote( 'actor_type' ) . ' = ' . $db->Quote( $actor_type );

		if( !empty($context_type) )
			$where[]    = 'a.' . $db->nameQuote( 'context_type' ) . ' = ' . $db->Quote( $context_type );

		$extra 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		$CountHeader  = 'select count(1)';

	    $header  = 'select a.*, b.' . $db->nameQuote( 'name' ) . ' as ' . $db->nameQuote( 'actor_name' ) . ',';
	    $header  .= ' FLOOR( TIME_TO_SEC( TIMEDIFF( NOW(), a.' . $db->nameQuote( 'created' ) . ' ) ) / 60) AS ' . $db->nameQuote( 'mindiff' ) . ',';
	    $header  .= ' FLOOR( TIME_TO_SEC( TIMEDIFF( NOW(), a.' . $db->nameQuote( 'created' ) . ' ) ) / 60 / 60) AS ' . $db->nameQuote( 'hourdiff' ) . ',';
		$header  .= ' FLOOR( TIME_TO_SEC( TIMEDIFF( NOW(), a.' . $db->nameQuote( 'created' ) . ' ) ) / 60 / 60 / 24) AS ' . $db->nameQuote( 'daydiff' );

	    $query  = ' from ' . $db->nameQuote( '#__social_stream' ) . ' as a';
	    $query  .= '   left join ' . $db->nameQuote( '#__users' ) . ' as b on a.' . $db->nameQuote( 'source_id' ) . ' = b.' . $db->nameQuote( 'id' ) . ' and a.' . $db->nameQuote( 'actor_type' ) . ' = ' . $db->Quote( 'people' );
	    $query  .= $extra;
	    $query  .= ' order by a.' . $db->nameQuote( 'created desc' );

	    $mainSQL    = $header   . $query;

	    $countSQL   = $CountHeader . $query;
		$this->setTotal( $countSQL );

		// echo $mainSQL;

		return $this->getStreamData( $mainSQL );
	}

	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination( $this->total , $this->getState('limitstart') , $this->getState('limit') );
		}

		return $this->pagination;
	}

	public function getType( $type )
	{
		$db = Foundry::db();
	    $targetType = '';

		switch( $type )
		{
		    case 'source':
		        $targetType = 'actor_type';
				break;
			case 'context':
		        $targetType = 'context_type';
				break;
			default:
			    break;
		}

	    if( empty( $targetType ) )
	        return;

		$query  = 'SELECT DISTINCT ' . $db->nameQuote( $targetType );
		$query  .= ' FROM  ' . $db->nameQuote( '#__social_stream' );

		return $this->getStreamData( $query, false );
	}


	public function getNextEndDate()
	{
		return $this->enddate;
	}

	public function getNextStartDate()
	{
		return $this->nextdate;
	}


	public function getCurrentStartDate()
	{
		if( empty( $this->startdate ) )
		{
			//use the current datetime
			return Foundry::date()->toMySQL();
		}

		return $this->startdate;
	}



	/**
	 * Retrive the start date and end date used in query limit.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string - start date ( in mysql date format )
	 * @return	array - startdate and enddate
	 */

	private function getLimitDates( $startdate, $enddate, $tables, $conds = array(), $direction = 'older' )
	{
		$config = Foundry::config();
		$db 	= Foundry::db();

		$fetchLimit = $config->get( 'stream.pagination.limit' );

		// $fetchLimit = 10080 + 10080;
		// $fetchLimit = 5;

		$dates      = array();
		$countConds = $conds;

		if( !$startdate )
		{
			//use last modified date from stream.
			$query = 'SELECT MAX( a.' . $db->nameQuote( 'modified' ) . ') AS ' . $db->nameQuote('startdate') . ',';
			$query .= ' DATE_ADD( MAX( a.' . $db->nameQuote( 'modified' ) . ' )  , INTERVAL -' . $fetchLimit . ' MINUTE) AS ' . $db->nameQuote( 'enddate' ) . ' ';
		}
		else
		{
			$query = 'SELECT MAX( a.' . $db->nameQuote( 'modified' ) . ') AS ' . $db->nameQuote( 'startdate' ) . ',';
			$query .= ' DATE_ADD( MAX( a.' . $db->nameQuote( 'modified' ) . ') , INTERVAL -' . $fetchLimit . ' MINUTE) AS ' . $db->nameQuote( 'enddate' ) . ' ';

			if( $direction == 'later' )
			{
				$conds[] = ' and a.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
			}
			else
			{
				$conds[] = ' and a.' . $db->nameQuote( 'modified' ) . ' < ' . $db->Quote( $startdate );
			}
		}

		$tables 	= implode( ' ', $tables );
		$conds 		= implode( ' ' , $conds );

		$query .= $tables . ' ' . $conds;

		// echo $query . '<br /><br />';
		// exit;

		$db->setQuery( $query );
		$data = $db->loadObject();

		if( isset( $data->startdate ) )
		{
			$dates['startdate'] 		= $data->startdate;
			$dates['paginationdate'] 	= $data->startdate;
		}

		if( $enddate )
		{
			$dates['enddate'] = $enddate;
		}
		else
		{
			if( isset( $data->enddate ) )
			{
				$dates['enddate'] = $data->enddate;
			}
		}

		// now lets test whether the next set of dates has data or not.
		if( isset( $data->enddate ) && $direction != 'later' )
		{

			$query = 'select count(1) as CNT';
			$countConds[] = ' and a.' . $db->nameQuote( 'modified' ) . ' < ' . $db->Quote( $data->enddate );

			// joining the condition
			$countConds = implode( ' ' , $countConds );

			$query .= $tables . ' ' . $countConds;

			// echo $query;

			$db->setQuery( $query );
			$data = $db->loadResult();
			if( empty( $data ) )
			{
				$dates['paginationdate'] = false;
			}
		}

		return $dates;
	}


	// called in backend dashboard.
	public function getRecentFeeds( $maxCnt = 10 )
	{
		$db = Foundry::db();

		if( empty( $maxCnt ) )
			$maxCnt = 10;

		$query	= 'SELECT a.*';
		$query	.= ', FLOOR( ( UNIX_TIMESTAMP( now() ) - UNIX_TIMESTAMP( a.' . $db->nameQuote( 'modified' ) . ') ) / 60 ) AS ' . $db->nameQuote( 'min' );
		$query	.= ', FLOOR( ( UNIX_TIMESTAMP( now() ) - UNIX_TIMESTAMP( a.' . $db->nameQuote( 'modified' ) . ') ) / 60 / 60 ) AS ' . $db->nameQuote( 'hour' );
		$query	.= ', FLOOR( ( UNIX_TIMESTAMP( now() ) - UNIX_TIMESTAMP( a.' . $db->nameQuote( 'modified' ) . ') ) / 60 / 60 / 24 ) AS ' . $db->nameQuote( 'day' );
		$query	.= ' FROM ' . $db->nameQuote( '#__social_stream' ) . ' AS a';
		$query	.= ' ORDER BY a.' . $db->nameQuote( 'modified' ) . ' DESC';
		$query  .= ' LIMIT ' . $maxCnt;

		$db->setQuery( $query );

		$result 	= $db->loadObjectList();
		return $result;
	}


	private function getStreamTableAlias( $userId, $type, $useDate = false, $direction = '', $startdate = null, $enddate = null )
	{
		$db 	= Foundry::db();
		$view 	= JRequest::getVar( 'view', '');

		$streamTableAlias = '(';
		$streamTableAlias .= 'select a1.* from ' . $db->nameQuote( '#__social_stream' ) . ' as a1 where ' . $db->nameQuote( 'actor_type' ) . ' = ' . $db->Quote( $type ) . ' and ' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $userId );
		if( $useDate )
		{
			// data fetch limit
			// startdate holding the larger date
			// enddte holding the smaller date.

			if( $direction == 'later' )
			{
				$streamTableAlias .=	' AND a1.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
			}
			else
			{
				$streamTableAlias .=	' AND ( a1.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $startdate ) . ' AND a1.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $enddate ) . ')';
			}
		}

		$streamTableAlias .= ' UNION ';

		// tagged item
		$streamTableAlias .= 'select a2.* from ' . $db->nameQuote( '#__social_stream' ) . ' as a2 ';
		$streamTableAlias .= ' inner join ' . $db->nameQuote( '#__social_stream_item' ) . ' as ai2 on a2.' . $db->nameQuote( 'id' ) . ' = ai2.' . $db->nameQuote( 'uid' );
		$streamTableAlias .= ' where a2.' . $db->nameQuote( 'actor_type' ) . ' = ' . $db->Quote( $type );
		$streamTableAlias .= ' and a2.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $userId ) . ' and a2.' . $db->nameQuote( 'context_type' ) . ' IN (' . $db->Quote( 'friends' ) . ',' . $db->Quote( 'story' ) . ', ' . $db->Quote( 'photos' ) . ',' . $db->Quote( 'links' ) . ')';
		// $streamTableAlias .= ' and ai2.' . $db->nameQuote( 'verb' ) . ' IN (' . $db->Quote( 'add' ) . ',' . $db->Quote( 'create' ) . ',' . $db->Quote( 'share' ) .')';

		$streamTableAlias .= ' and (';
		$streamTableAlias .= '	  ( ai2.' . $db->nameQuote( 'context_type' ) . ' = ' . $db->Quote( 'friends' ) . ' and ai2.' . $db->nameQuote( 'verb' ) . ' = ' . $db->Quote( 'add' ) . ' ) or ';
		$streamTableAlias .= ' 	  ( ai2.' . $db->nameQuote( 'context_type' ) . ' = ' . $db->Quote( 'story' ) .' and ai2.' . $db->nameQuote( 'verb' ) . ' = ' . $db->Quote( 'create' ) . ' ) or ';
		$streamTableAlias .= '	  ( ai2.' . $db->nameQuote( 'context_type' ) . ' = ' . $db->Quote( 'photos' ) .' and ai2.' . $db->nameQuote( 'verb' ) . ' = ' . $db->Quote( 'share' ) . ' ) or ';
		$streamTableAlias .= '	  ( ai2.' . $db->nameQuote( 'context_type' ) . ' = ' . $db->Quote( 'links' ) .' and ai2.' . $db->nameQuote( 'verb' ) . ' = ' . $db->Quote( 'create' ) . ' ) ';
		$streamTableAlias .= '	)';

		if( $useDate )
		{
			if( $direction == 'later' )
			{
				$streamTableAlias .=	' AND a2.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
			}
			else
			{
				$streamTableAlias .=	' AND ( a2.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $startdate ) . ' AND a2.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $enddate ) . ')';
			}
		}

		if( $view == 'dashboard' )
		{
			// $streamTableAlias .= ' UNION ';
			// site wide items
			// $streamTableAlias .= 'select a3.* from ' . $db->nameQuote( '#__social_stream' ) . ' as a3 where ' . $db->nameQuote( 'actor_type' ) . ' = ' . $db->Quote( $type ) . ' and ' . $db->nameQuote( 'sitewide' ) . ' = ' . $db->Quote( '1' );
			// if( $useDate )
			// {
			// 	if( $direction == 'later' )
			// 	{
			// 		$streamTableAlias .=	' AND a3.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
			// 	}
			// 	else
			// 	{
			// 		$streamTableAlias .=	' AND ( a3.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $startdate ) . ' AND a3.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $enddate ) . ')';
			// 	}
			// }

			$streamTableAlias .= ' UNION ';
			// friends item
			$streamTableAlias .= 'select a4.* from ' . $db->nameQuote( '#__social_stream' ) . ' as a4 INNER JOIN ' . $db->nameQuote( '#__social_friends' ) . ' AS f1 ON a4.' . $db->nameQuote( 'actor_id' ) . ' = f1.' . $db->nameQuote( 'target_id' ) . ' and f1.' . $db->nameQuote( 'actor_id' ) . ' =  ' . $db->Quote( $userId ) . ' and f1.' . $db->nameQuote( 'state') . ' = ' . $db->Quote('1');
			if( $useDate )
			{
				if( $direction == 'later' )
				{
					$streamTableAlias .=	' AND a4.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
				}
				else
				{
					$streamTableAlias .=	' AND ( a4.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $startdate ) . ' AND a4.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $enddate ) . ')';
				}
			}

			$streamTableAlias .= ' UNION ';
			$streamTableAlias .= 'select a5.* from ' . $db->nameQuote( '#__social_stream' ) . ' as a5 INNER JOIN ' . $db->nameQuote( '#__social_friends' ) . ' AS f2 ON a5.' . $db->nameQuote( 'actor_id' ) . ' = f2.' . $db->nameQuote( 'actor_id' ) . ' and f2.' . $db->nameQuote( 'target_id' ) . ' =  ' . $db->Quote( $userId ) . ' and f2.' . $db->nameQuote( 'state') . ' = ' . $db->Quote('1');
			if( $useDate )
			{
				if( $direction == 'later' )
				{
					$streamTableAlias .=	' AND a5.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
				}
				else
				{
					$streamTableAlias .=	' AND ( a5.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $startdate ) . ' AND a5.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $enddate ) . ')';
				}
			}
		}

		$streamTableAlias .= ' UNION ';
		$streamTableAlias .= 'select a6.* from ' . $db->nameQuote( '#__social_stream' ) . ' as a6 inner join ' . $db->nameQuote( '#__social_stream_tags' ) . ' as st on a6.' . $db->nameQuote( 'id' ) . ' = st.' . $db->nameQuote( 'stream_id' ) . ' where st.' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $userId ) . ' and st.' . $db->nameQuote( 'utype' ) . ' = ' . $db->Quote( SOCIAL_STREAM_TAGGING_TYPE_USER );
		if( $useDate )
		{
			if( $direction == 'later' )
			{
				$streamTableAlias .=	' AND a6.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $startdate );
			}
			else
			{
				$streamTableAlias .=	' AND ( a6.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $startdate ) . ' AND a6.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $enddate ) . ')';
			}
		}

		$streamTableAlias .= ') as a';

		// echo $streamTableAlias;

		return $streamTableAlias;
	}

	public function generateSampleData()
	{
		$db = Foundry::db();

		$userIds 		= array( '44', '45', '46', '84', '85', '86', '87' );
		$photoIds       = array('398','397','396', '395', '394', '393', '392', '391', '390', '389',
								'388', '387', '386', '385', '384', '383', '382', '381', '380', '379', '378',
								'377', '376', '375', '374', '373', '372', '371', '370', '369', '368', '367',
								'366', '365', '364', '363', '362', '361', '360', '359', '358', '357', '356', '355', '354');


		set_time_limit( 1800 );

		for($j = 0; $j < 10; $j++)
		{
			for($i = 0; $i < 35; $i++)
			{

				$randomActor 	= $userIds[ array_rand( $userIds ) ];
				$randomTarget 	= $userIds[ array_rand( $userIds ) ];
				$randomPhoto 	= $photoIds[ array_rand( $photoIds) ];

				$date = Foundry::date();

				$sql = "INSERT INTO `jos_social_stream` (`actor_id`, `alias`, `actor_type`, `created`, `modified`, `title`, `content`, `context_type`, `stream_type`, `sitewide`, `target_id`, `location_id`)";
				$sql .= " VALUES ";
				$sql .= " ( " . $randomActor . ", '', 'user', '" . $date->toMySQL() . "', '" . $date->toMySQL() . "', NULL, 'hello sammy boy', 'photos', NULL, 0, " . $randomTarget . ", 0)";

				// echo $sql;

				$db->setQuery( $sql );
				$db->query();
				$myUID = $db->insertid();

				$sql = "INSERT INTO `jos_social_stream_item` (`actor_id`, `actor_type`, `context_type`, `context_id`, `verb`, `target_id`, `created`, `uid`, `sitewide`)";
				$sql .= " VALUES ";
				$sql .= " ( " . $randomActor . ", 'user', 'photos', " . $randomPhoto . ", 'share', " . $randomTarget . ", '" . $date->toMySQL() . "', " . $myUID . ", 0)";

				// echo '<br><br>';
				// echo $sql;

				$db->setQuery( $sql );
				$db->query();
			}
			echo '.';
			sleep( 75 );
		}

		echo 'done';
		exit;


	}

	/**
	 * Retrieves the stream data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getStreamData( $config = array() )
	{

		$db 		= Foundry::db();
		$sysconfig 	= Foundry::config();

		$hardLimit	= SOCIAL_STREAM_HARD_LIMIT;

		// debug:
		//$this->generateSampleData();


		// If a context is given.
		$context 	= isset( $config[ 'context' ] ) ? $config[ 'context' ] : false;
		$userid 	= isset( $config[ 'userid' ] ) ? $config[ 'userid' ] : false;
		$listid 	= isset( $config[ 'list' ] ) ? $config[ 'list' ] : false;

		$uid 		= isset( $config[ 'uid' ] ) ? $config[ 'uid' ] : false;

		$type 		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : SOCIAL_TYPE_USER;
		$viewer 	= isset( $config[ 'viewer' ] ) ? $config[ 'viewer' ] : false;
		$limitstart = isset( $config[ 'limitstart' ] ) ? $config[ 'limitstart' ] : false;
		$limitend 	= isset( $config[ 'limitend' ] ) ? $config[ 'limitend' ] : false;

		$isFollow 	= isset( $config[ 'isfollow' ] ) ? $config[ 'isfollow' ] : false;

		$streamId	= isset( $config[ 'streamId' ] ) ? $config[ 'streamId' ] : false;

		$direction	= isset( $config[ 'direction' ] ) ? $config[ 'direction' ] : 'older';

		$ignoreUser = isset( $config[ 'ignoreUser' ] ) ? $config[ 'ignoreUser' ] : false ;

		// pagination for public stream
		$limit 		= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : false ;
		$startlimit = isset( $config[ 'startlimit' ] ) ? $config[ 'startlimit' ] : '0' ;
		$guest 		= isset( $config[ 'guest' ] ) ? $config[ 'guest' ] : false ;


		$query 		= array();
		$table 		= array();
		$cond 		= array();
		$order 		= array();

		$view = JRequest::getVar( 'view', '');

		$streamTableAlias = $db->nameQuote( '#__social_stream' ) . ' AS a';

		if( empty( $listid ) && empty( $isFollow ) && !$streamId && !$guest )
		{
			$streamTableAlias = $this->getStreamTableAlias( $userid[ 0 ], $type );
		}

		$query[]	= 'SELECT a.*';
		$query[]	= ',FLOOR( ( UNIX_TIMESTAMP( now() ) - UNIX_TIMESTAMP( a.' . $db->nameQuote( 'modified' ) . ') ) / 60 ) AS ' . $db->nameQuote( 'min' );
		$query[]	= ',FLOOR( ( UNIX_TIMESTAMP( now() ) - UNIX_TIMESTAMP( a.' . $db->nameQuote( 'modified' ) . ') ) / 60 / 60 ) AS ' . $db->nameQuote( 'hour' );
		$query[]	= ',FLOOR( ( UNIX_TIMESTAMP( now() ) - UNIX_TIMESTAMP( a.' . $db->nameQuote( 'modified' ) . ') ) / 60 / 60 / 24 ) AS ' . $db->nameQuote( 'day' );

		$table[] = ' FROM ' . $streamTableAlias;

		if( !$ignoreUser )
		{
			$table[] = 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS uu ON a.' . $db->nameQuote( 'actor_id' ) . ' = uu.' . $db->nameQuote( 'id' ) . ' AND uu.' . $db->nameQuote( 'block' ) . ' = 0' ;
		}

		if( $isFollow )
		{
			$table[] = 'INNER JOIN ' . $db->nameQuote( '#__social_subscriptions' ) . ' AS s';
			$table[] = 'ON a.' . $db->nameQuote( 'actor_id' ) . ' = s.' . $db->nameQuote( 'uid' );
		}

		if( !empty( $listid ) )
		{
			$table[] = 'INNER JOIN ' . $db->nameQuote( '#__social_lists_maps' ) . ' AS lm';
			$table[] = 'ON a.' . $db->nameQuote( 'actor_id' ) . ' = lm.' . $db->nameQuote( 'target_id' ) . ' AND lm.' . $db->nameQuote( 'list_id' ) . ' = ' . $db->Quote( $listid ) . ' and lm.' . $db->nameQuote( 'target_type' ) . ' = ' .$db->Quote( 'user' );
		}

		$isSingleItem = false;

		if( $streamId )
		{
			$cond[]		= 'WHERE a.' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $streamId );
			$isSingleItem 	= true;
		}
		else
		{
			if( ! $guest )
			{
				if( $context && $context !== false && $context !== 'all' && !empty( $uid ) )
				{
					// filtering based on a particular stream.
					$cond[]	= 'WHERE a.' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $uid );
					$isSingleItem = true;
				}
				else
				{
					$cond[]	= 'WHERE a.' . $db->nameQuote( 'actor_type' ) . '=' . $db->Quote( $type );

					if( $context != 'all' )
					{
						// context used to filter the apps.
						$cond[]	= 'AND a.' . $db->nameQuote( 'context_type' ) . '=' . $db->Quote( $context );
					}
				}

			}
			else
			{
				// get public stream
				$my = Foundry::user();

				if( $my->id == 0)
				{
					$cond[]	= 'WHERE a.' . $db->nameQuote( 'ispublic' ) . '=' . $db->Quote( '1' );
				}
			}

			if( $isFollow )
			{
				$cond[]	= 'AND s.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( SOCIAL_TYPE_USER . '.' . SOCIAL_SUBSCRIPTION_TYPE_USER );
				$cond[]	= 'AND s.' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $userid[ 0 ] );
			}


			if( $viewer )
			{
				// based on stream item.
				$cond[]	= 'AND a.' . $db->nameQuote( 'id' ) . ' NOT IN (';
				$cond[]	= 'SELECT h.' . $db->nameQuote( 'uid' ) . ' FROM ' . $db->nameQuote( '#__social_stream_hide' ) . ' AS h';
				$cond[]	= 'WHERE h.' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $viewer );
				$cond[]	= 'AND h.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'stream' );
				$cond[]	= ')';

				//based on context
				$cond[] = ' and a.' . $db->nameQuote( 'context_type' ) . ' NOT IN (';
				$cond[] = ' 	SELECT h1.' . $db->nameQuote( 'context' ) . ' FROM ' . $db->nameQuote( '#__social_stream_hide' ) . ' AS h1';
				$cond[] = 'WHERE h1.' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $viewer ) ;
				$cond[] = 'AND h1.' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( '0' );
				$cond[] = ')';

			}
		}

		// lets get the limit dates here instead
		$limitDates = array();

		if(! $isSingleItem && !$limit )
		{

			$limitDates = $this->getLimitDates( $limitstart, $limitend, $table, $cond, $direction );

			if( !isset( $limitDates['startdate'] ) || !isset( $limitDates['enddate'] ) )
			{
				// no dates found. this mean no data in stream table. return empty array.
				return array();
			}

			// setting next date.
			$this->nextdate 	= $limitDates['enddate'];

			// start date
			// $this->startdate 	= $limitDates['startdate'];

			// pagination date
			$this->paginationdate 	= ( isset( $limitDates['paginationdate'] ) ) ? $limitDates['paginationdate'] : false ;
		}


		// ordering. DO NOT change the ordering.
		$order[]	= 'ORDER BY a.' . $db->nameQuote( 'modified' ) . ' DESC';


		// regenerate the table list
		if( empty( $listid ) && empty( $isFollow ) && !$streamId && !$guest )
		{
			$useDate = ( $isSingleItem ) ? false : true;

			$streamTableAlias = '';

			if( $direction == 'later' )
			{
				$streamTableAlias = $this->getStreamTableAlias( $userid[ 0 ], $type, $useDate, $direction, $limitstart );
			}
			else
			{
				$streamTableAlias = $this->getStreamTableAlias( $userid[ 0 ], $type, $useDate, $direction, $limitDates[ 'startdate' ], $limitDates[ 'enddate' ] );
			}

			$table[0] = ' FROM ' . $streamTableAlias;
		}
		else
		{
			// startdate holding the larger date
			// enddte holding the smaller date.
			if(! $isSingleItem && !$limit)
			{
				if( $direction == 'later' )
				{
					$cond[]	= 'AND a.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $limitstart );
				}
				else
				{
					$cond[]	= 'AND ( a.' . $db->nameQuote( 'modified' ) . ' <= ' . $db->Quote( $limitDates[ 'startdate' ] ) . ' AND a.' . $db->nameQuote( 'modified' ) . ' >= ' . $db->Quote( $limitDates[ 'enddate' ] ) . ')';
				}
			}
		}


		// concate all the queries segments.
		$query 		= implode( ' ' , $query );
		$table 		= implode( ' ' , $table );

		$cond 		= implode( ' ' , $cond );
		$order 		= implode( ' ' , $order );

		$query 		= $query . ' ' . $table . ' ' . $cond . ' ' . $order;

		if( $guest && $limit )
		{
			$query 		.= ' LIMIT ' . $startlimit . ',' . $limit;
		}
		else
		{
			$query 		.= ' LIMIT ' . $hardLimit;
	 	}

		$db->setQuery( $query );

		$result 	= $db->loadObjectList();

		// echo $query;

		// var_dump( $result );
		// exit;

		// $result = array();

		$lastItemDate 	= '';
		$total 			= count( $result );

		if( $total )
		{
			$streamIds = array();

			foreach( $result as $row )
			{
				$streamIds[] 	= $row->id;
				$lastItemDate 	= $row->modified;
			}

			// -------------------------------------------------------------
			// This is the starting points of optimizing queries for stream.
			// -------------------------------------------------------------

			// @sam: it seems like adding these two slowing down the page load. lets comment it for now.
			// $streamTbl = Foundry::table( 'Stream' );
			// $streamTbl->loadByBatch( $streamIds );

			// $streamItemTbl = Foundry::table( 'StreamItem' );
			// $streamItemTbl->loadByUIDBatch( $streamIds );

			$this->setBatchRelatedItems( $streamIds, $context, $viewer );

			// set stream actors.
			$this->setActorsBatch( $result );

			// set stream photos.
			$this->setMediaBatch( $result );

			// set stream tagging
			$this->setTaggingBatch( $streamIds );

			if( $sysconfig->get( 'stream.likes.enabled' ) )
			{
				//set stream likes
				$like = Foundry::model( 'Likes' );
				$like->setStreamLikesBatch( $result );
			}

			if( $sysconfig->get( 'stream.repost.enabled' ) )
			{
				//set stream repost
				$repost = Foundry::model( 'Repost' );
				$repost->setStreamRepostBatch( $result );

				$share = Foundry::table( 'Share' );
				$share->setSharesBatch( $result );
			}

			if( $sysconfig->get( 'stream.comments.enabled' ) )
			{
				// comment count
				$commentModel = Foundry::model( 'Comments' );
				$commentModel->setStreamCommentCountBatch( $result );
			}

			// privacy
			$privacyModel = Foundry::model( 'Privacy' );
			$privacyModel->setStreamPrivacyItemBatch( $result );
		}

		if( ( !$isSingleItem )  && ( $total == $hardLimit ) && ( !$limit ) )
		{
			// this could be where we limiting the resultset using the hard.limit. let set the next paganation date using the last record's modified date.

			// setting next end date
			$this->enddate 		= $this->nextdate;

			// setting next start date.
			$this->nextdate 	= $lastItemDate;
		}
		else
		{
			if( $this->paginationdate === false )
			{
				$this->nextdate 	= '';
			}
		}

		// var_dump( $result );
		// exit;

		return $result;
	}

	public function setMediaBatch( $result )
	{
		$photoIds 	= array();
		$albumIds 	= array();

		$streamModel = Foundry::model( 'Stream' );

		foreach( $result as $item )
		{
			if( $item->context_type == 'photos' )
			{
				$relatedData = $streamModel->getBatchRalatedItem( $item->id );

				if( $relatedData )
				{
					foreach( $relatedData as $rdata )
					{
						$photoIds[] = $rdata->context_id;
					}
				}
			}
		}

		if( $photoIds )
		{
			// photos
			$photo = Foundry::table( 'Photo' );
			$photo->setCasheable( true );
			$albumIds = $photo->loadByBatch( $photoIds );

			// photos meta
			$photoModel = Foundry::model( 'Photos' );
			$photoModel->setCasheable( true );
			$photoModel->setMetasBatch( $photoIds );
		}

		if( $albumIds )
		{
			$albumIds = array_unique( $albumIds );
			$album = Foundry::table( 'Album' );
			$album->loadByBatch( $albumIds );
		}

	}

	public function setActorsBatch( $result )
	{
		$actorIds  = array();

		$streamModel = Foundry::model( 'Stream' );

		foreach( $result as $item )
		{
			$relatedData = $streamModel->getBatchRalatedItem( $item->id );

			if( $relatedData )
			{
				foreach( $relatedData as $rdata )
				{
					$actorIds[] = $rdata->actor_id;

					if( $rdata->target_id )
					{
						if( !( $rdata->context_type == 'photos' && $rdata->verb == 'add' )
							&& !( $rdata->context_type == 'shares' && $rdata->verb == 'add.stream' ) )
						{
							$actorIds[] = $rdata->target_id;
						}
					}
				}
			}
		}

		$actorIds[] = Foundry::user()->id;
		$actors		= array_unique($actorIds);

		if( $actors )
		{
			$userModel = Foundry::model( 'Users' );
			$userModel->setUserGroupsBatch( $actors );

			// Preload users
			Foundry::user( $actors );
		}
	}


	public function setBatchRelatedItems( $uids , $context, $viewer = null )
	{
		// _relateditems

		// make sure the keys is not already added.
		foreach( $uids as $id )
		{
			if( array_key_exists( $id, self::$_relateditems ) )
			{
				return ;
			}
		}

		// Get related activities for aggregation.
		$query 		= array();

		$db 		= Foundry::db();
		$idSegments = array_chunk( $uids, 5 );
		//$idSegments = array_chunk( $uids, count( $uids ) );

		for( $i = 0; $i < count( $idSegments ); $i++ )
		{
			$segment    = $idSegments[$i];
			$ids  		= implode( ',', $segment );

			$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_stream_item' ) . ' as a';
			$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . ' IN ( ' . $ids . ')';

			if( $context != 'all' && $context != SOCIAL_TYPE_STREAM )
			{
				$query[]	= 'AND ' . $db->nameQuote( 'context_type' ) . '=' . $db->Quote( $context );
			}

			if( $viewer )
			{
				$query[]	= 'AND a.' . $db->nameQuote( 'id' ) . ' NOT IN (';
				$query[]	= 'SELECT h.' . $db->nameQuote( 'uid' ) . ' FROM ' . $db->nameQuote( '#__social_stream_hide' ) . ' AS h';
				$query[]	= 'WHERE h.' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $viewer );
				$query[]	= 'AND h.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'activity' );
				$query[]	= ')';
			}

			if( ($i + 1)  < count( $idSegments ) )
			{
				$query[] = ' UNION ';
			}
		}

		$query 		= implode( ' ' , $query );

		// echo $query;
		// exit;

		$db->setQuery( $query );

		$result = $db->loadObjectList();

		foreach( $result as $row )
		{
			self::$_relateditems[ $row->uid ][] = $row;
		}

	}


	public function getBatchRalatedItem( $uid )
	{
		if( isset( self::$_relateditems[ $uid ] ) )
		{
			return self::$_relateditems[ $uid ];
		}
	}


	/**
	 * Get related activities from a single stream so that we can perform aggregation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The context.
	 */
	public function getRelatedActivities( $uid, $context, $viewer = null )
	{

		$keys = $uid;

		if( isset( self::$_relateditems[$keys] ) )
		{
			return self::$_relateditems[$keys];
		}

		// items not found from static variable. lets fall back to manual sql method.

		$db 		= Foundry::db();

		// Get related activities for aggregation.
		$query 		= array();
		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_stream_item' ) . ' as a';
		$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );

		if( $context != 'all' && $context != SOCIAL_TYPE_STREAM )
		{
			$query[]	= 'AND ' . $db->nameQuote( 'context_type' ) . '=' . $db->Quote( $context );
		}

		if( $viewer )
		{
			$query[]	= 'AND a.' . $db->nameQuote( 'id' ) . ' NOT IN (';
			$query[]	= 'SELECT h.' . $db->nameQuote( 'uid' ) . ' FROM ' . $db->nameQuote( '#__social_stream_hide' ) . ' AS h';
			$query[]	= 'WHERE h.' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $viewer );
			$query[]	= 'AND h.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'activity' );
			$query[]	= ')';
		}

		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$result 	= $db->loadObjectList();

		// log into static variable.
		self::$_relateditems[$keys] = $result;

		return $result;
	}

	public function setBatchActivityItems( $data )
	{
		foreach( $data as $row )
		{
			self::$_activitylogs[ $row->id ][] = $row;
 		}

 		// var_dump( self::$_activitylogs );
 		// exit;
	}

	/**
	 * Get related activities from a single stream so that we can perform aggregation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		activity item id.
	 */
	public function getActivityItem( $uid, $column = 'id' )
	{

		if( $column == 'uid' )
		{
			if( isset( self::$_relateditems[ $uid ] ) )
			{
				return self::$_relateditems[ $uid ];
			}
		}
		else
		{
			if( isset( self::$_activitylogs[ $uid ] ) )
			{
				return self::$_activitylogs[ $uid ];
			}
		}


		$db 		= Foundry::db();

		// Get related activities for aggregation.
		$sql 		= $db->sql();
		$sql->select( '#__social_stream_item' );
		$sql->where( $column , $uid );

		$db->setQuery( $sql );

		$result 	= $db->loadObjectList();

		return $result;
	}

	/**
	 * used in stream api
	 */
	public function updateStream( $data )
	{
		$db 		= Foundry::db();

		$date 		= Foundry::date( $data->created );
		$duration   = 30;

		// Get the config obj.
		$config 	= Foundry::config();

		$allowAggregation = $config->get( 'stream.aggregation.enabled' );


		// The duration between activities.
		$duration 	= $config->get( 'stream.aggregation.duration' );


// title , content , context , duration , stream_type

		// if( $allowAggregation && in_array( $data->context_type, $aggregateContext ) )
		if( $data->isAggregate )
		{

			// retrive the last item
			$query  = 'select ' . $db->nameQuote( 'id' ) . ', ' . $db->nameQuote( 'uid' ) . ' from ' . $db->nameQuote( '#__social_stream_item' );
			$query  .= ' where ' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $data->actor_id );
			$query  .= ' and ' . $db->nameQuote( 'actor_type' ) . ' = ' . $db->Quote( $data->actor_type );
			$query  .= ' and ' . $db->nameQuote( 'context_type' ) . ' = ' . $db->Quote( $data->context_type );
			$query  .= ' and ' . $db->nameQuote( 'verb' ) . ' = ' . $db->Quote( $data->verb );
			$query  .= ' and ' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $data->target_id );
			$query  .= ' and ' . $db->nameQuote( 'sitewide' ) . ' = ' . $db->Quote( $data->sitewide );
			$query  .= ' and date_add( ' . $db->nameQuote( 'created' ) . ', INTERVAL ' . $duration . ' MINUTE) >= ' . $db->Quote( $date->toMySQL() );
			$query	.= ' order by ' . $db->nameQuote( 'created' ) . ' DESC limit 1';

			$db->setQuery( $query );

			$result = $db->loadObject();


			if( isset( $result->uid ) )
			{

				$streamTbl	= Foundry::table('Stream');
				$streamTbl->load( $result->uid );
				$streamTbl->modified    = $date->toMySQL();
				$streamTbl->ispublic 	= ( isset( $data->isPublic ) && $data->isPublic ) ? 1 : 0;
				$streamTbl->store();

				return $result->uid;
			}

			// if not found.
			$query  = 'select * from ' . $db->nameQuote( '#__social_stream_item' );
			$query	.= ' order by ' . $db->nameQuote( 'created' ) . ' DESC limit 1';

			$db->setQuery( $query );

			$result = $db->loadObject();

			if( isset( $result->id ) )
			{
				if( $result->actor_id 		== $data->actor_id &&
					$result->actor_type 	== $data->actor_type &&
					$result->context_type 	== $data->context_type &&
					$result->verb 			== $data->verb &&
					$result->target_id 		== $data->target_id &&
					$result->sitewide 		== $data->sitewide )
				{

					$streamTbl	= Foundry::table('Stream');
					$streamTbl->load( $result->uid );
					$streamTbl->modified    = $date->toMySQL();
					$streamTbl->ispublic 	= ( isset( $data->isPublic ) && $data->isPublic ) ? 1 : 0;
					$streamTbl->store();

					return $result->uid;
				}
			}

		}

		// new stream
		$tbl    = Foundry::table( 'Stream' );
		$tbl->bind( $data );

		$tbl->actor_type	= ( isset( $data->actor_type ) && !empty( $data->actor_type ) ) ? $data->actor_type : 'user' ;
		$tbl->alias			= '';
		$tbl->created		= $date->toMySQL();
		$tbl->ispublic 		= ( isset( $data->isPublic ) && $data->isPublic ) ? 1 : 0;
		$tbl->params 		= $data->params;
		$tbl->modified		= $date->toMySQL();

		$tbl->store();

		return $tbl->id;
	}


	public function setWith( $streamId, $ids )
	{
		if(! is_array( $ids ) )
		{
			$ids = array( $ids );
		}

		foreach( $ids as $id )
		{
			if( ! $id )
				continue;

			$tbl = Foundry::table( 'StreamTags' );
			$tbl->stream_id = $streamId;
			$tbl->uid 		= $id;
			$tbl->utype 	= SOCIAL_STREAM_TAGGING_TYPE_USER;
			$tbl->with 		= 1;

			$tbl->store();
		}
	}

	public function setTaggingBatch( $streamIds )
	{
		// _tagging

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		//$uids = implode( ',', $streamIds );


		$ids = array();
		foreach( $streamIds as $sid )
		{
			if( ! isset( self::$_tagging[ $sid ] ) )
			{
				$ids[] = $db->Quote( $sid );
				self::$_tagging[ $sid ] = array();
			}
		}

		if( $ids )
		{
			$uids = implode( ',', $ids );

			$query = 'select * from `#__social_stream_tags` where `stream_id` IN (' . $uids . ') order by `stream_id`, `offset` desc';
			$sql->raw( $query );

			$db->setQuery( $sql );

			$result = $db->loadObjectList();

			if( $result )
			{
				foreach( $result as $row )
				{

					if( $row->with )
					{
						self::$_tagging[ $row->stream_id ][ 'with' ][] = Foundry::user( $row->uid );
					}
					else
					{
						//this is a mention
						$obj = new stdClass();
						$obj->user = Foundry::user( $row->uid );
						$obj->offset = $row->offset;
						$obj->length = $row->length;

						$mentions[] = $obj;
						self::$_tagging[ $row->stream_id ][ 'mention' ][] = $obj;

					}

				}
			}
		}

	}



	/**
	 * int - stream id
	 * string - request type. with / mention
	 */

	public function getTagging( $streamId, $reqType = 'with' )
	{

		if( ! isset( self::$_tagging[ $streamId ] ) )
		{

			$db 		= Foundry::db();
			$sql 		= $db->sql();

			$sql->select( '#__social_stream_tags', 'a' );
			$sql->column( 'a.*' );
			$sql->where( 'a.stream_id' , $streamId );
			$sql->order( 'a.offset' , 'DESC' );

			$db->setQuery( $sql );
			$result = $db->loadObjectList();

			$withs 		= array();
			$mentions 	= array();

			if( $result )
			{
				$users = array();
				foreach( $result as $row )
				{
					$users[] = $row->uid;
				}

				//preload users
				Foundry::user( $users );

				foreach( $result as $row )
				{
					if( $row->with )
					{
						$withs[] = Foundry::user( $row->uid );
					}
					else
					{
						//this is a mention
						$obj = new stdClass();
						$obj->user = Foundry::user( $row->uid );
						$obj->offset = $row->offset;
						$obj->length = $row->length;

						$mentions[] = $obj;
					}
				}
			}

			self::$_tagging[ $streamId ][ 'with' ] 		= $withs;
			self::$_tagging[ $streamId ][ 'mention' ] 	= $mentions;
		}

		if( isset( self::$_tagging[ $streamId ][ $reqType ] ) )
		{
			return self::$_tagging[ $streamId ][ $reqType ];
		}
		else
		{
			return array();
		}

	}

	public function getStreamActor( $streamId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$query = 'select `actor_id` from `#__social_stream` where `id` = ' . $db->Quote( $streamId );
		$sql->raw( $query );

		$db->setQuery( $sql );

		$result = $db->loadResult();

		if( $result )
		{
			$user = Foundry::user( $result );
			return $user;
		}

		return false;
	}

	public function getUpdateCount( $source, $currentdate, $type, $id = '', $exclude = null )
	{
		$db 	= Foundry::db();
		$sql  	= $db->sql();
		$user 	= Foundry::user();

		$userId = $user->id;
		if( $type == 'me' || $type == 'following')
		{
			if( $id )
			{
				$userId = $id;
			}
		}


		$commonCond = '';
		//$commonCond .= ' where a.`modified` >= ' . $db->Quote( $currentdate );

		// we use the > to unsure the same stream will not get counted.
		// this is to prevent when someone post a story at the same time as the current date being returned by the
		// checkupdate, which lead to duplicate stream.
		$commonCond .= ' where a.`actor_type` = ' . $db->Quote( SOCIAL_TYPE_USER );
		$commonCond .= ' and a.`modified` > ' . $db->Quote( $currentdate );
		if( $exclude )
		{
			$commonCond .= ' and a.id NOT IN (' . $exclude . ')';
		}

		$query = '';

		// following
		$query .= 'select count(1) as `cnt`, ' . $db->Quote( 'following' ) . ' as `type` from `#__social_stream` as a';
		$query .= ' inner join `#__social_subscriptions` as s';
		$query .= ' on a.`actor_id` = s.`uid`';
		$query .= '		AND s.`type` = ' . $db->Quote( SOCIAL_TYPE_USER . '.' . SOCIAL_SUBSCRIPTION_TYPE_USER );
		$query .= '		AND s.`user_id` = ' . $db->Quote( $userId );
		$query .= $commonCond;


		$query .= ' union ';

		// me
		$query .= '';
		$query .= 'select count(1) as `cnt`, ' . $db->Quote( 'me' ) . ' as `type` from `#__social_stream` as a';
		$view = JRequest::getVar( 'view', '');

		if( $source == 'dashboard' )
		{
			$query	.= ' LEFT JOIN `#__social_friends` AS f1 ON a.`actor_id` = f1.`target_id` and f1.`actor_id` = ' . $db->Quote( $userId ) . ' and f1.`state` = 1';
			$query	.= ' LEFT JOIN `#__social_friends` AS f2 ON a.`actor_id` = f2.`actor_id` and f2.`target_id` = ' . $db->Quote( $userId ) . ' and f2.`state` = 1';
		}
		$query .= $commonCond;

		// start bracket
		$tmp	= 'AND (';

		// my items.
		$tmp	.= ' a.`actor_id` = ' . $db->Quote( $userId );
		$tmp 	.= ' OR ( a.`target_id` = ' . $db->Quote( $userId ) . ' and a.`context_type` = ' . $db->Quote( 'story' ) . ')' ;

		if( $source == 'dashboard' )
		{
			// my friends items.
			$tmp 	.= ' OR f1.`actor_id` = ' . $db->Quote( $userId );
			$tmp 	.= ' OR f2.`target_id` = ' . $db->Quote( $userId );
		}

		// my tagged items.
		$tmp .= ' OR exists ( select st.`stream_id` from `#__social_stream_tags` as st' ;
		$tmp .= '                 where st.`stream_id` = a.`id`';
		$tmp .= ' 					and st.`uid` = ' . $db->Quote( $userId );
		$tmp .= ' 					and st.`utype` = ' . $db->Quote( SOCIAL_STREAM_TAGGING_TYPE_USER ) . ')';

		// end bracket
		$tmp 	.= ')';
		$query .= $tmp;

		if( $source == 'dashboard' )
		{
			$query .= ' union ';

			$query .= '';
			$query .= 'select count(1) as `cnt`, ' . $db->Quote( 'everyone' ) . ' as `type` from `#__social_stream` as a';
			$query .= $commonCond;
		}


		$query .= ' union ';

		//list
		$query .= '';
		$query .= 'select count(a.`id`) as `cnt`, CONCAT(' . $db->Quote( 'list-' ) . ', c.`id` ) as `type`';
		$query .= ' from `#__social_stream` as a';
		$query .= ' 	inner join `#__social_lists_maps` as b on a.`actor_id` = b.`target_id` and b.`target_type` = ' . $db->Quote( SOCIAL_TYPE_USER );
		$query .= ' 	inner join `#__social_lists` as c on b.`list_id` = c.`id`';
		$query .= $commonCond;
		$query .= '	and c.`user_id` = ' . $db->Quote( $userId );
		$query .= ' group by c.`id`';

		$sql->raw( $query );

		// echo $query;
		// exit;

		$db->setQuery( $sql );

		$result = $db->loadAssocList();

		return $result;
	}


	/**
	 * Retrieves assets based on the stream id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The id of the stream item
	 * @param	string	The context type
	 * @return
	 */
	public function getAssets( $id , $type )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_stream_assets' );
		$sql->where( 'stream_id' , $id );
		$sql->where( 'type' , $type );

		$db->setQuery( $sql );

		$items 	= $db->loadObjectList();

		return $items;
	}
}
