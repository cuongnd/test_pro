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

/**
 * Dashboard view for Tasks app.
 *
 * @since	1.0
 * @access	public
 */
class TasksViewDashboard extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null , $docType = null )
	{
		// Obtain the tasks model.
		$model 	= $this->getModel( 'Tasks' );

		// Get the list of items
		$result 	= $model->getItems( $userId );

		// On the dashboard view, we know that we need to fetch the current user's items.
		$my 		= Foundry::user();

		// If there are tasks, we need to bind them with the table.
		$tasks		= array();
		if( $result )
		{
			foreach( $result as $row )
			{
				// Bind the result back to the note object.
				$task 	= $this->getTable( 'Task' );
				$task->bind( $row );

				$tasks[] 	= $task;
			}
		}

		// Push tasks to the theme.
		$this->set( 'tasks'		, $tasks );
		
		echo parent::display( 'default.dashboard' );
	}
}