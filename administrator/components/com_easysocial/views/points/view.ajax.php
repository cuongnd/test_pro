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

// Include parent view
Foundry::import( 'admin:/views/views' );

class EasySocialViewPoints extends EasySocialAdminView
{
	/**
	 * Assign users into group
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function browse()
	{
		$ajax 		= Foundry::ajax();

		$callback	= JRequest::getWord( 'jscallback' );

		$theme 	= Foundry::themes();
		$theme->set( 'callback' , $callback );

		$output = $theme->output( 'admin/points/dialog.browse' );

		return $ajax->resolve( $output );
	}

	/**
	 * Display dialog for confirming deletion
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function confirmDelete()
	{
		$ajax 	= Foundry::ajax();

		$theme	= Foundry::themes();
		$contents 	= $theme->output( 'admin/points/dialog.delete' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Sends back the list of files to the caller.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function discoverFiles( $files = array() )
	{
		$ajax 		= Foundry::ajax();
		$message 	= JText::sprintf( 'COM_EASYSOCIAL_DISCOVER_FOUND_FILES' , count( $files ) );

		return $ajax->resolve( $files , $message );
	}

	/**
	 * Processes ajax calls to scan rules.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function scan( $obj )
	{
		$ajax 		= Foundry::ajax();
		$message 	= JText::sprintf( 'COM_EASYSOCIAL_DISCOVER_CHECKED_OUT' , $obj->file , count( $obj->rules ) );
		
		return $ajax->resolve( $obj , $message );
	}
}