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
 * Class for points manipulation.
 *
 * @since 	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialPoints
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 */
	public function __construct()
	{
	}

	/**
	 * Points factory.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	SocialPoints
	 */
	public static function getInstance()
	{
		static $instance = null;

		if( is_null( $instance ) )
		{
			$instance 	= new self();	
		}
		

		return $instance;
	}

	/**
	 * Updates the cache copy of the user's points.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id.
	 * @param	int		The total number of points
	 * @return	bool	True if success false otherwise.
	 */
	public function updateUserPoints( $userId , $points )
	{
		$user 			= Foundry::user( $userId );
		$user->addPoints( $points );

		return true;
	}

	/**
	 * Allows caller to assign a custom point
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id.
	 * @param	int		The number of points to insert.
	 * @param	string	Any custom message for this point assignment.
	 * @return	bool	True if success, false otherwise.
	 */
	public function assignCustom( $userId , $points , $message = '' )
	{
		// Add history.
		$history 				= Foundry::table( 'PointsHistory' );
		$history->user_id 		= $userId;
		$history->points 		= $points;
		$history->state 		= SOCIAL_STATE_PUBLISHED;
		$history->message 		= $message;
		$state 	= $history->store();

		if( $state )
		{
			$this->updateUserPoints( $userId , $points );
		}
		return $state;
	}

	/**
	 * Assign points to a specific user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The command to be executed. Refer to `#__social_points_commands`.`command`
	 * @param	string	The extension or app name. Refer to `#__social_points_commands`.`extension`
	 * @param	int 	The target user's id.
	 * @return	bool	True if point is given. False otherwise.
	 */
	public function assign( $command , $extension , $userId )
	{
		$config 	= Foundry::config();

		// Check if points system is enabled.
		if( !$config->get( 'points.enabled' ) )
		{
			return false;
		}

		// Retrieve the points table.
		$points 	= Foundry::table( 'Points' );

		$state 		= $points->load( array( 'command' => $command , 'extension' => $extension ) );

		// Check the command and extension and see if it is valid.
		if( !$state )
		{
			// If it doesn't exist, just throw an error.
			Foundry::logError( __FILE__ , __LINE__ , 'POINTS: Command and extension ' . $command . ',' . $extension . ' not found.' );
			return false;
		}

		// Check the rule and see if it is published.
		if( $points->state != SOCIAL_STATE_PUBLISHED )
		{
			// If points is unpublished, throw an error.
			Foundry::logError( __FILE__ , __LINE__ , 'POINTS: Command and extension ' . $command . ',' . $extension . ' unpublished.' );

			return false;
		}

		// @TODO: Check points threshold.
		if( $points->threshold )
		{

		}

		// @TODO: Check the interval to see if the user has achieved this for how many times.
		if( $points->interval != SOCIAL_POINTS_EVERY_TIME )
		{

		}

		// @TODO: Customizable point system where only users from specific profile type may achieve this point.

		// Add history.
		$history 				= Foundry::table( 'PointsHistory' );
		$history->points_id 	= $points->id;
		$history->user_id 		= $userId;
		$history->points 		= $points->points;
		$history->state 		= SOCIAL_STATE_PUBLISHED;
		$history->store();

		$this->updateUserPoints( $userId , $points->points );
		return true;
	}

}
