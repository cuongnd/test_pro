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

Foundry::import( 'admin:/controllers/controller' );

class EasySocialControllerReports extends EasySocialController
{
	/**
	 * Deletes specific reports
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function removeItem()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view.
		$view	= $this->getCurrentView();

		// Get the id from the request
		$id 	= JRequest::getInt( 'id' );

		// Load the report
		$report 	= Foundry::table( 'Report' );
		$report->load( $id );

		// Try to delete the report now.
		$state 	= $report->delete();

		if( !$state )
		{
			$view->setMessage( $report->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// @points: reports.delete
		// Deduct points from the author when their report is deleted.
		$points = Foundry::points();
		$points->assign( 'reports.delete' , 'com_easysocial' , $report->created_by );

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_REPORTS_REPORT_ITEM_HAS_BEEN_DELETED' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Deletes reports
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function remove()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view.
		$view	= $this->getCurrentView();

		// Get the id from the request
		$ids 	= JRequest::getVar( 'cid' );

		// If the user is deleting with the checkbox, find similar reports
		$model 	= Foundry::model( 'Reports' );

		foreach( $ids as $id )
		{
			$tmpReport 	= Foundry::table( 'Report' );
			$tmpReport->load( $id );
			
			// Load all related reports
			$reports 	= $model->getReporters( $tmpReport->extension , $tmpReport->uid , $tmpReport->type );
		
			foreach( $reports as $report )
			{
				$report->delete();

				// @points: reports.delete
				// Deduct points from the author when their report is deleted.
				$points = Foundry::points();
				$points->assign( 'reports.delete' , 'com_easysocial' , $report->created_by );
			}
		}


		$view->setMessage( JText::_( 'COM_EASYSOCIAL_REPORTS_REPORT_ITEM_HAS_BEEN_DELETED' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Purge all reports on site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function purge()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get current view
		$view 	= $this->getCurrentView();
		
		// Get reports model
		$model 	= Foundry::model( 'Reports' );

		$state 	= $model->purge();

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_REPORTS_PURGED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Stores a submitted report
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getReporters()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get current view.
		$view 		= $this->getCurrentView();

		// Get the report id.
		$id 		= JRequest::getInt( 'id' );

		if( !$id )
		{
			$view->setMessage( JText::_( 'Invalid report id provided.' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$report 	= Foundry::table( 'Report' );
		$report->load( $id );

		$model 		= Foundry::model( 'Reports' );
		$reporters	= $model->getReporters( $report->extension , $report->uid , $report->type );

		return $view->call( __FUNCTION__ , $reporters );
	}
}