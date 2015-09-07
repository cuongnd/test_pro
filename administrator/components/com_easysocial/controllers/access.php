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

class EasySocialControllerAccess extends EasySocialController
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

		// Map the alias methods here.
		$this->registerTask( 'save'		, 'store' );
		$this->registerTask( 'savenew' 	, 'store' );
		$this->registerTask( 'apply'    , 'store' );
	}

	/**
	 * Redirects user to the form layout.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function create()
	{
		$this->app->redirect( 'index.php?option=com_easysocial&view=access&layout=form' );
	}

	/**
	 * Stores a new or existing access record.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function store()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get variables from POST
		$post	= JRequest::get( 'POST' );

		// Get current view.
		$view 	= $this->getCurrentView();

		// Get the current task.
		$task 	= $this->getTask();

		// Get the group's id.
		$uid	= JRequest::getInt( 'uid' );

		// Load up the access table binding.
		$access	= Foundry::table( 'Access' );

		// Try to load the access records.
		$access->load( array( 'uid' => $uid , 'type' => SOCIAL_TYPE_USERGROUP ) );

		// Load the registry
		$registry 	= Foundry::registry( $access->params );

		// We want to exclude some of the variables from the $_POST request.
		$exclude 	= array( Foundry::token() , 'option' , 'controller' , 'uid' , 'task' );

		foreach( $post as $key => $value )
		{
			if( in_array( $key , $exclude ) )
			{
				continue;
			}

			$key 	= str_ireplace( '_' , '.' , $key );

			$registry->set( $key , $value );
		}

		$access->uid 		= $uid;
		$access->type 		= SOCIAL_TYPE_USERGROUP;
		$access->params 	= $registry->toString();

		// Try to store the access item
		if( !$access->store() )
		{
			$view->setError( $access->getError() );
			return $view->call( __FUNCTION__ , $access , $task );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_ACCESS_RULE_SAVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		
		return $view->call( __FUNCTION__ , $access , $task );
	}

	/**
	 * Publishes an access item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function publish()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$cid	= JRequest::getVar( 'cid' );
		$cid 	= Foundry::makeArray( $cid );
		$view 	= Foundry::getInstance( 'View', 'Access' );

		foreach( $cid as $id )
		{
			$access		= Foundry::table( 'Access' );

			// Load the access
			$access->load( $id );

			if( !$access->id )
			{
				$view->setError( JText::_( 'COM_EASYSOCIAL_ACCESS_ERROR_ACCESS_DOES_NOT_EXIST' ) );
				return $view->call( __FUNCTION__ );
			}

			if( !$access->publish() )
			{
				$view->setError( $access->getError() );
				return $view->call( __FUNCTION__ );
			}
		}
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Unpublishes an access item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function unpublish()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$cid	= JRequest::getVar( 'cid' );
		$cid 	= Foundry::makeArray( $cid );
		$view 	= Foundry::getInstance( 'View', 'Access' );

		foreach( $cid as $id )
		{
			$access		= Foundry::table( 'Access' );

			// Load the access
			$access->load( $id );

			if( !$access->id )
			{
				$view->setError( JText::_( 'COM_EASYSOCIAL_ACCESS_ERROR_ACCESS_DOES_NOT_EXIST' ) );
				return $view->call( __FUNCTION__ );
			}

			if( !$access->unpublish() )
			{
				$view->setError( $access->getError() );
				return $view->call( __FUNCTION__ );
			}
		}

		return $view->call( __FUNCTION__ );
	}
}