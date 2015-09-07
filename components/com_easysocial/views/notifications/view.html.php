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

// Necessary to import the custom view.
Foundry::import( 'site:/views/views' );

class EasySocialViewNotifications extends EasySocialSiteView
{
	/**
	 * Displays a list of notifications a user has.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function display( $tpl = null )
	{
		// Unauthorized users should not be allowed to access this page.
		Foundry::requireLogin();

		// Get the current logged in user.
		$user 		= Foundry::user();

		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALL_NOTIFICATIONS' ) );

		// Set breadcrumbs
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALL_NOTIFICATIONS' ) );

		$config = Foundry::config();
		$paginationLimit = $config->get('notifications.general.pagination');

		// Get notifications model.
		$options 	= array( 'target_id'	=> $user->id ,
							 'target_type'	=> SOCIAL_TYPE_USER ,
							 'group' 		=> SOCIAL_NOTIFICATION_GROUP_ITEMS,
							 'limit' 		=> $paginationLimit );

		$lib		= Foundry::notification();
		$items 		= $lib->getItems( $options );

		$this->set( 'items'			, $items );
		$this->set( 'startlimit'	, $paginationLimit );


		parent::display( 'site/notifications/default' );
	}

	/**
	 * Redirects a notification item to the intended location
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function route()
	{
		// The user needs to be logged in to access notifications
		Foundry::requireLogin();

		$id 	= JRequest::getInt( 'id' );

		$table 	= Foundry::table( 'Notification' );
		$table->load( $id );

		if( !$id || !$table->id )
		{
			Foundry::info()->set( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );

			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Check if the user is allowed to view this notification item.
		$my 	= Foundry::user();

		if( $table->target_id != $my->id )
		{
			Foundry::info()->set( JText::_( 'COM_EASYSOCIAL_NOTIFICATIONS_NOT_ALLOWED' ) , SOCIAL_MSG_ERROR );

			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Mark the notification item as read
		$table->markAsRead();

		// Ensure that all &amp; are replaced with &
		$url	= str_ireplace( '&amp;' , '&' , $table->url );

		$this->redirect( FRoute::_( $url , false ) );
		$this->close();
	}
}
