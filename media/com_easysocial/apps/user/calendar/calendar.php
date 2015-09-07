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
class SocialUserAppCalendar extends SocialAppItem
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
		if( $item->context !== 'calendar' )
		{
			return;
		}

		// Format the likes for the stream
		$likes 			= Foundry::likes();
		$likes->get( $item->contextId , 'calendar' );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments			= Foundry::comments( $item->contextId , 'calendar' , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::albums( array( 'layout' => 'item', 'id' => $item->contextId ) ) ) );
		$item->comments 	= $comments;

		// Set a color for the calendar
		$item->color	= '#7ec9af';

		$calendar	= $this->getTable( 'Calendar' );
		$calendar->load( $item->contextId );

		$app 		= $this->getApp();

		// Get the term to be displayed
		$term 		= $item->actor->getFieldValue( 'GENDER' );

		// If there is no term provided we use "his" by default.
		$term 		= !$term ? JText::_( 'COM_EASYSOCIAL_THEIR' ) : $term;

		$this->set( 'term'		, $term );
		$this->set( 'app'		, $app );
		$this->set( 'calendar'	, $calendar );
		$this->set( 'actor'		, $item->actor );


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
