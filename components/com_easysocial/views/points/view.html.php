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

class EasySocialViewPoints extends EasySocialSiteView
{
	private function checkFeature()
	{
		$config	= Foundry::config();

		// Do not allow user to access photos if it's not enabled
		if( !$config->get( 'points.enabled' ) )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_DISABLED' ) , SOCIAL_MSG_ERROR );

			Foundry::info()->set( $this->getMessage() );
			$this->redirect( FRoute::dashboard( array() , false ) );
			$this->close();
		}
	}

	/**
	 * Default method to display the registration page.
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	function display( $tpl = null )
	{
		$this->checkFeature();

		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_POINTS' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_POINTS' ) );

		// Get list of badges.
		$model		= Foundry::model( 'Points' );

		// Get number of badges to display per page.
		$limit 		= Foundry::themes()->getConfig()->get( 'pointslimit' );

		$options	= array( 'limit' => $limit );

		$points		= $model->getItems( $options );
		$pagination	= $model->getPagination();

		$this->set( 'pagination', $pagination );
		$this->set( 'points' 	, $points );

		parent::display( 'site/points/default' );
	}

	/**
	 * Displays user's points history
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function history()
	{
		$this->checkFeature();

		$id 	= JRequest::getInt( 'userid' );

		if( !$id )
		{
			$id 	= null;
		}

		$user 	= Foundry::user( $id );

		// If the user id is not provided, we need to display some error message.
		if( !$user->id )
		{
			Foundry::info()->set( JText::_( 'COM_EASYSOCIAL_POINTS_INVALID_USER_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// If the user blocked, we need to display some error message.
		if( $user->isBlock() )
		{
			Foundry::info()->set( JText::sprintf( 'COM_EASYSOCIAL_POINTS_USER_NOT_EXIST', $user->getName() ) , SOCIAL_MSG_ERROR );
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Language should be loaded for the back end.
		Foundry::language()->load( 'com_easysocial' , JPATH_ADMINISTRATOR );

		// Set the page title
		Foundry::page()->title( JText::sprintf( 'COM_EASYSOCIAL_PAGE_TITLE_POINTS_USER_HISTORY' , $user->getName() ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_POINTS' ) , FRoute::points() );
		Foundry::page()->breadcrumb( JText::sprintf( 'COM_EASYSOCIAL_PAGE_TITLE_POINTS_USER_HISTORY' , $user->getName() ) );


		$my 		= Foundry::user();
		$privacy 	= $my->getPrivacy();

		// Let's test if the current viewer is allowed to view this profile.
		if( $my->id != $user->id )
		{
			if( !$privacy->validate( 'profiles.view' , $user->id , SOCIAL_TYPE_USER ) )
			{
				$this->set( 'user' , $user );
				parent::display( 'site/profile/restricted' );
				return;
			}
		}



		$config 		= Foundry::config();
		$options 		= array( 'limit' => $config->get( 'points.history.limit' ) );

		$model 			= Foundry::model( 'Points' );

		// Get a list of histories for the user's points achievements.
		$histories		= $model->getHistory( $user->id , $options );

		$pagination		= $model->getPagination();

		$this->set( 'pagination', $pagination );
		$this->set( 'histories'	, $histories );
		$this->set( 'user'		, $user );

		parent::display( 'site/points/default.history' );
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
		$this->checkFeature();

		$id 	= JRequest::getInt( 'id' );

		$point 	= Foundry::table( 'Points' );
		$point->load( $id );

		if( !$id || !$point->id )
		{
			Foundry::info()->set( null , JText::_( 'The points id provided is not a valid id.' ) , SOCIAL_MSG_ERROR );
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Load language file.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		// Set the page title
		Foundry::page()->title( $point->get( 'title' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_POINTS' ) , FRoute::points() );
		Foundry::page()->breadcrumb( $point->get( 'title' ) );

		// Get list of point achievers.
		$achievers 	= $point->getAchievers();

		$this->set( 'achievers' , $achievers );
		$this->set( 'point'		, $point );

		parent::display( 'site/points/default.item' );
	}
}
