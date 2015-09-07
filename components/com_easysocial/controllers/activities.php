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

Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerActivities extends EasySocialController
{
	/**
	 * Hide / unhide an activity log item.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function toggle()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		// Ensure that the user is logged in before allowing such actions.
		Foundry::requireLogin();

		// get required form post variable
		$id 	= JRequest::getInt( 'id' );

		$cState = JRequest::getInt( 'curState' );

		// Get the view.
		$view 	= $this->getCurrentView();

		// Load the stream item
		$item	= Foundry::table( 'StreamItem' );
		$item->load( $id );

		// If id is invalid, throw an error.
		if( !$id || !$item->id )
		{
			$view->setError( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return $view->call( __FUNCTION__ , $id, $cState );
		}

		// Check if the current user is allowed to delete this stream item
		if( !$item->isOwner() )
		{
			$view->setError( JText::_( 'COM_EASYSOCIAL_ACTIVITIES_NOT_OWNER_OF_ITEM' ) );
			return $view->call( __FUNCTION__ );
		}

		// Get the current logged in user.
		$my 	= Foundry::user();

		// The user needs to be at least logged in to perform this action.
		if( !$my->id )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Activity Log: Unable to hide item because user is not logged in.' );

			$view->setError( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return $view->call( __FUNCTION__ , $id, $cState );
		}

		// Get the model
		$model 	= Foundry::model( 'Activities' );
		$state	= $model->toggle( $id , $my->id );

		// If there's an error, log this down.
		if( !$state )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Activity Log: Unable to hide item because model returned the error, ' . $model->getError() );

			$view->setError( $model->getError() );

			return $view->call( __FUNCTION__ , $id, $cState );
		}

		return $view->call( __FUNCTION__ , $id, $cState );
	}

	/**
	 * Allows caller to delete an activity item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete()
	{
		Foundry::checkToken();
		Foundry::requireLogin();

		// get required form post variable
		$id 	= JRequest::getInt( 'id' );

		// Get the view.
		$view 	= $this->getCurrentView();

		// Load the stream item
		$item	= Foundry::table( 'StreamItem' );
		$item->load( $id );

		// If id is invalid, throw an error.
		if( !$id || !$item->id )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Activity Log: Unable to delete item because id provided is invalid.' );

			$view->setError( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return $view->call( __FUNCTION__ );
		}

		// Check if the current user is allowed to delete this stream item
		if( !$item->deleteable() )
		{
			$view->setError( JText::_( 'COM_EASYSOCIAL_ACTIVITIES_NOT_ALLOWED_TO_DELETE_ITEM' ) );
			return $view->call( __FUNCTION__ );
		}

		$model = Foundry::model( 'Activities' );
		$state = $model->delete( $id );

		if(! $state )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Activity Log: Unable to delete item because model returned the error, ' . $model->getError() );

			$view->setError( $model->getError() );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}


	/**
	 * get activity logs.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getActivities()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		// Ensure that the user is logged in before allowing such actions.
		Foundry::requireLogin();

		$filterType = JRequest::getVar( 'type', 'all' );
		$isloadmore = JRequest::getVar( 'loadmore', '' );

		$context   	= SOCIAL_STREAM_CONTEXT_TYPE_ALL;

		if( $filterType != 'all' && $filterType != 'hidden' && $filterType != 'hiddenapp' )
		{
			$context    = $filterType;
			$filterType = 'all';
		}

		// Get the view.
		$view 	= Foundry::view( 'Activities' , false );

		if( $filterType == 'hiddenapp' )
		{
			return $this->getHiddenApps();
		}

		$my 		= Foundry::user();

		$stream		= Foundry::stream();
		$activities = $stream->getActivityLogs(
										array( 'uId' => $my->id,
											   'context' => $context,
											   'filter' => $filterType )
										);

		$nextlimit = $stream->getActivityNextLimit();

		return $view->call( __FUNCTION__, $filterType, $activities, $nextlimit, $isloadmore );

	}

	public function getHiddenApps()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		Foundry::requireLogin();

		// Get the view.
		$view 	= Foundry::view( 'Activities' , false );

		$my    = Foundry::user();
		$model = Foundry::model( 'Activities' );

		$data  = $model->getHiddenApps( $my->id );

		return $view->call( __FUNCTION__, $data );
	}

	public function unhideapp()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		Foundry::requireLogin();

		$context	= JRequest::getVar( 'context' );
		$id			= JRequest::getInt( 'id' );

		// Get the view.
		$view 	= Foundry::view( 'Activities' , false );

		$model = Foundry::model( 'Activities' );
		$state  = $model->unhideapp( $context, $id );

		if(! $state )
		{
			$view->setErrors( JText::_( 'COM_EASYSOCIAL_STREAM_FAILED_UNHIDE' ) );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

}
