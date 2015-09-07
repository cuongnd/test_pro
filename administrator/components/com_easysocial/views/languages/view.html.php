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

// Include main views file.
Foundry::import( 'admin:/views/views' );

class EasySocialViewLanguages extends EasySocialAdminView
{
	/**
	 * Default user listings page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function display( $tpl = null )
	{
		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_LANGUAGES' ) );

		// Set page icon.
		$this->setIcon( 'icon-jar jar-directions' );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_LANGUAGES' ) );

		$config 	= Foundry::config();

		$key 		= $config->get( 'general.key' );

		if( !$key )
		{
			$return 	= base64_encode( 'index.php?option=com_easysocial&view=languages' );

			$this->set( 'return' , $return );

			return parent::display( 'admin/settings/key' );
		}

		// Check if there's any data on the server
		$model 			= Foundry::model( 'Languages' , array( 'initState' => true ) );
		$initialized	= $model->initialized();

		if( !$initialized )
		{
			$this->set( 'key' , $key );

			return parent::display( 'admin/languages/initialize' );
		}

		// Add Joomla buttons
		JToolbarHelper::custom( 'discover' , 'refresh' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_BUTTON_FIND_UPDATES' ) , false );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'install' , 'upload' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_BUTTON_INSTALL_OR_UPDATE' ) );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'purge' , 'purge' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_BUTTON_PURGE_CACHE' ) , false );

		// Get filter states.
		$ordering 	= JRequest::getVar( 'ordering' , $model->getState( 'ordering' ) );
		$direction 	= JRequest::getVar( 'direction'	, $model->getState( 'direction' ) );
		$limit 		= $model->getState( 'limit' );
		$published 	= $model->getState( 'published' );

		$languages 	= $model->getLanguages();

		foreach( $languages as &$language )
		{
			$translators 	= Foundry::json()->decode( $language->translator );
			
			$language->translator 	= $translators;
		}

		$pagination	= $model->getPagination();

		$this->set( 'ordering' 		, $ordering );
		$this->set( 'direction' 	, $direction );
		$this->set( 'languages'		, $languages );
		$this->set( 'pagination'	, $pagination );

		return parent::display( 'admin/languages/default' );
	}

	/**
	 * Discover languages from our server
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function discover()
	{
		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_LANGUAGES' ) );

		// Set page icon.
		$this->setIcon( 'icon-jar jar-directions' );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_LANGUAGES' ) );


		$config 	= Foundry::config();

		$key 		= $config->get( 'general.key' );

		$this->set( 'key' , $key );

		return parent::display( 'admin/languages/initialize' );
	}

	/**
	 * Post processing after purge happens
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function purge()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=languages' );
	}

	/**
	 * Post processing after language has been installed
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function install()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=languages' );
	}
}
