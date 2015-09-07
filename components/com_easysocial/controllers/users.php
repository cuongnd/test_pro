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

class EasySocialControllerUsers extends EasySocialController
{
	/**
	 * Retrieves the list of users on the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getUsers()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();
		$my 	= Foundry::user();

		// Get the current filter
		$filter		= JRequest::getWord( 'filter' , 'all' );

		// Get the current sorting
		$sort 			= JRequest::getWord( 'sort' , 'latest' );
		$isSort			= JRequest::getBool( 'isSort' );
		$showPagination	= JRequest::getVar( 'showpagination', 0 );

		$model 		= Foundry::model( 'Users' );
		$options	= array( 'exclusion' => $my->id );

		if( $sort == 'alphabetical' )
		{
			$options[ 'ordering' ]	= 'a.name';
			$options[ 'direction' ]	= 'ASC';
		}

		switch( $filter )
		{
			case 'online':
				$options[ 'login' ]	= true;

				break;

			case 'photos':
				$options[ 'picture' ]	= true;
				break;

			default:
				break;
		}

		// setup the limit
		$limit 		= Foundry::themes()->getConfig()->get( 'userslimit' );
		$options[ 'limit' ]	= $limit;

		// Determine if we should display admins
		$config 	= Foundry::config();
		$admin 		= $config->get( 'users.listings.admin' ) ? true : false;

		$options[ 'includeAdmin' ]	= $admin;
		
		// we only want published user.
		$options[ 'published' ]	= 1;

		$result		= $model->getUsers( $options );
		$pagination  = null;

		if( $showPagination )
		{
			$pagination	= $model->getPagination();

			// Define those query strings here
			$pagination->setVar( 'Itemid'	, FRoute::getItemId( 'users' ) );
			$pagination->setVar( 'view'		, 'users' );
			$pagination->setVar( 'filter' , $filter );
			$pagination->setVar( 'sort' , $sort );
		}

		$users 		= array();

		// preload users.
		$arrIds = array();
		foreach( $result as $obj )
		{
			$arrIds[]	= Foundry::user( $obj->id );
		}

		if( $arrIds )
		{
			Foundry::user( $arrIds );
		}

		foreach( $result as $obj )
		{
			$users[]	= Foundry::user( $obj->id );
		}

		return $view->call( __FUNCTION__ , $users , $isSort, $pagination );
	}
}
