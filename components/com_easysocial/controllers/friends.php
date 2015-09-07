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

class EasySocialControllerFriends extends EasySocialController
{
	protected $app	= null;

	/**
	 * Gets a list of users from a particular list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getListFriends()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in
		Foundry::requireLogin();

		// Get current view.
		$view 	= Foundry::view( 'Friends' , false );

		// Check if friends lists are enabled.
		$config 	= Foundry::config();
		if( !$config->get( 'friends.list.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get current id.
		$id 	= JRequest::getInt( 'id' , 0 );

		// Try to load the list.
		$list 	= Foundry::table( 'List' );
		$state 	= $list->load( $id );

		if( !$id || !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'FRIENDS: Invalid id provided to retrieve list users.' );
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_INVALID_LIST_ID' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $list , array() );
		}

		$limit 		= Foundry::themes()->getConfig()->get( 'friendslimit' , 20 );
		$options 	= array( 'limit' => $limit , 'list_id' => $list->id );

		// Get list members
		$model 		= Foundry::model( 'Friends' );
		$members 	= $model->getFriends( $list->user_id , $options );

		// Get the pagination
		$pagination	= $model->getPagination();

		// Set additional vars for the pagination
		$pagination->setVar( 'view' 	, 'friends' );
		$pagination->setVar( 'filter' 	, 'list' );
		$pagination->setVar( 'id'		, $list->id );

		return $view->call( __FUNCTION__ , $list , $members , $pagination );
	}

	/**
	 * Allows caller to set a friend list as the default list.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function setDefault()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Ensure that user is logged in.
		Foundry::requireLogin();

		$my 	= Foundry::user();
		$view 	= $this->getCurrentView();
		$config	= Foundry::config();

		if( !$config->get( 'friends.list.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the list id.
		$id 	= JRequest::getInt( 'id' );

		$list 	= Foundry::table( 'List' );
		$list->load( $id );

		if( !$id || !$list->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_ERROR_LIST_INVALID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if the user owns this list item.
		if( !$list->isOwner() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_ERROR_LIST_IS_NOT_OWNED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Set the list as default
		$state 	= $list->setDefault();

		if( !$state )
		{
			$view->setMessage( $list->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Creates a new friend list.
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function storeList()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Check if user is valid.
		Foundry::requireLogin();

		// Check if friends list is enabled
		$config = Foundry::config();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get current logged in user.
		$my 	= Foundry::user();

		// @friends.list.enabled
		// Check if the friend list feature is enabled
		if( !$config->get( 'friends.list.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get post data.
		$data	= JRequest::get( 'POST' );

		// Detect if this is an edited list or a new list
		$id  	= JRequest::getVar( 'id' );

		// Generate a new list.
		$list 	= Foundry::table( 'List' );

		// Get the access
		$access	= Foundry::access();

		// @access friends.list.enabled
		// Check if the user is allowed to create friend lists
		if( !$access->allowed( 'friends.list.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LISTS_ACCESS_NOT_ALLOWED' ) );
			return $view->call( __FUNCTION__ );
		}

		if( !empty( $id ) )
		{
			$list->load( $id );
		}
		else
		{
			// This will be a new friend list, check if the user has already reached the limit
			$listModel 			= Foundry::model( 'Lists' );

			// Get the total friends list a user has
			$totalFriendsList	= $listModel->getTotalLists( $my->id );

			if( $access->exceeded( 'friends.list.limit' , $totalFriendsList ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LISTS_ACCESS_LIMIT_EXCEEDED' ) );
				return $view->call( __FUNCTION__ );
			}
		}

		// Bind the list with the posted data.
		$list->bind( $data );

		// Check if the user owns this list item.
		if( !empty( $list->id ) && $my->id != $list->user_id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_ERROR_LIST_IS_NOT_OWNED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Set the creator of the list.
		$list->user_id 	= $my->id;

		// Try to store the list.
		$state 	= $list->store();

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'FRIENDS: Unable to create new friend list for user ' . $my->id );

			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_ERROR_CREATING_LIST' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $list );
		}

		// Get friends from this list
		$friends 	= JRequest::getVar( 'uid' );

		// Assign these friends into the list.
		$list->addFriends( $friends );

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_CREATED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $list );
	}


	/**
	 * Gets a list of friend lists.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getLists()
	{
		// // Check for request forgeries.
		// Foundry::checkToken();

		// // Ensure that only valid user is allowed.
		// Foundry::requireLogin();

		// // Get current logged in user.
		// $my 	= Foundry::user();

		// // Get the current limitstart
		// $limitstart = JRequest::getInt( 'limitstart' , 0 );

		// // Get current view.
		// $view 	= $this->getCurrentView();

		// // Check if friends lists are enabled.
		// $config 	= Foundry::config();

		// if( !$config->get( 'friends.list.enabled' ) )
		// {
		// 	$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
		// 	return $view->call( __FUNCTION__ );
		// }

		// // Get lists model.
		// $model 	= Foundry::model( 'Lists' );

		// // Get lists
		// $model->setState( 'limitstart' , $limitstart );
		// $lists 	= $model->getLists( array( 'user_id' => $my->id ) );

		// // Pass the lists to the view for processing.
		// return $view->call( __FUNCTION__ , $lists );
	}

	/**
	 * Retrieve the counts
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getCounters()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in
		Foundry::requireLogin();

		// Get current view.
		$view 		= $this->getCurrentView();
		$my 		= Foundry::user();

		// Get the friends model
		$model 			= Foundry::model( 'Friends' );

		// Set the total friends.
		$totalFriends 	= $model->getTotalFriends( $my->id , array( 'state' => SOCIAL_FRIENDS_STATE_FRIENDS ) );

		// Get the total pending friends
		$totalPendingFriends 	= $model->getTotalPendingFriends( $my->id );

		// Get the total request made
		$totalRequestSent 		= $model->getTotalRequestSent( $my->id );

		// Get the suggestion count.
		$totalSuggest			= $model->getSuggestedFriends( $my->id, null, true );

		return $view->call( __FUNCTION__ , $totalFriends , $totalPendingFriends , $totalRequestSent , $totalSuggest );
	}

	/**
	 * Gets all the count of the user's friend lists.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getListCounts()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in
		Foundry::requireLogin();

		// Get current view.
		$view 		= $this->getCurrentView();

		// Check if friends lists are enabled.
		$config 	= Foundry::config();

		if( !$config->get( 'friends.list.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get current logged in user.
		$my 	= Foundry::user();

		$model 	= Foundry::model( 'Lists' );
		$lists	= $model->getLists( array( 'user_id' => $my->id ) );

		return $view->call( __FUNCTION__ , $lists );
	}

	/**
	 * Adds a list of user into a friend list.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function assign()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user needs to be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Check if friends lists are enabled.
		$config 	= Foundry::config();

		if( !$config->get( 'friends.list.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get list of user id's.
		$ids 	= JRequest::getVar( 'uid' );
		$ids 	= Foundry::makeArray( $ids );

		if( empty( $ids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_PLEASE_ENTER_NAMES' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get current logged in user.
		$my 	= Foundry::user();

		// Get the list id.
		$listId = JRequest::getInt( 'listId' );

		$list 	= Foundry::table( 'List' );
		$list->load( $listId );

		if( !$listId || !$list->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_INVALID_LIST_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// We need to run some tests to see if the user tries to add a user that is not their friend.
		$friendsModel 	= Foundry::model( 'Friends' );

		foreach( $ids as $id )
		{
			if( !$friendsModel->isFriends( $my->id , $id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_NOT_FRIEND_YET' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			if( $list->mapExists( $id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_FRIEND_ALREADY_IN_LIST' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		// User needs to own this list.
		if( !$list->isOwner() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_NOT_OWNER' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$users 	= Foundry::user( $ids );

		// Add the user to the list.
		$list->addFriends( $ids );

		return $view->call( __FUNCTION__ , $users );
	}

	/**
	 * Suggest a list of friend names for a user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 *
	 */
	public function filter()
	{
		// Check for valid tokens.
		Foundry::checkToken();

		// Check for valid user.
		Foundry::requireLogin();

		// Load friends model.
		$model 		= Foundry::model( 'Friends' );

		// Load the view.
		$view 		= Foundry::view( 'Friends' , false );

		// Get the filter types.
		$type 		= JRequest::getVar( 'filter', 'all' );
		$userId 	= JRequest::getVar( 'userid', null );

		$user 		= Foundry::user( $userId );
		$my 		= Foundry::user();

		$friends 	= array();

		$limit 		= Foundry::themes()->getConfig()->get( 'friendslimit' , 20 );
		$options 	= array( 'limit' => $limit );

		if( $type == 'pending' )
		{
			$options[ 'state' ]	= SOCIAL_FRIENDS_STATE_PENDING;

			$friends 	= $model->getFriends( $user->id , $options );
			$userAlias	= $user->getAlias();
		}

		if( $type == 'all' )
		{
			$options[ 'state' ]	= SOCIAL_FRIENDS_STATE_FRIENDS;
			$friends 	= $model->getFriends( $user->id , $options );
			$userAlias	= $user->getAlias();
		}

		if( $type == 'mutual' )
		{
			$limit      =
			$friends 	= $model->getMutualFriends( $my->id, $user->id, $limit );
			$userAlias	= $user->getAlias();
		}

		if( $type == 'suggest' )
		{
			$friends 	= $model->getSuggestedFriends( $my->id, $limit );
			$userAlias	= $my->getAlias();
		}

		if( $type == 'request' )
		{
			$options[ 'state' ]		= SOCIAL_FRIENDS_STATE_PENDING;
			$options[ 'isRequest' ]	= true;

			$friends 	= $model->getFriends( $user->id , $options );
			$userAlias	= $user->getAlias();
		}

		// Get the pagination
		$pagination	= $model->getPagination();

		// Set additional vars for the pagination
		$pagination->setVar( 'view' 	, 'friends' );
		$pagination->setVar( 'userid'	, $userAlias );
		// $pagination->setVar( 'type'		, $type );
		$pagination->setVar( 'filter'	, $type );


		return $view->call( __FUNCTION__ , $type , $friends , $pagination );
	}

	/**
	 * Suggest a list of friend names for a user in photo tagging.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function suggestPhotoTagging()
	{
		$this->suggest( 'photos.tagme' );
	}

	/**
	 * Suggest a list of friend names for a user.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function suggest( $privacy = null )
	{
		// Check for valid tokens.
		Foundry::checkToken();

		// Only valid registered user has friends.
		Foundry::requireLogin();

		$my 		= Foundry::user();

		// Load friends model.
		$model 		= Foundry::model( 'Friends' );

		// Load the view.
		$view 		= $this->getCurrentView();

		// Properties
		$search 	= JRequest::getVar( 'search' );
		$exclude 	= JRequest::getVar( 'exclude' );
		$includeme 	= JRequest::getVar( 'includeme', 0 );

		// Determine what type of string we should search for.
		$config 	= Foundry::config();
		$type 		= $config->get( 'users.displayName' );

		//check if we need to apply privacy or not.
		$options = array();
		if( $privacy )
		{
			$options['privacy'] = $privacy;
		}

		if( $exclude )
		{
			$options[ 'exclude' ] = $exclude;
		}


		if( $includeme )
		{
			$options[ 'includeme' ] = $includeme;
		}

		// Try to get the search result.
		$result		= $model->search( $my->id , $search , $type, $options);

		return $view->call( __FUNCTION__ , $result );
	}

	/**
	 * Creates a new friend request to a target
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function request()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// User needs to be logged in
		Foundry::requireLogin();

		// Get the target user that is being added.
		$id		= JRequest::getInt( 'id' );
		$user 	= Foundry::user( $id );

		// Get the current view.
		$view 	= $this->getCurrentView();

		// @TODO: Check if target user blocks this.

		// If the user doesn't exist;
		if( !$user || !$id )
		{
			$view->setError( JText::_( 'COM_EASYSOCIAL_FRIENDS_UNABLE_TO_LOCATE_USER' ) );

			return $view->call( __FUNCTION__ );
		}

		// Get the current viewer.
		$my 		= Foundry::user();

		// Load up the model to check if they are already friends.
		$model 		= Foundry::model( 'Friends' );

		$friend 	= Foundry::table( 'Friend' );

		// Do not allow user to create a friend request to himself
		if( $my->id == $user->id )
		{
			$view->setError( JText::_( 'COM_EASYSOCIAL_FRIENDS_UNABLE_TO_ADD_YOURSELF' ) );
			return $view->call( __FUNCTION__ , $friend );
		}

		// If they are already friends, ignore this.
		if( $model->isFriends( $my->id , $user->id ) )
		{
			// The user should not land themselves here at all, only take care of this if somehow they try to be funky.
			$view->setError( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_ALREADY_FRIENDS' ) );
			return $view->call( __FUNCTION__ , $friend );
		}

		// Check if user has already previously requested this.
		if( $model->isFriends( $my->id , $user->id , SOCIAL_FRIENDS_STATE_PENDING ) )
		{
			// The user should not land themselves here at all, only take care of this if somehow they try to be funky.
			$view->setError( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_ALREADY_REQUESTED' ) );
			return $view->call( __FUNCTION__ , $friend );
		}

		// If everything is okay, we proceed to add this request to the friend table.
		$friend->setActorId( $my->id );

		// Set the target's id.
		$friend->setTargetId( $user->id );

		// @TODO: Configurable. Set the state.
		$friend->setState( SOCIAL_FRIENDS_STATE_PENDING );

		// Store the friend request
		$state 	= $friend->store();

		// Send notification to the target when a user requests to be his / her friend.
		$params 	= array(
								'requesterId'		=> $my->id,
								'requesterAvatar'	=> $my->getAvatar( SOCIAL_AVATAR_LARGE ),
								'requesterName'		=> $my->getName(),
								'requesterLink'		=> $my->getPermalink(),
								'requestDate'		=> Foundry::date()->toMySQL(),
								'totalFriends'		=> $my->getTotalFriends(),
								'totalMutualFriends'=> $my->getTotalMutualFriends( $user->id )
							);
		// Email template
		$emailOptions 		= array(
										'title'		=> JText::sprintf( 'COM_EASYSOCIAL_EMAILS_SUBJECT_FRIENDS_NEW_REQUEST' , $my->getName() ),
										'template'	=> 'site/friends/request',
										'params'	=> $params
									);


		Foundry::notify( 'friends.request' , array( $user->id ) , $emailOptions , false );

		// @badge: friends.create
		// Assign badge for the person that initiated the friend request.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'friends.create' , $user->id , JText::_( 'COM_EASYSOCIAL_FRIENDS_BADGE_REQUEST_TO_BE_FRIEND' ) );

		$allowedCallbacks	= array( __FUNCTION__ , 'usersRequest' , 'popboxRequest' );
		$callback 			= JRequest::getVar( 'viewCallback' , __FUNCTION__ );

		if( !in_array( $callback , $allowedCallbacks ) )
		{
			$callback 	= __FUNCTION__;
		}

		return $view->call( $callback , $friend );
	}

	/**
	 * Approves a friend request
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function approve()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Do not allow non registered user access
		Foundry::requireLogin();

		// Get the connection id.
		$id		= JRequest::getInt( 'id' );

		// Get the current user.
		$my 	= Foundry::user();

		// Get the view.
		$view 	= $this->getCurrentView();

		// Try to load up the friend table
		$friend	= Foundry::table( 'Friend' );

		// Load the connection.
		if( !$friend->load( $id ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__  , $friend );
		}

		// Get the person that initiated the friend request.
		$actor 	= Foundry::user( $friend->actor_id );

		// Test if the target is really the current user.
		if( $friend->target_id != $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_NOT_YOUR_REQUEST' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__  , $friend );
		}

		// Try to approve the request.
		if( !$friend->approve() )
		{
			Foundry::logError( __FILL__ , __LINE__ , 'FRIENDS: There was an error approving the friend request ' . $id );
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_APPROVING_REQUEST' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $friend );
		}

		$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_FRIENDS_NOW_FRIENDS_WITH' , $actor->getName() ) , SOCIAL_MSG_SUCCESS );

		$callback 			= JRequest::getVar( 'viewCallback' , __FUNCTION__ );
		$allowedCallbacks	= array( __FUNCTION__ , 'notificationsApprove' );

		if( !in_array( $callback , $allowedCallbacks ) )
		{
			$callback 	= __FUNCTION__;
		}

		return $view->call( $callback  , $friend );
	}

	/**
	 * Cancels a friend request.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function cancelRequest()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Guests shouldn't be here.
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Get the current view.
		$view 	= Foundry::view( 'Friends' , false );

		// Get the friend id.
		$id 	= JRequest::getInt( 'id' );

		// Get the model
		$friends	= Foundry::model( 'Friends' );

		$table 		= Foundry::table( 'Friend' );
		$table->load( $id );

		if( !$id || !$table->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if the user is allowed to cancel the request.
		if( !$table->isInitiator() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_NOT_ALLOWED_TO_CANCEL_REQUEST' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Try to cancel the request.
		$state 		= $friends->cancel( $id );

		if( !$state )
		{
			$view->setMessage( $friends->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ , $id );
	}

	/**
	 * Rejects a friend request
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function reject()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Guests shouldn't be able to view this.
		Foundry::requireLogin();

		// Get current logged in user.
		$my 	= Foundry::user();

		// Get the friend id.
		$id 	= JRequest::getInt( 'id' );

		// Get the current view
		$view	= $this->getCurrentView();

		// Try to load up the friend table
		$friend	= Foundry::table( 'Friend' );

		if( !$friend->load( $id ) || !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// Test if the target is really the current user.
		if( $friend->target_id != $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_NOT_YOUR_REQUEST' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// @task: Run approval
		if( !$friend->reject() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_REJECTING_REQUEST' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Removes a user from the friend list.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function removeFromList()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only logged in users can add users to their list.
		Foundry::requireLogin();

		// Get current logged in user.
		$my 		= JFactory::getUser();

		// Get current view.
		$view 		= $this->getCurrentView();

		// Get the user that's being removed from the list.
		$userId 	= JRequest::getInt( 'userId' );

		// Get the current list id.
		$listId 	= JRequest::getInt( 'listId' );

		// Try to load the list now.
		$list 		= Foundry::table( 'List' );
		$state 		= $list->load( $listId );

		if( !$listId || !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_INVALID_LIST_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if the list is owned by the current user.
		if( !$list->isOwner() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_NOT_OWNER' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Try to delete the item from the list.
		$state 	= $list->deleteItem( $userId );

		if( !$state )
		{
			$view->setMessage( $list->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Removes a friend
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unfriend()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// User needs to be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the target user that will be removed.
		$id		= JRequest::getInt( 'id' );

		// Get the current user.
		$my 	= Foundry::user();

		// Try to load up the friend table
		$friend	= Foundry::table( 'Friend' );
		$state 	= $friend->load( $id );

		if( !$state || !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Need to ensure that the target or source of the friend belongs to the current user.
		if( $friend->actor_id != $my->id && $friend->target_id != $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_ERROR_NOT_YOUR_FRIEND' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Throw errors when there's a problem removing the friends
		if( !$friend->unfriend( $my->id ) )
		{
			$view->setMessage( $friend->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Deletes a friend list from the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function deleteList()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only logged in users can try to delete the friend list.
		Foundry::requireLogin();

		// Get the view.
		$view 	= $this->getCurrentView();

		// Lets get some of the information that we need.
		$my 	= Foundry::user();

		// Get the list id.
		$id 	= JRequest::getInt( 'id' );

		// Try to load the list.
		$list 	= Foundry::table( 'List' );
		$list->load( $id );

		// Test if the id provided is valid.
		if( !$list->id || !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_ERROR_LIST_INVALID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $list );
		}

		// Test if the owner of the list matches.
		if( !$list->isOwner() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LISTS_ERROR_LIST_IS_NOT_OWNED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $list );
		}

		// Try to delete the list.
		$state 	= $list->delete();

		if( !$state )
		{
			$view->setMessage( $list->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $list );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DELETE_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $list );
	}
}
