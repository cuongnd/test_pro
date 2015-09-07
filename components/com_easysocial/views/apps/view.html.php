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

Foundry::import( 'site:/views/views' );

class EasySocialViewApps extends EasySocialSiteView
{
	/**
	 * Displays the apps on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function display( $tpl = null )
	{
		// Require user to be logged in
		Foundry::requireLogin();

		// Get current logged in user.
		$my 		= Foundry::user();

		// Get model.
		$model 		= Foundry::model( 'Apps' );
		$sort 		= JRequest::getVar( 'sort' , 'alphabetical' );
		$order 		= JRequest::getWord( 'order' , 'asc' );
		$options	= array( 'type' => SOCIAL_APPS_TYPE_APPS , 'installable' => true , 'sort' => $sort , 'order' => $order );
		$modelFunc	= 'getApps';

		switch( $sort )
		{
			case 'recent':
				$options['sort'] = 'a.created';
				$options['order'] = 'desc';
				break;

			case 'alphabetical':
				$options['sort'] = 'a.title';
				$options['order'] = 'asc';
				break;

			case 'trending':
				// need a separate logic to get trending based on apps_map
				$modelFunc = 'getTrendingApps';
				break;
		}

		// Get the current filter
		$filter 	= JRequest::getWord( 'filter' , 'browse' );
		$title	 	= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_BROWSE_APPS' );

		if( $filter == 'mine' )
		{
			$options[ 'uid' ]	= $my->id;
			$options[ 'key' ]	= SOCIAL_TYPE_USER;
			$title 				= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_YOUR_APPS' );
		}

		// Set the page title
		Foundry::page()->title( $title );
		
		// Try to fetch the apps now.
		$apps 		= $model->$modelFunc( $options );

		$this->set( 'filter', $filter );
		$this->set( 'sort'	, $sort );
		$this->set( 'apps'	, $apps );

		parent::display( 'site/apps/default' );
	}

	/**
	 * Displays the application in a main canvas layout which is the full width of the component.
	 * Example:
	 * 		index.php?option=com_easysocial&view=apps&layout=canvas&id=[id]&appView=[appView]
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 *
	 */
	public function canvas()
	{
		// Get the current logged in user.
		$my 			= Foundry::user();

		// Get the user that is being viewed.
		$userId 	= JRequest::getInt( 'userid', null );
		$user 		= Foundry::user( $userId );

		// Get config object.
		$config 	= Foundry::config();

		// Get the current app id.
		$id 		= JRequest::getInt( 'id' );

		// Get the current app.
		$app 		= Foundry::table( 'App' );
		$state 		= $app->load( $id );

		// Check if the user has access to this app
		if( !$app->accessible( $user->id ) )
		{
			Foundry::info()->set( null , JText::_( 'COM_EASYSOCIAL_APPS_CANVAS_APP_IS_NOT_INSTALLED' ) , SOCIAL_MSG_ERROR );
			return $this->redirect( FRoute::profile( array( 'id' => $user->getAlias() ) , false ) );
		}

		// Load info
		$info	 	= Foundry::info();
		
		// If id is not provided, we need to throw some errors here.
		if( !$id || !$state )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_APPS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			$info->set( $this->getMessage() );

			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Try to load the app's css.
		$app->loadCss();

		// Check if the app provides any custom view
		$appView	= JRequest::getVar( 'customView' , 'canvas' );

		// Load the library.
		$lib		= Foundry::apps();
		$contents 	= $lib->renderView( SOCIAL_APPS_VIEW_TYPE_EMBED , $appView , $app , array( 'userId' => $user->id ) );

		$this->set( 'user' 		, $user );
		$this->set( 'contents'	, $contents );

		echo parent::display( 'site/apps/default.canvas' );
	}
}
