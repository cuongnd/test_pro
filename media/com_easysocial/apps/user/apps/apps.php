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
 * Friends application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppApps extends SocialAppItem
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
	 * Trigger for onPrepareStream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		// We only want to process related items
		if( $item->context != SOCIAL_TYPE_APPS )
		{
			return;
		}

		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $item->contextId , $item->context );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments		= Foundry::comments( $item->contextId , $item->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::stream( array( 'layout' => 'item', 'id' => $item->contextId ) ) ) );
		$item->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;

		// Get current logged in user.
		$my         = Foundry::user();

		// Get user's privacy.
		$privacy 	= Foundry::privacy( $my->id );

		$verb 		= strtolower( $item->verb );
		$method 	= 'prepare' .ucfirst( $verb ) . 'Stream';

		$this->$method( $item );

		return true;
	}

	/**
	 * Formats the activity log
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
		// We only want to process related items
		if( $item->context != SOCIAL_TYPE_APPS )
		{
			return;
		}

		$element	= $item->context;
		$appId 		= $item->contextId;
		$actor		= Foundry::user( $item->actor_id );
		$my 		= Foundry::user();

		// Load the app
		$app 		= Foundry::table( 'App' );
		$app->load( $appId );


		$this->set( 'actor'	, $actor );
		$this->set( 'app' , $app );

		// Set the display mode to be full.
		$item->display	= SOCIAL_STREAM_DISPLAY_FULL;

		// Display the title
		$item->title 	= parent::display( 'logs/' . $item->verb . '.title' );

		$item->content 	= parent::display( 'logs/' . $item->verb . '.content' );

		return true;
	}

	/**
	 * Prepares the stream item for installed apps
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function prepareInstallStream( &$item )
	{
		$element	= $item->context;
		$appId 		= $item->contextId;
		$actor 		= $item->actor;
		$my 		= Foundry::user();

		// Define a color for the context
		$item->color	= '#1d66b9';

		// Load the app
		$app 		= Foundry::table( 'App' );
		$app->load( $appId );

		// Determine if the current viewer has already installed this app.
		$installed	= $app->isInstalled( $my->id );

		$this->set( 'installed' , $installed );
		$this->set( 'actor'	, $actor );
		$this->set( 'app' , $app );

		$this->set( 'uid', $item->uid );

		// Set the display mode to be full.
		$item->display	= SOCIAL_STREAM_DISPLAY_FULL;

		// Display the title
		$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );

		$item->content 	= parent::display( 'streams/' . $item->verb . '.content' );
	}

	public function prepareUninstallStream( &$item )
	{

	}
}
