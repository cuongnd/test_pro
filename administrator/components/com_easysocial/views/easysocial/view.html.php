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

Foundry::import( 'admin:/views/views' );

class EasySocialViewEasySocial extends EasySocialAdminView
{
	/**
	 * Main method to display the dashboard view.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function display( $tpl = null )
	{
		// Add heading here.
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_DASHBOARD' ) );

		// Set page icon.
		$this->setIconUrl( rtrim( JURI::root() , '/' ) . '/media/com_easysocial/images/icons/logo/large.png' , false );

		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_DASHBOARD' ) );

		// Get users model
		$usersModel	= Foundry::model( 'Users' );

		// Get total albums
		$photosModel	= Foundry::model( 'Albums' );
		$totalAlbums 	= $photosModel->getTotalAlbums();

		// Get mailer model
		$mailerModel 	= Foundry::model( 'Mailer' );
		$mailStats 		= $mailerModel->getDeliveryStats();

		// profiles signup data
		$profilesModel	= Foundry::model( 'Profiles' );
		$signupData		= $profilesModel->getRegistrationStats();

		$xAxes			= array();

		foreach( $signupData->dates as $date )
		{
			$xAxes[] 	= Foundry::date( $date )->format( 'jS M' );
		}

		$this->set( 'mailStats'			, $mailStats );
		
		$this->set( 'axes' 				, $xAxes );
		$this->set( 'signupData'		, $signupData );
		$this->set( 'pendingUsers'		, $usersModel->getPendingUsers() );
		$this->set( 'totalUsers' 		, $usersModel->getTotalUsers() );
		$this->set( 'totalOnline'		, $usersModel->getTotalOnlineUsers() );
		$this->set( 'totalAlbums'		, $totalAlbums );

		// Add Joomla button
		if( Foundry::user()->authorise( 'core.admin' , 'com_easysocial' ) )
		{
			JToolbarHelper::preferences( 'com_easysocial' );	
		}

		// Add clear cache button here.
		JToolbarHelper::custom( 'clearCache' , 'trash' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_BUTTON_PURGE_CACHE' ) , false );

		echo parent::display( 'admin/easysocial/default' );
	}

	/**
	 * Post process after clearing cache files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function clearUrls()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial' );
	}

	/**
	 * Post process after clearing cache files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function clearCache()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial' );
	}

	/**
	 * Post process after synchronizing the database columns
	 *
	 * @since	1.0
	 * @access	public
	 * @return	
	 */
	public function sync()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial' );
	}

}
