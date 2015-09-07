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

class EasySocialViewLogin extends EasySocialSiteView
{
	/**
	 * Determines if the view should be visible on lockdown mode
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool
	 */
	public function isLockDown()
	{
		return false;
	}

	/**
	 * Responsible to display the generic login form via ajax
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function form( $tpl = null )
	{
		$ajax 	= Foundry::ajax();

		$my 	= Foundry::user();

		// If user is already logged in, they should not see this page.
		if( $my->id > 0 )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_LOGIN_ALREADY_LOGGED_IN' ) , SOCIAL_MSG_ERROR );
			return $ajax->reject( $this->getMessage() );
		}
		
		// Facebook codes.
		$facebook 	= Foundry::oauth( 'Facebook' );

		// Get any callback urls.
		$return 	= Foundry::getCallback();

		// If return value is empty, always redirect back to the dashboard
		if( !$return )
		{
			$return	= FRoute::dashboard( array() , false );
		}
		
		$return 	= base64_encode( $return );

		$this->set( 'return'	, $return );
		$this->set( 'facebook' 	, $facebook );

		$contents	= parent::display( 'site/login/dialog.login' );

		return $ajax->resolve( $contents );
	}
}
