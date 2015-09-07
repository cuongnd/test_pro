<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewFriends extends EasySocialSiteView
{
	/**
	 * Displays confirmation dialog to delete a user from a list
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmRemoveFromList()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$user 	= Foundry::user( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );

		$contents	= $theme->output( 'site/friends/dialog.delete.list.user' );

		return $ajax->resolve( $contents );
	}

	/**
	 * This returns the html block for items generated via the data api
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function popboxRequest( $friend = null )
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$contents 	= $theme->output( 'site/friends/request.popbox' );

		return $ajax->resolve( $contents );
	}

	/**
	 * This returns the html block on friend request made on users listing
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function usersRequest( $friend = null )
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$contents 	= $theme->output( 'site/users/button.pending' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Post processing after setting item as default
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function setDefault()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}
		return $ajax->resolve();
	}

	/**
	 * Displays confirmation to delete a friend list
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignList()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
			return $ajax->reject();
		}

		$list 	= Foundry::table( 'List' );
		$list->load( $id );

		// Get a list of users that are already in this list.
		$users 	= $list->getMembers();
		$users	= Foundry::json()->encode( $users );

		$theme 	= Foundry::themes();
		$theme->set( 'list' 	, $list );
		$theme->set( 'users'	, $users );

		$contents	= $theme->output( 'site/friends/dialog.list.assign' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation to delete a friend list
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmDeleteList()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$list 	= Foundry::table( 'List' );
		$list->load( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'list' , $list );

		$contents	= $theme->output( 'site/friends/dialog.delete.list' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation to reject a friend request
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmReject()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$user 	= Foundry::user( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );

		$contents	= $theme->output( 'site/friends/dialog.reject' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation that the friend has been removed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function friendRemoved()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$user 	= Foundry::user( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );

		$contents	= $theme->output( 'site/friends/dialog.removed' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation dialog to remove a friend.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmUnfriend()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$user 	= Foundry::user( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );

		$contents	= $theme->output( 'site/friends/dialog.unfriend' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation dialog to remove a friend.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function requestCancelled()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$user 	= Foundry::user( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );

		$contents	= $theme->output( 'site/friends/dialog.request.cancelled' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation dialog to remove a friend.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmCancelRequest()
	{
		// Only registered users allowed here
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// Get the target id.
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			// Throw error here.
		}

		$user 	= Foundry::user( $id );

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );

		$contents	= $theme->output( 'site/friends/dialog.cancel.request' );

		return $ajax->resolve( $contents );
	}


	/**
	 * Post processing after a friend request is rejected
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reject()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the standard button
		$theme 	= Foundry::themes();

		$button	= $theme->output( 'site/profile/button.friends.add' );

		return $ajax->resolve( $button );
	}

	/**
	 * Displays the dialog content when a friend is rejected
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function friendRejected()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$output	= $theme->output( 'site/profile/dialog.friends.rejected' );


		return $ajax->resolve( $output );
	}

	/**
	 * Display confirmation message before cancelling the friend request.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmCancel()
	{
		// Require user to be logged in.
		Foundry::requireLogin();

		$ajax	= Foundry::ajax();

		// Get dialog
		$theme	= Foundry::themes();

		$output = $theme->output( 'site/profile/dialog.friends.cancel' );

		return $ajax->resolve( $output );
	}

	/**
	 * Cancels a friend request.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function cancelRequest( $friendId )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the new button that should be applied
		$theme 	= Foundry::themes();
		$theme->set( 'friendId' , $friendId );
		$button	= $theme->output( 'site/profile/button.friends.add' );

		return $ajax->resolve( $button );
	}

	/**
	 * Returns a JSON formatted value of the list item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array 	An array of SocialTableList
	 */
	public function getLists( $lists )
	{
		$ajax 	= Foundry::ajax();

		// Format the result.
		$result 	= array();

		if( !$lists )
		{
			return $ajax->resolve( $result );
		}

		foreach( $lists as $list )
		{
			$obj 		= new stdClass();

			$obj->id 		= $list->id;
			$obj->title 	= $list->title;
			$obj->count 	= $list->getCount();

			$result[]		= $obj;
		}

		return $ajax->resolve( $result );
	}

	/**
	 * Cancels a friend request.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getListCounts( $lists = array() )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$result 	= array();

		if( !$lists )
		{
			return $ajax->resolve( $result );
		}

		foreach( $lists as $list )
		{
			$data 			= new stdClass();
			$data->id		= $list->id;
			$data->count	= $list->getCount();
			$result[]		= $data;
		}

		return $ajax->resolve( $result );
	}

	/**
	 * Executes when a user is removed from the list.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function removeFromList()
	{
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		return $ajax->resolve();
	}

	/**
	 * Assigns a user into a list.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function assign( $users = array() )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$contents 	= array();

		$activeUser	= Foundry::user( JRequest::getInt( 'userId' ) );

		foreach( $users as $user )
		{
			$theme 	= Foundry::themes();

			$theme->set( 'activeUser'	, $activeUser );
			$theme->set( 'user'		, $user );
			$theme->set( 'filter'	, 'list' );

			$contents[] 	= $theme->output( 'site/friends/default.item' );
		}

		return $ajax->resolve( $contents );
	}

	/**
	 * Returns a JSON formatted value of result when item is added to the list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	JSON object.
	 */
	public function getListFriends( $list , $items = array() , $pagination )
	{
		$ajax 	= Foundry::getInstance( 'Ajax' );

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$theme 	= Foundry::themes();

		$friends 	= array();

		if( $items )
		{
			$friends	= Foundry::user( $items );
		}

		$theme->set( 'filter'		, 'list' );
		$theme->set( 'activeList' 	, $list );
		$theme->set( 'friends'		, $friends );
		$theme->set( 'pagination'	, $pagination );

		$output 	= $theme->output( 'site/friends/default.items' );

		return $ajax->resolve( $output );
	}

	/**
	 * Responsible to return html codes to the ajax calls.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function filter( $filter , $friends = array() , $pagination )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$theme 	= Foundry::themes();

		$theme->set( 'pagination'	, $pagination );
		$theme->set( 'friends'		, $friends );
		$theme->set( 'activeList'	, '' );
		$theme->set( 'filter'		, $filter );

		$output 	= $theme->output( 'site/friends/default.items' );

		return $ajax->resolve( $output );
	}

	/**
	 * Process request calls
	 *
	 * @param	null
	 * @return	null
	 **/
	public function request( $friend )
	{
		$ajax 	= Foundry::ajax();
		$error	= $this->getError();

		// Reject the request since there was some errors here.
		if( $error )
		{
			return $ajax->reject( $error );
		}

		// Get the new button that should be applied
		$theme 	= Foundry::themes();
		$theme->set( 'friend' , $friend );
		$button	= $theme->output( 'site/profile/button.friends.sent' );

		return $ajax->resolve( $friend->id , $button );
	}

	/**
	 * This displays the request form when adding a particular user.
	 *
	 * Example calling using Ajax:
	 *
	 * <code>
	 * EasySocial.ready(function($){
	 *		EasySocial.ajax( 'site.views.friends.requestForm' , {}, function(){
	 * 			console.log( 'do something here' );
	 * 		})
	 * });
	 *</code>
	 *
	 * @since	1.0
	 * @param	null
	 * @return	JSON		JSON data.
	 */
	public function requestForm()
	{
		// Guests are not allowed here.
		Foundry::requireLogin();

		$id 	= JRequest::getInt( 'id' );
		$user 	= Foundry::user( $id );
		$my 	= Foundry::user();

		// Get current user's lists
		$listsModel	= Foundry::model( 'Lists' );
		$lists 		= $listsModel->getLists( array( 'user_id' => $my->id ) );

		// Let's get the theme.
		$theme	= Foundry::get( 'Themes' );
		$theme->set( 'user' 	, $user );
		$theme->set( 'lists'	, $lists );

		$output = $theme->output( 'site/friends/request' );

		$ajax 	= Foundry::getInstance( 'Ajax' );
		$ajax->success( $output );
	}

	/**
	 * Retrieve the counts
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getCounters( $totalFriends , $totalPendingFriends , $totalRequestSent , $totalSuggestedFriends )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $totalFriends , $totalPendingFriends , $totalRequestSent , $totalSuggestedFriends );
	}

	/**
	 * This view is responsible to output back to the notifications bar
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function notificationsApprove( $friend )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the current logged in user.
		$my 			= Foundry::user();

		// Get the initiator's information
		$user 			= Foundry::user( $friend->actor_id );
		$totalMutual	= $user->getTotalMutualFriends( $my->id );

		// Get the buttons
		$theme	= Foundry::themes();
		$theme->set( 'user' , $user );
		$contents = $theme->output( 'site/toolbar/friends.accepted' );

		// Get the mutual friends result
		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );
		$mutualContent 	= $theme->output( 'site/toolbar/friends.mutual' );

		return $ajax->resolve( $contents , $mutualContent );
	}

	/**
	 * This view is responsible to approve pending friend requests.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function approve( $friend )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the initiator's information
		$user 	= Foundry::user( $friend->actor_id );

		// Get the buttons
		$theme	= Foundry::themes();
		$button	= $theme->output( 'site/profile/button.friends.friends' );

		return $ajax->resolve( $button );
	}

	/**
	 * Called when the friend is deleted
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unfriend()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the new button that should be applied
		$theme 	= Foundry::themes();
		$button	= $theme->output( 'site/profile/button.friends.add' );

		return $ajax->resolve( $button );
	}

	/**
	 * Responsible to output the JSON object of a result when searched.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function suggest( $result )
	{
		$ajax 	= Foundry::ajax();

		// If there's nothing, just return the empty object.
		if( !$result )
		{
			return $ajax->resolve( $result );
		}

		// Format result to SocialUser object.
		$friends 	= array();

		// Load through the result list.
		foreach( $result as $user )
		{
			$obj 				= new stdClass();
			$obj->avatar		= $user->getAvatar( SOCIAL_AVATAR_SMALL );
			$obj->screenName 	= $user->getName();
			$obj->id 			= $user->id;

			$friends[]	= $obj;
		}

		return $ajax->resolve( $friends );
	}
}
