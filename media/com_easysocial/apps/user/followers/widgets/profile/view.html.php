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

/**
 * Profile view for Notes app.
 *
 * @since	1.0
 * @access	public
 */
class FollowersWidgetsProfile extends SocialAppsWidgets
{
	/**
	 * Display user photos on the side bar
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function sidebarBottom( $user )
	{
		// Get the user params
		$params 	= $this->getUserParams( $user->id );

		echo $this->getFollowers( $user , $params );

		echo $this->getFollowing( $user , $params );
	}

	/**
	 * Display a list of followers for the user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getFollowers( $user , &$params )
	{
		$appParams 	= $this->app->getParams();

		if( !$params->get( 'show_profile_followers' , $appParams->get( 'show_profile_followers' , true ) ) )
		{
			return;
		}

		$my		= Foundry::user();
		if( $my->id != $user->id )
		{
			$privacy = $my->getPrivacy();

			if(! $privacy->validate( 'followers.view' , $user->id ) )
			{
				return;
			}
		}

		$model 		= Foundry::model( 'Followers' );

		$users 		= $model->getFollowers( $user->id );
		$total 		= $model->getTotalFollowers( $user->id );

		$theme 		= Foundry::themes();

		$theme->set( 'activeUser'	, $user );
		$theme->set( 'total'		, $total );
		$theme->set( 'users' 		, $users );

		return $theme->output( 'themes:/apps/user/followers/widgets/profile/followers' );
	}

	/**
	 * Display a list of users this user is following
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getFollowing( $user , &$params )
	{
		$appParams 	= $this->app->getParams();

		if( !$params->get( 'show_profile_following' , $appParams->get( 'show_profile_following' , true ) ) )
		{
			return;
		}
		$my		= Foundry::user();

		if( $my->id != $user->id )
		{
			$privacy = $my->getPrivacy();

			if(! $privacy->validate( 'followers.view' , $user->id ) )
			{
				return;
			}
		}

		$model 		= Foundry::model( 'Followers' );

		$users 		= $model->getFollowing( $user->id );
		$total 		= $model->getTotalFollowing( $user->id );
		
		$theme 		= Foundry::themes();

		$theme->set( 'activeUser'	, $user );
		$theme->set( 'total'		, $total );
		$theme->set( 'users' , $users );

		return $theme->output( 'themes:/apps/user/followers/widgets/profile/following' );
	}


}
