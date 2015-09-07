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

// Include main view file.
Foundry::import( 'site:/views/views' );

/**
 * Friend's view.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class EasySocialViewFriends extends EasySocialSiteView
{
	/**
	 * Default method to display a list of friends a user has.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function display( $tpl = null )
	{
		// User needs to be logged in to access this page.
		Foundry::requireLogin();

		// Check if there's an id.
		// Okay, we need to use getInt to prevent someone inject invalid data from the url. just do another checking later.
		$id 	= JRequest::getInt( 'userid' , null );

		// this checking is to make sure the id != 0. if 0, we set to null to get the current user.
		if( empty( $id ) )
		{
			$id = null;
		}

		// Get the user.
		$user 	= Foundry::user( $id );

		// Get the current logged in user.
		$my		= Foundry::user();

		// Get user's privacy
		$privacy 	= Foundry::privacy( $my->id );

		// Let's test if the current viewer is allowed to view this profile.
		if( $my->id != $user->id )
		{
			if(! $privacy->validate( 'friends.view' , $user->id ) )
			{
				// @TODO: show proper message to user.
				Foundry::showNoAccess( JText::_( 'COM_EASYSOCIAL_FRIENDS_NOT_ALLOWED_TO_VIEW' ) );
				return;
			}
		}

		// Get the list of friends this user has.
		$model 		= Foundry::model( 'Friends' );
		$limit 		= Foundry::themes()->getConfig()->get( 'friendslimit' , 20 );


		$options 	= array( 'state' => SOCIAL_FRIENDS_STATE_FRIENDS , 'limit' => $limit );

		// By default the view is "All Friends"
		$filter 	= JRequest::getWord( 'filter' , 'all' );

		// If current view is pending, we need to only get pending friends.
		if( $filter == 'pending' )
		{
			$options[ 'state' ] 	= SOCIAL_FRIENDS_STATE_PENDING;
		}

		if( $filter == 'request' )
		{
			$options[ 'state' ] 	= SOCIAL_FRIENDS_STATE_PENDING;
			$options[ 'isRequest' ] = true;
		}

		// Detect if list id is provided.
		$listId			= JRequest::getInt( 'listId' );
		$activeList 	= Foundry::table( 'List' );
		$activeList->load( $listId );

		// Check if list id is provided.
		$filter 	= $listId ? 'list' : $filter;

		if( $activeList->id )
		{
			$options[ 'list_id' ]	= $activeList->id;
		}

		$totalPendingFriends 	= $model->getTotalPendingFriends( $user->id );
		$totalRequestSent 		= $model->getTotalRequestSent( $user->id );

		// Get the list of lists the user has.
		$listModel	= Foundry::model( 'Lists' );

		// Only fetch x amount of list to be shown by default.
		$limit 		= Foundry::config()->get( 'lists.display.limit' );

		// Get the list items.
		$lists 		= $listModel->getLists( array( 'user_id' => $user->id ) );

		// Get the total friends list a user has
		$totalFriendsList	= $user->getTotalFriendsList();

		// Set the total friends.
		$totalFriends 		= $model->getTotalFriends( $user->id );

		// set total mutual friends
		if( $my->id != $user->id )
		{
			$totalMutualFriends 	= $model->getMutualFriendCount( $my->id, $user->id );
			$this->set( 'totalMutualFriends'	, $totalMutualFriends );
		}

		// Get the suggestion count.
		$friendSuggestedCnt = $model->getSuggestedFriends( $my->id, null , true );

		$friends 			= array();
		$title 				= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS' );

		if( $activeList->id )
		{
			$title 	= $activeList->get( 'title' );
		}

		if( $filter == 'mutual' )
		{
			$title 		 = JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_MUTUAL_FRIENDS' );
			$mutuallimit = Foundry::themes()->getConfig()->get( 'friendslimit' , 20 );
			$friends 	 = $model->getMutualFriends( $my->id, $user->id, $mutuallimit );

			// Set breadcrumbs
			Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS' ) , FRoute::friends() );
		}

		if( $filter == 'pending' )
		{
			$title 		= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS_PENDING_APPROVAL' );

			// Set breadcrumbs
			Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS' ) , FRoute::friends() );
		}

		if( $filter == 'request' )
		{
			$title 		= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS_REQUESTS' );

			$options[ 'state' ]		= SOCIAL_FRIENDS_STATE_PENDING;
			$options[ 'isRequest']	= true;

			$friends 	= $model->getFriends( $user->id , $options );

			// Set breadcrumbs
			Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS' ) , FRoute::friends() );
		}

		if( $filter == 'suggest' )
		{
			$title 		= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS_SUGGESTIONS' );
			$friends 	= $model->getSuggestedFriends( $my->id, Foundry::themes()->getConfig()->get( 'friendslimit' , 20 ) );

			// Set breadcrumbs
			Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS' ) , FRoute::friends() );
		}


		if( $filter == 'all' || $filter == 'pending' || $filter == 'list' )
		{
			$friends 	= $model->getFriends( $user->id , $options );
		}

		// Get pagination
		$pagination	= $model->getPagination();

		// Set additional params for the pagination links
		$pagination->setVar( 'view' , 'friends' );

		if( !$user->isViewer() )
		{
			$pagination->setVar( 'userid' , $user->getAlias() );
		}


		// Set the page title
		if( $user->isViewer() )
		{
			Foundry::page()->title( $title );
		}
		else
		{
			Foundry::page()->title( $user->getName() . ' - ' . $title );
		}


		// Set breadcrumbs
		Foundry::page()->breadcrumb( $title );

		// Determines if the user is allowed to create friend list.
		$access 	= Foundry::access();

		// Push vars to the theme
		$this->set( 'pagination'		, $pagination );
		$this->set( 'privacy'			, $privacy );
		$this->set( 'filter' 			, $filter );
		$this->set( 'activeList' 		, $activeList );
		$this->set( 'totalFriendsList'	, $totalFriendsList );
		$this->set( 'friends' 			, $friends );
		$this->set( 'totalPendingFriends'	, $totalPendingFriends );
		$this->set( 'totalRequestSent' 		, $totalRequestSent );
		$this->set( 'totalFriendSuggest'	, $friendSuggestedCnt );
		$this->set( 'user' , $user );
		$this->set( 'lists' , $lists );
		$this->set( 'totalFriends'	, $totalFriends );

		// Load theme files.
		return parent::display( 'site/friends/default' );
	}


	/**
	 * Displays the list form.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function listForm()
	{
		// Ensure that user is logged in.
		Foundry::requireLogin();

		// Check if friends list is enabled
		$config = Foundry::config();

		if( !$config->get( 'friends.list.enabled' ) )
		{
			Foundry::info()->set( false , JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_DISABLED' ) , SOCIAL_MSG_ERROR );
			$this->redirect( FRoute::friends( array() , false ) );
			$this->close();
		}

		// Get current logged in user.
		$my 	= Foundry::user();

		// Get the list id.
		$id 	= JRequest::getInt( 'id' , 0 );

		$list 	= Foundry::table( 'List' );
		$list->load( $id );

		// Check if this list is being edited.
		if( $id && !$list->id )
		{
			Foundry::info()->set( false , JText::_( 'COM_EASYSOCIAL_FRIENDS_INVALID_LIST_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			$this->redirect( FRoute::friends( array() , false ) );
			$this->close();
		}

		// Set the page title
		if( $list->id )
		{
			Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS_EDIT_LIST_FORM' ) );
		}
		else
		{
			Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FRIENDS_LIST_FORM' ) );
		}

		// Get list of users from this list.
		$result 	= $list->getMembers();
		$members 	= array();

		if( $result )
		{
			$members	= Foundry::user( $result );
		}

		$this->set( 'members'	, $members );
		$this->set( 'list' 		, $list );
		$this->set( 'id', $id );

		// Load theme files.
		echo parent::display( 'site/friends/form.list' );
	}

	/**
	 * Perform redirection after the list is created.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 **/
	public function storeList( $list )
	{
		Foundry::info()->set( $this->getMessage() );

		$url	 = FRoute::friends( array( 'list' => $list->id ) , false );

		if( $this->hasErrors() )
		{
			$this->redirect( FRoute::friends( array() , false ) );
			$this->close();
		}

		$this->redirect( FRoute::friends( array() , false ) );
		$this->close();
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
	public function approve()
	{
		// Get the return url.
		$return = JRequest::getVar( 'return' , null );

		$info	= Foundry::info();

		// Set the message data
		$info->set( $this->getMessage() );

		return $this->redirect( FRoute::friends( array() , false ) );
	}

	/**
	 * Post processing of delete list item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function deleteList()
	{
		$info 	= Foundry::info();

		$info->set( $this->getMessage() );

		$url 	= FRoute::friends( array() , false );
		$this->redirect( $url );
		$this->close();
	}
}
