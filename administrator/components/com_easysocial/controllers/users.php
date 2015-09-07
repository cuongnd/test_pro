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

class EasySocialControllerUsers extends EasySocialController
{
	public function __construct()
	{
		parent::__construct();

		// Map the alias methods here.
		$this->registerTask( 'save'		, 'store' );
		$this->registerTask( 'savenew' 	, 'store' );
		$this->registerTask( 'apply'    , 'store' );

		$this->registerTask( 'publish'	, 'togglePublish' );
		$this->registerTask( 'unpublish', 'togglePublish' );

		$this->registerTask( 'activate'		, 'toggleActivation' );
		$this->registerTask( 'deactivate'	, 'toggleActivation' );
	}

	/**
	 * Toggle's user publishing state
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function togglePublish()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current task
		$task 	= $this->getTask();

		// Get the user's id.
		$ids 	= JRequest::getVar( 'cid' );

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the state
		$method 	= $task == 'unpublish' ? 'block' : 'unblock';

		foreach( $ids as $id )
		{
			$user 	= Foundry::user( $id );

			if( $user == $my->id )
			{
				// Do not allow the person to block themselves.
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_NOT_ALLOWED_TO_BLOCK_SELF' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ , $task );
			}

			$state 	= $user->$method();
		}

		$message 	= $task == 'unpublish' ? 'COM_EASYSOCIAL_USERS_UNPUBLISHED_SUCCESSFULLY' : 'COM_EASYSOCIAL_USERS_PUBLISHED_SUCCESSFULLY';
		$message 	= JText::_( $message );

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $task );
	}

	/**
	 * Toggles activation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function activate()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current task
		$view	= $this->getCurrentView();

		// Get the user id that we want to modify now
		$ids 	= JRequest::getVar( 'cid' );

		// Ensure that it's an array
		$ids 	= Foundry::makeArray( $ids );

		foreach( $ids as $id )
		{
			$user 	= Foundry::user( $id );

			$user->activate();
		}

		$view->setMessage( JText::_( 'User account activated successfully' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Switches a user's profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function switchProfile()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get affected users
		$ids 	= JRequest::getVar( 'cid' );

		// Get the profile to switch to
		$profileId	= JRequest::getInt( 'profile' );

		$profileModel	= Foundry::model( 'Profiles' );

		foreach( $ids as $id )
		{
			// Switch the user's profile
			$user 	= Foundry::user( $id );
			$profileModel->updateUserProfile( $id , $profileId );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_USER_PROFILE_UPDATED' ) );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Inserts points for a list of users
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function insertPoints()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 		= $this->getCurrentView();

		// Total points to insert for the user
		$points 	= JRequest::getInt( 'points' );

		// Get the custom message to insert
		$message 	= JRequest::getVar( 'message' );

		// Get list of users to assign points to
		$uids 		= JRequest::getVar( 'uid' );
		$uids 		= Foundry::makeArray( $uids );

		// If user is not provided, break this
		if( empty( $uids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_UNABLE_TO_FIND_USER' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// Load up our own points library.
		$lib 	= Foundry::points();

		foreach( $uids as $userId )
		{
			$user 	= Foundry::user( $userId );

			$lib->assignCustom( $user->id , $points , $message );
		}

		$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_USERS_POINTS_ASSIGNED_TO_USERS' , $points ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Inserts a badge for a list of users
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function insertBadge()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 		= $this->getCurrentView();

		// Get the badge to insert
		$id 		= JRequest::getInt( 'id' );
		$badge 		= Foundry::table( 'Badge' );
		$badge->load( $id );

		if( !$id || !$badge->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_UNABLE_TO_FIND_BADGE' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$uids 		= JRequest::getVar( 'uid' );
		$uids 		= Foundry::makeArray( $uids );

		if( empty( $uids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_UNABLE_TO_FIND_USER' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$model 	= Foundry::model( 'Badges' );

		// Get custom message
		$message	= JRequest::getVar( 'message' );

		// Get custom achieved date
		$achieved 	= JRequest::getVar( 'achieved' );

		foreach( $uids as $userId )
		{
			$user 	= Foundry::user( $userId );

			// Only create a new record if user hasn't achieved the badge yet.
			if( !$model->hasAchieved( $badge->id , $user->id ) )
			{
				// Insert the badge
				$lib 	= Foundry::badges();

				$state 		= $lib->create( $badge , $user , $message , $achieved );

				if( $state )
				{
					$lib->addStream( $badge , $user->id );
					$lib->sendNotification( $badge , $user->id );
				}
			}
		}

		$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_USERS_BADGE_ASSIGNED_TO_USERS' , $badge->get( 'title' ) ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Retrieves the total number of pending users on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTotalPending()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$view 	= $this->getCurrentView();

		$model 	= Foundry::model( 'Users' );

		$total 	= $model->getTotalPending();

		return $view->call( __FUNCTION__ , $total );
	}

	/**
	 * Allows caller to remove a badge
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function removeBadge()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the badge id.
		$id 	= JRequest::getInt( 'id' );
		$userId	= JRequest::getInt( 'userid' );

		// Load up the badge library
		$badge	= Foundry::badges();

		$badge->remove( $id , $userId );

		$view->setMessage( JText::_( 'Achievement removed from user successfully.' ) );
		$view->call( __FUNCTION__ );
	}

	/**
	 * Deletes a user from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the list of user that needs to be deleted.
		$ids 	= JRequest::getVar( 'id' );

		// Ensure that the id's are in an array
		$ids 	= Foundry::makeArray( $ids );

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Let's loop through all of the users now
		foreach( $ids as $id )
		{
			$user 	= Foundry::user( $id );

			if( $user )
			{
				$user->delete();
			}
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Approves a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function approve()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get current view.
		$view 	= $this->getCurrentView();

		// Get the user's id
		$ids 	= JRequest::getVar( 'id' );

		if( !$ids )
		{
			$ids 	= JRequest::getVar( 'cid' );
		}

		// Ensure that they are in an array
		$ids 	= Foundry::makeArray( $ids );

		if( !$ids )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// Determine if we should send a confirmation email to the user.
		$sendEmail	 = JRequest::getVar( 'sendConfirmationEmail' ) ? true : false;

		foreach( $ids as $id )
		{
			// Get the user.
			$user 	= Foundry::user( $id );

			$user->approve( $sendEmail );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_APPROVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $user );
	}


	/**
	 * Approves a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reject()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get the user's id
		$ids 	= JRequest::getVar( 'id' );

		$ids 	= Foundry::makeArray( $ids );

		// Get current view.
		$view 	= $this->getCurrentView();

		// Determine if we should send a confirmation email to the user.
		$sendEmail	 = JRequest::getVar( 'sendRejectEmail' ) ? true : false;

		// Determine if we should delete the user.
		$deleteUser = JRequest::getVar( 'deleteUser' ) ? true : false;

		// Get the rejection message
		$reason 	= JRequest::getVar( 'reason' );

		foreach( $ids as $id )
		{
			// Get the user.
			$user 	= Foundry::user( $id );

			// Try to approve the user.
			$user->reject( $reason , $sendEmail , $deleteUser );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_REJECTED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Assigns user to a specific group
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function assign()
	{
		// Check for request forgeries
		Foundry::checkToken();

		$view 	= $this->getCurrentView();

		$ids 	= JRequest::getVar( 'cid' );

		// Ensure that id's are in an array
		$ids 	= Foundry::makeArray( $ids );

		// Get the group id
		$gid 	= JRequest::getInt( 'gid' );

		if( !$ids )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_UNABLE_TO_FIND_USER' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		if( !$gid )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_UNABLE_TO_FIND_GROUP' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		foreach( $ids as $id )
		{
			$user 	= Foundry::user( $id );

			$user->assign( $gid );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_USERS_ASSIGNED_TO_GROUP' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Stores the user object
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Load front end's language file
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current task
		$task 	= $this->getTask();

		// Determine if this is an edited user.
		$id 	= JRequest::getInt( 'id' );
		$id 	= !$id ? null : $id;

		// Get the posted data
		$post	= JRequest::get( 'post' );

		// this should come from backend user management page only.
		$autoApproval = isset( $post['autoapproval'] ) ? $post['autoapproval'] : 0;

		// Create an options array for custom fields
		$options = array();

		if( !$id )
		{
			$user 		= new SocialUser();

			// Get the profile id
			$profileId	= JRequest::getInt( 'profileId' );
		}
		else
		{
			// Here we assume that the user record already exists.
			$user 				= Foundry::user( $id );

			// Get the profile id from the user
			$profileId 			= $user->getProfile()->id;

			$options['data'] 		= true;
			$options['dataId']		= $id;
			$options['dataType']	= SOCIAL_TYPE_USER;
		}

		// Set the profile id
		$options['profile_id']	= $profileId;

		// Load the profile
		$profile 	= Foundry::table( 'Profile' );
		$profile->load( $profileId );

		// Set the visibility
		$options['visible']		= SOCIAL_PROFILES_VIEW_EDIT;

		// Get fields model
		$fieldsModel			= Foundry::model( 'Fields' );

		// Get the custom fields
		$fields					= $fieldsModel->getCustomFields( $options );

		// Initialize default registry
		$registry 				= Foundry::registry();

		// Get disallowed keys so we wont get wrong values.
		$disallowed 			= array( Foundry::token() , 'option' , 'task' , 'controller', 'autoapproval' );

		// Process $_POST vars
		foreach( $post as $key => $value )
		{
			if( !in_array( $key , $disallowed ) )
			{
				if( is_array( $value ) )
				{
					$value  = Foundry::json()->encode( $value );
				}
				$registry->set( $key , $value );
			}
		}

		// Convert the values into an array.
		$data		= $registry->toArray();

		// Get the fields lib
		$fieldsLib	= Foundry::fields();

		// Build arguments to be passed to the field apps.
		$args 		= array( &$data , &$user );

		// @trigger onAdminEditValidate
		$errors		= $fieldsLib->trigger( 'onAdminEditValidate', SOCIAL_FIELDS_GROUP_USER, $fields, $args );

		// If there are errors, we should be exiting here.
		if( is_array( $errors ) && count( $errors ) > 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_SAVE_ERRORS' ), SOCIAL_MSG_ERROR );

			// We need to set the data into the post again because onEditValidate might have changed the data structure
			JRequest::set( $data, 'post' );

			return $view->call( 'form', $errors );
		}

		// @trigger onAdminEditBeforeSave
		$errors		= $fieldsLib->trigger( 'onAdminEditBeforeSave', SOCIAL_FIELDS_GROUP_USER, $fields, $args );

		if( is_array( $errors ) && count( $errors ) > 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_ERRORS_IN_FORM' ), SOCIAL_MSG_ERROR );

			// We need to set the data into the post again because onEditValidate might have changed the data structure
			JRequest::set( $data, 'post' );

			return $view->call( 'form' , $errors );
		}

		// Update the user's gid
		$gid 	= JRequest::getVar( 'gid' );
		$data[ 'gid' ]	= $gid;

		// Bind the user object with the form data.
		$user->bind( $data );


		// Create a new user record if the id don't exist yet.
		if( !$id )
		{
			$model		= Foundry::model( 'Users' );
			$user 		= $model->create( $data , $user , $profile );

			if( $autoApproval )
			{
				// let approve this user. since this user created by admin, we dont need to notify the user.
				$user->approve( false );
			}

			// @TODO: Send email notifications

			$message 	= ( $autoApproval ) ? JText::_( 'COM_EASYSOCIAL_USERS_CREATED_SUCCESSFULLY_AND_APPROVED' ) : JText::_( 'COM_EASYSOCIAL_USERS_CREATED_SUCCESSFULLY' );
		}
		else
		{
			// If this was an edited user, save the user object.
			$user->save();

			$message 	= JText::_( 'COM_EASYSOCIAL_USERS_USER_UPDATED_SUCCESSFULLY' );
		}

		// Reconstruct args
		$args 		= array( &$data , &$user );

		// @trigger onEditAfterSave
		$fieldsLib->trigger( 'onAdminEditAfterSave', SOCIAL_FIELDS_GROUP_USER, $fields, $args );

		// Bind the custom fields for the user.
		$user->bindCustomFields( $data );

		// Reconstruct args
		$args 		= array( &$data , &$user );

		// @trigger onEditAfterSaveFields
		$fieldsLib->trigger( 'onAdminEditAfterSaveFields' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// Process notifications
		if( isset( $post[ 'notifications' ] ) && !empty( $post[ 'notifications' ] ) )
		{
			$systemNotifications	= $post[ 'notifications' ][ 'system' ];
			$emailNotifications		= $post[ 'notifications' ][ 'email' ];

			// Store the notification settings for this user.
			$model 	= Foundry::model( 'Notifications' );

			$model->saveNotifications( $systemNotifications , $emailNotifications , $user );
		}

		// Process privacy items
		if( isset( $post[ 'privacy' ] ) && !empty( $post[ 'privacy' ] ) )
		{
			$resetPrivacy = isset( $post['privacyReset'] ) ? true : false;

			$user->bindPrivacy( $post[ 'privacy' ] , $post[ 'privacyID' ] , $post[ 'privacyCustom' ], $post[ 'privacyOld' ], $resetPrivacy );
		}

		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $task , $user );
	}
}
