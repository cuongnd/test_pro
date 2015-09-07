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

// Import main view
Foundry::import( 'site:/views/views' );

class EasySocialViewUsers extends EasySocialSiteView
{
	/**
	 * Displays a list of users on the site
	 *
	 * @access	public
	 * @param	string	The name of the template file to parse; automatically searches through the template paths.
	 * @return	null
	 */
	public function display( $tpl = null )
	{
		// Retrieve the users model
		$model 	= Foundry::model( 'Users' );
		$my 	= Foundry::user();

		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_USERS' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_USERS' ) );

		// Need to exclude the current logged in user.
		$config 	= Foundry::config();
		$admin 		= $config->get( 'users.listings.admin' ) ? true : false;
		$options	= array( 'exclusion' => $my->id , 'includeAdmin' => $admin );

		$limit 		= Foundry::themes()->getConfig()->get( 'userslimit' );
		$options[ 'limit' ]	= $limit;

		$filter 	= JRequest::getWord( 'filter' , 'all' );
		$sort 		= JRequest::getWord( 'sort' , 'latest' );

		// Set the sorting options
		if( $sort == 'alphabetical' )
		{
			$options[ 'ordering' ]	= 'a.name';
			$options[ 'direction' ]	= 'ASC';
		}

		switch( $filter )
		{
			case 'online':
				$options[ 'login' ]	= true;

				break;

			case 'photos':
				$options[ 'picture' ]	= true;
				break;

			default:
				break;
		}

		// we only want published user.
		$options[ 'published' ]	= 1;

		// Retrieve the users
		$result		= $model->getUsers( $options );
		$pagination	= $model->getPagination();

		// Define those query strings here
		$pagination->setVar( 'filter' , $filter );
		$pagination->setVar( 'sort' , $sort );

		$users 		= array();

		foreach( $result as $obj )
		{
			$users[]	= Foundry::user( $obj->id );
		}

		$this->set( 'pagination', $pagination );
		$this->set( 'filter'	, $filter );
		$this->set( 'sort'		, $sort );
		$this->set( 'users'		, $users );

		echo parent::display( 'site/users/default' );
	}
}
