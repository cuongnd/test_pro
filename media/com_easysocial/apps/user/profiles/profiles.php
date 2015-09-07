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

Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Profiles application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppProfiles extends SocialAppItem
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
	 * Responsible to generate the activity contents.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
		if( $item->context != 'profiles' )
			return;

		// Get the context id.
		$id 		= $item->contextId;

		// Load the profiles table.
		$profile	= Foundry::table( 'Profile' );
		$state 		= $profile->load( $id );

		// Get the actor
		$actor 		= $item->actor;

		// Get the term to be displayed
		$term 		= $actor->getFieldValue( 'GENDER' );
		$this->set( 'term'	, $term );

		// Set the actor for the themes.
		$this->set( 'actor' , $actor );

		// We need to know the profile meta.
		$this->set( 'profile'	, $profile );

		$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
		$item->title	= parent::display( 'streams/' . $item->verb . '.title' );

		if( $includePrivacy )
		{
			$my         = Foundry::user();
			$privacy	= Foundry::privacy( $my->id );
			// when in activity, the item->uid is the stream_item.id
			$item->privacy 	= $privacy->form( $item->uid , SOCIAL_TYPE_ACTIVITY, $item->actor->id, 'core.view' );
		}

		return true;
	}

	/**
	 * Responsible to generate the stream content for profiles apps.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	$params		A standard object with key / value binding.
	 *
	 * @return	none
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{

		if( $item->context != 'profiles' )
		{
			return;
		}

		$my         = Foundry::user();
		$privacy	= Foundry::privacy( $my->id );


		$tbl = Foundry::table( 'StreamItem' );
		$tbl->load( array('uid' => $item->uid ) );
		$uid = $tbl->id;

		$uidContext = SOCIAL_TYPE_ACTIVITY;

		if( $includePrivacy )
		{
			if(! $privacy->validate( 'core.view', $item->contextId, $uidContext, $item->actor->id ) )
			{
				return;
			}
		}

		// Get the single context id
		$id 		= $item->contextId;

		// Apply likes on the stream
		$likes 			= Foundry::likes();
		$likes->get( $uid , $uidContext );
		$item->likes	= $likes;


		// Apply comments on the stream
		$comments		= Foundry::comments( $uid , $uidContext , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::profile( array( 'id' => $item->actor->getAlias() ) ) ) );
		$item->comments 	= $comments;

		// Apply repost on the stream
		$repost 		= Foundry::get( 'Repost', $item->uid , SOCIAL_TYPE_STREAM );
		$item->repost	= $repost;

		// We only need the single actor.
		$this->set( 'actor'		, $item->actor );

		if( $item->verb == 'register' )
		{
			$item->display 	= ( $item->display ) ? $item->display : SOCIAL_STREAM_DISPLAY_MINI;
			$item->title	= parent::display( 'streams/' . $item->verb . '.title' );
		}

		// When user updates their profile.
		if( $item->verb == 'update' )
		{
			$actor 		= $item->actor;

			// Get the term to be displayed
			$term 		= $actor->getFieldValue( 'GENDER' );
			
			$this->set( 'term'	, $term );

			// The stream should display in mini mode
			$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
			$item->title	= parent::display( 'streams/' . $item->verb . '.title' );
		}

		if( $includePrivacy )
		{
			$item->privacy 	= $privacy->form( $uid , $uidContext, $item->actor->id, 'core.view' );
		}

		return true;
	}

}
