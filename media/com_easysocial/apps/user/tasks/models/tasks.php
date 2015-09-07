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

Foundry::import( 'admin:/includes/model' );

class TasksModel extends EasySocialModel
{
	/**
	 * Retrieves a list of tasks created by a particular user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		$userId		The user's / creator's id.
	 *
	 * @return	Array				A list of notes item.
	 */
	public function getItems( $userId )
	{
		$db 		= Foundry::db();

		// Get sql helper.
		$query 		= $db->sql();

		// Select the table.
		$query->select( '#__social_tasks' );

		// Build the where.
		$query->where( 'user_id' , $userId );

		// Execute the query.
		$db->setQuery( $query );

		// Get the result.
		$tasks		= $db->loadObjectList();

		return $tasks;
	}

}