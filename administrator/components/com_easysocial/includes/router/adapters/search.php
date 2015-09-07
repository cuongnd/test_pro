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
 * Component's router.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialRouterSearch extends SocialRouterAdapter
{

	/**
	 * Constructs the profile urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function route( $options = array() , $xhtml = true )
	{
		$url = self::$base . '&view=search';

		$query 	= isset( $options[ 'q' ] ) ? $options[ 'q' ] : '';
		
		if( $query )
		{
			$url 	= $url . '&q=' . $query;
		}

		return FRoute::_( $url , $xhtml , $this->name , $options[ 'ssl' ] , $options[ 'tokenize' ] , $options[ 'external' ] );
	}

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
		$segments	= array();

		return $segments;
	}



	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function parse( &$segments )
	{
		$total 		= count( $segments );

		$layouts 	= $this->getAvailableLayouts( 'Unity' );

		$vars[ 'view' ]	= $segments[ 0 ];


		return $vars;
	}

}
