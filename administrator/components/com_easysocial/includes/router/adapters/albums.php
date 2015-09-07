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
 * Component's router for albums view.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialRouterAlbums extends SocialRouterAdapter
{
	/**
	 * Constructs the album's urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function build( &$menu , &$query )
	{
		$segments 	= array();

		// If there is no active menu for friends, we need to add the view.
		if( $menu && $menu->query[ 'view' ] != 'albums' )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}

		if( !$menu )
		{
			$segments[]	= $this->translate( $query[ 'view' ] );
		}
		unset( $query[ 'view' ] );

		$layout 	= isset( $query[ 'layout' ] ) ? $query[ 'layout' ] : null;

		if( !is_null( $layout ) )
		{
			$segments[]	= $this->translate( 'albums_layout_' . $layout );
			unset( $query[ 'layout' ] );
		}

		$id 		= isset( $query[ 'id' ] ) ? $query[ 'id' ] : null;

		if( !is_null( $id ) )
		{
			$segments[]	= $id;
			unset( $query[ 'id' ] );
		}

		// Determines if userid is present in query string
		$userId 	= isset( $query[ 'userid' ] ) ? $query[ 'userid' ] : null;

		if( !is_null( $userId ) )
		{
			$segments[]	= $query[ 'userid' ];
			unset( $query[ 'userid' ] );
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

		// User is viewing their own albums list
		// URL: http://site.com/menu/albums
		if( $total == 1 && $segments[ 0 ] == $this->translate( 'albums' ) )
		{
			$vars[ 'view' ]	= 'albums';
			
			return $vars;
		}

		// User is viewing their own album
		// URL: http://site.com/menu/albums/item/ID-album-alias
		if( $total == 3 && $segments[ 1 ] == $this->translate( 'albums_layout_item' ) )
		{
			$vars[ 'view' ]		= 'albums';
			$vars[ 'layout' ]	= 'item';
			$vars[ 'id' ]		= $this->getIdFromPermalink( $segments[ 2 ] );

			return $vars;
		}
		
		// User is trying to create a new album
		// URL: http://site.com/menu/albums/form
		if( $total == 2 && $segments[ 1 ] == $this->translate( 'albums_layout_form' ) )
		{
			$vars[ 'view' ]		= 'albums';
			$vars[ 'layout' ]	= 'form';

			return $vars;
		}

		// User is viewing another person's albums list
		// URL: http://site.com/menus/albums/ID-username
		if( $total == 2 && $segments[ 0 ] == $this->translate( 'albums' ) )
		{
			$vars[ 'view' ]		= 'albums';
			$vars[ 'userid' ]	= $this->getUserId( $segments[ 1 ] );

			return $vars;
		}

		// User is viewing another user's single album item
		if( $total == 4 && $segments[ 1 ] == $this->translate( 'albums_layout_item' ) )
		{
			$vars[ 'view' ]		= 'albums';
			$vars[ 'layout' ]	= 'item';
			$vars[ 'id' ]		= $this->getIdFromPermalink( $segments[ 2 ] );
			$vars[ 'userid' ]	= $this->getUserId( $segments[ 3 ] );

			return $vars;
		}



		return $vars;
	}
}
