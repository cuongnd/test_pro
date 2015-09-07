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

class EasySocialModelNotifications extends EasySocialModel
{
	function __construct()
	{
		parent::__construct( 'notifications' );
	}

	public function setAllState( $state )
	{
		$my 	= Foundry::user();

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$query 	= '';

		if( $state == 'clear' )
		{
			$query = 'delete from `#__social_notifications`';
			$query .= ' where `target_id` = ' . $db->Quote( $my->id );
			$query .= ' and `target_type` = ' . $db->Quote( SOCIAL_TYPE_USER );
		}
		else
		{
			$query = 'update `#__social_notifications` set `state` = ' . $db->Quote( $state );
			$query .= ' where `target_id` = ' . $db->Quote( $my->id );
			$query .= ' and `target_type` = ' . $db->Quote( SOCIAL_TYPE_USER );
		}

		$sql->clear();
		$sql->raw( $query );
		$db->setQuery( $sql );

		// echo $query;
		// exit;

		$state = $db->query();
		return $state;

	}

	/**
	 * Saves a notification settings
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of notification, whether it is an email or system
	 * @return	
	 */
	public function saveNotifications( $systemNotifications , $emailNotifications , SocialUser $user )
	{
		// Get the id's of all the notifications
		$keys	= array_keys( $systemNotifications );
		$rules	= array();

		foreach( $keys as $key )
		{
			$obj 			= new stdClass();
			$obj->id 		= $key;
			$obj->email		= isset( $emailNotifications[ $key ] ) ? $emailNotifications[ $key ] : true;
			$obj->system 	= isset( $systemNotifications[ $key ] ) ? $systemNotifications[ $key ] : true;

			$rules[]	= $obj;
		}

		// Now that we have the rules, store them.
		foreach( $rules as $rule )
		{
			$map 	= Foundry::table( 'AlertMap' );
			$state	= $map->load( array( 'alert_id' => $rule->id , 'user_id' => $user->id ) );

			$map->alert_id 	= $rule->id;
			$map->user_id	= $user->id;

			$map->email 	= $rule->email;
			$map->system 	= $rule->system;

			$map->store();
		}

		return true;
	}

	/**
	 * Retrieve a list of notification items from the database.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getItems( $options = array() )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_notifications' );

		// If published options is provided, only search for respective notification items.
		if( isset( $options[ 'unread' ] ) )
		{
			$sql->where( 'state' , SOCIAL_NOTIFICATION_STATE_UNREAD );
		}

		// Only fetch items from specific target id and type if necessary.
		$target 		= isset( $options[ 'target_id' ] ) ? $options[ 'target_id' ] : null;

		if( $target )
		{
			$targetType	= $options[ 'target_type' ];

			$sql->where( 'target_id' , $target );
			$sql->where( 'target_type' , $targetType );
		}

		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : 0;
		if( $limit )
		{
			$startlimit = isset( $options[ 'startlimit' ] ) ? $options[ 'startlimit' ] : 0;
			$sql->limit( $startlimit, $limit );
		}


		// Always order by latest first
		$ordering 	= isset( $options[ 'ordering' ] ) ? $options[ 'ordering' ] : '';

		if( $ordering )
		{
			$direction 	= isset( $options[ 'direction' ] ) ? $options[ 'direction' ] : 'DESC';
			$sql->order( $ordering , $direction );
		}
		else
		{
			$sql->order( 'created' , 'DESC' );
		}

		$db->setQuery( $sql );

		// echo $sql;

		$items	= $db->loadObjectList();

		if( !$items )
		{
			return $items;
		}

		$result 	= array();

		foreach( $items as $item )
		{
			$notification 	= Foundry::table( 'Notification' );
			$notification->bind( $item );

			$result[]		= $notification;
		}

		return $result;
	}

	/**
	 * Retrieves the count of notification items.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options. unread - Only count unread items
	 * @return	int		The count.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCount( $options = array() )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_notifications' );
		$sql->column( 'COUNT(1)' );

		// Only fetch items from specific target id and type if necessary.
		$target 		= isset( $options[ 'target' ] ) ? $options[ 'target' ] : null;

		if( !is_null( $target ) && is_array( $target ) )
		{
			$targetId 	= $target[ 'id' ];
			$targetType	= $target[ 'type' ];

			$sql->where( 'target_id' 	, $targetId );
			$sql->where( 'target_type'	, $targetType );
		}

		// Only fetch unread items
		if( isset( $options[ 'unread' ] ) )
		{
			$sql->where( 'state' , SOCIAL_NOTIFICATION_STATE_UNREAD );
		}
// echo $sql->debug();exit;
		$db->setQuery( $sql );

		$total 		= $db->loadResult();

		return $total;
	}
}
