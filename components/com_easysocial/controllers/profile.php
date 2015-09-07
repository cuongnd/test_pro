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

// Import main controller
Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerProfile extends EasySocialController
{
	/**
	 * Save user's information.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function save()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is registered
		Foundry::requireLogin();

		// Get post data.
		$post 	= JRequest::get( 'POST' );

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get all published fields apps that are available in the current form to perform validations
		$fieldsModel 	= Foundry::model( 'Fields' );

		// Get current user.
		$my 	= Foundry::user();

		// Only fetch relevant fields for this user.
		$options		= array( 'profile_id' => $my->getProfile()->id, 'data' => true, 'dataId' => $my->id, 'dataType' => SOCIAL_TYPE_USER, 'visible' => SOCIAL_PROFILES_VIEW_EDIT );

		$fields			= $fieldsModel->getCustomFields( $options );

		// Load json library.
		$json 		= Foundry::json();

		// Initialize default registry
		$registry 	= Foundry::registry();

		// Get disallowed keys so we wont get wrong values.
		$disallowed = array( Foundry::token() , 'option' , 'task' , 'controller' );

		// Process $_POST vars
		foreach( $post as $key => $value )
		{
			if( !in_array( $key , $disallowed ) )
			{
				if( is_array( $value ) )
				{
					$value  = $json->encode( $value );
				}
				$registry->set( $key , $value );
			}
		}

		// Convert the values into an array.
		$data		= $registry->toArray();

		// Perform field validations here. Validation should only trigger apps that are loaded on the form
		// @trigger onRegisterValidate
		$fieldsLib	= Foundry::fields();

		// Build arguments to be passed to the field apps.
		$args 		= array( &$data , &$my );

		// Ensure that there is no errors.
		// @trigger onEditValidate
		$errors 	= $fieldsLib->trigger( 'onEditValidate' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// If there are errors, we should be exiting here.
		if( is_array( $errors ) && count( $errors ) > 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_SAVE_ERRORS' ) , SOCIAL_MSG_ERROR );

			// We need to set the proper vars here so that the es-wrapper contains appropriate class
			JRequest::setVar( 'view' 	, 'profile' , 'POST' );
			JRequest::setVar( 'layout'	, 'edit' , 'POST' );

			// We need to set the data into the post again because onEditValidate might have changed the data structure
			JRequest::set( $data , 'post' );

			return $view->call( 'edit', $errors , $data );
		}


		// @trigger onEditBeforeSave
		$errors 	= $fieldsLib->trigger( 'onEditBeforeSave' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		if( is_array( $errors ) && count( $errors ) > 0 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_ERRORS_IN_FORM' ) , SOCIAL_MSG_ERROR );

			// We need to set the proper vars here so that the es-wrapper contains appropriate class
			JRequest::setVar( 'view' 	, 'profile' );
			JRequest::setVar( 'layout'	, 'edit' );

			// We need to set the data into the post again because onEditValidate might have changed the data structure
			JRequest::set( $data, 'post' );

			return $view->call( 'edit' , $errors );
		}

		// Bind the my object with appropriate data.
		$my->bind( $data );

		// Save the user object.
		$my->save();

		// Reconstruct args
		$args 		= array( &$data , &$my );

		// @trigger onEditAfterSave
		$fieldsLib->trigger( 'onEditAfterSave' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// Bind custom fields for the user.
		$my->bindCustomFields( $data );

		// Reconstruct args
		$args 		= array( &$data , &$my );

		// @trigger onEditAfterSaveFields
		$fieldsLib->trigger( 'onEditAfterSaveFields' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// Now we update the Facebook details if it is available
		$associatedFacebook = JRequest::getInt( 'associatedFacebook' );

		if( !empty( $associatedFacebook ) )
		{
			$facebookPull	= JRequest::getVar( 'oauth_facebook_pull' , null );
			$facebookPush	= JRequest::getVar( 'oauth_facebook_push' , null );

			$my 					= Foundry::user();
			$facebookTable			= $my->getOAuth( SOCIAL_TYPE_FACEBOOK );

			if( $facebookTable )
			{
				$facebookTable->pull 	= $facebookPull;
				$facebookTable->push 	= $facebookPush;

				$facebookTable->store();
			}
		}

		// Add stream item to notify the world that this user updated their profile.
		$my->addStream( 'updateProfile' );

		// Update indexer
		$my->syncIndex();

		// @points: profile.update
		// Assign points to the user when their profile is updated
		$points = Foundry::points();
		$points->assign( 'profile.update' , 'com_easysocial' , $my->id );


		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_ACCOUNT_UPDATED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $my );
	}

	/**
	 * Save user's privacy.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function savePrivacy()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is registered
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// current logged in user
		$my = Foundry::user();

		// $resetMap = array( 'story.view', 'photos.view', 'albums.view', 'core.view' );
		$privacyLib = Foundry::privacy();
		//$resetMap 	= call_user_func_array( array( $privacyLib , 'getResetMap' ) );
		$resetMap 	= $privacyLib->getResetMap();



		$post 	 	= JRequest::get('POST');
		$privacy 	= $post['privacy'];
		$ids     	= $post['privacyID'];
		$curValues  = $post['privacyOld'];
		$customIds  = $post['privacyCustom'];

		$requireReset = isset( $post['privacyReset'] ) ? true : false;

		$data = array();

		if( count( $privacy ) )
		{
			foreach( $privacy as $group => $items )
			{
				foreach( $items as $rule => $val )
				{
					$id 		 = $ids[ $group ][ $rule ];
					$custom 	 = $customIds[ $group ][ $rule ];
					$curVal 	 = $curValues[ $group ][ $rule ];

					$customUsers = array();


					if( !empty( $custom ) )
					{
						$tmp = explode( ',', $custom );
						foreach( $tmp as $tid )
						{
							if( !empty( $tid ) )
							{
								$customUsers[] = $tid;
							}
						}
					}

					$id = explode('_', $id);

					$obj = new stdClass();

					$obj->id 	 = $id[0];
					$obj->mapid  = $id[1];
					$obj->value  = $val;
					$obj->custom = $customUsers;
					$obj->reset  = false;

					//check if require to reset or not.
					$gr = strtolower( $group . '.' . $rule );
					if( $requireReset && in_array( $gr,  $resetMap ) )
					{
						$obj->reset = true;
					}

					$data[] = $obj;
				}

			}

		}

		// Set the privacy for this user
		if( count( $data ) > 0 )
		{
			$privacyModel 	= Foundry::model( 'Privacy' );
			$state 			= $privacyModel->updatePrivacy( $my->id , $data, SOCIAL_PRIVACY_TYPE_USER );

			if( $state !== true )
			{
				$view->setMessage( $state , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		// @points: privacy.update
		// Assign points when user updates their privacy
		$points = Foundry::points();
		$points->assign( 'privacy.update' , 'com_easysocial' , $my->id );


		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PRIVACY_UPDATED_SUCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allows user to remove his avatar
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function removeAvatar()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		$my 	= Foundry::user();
		$my->removeAvatar();

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_AVATAR_REMOVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Save user's notification.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function saveNotification()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is registered
		Foundry::requireLogin();

		// current logged in user
		$my = Foundry::user();

		// Get post data.
		$post 	= JRequest::get( 'POST' );

		// Get the current view.
		$view 	= $this->getCurrentView();

		$systemNotifications 	= $post[ 'system' ];
		$emailNotifications 	= $post[ 'email' ];

		$model 	= Foundry::model( 'Notifications' );
		$state	= $model->saveNotifications( $systemNotifications , $emailNotifications , $my );

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_NOTIFICATION_UPDATED_SUCESSFULLY' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Retrieves the timeline for the current user that is being viewed.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getStream()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get the view.
		$view	 = $this->getCurrentView();

		// Get the current user that is being viewed.
		$id 	= JRequest::getInt( 'id' , null );
		$user 	= Foundry::user( $id );

		// @TODO: Check if the viewer can access the user's timeline or not.

		// Retrieve user's stream
		$stream 	= Foundry::get( 'Stream' );
		$stream->get( array( 'userId' => $user->id ) );

		// Retrieve user's status
		$story 			= Foundry::get( 'Story' , SOCIAL_TYPE_USER );
		$story->target 	= $user->id;

		$stream->story  = $story;

		return $view->call( __FUNCTION__ , $stream , $story );
	}

	/**
	 * Allows a user to follow another user.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function follow()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user needs to be logged in.
		Foundry::requireLogin();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the object identifier.
		$id 	= JRequest::getInt( 'id' );

		// Get the user that is being followed
		$user 	= Foundry::user( $id );

		$type 	= JRequest::getVar( 'type' );
		$group 	= JRequest::getVar( 'group', SOCIAL_APPS_GROUP_USER );

		// Get the current logged in user.
		$my		= Foundry::user();

		// Load subscription table.
		$subscription 	= Foundry::table( 'Subscription' );

		// Get subscription library
		$subscriptionLib 	= Foundry::get( 'Subscriptions' );

		// User should never be allowed to follow themselves.
		if( $my->id == $id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_FOLLOWERS_NOT_ALLOWED_TO_FOLLOW_SELF' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $subscription );
		}

		// Determine if the current user is already a follower
		$isFollowing 	= $subscriptionLib->isFollowing( $id , $type , $group , $my->id );

		// If it's already following, throw proper message
		if( $isFollowing )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_SUBSCRIPTIONS_ERROR_ALREADY_FOLLOWING_USER' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $subscription );
		}

		// If the user isn't alreayd following, create a new subscription record.
		$subscription->uid 		= $id;
		$subscription->type 	= $type . '.' . $group;
		$subscription->user_id	= $my->id;

		$state 	= $subscription->store();

		if( !$state )
		{
			$view->setMessage( $subscription->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $subscription );
		}

		// @badge: followers.follow
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'followers.follow' , $my->id , JText::_( 'COM_EASYSOCIAL_FOLLOWERS_BADGE_FOLLOWING_USER' ) );

		// @badge: followers.followed
		$badge->log( 'com_easysocial' , 'followers.followed' , $user->id , JText::_( 'COM_EASYSOCIAL_FOLLOWERS_BADGE_FOLLOWED' ) );

		// @points: profile.follow
		// Assign points when user starts new conversation
		$points = Foundry::points();
		$points->assign( 'profile.follow' , 'com_easysocial' , $my->id );

		// @points: profile.followed
		// Assign points when user starts new conversation
		$points->assign( 'profile.followed' , 'com_easysocial' , $user->id );

		// Share this on the stream.
		$stream 			= Foundry::stream();
		$streamTemplate		= $stream->getTemplate();

		// Set the actor.
		$streamTemplate->setActor( $my->id , SOCIAL_TYPE_USER );

		// Set the context.
		$streamTemplate->setContext( $subscription->id , SOCIAL_TYPE_FOLLOWERS );

		// Set the verb.
		$streamTemplate->setVerb( 'follow' );


		$streamTemplate->setPublicStream( 'followers.view' );


		// Create the stream data.
		$stream->add( $streamTemplate );

		// Add new notification item
		$mailParams 	= Foundry::registry();
		$mailParams->set( 'follower'		, $my->getName() );
		$mailParams->set( 'name'			, $user->getName() );
		$mailParams->set( 'followerAvatar'	, $my->getAvatar( SOCIAL_AVATAR_LARGE ) );
		$mailParams->set( 'followerLink'	, $my->getPermalink() );
		$mailParams->set( 'followerTotalFriends' , $my->getTotalFriends() );
		$mailParams->set( 'followerTotalFollowers' , $my->getTotalFollowers() );
		$mailParams->set( 'followerTotalFollowing'	, $my->getTotalFollowing() );

		$state 	= Foundry::notify( 'profile.followed' , array( $user->id ),
									array( 'title' => JText::sprintf( 'COM_EASYSOCIAL_PROFILE_FOLLOWED_EMAIL_TITLE' , $my->getName() ) , 'params' => $mailParams ),
									array( 'url' => $my->getPermalink( false ) ,  'actor_id' => $my->id , 'uid' => $id )
								);

		return $view->call( __FUNCTION__ , $subscription );
	}

	/**
	 * Allows a user to unfollow an object.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unfollow()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user needs to be logged in.
		Foundry::requireLogin();

		// Get current logged in user.
		$my		= Foundry::user();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the object identifier.
		$id 		= JRequest::getInt( 'id' );

		// Get the target that is being unfollowed
		$user 		= Foundry::user( $id );

		$type 		= JRequest::getVar( 'type' );
		$group 		= JRequest::getVar( 'group', SOCIAL_APPS_GROUP_USER );

		$subscribe  = Foundry::get( 'Subscriptions');
		$state		= $subscribe->unfollow( $id, $type, $group, $my->id );

		if( !$state )
		{
			$view->setMessage( 'COM_EASYSOCIAL_UNFOLLOW_ERROR', SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// @points: profile.unfollow
		// Assign points when user starts new conversation
		$points = Foundry::points();
		$points->assign( 'profile.unfollow' , 'com_easysocial' , $my->id );

		// @points: profile.unfollowed
		// Assign points when user starts new conversation
		$points->assign( 'profile.unfollowed' , 'com_easysocial' , $user->id );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Retrieves the dashboard contents.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getAppContents()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get the app id.
		$appId 		= JRequest::getInt( 'appId' );

		// Get the user's id.
		$userId 	= JRequest::getInt( 'id' );

		// Load application.
		$app 	= Foundry::table( 'App' );
		$state 	= $app->load( $appId );

		// Get the view.
		$view	 = $this->getCurrentView();

		// If application id is not valid, throw an error.
		if( !$appId || !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_APPS_APP_ID_INVALID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $app , $userId );
		}

		// @TODO: Check if the user has access to this app or not.

		return $view->call( __FUNCTION__ , $app , $userId );
	}

	/**
	 * Allows user to delete their own account
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

		// Get current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Determine if the user is really allowed
		if( !$my->deleteable() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_NOT_ALLOWED_TO_DELETE' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$config 	= Foundry::config();

		// Determine if we should immediately delete the user
		if( $config->get( 'users.deleteLogic' ) == 'delete' )
		{
			$mailTemplate 	= 'deleted.removed';

			// Delete the user.
			$my->delete();
		}

		if( $config->get( 'users.deleteLogic' ) == 'unpublish' )
		{
			$mailTemplate 	= 'deleted.blocked';

			// Block the user
			$my->block();
		}

		// Send notification to admin

		// Push arguments to template variables so users can use these arguments
		$params 	= array(
								'name'				=> $my->getName(),
								'avatar'			=> $my->getAvatar( SOCIAL_AVATAR_MEDIUM ),
								'profileLink'		=> JURI::root() . 'administrator/index.php?option=com_easysocial&view=users&layout=form&id=' . $my->id,
								'date'				=> Foundry::date()->format( 'jS M, Y' ),
								'totalFriends'		=> $my->getTotalFriends(),
								'totalFollowers'	=> $my->getTotalFollowers()
						);


		$title 		= JText::sprintf( 'COM_EASYSOCIAL_EMAILS_USER_DELETED_ACCOUNT_TITLE' , $my->getName() );

		// Get a list of super admins on the site.
		$usersModel = Foundry::model( 'Users' );

		$admins 	= $usersModel->getSiteAdmins();

		if( $admins )
		{
			foreach( $admins as $admin )
			{
				$params[ 'adminName' ]	= $admin->getName();

				$mailer 	= Foundry::mailer();
				$template	= $mailer->getTemplate();

				$template->setRecipient( $admin->getName() , $admin->email );
				$template->setTitle( $title );
				$template->setTemplate( 'site/profile/' . $mailTemplate , $params );
				$template->setPriority( SOCIAL_MAILER_PRIORITY_IMMEDIATE );

				// Try to send out email to the admin now.
				$state 		= $mailer->create( $template );
			}
		}

		// Log the user out from the system
		$my->logout();

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Processes username reminder
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function remindUsername()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user.
		$my 	= Foundry::user();

		if( $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_YOU_ARE_ALREADY_LOGGED_IN' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the email address
		$email 	= JRequest::getVar( 'es-email' );

		$model 	= Foundry::model( 'Users' );
		$state	= $model->remindUsername( $email );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_PROFILE_USERNAME_SENT' , $email ) );
		return $view->call( __FUNCTION__ );
	}


	/**
	 * Processes username reminder
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function remindPassword()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user.
		$my 	= Foundry::user();

		if( $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_YOU_ARE_ALREADY_LOGGED_IN' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the email address
		$email 	= JRequest::getVar( 'es-email' );

		$model 	= Foundry::model( 'Users' );
		$state	= $model->remindPassword( $email );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_PROFILE_USERNAME_SENT' , $email ) );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Password reset confirmation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmResetPassword()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user.
		$my 	= Foundry::user();

		if( $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_YOU_ARE_ALREADY_LOGGED_IN' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$username 	= JRequest::getVar( 'es-username' );
		$code 		= JRequest::getVar( 'es-code' );

		$model 	= Foundry::model( 'Users' );
		$state	= $model->verifyResetPassword( $username , $code );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Completes password reset
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function completeResetPassword()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current logged in user.
		$my 	= Foundry::user();

		if( $my->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_YOU_ARE_ALREADY_LOGGED_IN' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$password 		= JRequest::getVar( 'es-password' );
		$password2 		= JRequest::getVar( 'es-password2' );

		// Check if the password matches
		if( $password != $password2 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_PASSWORDS_NOT_MATCHING' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$model 	= Foundry::model( 'Users' );
		$state	= $model->resetPassword( $password , $password2 );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILE_REMIND_PASSWORD_SUCCESSFUL' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Replicate's Joomla login behavior
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function login()
	{
		JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

		$app = JFactory::getApplication();

		// Populate the data array:
		$data = array();
		$data['return'] = base64_decode($app->input->post->get('return', '', 'BASE64'));
		$data['username'] = JRequest::getVar('username', '', 'method', 'username');
		$data['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$data['secretkey'] = JRequest::getString('secretkey', '');

		// Set the return URL if empty.
		if (empty($data['return']))
		{
			$data['return'] = 'index.php?option=com_easysocial&view=login';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = $data['secretkey'];

		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{
			// Success
			$app->setUserState('users.login.form.data', array());
			$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
		}
		else
		{
			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$app->setUserState('users.login.form.data', $data);

			$returnFailed 	= base64_decode($app->input->post->get('returnFailed', '', 'BASE64'));

			if( empty( $returnFailed ) )
			{
				$returnFailed 	= FRoute::login( array() , false );
			}

			$app->redirect( $returnFailed );
		}
	}


	/**
	 * Determines if the view should be visible on lockdown mode
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool
	 */
	public function isLockDown( $task )
	{
		$allowed 	= array( 'login' , 'confirmResetPassword' , 'completeResetPassword' , 'remindPassword' , 'remindUsername' );

		if( in_array( $task , $allowed ) )
		{
			return false;
		}

		return true;
	}
}
