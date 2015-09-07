<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewRepost extends EasySocialSiteView
{
	/**
	 * Returns an ajax chain.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The verb that we have performed.
	 */
	public function share( $uid = null , $element = null, $group = SOCIAL_APPS_GROUP_USER, $streamId = 0 )
	{
		// Load ajax lib
		$ajax	= Foundry::ajax();

		// Determine if there's any errors on the form.
		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		// Set the message
		$share 	= Foundry::get( 'Repost', $uid, $element, $group );
		$cnt 	= $share->getCount();

		$cntPluralize 	= Foundry::language()->pluralize( $cnt, true)->getString();
		$text 			= JText::sprintf( 'COM_EASYSOCIAL_REPOST' . $cntPluralize, $cnt );

		//$text = $share->getHTML();

		$isHidden	= ( $cnt > 0 ) ? false : true;

		$streamHTML = '';

		if( $streamId )
		{
			$stream 	= Foundry::stream();
			$stream->getItem( $streamId );

			$streamHTML = $stream->html();
		}

		return $ajax->resolve( $text, $isHidden, $cnt, $streamHTML );
	}

	/**
	 * Display a list of sharers
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getSharers( $sharers = array() )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$theme 	= Foundry::themes();

		$theme->set( 'users' , $sharers );
		$contents 	= $theme->output( 'site/repost/sharer.item' );

		return $ajax->resolve( $contents );
	}

	public function form( )
	{

		$ajax = Foundry::ajax();

		$preview = 'halo this is a preview.';

		// Get dialog
		$theme = Foundry::themes();
		$theme->set( 'preview', $preview );

		$html = $theme->output( 'site/repost/dialog.form' );

		return $ajax->resolve( $html );


	}
}
