<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewSearch extends EasySocialSiteView
{

	public function getItems( $data, $last_type, $next_limit, $isloadmore = false, $ismini = false, $totalCnt = 0 )
	{
		// Load ajax lib
		$ajax	= Foundry::ajax();

		// Determine if there's any errors on the form.
		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$keywords 	= JRequest::getVar( 'q', '' );

		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'data' , $data );
		$theme->set( 'last_type' , $last_type );
		$theme->set( 'keywords', $keywords );
		$theme->set( 'total', $totalCnt );

		$next_type 		= '';
		$next_update 	= '';


		if( $data )
		{
			foreach( $data as $group => $items )
			{
				foreach( $items as $item )
				{
					$next_type 		= $item->utype;
				}
			}
		}


		$output = '';
		if( $isloadmore )
		{
			$theme->set( 'next_limit' , $next_limit );
			$output = $theme->output( 'site/search/default.list.ajax' );
			return $ajax->resolve( $output, $next_type, $next_limit );
		}
		else if( $ismini )
		{
			$output 	= $theme->output( 'site/search/default.list.mini' );
			return $ajax->resolve( $output );
		}
		else
		{
			$theme->set( 'next_limit' , $next_limit );
			$output 	= $theme->output( 'site/search/default.list' );

			return $ajax->resolve( $output );
		}


	}


	public function getActivities( $data, $nextlimit, $isloadmore = false )
	{
		// Load ajax lib
		$ajax	= Foundry::ajax();

		// Determine if there's any errors on the form.
		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'activities' , $data );
		$theme->set( 'nextlimit' , $nextlimit );

		$output = '';
		if( $isloadmore )
		{
			if( $data )
			{
				foreach( $data as $activity ){
					$output .= $theme->loadTemplate( 'site/activities/default.activities.item' , array( 'activity' => $activity ) );
				}
			}

			return $ajax->resolve( $output, $nextlimit );
		}
		else
		{
			$output 	= $theme->output( 'site/activities/default.activities' );

			return $ajax->resolve( $output );
		}

	}

}
