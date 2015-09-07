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
 * Dashboard view for the calendar app.
 *
 * @since	1.0
 * @access	public
 */
class CalendarViewItem extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null, $docType = null )
	{
		// Require user to be logged in
		Foundry::requireLogin();

		$id 		= JRequest::getVar( 'schedule_id' );

		// Get the user that's being accessed.
		$user 		= Foundry::user( $userId );

		$calendar	= $this->getTable( 'Calendar' );
		$calendar->load( $id );

		if( !$calendar->id || !$id )
		{
			Foundry::info()->set( false , JText::_( 'APP_CALENDAR_CANVAS_INVALID_SCHEDULE_ID' ) , SOCIAL_MSG_ERROR );

			return $this->redirect( Foundry::profile( array( 'id' => $user->getAlias() ) , false ) );
		}

		Foundry::page()->title( $calendar->title );

		// Render the comments and likes
		$likes 			= Foundry::likes();
		$likes->get( $id , 'calendar' );

		// Apply comments on the stream
		$comments			= Foundry::comments( $id , 'calendar' , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::albums( array( 'layout' => 'item', 'id' => $id ) ) ) );

		$this->set( 'likes'		, $likes );
		$this->set( 'comments'	, $comments );
		$this->set( 'calendar'	, $calendar );
		$this->set( 'user'		, $user );

		echo parent::display( 'canvas/item/default' );
	}
}
