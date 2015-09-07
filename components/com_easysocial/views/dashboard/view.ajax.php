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
	 * Responsible to output the application contents.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialAppTable	The application ORM.
	 */
	public function getAppContents( $app )
	{
		$ajax 	= Foundry::ajax();

		// If there's an error throw it back to the caller.
		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the current logged in user.
		$my 		= Foundry::user();

		// Load the library.
		$lib		= Foundry::getInstance( 'Apps' );
		$contents 	= $lib->renderView( SOCIAL_APPS_VIEW_TYPE_EMBED , 'dashboard' , $app , array( 'userId' => $my->id ) );

		// Return the contents
		return $ajax->resolve( $contents );
	}

	/**
	 * Retrieves the stream contents.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getStream( $stream , $type = '' )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$streamCnt = $stream->getCount();

		// Retrieve user's status
		$my 			= Foundry::user();
		$story 			= Foundry::get( 'Story' , SOCIAL_TYPE_USER );

		$stream->story  = $story;

		$theme 			= Foundry::themes();
		$theme->set( 'stream' 	, $stream );
		$theme->set( 'story'	, $story );
		$theme->set( 'streamcount', $streamCnt );
		$contents 		= $theme->output( 'site/dashboard/feeds' );

		return $ajax->resolve( $contents, $streamCnt );
	}
}
