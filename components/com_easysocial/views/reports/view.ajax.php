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

// Necessary to import the custom view.
Foundry::import( 'site:/views/views' );

class EasySocialViewReports extends EasySocialSiteView
{
	/**
	 * Post processing for storing a report.
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	public function store()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			$message 	= $this->getMessage();

			return $ajax->resolve( '<div class="alert alert-error">' . $message->message . '</div>' );
		}

		$theme = Foundry::themes();

		$html = $theme->output( 'site/reports/dialog.submitted' );

		return $ajax->resolve( $html );
	}

	/**
	 * Dialog to confirm a report.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function confirmReport()
	{
		$ajax 	= Foundry::ajax();
		
		// Check if user is really allowed to submit any reports.
		$access	= Foundry::access();

		if( !$access->allowed( 'reports.submit' ) )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_REPORTS_NOT_ALLOWED_TO_SUBMIT_REPORTS' ) , SOCIAL_MSG_ERROR );
			return $ajax->reject( $this->getMessage() );
		}

		$title 			= JRequest::getVar( 'title' , JText::_( 'COM_EASYSOCIAL_REPORTS_DIALOG_TITLE' ) );
		$description 	= JRequest::getVar( 'description' , '' );

		$theme			= Foundry::themes();
		
		$theme->set( 'title'		, $title );
		$theme->set( 'description'	, $description );
		
		$html = $theme->output( 'site/reports/dialog.form' );

		return $ajax->resolve( $html );
	}
}
