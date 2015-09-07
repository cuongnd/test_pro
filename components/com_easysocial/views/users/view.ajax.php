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

Foundry::import( 'site:/views/views' );

class EasySocialViewUsers extends EasySocialSiteView
{
	/**
	 * Post processing after filtering users
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of SocialUser objects
	 */
	public function getUsers( $users , $isSort = false, $pagination = null )
	{
		$ajax 		= Foundry::ajax();

		$filter 	= JRequest::getWord( 'filter' );
		$sort 		= JRequest::getWord( 'sort' );

		$theme 		= Foundry::themes();
		$theme->set( 'users' 	, $users );
		$theme->set( 'isSort' 	, $isSort );
		$theme->set( 'filter'	, $filter );
		$theme->set( 'sort'		, $sort );
		$contents 	= $theme->output( 'site/users/default.list' );

		if($pagination )
		{
			$contents .= '<div class="es-pagination-footer">' . $pagination->getListFooter( 'site' ) . '</div>';
		}

		return $ajax->resolve( $contents );
	}
}
