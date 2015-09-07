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
require_once JPATH_ROOT . '/components/com_easydiscuss/helpers/helper.php' ;

class DiscussModel extends EasySocialModel
{
	/**
	 * Retrieve user statistics for the past 7 days
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getUserStats( $userId )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();
		$creations	= array();
		$replies 	= array();
		$curDate 	= Foundry::date();

		for( $i = 0 ; $i < 7; $i++ )
		{
			$obj = new stdClass();

			if( $i == 0 )
			{
				$dates[]		= $curDate->toMySQL();
			}
			else
			{
				$unixdate 		= $curDate->toUnix();
				$new_unixdate 	= $unixdate - ( $i * 86400);
				$newdate  		= Foundry::date( $new_unixdate );

				$dates[] 	= $newdate->toMySQL();
			}
		}

		// Reverse the dates
		$dates 			= array_reverse( $dates );

		foreach( $dates as $date )
		{
			$date		= Foundry::date( $date )->format( 'Y-m-d' );
			$query 		= array();
			$query[] 	= 'SELECT COUNT(1) FROM `#__discuss_posts`'; 
			$query[]	= 'WHERE `user_id` = ' . $db->Quote( $userId );
			$query[]	= 'AND `parent_id` = ' . $db->Quote( 0 );
			$query[]	= 'AND DATE_FORMAT( `created`, GET_FORMAT( DATE , "ISO" ) ) = ' . $db->Quote( $date );

			$query 		= implode( ' ' , $query );
			$sql 		= $sql->raw( $query );
			$db->setQuery( $sql );

			$count 		= $db->loadResult();

			$creations[]	= $count;
		}

		foreach( $dates as $date )
		{
			$date		= Foundry::date( $date )->format( 'Y-m-d' );
			$query 		= array();
			$query[] 	= 'SELECT COUNT(1) FROM `#__discuss_posts`'; 
			$query[]	= 'WHERE `user_id` = ' . $db->Quote( $userId );
			$query[]	= 'AND `parent_id` != ' . $db->Quote( 0 );
			$query[]	= 'AND DATE_FORMAT( `created`, GET_FORMAT( DATE , "ISO" ) ) = ' . $db->Quote( $date );

			$query 		= implode( ' ' , $query );
			$sql 		= $sql->raw( $query );
			$db->setQuery( $sql );

			$count 		= $db->loadResult();

			$replies[]	= $count;
		}

		$obj 			= new stdClass();
		$obj->creations	= $creations;
		$obj->replies	= $replies;
		
		return $obj;
	}

	/**
	 * Gets a total votes of a user
	 *
	 * @since	3.0
	 * @param	int 	The unique user id.
	 * @return	Array	An array of voter objects.
	 */
	public function getTotalUserVotes( $userId )
	{
		$db 	= DiscussHelper::getDBO();
		$query 	= 'SELECT count(*) AS `totalVotes` '
				. 'FROM ' . $db->nameQuote( '#__discuss_votes' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $userId );
		$db->setQuery( $query );

		$result 	= $db->loadObject();

		return $result->totalVotes;
	}

	/**
	 * Gets a total replies count of a user
	 *
	 * @since	3.0
	 * @param	int 	The unique user id.
	 * @return	Array	An array of voter objects.
	 */
	public function getTotalUserReplies( $userId )
	{
		$db 	= DiscussHelper::getDBO();
		$query 	= 'SELECT count(*) AS `totalReplies` '
				. ' FROM ' . $db->nameQuote( '#__discuss_posts' )
				. ' WHERE ' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $userId )
				. ' AND ' . $db->nameQuote( 'parent_id' ) . '!=' . $db->Quote( 0 )
				. ' AND ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( 1 );

		$db->setQuery( $query );
		$result 	= $db->loadObject();

		return $result->totalReplies;
	}

}
