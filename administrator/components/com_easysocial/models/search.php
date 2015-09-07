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
Foundry::import( 'admin:/includes/privacy/option' );

class EasySocialModelSearch extends EasySocialModel
{
	private $data			= null;
	private $types     		= null;
	private $next_limit    	= null;
	protected $total 			= null;

	function __construct()
	{
		parent::__construct( 'search' );
	}

	public function getTypes()
	{
		$db = Foundry::db();

		if(! $this->types )
		{
			// get utypes from queries
			$typeQuery = 'select distinct ' . $db->nameQuote( 'utype' ) . ' FROM ' . $db->nameQuote( '#__social_indexer' );
			$db->setQuery( $typeQuery );
			$types = $db->loadObjectList();

			$this->types = $types;
		}

		return $this->types;
	}


	public function verifyFieldsData( $keywords, $userId )
	{
		// return variable
		$content 		= '';

		// get customfields.
		$fieldsLib		= Foundry::fields();
		$fieldModel  	= Foundry::model( 'Fields' );
		$fieldsResult 	= array();

		$options = array();
		$options['data'] 		= true;
		$options['dataId'] 		= $userId;
		$options['dataType'] 	= SOCIAL_TYPE_USER;
		$options['searchable'] 	= 1;

		//todo: get customfields.
		$fields = $fieldModel->getCustomFields( $options );

		if( count( $fields ) > 0 )
		{
			//foreach( $fields as $item )
			foreach( $fields as $field )
			{
				$userFieldData  = isset( $field->data ) ? $field->data : '';

				$args 			= array( $userId, $keywords, $userFieldData );
				$f 				= array( &$field );

				$dataResult 	= $fieldsLib->trigger( 'onIndexerSearch' , SOCIAL_FIELDS_GROUP_USER , $f , $args );

				if( $dataResult !== false && count( $dataResult ) > 0 )
					$fieldsResult[]  	= $dataResult[0];
			}

			$contentSnapshot = array();

			$totalReturnFields = count( $fieldsResult );
			$invalidCnt        = 0;

			if( $fieldsResult )
			{
				// we need to go through each one to see if any of the result returned is a false or not.
				// false mean, the user canot view the fields.
				// this also mean, the user canot view the searched item.

				foreach( $fieldsResult as $fr )
				{
					if( $fr == -1 )
					{
						$invalidCnt++;
					}
					else if( !empty( $fr ) )
					{
						$contentSnapshot[] = $fr;
					}
				}

				if( $invalidCnt == $totalReturnFields )
				{
					return -1;
				}
			}

			if( $contentSnapshot )
			{
				$content = implode( '<br />', $contentSnapshot );
			}

		}

		return $content;
	}


	public function getItems( $keywords, $type = '', $next_limit = null, $limit = 0 )
	{
	    $db     = Foundry::db();
	    $sql 	= $db->sql();
	    $my     = Foundry::user();

	    $coreType = array( SOCIAL_INDEXER_TYPE_USERS, SOCIAL_INDEXER_TYPE_PHOTOS, SOCIAL_INDEXER_TYPE_LISTS );

		if( empty( $keywords ) )
			return;

		$where		= array();
		$wheres		= array();
		$words		= explode( ' ', $keywords );

		if( count( $words ) > 1 )
		{
			$tmp = array();
			$cnt = count( $words ) - 1;
			for( $i = 0; $i < $cnt; $i++ )
			{
				$tmp[] = $words[ $i ] . ' ' . $words[ $i + 1 ];
			}

			$words	= $tmp;
		}

		foreach ($words as $word)
		{
			$word		= $db->Quote( '%'.$db->escape( $word, true ).'%', false );

			// $where[]	= 'a.`title` LIKE ' . $word;
			$where[]	= 'a.`content` LIKE ' . $word;


			$wheres[] 	= implode( ' OR ', $where );
		}
		$where	= ' (' . implode( ') OR (' , $wheres ) . ')';


		$mainQuery = array();


		//process item limit
		$defaultLimit = $limit;

		$queryLimit = '';
		if( $next_limit )
		{
			$queryLimit = ' LIMIT ' . $next_limit . ', ' . $defaultLimit;
			$next_limit = $next_limit + $defaultLimit;
		}
		else
		{
			$queryLimit = ' LIMIT ' . $defaultLimit;
			$next_limit = $defaultLimit;
		}


		// users
		$query = 'select a.* FROM `#__social_indexer` as a';
		$query .= ' inner join `#__users` as u ON a.`uid` = u.`id` and u.`block` = ' . $db->Quote( '0' );
		$query .= ' where `utype` = ' . $db->Quote( SOCIAL_INDEXER_TYPE_USERS );
		$query .= ' and (' . $where . ')';
		if( $type == '' || $type == SOCIAL_INDEXER_TYPE_USERS)
			$mainQuery[] = $query;


		if( $my->id )
		{
			// own photos
			$query = 'select a.* FROM `#__social_indexer` as a';
			$query .= ' where `utype` = ' . $db->Quote( SOCIAL_INDEXER_TYPE_PHOTOS );
			$query .= ' and `ucreator` = ' . $db->Quote( $my->id );
			$query .= ' and (' . $where . ')';
			if( $type == '' || $type == SOCIAL_INDEXER_TYPE_PHOTOS)
				$mainQuery[] = $query;


			// own friend list
			$query = 'select a.* FROM `#__social_indexer` as a';
			$query .= ' where `utype` = ' . $db->Quote( SOCIAL_INDEXER_TYPE_LISTS );
			$query .= ' and `ucreator` = ' . $db->Quote( $my->id );
			$query .= ' and (' . $where . ')';
			if( $type == '' || $type == SOCIAL_INDEXER_TYPE_LISTS)
				$mainQuery[] = $query;
		}

		// others
		$query = 'select a.* FROM `#__social_indexer` as a';
		if( $type && !in_array( $type, $coreType) )
		{
			$query .= ' where `utype` = ' . $db->Quote( $type );
		}
		else
		{
			$query .= ' where `utype` NOT IN (' . $db->Quote( SOCIAL_INDEXER_TYPE_USERS ) . ',' . $db->Quote( SOCIAL_INDEXER_TYPE_PHOTOS ) . ',' . $db->Quote( SOCIAL_INDEXER_TYPE_LISTS ) . ')';
		}
		$query .= ' and (' . $where . ')';
		if( $type == '' || ( $type != SOCIAL_INDEXER_TYPE_USERS && $type != SOCIAL_INDEXER_TYPE_PHOTOS && $type != SOCIAL_INDEXER_TYPE_LISTS) )
			$mainQuery[] = $query;





		if( ! $mainQuery )
		{
			// this mean the user is a guest and trying to click on the photos / friend list filtering.
			$this->next_limit = '-1';
			return array();
		}


		$mainQuery = '(' . implode( ') UNION (', $mainQuery ) . ')';
		$mainQuery = 'select * FROM ( ' . $mainQuery . ' ) as x';

		// query for total count.
		$cntQuery = 'select COUNT(1) FROM ( ' . $mainQuery . ' ) as x';


		// continue
		$mainQuery .= ' order by x.`utype` desc, x.`last_update` desc';

		// limit
		$query = $mainQuery . $queryLimit;

		// getting items count.
		$sql->clear();
		$sql->raw( $cntQuery );

		$db->setQuery( $sql );

		$this->total = $db->loadResult();

		$sql->clear();
		$sql->raw( $query );


		$db->setQuery( $sql );
		$result = $db->loadObjectList();

		$filtered 	= array();
		$privacy 	= Foundry::privacy( $my->id );

		if( count( $result ) > 0 )
		{
			if( count( $result ) < $defaultLimit )
			{
				//this mean the resultset is the last batch
				$next_limit = '-1';
			}

			//foreach( $result as $item )
			for( $i = 0; $i < count( $result ); $i++  )
			{
				$item 			=& $result[ $i ];

				$privacy_key 	= ( $item->utype == SOCIAL_INDEXER_TYPE_USERS ) ? 'profiles' : $item->utype;
				$privacy_rule 	= ( $item->utype == SOCIAL_INDEXER_TYPE_USERS ) ? 'search' : 'view';

				$keys = $privacy_key . '.' . $privacy_rule;

				$addItem = false;

				if( $keys == 'profiles.search' )
				{
					$addItem = $privacy->validate( $keys, $item->ucreator );
				}
				else
				{
					$addItem = $privacy->validate( $keys, $item->uid, $item->utype, $item->ucreator );
				}

				// if this item is a user type, the content might be from fields. let check the fields privacy.
				if( $addItem && $item->utype == SOCIAL_INDEXER_TYPE_USERS )
				{
					$smallText  = $this->verifyFieldsData( $keywords, $item->uid );

					if( $smallText === false )
					{
						// when this is false, meean the user canot view the result which is returned by the fields.
						$addItem = false;
					}
					else
					{
						$item->description = $smallText;
					}
				}

				if( $addItem )
				{
					$filtered[] = $item;
				}
			}

			if( count( $filtered ) < $defaultLimit && $next_limit > 0)
			{
				$this->fillData( $defaultLimit, $next_limit, $filtered, $mainQuery);
			}

		}
		else
		{
			$next_limit = '-1';
		}


		//setting next limit for loadmore
		$this->next_limit = $next_limit;

		// we need to adjust the total item count here due to privacy checking
		if( $this->total > count( $filtered ) && !$filtered )
		{
			$this->total = 0;
		}

		// var_dump( $groups );
		// exit;

		// echo $query;

		$groups 	= array();
		if( count( $filtered ) > 0 )
		{
			foreach( $filtered as $item )
			{
				$groups[$item->utype][] = $item;
			}
		}

		return $groups;
	}

	public function fillData( $defaultLimit, $next_limit, &$filtered, $query )
	{
		$db 		= Foundry::db();

		$my     	= Foundry::user();
		$privacy 	= Foundry::privacy( $my->id );
		$cnt 		= 0;

		$tryLimit 	= 2;

		do{
			$startLimit = $next_limit;

			if( $next_limit == '-1' )
				return;

			$queryLimit = ' LIMIT ' . $next_limit . ',' . $defaultLimit;
			$nextQuery = $query . $queryLimit;

			$db->setQuery( $nextQuery );

			$result = $db->loadObjectList();

			if( count( $result ) > 0 )
			{
				foreach( $result as $item )
				{
					$privacy_key 	= ( $item->utype == SOCIAL_INDEXER_TYPE_USERS ) ? 'profiles' : $item->utype;
					$privacy_rule 	= ( $item->utype == SOCIAL_INDEXER_TYPE_USERS ) ? 'search' : 'view';

					$keys = $privacy_key . '.' . $privacy_rule;

					$addItem = false;

					if( 'profiles.search' )
					{
						$addItem = $privacy->validate( $keys, $item->ucreator );
					}
					else
					{
						$addItem = $privacy->validate( $keys, $item->uid, $item->utype, $item->ucreator );
					}

					if( $addItem )
					{
						$filtered[] = $item;
						$next_limit = $next_limit + 1;

						if( count( $filtered ) == $defaultLimit )
						{
							break;
						}

					}
				}

				$cnt = count( $filtered );
			}
			else
			{
				$next_limit = '-1';
			}

			$tryLimit--;

		} while ( ( $cnt < $defaultLimit || $next_limit != '-1' ) && $tryLimit > 0 );


	}

	public function getCount()
	{
		return empty ( $this->total ) ? '0' : $this->total ;
	}




	public function getNextLimit()
	{
		return $this->next_limit;
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
}
