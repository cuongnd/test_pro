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

// Include apps interface.
Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Notes application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppNotes extends SocialAppItem
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
		if( $item->context !== 'notes' )
		{
			return;
		}

		// Apply comments on the stream
		$comments			= Foundry::comments( $item->contextId , $item->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::apps( array( 'layout' => 'canvas', 'userid' => $item->actor->getAlias() , 'cid' => $item->contextId ) ) ) );
		$item->comments 	= $comments;

		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $item->contextId , $item->context );
		$item->likes	= $likes;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;
		

		$note 	= $this->getTable( 'Note' );
		$note->load( $item->contextId );

		$this->set( 'note'	, $note );
		$this->set( 'actor'	, $item->actor );


		$item->display	= SOCIAL_STREAM_DISPLAY_FULL;
		$item->title 	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content	= parent::display( 'streams/' . $item->verb . '.content' );
	}

	/**
	 * Prepares the activity log
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
	}

}