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

class EasySocialViewUsers extends EasySocialAdminView
{
	/**
	 * Default user listings page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function display( $tpl = null )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_USERS' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-user_client' );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_USERS' ) );

		// Add Joomla buttons
		JToolbarHelper::addNew();
		JToolbarHelper::divider();
		JToolbarHelper::publishList( 'publish' , JText::_( 'COM_EASYSOCIAL_UNBLOCK' ) );
		JToolbarHelper::unpublishList( 'unpublish' , JText::_( 'COM_EASYSOCIAL_BLOCK' ) );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'assign' , 'addusers' , '' , JText::_( 'COM_EASYSOCIAL_ASSIGN_GROUP' ) );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'switchProfile' , 'switchprofile' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SWITCH_PROFILE' ) );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'assignBadge' , 'assignbadge' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_ASSIGN_BADGE' ) );
		JToolbarHelper::custom( 'assignPoints' , 'assignpoints' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_ASSIGN_POINTS' ) );
		JToolbarHelper::deleteList();

		// Get the model
		$modelProfiles 	= Foundry::model( 'Profiles' );
		$model 			= Foundry::model( 'Users' , array( 'initState' => true ) );

		// perform some maintenance actions here
		$modelProfiles->deleteOrphanItems();

		// Get filter states.
		$ordering 	= JRequest::getVar( 'ordering' , $model->getState( 'ordering' ) );
		$direction 	= JRequest::getVar( 'direction'	, $model->getState( 'direction' ) );
		$limit 		= $model->getState( 'limit' );
		$published 	= $model->getState( 'published' );
		$search 	= JRequest::getVar( 'search'	, $model->getState( 'search' ) );
		$group		= JRequest::getInt( 'group' , $model->getState( 'group' ) );
		$profile 	= JRequest::getInt( 'profile' , $model->getState( 'profile' ) );

		// Get users
		$users		= $model->getUsersWithState();

		// Get pagination from model
		$pagination		= $model->getPagination();

		$callback 		= JRequest::getVar( 'callback' , '' );

		$this->set( 'profile'		, $profile );
		$this->set( 'ordering'		, $ordering );
		$this->set( 'limit'			, $limit );
		$this->set( 'direction'		, $direction );
		$this->set( 'callback'		, $callback );
		$this->set( 'search'		, $search );
		$this->set( 'published'		, $published );
		$this->set( 'group'			, $group );
		$this->set( 'pagination'	, $pagination );
		$this->set( 'users' 		, $users );

		echo parent::display( 'admin/users/default' );
	}


	/**
	 * Displays a list of pending approval users.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function pending()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		// Display buttons on this page.
		JToolbarHelper::deleteList();
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'approve' , 'publish' , 'social-publish-hover' , JText::_( 'COM_EASYSOCIAL_APPROVE_BUTTON' ) , true );
		JToolbarHelper::custom( 'reject' , 'unpublish' , 'social-unpublish-hover' , JText::_( 'COM_EASYSOCIAL_REJECT_BUTTON' ) , true );

		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_PENDING_APPROVALS' ) );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_PENDING_APPROVALS' ) );

		// Get the user's model.
		$model 			= Foundry::model( 'Users' );

		$ordering 	= JRequest::getVar( 'ordering' , $model->getState( 'ordering' ) );
		$direction 	= JRequest::getVar( 'direction'	, $model->getState( 'direction' ) );
		$limit 		= $model->getState( 'limit' );
		$published 	= $model->getState( 'published' );
		$filter		= JRequest::getWord( 'filter' , $model->getState( 'filter' ) );
		$profile 	= JRequest::getInt( 'profile' , $model->getState( 'profile' ) );

		$result 		= $model->getUsers( array( 'state' => SOCIAL_REGISTER_APPROVALS ) );
		$pagination 	= $model->getPagination();
		$users 			= array();

		if( $result )
		{
			foreach( $result as $row )
			{
				$users[]	= Foundry::user( $row->id );
			}
		}

		$profilesModel	= Foundry::model( 'Profiles' );
		$profiles 		= $profilesModel->getProfiles();

		$this->set( 'profile'		, $profile );
		$this->set( 'limit'			, $limit );
		$this->set( 'ordering'		, $ordering );
		$this->set( 'direction'		, $direction );
		$this->set( 'profiles'		, $profiles );
		$this->set( 'users'			, $users );
		$this->set( 'pagination'	, $pagination );
		$this->set( 'filter'		, $filter );
		$this->set( 'search'		, '' );

		parent::display( 'admin/users/default.pending' );
	}

	/**
	 * Post process after account is activated
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function activate()
	{
		Foundry::info()->set( $this->getMessage() );

		return $this->redirect( 'index.php?option=com_easysocial&view=users' );
	}

	/**
	 * Post processing after a user is blocked or unblocked
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function togglePublish()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
		$this->close();
	}

	/**
	 * Post processing after a user profile has changed
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function switchProfile()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
		$this->close();
	}

	/**
	 * Displays user profile layout in the back end.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function form( $errors = null )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		// Set any errors
		if( $this->hasErrors() )
		{
			Foundry::info()->set( $this->getMessage() );
		}

		// Get the user from the request.
		$id 		= JRequest::getInt( 'id' );

		// Add Joomla buttons
		$this->addButtons( __FUNCTION__ );

		// Set page heading
		if( !$id )
		{
			echo $this->newForm( $errors );
		}
		else
		{
			echo $this->editForm( $id, $errors );
		}
	}

	/**
	 * Displays the new user form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function newForm( $errors = null )
	{
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_CREATE_USER' ) );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_CREATE_USER' ) );

		// Get the profile id
		$profileId 		= JRequest::getInt( 'profileId' );

		$model 		= Foundry::model( 'Profiles' );
		$profiles	= $model->getProfiles();

		$profile 	= Foundry::table( 'Profile' );

		// Load front end's language file
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
		
		// If profile id is already loaded, just display the form
		if( $profileId )
		{
			$profile->load( $profileId );

			// Get the steps model
			$stepsModel = Foundry::model( 'Steps' );
			$steps 		= $stepsModel->getSteps( $profileId , SOCIAL_TYPE_PROFILES , SOCIAL_PROFILES_VIEW_EDIT );

			// Get custom fields model.
			$fieldsModel 	= Foundry::model( 'Fields' );
			$fields 		= Foundry::fields();

			// Build the arguments
			$user 			= new SocialUser();
			$post			= JRequest::get( 'post' );
			$args 			= array( &$post, &$user, $errors );

			// Get the custom fields for each of the steps.
			foreach( $steps as &$step )
			{
				$step->fields 	= $fieldsModel->getCustomFields( array( 'step_id' => $step->id ) );

				// Trigger onEdit for custom fields.
				if( !empty( $step->fields ) )
				{
					$fields->trigger( 'onAdminEdit' , SOCIAL_FIELDS_GROUP_USER , $step->fields , $args );
				}
			}

			$this->set( 'steps' , $steps );
		}

		$this->set( 'profile'	, $profile );
		$this->set( 'profileId'	, $profileId );
		$this->set( 'profiles' , $profiles );

		return parent::display( 'admin/users/form.new' );
	}

	/**
	 * Displays the edit form of user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The id of the user that is being edited.
	 */
	public function editForm( $id, $errors = null )
	{
		$user 		= Foundry::user( $id );
		$profile	= $user->getProfile();

		$this->setHeading( $user->getName() . ' (' . $profile->get( 'title' ) . ')' );

		$this->setIconUrl( $user->getAvatar( SOCIAL_AVATAR_LARGE ) );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_EDIT_USER' ) );

		// Load up language file from the front end.
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		// Get a list of access rules that are defined for this
		$accessModel	= Foundry::model( 'Access' );

		// Get user's privacy
		$privacy 		= Foundry::get( 'Privacy' , $user->id );
		$privacy 		= $privacy->getData();

		// Update the privacy data with proper properties.
		if( $privacy )
		{
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
		}


		// Get the steps model
		$stepsModel = Foundry::model( 'Steps' );
		$steps 		= $stepsModel->getSteps( $user->profile_id , SOCIAL_TYPE_PROFILES , SOCIAL_PROFILES_VIEW_EDIT );

		// Get custom fields model.
		$fieldsModel 	= Foundry::model( 'Fields' );

		// Get custom fields library.
		$fields 	= Foundry::fields();

		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		// Get the custom fields for each of the steps.
		foreach( $steps as &$step )
		{
			$step->fields 	= $fieldsModel->getCustomFields( array( 'step_id' => $step->id , 'data' => true , 'dataId' => $user->id , 'dataType' => SOCIAL_TYPE_USER ) );

			// Trigger onEdit for custom fields.
			if( !empty( $step->fields ) )
			{
				$post = JRequest::get( 'post' );
				$args 	= array( &$post, &$user, $errors );
				$fields->trigger( 'onAdminEdit' , SOCIAL_FIELDS_GROUP_USER , $step->fields , $args );
			}
		}

		// Get user badges
		$badges 	= $user->getBadges();

		// Get the user notification settings
		$alertLib 	= Foundry::alert();
		$alerts 	= $alertLib->getUserSettings( $user->id );

		// Get stats
		$stats 		= $this->getStats( $user );

		// Get user points history
		$pointsModel	= Foundry::model( 'Points' );
		$pointsHistory	= $pointsModel->getHistory( $user->id );

		// Default for Joomla 1.5
		$guestGroup		= array( 0 , 'Guest' );
		$userGroups 	= array_keys( $user->groups );

		// We need to hide the guest user group that is defined in com_users options.
		// Public group should also be hidden.
		if( Foundry::version()->getVersion() >= '1.6' )
		{
			$userOptions 	= JComponentHelper::getComponent( 'com_users' )->params;

			$defaultRegistrationGroup 	= $userOptions->get( 'new_usertype' );
			$guestGroup		= array( 1 , $userOptions->get( 'guest_usergroup' ) );
		}


		$this->set( 'userGroups'	, $userGroups );
		$this->set( 'guestGroup'	, $guestGroup );
		$this->set( 'stats'			, $stats );
		$this->set( 'pointsHistory' , $pointsHistory );
		$this->set( 'alerts'		, $alerts );
		$this->set( 'privacy'		, $privacy );
		$this->set( 'badges'		, $badges );
		$this->set( 'steps'			, $steps );
		$this->set( 'user'			, $user );

		return parent::display( 'admin/users/form' );
	}

	/**
	 * Retrieves user statistics
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getStats( SocialUser $user )
	{
		// Build user statistics
		$stats 			= $this->getStatsDates();
		$stats->items	= array();

		// Get list of user activity
		$streamModel		= Foundry::model( 'Stream' );
		$obj 			= new stdClass();
		$obj->title		= JText::_( 'COM_EASYSOCIAL_USERS_STATS_STREAM_POSTS' );
		$obj->items 	= $streamModel->getPostStats( $stats->dates , $user->id );
		$stats->items[]	= $obj;

		// Get list of user conversations
		// $conversationsModel 	= Foundry::model( 'Conversations' );
		// $conversationsModel->getConversationStats( $stats->dates , $user->id );

		// Get stats for user likes
		$likesModel 	= Foundry::model( 'Likes' );
		$obj 			= new stdClass();
		$obj->title		= JText::_( 'COM_EASYSOCIAL_USERS_STATS_LIKES' );
		$obj->items 	= $likesModel->getLikeStats( $stats->dates , $user->id );
		$stats->items[]	= $obj;

		// Get stats for user comments
		$commentsModel 		= Foundry::model( 'Comments' );
		$obj 			= new stdClass();
		$obj->title		= JText::_( 'COM_EASYSOCIAL_USERS_STATS_COMMENTS' );
		$obj->items 	= $commentsModel->getCommentStats( $stats->dates , $user->id );
		$stats->items[]	= $obj;

		return $stats;
	}

	/**
	 * Retrieves the date stats
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getStatsDates()
	{
		$dates 		= array();

		// Get the past 7 days
		$curDate 	= Foundry::date();
		for( $i = 0 ; $i < 7; $i++ )
		{
			$obj = new stdClass();

			if( $i == 0 )
			{
				$dates[]			= $curDate->toMySQL();
				$friendlyDates[]	= $curDate->format( 'jS M' );
			}
			else
			{
				$unixdate 		= $curDate->toUnix();
				$new_unixdate 	= $unixdate - ( $i * 86400);
				$newdate  		= Foundry::date( $new_unixdate );

				$dates[] 			= $newdate->toMySQL();
				$friendlyDates[]	= $newdate->format( 'jS M' );
			}
		}

		// Reverse the dates
		$dates 			= array_reverse( $dates );
		$friendlyDates	= array_reverse( $friendlyDates );

		$result 		= new stdClass();
		$result->dates			= $dates;
		$result->friendlyDates	= $friendlyDates;

		return $result;
	}

	/**
	 * Gets triggered when the user is approved
	 *
	 * @param	SocialUser	The user objct.
	 */
	public function approve( $user )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users&layout=pending' );
	}

	/**
	 * Gets triggered when the apply button is clicked.
	 *
	 * @param	Socialuser	The user objct.
	 */
	public function apply( &$user )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		$errors 	= $this->getErrors();

		if( $errors )
		{

		}

		$this->redirect( 'index.php?option=com_easysocial&view=users&id=' . $user->id . '&layout=form' );
	}

	/**
	 * Gets triggered when the save & close button is clicked.
	 *
	 * @param	Socialuser	The user objct.
	 */
	public function save( &$user )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		$errors 	= $this->getErrors();

		if( $errors )
		{

		}

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
	}

	/**
	 * Gets triggered when the save & new button is clicked.
	 *
	 * @param	Socialuser	The user objct.
	 */
	public function savenew( &$user )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		$errors 	= $this->getErrors();

		if( $errors )
		{

		}

		$this->redirect( 'index.php?option=com_easysocial&view=users&layout=form' );
	}

	/**
	 * Post process after saving a user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The current task
	 * @return
	 */
	public function store( $task , $user )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		// Enqueue the message
		Foundry::info()->set( $this->getMessage() );

		// If there's an error on the storing, we don't need to perform any redirection.
		if( $this->hasErrors() )
		{
			// Load the form for the user.
			return $this->form( $user );
		}

		if( $task == 'save' )
		{
			return $this->redirect( 'index.php?option=com_easysocial&view=users' );
		}

		if( $task == 'apply' )
		{
			return $this->redirect( 'index.php?option=com_easysocial&view=users&layout=form&id=' . $user->id );
		}

		if( $task == 'savenew' )
		{
			return $this->redirect( 'index.php?option=com_easysocial&view=users&layout=form' );
		}
	}

	/**
	 * Post process after a badge has been removed
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function removeBadge()
	{
		Foundry::info()->set( $this->getMessage() );

		$userId 	= JRequest::getInt( 'userid' );
		
		$this->redirect( 'index.php?option=com_easysocial&view=users&layout=form&id=' . $userId );
	}

	/**
	 * Reject a user's registration application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reject()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users&layout=pending' );
	}

	/**
	 * Post processing after user is assigned into a group
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assign()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
	}

	/**
	 * Post processing after user is deleted
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.users' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
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
				JToolbarHelper::save2new( 'savenew' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_NEW' ) );
				JToolbarHelper::cancel( 'cancel' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_CANCEL' ) );
			break;

			case 'display':
			default:


			break;
		}
	}

	/**
	 * Post process after points has been inserted for user
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function insertPoints()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
	}

	/**
	 * Post process after badge has been inserted for user
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function insertBadge()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=users' );
	}
}
