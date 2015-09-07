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

// Include main controller here.
Foundry::import( 'admin:/controllers/controller' );

/**
 * Settings controller.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class EasySocialControllerSettings extends EasySocialController
{
	/**
	 * Class construct
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		// Register task aliases here.
		$this->registerTask( 'apply' , 'save' );
	}

	/**
	 * Resets the settings to the factory settings
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reset()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view	= $this->getCurrentView();

		// Get the current section
		$page 	= JRequest::getWord( 'section' );

		// We don't really need to do anything here. Just delete the record from the database
		$table 	= Foundry::table( 'Config' );
		$state	= $table->load( array( 'type' => 'site' ) );

		if( $state )
		{
			// Reset this to empty
			$table->value 	= '';

			$state	= $table->store();

			if( !$state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_ERROR_RESET' ) , SOCIAL_MSG_ERROR );

				return $view->call( __FUNCTION__ , $page );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_RESET_SUCCESS' ) );
		return $view->call( __FUNCTION__ , $page );
	}

	/**
	 * Imports the settings from a json file
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function import()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view	= $this->getCurrentView();

		$page 		= JRequest::getVar( 'page' , '' );

		// We don't really need to do anything here. Just delete the record from the database
		$table 	= Foundry::table( 'Config' );
		$state	= $table->load( array( 'type' => 'site' ) );

		if( $state )
		{
			$file 		= JRequest::getVar( 'settings_file' , array() , 'FILES' );

			if( !isset( $file[ 'tmp_name' ] ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_IMPORT_FILE_ERROR' ) );
				return $view->call( __FUNCTION__ , $page );
			}

			$path 		= $file[ 'tmp_name' ];
			$contents	= JFile::read( $path );

			// Ensure that this is a json object
			$obj 		= Foundry::json( $contents );

			if( $obj === false )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_IMPORT_FILE_ERROR_INVALID' ) );
				return $view->call( __FUNCTION__ , $page );
			}

			// Reset this to empty
			$table->value 	= $contents;

			$state	= $table->store();

			if( !$state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_IMPORT_ERROR' ) , SOCIAL_MSG_ERROR );

				return $view->call( __FUNCTION__ , $page );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_IMPORT_SUCCESS' ) );
		return $view->call( __FUNCTION__ , $page );
	}

	/**
	 * Stores the API key
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function savekey()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		$key 		= JRequest::getVar( 'key' );
		$return		= JRequest::getVar( 'return' );
		$return 	= base64_decode( $return );

		$view 		= $this->getCurrentView();

		$config 	= Foundry::config();
		$config->set( 'general.key' , $key );

		// Convert the config object to a json string.
		$jsonString 	= $config->toString();

		$configTable 	= Foundry::table( 'Config' );

		if( !$configTable->load( 'site' ) )
		{
			$configTable->type 	= 'site';
		}

		$configTable->set( 'value' , $jsonString );

		// Try to store the configuration.
		if( !$configTable->store() )
		{
			$view->setMessage( $configTable->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $return );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_SETTINGS_API_KEY_SAVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $return );
	}

	/**
	 * Processes the saving of the settings.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function save()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Since there are more than 1 tasks are linked here, get the appropriate task here.
		$task 		= $this->getTask();
		$method 	= $task;
		$page 		= JRequest::getVar( 'page' , '' );
		$view 		= Foundry::view( 'Settings' );

		// Get the posted data.
		$post		= JRequest::get( 'POST' );

		// Only load the config that is already stored.
		// We don't want to store everything as we want to have hidden settings.
		$configTable	= Foundry::table( 'Config' );
		$config 		= Foundry::registry();

		if( $configTable->load( 'site' ) )
		{
			$config->load( $configTable->value );
		}

		$token 		= Foundry::token();

		if( !$post )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_INVALID_POST_DATA' ) , SOCIAL_MSG_ERROR );
			return $view->call( $method , $page );
		}

		// Some post vars are unwanted / unecessary because of the hidden inputs.
		$ignored 	= array( 'task' , 'option' , 'controller' , 'view' , $token , 'page' );

		$updatedUserIndexing = false;

		foreach( $post as $key => $value )
		{
			if( !in_array( $key , $ignored ) )
			{
				// Replace all _ with .
				$key 		= str_ireplace( '_' , '.' , $key );

				// If the value is an array, and there's only 1 index,
				// the input might need to be checked if it needs to be in an array form.
				// E.g: some,values,here,should,be,an,array
				if( is_array( $value ) && count( $value ) == 1)
				{
					$value 	= Foundry::makeArray( $value[ 0 ] , ',' );
				}

				if( $key == 'users.indexer.name' || $key == 'users.indexer.email' )
				{
					$previousVal = $config->get( $key );
					if( $previousVal != $value )
					{
						$updatedUserIndexing = true;
					}
				}

				$config->set( $key , $value );
			}
		}

		// Convert the config object to a json string.
		$jsonString 	= $config->toString();

		$configTable 	= Foundry::table( 'Config' );

		if( !$configTable->load( 'site' ) )
		{
			$configTable->type 	= 'site';
		}

		$configTable->set( 'value' , $jsonString );


		// Try to store the configuration.
		if( !$configTable->store() )
		{
			$view->setMessage( $configTable->getError() , SOCIAL_MSG_ERROR );
			return $view->call( $method , $page );
		}

		// Check if any of the configurations are stored as non local
		if( $config->get( 'storage.photos' ) == 'amazon' || $config->get( 'storage.conversations' ) == 'amazon' )
		{
			// Initialize the storage
			$bucket 	= Foundry::storage( 'Amazon' )->init();

			$config->set( 'storage.amazon.bucket' , $bucket );

			$configTable->set( 'value' , $config->toString() );

			$configTable->store();
		}

		$message 	= ( $updatedUserIndexing ) ? JText::_( 'COM_EASYSOCIAL_SETTINGS_SAVED_SUCCESSFULLY_WITH_USER_INDEXING_UPDATED' ) : JText::_( 'COM_EASYSOCIAL_SETTINGS_SAVED_SUCCESSFULLY' ) ;

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( $method , $page );
	}

	/**
	 * Refreshes the list of mollom servers.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function refreshMollom()
	{
		// Get mollom's captcha service
		$mollom 	= Foundry::get( 'Captcha' , 'Mollom' );

		// Get list of servers.
		$servers 	= $mollom->getServers();

		// Get JSON library.
		$json 		= Foundry::json();

		// Let's encode this into a json string
		$data 		= $json->encode( $servers );

		// Get configuration object.
		$config 	= Foundry::config();

		// Set the data.
		$config->set( 'antispam.mollom.servers' , $data );

		// Save the configuration.
		$configTable 	= Foundry::table( 'Config' );
		$configTable->load( 'site' );

		$configTable->type 	= 'site';
		$configTable->value	= $config->toString();
		$configTable->store();

		// Foundry::get( 'View' , 'Settings' )->refreshMollom( $servers );
	}
}
