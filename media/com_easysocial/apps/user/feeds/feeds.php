<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Feeds application for EasySocial
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppFeeds extends SocialAppItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Prepares the stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		if( $item->context !== 'feeds' )
		{
			return;
		}

		$feed 	= $this->getTable( 'Feed' );
		$feed->load( $item->contextId );

		$actor	= $item->actor;
		$app 	= $this->getApp();

		$this->set( 'app'	, $app );
		$this->set( 'feed'	, $feed );
		$this->set( 'actor'	, $actor );

		$item->color 	= '#fc0';
		$item->display	= SOCIAL_STREAM_DISPLAY_FULL;
		$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content	= parent::display( 'streams/' . $item->verb . '.content' );
	}
}
