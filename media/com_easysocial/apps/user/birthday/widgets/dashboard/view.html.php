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

/**
 * Profile view for Notes app.
 *
 * @since	1.0
 * @access	public
 */
class BirthdayWidgetsDashboard extends SocialAppsWidgets
{
	/**
	 * Displays the dashboard widget
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function sidebarBottom()
	{
		// Get the app params
		$params 	= $this->app->getParams();
		$key 		= $params->get( 'dashboard_show_uniquekey' , 'BIRTHDAY' );

		// @TODO: Based on the unique key, get the field id.
		// $fieldId 	=

		// Get current logged in user
		$my 		= Foundry::user();
		$birthdays 	= $this->getUpcomingBirthdays( $key , $my->id );

		$ids 		= array();
		$dateToday 	= Foundry::date()->toFormat( 'md' );

		$today 		= array();
		$otherDays 	= array();

		// Hide app when there's no upcoming birthdays
		if( !$birthdays )
		{
			return;
		}

		if( $birthdays )
		{
			foreach( $birthdays as $birthday )
			{
				$ids[]	= $birthday->uid;
			}

			// Preload list of users
			Foundry::user( $ids );

			foreach( $birthdays as $birthday )
			{
				$obj = new stdClass();
				$obj->user 		= Foundry::user( $birthday->uid );
				$obj->birthday 	= $birthday->displayday;


				if( $birthday->day == $dateToday )
				{
					$today[]		= $obj;
				}
				else
				{
					$otherDays[]	= $obj;
				}
			}
		}

		$this->set( 'ids'		, $ids );
		$this->set( 'birthdays'	, $birthdays );
		$this->set( 'today'		, $today );
		$this->set( 'otherDays' , $otherDays );

		echo parent::display( 'widgets/upcoming.birthday' );
	}

	/**
	 * Get list of upcoming birhtdays
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUpcomingBirthdays( $key , $userId )
	{
		$db		= Foundry::db();
		$sql 	= $db->sql();

		$query = 'select a.' . $db->nameQuote( 'uid' ) . ', DATE_FORMAT( a.' . $db->nameQuote( 'data' ) . ', ' . $db->Quote( '%m%d' ) . ') as day,';
		$query .= ' DATE_FORMAT( a.' . $db->nameQuote( 'data' ) . ', ' . $db->Quote( '%M %d' ) . ') as displayday';
		$query .= ' from ' . $db->nameQuote( '#__social_fields_data' ) . ' as a';
		$query .= ' INNER JOIN ' . $db->nameQuote( '#__social_fields' ) . ' as b on a.' . $db->nameQuote( 'field_id' ) . ' = b.' . $db->nameQuote( 'id' );
		$query .= ' INNER JOIN (';
		$query .= ' 	select ' . $db->nameQuote( 'actor_id' ) . ' as ' . $db->nameQuote( 'user_id' ) . ' from ' . $db->nameQuote( '#__social_friends' ) . ' where ' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $userId ) . ' and ' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( '1' );
		$query .= ' 	 union ';
		$query .= ' 	select ' . $db->nameQuote( 'target_id' ) . ' as ' . $db->nameQuote( 'user_id' ) . ' from ' . $db->nameQuote( '#__social_friends' ) . ' where ' . $db->nameQuote( 'actor_id' ) . ' = '. $db->Quote( $userId ) . ' and ' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( '1' );
		$query .= ' 	) as x on a.uid = x.user_id';

		// @TODO: Here, it needs to fetch the field id based on the key.
		$query .= ' where b.' . $db->nameQuote( 'unique_key' ) . ' = ' . $db->Quote( $key );

		$query .= ' and a.' . $db->nameQuote( 'uid' ) . ' != ' . $db->Quote( $userId );

		$query .= ' and a.' . $db->nameQuote( 'data' ) . ' != ' . $db->Quote( '' );


		$query .= ' and ( DATE_FORMAT( a.' . $db->nameQuote( 'data' ) . ', ' . $db->Quote( '%m%d' ) . ') >= date_format( now(), ' . $db->Quote( '%m%d' ) . ' )';
		$query .= '		and DATE_FORMAT( a.' . $db->nameQuote( 'data' ) . ', ' . $db->Quote( '%m%d' ) . ' ) <= date_format( date_add( now(), INTERVAL 7 DAY ) , ' . $db->Quote( '%m%d' ) . ' ) )';
		$query .= ' order by a.' . $db->nameQuote( 'data' ) . ' asc';


		// echo $query;exit;

		$db->setQuery( $query );

		$result = $db->loadObjectList();

		return $result;
	}


	public function beautifyNames( $userIds )
	{
		$text = '';

		if( count( $userIds ) > 0 )
		{
			foreach( $userIds as $id )
			{
				$user = Foundry::user( $id );

				$name = '<a href="' . FRoute::profile( array( 'id' => $user->getAlias() ) ) . '">' . $user->getName() . '</a>';
				if( empty( $text ) )
				{
					$text = $name;
				}
				else
				{
					$text .= ', ' . $name;
				}
			}
		}

		return $text;
	}
}
