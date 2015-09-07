<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import parent view
Foundry::import( 'site:/views/views' );

class EasySocialViewBadges extends EasySocialSiteView
{
	/**
	 * Default method to display the registration page.
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	function display( $tpl = null )
	{
		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_BADGES' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_BADGES' ) );

		$config 	= Foundry::config();

		if( !$config->get( 'badges.enabled' ) )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Get list of badges.
		$model		= Foundry::model( 'Badges' );

		// Get number of badges to display per page.
		$limit 		= Foundry::themes()->getConfig()->get( 'badgeslimit' );

		$options	= array( 'state' => SOCIAL_STATE_PUBLISHED , 'limit' => $limit );
		$badges		= $model->getItems( $options );
		$pagination	= $model->getPagination();

		$this->set( 'pagination', $pagination );
		$this->set( 'badges' 	, $badges );

		parent::display( 'site/badges/default' );
	}

	/**
	 * Default method to display the registration page.
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	function item( $tpl = null )
	{
		$config 	= Foundry::config();

		if( !$config->get( 'badges.enabled' ) )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Get id of badge
		$id 	= JRequest::getInt( 'id' );

		$badge 	= Foundry::table( 'Badge' );
		$badge->load( $id );

		if( !$id || !$badge->id )
		{
			Foundry::info()->set( JText::_( 'COM_EASYSOCIAL_BADGES_INVALID_BADGE_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			$this->redirect( FRoute::badges() );

			$this->close();
		}

		// Set the page title
		Foundry::page()->title( $badge->get( 'title' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_BADGES' ) , FRoute::badges() );
		Foundry::page()->breadcrumb( $badge->get( 'title' ) );

		// Get the badges model
		$options = array(
			'start' => 0,
			'limit' => Foundry::themes()->getConfig()->get( 'achieverslimit', 50 )
		);
		$achievers 	= $badge->getAchievers( $options );

		$totalAchievers = $badge->getTotalAchievers();

		$this->set( 'totalAchievers', $totalAchievers );
		$this->set( 'achievers'	, $achievers );
		$this->set( 'badge'		, $badge );

		parent::display( 'site/badges/default.item' );
	}

	/**
	 * Displays a list of badges the user has achieved
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function achievements()
	{
		$config 	= Foundry::config();

		if( !$config->get( 'badges.enabled' ) )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}
		
		// Get the current user id that should be displayed
		$userId 	= JRequest::getInt( 'userid' , null );

		$user 		= Foundry::user( $userId );

		// If user is not found, we need to redirect back to the dashboard page
		if( !$user->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		$title 		= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ACHIEVEMENTS' );


		if( !$user->isViewer() )
		{
			$title		= JText::sprintf( 'COM_EASYSOCIAL_PAGE_TITLE_ACHIEVEMENTS_USER' , $user->getName() );

			// Let's test if the current viewer is allowed to view this user's achievements.
			$my 		= Foundry::user();
			$privacy	= $my->getPrivacy();
			$allowed 	= $privacy->validate( 'profiles.view' , $user->id , SOCIAL_TYPE_USER );

			if( !$allowed )
			{
				$this->set( 'user' , $user );
				parent::display( 'site/profile/restricted' );

				return;
			}
		}

		// Set the page title
		Foundry::page()->title( $title );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ACHIEVEMENTS' ) , FRoute::badges( array( 'userid' => $userId , 'layout' => 'achievements' ) ) );

		// @TODO: Check for privacy

		$model 			= Foundry::model( 'badges' );
		$badges 		= $model->getBadges( $user->id );
		$totalBadges	= count( $badges );

		$this->set( 'totalBadges' , $totalBadges );
		$this->set( 'badges' , $badges );
		$this->set( 'user'	, $user );

		parent::display( 'site/badges/achievements' );
	}
}
