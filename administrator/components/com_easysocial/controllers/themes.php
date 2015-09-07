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
Foundry::import( 'admin:/controllers/main' );

class EasySocialControllerThemes extends EasySocialController
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->registerTask( 'toggleDefault' , 'makeDefault' );
		$this->registerTask( 'apply' , 'store' );
		$this->registerTask( 'save' , 'store' );
	}

	/**
	 * Set's the template as the default template
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function makeDefault()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$element 	= JRequest::getVar( 'cid' );
		$element 	= $element[ 0 ];
		$element 	= strtolower( $element );

		// Get the current view
		$view 		= $this->getCurrentView();

		// Get the configuration object		
		$configTable	= Foundry::table( 'Config' );
		$config 		= Foundry::registry();

		if( $configTable->load( 'site' ) )
		{
			$config->load( $configTable->value );
		}

		// Convert the config object to a json string.
		$config->set( 'theme.site' , $element );

		// Convert the configuration to string
		$jsonString 		= $config->toString();

		// Store the setting
		$configTable->value	= $jsonString;

		if( !$configTable->store() )
		{
			$view->setMessage( $configTable->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Stores the theme parameter
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function store()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// @TODO: Check if the user has privilege to access this section.

		// Get the element from the query
		$element 	= JRequest::getWord( 'element' , '' );

		// Get the current view
		$view 		= $this->getCurrentView();

		if( !$element )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_THEMES_INVALID_ELEMENT_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $this->getTask() );
		}

		// Load the model
		$model 		= Foundry::model( 'Themes' );
		
		// Format through all the properties that we want to save here.
		$data		= JRequest::get( 'post' );

		// Remove unwanted stuffs from the post data.
		unset( $data[ Foundry::token() ] );
		unset( $data[ 'option' ] );
		unset( $data[ 'controller' ] );
		unset( $data[ 'task' ] );
		unset( $data[ 'element' ] );

		$state 	= $model->update( $element , $data );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $this->getTask() , $element );
		}

		$view->setMessage( JText::sprintf( 'Theme settings for %1s is saved successfully.' , $element ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $this->getTask() , $element );
	}


	/**
	 * Installs a new theme on the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function install()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// @TODO: Check if the user has privilege to access this section.

		// Get the current view
		$view 		= $this->getCurrentView();

		// Load the model
		$model 		= Foundry::model( 'Themes' );
		
		// Get the file from the server.
		$file 		= JRequest::getVar( 'package' , '' , 'FILES' );

		// If the tmp_name is empty, we know this is wrong.
		if( !isset( $file[ 'tmp_name' ] ) || empty( $file[ 'tmp_name' ] ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INSTALL_UPLOAD_ERROR_INVALID_TYPE' ) , SOCIAL_MSG_ERROR );

			return $view->call( 'installCompleted' );
		}

		// Only allow application/octet-stream
		if( $file[ 'type' ] != 'application/zip' )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INSTALL_UPLOAD_ERROR_INVALID_TYPE' ) , SOCIAL_MSG_ERROR);
			return $view->call( 'installCompleted' );
		}

		// Load the model to begin with the installation.
		$model 	= Foundry::model( 'Themes' );
		$state	= $model->install( $file );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );
			return $view->call( 'installCompleted' );
		}

		// Set message
		$view->setMessage( JText::_( 'COM_EASYSOCIAL_THEMES_INSTALLER_THEME_INSTALLED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( 'installCompleted' , $result );
	}

	/**
	 * Returns the template file when loading through mvc.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getAjaxTemplate()
	{
		$templateFiles		= JRequest::getVar( 'names' );

		// Ensure the integrity of each items submitted to be an array.
		$templateFiles		= Foundry::makeArray( $templateFiles );

		$result		= array();

		foreach( $templateFiles as $path )
		{
			$theme = Foundry::get( 'Themes' );
			$theme->extension = 'ejs';
			$output = $theme->output($path);

			$obj			= new stdClass();
			$obj->name		= $file;
			$obj->content	= $output;

			$result[]		= $obj;
		}

		if( !$result )
		{
			header('HTTP/1.1 404 Not Found');
			exit;
		}

		header('Content-type: text/x-json; UTF-8');

		$json 	= Foundry::json();
		echo $json->encode( $result );
		exit;
	}

}
