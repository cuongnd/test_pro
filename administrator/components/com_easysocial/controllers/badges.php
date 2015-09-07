<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include main controller
Foundry::import( 'admin:/controllers/controller' );

class EasySocialControllerBadges extends EasySocialController
{
	/**
	 * Class Constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->registerTask( 'save' , 'store' );
		$this->registerTask( 'apply' , 'store' );

		$this->registerTask( 'publish' 	, 'togglePublish' );
		$this->registerTask( 'unpublish', 'togglePublish' );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function remove()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get ids from request
		$ids 	= JRequest::getVar( 'cid' );

		// Ensure that they are in an array form.
		$ids 	= Foundry::makeArray( $ids );

		// Get the current view
		$view 	= $this->getCurrentView();

		if( empty( $ids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_BADGES_INVALID_BADGE_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		foreach( $ids as $id )
		{
			$badge 	= Foundry::table( 'Badge' );
			$badge->load( $id );

			$badge->delete();
		}

		$message 	= JText::_( 'COM_EASYSOCIAL_BADGES_DELETED' );

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $task );
	}

	/**
	 * Toggles the publish state for the badges
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function togglePublish()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get ids from request
		$ids 	= JRequest::getVar( 'cid' );

		// Ensure that they are in an array form.
		$ids 	= Foundry::makeArray( $ids );

		// Get the current view
		$view 	= $this->getCurrentView();

		if( empty( $ids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_BADGES_INVALID_BADGE_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the current task
		$task 	= $this->getTask();

		foreach( $ids as $id )
		{
			$badge 	= Foundry::table( 'Badge' );
			$badge->load( $id );

			$badge->$task();
		}

		$message 	= JText::_( 'COM_EASYSOCIAL_BADGES_PUBLISHED' );

		if( $task == 'unpublish' )
		{
			$message 	= JText::_( 'COM_EASYSOCIAL_BADGES_UNPUBLISHED' );
		}

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $task );
	}

	/**
	 * Saves a badge
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

		// Get the badge id from the request
		$id 	= JRequest::getInt( 'id' );

		// Get the current view
		$view 	= $this->getCurrentView();

		// Try to load the badge now.
		$badge 	= Foundry::table( 'Badge' );
		$badge->load( $id );

		if( !$id || !$badge->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_BADGES_INVALID_BADGE_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the posted data.
		$post 	= JRequest::get( 'POST' );
		$badge->bind( $post );

		// Try to store the badge now
		$state 	= $badge->store();

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_BADGES_UPDATED_SUCCESS' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $this->getTask() , $badge );
	}

	/**
	 * Processes the uploaded rule file.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function upload()
	{
		Foundry::checkToken();

		// Retrieve info lib.
		$info 		= Foundry::info();

		// Retrieve the view.
		$view 		= Foundry::view( 'Points' );

		// Get the current path that we should be searching for.
		$file 		= JRequest::getVar( 'package' , '' , 'FILES');

		// If the tmp_name is empty, we know this is wrong.
		if( !isset( $file[ 'tmp_name' ] ) || empty( $file[ 'tmp_name' ] ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INSTALL_UPLOAD_ERROR_INVALID_TYPE' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}
		//@TODO: Allow zip archives.
		
		// Only allow application/octet-stream
		if( $file[ 'type' ] != 'application/octet-stream' )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INSTALL_UPLOAD_ERROR_INVALID_TYPE' ) , SOCIAL_MSG_ERROR);
			return $view->call( __FUNCTION__ );
		}

		// Get the file path from the file data.
		$path 	= $file[ 'tmp_name' ];

		// Load the model to begin with the installation.
		$model 	= Foundry::model( 'Points' );

		$model->install( $path );

		// Set message
		$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INSTALL_UPLOAD_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $result );
	}

	/**
	 * Discover .points files from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	
	 */
	public function discoverFiles()
	{
		Foundry::checkToken();
		
		// Retrieve the view.
		$view 	= Foundry::view( 'Badges' );

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Badges' );

		// Get the list of paths that may store points
		$config = Foundry::config();
		$paths 	= $config->get( 'badges.paths' );

		// Result set.
		$files	= array();

		foreach( $paths as $path )
		{
			$data 	= $model->scan( $path );

			foreach( $data as $file )
			{
				$files[]	= $file;
			}
		}

		// Return the data back to the view.
		return $view->call( __FUNCTION__ , $files );
	}

	/**
	 * Scans for rules throughout the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function scan()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the allowed rule scan sections
		$config		= Foundry::config();

		// Retrieve info lib.
		$info 		= Foundry::info();

		// Retrieve the view.
		$view 		= $this->getCurrentView();

		// Get the current path that we should be searching for.
		$file 		= JRequest::getVar( 'file' , '' );

		// Log errors when invalid data is passed in.
		if( empty( $file ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'BADGES: Invalid file path given to scan.' );
		}

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Badges' );

		$obj 			= new stdClass();

		// Format the output to display the relative path.
		$obj->file		= str_ireplace( JPATH_ROOT , '' , $file );
		$obj->rules 	= $model->install( $file );

		return $view->call( __FUNCTION__ , $obj );
	}
}
