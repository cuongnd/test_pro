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
 * Dashboard view for the calendar app.
 *
 * @since	1.0
 * @access	public
 */
class CalendarViewCanvas extends SocialAppsView
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
		// Require user to be logged in
		Foundry::requireLogin();

		$model 		= $this->getModel( 'Calendar' );
		$result		= $model->getItems( $userId );

		$user 		= Foundry::user( $userId );

		$schedules 	= array();
	
		if( $user->isViewer() )
		{
			$title 	= JText::_( 'APP_CALENDAR_CANVAS_TITLE_OWNER' );
		}
		else
		{
			$title	= JText::sprintf( 'APP_CALENDAR_CANVAS_TITLE_VIEWER' , $user->getName() );
		}

		Foundry::page()->title( $title );

		if( $result )
		{
			foreach( $result as $row )
			{
				$table 	= $this->getTable( 'Calendar' );
				$table->bind( $row );

				$schedules[]	= $table;
			}	
		}

		$doc 		= JFactory::getDocument();
		$direction	= $doc->getDirection();

		$isRTL 		= $direction == 'rtl' ? true : false;

		$this->set( 'isRTL'		, $isRTL );
		$this->set( 'user'		, $user );
		$this->set( 'schedules' , $schedules );

		echo parent::display( 'canvas/calendar/default' );
	}
}