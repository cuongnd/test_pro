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
 * Component's router for users view.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialRouterUsers extends SocialRouterAdapter
{
	/**
	 * Constructs users urls
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

		// If there is a menu but not pointing to the profile view, we need to set a view
		if( $menu && $menu->query[ 'view' ] != 'users' )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}

		// If there's no menu, use the view provided
		if( !$menu )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}
		unset( $query[ 'view' ] );

		$filter = isset( $query[ 'filter' ] ) ? $query[ 'filter' ] : null;

		if( !is_null( $filter ) )
		{
			$segments[]	= $this->translate( 'users_filter_' . $query[ 'filter' ] );
			unset( $query[ 'filter' ] );
		}

		$sort 	= isset( $query[ 'sort' ] ) ? $query[ 'sort' ] : null;

		if( !is_null( $sort ) )
		{
			$segments[]	= $this->translate( 'users_sort_' . $query[ 'sort' ] );
			unset( $query[ 'sort' ] );
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


		// URL: http://site.com/menu/users/online
		if( ($total == 2 || $total == 3) && $segments[ 1 ] == $this->translate( 'users_filter_online' ) )
		{
			$vars[ 'view' ]		= 'users';
			$vars[ 'filter' ]	= 'online';

			return $vars;
		}

		if( ($total == 2 || $total == 3 ) && $segments[ 1 ] == $this->translate( 'users_filter_photos' ) )
		{
			$vars[ 'view' ]		= 'users';
			$vars[ 'filter' ]	= 'photos';

			return $vars;
		}

		// URL: http://site.com/menu/users
		if( ( $total <= 3 ) && ($segments[ 0 ] == $this->translate( 'users' ) || $segments[ 1 ] == $this->translate( 'users_filter_all' ) ) )
		{
			$vars[ 'view' ]		= 'users';

			if( isset( $segments[ 1 ] ) )
			{
				$vars[ 'filter' ]	= $segments[ 1 ];
			}

			if( isset( $segments[ 2 ] ) )
			{
				$vars[ 'sort' ]		= $segments[ 2 ];
			}

			return $vars;
		}

		

		return $vars;
	}
}
