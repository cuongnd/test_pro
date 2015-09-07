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

Foundry::import( 'admin:/views/views' );

class EasySocialViewUsers extends EasySocialAdminView
{

	/**
	 * Retrieves the total number of pending users on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalPending( $total )
	{
		$ajax 	= Foundry::ajax();

		return $ajax->resolve( $total );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmDelete()
	{
		$ajax 	= Foundry::ajax();

		$ids 	= JRequest::getVar( 'id' );

		// Ensure that it is in an array form
		$ids 	= Foundry::makeArray( $ids );

		$theme 	= Foundry::themes();

		$theme->set( 'ids' , $ids );

		$contents 	= $theme->output( 'admin/users/dialog.delete' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmApprove()
	{
		$ajax 	= Foundry::ajax();

		$ids 	= JRequest::getVar( 'id' );

		// Ensure that it is in an array form
		$ids 	= Foundry::makeArray( $ids );

		$theme 	= Foundry::themes();

		$theme->set( 'ids' , $ids );

		$contents 	= $theme->output( 'admin/users/dialog.approve' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation dialog to reject users
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmReject()
	{
		$ajax 	= Foundry::ajax();

		$ids 	= JRequest::getVar( 'id' );

		// Ensure that it is in an array form
		$ids 	= Foundry::makeArray( $ids );

		$theme 	= Foundry::themes();

		$theme->set( 'ids' , $ids );

		$contents 	= $theme->output( 'admin/users/dialog.reject' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Form for admin to enter a custom message for points assignments
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignPoints()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		// Get the user's association
		$uids 	= JRequest::getVar( 'uid' );
		$uids 	= Foundry::makeArray( $uids );

		$theme->set( 'uids'		, $uids );

		$output = $theme->output( 'admin/users/dialog.assign.points' );

		return $ajax->resolve( $output );
	}

	/**
	 * Form for admin to enter a custom message
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignBadgeMessage()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		// Get the badge to insert
		$id 	= JRequest::getInt( 'id' );
		$badge 	= Foundry::table( 'Badge' );
		$badge->load( $id );

		// Get the user's association
		$uids 	= JRequest::getVar( 'uid' );
		$uids 	= Foundry::makeArray( $uids );

		$theme->set( 'uids'		, $uids );
		$theme->set( 'badge'	, $badge );


		$output = $theme->output( 'admin/users/dialog.assign.badge' );

		return $ajax->resolve( $output );
	}

	/**
	 * Displays the new user form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function newUserForm()
	{
		$ajax 	= Foundry::ajax();


		$theme 	= Foundry::themes();

		$output	= $theme->output( 'admin/users/dialog.new.user' );

		return $ajax->resolve( $output );
	}

	/**
	 * Displays the switch profile form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function switchProfileForm()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		// Get the id's of the user that we are trying to modify
		$ids 	= JRequest::getVar( 'ids' );
		$ids 	= Foundry::makeArray( $ids );


		$theme->set( 'ids' , $ids );

		$output = $theme->output( 'admin/users/dialog.switch.profile' );

		return $ajax->resolve( $output );
	}

	/**
	 * Assign badge for user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignBadge()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$ids 	= JRequest::getVar( 'ids' );
		$ids 	= Foundry::makeArray( $ids );

		$theme->set( 'ids' , $ids );

		$output = $theme->output( 'admin/users/dialog.browse.badge' );

		return $ajax->resolve( $output );
	}

	/**
	 * Retrieves user's recent activity
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getActivity()
	{
		$ajax 	= Foundry::ajax();

		$id 	= JRequest::getInt( 'id' );
		$user 	= Foundry::user( $id );

		if( !$id || !$user->id )
		{
			return $ajax->reject( JText::_( 'COM_EASYSOCIAL_INVALID_USER_ID' ) );
		}

		$config = Foundry::config();
		$max    = $config->get( 'activity.pagination.max', 5);

		// Get user's recent stream
		$stream 	= Foundry::stream();
		$activities	= $stream->getActivityLogs( array( 'uId' => $user->id, 'max' => $max ) );

		$theme		= Foundry::themes();
		$theme->set( 'activities' , $activities );
		$output 	=  $theme->output( 'admin/users/form.activity' );

		return $ajax->resolve( $output );
	}

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

		$title 	= JRequest::getVar( 'dialogTitle' , JText::_( 'COM_EASYSOCIAL_USERS_ASSIGN_USER_GROUP_DIALOG_TITLE' ) );

		$theme 	= Foundry::themes();

		$theme->set( 'dialogTitle' , $title );
		$theme->set( 'callback' , $callback );

		$output = $theme->output( 'admin/users/dialog.browse' );

		return $ajax->resolve( $output );
	}

	/**
	 * Assign users into group
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assign()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$ids 	= JRequest::getVar( 'ids' );
		$ids 	= Foundry::makeArray( $ids );

		$theme->set( 'ids' , $ids );

		$output = $theme->output( 'admin/users/dialog.assign' );

		return $ajax->resolve( $output );
	}

	/**
	 * Displays confirmation dialog to remove a person's badge
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function confirmRemoveBadge()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$id 	= JRequest::getInt( 'id' );
		$userid = JRequest::getInt( 'userid' );

		$theme->set( 'id' , $id );
		$theme->set( 'userid' , $userid );

		$output	= $theme->output( 'admin/users/dialog.remove.badge' );

		return $ajax->resolve( $output );
	}

	/**
	 * Displays error dialog
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function showFormError()
	{
		$ajax = Foundry::ajax();

		$theme = Foundry::themes();

		$contents = $theme->output( 'admin/users/dialog.save.error' );

		return $ajax->resolve( $contents );
	}
}
