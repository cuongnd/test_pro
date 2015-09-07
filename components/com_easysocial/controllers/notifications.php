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

// Import parent controller
Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerNotifications extends EasySocialController
{

	/**
	 * Checks for new friend requests
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function friendsCounter()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		$view 	= Foundry::view( 'Notifications' , false );
		$my 	= Foundry::user();

		$model 	= Foundry::model( 'Friends' );
		$total 	= $model->getTotalRequests( $my->id );

		return $view->call( __FUNCTION__ , $total );
	}

	/**
	 * Allows the caller to set the state of the notification item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setAllState()
	{
		Foundry::checkToken();

		Foundry::requireLogin();

		$view 	= $this->getCurrentView();
		$my 	= Foundry::user();

		$state 	= JRequest::getVar( 'state', '' );

		if( ! $state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_INVALID_STATE_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}


		$model 	= Foundry::model( 'Notifications' );

		if( $state == 'read' )
		{
			// mark all notification as read
			$state = $model->setAllState( SOCIAL_NOTIFICATION_STATE_READ );

			if( ! $state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_FAILED_TO_MARK_AS_READ' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}
		else if( $state == 'clear' )
		{
			// remove all notification from this user.
			$state = $model->setAllState( 'clear' );

			if( ! $state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_FAILED_TO_REMOVE' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}


		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allows the caller to set the state of the notification item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setState()
	{
		Foundry::checkToken();

		Foundry::requireLogin();

		$view 	= $this->getCurrentView();
		$my 	= Foundry::user();

		$state 	= JRequest::getVar( 'state' );
		$id 	= JRequest::getVar( 'id' );

		$notification 	= Foundry::table( 'Notification' );
		$notification->load( $id );

		if( !$id || !$notification->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		if( $notification->target_id != $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_NOT_ALLOWED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$stateValue 	= SOCIAL_NOTIFICATION_STATE_READ;

		if( $state == 'unread' )
		{
			$stateValue 	= SOCIAL_NOTIFICATION_STATE_UNREAD;
		}

		if( $state == 'hidden' )
		{
			$stateValue 	= SOCIAL_NOTIFICATION_STATE_HIDDEN;
		}


		if( $state == 'clear' )
		{
			// remove all notification from this user.
			$state = $notification->delete();

			if( ! $state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_FAILED_TO_REMOVE' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}
		else
		{
			$notification->state	= $stateValue;

			$notification->store();
		}


		return $view->call( __FUNCTION__ );
	}

	/**
	 * Checks for new friend requests
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getConversationCounter()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current user.
		$my 	= Foundry::user();

		$model 	= Foundry::model( 'Conversations' );
		$total 	= $model->getNewCount( $my->id , 'inbox' );

		return $view->call( __FUNCTION__ , $total );
	}

	/**
	 * Checks for new friend requests
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getSystemCounter()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		$view 	= Foundry::view( 'Notifications' , false );
		$my 	= Foundry::user();

		$model 	= Foundry::model( 'Notifications' );
		$total 	= $model->getCount( array( 'unread' => true , 'target' => array( 'id' => $my->id , 'type' => SOCIAL_TYPE_USER ) ) );

		return $view->call( __FUNCTION__ , $total );
	}

	/**
	 * Retrieves a list of new system notifications for the user.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getSystemItems()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		// Get current view.
		$view 	= $this->getCurrentView();

		$my 	= Foundry::user();

		$notification 	= Foundry::notification();

		$options 		= array( 'target_id' => $my->id , 'target_type' => SOCIAL_TYPE_USER , 'unread' => true );
		$items 			= $notification->getItems( $options );

		return $view->call( __FUNCTION__ , $items );
	}

	/**
	 * Retrieves a list of conversation notifications for the user.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getConversationItems()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user
		$my 	= Foundry::user();

		$usemax 	= JRequest::getVar( 'usemax', '' );
		$filter 	= JRequest::getWord( 'filter', '' );
		$maxlimit	= 0;

		if( $usemax )
		{
			$config 	= Foundry::config();
			$maxlimit 	= $config->get( 'conversations.pagination.toolbarlimit', 5 );
		}

		// Get the conversations model
		$model 			= Foundry::model( 'Conversations' );

		// We want to sort items by latest first
		$options 		= array( 'sorting' => 'lastreplied', 'maxlimit' => $maxlimit );

		if( $filter )
		{
			$options['filter'] = $filter;
		}

		// Get conversation items.
		$conversations	= $model->getConversations( $my->id , $options );


		return $view->call( __FUNCTION__ , $conversations );
	}

	/**
	 * Checks for new friend requests
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function friendsRequests()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		$view 	= $this->getCurrentView();
		$my 	= Foundry::user();

		$model 		= Foundry::model( 'Friends' );
		$pending 	= $model->getPendingRequests( $my->id );

		return $view->call( __FUNCTION__ , $pending );
	}



	public function loadmore()
	{

		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that user is logged in
		Foundry::requireLogin();

		$view 	= Foundry::view( 'Notifications' , false );
		$user 	= Foundry::user();

		$config = Foundry::config();
		$paginationLimit = $config->get('notifications.general.pagination');

		$startlimit = JRequest::getInt( 'startlimit' );

		// Get notifications model.
		$options 	= array( 'target_id' => $user->id ,
							 'target_type' => SOCIAL_TYPE_USER ,
							 'group' => SOCIAL_NOTIFICATION_GROUP_ITEMS,
							 'limit' => $paginationLimit,
							 'startlimit' => $startlimit );

		$lib		= Foundry::notification();
		$items 		= $lib->getItems( $options );

		$groupCnt 	= count( $items );
		$recurvCnt 	= count( $items , COUNT_RECURSIVE );
		$actualCnt 	= $recurvCnt - $groupCnt;

		$nextlimit  = $startlimit + $paginationLimit;
		if( $actualCnt < $paginationLimit )
		{
			$nextlimit  = -1;
		}

		return $view->call( __FUNCTION__ , $items, $nextlimit );

	}



}
