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

class EasySocialControllerLikes extends EasySocialController
{

	/**
	 * display the remainder's name.
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return	string
	 */

	public function showOthers()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// User needs to be logged in.
		Foundry::requireLogin();

		$uid 	= JRequest::getInt( 'uid' );
		$type 	= JRequest::getVar( 'type' );
		$group 	= JRequest::getVar( 'group', SOCIAL_APPS_GROUP_USER );


		$excludeIds = JRequest::getVar( 'exclude' );

		// Get the view.
		$view 	= Foundry::view( 'Likes' , false );

		$model = Foundry::model( 'Likes' );

		$excludeIds	= explode( ',', $excludeIds );

		$key = $type . '.' . $group;
		$userIds = $model->getLikerIds( $uid, $key, $excludeIds );

		$users	= array();

		if( $userIds && count( $userIds ) > 0 )
		{
			$users = Foundry::user( $userIds );
		}

		return $view->call( __FUNCTION__ , $users );
	}



	/**
	 * Toggle the likes on an object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return	string
	 */
	public function toggle()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// User needs to be logged in.
		Foundry::requireLogin();

		// Get the stream id.
		$id 	= JRequest::getInt( 'id' );
		$type 	= JRequest::getString( 'type' );
		$group 	= JRequest::getString( 'group', SOCIAL_APPS_GROUP_USER );

		// Get the view.
		$view 	= $this->getCurrentView();

		// If id is invalid, throw an error.
		if( !$id || !$type)
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Likes: Unable to process because id or element provided is invalid.' );

			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get current logged in user.
		$my 		= Foundry::user();

		// Load likes library.
		$model 		= Foundry::model( 'Likes' );

		// Build the key for likes
		$key		= $type . '.' . $group;

		// Determine if user has liked this item previously.
		$hasLiked	= $model->hasLiked( $id , $key, $my->id );

		// If user had already liked this item, we need to unlike it.
		if( $hasLiked )
		{
			$state 	= $model->unlike( $id , $key , $my->id );
		}
		else
		{
			$state 	= $model->like( $id , $key , $my->id );
		}

		// The current action
		$verb 	= $hasLiked ? 'unlike' : 'like';

		// If there's an error, log this down here.
		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'LIKES: Unable to ' . $verb . ' the stream item because of the error message ' . $model->getError() );

			// Set the view with error
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $verb, $id , $type, $group );
		}

		return $view->call( __FUNCTION__ , $verb , $id , $type, $group );
	}
}
