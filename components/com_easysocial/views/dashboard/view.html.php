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

// Necessary to import the custom view.
Foundry::import( 'site:/views/views' );

class EasySocialViewDashboard extends EasySocialSiteView
{
	/**
	 * Responsible to output the dashboard layout for the current logged in user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The name of the template file to parse; automatically searches through the template paths.
	 * @return	null
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function display( $tpl = null )
	{
		// Unauthorized users should not be allowed to access this page.
		Foundry::requireLogin();

		// Get the current logged in user.
		$user 	= Foundry::user();

		// Set the page title
		Foundry::page()->title( $user->getName() . ' - ' . JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_DASHBOARD' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_DASHBOARD' ) );

		// Get config object.
		$config 	= Foundry::config();

		// Get list of apps
		$model		= Foundry::model( 'Apps' );
		$options	= array( 'view' => 'dashboard' , 'uid' => $user->id , 'key' => SOCIAL_TYPE_USER );

		// Retrieve apps
		$apps 		= $model->getApps( $options );

		// We need to load the app's own css file.
		if( $apps )
		{
			foreach( $apps as $app )
			{
				// Load app language
				Foundry::language()->loadApp( $app->group , $app->element );

				// Load app's css
				$app->loadCss();
			}
		}

		// Check if there is an app id in the current request as we need to show the app's content.
		$appId 			= JRequest::getInt( 'appId' );
		$contents 		= '';
		$isAppView 		= false;

		if( $appId )
		{
			// Load the application.
			$app 		= Foundry::table( 'App' );
			$app->load( $appId );

			// Check if the user has access to this app
			if( !$app->accessible( $user->id ) )
			{
				Foundry::info()->set( null , JText::_( 'COM_EASYSOCIAL_DASHBOARD_APP_IS_NOT_INSTALLED' ) , SOCIAL_MSG_ERROR );
				return $this->redirect( FRoute::dashboard( array() , false ) );
			}

			$app->loadCss();

			// Load application language file
			Foundry::language()->loadApp( $app->group , $app->element );

			Foundry::page()->title( $user->getName() . ' - ' . $app->get( 'title' ) );


			// Load the library.
			$lib		= Foundry::apps();
			$contents 	= $lib->renderView( SOCIAL_APPS_VIEW_TYPE_EMBED , 'dashboard' , $app , array( 'userId' => $user->id ) );

			$isAppView 	= true;
		}

		// Retrieve user's status
		$story 			= Foundry::get( 'Story' , SOCIAL_TYPE_USER );
		$story->setTarget( $user->id );

		// Retrieve user's stream
		$stream 		= Foundry::stream();
		$stream->story  = $story;

		$start 			= $config->get( 'users.dashboard.start' );

		//check if there is any stream filtering or not.
		$filter			= JRequest::getWord( 'type' , $start );

		$listId 		= JRequest::getInt( 'listId' );

		switch( $filter )
		{
			case 'list';

				if( !empty( $listId ) )
				{
					$list 		= Foundry::table( 'List' );
					$list->load( $listId );

					Foundry::page()->title( $user->getName() . ' - ' . $list->get( 'title' ) );


					// Get list of users from this list.
					$friends 	= $list->getMembers();

					if( $friends )
					{
						$stream->get( array( 'listId' => $listId ) );
					}
					else
					{
						$stream->filter 	= 'list';
					}
				}

				break;

			case 'everyone':

				$stream->get( array(
									'guest' 	=> true
								)
							);

				break;

			case 'following':

				// Set the page title
				Foundry::page()->title( $user->getName() . ' - ' . JText::_( 'COM_EASYSOCIAL_DASHBOARD_FEED_FOLLLOW' ) );

				$stream->get(
							array(
								'context' 	=> SOCIAL_STREAM_CONTEXT_TYPE_ALL,
								'type' 		=> 'follow'
								)
						);
				break;
			case 'me':
			default:
				$stream->get();
				break;
		}

		// Retrieve lists model
		$listsModel		= Foundry::model( 'Lists' );

		// Only fetch x amount of list to be shown by default.
		$limit 			= Foundry::config()->get( 'lists.display.limit' );

		// Get the friend's list.
		$lists 			= $listsModel->setLimit( $limit )->getLists( array( 'user_id' => $user->id , 'showEmpty' => $config->get('friends.list.showEmpty' ) )  );

		$this->set( 'listId'		, $listId );
		$this->set( 'filter'		, $filter );
		$this->set( 'isAppView'		, $isAppView );
		$this->set( 'apps'			, $apps );
		$this->set( 'lists'			, $lists );
		$this->set( 'appId'			, $appId );
		$this->set( 'contents'		, $contents );
		$this->set( 'user'			, $user );
		$this->set( 'stream'		, $stream );

		echo parent::display( 'site/dashboard/default' );
	}
}
