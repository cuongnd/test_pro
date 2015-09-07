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

class EasySocialControllerPoints extends EasySocialController
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

		// Register aliases.
		$this->registerTask( 'apply' , 'save' );
		$this->registerTask( 'unpublish' , 'publish' );
	}

	/**
	 * Deletes a list of provided points
	 *
	 * @since	1.0
	 * @access	public
	 * @return	
	 */
	public function remove()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$ids 	= JRequest::getVar( 'cid' );
		$ids 	= Foundry::makeArray( $ids );

		$view 	= $this->getCurrentView();

		if( empty( $ids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		foreach( $ids as $id )
		{
			$point 	= Foundry::table( 'Points' );
			$point->load( $id );

			$point->delete();
		}

		$message 	= JText::_( 'COM_EASYSOCIAL_POINTS_DELETED_SUCCESSFULLY' );

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Publishes a point
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function publish()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		$id 	= JRequest::getVar( 'cid' );

		// Get current view
		$view 	= $this->getCurrentView();

		// Get current task
		$task 	= $this->getTask();

		// Ensure that it's an array.
		$ids 	= Foundry::makeArray( $id );

		if( empty( $id ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		foreach( $ids as $id )
		{
			$point 	= Foundry::table( 'Points' );
			$point->load( $id );

			$point->$task();
		}

		$message 	= $task == 'publish' ? 'COM_EASYSOCIAL_POINTS_PUBLISHED_SUCCESSFULLY' : 'COM_EASYSOCIAL_POINTS_UNPUBLISHED_SUCCESSFULLY';

		$view->setMessage( JText::_( $message ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Responsible to save a user point.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function save()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get current view
		$view 	= $this->getCurrentView();

		// Get the current task of this request.
		$task 	= $this->getTask();

		// Get the point id.
		$id 	= JRequest::getInt( 'id' );

		$point = Foundry::table( 'Points' );
		$point->load( $id );

		if( !$id || !$point->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $task , $point );
		}

		// Try to bind the data from $_POST now.
		$post 	= JRequest::get( 'POST' );

		$point->bind( $post );

		$state 	= $point->store();

		if( !$state )
		{
			$view->setMessage( $point->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $task , $point );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_POINTS_SAVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $task , $point );
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
		// Check for request forgeries
		Foundry::checkToken();
		
		// Retrieve the view.
		$view 	= $this->getCurrentView();

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Points' );

		// Get the list of paths that may store points
		$config = Foundry::config();
		$paths 	= $config->get( 'points.paths' );

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
		Foundry::checkToken();

		// Get the allowed rule scan sections
		$config		= Foundry::config();

		// Retrieve the view.
		$view 		= $this->getCurrentView();

		// Get the current path that we should be searching for.
		$file 		= JRequest::getVar( 'file' , '' );

		// Log errors when invalid data is passed in.
		if( empty( $file ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'POINTS: Invalid file path given to scan.' );
		}

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Points' );

		$obj 			= new stdClass();
		
		// Format the output to display the relative path.
		$obj->file		= str_ireplace( JPATH_ROOT , '' , $file );
		$obj->rules 	= $model->install( $file );

		return $view->call( __FUNCTION__ , $obj );
	}
}
