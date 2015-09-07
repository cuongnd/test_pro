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

class EasySocialControllerStream extends EasySocialController
{

	public function getCurrentDate()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// In order to access the dashboard apps, user must be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= Foundry::view( 'Stream' , false );

		$date = Foundry::date()->toMySQL();

		return $view->call( __FUNCTION__, $date );
	}

	public function getUpdates()
	{

		// Check for request forgeries.
		Foundry::checkToken();

		// In order to access the dashboard apps, user must be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= Foundry::view( 'Stream' , false );

		// Get the type of the stream to load.
		$type 		= JRequest::getWord( 'type' , 'me' );
		$uid 		= JRequest::getVar( 'id', '');
		$source 	= JRequest::getWord( 'source' , '' );

		// next start date
		$currentdate 	= JRequest::getVar( 'currentdate' , '' );

		$streamType = ( $type == 'following' ) ? 'follow' : SOCIAL_TYPE_USER;

		$userId = '';
		$listId = '';

		if( $source == 'dashboard' )
		{
			if( $type == 'me' && !empty( $uid ) )
			{
				$listId = $uid;
			}
		}
		else if( $source == 'profile' )
		{
			$userId = $uid;
		}

		// // Get the stream
		$stream		= Foundry::stream();

		if( $type == 'everyone' )
		{
			$stream->getPublicStream( SOCIAL_STREAM_GUEST_LIMIT, 0 );
		}
		else
		{
			$options 	= array(
									'userId' 	=> $userId,
									'listId' 	=> $listId,
									'context' 	=> SOCIAL_STREAM_CONTEXT_TYPE_ALL,
									'type' 		=> $streamType,
									'limitStart' => $currentdate,
									'direction' => 'later'
								);

			$stream->get( $options );
		}

		return $view->call( __FUNCTION__, $stream );
	}

	public function checkUpdates()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get the current view.
		$view 	= Foundry::view( 'Stream' , false );

		// Get the type of the stream to load.
		$type 		= JRequest::getVar( 'type', 'me' );
		$source 	= JRequest::getVar( 'source' );
		$uid 		= JRequest::getVar( 'id', '');
		$exclude 	= JRequest::getVar( 'exclude', '' );

		// next start date
		$currentdate 	= JRequest::getVar( 'currentdate' , '' );

		$model = Foundry::model( 'Stream' );
		$data  = $model->getUpdateCount( $source, $currentdate, $type, $uid, $exclude );

		return $view->call( __FUNCTION__, $data, $source, $type, $uid, $currentdate );
	}


	/**
	 * retrieve more stream items. ( used in pagination )
	 *
	 * @since 	1.0
	 * @access 	public
	 * return   StreamItem object
	 *
	 */
	public function loadmoreGuest()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// In order to access the dashboard apps, user must be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= Foundry::view( 'Stream' , false );


		// next start date
		$startlimit 	= JRequest::getVar( 'startlimit' , 0 );


		// Get the stream
		$stream 	= Foundry::stream();
		$stream->getPublicStream( SOCIAL_STREAM_GUEST_LIMIT, $startlimit );

		return $view->call( __FUNCTION__ , $stream );

	}


	/**
	 * retrieve more stream items. ( used in pagination )
	 *
	 * @since 	1.0
	 * @access 	public
	 * return   StreamItem object
	 *
	 */
	public function loadmore()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// In order to access the dashboard apps, user must be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= Foundry::view( 'Stream' , false );

		// Get the type of the stream to load.
		$type 	= JRequest::getWord( 'type' , 'me' );

		// next start date
		$startdate 	= JRequest::getVar( 'startdate' , '' );

		// next end date
		$enddate 	= JRequest::getVar( 'enddate' , '' );


		// Get the stream
		$stream	= Foundry::stream();


		if( !$type )
		{
			$view->setMessage( JText::_( 'Invalid feed type provided.' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $stream );
		}

		// Get feeds from user's friend list.
		if( $type == 'list' )
		{
			// The id of the friend list.
			$id 	= JRequest::getInt( 'id', 0 );

			// @TODO: We need to filter stream items from friends in specific friend list.
			if( !empty( $id ) )
			{
				$listsModel 	= Foundry::model( 'Lists' );
				$memberIds		= $listsModel->getMembers( $id, true);

				if( $memberIds )
				{
					$stream->get(
									array(
										'listId' 	=> $id,
										'context' 	=> SOCIAL_STREAM_CONTEXT_TYPE_ALL,
										'type' 		=> SOCIAL_TYPE_USER,
										'limitStart' => $startdate,
										'limitEnd' 	=> $enddate
										)
								);
				}
			}
		}

		if( $type == 'following' )
		{
			$stream->get(
							array(
								'context' 	=> SOCIAL_STREAM_CONTEXT_TYPE_ALL,
								'type' 		=> 'follow',
								'limitStart' => $startdate,
								'limitEnd' 	=> $enddate
								)
						);
		}

		// Get feeds from everyone
		if( $type == 'everyone' )
		{
			// $stream->getPublicStream( SOCIAL_STREAM_GUEST_LIMIT, 0 );
			$stream->get( array(
								'guest' 	=> true,
								'limitStart' => $startdate,
								'limitEnd' 	=> $enddate
							)
						);
		}

		// Get feeds from the current user and friends only.
		if( $type == 'me' )
		{
			$uid = JRequest::getVar( 'id', '');
			$stream->get(
							array(
								'userId' 	=> $uid,
								'context' 	=> SOCIAL_STREAM_CONTEXT_TYPE_ALL,
								'type' 		=> SOCIAL_TYPE_USER,
								'limitStart' => $startdate,
								'limitEnd' 	=> $enddate
								)
						);

		}

		// $nextStartDate = $stream->getNextStartDate();

		return $view->call( __FUNCTION__ , $stream );

	}

	/**
	 * Hides a feeds from a context ( app ).
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function hideapp()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		// Ensure that the user is logged in before allowing such actions.
		Foundry::requireLogin();

		// Get the stream's context.
		$context 	= JRequest::getVar( 'context' );

		// Get the view.
		$view 	= Foundry::view( 'Stream' , false );

		// If id is invalid, throw an error.
		if( !$context )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'STREAM: Unable to hide stream because app provided is invalid or not found.' );

			$view->setError( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_APP' ) );
			return $view->call( __FUNCTION__ );
		}

		// Get the current logged in user.
		$my 	= Foundry::user();

		// The user needs to be at least logged in to perform this action.
		if( !$my->id )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'STREAM: Unable to hide app\'s feeds because user is not logged in.' );

			$view->setError( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_APP' ) );
			return $view->call( __FUNCTION__ );
		}

		// Get the model
		$model 	= Foundry::model( 'Stream' );
		$state	= $model->hideapp( $context , $my->id );

		// If there's an error, log this down.
		if( !$state )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'STREAM: Unable to hide stream because model returned the error, ' . $model->getError() );

			$view->setError( $model->getError() );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Hides a stream item.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unhideapp()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		Foundry::requireLogin();

		$context		= JRequest::getVar( 'context' );
		$my             = Foundry::user();

		$view 	= Foundry::view( 'Stream' , false );


		// Get the view.
		$view 	= Foundry::view( 'Stream' , false );

		if( empty( $context ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'STREAM: Unable to unhide stream because app provided is invalid or not found.' );

			$view->setErrors( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_APP' ) );

			return $view->call( __FUNCTION__ );
		}


		$model 	= Foundry::model( 'Stream' );
		$state 	= $model->unhideapp( $context, $my->id);

		if(! $state )
		{
			$view->setErrors( JText::_( 'COM_EASYSOCIAL_STREAM_FAILED_UNHIDE' ) );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}


	/**
	 * Delete a stream item.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function delete()
	{

		// Check for request forgeries!
		Foundry::checkToken();

		// Ensure that the user is logged in before allowing such actions.
		Foundry::requireLogin();

		// Get the stream's uid.
		$id 	= JRequest::getInt( 'id' );

		// Get the view.
		$view 	= $this->getCurrentView();

		// Get logged in user
		$my 	= Foundry::user();

		$access = $my->getAccess();

		//check if this user has the right to delete the steam or not.
		if( !$my->isSiteAdmin() && !$access->allowed( 'stream.delete', false ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_STREAM_NOT_ALLOWED_TO_HIDE' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Load the stream item.
		$item 	= Foundry::table( 'Stream' );
		$item->load( $id );

		// If id is invalid, throw an error.
		if( !$id || !$item->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$state = $item->delete();

		// If there's an error, log this down.
		if( !$state )
		{
			$view->setMessage( $model->getError() );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );


	}


	/**
	 * Hides a stream item.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function hide()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		// Ensure that the user is logged in before allowing such actions.
		Foundry::requireLogin();

		// Get the stream's uid.
		$id 	= JRequest::getInt( 'id' );

		// Get the view.
		$view 	= $this->getCurrentView();

		// Get logged in user
		$my 	= Foundry::user();

		// Load the stream item.
		$item 	= Foundry::table( 'Stream' );
		$item->load( $id );

		// If id is invalid, throw an error.
		if( !$id || !$item->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return $view->call( __FUNCTION__ );
		}

		// Check if the user is allowed to hide this item
		if( !$item->hideable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_STREAM_NOT_ALLOWED_TO_HIDE' ) );
			return $view->call( __FUNCTION__ );
		}

		// Get the model
		$model 	= Foundry::model( 'Stream' );
		$state	= $model->hide( $id , $my->id );

		// If there's an error, log this down.
		if( !$state )
		{
			$view->setMessage( $model->getError() );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Unhide a stream item
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unhide()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// User needs to be logged in
		Foundry::requireLogin();

		$id				= JRequest::getVar( 'id' );
		$my             = Foundry::user();

		// Get the view.
		$view 		= $this->getCurrentView();

		// Load the stream item.
		$item 	= Foundry::table( 'Stream' );
		$item->load( $id );

		// Check for valid id
		if( !$id || !$item->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return $view->call( __FUNCTION__ );
		}

		// Check if the user is allowed to hide this item
		if( !$item->hideable() )
		{
			$view->setError( JText::_( 'COM_EASYSOCIAL_STREAM_NOT_ALLOWED_TO_HIDE' ) );
			return $view->call( __FUNCTION__ );
		}

		$model 	= Foundry::model( 'Stream' );
		$state 	= $model->unhide($id, $my->id);

		if(! $state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_STREAM_FAILED_UNHIDE' ) );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}
}
