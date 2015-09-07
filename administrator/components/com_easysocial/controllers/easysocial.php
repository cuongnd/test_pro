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

Foundry::import( 'admin:/controllers/controller' );

class EasySocialControllerEasySocial extends EasySocialController
{
	/**
	 * Checks to see if there are any new columns that are added to the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function sync()
	{
		$affected	= Foundry::syncDB();
		
		$view 		= $this->getCurrentView();

		if( !$affected )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_NO_COLUMNS_TO_UPDATE' ) );	
		}
		else
		{
			$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_UPDATED_COLUMNS' , $affected ) );	
		}
		
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Checks with the server for the current and latest version from the server.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function versionChecks()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 		= $this->getCurrentView();
		
		// Get the current version.
		$localVersion	= Foundry::getLocalVersion();

		// Get the latest version online.
		$onlineVersion 	= Foundry::getOnlineVersion();

		return $view->call( __FUNCTION__ , $localVersion , $onlineVersion );
	}

	/**
	 * Purges the less cache files on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function clearCache()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		$purgeLess	= JRequest::getBool( 'less-cache' );

		if( $purgeLess )
		{
			$less 	= Foundry::get( 'Less' );
			$less->clear();
		}

		$purgeJS	= JRequest::getBool( 'js-cache' );

		if( $purgeJS )
		{
			// Clear javascript files
			$configuration	= Foundry::getInstance( 'Configuration' );
			$configuration->purge();

			$compiler = Foundry::getInstance( 'Compiler' );
			$compiler->purgeResources();	
		}

		$purgeURL	= JRequest::getBool( 'url-cache' );

		if( $purgeURL )
		{
			$model 	= Foundry::model( 'Links' );

			$model->clear();
		}

		$message 	= JText::sprintf( 'COM_EASYSOCIAL_CACHE_PURGED_FROM_SITE' );

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}
}