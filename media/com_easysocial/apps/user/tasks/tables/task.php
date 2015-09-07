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

// Import main table to assis this table.
Foundry::import( 'admin:/tables/table' );

/**
 * Tasks object relation mapper.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class TasksTableTask extends SocialTable
{
	/**
	 * The unique task id.
	 * @var	int
	 */
	public $id 		= null;

	/**
	 * The owner of the task.
	 * @var	int
	 */
	public $user_id	= null;

	/**
	 * The task title.
	 * @var	string
	 */
	public $title 	= null;

	/**
	 * The state of the task
	 * @var	string
	 */
	public $state 	= null;

	/**
	 * The date time this task has been created.
	 * @var	datetime
	 */
	public $created 	= null;

	/**
	 * Class Constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct(& $db )
	{
		parent::__construct( '#__social_tasks' , 'id' , $db );
	}

	/**
	 * Marks a task as resolved.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function resolve( $state = 1 )
	{
		$this->state 	= $state;

		if( !$this->store() )
		{
			return false;
		}

		return true;
	}

	/**
	 * Marks a task as resolved.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unresolve()
	{
		return $this->resolve( 0 );
	}
}