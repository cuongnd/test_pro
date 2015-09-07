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
 * Class for badges manipulation.
 *
 * @since 	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialBadges
{
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
	 * Logs a user action.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $badges 	= Foundry::badges();
	 * $my 		= Foundry::user();
	 *
	 * $badges->log( 'com_easyblog' , 'blog.create' , $my->id , 'Created a new blog post Hello World');
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The unique extension name.
	 * @param	string		The unique command string.
	 */
	public function log( $extension , $command , $userId , $message )
	{
		// If user id is not provided we shouldn't log anything
		if( !$userId )
		{
			return false;
		}

		$badge 	= Foundry::table( 'Badge' );

		// Try to load the badge rule for this extension
		$state 	= $badge->load( array( 'extension' => $extension , 'command' => $command ) );

		// If the extension / command does not exist, quit this.
		if( !$state )
		{
			return false;
		}

		// Badge needs to be published.
		if( !$badge->state )
		{
			return false;
		}

		// Load badges model
		$model 			= Foundry::model( 'Badges' );

		// Check if the user reached the specified frequency already or not.
		$achieving 		= $model->hasReachedFrequency( $badge->id , $userId );
		$achieved 		= $model->hasAchieved( $badge->id , $userId );

		// If the frequency of the badge is only 1, the achieving will not return anything.
		if( $badge->frequency == 1 && !$achieved )
		{
			$achieving 	= true;
		}
		
		$log 			= Foundry::table( 'BadgeHistory' );
		$log->badge_id 	= $badge->id;
		$log->user_id	= $userId;
		$log->achieved 	= $achieving && !$achieved;

		// Try to store the history action.
		$state 			= $log->store();

		// Only add a badge for this user when they have never achieved it before.
		if( $achieving && !$achieved )
		{
			// Create the new badge maps
			$user 	= Foundry::user( $userId );
			$state	= $this->create( $badge , $user );

			// Only announce to the world when the badge is really achieved.
			if( $state )
			{
				// @notifications: Send a notification to the user when they achieved a badge.
				$this->sendNotification( $badge ,$user->id );

				// @stream: Log stream here that the user achieved a new badge.
				$this->addStream( $badge , $user->id );

				// Add points for the user when they achieve a badge.
				$points 	= Foundry::points();
				$points->assign( 'badges.achieve' , 'com_easysocial' , $user->id );
			}
		}

		if( !$state )
		{
			return false;
		}
	}

	/**
	 * Deletes a badge from a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id
	 * @param	int		The badge id.
	 * @return	bool	True on success false otherwise.
	 */
	public function remove( $badgeId , $userId = null )
	{
		// Load up the badge model
		$model 	= Foundry::model( 'Badges' );

		// Removes the history for this badge
		$state 	= $model->deleteHistory( $badgeId , $userId );

		// Removes the mapping for this badge
		$state 	= $model->deleteAssociations( $badgeId , $userId );

		return true;
	}

	/**
	 * Allows caller to manually create a badge for a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableBadge	The badge object.
	 * @param	SocialUser			The user object.
	 * @param	string				A custom message that will be tied to the badge. (Optional)
	 * @param	string				A custom achieved date
	 * @return
	 */
	public function create( SocialTableBadge $badge , SocialUser $user , $customMessage = '' , $achieved = '' )
	{
		// Create the new badge maps
		$mapping 					= Foundry::table( 'BadgeMap' );
		$mapping->badge_id 			= $badge->id;
		$mapping->user_id 			= $user->id;
		$mapping->custom_message	= (string) $customMessage;

		if( !empty( $achieved ) )
		{
			$date 	= Foundry::date( $achieved );

			$mapping->created 	= $date->toSql();
		}

		$state 		= $mapping->store();

		if( !$state )
		{
			return false;
		}

		return true;
	}

	/**
	 * Responsible to create a new stream item when user unlocked a badge.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function addStream( SocialTableBadge $badge , $userId )
	{
		// Add activity logging when a friend connection has been made.
		// Activity logging.
		$stream				= Foundry::stream();
		$streamTemplate		= $stream->getTemplate();

		// Set the actor.
		$streamTemplate->setActor( $userId , SOCIAL_TYPE_USER );

		// Set the context.
		$streamTemplate->setContext( $badge->id , SOCIAL_TYPE_BADGES );

		// Set the verb.
		$streamTemplate->setVerb( 'unlocked' );

		// set the ispublic
		$streamTemplate->setPublicStream( 'core.view' );

		// Create the stream data.
		$stream->add( $streamTemplate );
	}

	/**
	 * Responsible to send notification to the user when they achieved a badge
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function sendNotification( SocialTableBadge $badge , $userId )
	{
		// We need the language file from the front end
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		// We want to send a notification to the user who earned the badge
		$recipient 	= array( $userId );

		// Add notification to the requester that the user accepted his friend request.
		$systemOptions		= array(
										// The unique node id here is the #__social_friend id.
										'uid'		=> $badge->id,
										'title'		=> JText::sprintf( 'COM_EASYSOCIAL_BADGES_NOTIFICATIONS_SYSTEM_ACHIEVED_BADGE' , $badge->get( 'title' )  ),
										'type'		=> SOCIAL_TYPE_BADGES,
										'url'		=> FRoute::badges( array( 'id' => $badge->getAlias() , 'layout' => 'item' , 'external' => true ) ),
										'image'		=> $badge->getAvatar()
									);

		$params 	= array(
								'badgeTitle'		=> $badge->get( 'title' ),
								'badgePermalink'	=> $badge->getPermalink( false , true ),
								'badgeAvatar'		=> $badge->getAvatar(),
								'badgeDescription'	=> $badge->get( 'description' )
							);
		// Email template
		$emailOptions 		= array(
										'title'		=> JText::sprintf( 'COM_EASYSOCIAL_EMAILS_UNLOCKED_BADGE_TITLE' , $badge->get( 'title' ) ),
										'template'	=> 'site/badges/unlocked',
										'params'	=> $params
									);

		// Send notifications to the receivers when they unlock the badge
		Foundry::notify( 'badges.unlocked' , $recipient , $emailOptions , $systemOptions );

	}
}
