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

Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerRegistration extends EasySocialController
{
	/**
	 * Allows user to activate their account.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function activate()
	{
		$my 	= Foundry::user();

		// Get current view.
		$view 	= $this->getCurrentView();

		// Get the id from the request
		$id 			= JRequest::getInt( 'userid' );
		$currentUser 	= Foundry::user( $id );

		// If user is already logged in, redirect to the dashboard.
		if( $my->isLoggedIn() )
		{
			return $view->call( __FUNCTION__ , $currentUser );
		}

		$token 	= JRequest::getVar( 'token' , '' );

		// If token is empty, warn the user.
		if( empty( $token ) || strlen( $token ) !== 32 )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_ACTIVATION_TOKEN_INVALID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $currentUser );
		}

		// Retrieve registration model
		$model 	= Foundry::model( 'Registration' );

		// Activate the token.
		$user 	= $model->activate( $token );

		if( $user === false )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $currentUser );
		}

		// @badge: registration.create
		// Assign badge for the person that initiated the friend request.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'registration.create' , $user->id , JText::_( 'COM_EASYSOCIAL_REGISTRATION_BADGE_REGISTERED' ) );

		// Get configuration object.
		$config 	= Foundry::config();

		// Add activity logging when a uer registers on the site.
		if( $config->get( 'registrations.stream.create' ) )
		{
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor
			$streamTemplate->setActor( $user->id , SOCIAL_TYPE_USER );

			// Set the context
			$streamTemplate->setContext( $user->id , SOCIAL_TYPE_PROFILES );

			// Set the verb
			$streamTemplate->setVerb( 'register' );

			// set sitewide
			$streamTemplate->setSiteWide();

			$streamTemplate->setPublicStream( 'core.view' );


			// Add stream template.
			$stream->add( $streamTemplate );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_ACTIVATION_COMPLETED_SUCCESS' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $user );
	}

	/**
	 * This adds information about the current profile that the user selected during registration.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function selectType()
	{
		$config 	= Foundry::config();
		$view 		= Foundry::view( 'Registration' , false );

		// @task: Ensure that registrations is enabled.
		if( !$config->get( 'registrations.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_REGISTRATION_DISABLED' , SOCIAL_MSG_ERROR ) );
			return $view->call( __FUNCTION__ );
		}

		$id 	= JRequest::getInt( 'profile_id' , 0 );

		// If there's no profile id selected, throw an error.
		if( !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_REGISTRATION_EMPTY_PROFILE_ID' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// @task: Let's set some info about the profile into the session.
		$session		= JFactory::getSession();
		$session->set( 'profile_id' , $id , SOCIAL_SESSION_NAMESPACE );

		// @task: Try to load more information about the current registration procedure.
		$registration				= Foundry::table( 'Registration' );
		$registration->load( $session->getId() );
		$registration->profile_id	= $id;

		// When user accesses this page, the following will be the first page
		$registration->set( 'step' , 1 );

		// Add the first step into the accessible list.
		$registration->addStepAccess( 1 );
		$registration->store();

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Each time the user clicks on the next button, this method is invoked.
	 *
	 * @since	1.0
	 * @access	public
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function saveStep()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get configuration object.
		$config 	= Foundry::config();

		// Get the current view
		$view 		= $this->getCurrentView();

		// Registrations must be enabled.
		if( !$config->get( 'registrations.enabled' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATIONS_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Retrieve all file objects if needed
		$files 		= JRequest::get( 'FILES' );
		$post		= JRequest::get( 'POST' );
		$token      = Foundry::token();

		// Get current user's info
		$session    = JFactory::getSession();

		// Get necessary info about the current registration process.
		$registration		= Foundry::table( 'Registration' );
		$registration->load( $session->getId() );

		// Load the profile object.
		$profile    = Foundry::table( 'Profile' );
		$profile->load( $registration->get( 'profile_id' ) );

		// Load the current step.
		$step 		= Foundry::table( 'FieldStep' );
		$step->loadBySequence( $profile->id , SOCIAL_TYPE_PROFILES , $registration->get( 'step' ) );

		// Merge the post values
		$registry 	= Foundry::get( 'Registry' );
		$registry->load( $registration->values );

		// Load registration model
		$registrationModel	= Foundry::model( 'Registration' );

		// Get all published fields apps that are available in the current form to perform validations
		$fieldsModel 		= Foundry::model( 'Fields' );
		$fields				= $fieldsModel->getCustomFields( array( 'step_id' => $step->id, 'visible' => SOCIAL_PROFILES_VIEW_REGISTRATION ) );

		// Load json library.
		$json 	= Foundry::json();

		// Process $_POST vars
		foreach( $post as $key => $value )
		{
			if( $key != $token )
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

		$args       = array( &$data , &$registration );

		// Perform field validations here. Validation should only trigger apps that are loaded on the form
		// @trigger onRegisterValidate
		$fieldsLib			= Foundry::fields();

		// Get error messages
		$errors				= $fieldsLib->trigger( 'onRegisterValidate' , SOCIAL_FIELDS_GROUP_USER , $fields , $args );

		// The values needs to be stored in a JSON notation.
		$registration->values   = $json->encode( $data );

		// Store registration into the temporary table.
		$registration->store();

		// Get the current step (before saving)
		$currentStep    = $registration->get( 'step' );

		// Add the current step into the accessible list
		$registration->addStepAccess( $currentStep );

		// Bind any errors into the registration object
		$registration->setErrors( $errors );

		// Saving was intercepted by one of the field applications.
		if( is_array( $errors ) && count( $errors ) > 0 )
		{
			// @rule: If there are any errors on the current step, remove access to future steps to avoid any bypass
			$registration->removeAccess( $currentStep );

			// @rule: Reset steps to the current step
			$registration->step = $currentStep;
			$registration->store();

			$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_SOME_ERRORS_IN_THE_REGISTRATION_FORM' ) , SOCIAL_MSG_ERROR );

			return $view->call( 'saveStep' , $registration , $currentStep );
		}

		// Load the profile
		$profile        = Foundry::table( 'Profile' );
		$profile->load( $registration->profile_id );

		// Determine whether the next step is completed. It has to be before updating the registration table's step
		// Otherwise, the step doesn't exist in the site.
		$step       = Foundry::table( 'FieldStep' );
		$step->loadBySequence( $profile->id , SOCIAL_TYPE_PROFILES , $registration->step );

		// Determine if this is the last step.
		$completed      = $step->isFinalStep( SOCIAL_PROFILES_VIEW_REGISTRATION );

		// Update creation date
		$registration->created = Foundry::date()->toMySQL();

		// Since user has already came through this step, add the step access
		$nextStep		= $step->getNextSequence( SOCIAL_PROFILES_VIEW_REGISTRATION );
		if( $nextStep !== false )
		{
			$registration->addStepAccess( $nextStep );
		}

		// Save the temporary data.
		$registration->store();

		// If this is the last step, we try to save all user's data and create the necessary values.
		if( $completed )
		{
			// Create user object.
			$user 	= $registrationModel->createUser( $registration );

			// If there's no id, we know that there's some errors.
			if( !$user->id )
			{
				$errors 		= $registrationModel->getError();

				$view->setMessage( $errors , SOCIAL_MSG_ERROR );

				return $view->call( 'saveStep' , $registration , $currentStep );
			}

			// Get the registration data
			$registrationData 	= Foundry::registry( $registration->values );

			// Clear existing registration objects once the creation is completed.
			$registration->delete();

			// Force unset on the user first to reload the user object
			SocialUser::$userInstances[$user->id] = null;

			// Get the current registered user data.
			$my 		= Foundry::user( $user->id );

			// We need to send the user an email with their password
			$my->password_clear	= $user->password_clear;

			// Convert the data into an array of result.
			$mailerData		= Foundry::registry( $registration->values )->toArray();

			// Send notification to admin if necessary.
			$registrationModel->notifyAdmins( $mailerData , $my , $profile );

			// If everything goes through fine, we need to send notification emails out now.
			$registrationModel->notify( $mailerData , $my , $profile );

			// We need to log the user in after they have successfully registered.
			if( $profile->getRegistrationType() == 'auto' )
			{
				// @points: user.register
				// Assign points when user registers on the site.
				$points = Foundry::points();
				$points->assign( 'user.registration' , 'com_easysocial' , $my->id );

				// @badge: registration.create
				// Assign badge for the person that initiated the friend request.
				$badge 	= Foundry::badges();
				$badge->log( 'com_easysocial' , 'registration.create' , $my->id , JText::_( 'COM_EASYSOCIAL_REGISTRATION_BADGE_REGISTERED' ) );

				// Add activity logging when a uer registers on the site.
				if( $config->get( 'registrations.stream.create' ) )
				{
					$stream				= Foundry::stream();
					$streamTemplate		= $stream->getTemplate();

					// Set the actor
					$streamTemplate->setActor( $my->id , SOCIAL_TYPE_USER );

					// Set the context
					$streamTemplate->setContext( $my->id , SOCIAL_TYPE_PROFILES );

					// Set the verb
					$streamTemplate->setVerb( 'register' );

					$streamTemplate->setSiteWide();

					$streamTemplate->setPublicStream( 'core.view' );


					// Add stream template.
					$stream->add( $streamTemplate );
				}

				$app 			= JFactory::getApplication();

				$credentials	= array( 'username' => $data[ 'username' ] , 'password' => $registrationData->get( 'password_clear' ) );

				// Try to log the user in
				$app->login( $credentials );
			}

			// add new registered user into indexer
			$my->syncIndex();


			// Store the user's custom fields data now.
			return $view->complete( $user , $profile );
		}

		// Always increment the step by one and save the current step.
		// $registration->step		= $currentStep + 1;

		// Don't increase by 1, use field step to find the next valid step instead
		$registration->step = $step->getNextSequence( SOCIAL_PROFILES_VIEW_REGISTRATION );

		// Save the temporary data.
		$registration->store();

		// Get the currentIndex based on currentStep
		$currentIndex = $registry->get( 'currentStep' );

		return $view->saveStep( $registration , $currentIndex , $completed );
	}

	/**
	 * Normal oauth registration or if the user has an invalid email or username in simplified process.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function oauthCreateAccount()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view.
		$view 			= $this->getCurrentView();

		// Get component's configuration
		$config 	= Foundry::config();

		// Get allowed clients
		$allowedClients	= array_keys( (array) $config->get( 'oauth' ) );

		// Get the client type
		$clientType 	= JRequest::getWord( 'client' , '' );

		// Check if the client is valid.
		if( !$clientType || !in_array( $clientType , $allowedClients ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_INVALID_CLIENT' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the profile
		$profileId 	= JRequest::getInt( 'profile' );
		$profile 	= Foundry::table( 'Profile' );
		$profile->load( $profileId );

		// Check if the profile id is provided.
		if( !$profileId || !$profile->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_INVALID_PROFILEID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the access token from session
		$client 		= Foundry::oauth( $clientType );
		$session 		= JFactory::getSession();
		$accessToken 	= $client->getAccess();

		// Check if the profile id is provided.
		if( !$accessToken )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_ACCESS_TOKEN_NOT_FOUND' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Determines if the oauth id is already registered on the site.
		$isRegistered 	= $client->isRegistered();

		// If user has already registered previously, just log them in.
		if( $isRegistered )
		{
			// Throw an error message here because they shouldn't be coming through this page.
			return $view->call( __FUNCTION__ );
		}

		// Get the user's meta
		$meta 		= $client->getUserMeta();
		$import		= JRequest::getBool( 'import' );
		$sync 		= JRequest::getBool( 'stream' );
		$username 	= JRequest::getVar( 'oauth-username' );
		$email 		= JRequest::getVar( 'oauth-email' );

		// Detect if user has set a password.
		$password 	= JRequest::getVar( 'password' , '' );

		if( !empty( $password ) )
		{
			$meta[ 'password' ]	= $password;
		}

		// Reset the profile id
		$meta[ 'profileId' ]	= $profile->id;

		// Re-apply the username to the meta.
		$meta[ 'username' ]		= $username;
		$meta[ 'email' ]		= $email;

		// Retrieve the model.
		$model 		= Foundry::model( 'Registration' );

		// Double check to see if the email and username still exists.
		if( $model->isUsernameExists( $meta[ 'username' ] ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_USERNAME_ALREADY_USED' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'oauthPreferences' , $profile->id , $meta[ 'username' ] , $meta[ 'email' ] , $client );
		}

		// Double check to see if the email and username still exists.
		if( $model->isEmailExists( $meta[ 'email' ] ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_EMAIL_ALREADY_USED' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'oauthPreferences' , $profile->id , $meta[ 'username' ] , $meta[ 'email' ] , $client );
		}

		// Create the user account in Joomla
		$user 		= $model->createOauthUser( $accessToken , $meta , $client , $import , $sync );

		// If there's a problem creating user, throw message.
		if( !$user )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}


		// Check if the profile type requires activation. Only log the user in when user is supposed to automatically login.
		$type 	= $profile->getRegistrationType();

		// Send notification to admin if necessary.
		$model->notifyAdmins( $meta , $user , $profile );

		// Only log the user in if the profile allows this.
		if( $type == 'auto' )
		{
			// Log the user in
			$client->login();

			// Once the user is logged in, get the new user object.
			$my 	= Foundry::user();

			// @points: user.register
			// Assign points when user registers on the site.
			$points = Foundry::points();
			$points->assign( 'user.registration' , 'com_easysocial' , $my->id );

			// Add activity logging when a uer registers on the site.
			if( $config->get( 'registrations.stream.create' ) )
			{
				$stream				= Foundry::stream();
				$streamTemplate		= $stream->getTemplate();

				// Set the actor
				$streamTemplate->setActor( $my->id , SOCIAL_TYPE_USER );

				// Set the context
				$streamTemplate->setContext( $my->id , SOCIAL_TYPE_PROFILES );

				// Set the verb
				$streamTemplate->setVerb( 'register' );

				$streamTemplate->setSiteWide();

				$streamTemplate->setPublicStream( 'core.view' );

				// Add stream template.
				$stream->add( $streamTemplate );
			}

		}
		else
		{
			// Send notification to user
			$model->notify( $meta , $user , $profile );
		}

		return $view->call( __FUNCTION__ , $user );
	}

	/**
	 * Links a previously registered account with an oauth account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function oauthLinkAccount()
	{
		// Check for request forgeries
		// Foundry::checkToken();

		// Get the current view
		$view 			= $this->getCurrentView();

		// Get the current client type.
		$clientType		= JRequest::getVar( 'client' );

		// Get the client library.
		$client 		= Foundry::oauth( $clientType );

		// Get the user's username and password
		$username 		= JRequest::getVar( 'username' );
		$password 		= JRequest::getVar( 'password' );
		$credentials 	= array( 'username' => $username , 'password' => $password );

		$app 			= JFactory::getApplication();
		$state 			= $app->login( $credentials );

		if( !$state )
		{
			// We do not need to set any messages since Joomla will automatically display this in the queue.
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_USERNAME_PASSWORD_ERROR' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $clientType );
		}

		$my 	= Foundry::user();

		// If user logged in successfully, link the oauth account to this user account.
		$model 	= Foundry::model( 'Registration' );
		$state	= $model->linkOAuthUser( $client , $my );

		if( !$state )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $clientType );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_OAUTH_ACCOUNT_LINK_SUCCESS' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $clientType );
	}

	/**
	 * This is when user clicks on Create account which we will automatically register them on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function oauthSignup()
	{
		// Load our own configuration.
		$config = Foundry::config();

		// Retrieve current view.
		$view 	= $this->getCurrentView();

		// Get the current client
		$client = JRequest::getWord( 'client' );

		// Get allowed clients
		$allowedClients	= array_keys( (array) $config->get( 'oauth' ) );

		// Check for allowed clients.
		if( !in_array( $client , $allowedClients ) )
		{
			$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_OAUTH_INVALID_OAUTH_CLIENT_PROVIDED' , $client ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Load up oauth library
		$oauthClient 	= Foundry::oauth( $client );

		// Get the external user id.
		$oauthUserId	= $oauthClient->getUser();

		// Determines if the oauth id is already registered on the site.
		$isRegistered 	= $oauthClient->isRegistered();

		// If user has already registered previously, just log them in.
		if( $isRegistered )
		{
			$state	= $oauthClient->login();

			if( $state )
			{
				$view->setMessage( 'COM_EASYSOCIAL_OAUTH_AUTHENTICATED_ACCOUNT_SUCCESS' );
			}

			return $view->call( __FUNCTION__ );
		}

		// Get the access tokens.
		$accessToken 	= $oauthClient->getAccess();

		// Retrieve user's information
		$meta			= $oauthClient->getUserMeta();

		// Get the registration type.
		$registrationType 	= $config->get( 'oauth.' . $client . '.registration.type' );

		// Load up registration model
		$model 		= Foundry::model( 'Registration' );

		// If this is a simplified registration, check if the user name exists.
		if( $registrationType == 'simplified' )
		{
			// If the username or email exists
			if( $model->isEmailExists( $meta[ 'email' ] ) || $model->isUsernameExists( $meta[ 'username' ] ) )
			{
				return $view->call( 'oauthPreferences' , $meta[ 'profileId'] , $meta[ 'username' ] , $meta[ 'email' ] , $client );
			}
		}

		// Create user account
		$user 		= $model->createOauthUser( $accessToken , $meta , $oauthClient );

		// @badge: registration.create
		// Assign badge for the person that initiated the friend request.
		$badge 	= Foundry::badges();
		$badge->log( 'com_easysocial' , 'registration.create' , $user->id , JText::_( 'COM_EASYSOCIAL_REGISTRATION_BADGE_REGISTERED' ) );

		if( !$user )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		// If the profile type is auto login, we need to log the user in
		$profile 		= Foundry::table( 'Profile' );
		$profile->load( $meta[ 'profileId' ] );

		// Check if the profile type requires activation. Only log the user in when user is supposed to automatically login.
		$type 	= $profile->getRegistrationType();

		// Send notification to admin if necessary.
		$model->notifyAdmins( $meta , $user , $profile );

		// Only log the user in if the profile allows this.
		if( $type == 'auto' )
		{
			// Log the user in
			$oauthClient->login();

			// Once the user is logged in, get the new user object.
			$my 	= Foundry::user();

			// @points: user.register
			// Assign points when user registers on the site.
			$points = Foundry::points();
			$points->assign( 'user.registration' , 'com_easysocial' , $my->id );

			// Add activity logging when a uer registers on the site.
			if( $config->get( 'registrations.stream.create' ) )
			{
				$stream				= Foundry::stream();
				$streamTemplate		= $stream->getTemplate();

				// Set the actor
				$streamTemplate->setActor( $my->id , SOCIAL_TYPE_USER );

				// Set the context
				$streamTemplate->setContext( $my->id , SOCIAL_TYPE_PROFILES );

				// Set the verb
				$streamTemplate->setVerb( 'register' );

				$streamTemplate->setSiteWide();

				$streamTemplate->setPublicStream( 'core.view' );

				// Add stream template.
				$stream->add( $streamTemplate );
			}

		}
		else
		{
			// Send notification to user
			$model->notify( $meta , $user , $profile );
		}

		return $view->call( 'oauthCreateAccount' , $user );
	}


	/**
	 * Allows admin to approve a user via email
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function approveUser()
	{
		$key 	= JRequest::getVar( 'key' );
		$id 	= JRequest::getInt( 'id' );

		$user 	= Foundry::user( $id );

		$view 	= $this->getCurrentView();

		// Re-generate the hash
		$hash	= md5( $user->password . $user->email . $user->name . $user->username );

		// If the key provided is not valid, we do not do anything
		if( $hash != $key )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_MODERATION_FAILED_KEY_DOES_NOT_MATCH' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Approve the user now.
		$user->approve();

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_USER_ACCOUNT_APPROVED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allows admin to reject a user via email
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function rejectUser()
	{
		$key 	= JRequest::getVar( 'key' );
		$id 	= JRequest::getInt( 'id' );

		$user 	= Foundry::user( $id );

		$view 	= $this->getCurrentView();

		// Re-generate the hash
		$hash	= md5( $user->password . $user->email . $user->name . $user->username );

		// If the key provided is not valid, we do not do anything
		if( $hash != $key )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_MODERATION_FAILED_KEY_DOES_NOT_MATCH' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Approve the user now.
		$user->reject();

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_REGISTRATION_USER_ACCOUNT_REJECTED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Determines if the view should be visible on lockdown mode
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool
	 */
	public function isLockDown()
	{
		$config 	= Foundry::config();

		if( $config->get( 'general.site.lockdown.registration' ) )
		{
			return false;
		}

		return true;
	}
}
