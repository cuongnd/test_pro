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

// Import main controller
Foundry::import( 'admin:/controllers/controller' );

class EasySocialControllerNews extends EasySocialController
{
	/**
	 * Get's the latest news from updater server.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getNews()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current model
		$model 	= Foundry::model( 'News' );

		// Get the news
		$news	= $model->getNews();

		return $view->call( __FUNCTION__ , $news );
	}
}