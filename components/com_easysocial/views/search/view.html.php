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

class EasySocialViewSearch extends EasySocialSiteView
{
	/**
	 * Responsible to output the search layout.
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	public function display( $tpl = null )
	{
		// Get the current logged in user.

		$query	= JRequest::getVar( 'q', NULL );
		$type	= JRequest::getString( 'type', '' );

		$indexModel 	= Foundry::model( 'Indexer' );


		// make the the type are the supported type.
		$supportedType = $indexModel->getSupportedType();

		if( ! in_array( $type, $supportedType ) )
		{
			$type = '';
		}

		$my 	= Foundry::user();

		// default value.
		$data			= null;
		$types			= null;
		$count 			= 0;
		$next_limit 	= '';
		$limit 			= Foundry::themes()->getConfig()->get( 'search_limit' );

		$model 	= Foundry::model( 'Search' );

		if( !empty( $query ) )
		{
			$data			= $model->getItems( $query, $type, $next_limit, $limit );
			$count   		= $model->getCount();
			$next_limit 	= $model->getNextLimit();

			// @badge: search.create
			// Assign badge for the person that initiated the friend request.
			$badge 	= Foundry::badges();
			$badge->log( 'com_easysocial' , 'search.create' , $my->id , JText::_( 'COM_EASYSOCIAL_SEARCH_BADGE_SEARCHED_ITEM' ) );
		}

		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_SEARCH' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_SEARCH' ) );

		// get types
		$types	= $model->getTypes();

		foreach( $types as &$type )
		{
			$type->icon = 'file';

			if( $type->utype == 'users' )
			{
				$type->icon 	= 'user';
			}

			if( $type->utype == 'photos' )
			{
				$type->icon		= 'picture';
			}

			if( $type->utype == 'lists' )
			{
				$type->icon		= 'bookmark';
			}

			if( $type->utype == 'albums' )
			{
				$type->icon		= 'pictures';
			}
		}

		$this->set( 'types'		, $types );
		$this->set( 'data'		, $data );
		$this->set( 'query'		, $query );
		$this->set( 'total'		, $count );
		$this->set( 'next_limit', $next_limit);
		$this->set( 'totalcount', $count );

		echo parent::display( 'site/search/default' );
	}
}
