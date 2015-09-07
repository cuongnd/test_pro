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

// Include main view here.
Foundry::import( 'site:/views/views' );

class EasySocialViewUnity extends EasySocialSiteView
{
	/**
	 * Responsible to display the unity view
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function display( $tpl = null )
	{
		// Get the current logged in user.
		$my 		= Foundry::user();

		// Load up facebook button for guest only.
		if( !$my->id )
		{
			$facebook	= Foundry::oauth( 'Facebook' );

			$this->set( 'facebook'		, $facebook );
		}

		// Set the title
		$title 		= JText::_( 'COM_EASYSOCIAL_UNITY_PAGE_TITLE' );

		Foundry::page()->title( $title );

		// Add breadcrumbs
		Foundry::page()->breadcrumb( $title );

		// Get any callback urls.
		$return 	= Foundry::getCallback();

		// If return value is empty, always redirect back to the dashboard
		if( !$return )
		{
			$return	= FRoute::dashboard( array() , false );
		}

		$return 	= base64_encode( $return );

		// Get the layout to use.
		$stream 	= Foundry::stream();
		$stream->getPublicStream( SOCIAL_STREAM_GUEST_LIMIT, 0 );

		$readmoreURL 	= '';
		$readmoreText 	= '';

		if( $my->id == 0 )
		{
			$readmoreURL 	= FRoute::login( array() , false );
			$readmoreText 	= JText::_( 'COM_EASYSOCIAL_UNITY_STREAM_LOGIN' );
		}
		else
		{
			$readmoreURL 	= FRoute::dashboard( array() , false );
			$readmoreText 	= JText::_( 'COM_EASYSOCIAL_UNITY_STREAM_GOTO_DASHBOARD' );
		}

		// Default empty message
		$empty 	= JText::_( 'COM_EASYSOCIAL_UNITY_STREAM_LOGIN_TO_VIEW' );

		if( $my->id )
		{
			$empty = JText::_( 'COM_EASYSOCIAL_UNITY_STREAM_NO_DATA_YET' );	
		}
		

		$this->set( 'stream'		, $stream );
		$this->set( 'readmoreURL'	, $readmoreURL );
		$this->set( 'readmoreText'	, $readmoreText );
		$this->set( 'return'		, $return );
		$this->set( 'empty'			, $empty );



		return parent::display( 'site/unity/default' );
	}
}
