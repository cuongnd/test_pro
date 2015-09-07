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
 * Component's router for profile view.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialRouterProfile extends SocialRouterAdapter
{
	/**
	 * Constructs the profile urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function build( &$menu , &$query )
	{
		$segments 	= array();

		// If there is a menu but not pointing to the profile view, we need to set a view
		if( $menu && $menu->query[ 'view' ] != 'profile' )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}

		// If there's no menu, use the view provided
		if( !$menu )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}
		unset( $query[ 'view' ] );

		// Check if the user
		$id 		= isset( $query[ 'id' ] ) ? $query[ 'id' ] : null;

		// If user id is provided, use their given alias.
		if( !is_null( $id ) )
		{
			$config 	= Foundry::config();

			$segments[]	= $query[ 'id' ];

			unset( $query[ 'id' ] );
		}

		$layout = isset( $query[ 'layout' ] ) ? $query[ 'layout' ] : null;

		if( !is_null( $layout ) )
		{
			$segments[]	= $this->translate( 'profile_layout_' . $query[ 'layout' ] );
			unset( $query[ 'layout' ] );
		}

		// Determines if the viewer is trying to view an app from a user.
		$appId 		= isset( $query[ 'appId' ] ) ? $query[ 'appId' ] : null;

		if( !is_null( $appId ) )
		{
			$segments[]	= $appId;
			unset( $query[ 'appId' ] );
		}

		return $segments;
	}

	/**
	 * Translates the SEF url to the appropriate url
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	An array of url segments
	 * @return	array 	The query string data
	 */
	public function parse( &$segments )
	{
		$vars 		= array();
		$total 		= count( $segments );
		$layouts 	= $this->getAvailableLayouts( 'Profile' );

		// URL: http://site.com/menu/profile
		if( $total == 1 && $segments[ 0 ] == $this->translate( 'profile' ) )
		{
			$vars[ 'view' ]		= 'profile';

			return $vars;
		}

		// URL: http://site.com/menu/profile/confirmReset
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_confirmreset' ) )
		{
			$vars[ 'view' ]		= 'profile';
			$vars[ 'layout' ]	= 'confirmReset';
			
			return $vars;
		}

		// URL: http://site.com/menu/profile/confirmReset
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_completereset' ) )
		{
			$vars[ 'view' ]		= 'profile';
			$vars[ 'layout' ]	= 'completeReset';
			
			return $vars;
		}

		// This rule has to be before the "id" because passing an "id" would also mean viewing the person's profile.
		//
		// URL: http://site.com/menu/profile/edit
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_edit' ) )
		{
			$vars[ 'view' ]		= 'profile';
			$vars[ 'layout' ]	= 'edit';

			return $vars;
		}

		// This rule has to be before the "id" because passing an "id" would also mean viewing the person's profile.
		//
		// URL: http://site.com/menu/profile/editprivacy
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_editprivacy' ) )
		{
			$vars[ 'view' ]		= 'profile';
			$vars[ 'layout' ]	= 'editPrivacy';

			return $vars;
		}

		// This rule has to be before the "id" because passing an "id" would also mean viewing the person's profile.
		//
		// URL: http://site.com/menu/profile/editnotifications
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_editnotifications' ) )
		{
			$vars[ 'view' ]		= 'profile';
			$vars[ 'layout' ]	= 'editNotifications';

			return $vars;
		}

		// URL: http://site.com/menu/profile/username/about
		// URL: http://site.com/menu/profile/ID-username/about
		if( $total == 3 && $segments[ 0 ] == $this->translate( 'profile' ) && $segments[ 2 ] == $this->translate( 'profile_layout_about' ) )
		{
			$vars[ 'view' ] 	= 'profile';
			$vars[ 'id' ]		= $this->getUserId( $segments[ 1 ] );
			$vars[ 'layout' ]	= 'about';

			return $vars;
		}

		// URL: http://site.com/menu/profile/forgetpassword
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_forgetpassword' ) )
		{
			$vars[ 'view' ] 	= 'profile';
			$vars[ 'layout' ]	= 'forgetPassword';

			return $vars;
		}

		// URL: http://site.com/menu/profile/forgetusername
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'profile_layout_forgetusername' ) )
		{
			$vars[ 'view' ] 	= 'profile';
			$vars[ 'layout' ]	= 'forgetUsername';

			return $vars;
		}

		if( $total == 2 && in_array( $this->translate( $segments[ 1 ] ) , $layouts ) )
		{
			$vars[ 'view' ] 	= $segments[ 0 ];
			$vars[ 'layout' ]	= $segments[ 1 ];

			return $vars;
		}

		// This rule has to be before the "id" because passing an "id" would also mean viewing the person's profile.
		//
		// URL: http://site.com/menu/profile/editPrivacy
		// URL: http://site.com/menu/profile/editNotifications
		if( $total == 2 && in_array( $this->translate( $segments[ 1 ] ) , $layouts ) )
		{
			$vars[ 'view' ] 	= $segments[ 0 ];
			$vars[ 'layout' ]	= $segments[ 1 ];

			return $vars;
		}

		// URL: http://site.com/menu/profile/username OR http://site.com/menu/profile/ID-name
		if( $total == 2 && ( $segments[ 0 ] == $this->translate( 'profile' ) || $segments[ 0 ] == 'profile' ) )
		{
			$vars[ 'view' ]	= 'profile';

			$vars[ 'id' ]	= $this->getUserId( $segments[ 1 ] );

			return $vars;
		}

		// Viewing an app in a profile
		//
		// URL: http://site.com/menu/profile/username/ID-app
		if( $total == 3 && $segments[ 0 ] == $this->translate( 'profile' ) )
		{
			$vars[ 'view' ]		= 'profile';
			$vars[ 'id' ]		= $this->getUserId( $segments[ 1 ] );
			$vars[ 'appId' ]	= $this->getIdFromPermalink( $segments[ 2 ] );

			return $vars;
		}

		return $vars;
	}
}
