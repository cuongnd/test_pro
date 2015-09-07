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
 * Component's router for dashboard view.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialRouterDashboard extends SocialRouterAdapter
{
	/**
	 * Constructs dashboard urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	JMenu 	The active menu object.
	 * @param	array 	An array of query strings
	 * @return	array 	The url structure
	 */
	public function build( &$menu , &$query )
	{
		$segments 	= array();

		// If there is no active menu for profile, we need to add the view, otherwise we
		// would not be able to determine the correct profile to display.
		if( $menu && $menu->query[ 'view' ] != 'dashboard' )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}
		unset( $query[ 'view' ] );

		$appId 	= isset( $query[ 'appId' ] ) ? $query[ 'appId' ] : null;

		// If app id is provided, get the app alias.
		if( !is_null( $appId ) )
		{
			$segments[]	= $appId;
			unset( $query[ 'appId' ] );
		}

		$filter = isset( $query[ 'type' ] ) ? $query[ 'type' ] : '';

		if( $filter )
		{
			$segments[]	= $filter;
			unset($query[ 'type' ] );
		}

		$listId 	= isset( $query[ 'listId' ] ) ? $query[ 'listId' ] : '';

		if( $listId )
		{
			$segments[]	= $listId;
			unset( $query[ 'listId' ] );
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
		$vars 	= array();
		$total 	= count( $segments );

		// URL: http://site.com/menu/dashboard/following
		if( $total == 2 && $segments[ 0 ] == $this->translate( 'dashboard' ) && $segments[ 1 ] == $this->translate( 'dashboard_following' ) )
		{
			$vars[ 'view' ]	= 'dashboard';
			$vars[ 'type' ]	= 'following';

			return $vars;
		}

		// URL: http://site.com/menu/dashboard/following
		if( $total == 2 && $segments[ 0 ] == $this->translate( 'dashboard' ) && $segments[ 1 ] == $this->translate( 'dashboard_everyone' ) )
		{
			$vars[ 'view' ]	= 'dashboard';
			$vars[ 'type' ]	= 'everyone';

			return $vars;
		}

		// URL: http://site.com/menu/dashboard/ID-app
		if( $total == 2 && $segments[ 0 ] == $this->translate( 'dashboard' ) )
		{
			$vars[ 'view' ]		= 'dashboard';
			$vars[ 'appId' ]	= $segments[ 1 ];

			return $vars;
		}

		// URL: http://site.com/menu/list/ID-list
		if( $total == 3 && $segments[ 0 ] == $this->translate( 'dashboard' ) && $segments[ 1 ] == $this->translate( 'dashboard_list' ) )
		{
			$vars[ 'view' ]		= 'dashboard';
			$vars[ 'type' ]		= 'list';
			$vars[ 'listId' ]	= $segments[ 2 ];

			return $vars;
		}


		$vars[ 'view' ]		= 'dashboard';
		// @TODO: Friends list
		return $vars;

	}
}
