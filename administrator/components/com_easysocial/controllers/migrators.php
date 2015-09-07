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

class EasySocialControllerMigrators extends EasySocialController
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
	}

	/**
	 * Discover .points files from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function check()
	{
		Foundry::checkToken();

		// Retrieve the view.
		$view 	= Foundry::view( 'Migrators', true );

		$component = JRequest::getVar( 'component', '');

		$migrator = Foundry::get( 'Migrators', $component );
		$obj 	  = $migrator->isComponentExist();

		// Return the data back to the view.
		return $view->call( __FUNCTION__ , $obj );
	}


	public function process()
	{
		Foundry::checkToken();

		// Retrieve the view.
		$view 	= Foundry::view( 'Migrators', true );

		$component 	= JRequest::getVar( 'component', '');
		$item 		= JRequest::getVar( 'item', '' );
		$mapping 	= JRequest::getVar( 'mapping', '' );

		$migrator = Foundry::get( 'Migrators', $component );
		$migrator->setUserMapping( $mapping );
		$obj 	  = $migrator->process( $item );

		// Return the data back to the view.
		return $view->call( __FUNCTION__ , $obj );
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
		$view 		= Foundry::view( 'Privacy', true );

		// Get the current path that we should be searching for.
		$file 		= JRequest::getVar( 'file' , '' );

		// Log errors when invalid data is passed in.
		if( empty( $file ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'Privacy Scan: Invalid file path given to scan.' );
		}

		// Retrieve the points model to scan for the path
		$model 	= Foundry::model( 'Privacy' );

		$obj 			= new stdClass();

		// Format the output to display the relative path.
		$obj->file		= str_ireplace( JPATH_ROOT , '' , $file );
		$obj->rules 	= $model->install( $file );

		return $view->call( __FUNCTION__ , $obj );
	}
}
