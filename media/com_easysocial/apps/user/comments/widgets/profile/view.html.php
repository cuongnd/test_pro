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

class CommentsWidgetsProfile extends SocialAppsWidgets
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
		// Load up Komento's helper library
		$file 	= JPATH_ROOT . '/components/com_komento/helpers/helper.php';

		if( !JFile::exists( $file ) )
		{
			return;
		}
		
		require_once( $file );

		// Get the user params
		$params 	= $this->getUserParams( $user->id );

		// User might not want to show this app in their profile.
		if( !$params->get( 'widget-profile' , true ) )
		{
			return;
		}

		echo $this->getRecentComments( $user , $params );
	}


	/**
	 * Display the list of photos a user has uploaded
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getRecentComments( $user , $params )
	{
		$user	=  Foundry::user( $user->id );
		$my		=  Foundry::user();

		// Get the Komento comments model
		$model	= Komento::getModel( 'comments' );

		$params	= $this->getUserParams( $user->id );

		// Set options for comments retrival
		$options = array(
						'userid'		=> $user->id,
						'threaded'		=> 0,
						'sort'			=> 'latest',
						'limit'			=> $params->get( 'total-profile' , 5 )
						);

		// Get list of comments created by the user on the site.
		$result		= $model->getComments( 'all', 'all', $options );
		$comments	= array();

		if( $result )
		{
			foreach( $result as $row )
			{
				$row 	= Komento::getHelper( 'comment' )->process( $row );

				if( $row === false )
				{
					continue;
				}

				$comments[]	= $row;
			}
		}

		$this->set( 'comments'	, $comments );
		$this->set( 'user'		, $user );
		$this->set( 'params'	, $params );

		return parent::display( 'widgets/profile/default' );
	}
}