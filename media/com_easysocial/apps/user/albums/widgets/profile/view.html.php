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
 * Profile view for Notes app.
 *
 * @since	1.0
 * @access	public
 */
class AlbumsWidgetsProfile extends SocialAppsWidgets
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
		$config 	= Foundry::config();
		
		if( !$config->get( 'photos.enabled' ) ) 
		{
			return;
		}

		// Get the user params
		$params 	= $this->getUserParams( $user->id );

		// Get the app params
		$appParam	= $this->app->getParams();

		// User might not want to show this app in their profile.
		if( !$params->get( 'showalbums' , $appParam->get( 'showalbums' , true ) ) )
		{
			return;
		}

		echo $this->getAlbums( $user , $params );
	}

	/**
	 * Display the list of photos a user has uploaded
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getAlbums( $user , $params )
	{
		// Load up albums model
		$model	 	= Foundry::model( 'Albums' );

		$options 	= array( 'uid' => $user->id , 'type' => SOCIAL_TYPE_USER , 'core' => false );
		$albums 	= $model->getAlbums( $user->id , SOCIAL_TYPE_USER );

		if( !$albums )
		{
			return;
		}
	
		$total		= $model->getTotalAlbums( $options );

		$this->set( 'total'		, $total );
		$this->set( 'appParams'	, $this->app->getParams() );
		$this->set( 'params'	, $params );
		$this->set( 'user'		, $user );
		$this->set( 'albums'	, $albums );

		return parent::display( 'widgets/profile/albums' );
	}
}