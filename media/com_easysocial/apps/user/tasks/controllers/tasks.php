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


class TasksControllerTasks extends SocialAppsController
{
	/**
	 * Unresolve a task
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unresolve()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in.
		Foundry::requireLogin();

		// Get the ajax object.
		$ajax 		= Foundry::ajax();

		$my 		= Foundry::user();
		$id 		= JRequest::getInt( 'id' );
		$task 		= $this->getTable( 'Task' );
		$state 		= $task->load( $id );

		// Title should never be empty.
		if( !$id || !$state )
		{
			return $ajax->reject( JText::_( 'The id provided is invalid.' ) );
		}

		// Title should never be empty.
		if( $task->user_id != $my->id )
		{
			return $ajax->reject( JText::_( 'You do not have access to remove this task.' ) );
		}

		if( !$task->unresolve() )
		{
			return $ajax->reject( $task->getError() );
		}

		// Return the ajax response.
		return $ajax->resolve();
	}

	/**
	 * When a note is stored, this method would be invoked.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function resolve()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in.
		Foundry::requireLogin();

		// Get the ajax object.
		$ajax 		= Foundry::ajax();

		$my 		= Foundry::user();
		$id 		= JRequest::getInt( 'id' );
		$task 		= $this->getTable( 'Task' );
		$state 		= $task->load( $id );

		// Title should never be empty.
		if( !$id || !$state )
		{
			return $ajax->reject( JText::_( 'The id provided is invalid.' ) );
		}

		// Title should never be empty.
		if( $task->user_id != $my->id )
		{
			return $ajax->reject( JText::_( 'You do not have access to remove this task.' ) );
		}

		if( !$task->resolve() )
		{
			return $ajax->reject( $task->getError() );
		}

		// Return the ajax response.
		return $ajax->resolve();
	}

	/**
	 * When a note is stored, this method would be invoked.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function save()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in.
		Foundry::requireLogin();

		// Get the ajax object.
		$ajax 		= Foundry::ajax();

		$my 		= Foundry::user();
		$title 		= JRequest::getVar( 'title' );

		// Title should never be empty.
		if( empty( $title ) )
		{
			return $ajax->reject( JText::_( 'Please enter a valid title' ) );
		}

		$task 			= $this->getTable( 'Task' );
		$task->title 	= $title;
		$task->user_id	= $my->id;

		// By default the state is not done.
		$task->state 	= 0;

		// Store the note.
		if( $task->store() )
		{
			// Add stream.
			$stream	= Foundry::stream();

			$data	= $stream->getTemplate();
			$data->setActor( $my->id, SOCIAL_STREAM_ACTOR_TYPE_USER );
			$data->setContext( $task->id, SOCIAL_STREAM_CONTEXT_TASKS);
			$data->setVerb( 'add' );
			$data->setType( 'mini' );

			$data->setPublicStream( 'core.view' );


			$stream->add( $data );
		}

		// Get the theme
		$theme 		= Foundry::themes();
		$theme->set( 'task' , $task );
		$contents 	= $theme->output( 'apps/user/tasks/default.dashboard.item' );

		// Return the ajax response.
		return $ajax->resolve( $contents );
	}

	/**
	 * When a note is stored, this method would be invoked.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function remove()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in.
		Foundry::requireLogin();

		// Get the ajax object.
		$ajax 		= Foundry::ajax();

		$my 		= Foundry::user();
		$id 		= JRequest::getInt( 'id' );
		$task 		= $this->getTable( 'Task' );
		$state 		= $task->load( $id );

		// Title should never be empty.
		if( !$id || !$state )
		{
			return $ajax->reject( JText::_( 'The id provided is invalid.' ) );
		}

		// Title should never be empty.
		if( $task->user_id != $my->id )
		{
			return $ajax->reject( JText::_( 'You do not have access to remove this task.' ) );
		}

		if( !$task->delete() )
		{
			return $ajax->reject( $task->getError() );
		}

		// Return the ajax response.
		return $ajax->resolve();
	}

	/**
	 * Deletes a note.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function delete()
	{
		$my 	= Foundry::user();
		$id 	= JRequest::getInt( 'noteid' );

		$note 	= $this->table( 'Note' );
		$note->load( $id );

		if( !$note->delete() )
		{
			Foundry::getInstance( 'Info' )->set( $note->getError() , 'error' );

			return $this->redirect( 'index.php?option=com_easysocial&view=dashboard' );
		}

		Foundry::getInstance( 'Info' )->set( 'Note deleted successfully' , 'success' );
		$this->redirect( 'index.php?option=com_easysocial&view=dashboard#app-notes' );
	}
}
