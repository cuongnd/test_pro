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
 * Follower's view.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class EasySocialViewFollowers extends EasySocialSiteView
{
	/**
	 * Determines if this feature is enabled
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function isEnabled()
	{
		$config 	= Foundry::config();

		if( $config->get( 'followers.enabled' ) )
		{
			return true;
		}

		return false;
	}

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
		if( !$this->isEnabled() )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}
		
		// Check if there's an id.
		$id 	= JRequest::getInt( 'userid' , null );

		// Get the user.
		$user 		= Foundry::user( $id );
		$my			= Foundry::user();
		$privacy 	= Foundry::privacy( $my->id );

		// Let's test if the current viewer is allowed to view this profile.
		if( $my->id != $user->id )
		{
			if(! $privacy->validate( 'followers.view' , $user->id ) )
			{
				return $this->restricted( $user );
			}
		}

		if( $user->isViewer() )
		{
			// Only registered users allowed to view their own followers
			Foundry::requireLogin();
		}

		// If user is not found, we need to redirect back to the dashboard page
		if( !$user->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}
		
		// Get current active filter.
		$active 	= JRequest::getWord( 'filter' , 'followers' );


		// Get the list of followers for this current user.
		$model		= Foundry::model( 'Followers' );
		$title 		= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FOLLOWERS' );

		if( $active == 'followers' )
		{
			$users		= $model->getFollowers( $user->id );
		}

		if( $active == 'following' )
		{
			$users		= $model->getFollowing( $user->id );
			$title 		= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_FOLLOWING' );
		}

		$filterFollowers 	= FRoute::followers( array() , false );
		$filterFollowing 	= FRoute::followers( array( 'filter' => 'following' ) , false );

		if( !$user->isViewer() )
		{
			$title 	= $user->getName() . ' - ' . $title;

			$filterFollowers	= FRoute::followers( array( 'userid' => $user->getAlias() ) , false );
			$filterFollowing 	= FRoute::followers( array( 'userid' => $user->getAlias() , 'filter' => 'following' ) , false );
		}

		Foundry::page()->title( $title );

		// Set the breadcrumb
		Foundry::page()->breadcrumb( $title );

		// Get total followers and following
		$totalFollowers 	= $model->getTotalFollowers( $user->id );
		$totalFollowing 	= $model->getTotalFollowing( $user->id );

		$this->set( 'user' , $user );
		$this->set( 'active' , $active );
		$this->set( 'filterFollowers'	, $filterFollowers );
		$this->Set( 'filterFollowing'	, $filterFollowing );
		$this->set( 'totalFollowers'	, $totalFollowers );
		$this->set( 'totalFollowing'	, $totalFollowing );
		$this->set( 'currentUser'		, $user );
		$this->set( 'users'		, $users );
		$this->set( 'privacy'	, $privacy );

		// Load theme files.
		return parent::display( 'site/followers/default' );
	}

	/**
	 * Displays a restricted page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id
	 */
	public function restricted( $user )
	{
		$this->set( 'showProfileHeader', true);
		$this->set( 'user'   , $user );

		echo parent::display( 'site/followers/restricted' );
	}
}
