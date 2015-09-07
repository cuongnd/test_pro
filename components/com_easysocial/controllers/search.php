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

class EasySocialControllerSearch extends EasySocialController
{
	/**
	 * get activity logs.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getItems()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		// search controller do not need to check islogin.

		// Get the current view
		$view 			= $this->getCurrentView();

		// Get current logged in user
		$my 			= Foundry::user();

		$type 			= JRequest::getVar( 'type', '' );
		$keywords 		= JRequest::getVar( 'q', '' );
		$next_limit 	= JRequest::getVar( 'next_limit', '' );
		$last_type 		= JRequest::getVar( 'last_type', '' );
		$isloadmore 	= JRequest::getVar( 'loadmore', false );
		$ismini 		= JRequest::getVar( 'mini', false );

		$limit 			= ( $ismini ) ? Foundry::themes()->getConfig()->get( 'search_toolbarlimit' ) : Foundry::themes()->getConfig()->get( 'search_limit' );

		// @badge: search.create
		// Assign badge for the person that initiated the friend request.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'search.create' , $my->id , JText::_( 'COM_EASYSOCIAL_SEARCH_BADGE_SEARCHED_ITEM' ) );

		$model			= Foundry::model( 'Search' );
		$result 		= $model->getItems( $keywords, $type, $next_limit, $limit );
		$count 			= $model->getCount();
		$next_limit 	= $model->getNextLimit();

		return $view->call( __FUNCTION__, $result, $last_type, $next_limit, $isloadmore, $ismini, $count );

	}

}
