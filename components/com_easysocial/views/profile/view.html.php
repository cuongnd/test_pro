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

// Include main view file.
Foundry::import( 'site:/views/views' );

class EasySocialViewProfile extends EasySocialSiteView
{
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
		$layout 	= $this->getLayout();

		// Allowed layouts on lockdown mode
		$allowed 	= array( 'forgetUsername' , 'forgetPassword' , 'confirmReset' , 'confirmResetPassword' , 'resetUser' , 'completeResetPassword' );

		if( $config->get( 'general.site.lockdown.registration' ) || in_array( $layout , $allowed ) )
		{
			return false;
		}

		return true;
	}

	/**
	 * Displays a user profile to a 3rd person perspective.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 **/
	public function display( $tpl = null )
	{
		// Get the user's id.
		$id    = JRequest::getInt( 'id' , 0 );

		// The current logged in user might be viewing their own profile.
		if( $id == 0 )
		{
			$id 	= Foundry::user()->id;
		}

		// When the user tries to view his own profile but if he isn't logged in, throw a login page.
		if( $id == 0 )
		{
			return Foundry::requireLogin();
		}

		// Get the user's object.
		$user 	= Foundry::user( $id );

		if( $user->isBlock() )
		{
			Foundry::info()->set( JText::sprintf( 'COM_EASYSOCIAL_PROFILE_USER_NOT_EXIST', $user->getName() ), SOCIAL_MSG_ERROR );
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Set the page title
		Foundry::page()->title( Foundry::string()->escape( $user->getName() ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( Foundry::string()->escape( $user->getName() ) );

		// Apply opengraph tags.
		Foundry::opengraph()->addProfile( $user );

		// Get the current logged in user's object.
		$my 	= Foundry::user();

		// If the user still don't exist, throw a 404
		if( !$user->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Do not assign badge if i view myself.
		if( $user->id != $my->id && $my->id )
		{
			// @badge: profile.view
			$badge 	= Foundry::badges();
			$badge->log( 'com_easysocial' , 'profile.view' , $my->id , JText::_( 'COM_EASYSOCIAL_PROFILE_VIEWED_A_PROFILE' ) );
		}

		// Determine if the current request is to load an app
		$appId 		= JRequest::getInt( 'appId' );

		// Get site configuration
		$config 	= Foundry::config();

		// Get the apps library.
		$appsLib 	= Foundry::apps();

		if( $appId )
		{
			// Load the app
			$app 	= Foundry::table( 'App' );
			$app->load( $appId );

			// Check if the user has access to this app
			if( !$app->accessible( $user->id ) )
			{
				Foundry::info()->set( null , JText::_( 'COM_EASYSOCIAL_PROFILE_APP_IS_NOT_INSTALLED_BY_USER' ) , SOCIAL_MSG_ERROR );
				return $this->redirect( FRoute::profile( array( 'id' => $user->getAlias() ) , false ) );
			}

			// Set the page title
			Foundry::page()->title( Foundry::string()->escape( $user->getName() ) . ' - ' . $app->get( 'title' ) );

			$contents 	= $appsLib->renderView( SOCIAL_APPS_VIEW_TYPE_EMBED , 'profile' , $app , array( 'userId' => $user->id ) );

			$this->set( 'contents' , $contents );
		}
		else
		{
			// Retrieve user's stream
			$theme 	= Foundry::themes();

			// Get story
			$story 			= Foundry::get( 'Story' , SOCIAL_TYPE_USER );
			$story->target 	= $user->id;

			$stream = Foundry::stream();
			$stream->get( array( 'userId' => $user->id ) );

			$stream->story = $story;

			// Set stream to theme
			$theme->set( 'stream'	, $stream );

			$contents 	= $theme->output( 'site/profile/default.stream' );

			$this->set( 'contents' , $contents );
		}

		$privacy 	= $my->getPrivacy();

		// Let's test if the current viewer is allowed to view this profile.
		if( $my->id != $user->id )
		{
			if( !$privacy->validate( 'profiles.view' , $user->id , SOCIAL_TYPE_USER ) )
			{
				$this->set( 'user' , $user );
				parent::display( 'site/profile/restricted' );

				return;
			}
		}

		// Get user's cover object
		$cover = $user->getCover();
		$this->set( 'cover'	, $cover );

 		// If we're setting a cover
 		$coverId = JRequest::getInt('cover_id', null);

		if( $coverId )
		{
			// Load cover photo
			$newCover = Foundry::table( 'Photo' );
			$newCover->load( $coverId );

			// If the cover photo belongs to the user
			if ($newCover->isMine()) {

				// Then allow replacement of cover
				$this->set('newCover', $newCover);
			}
		}

		$photosModel 	= Foundry::model( 'Photos' );
		$photos 		= $photosModel->getPhotos( array( 'uid' => $user->id ) );
		$totalPhotos 	= 10;

		// Retrieve list of apps for this user
		$appsModel 	= Foundry::model( 'Apps' );
		$options	= array( 'view' => 'profile' , 'uid' => $user->id , 'key' => SOCIAL_TYPE_USER );
		$apps 		= $appsModel->getApps( $options );

		// Set the apps lib
		$this->set( 'appsLib' , $appsLib );
		$this->set( 'totalPhotos' , $totalPhotos );
		$this->set( 'photos' 	, $photos );
		$this->set( 'apps'		, $apps );
		$this->set( 'activeApp'	, $appId );
		$this->set( 'privacy', $privacy );
		$this->set( 'user'		, $user );

		// Load the output of the profile.
		echo parent::display( 'site/profile/default' );
	}

	/**
	 * Responsible to output the edit profile layout
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The name of the template file to parse; automatically searches through the template paths.
	 * @return	null
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function edit( $errors = null )
	{
		// Unauthorized users should not be allowed to access this page.
		Foundry::requireLogin();

		// Set any messages here.
		Foundry::info()->set( $this->getMessage() );

		// Load the language file from the back end.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ADMINISTRATOR );
		
		// Get the current logged in user.
		$my 		= Foundry::user();

		// Get list of steps for this user's profile type.
		$profile 	= $my->getProfile();

		// Get user's installed apps
		$appsModel 	= Foundry::model( 'Apps' );
		$userApps 	= $appsModel->getUserApps( $my->id );

		// Get the steps model
		$stepsModel = Foundry::model( 'Steps' );
		$steps 		= $stepsModel->getSteps( $profile->id , SOCIAL_TYPE_PROFILES , SOCIAL_PROFILES_VIEW_EDIT );

		// Get custom fields model.
		$fieldsModel 	= Foundry::model( 'Fields' );

		// Get custom fields library.
		$fields 		= Foundry::fields();

		// Set page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ACCOUNT_SETTINGS' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_PROFILE' ) , FRoute::profile() );
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ACCOUNT_SETTINGS' ) );

		// Get the custom fields for each of the steps.
		foreach( $steps as &$step )
		{
			$step->fields 	= $fieldsModel->getCustomFields( array( 'step_id' => $step->id , 'data' => true , 'dataId' => $my->id , 'dataType' => SOCIAL_TYPE_USER, 'visible' => 'edit' ) );

			// Trigger onEdit for custom fields.
			if( !empty( $step->fields ) )
			{
				$post = JRequest::get( 'post' );
				$args 	= array( &$post, &$my, $errors );
				$fields->trigger( 'onEdit' , SOCIAL_FIELDS_GROUP_USER , $step->fields , $args );
			}
		}

		// Determines if we should show the social tabs on the left.
		$showSocialTabs 		= false;

		// Determines if the user has associated
		$associatedFacebook		= $my->isAssociated( 'facebook' );
		$facebookClient 		= false;
		$facebookMeta 			= array();
		$fbOAuth				= false;
		$fbUserMeta				= array();

		if( $associatedFacebook )
		{
			// We want to show the tabs
			$showSocialTabs = true;

			$facebookToken	= $my->getOAuthToken( 'facebook' );
			$facebookClient = Foundry::oauth( 'facebook' );

			// Set the access for the client.
			$facebookClient->setAccess( $facebookToken );

			$fbUserMeta 			= $facebookClient->getUserMeta();
			$fbOAuth				= $my->getOAuth( SOCIAL_TYPE_FACEBOOK );

			$facebookMeta			= Foundry::registry( $fbOAuth->params );
			$facebookPermissions	= Foundry::makeArray( $fbOAuth->permissions );
		}

		$this->set( 'fbUserMeta' 		, $fbUserMeta );
		$this->set( 'fbOAuth'			, $fbOAuth );
		$this->set( 'showSocialTabs'	, $showSocialTabs );
		$this->set( 'facebookMeta'		, $facebookMeta );
		$this->set( 'facebookClient'	, $facebookClient );
		$this->set( 'associatedFacebook', $associatedFacebook );

		$this->set( 'my'		, $my );
		$this->set( 'profile'	, $profile );
		$this->set( 'steps' 	, $steps );
		$this->set( 'apps'		, $userApps);

		return parent::display( 'site/profile/default.edit.profile' );
	}

	/**
	 * Edit privacy form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function editNotifications()
	{
		// User needs to be logged in
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 		= Foundry::user();

		// Get the user notification settings
		$alertLib 	= Foundry::alert();
		$alerts 	= $alertLib->getUserSettings( $my->id );

		// Set page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_NOTIFICATION_SETTINGS' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_PROFILE' ) , FRoute::profile() );
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_NOTIFICATION_SETTINGS' ) );

		$this->set( 'alerts'	, $alerts );

		parent::display( 'site/profile/default.edit.notifications' );
	}

	/**
	 * Edit privacy form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function editPrivacy()
	{
		// User needs to be logged in
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 		= Foundry::user();

		// Get user's privacy
		$privacyLib		= Foundry::privacy( $my->id );
		$privacy 		= $privacyLib->getData();

		// Set page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_PRIVACY_SETTINGS' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_PROFILE' ) , FRoute::profile() );
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_PRIVACY_SETTINGS' ) );

		// Update the privacy data with proper properties.
		foreach( $privacy as $group => $items )
		{
			foreach( $items as &$item )
			{
				$rule 		= strtoupper( JString::str_ireplace( '.' , '_' , $item->rule ) );
				$groupKey 	= strtoupper( $group );

				$item->groupKey 	= $groupKey;
				$item->label 		= JText::_( 'COM_EASYSOCIAL_PRIVACY_LABEL_' . $groupKey . '_' . $rule );
				$item->tips 		= JText::_( 'COM_EASYSOCIAL_PRIVACY_TIPS_' . $groupKey . '_' . $rule );
			}
		}

		$this->set( 'privacy'	, $privacy );

		parent::display( 'site/profile/default.edit.privacy' );
	}

	/**
	 * Handle save profiles.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function save()
	{
		$info 	= Foundry::info();
		$info->set( $this->getMessage() );

		$this->redirect( FRoute::profile( array( 'layout' => 'edit' ) , false ) );
	}

	/**
	 * Handle save notification.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function saveNotification()
	{
		$info 	= Foundry::info();
		$info->set( $this->getMessage() );

		$this->redirect( FRoute::profile( array( 'layout' => 'editNotifications' ) , false ) );
	}


	/**
	 * Handle save privacy.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function savePrivacy()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( FRoute::profile( array( 'layout' => 'editPrivacy' ) , false ) );
	}

	/**
	 * Displays a user information to a 3rd person perspective.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 **/
	public function about( $tpl = null )
	{
		// Get the user's id.
		$id    = JRequest::getInt( 'id' , 0 );

		// The current logged in user might be viewing their own profile.
		if( $id == 0 )
		{
			$id 	= Foundry::user()->id;
		}

		// When the user tries to view his own profile but if he isn't logged in, throw a login page.
		if( $id == 0 )
		{
			return Foundry::requireLogin();
		}

		// Get the user's object.
		$user 	= Foundry::user( $id );

		// If user does not exist, redirect to the front page
		if( !$user->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Get the current logged in user's object.
		$my 		= Foundry::user();
		$privacy	= $my->getPrivacy();

		// @privacy: Let's test if the current viewer is allowed to view this profile.
		if( $my->id != $user->id )
		{
			if( !$privacy->validate( 'profiles.view' , $user->id , SOCIAL_TYPE_USER ) )
			{
				$this->set( 'user' , $user );
				parent::display( 'site/profile/restricted' );
				return;
			}
		}

		// Set page title
		Foundry::page()->title( $user->getName() . ' - ' . JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ABOUT' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( $user->getName() , $user->getPermalink() );
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ABOUT' ) );

		// Load language file from back end.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		// Get the custom fields steps.
		// Get the steps model
		$stepsModel = Foundry::model( 'Steps' );
		$steps 		= $stepsModel->getSteps( $user->profile_id , SOCIAL_TYPE_PROFILES , SOCIAL_PROFILES_VIEW_DISPLAY );

		$fields 	= Foundry::fields();
		$fieldsModel= Foundry::model( 'Fields' );

		// Get the custom fields for each of the steps.
		foreach( $steps as &$step )
		{
			$step->fields 	= $fieldsModel->getCustomFields( array( 'step_id' => $step->id , 'data' => true , 'dataId' => $user->id , 'dataType' => SOCIAL_TYPE_USER , 'visible' => SOCIAL_PROFILES_VIEW_DISPLAY ) );

			// Trigger onEdit for custom fields.
			if( !empty( $step->fields ) )
			{
				$args 	= array( $user );

				$fields->trigger( 'onDisplay' , SOCIAL_FIELDS_GROUP_USER , $step->fields , $args );
			}
		}

		$this->set( 'user'	, $user );
		$this->set( 'steps'	, $steps );

		// Load the output of the profile.
		echo parent::display( 'site/profile/default.about' );
	}

	/**
	 * Post process after removing an avatar
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function removeAvatar()
	{
		Foundry::info()->set( $this->getMessage() );

		$my 	= Foundry::user();

		$this->redirect( FRoute::profile( array( 'id' => $my->getAlias() ) , false ) );
	}

	/**
	 * Post process after reminding username
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function remindUsername()
	{
		// Enqueue the message
		Foundry::info()->set( $this->getMessage() );

		if( $this->hasErrors() )
		{
			return $this->redirect( FRoute::profile( array( 'layout' => 'forgetUsername' ) , false ) );
		}

		$this->redirect( FRoute::login( array() , false )  );
	}

	/**
	 * Post process after reminding password
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function remindPassword()
	{
		// Enqueue the message
		Foundry::info()->set( $this->getMessage() );

		if( $this->hasErrors() )
		{
			return $this->redirect( FRoute::profile( array( 'layout' => 'forgetPassword' ) , false ) );
		}

		$url 	= FRoute::profile( array( 'layout' => 'confirmReset' ) , false );

		$this->redirect( $url );
	}

	/**
	 * Post process after user resets the password
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function completeResetPassword()
	{
		// Enqueue the message
		Foundry::info()->set( $this->getMessage() );

		if( $this->hasErrors() )
		{
			return $this->redirect( FRoute::profile( array( 'layout' => 'completeReset' ) , false ) );
		}

		// If it was successful, redirect user to the login page
		$this->redirect( FRoute::login( array() , false ) );
	}

	/**
	 * Post process after user enters the verification code
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function confirmResetPassword()
	{
		// Enqueue the message
		Foundry::info()->set( $this->getMessage() );

		if( $this->hasErrors() )
		{
			return $this->redirect( FRoute::profile( array( 'layout' => 'confirmReset' ) , false ) );
		}

		$this->redirect( FRoute::profile( array( 'layout' => 'completeReset' ) , false ) );
	}

	/**
	 * Post processing after the user wants to delete their account
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete()
	{
		Foundry::info()->set( $this->getMessage() );


		$this->redirect( FRoute::dashboard( array() , false ) );
	}

	/**
	 * Displays the forget username form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function forgetUsername()
	{
		$my 	= Foundry::user();

		// If user is already logged in, do not allow them here.
		if( $my->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_REMIND_USERNAME' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_REMIND_USERNAME' ) );

		parent::display( 'site/profile/forget.username' );
	}

	/**
	 * Displays the forget password form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function forgetPassword()
	{
		$my 	= Foundry::user();

		// If user is already logged in, do not allow them here.
		if( $my->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}
		
		// Set the page title
		Foundry::page()->title( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_REMIND_PASSWORD' ) );

		// Set the page breadcrumb
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_REMIND_PASSWORD' ) );


		parent::display( 'site/profile/forget.password' );
	}

	/**
	 * Displays the forget password form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmReset()
	{
		parent::display( 'site/profile/reset.password' );
	}


	/**
	 * Displays the forget password form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function completeReset()
	{
		parent::display( 'site/profile/reset.password.complete' );
	}


}
