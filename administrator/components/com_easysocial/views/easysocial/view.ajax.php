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

Foundry::import( 'admin:/views/views' );

class EasySocialViewEasySocial extends EasySocialAdminView
{
	/**
	 * Displays the update version in a popbox modal
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function popboxUpdate()
	{
		$ajax 	= Foundry::ajax();
		
		$local 		= JRequest::getVar( 'local' );
		$online 	= JRequest::getVar( 'online' );

		$theme 		= Foundry::themes();
		$theme->set( 'local' , $local );
		$theme->set( 'online', $online );
		
		$contents 	= $theme->output( 'admin/easysocial/popbox.version.outdated' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Main method to display the dashboard view.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function versionChecks( $localVersion , $onlineVersion )
	{
		$ajax 	= Foundry::ajax();

		$state 	= version_compare( $localVersion , $onlineVersion );

		$theme 	= Foundry::themes();

		$theme->set( 'localVersion'		, $localVersion );
		$theme->set( 'onlineVersion' 	, $onlineVersion );

		$contents 	= '';
			
		$outdated 	= $state === -1;

		// Requires updating
		if( $outdated )
		{
			$contents 	= $theme->output( 'admin/easysocial/version.outdated' );
		}
		else
		{
			// Version up to date
			$contents 	= $theme->output( 'admin/easysocial/version.latest' );
		}	

		return $ajax->resolve( $contents , $outdated , $localVersion , $onlineVersion );
	}

	/**
	 * Confirmation to purge cache
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function confirmPurgeCache()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$contents 	= $theme->output( 'admin/easysocial/dialog.purge.cache' );

		$ajax->resolve( $contents );
	}
}
