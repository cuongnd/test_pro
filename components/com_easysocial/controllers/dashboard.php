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

// Import main controller
Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerDashboard extends EasySocialController
{

	/**
	 * Retrieves the stream contents.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getStream()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// In order to access the dashboard apps, user must be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= Foundry::view( 'Dashboard' , false );

		// Get the type of the stream to load.
		$type 	= JRequest::getWord( 'type' , '' );

		// Get the stream
		$stream	= Foundry::stream();

		if( !$type )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_STREAM_INVALID_FEED_TYPE' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $stream , $type );
		}

		// Get feeds from user's friend list.
		if( $type == 'list' )
		{
			// The id of the friend list.
			$id 	= JRequest::getInt( 'id', 0 );

			$list 	= Foundry::table( 'List' );
			$list->load( $id );

			if( !$id || !$list->id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_STREAM_INVALID_LIST_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ , $stream , $type );
			}

			// Get list of users from this list.
			$friends 	= $list->getMembers();

			if( $friends )
			{
				$stream->get( array( 'listId' => $id ) );
			}
			else
			{
				$stream->filter 	= 'list';
			}
		}

		if( $type == 'following' )
		{
			$stream->get( array(
								'context' 	=> SOCIAL_STREAM_CONTEXT_TYPE_ALL,
								'type' 		=> 'follow'
							)
						);
		}

		// Get feeds from everyone
		if( $type == 'everyone' )
		{
			// $stream->getPublicStream( SOCIAL_STREAM_GUEST_LIMIT, 0 );
			$stream->get( array(
								'guest' 	=> true
							)
						);
		}

		// Get feeds from the current user and friends only.
		if( $type == 'me' )
		{
			$stream->get();
		}

		// $nextStartDate = $stream->getNextStartDate();

		return $view->call( __FUNCTION__ , $stream , $type );
	}

	/**
	 * Retrieves the dashboard contents.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getAppContents()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// In order to access the dashboard apps, user must be logged in.
		Foundry::requireLogin();

		// Get the app id.
		$appId 		= JRequest::getInt( 'appId' );

		// Load application.
		$app 	= Foundry::table( 'App' );
		$state 	= $app->load( $appId );

		// Get the view.
		$view 	= $this->getCurrentView();

		// If application id is not valid, throw an error.
		if( !$appId || !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_APPS_INVALID_APP_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $app );
		}

		$my 	= Foundry::user();


		// Check if the user has access to this app or not.
		// If application id is not valid, throw an error.
		if( !$app->accessible( $my->id ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_APPS_PLEASE_INSTALL_APP_FIRST' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $app );
		}

		return $view->call( __FUNCTION__ , $app );
	}
}
