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

class EasySocialControllerAlerts extends EasySocialController
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

		$this->registerTask( 'emailPublish' 	, 'togglePublish' );
		$this->registerTask( 'emailUnpublish'	, 'togglePublish' );
		$this->registerTask( 'systemPublish' 	, 'togglePublish' );
		$this->registerTask( 'systemUnpublish'	, 'togglePublish' );
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

		// Get the current task
		$task 	= $this->getTask();

		// Ensure that they are in an array form.
		$ids 	= Foundry::makeArray( $ids );

		// Get the current view
		$view 	= $this->getCurrentView();

		if( empty( $ids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ALERTS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		foreach( $ids as $id )
		{
			$alert 	= Foundry::table( 'Alert' );
			$alert->load( $id );

			if( $task == 'emailUnpublish' )
			{
				$alert->email 	= SOCIAL_STATE_UNPUBLISHED;
			}

			if( $task == 'emailPublish' )
			{
				$alert->email 	= SOCIAL_STATE_PUBLISHED;
			}

			if( $task == 'systemPublish' )
			{
				$alert->system 	= SOCIAL_STATE_PUBLISHED;
			}

			if( $task == 'systemUnpublish' )
			{
				$alert->system 	= SOCIAL_STATE_UNPUBLISHED;
			}

			$alert->store();
		}

		$message 	= JText::_( 'COM_EASYSOCIAL_BADGES_PUBLISHED_SUCCESS' );	
	
		if( $task == 'emailUnpublish' || $task == 'systemUnpublish' )
		{
			$message 	= JText::_( 'COM_EASYSOCIAL_ALERTS_UNPUBLISHED_SUCCESS' );		
		}
		

		if( $task == 'unpublish' )
		{
			$message 	= JText::_( 'COM_EASYSOCIAL_BADGES_UNPUBLISHED' );
		}

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $task );
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
		$model 	= Foundry::model( 'Alert' );

		// Get the list of paths that may store points
		$config = Foundry::config();
		$paths 	= $config->get( 'alerts.paths' );

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
	 * Scans for .alert rules throughout the site.
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
			Foundry::logError( __FILE__ , __LINE__ , 'ALERTS: Invalid file path given to scan.' );
		}

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Alert' );

		$obj 	= new stdClass();
		
		// Format the output to display the relative path.
		$obj->file		= str_ireplace( JPATH_ROOT , '' , $file );
		$obj->rules 	= $model->install( $file );

		return $view->call( __FUNCTION__ , $obj );
	}
}