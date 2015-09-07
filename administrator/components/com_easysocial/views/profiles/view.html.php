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

// Include main views file.
Foundry::import( 'admin:/views/views' );

class EasySocialViewProfiles extends EasySocialAdminView
{
	/**
	 * Displays a list of profiles in the back end.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function display( $tpl = null )
	{
		// Add Joomla buttons here.
		$this->addButtons( __FUNCTION__ );

		// Set the structure heading here.
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_PROFILE_TYPES' ) );

		// Set page icon.
		$this->setIcon( 'icon-jar jar-id_card_clear' );

		// Set the structure description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_PROFILES' ) );

		// Gets a list of profiles from the system.
		$model 		= Foundry::model( 'Profiles' , array( 'initState' => true ));

		// perform some maintenance actions here
		$model->deleteOrphanItems();

		// Get the search query from post
		$search		= JRequest::getVar( 'search' , $model->getState( 'search' ) );

		// Get the current ordering.
		$ordering 	= JRequest::getWord( 'ordering' , $model->getState( 'ordering' ) );
		$direction 	= JRequest::getWord( 'direction' , $model->getState( 'direction' ) );
		$state	 	= JRequest::getVar( 'state', $model->getState( 'state' ) );
		$limit 		= $model->getState( 'limit' );

		// Prepare options
		$profiles	= $model->getItems();
		$pagination	= $model->getPagination();

		$callback 	= JRequest::getVar( 'callback' , '' );

		$orphanCount = $model->getOrphanMembersCount( false );

		// Set properties for the template.
		$this->set( 'limit'		, $limit );
		$this->set( 'state'		, $state );
		$this->set( 'ordering'		, $ordering );
		$this->set( 'direction'		, $direction );
		$this->set( 'callback'		, $callback );
		$this->set( 'pagination'	, $pagination );
		$this->set( 'profiles'		, $profiles );
		$this->set( 'search'		, $search );
		$this->set( 'orphanCount'	, $orphanCount );

		echo parent::display( 'admin/profiles/default' );
	}

	/**
	 * Displays a the profile form when someone creates a new profile type or edits an existing profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableProfile	The profile object (Optional)
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function form( $profile = '' )
	{
		// Get the profile id from the request.
		$id 		= JRequest::getInt( 'id' );

		// Add Joomla buttons here.
		$this->addButtons( __FUNCTION__ );

		// Test if id is provided by the query string
		if( !$profile )
		{
			$profile    = Foundry::table( 'Profile' );

			if( $id )
			{
				$state 		= $profile->load( $id );

				if( !$state )
				{
					$this->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );

					Foundry::info()->set( $this->getMessage() );

					return $this->redirect( 'index.php?option=com_easysocial&view=profiles' );
				}
			}
		}

		// Apply the heading
		if( !empty( $id ) )
		{
			// Set additional pathway here.
			$this->setHeading( JText::sprintf( 'COM_EASYSOCIAL_TOOLBAR_TITLE_EDIT_PROFILE_TYPE' , $profile->get( 'title' ) ) );
		}
		else
		{
			// Set the structure heading here.
			$this->setHeading( JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_NEW_PROFILE_TYPE' ) );
		}

		// Set page icon here.
		$this->setIcon( 'icon-jar jar-id_card_clear' );

		// Set the structure description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_PROFILES_FORM' ) );

		// Default Values
		$defaultAvatars 	= array();

		// Only process the rest of the blocks of this is not a new item.
		if( $id )
		{
			// Get a list of users in this profile.
			$profilesModel 	= Foundry::model( 'Profiles' );

			// Get default avatars for this profile type.
			$avatarsModel 	= Foundry::model( 'Avatars' );
			$defaultAvatars = $avatarsModel->getDefaultAvatars( $profile->id );

			// Get a list of available field apps
			$fieldsModel	= Foundry::model( 'Fields' );
			$defaultApps	= $fieldsModel->getFieldApps();

			// Get a list of workflows for this profile type.
			$stepsModel		= Foundry::model( 'Steps' );
			$steps			= $stepsModel->getSteps( $profile->id, SOCIAL_TYPE_PROFILES );

			// Get a list of fields based on the id
			$fields			= $fieldsModel->getCustomFields( array( 'profile_id' => $profile->id, 'state' => 'all' ) );

			$data = array();

			// @field.triggers: onSample
			$lib = Foundry::getInstance( 'Fields' );
			$lib->trigger( 'onSample' , SOCIAL_FIELDS_GROUP_USER , $fields , $data );


			// Create a temporary storage
			$tmpFields 	= array();

			// Group the fields to each workflow properly
			if( $steps )
			{
				foreach( $steps as $step )
				{
					$step->fields = array();

					if( !empty( $fields ) )
					{
						foreach( $fields as $field )
						{
							if( $field->step_id == $step->id )
							{
								$step->fields[] = $field;
							}

							$tmpFields[ $field->app_id ]	= $field;
						}
					}
				}
			}

			// We need to know the amount of core apps and used core apps
			$coreAppsCount 		= 0;
			$usedCoreAppsCount	= 0;

			// hide the apps if it is a core app and it is used in the field
			if( $defaultApps )
			{
				foreach( $defaultApps as $app )
				{
					$app->hidden = false;

					if( $app->core )
					{
						$coreAppsCount++;
					}

					// Test if this app has already been assigned to the $tmpFields
					if( isset( $tmpFields[ $app->id ] ) && $app->core )
					{
						$usedCoreAppsCount++;

						$app->hidden	= true;
					}

					// Test if this app is unique and has already been assigned
					if( isset( $tmpFields[ $app->id ] ) && $app->unique )
					{
						$app->hidden	= true;
					}
				}
			}

			unset( $tmpFields );

			// We need to know if there are any core apps remain
			$coreAppsRemain = $usedCoreAppsCount < $coreAppsCount;

			// Render the access form.
			$accessModel 	= Foundry::model( 'Access' );
			$accessForm		= $accessModel->getForm( $id , SOCIAL_TYPE_PROFILES , 'access' );

			$this->set( 'accessForm'	, $accessForm );

			// Set the flag of coreAppsRemain
			$this->set( 'coreAppsRemain', $coreAppsRemain);

			// Set the default apps to the template.
			$this->set( 'defaultApps'	, $defaultApps );

			// Set the steps for the template.
			$this->set( 'steps'			, $steps );

			// Set the fields to the template
			$this->set( 'fields'		, $fields );

			// Get the total number of users in the current profile.
			$membersCount	= $profile->getMembersCount();

			// Set member's count to the template.
			$this->set( 'membersCount'	, $membersCount );
		}

		// Get a list of themes.
		$themesModel	= Foundry::model( 'Themes' );
		$themes 		= $themesModel->getThemes();

		// Get profile parameters
		$params 		= $profile->getParams();

		// Get default privacy
		$privacy	= Foundry::get( 'Privacy' , $profile->id , SOCIAL_PRIVACY_TYPE_PROFILE );

		// We need to hide the guest user group that is defined in com_users options.
		// Public group should also be hidden.
		if( Foundry::getInstance( 'Version' )->getVersion() == '1.5' )
		{
			$guestGroup		= array( 0 , 'Guest' );
		}
		else
		{
			$userOptions 	= JComponentHelper::getComponent( 'com_users' )->params;

			$defaultRegistrationGroup 	= $userOptions->get( 'new_usertype' );
			$guestGroup		= array( 1 , $userOptions->get( 'guest_usergroup' ) );
		}

		// Set the default registration group for new items
		if( !$id )
		{
			$profile->gid 	= $defaultRegistrationGroup;
		}

		$this->set( 'defaultAvatars', $defaultAvatars );
		$this->set( 'guestGroup'	, $guestGroup );
		$this->set( 'id'			, $id );
		$this->set( 'themes'		, $themes );
		$this->set( 'param'			, $params );
		$this->set( 'profile' 		, $profile );
		$this->set( 'privacy'		, $privacy );

		echo parent::display( 'admin/profiles/default.form' );
	}

	/**
	 * This is to return a list of users in an iframe / html.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getUsers()
	{
		// @task: Obtain the profile id from query string.
		$id			= JRequest::getInt( 'profile_id' );

		// @task: Obtain search from query string
		$search		= JRequest::getVar( 'search' );

		// @task: Get the profiles model.
		$model		= Foundry::get( 'Model' , 'Profiles' );

		// @task: Get the users that are already part of this profile so that we can exclude them.
		$exclusion	= $model->getMembers( $id );

		// @task: Now, we need to get the final result of users.
		$userModel	= Foundry::get( 'Model' , 'User' );
		$users		= $userModel->getItems( array( 'exclusion' => array( 'a.id' => $exclusion ) ) );
		$pagination	= $userModel->getPagination();

		// @task: Initialize the user objects.
		$users		= Foundry::user( $users );

		$this->set( 'search'	, $search );
		$this->set( 'pagination', $pagination );
		$this->set( 'users'		, $users );

		parent::display( 'admin.profiles.users' );
	}


	/**
	 * Post processing for storing. What the view should do after a storing is executed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function store( $profile = '' )
	{
		// Get info object.
		$info 	= Foundry::info();

		// Set message
		$info->set( $this->getMessage() );

		// If there's an error on the storing, we don't need to perform any redirection.
		if( $error )
		{
			// Load the form for the user.
			return $this->form( $profile );
		}

		switch( $this->task )
		{
			case 'apply':
				$this->redirect( 'index.php?option=com_easysocial&view=profiles&id=' . $profile->id . '&layout=form' );
			break;

			case 'savenew':
				$this->redirect( 'index.php?option=com_easysocial&view=profiles&layout=form' );
			break;

			case 'save':
			default:
				$this->redirect( 'index.php?option=com_easysocial&view=profiles' );
			break;

		}
	}

	/**
	 * Stores the profile and redirect back to the same edit page.
	 */
	public function apply( $profile = '' )
	{
		$this->processMessages();

		return $this->form( $profile );
	}

	/**
	 * Post processing for delete. What the view should do after a delete is executed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function delete()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->app->redirect( 'index.php?option=com_easysocial&view=profiles' );
	}

	/**
	 * Post processing after items have been reordered
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function updateOrdering()
	{
		$this->app->redirect( 'index.php?option=com_easysocial&view=profiles' );
	}

	/**
	 * Post processing after an item have been moved up
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function move()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=profiles' );
	}

	/**
	 * Post processing for publish / unpublish. What the view should do after publishing is executed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function togglePublish()
	{
		$info 	= Foundry::info();

		// Set the message that is passed from the controller.
		$info->set( $this->getMessage() );

		return $this->redirect( 'index.php?option=com_easysocial&view=profiles' );
	}

	/**
	 * Post processing for setting a profile type as default.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function toggleDefault()
	{
		$info 	= Foundry::info();

		$info->set( $this->getMessage() );

		return $this->redirect( 'index.php?option=com_easysocial&view=profiles' );
	}

	/**
	 * Adds buttons to the page.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Array	An array of buttons.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function addButtons( $layout )
	{
		switch( $layout )
		{
			case 'form':

				JToolbarHelper::apply( 'apply' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE' ) , false , false );
				JToolbarHelper::save( 'save' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_CLOSE' ) );

				if( Foundry::getInstance( 'Version' )->getVersion() >= '1.6' )
				{
					JToolbarHelper::save2new( 'savenew' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_NEW' ) );
				}
				else
				{
					JToolbarHelper::save( 'savenew' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_NEW' ) );
				}
				JToolbarHelper::divider();
				JToolbarHelper::cancel( 'cancel' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_CANCEL' ) );
			break;

			case 'display':
			default:
				JToolbarHelper::addNew( 'form' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_NEW' ) , false );
				JToolbarHelper::divider();
				JToolbarHelper::publishList( 'publish' );
				JToolbarHelper::unpublishList( 'unpublish' );
				JToolbarHelper::divider();
				JToolbarHelper::deleteList( '' , 'delete' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_DELETE' ) );

			break;
		}
	}

}
