<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include main controller
Foundry::import( 'admin:/controllers/controller' );

class EasySocialControllerProfiles extends EasySocialController
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		// Map the alias methods here.
		$this->registerTask( 'unpublish', 'togglePublish' );
		$this->registerTask( 'publish'	, 'togglePublish' );

		$this->registerTask( 'save'		, 'store' );
		$this->registerTask( 'savenew' 	, 'store' );
		$this->registerTask( 'apply'    , 'store' );
	}

	/**
	 * Method to add a member into an existing profile type.
	 *
	 * @param   null    All parameters are from HTTP $_POST
	 * @return  JSON    JSON encoded string.
	 */
	public function insertMember()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get the id from request.
		$id 	= JRequest::getInt( 'id' );

		// Get the profile id.
		$profile_id 	= JRequest::getInt( 'profile_id' );

		// Get the current view.
		$view 	= $this->getCurrentView();

		if( !$id )
		{
			$view->setMessage( JText::_( 'Please enter a valid user id.' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// @TODO: Try to remove user from any other existing profile maps.
		$model 	= Foundry::model( 'Profiles' );
		$model->removeUserFromProfiles( $id );

		$table 	= Foundry::table( 'ProfileMap' );

		$table->user_id 	= $id;
		$table->profile_id 	= $profile_id;
		$table->state 		= SOCIAL_STATE_PUBLISHED;


		// @rule: Store user profile bindings
		$table->store();

		$user 	= Foundry::user( $id );

		return $view->call( __FUNCTION__ , $user );
	}

	/**
	 * Responsible to delete a profile from the system.
	 *
	 * @since   1.0
	 * @access  public
	 * @return  null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function delete()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the id from the post.
		$ids    = JRequest::getVar( 'cid' );

		// Ensure that the ids is now an array.
		$ids 	= Foundry::makeArray( $ids );

		// Get the view object.
		$view 	= Foundry::view( 'Profiles' );

		// Test if there's any id's being passed in.
		if( empty( $ids ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_DELETE_NO_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Let's go through each of the profile and delete it.
		foreach( $ids as $id )
		{
			$profile    = Foundry::table( 'Profile' );
			$profile->load( $id );

			// If profile has members in it, do not try to delete this.
			if( $profile->hasMembers() )
			{
				$view->setMessage( JText::sprintf( 'COM_EASYSOCIAL_ERROR_DELETE_PROFILE_CONTAINS_USERS' , $profile->title ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Now try to delete the profile.
			$profile->delete();
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_DELETED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Saves a new or existing profile.
	 *
	 * @since   1.0
	 * @access  public
	 * @param   null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function store()
	{
		// Check for request forgeries!
		Foundry::checkToken();

		$id         = JRequest::getInt( 'id' );
		$post       = JRequest::get( 'POST' );
		$isNew	    = ( empty( $id ) ) ? true : false;

		// Load the profile type.
		$profile    = Foundry::table( 'Profile' );
		$profile->load( $id );

		// Bind the posted data.
		$profile->bind( $post );

		// Get the view.
		$view		= $this->getCurrentView();

		// Get the current task since we need to know what to do after the storing is successful.
		$view->task 	= $this->getTask();

		// Bind the user group's that are associated with the profile.
		$gid 			= JRequest::getVar( 'gid' );

		// This is a minimum requirement to create a profile.
		if( !$gid )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ERROR_SELECT_GROUP' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $profile );
		}

		// Bind user groups for this profile.
		$profile->bindUserGroups( $gid );

		// Validate the profile field.
		$valid      = $profile->validate();

		// If there's errors, just show the error.
		if( $valid !== true )
		{
			$view->setMessage( $profile->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $profile );
		}

		// Try to store the profile.
		if( !$profile->store() )
		{
			$view->setMessage( $profile->getError() , SOCIAL_MSG_ERROR );
			return $view->store( $profile );
		}

		// Bind the access
		$profile->bindAccess( $post[ 'access' ] );

		// If this profile is default, we need to ensure that the rest of the profiles are not default any longer.
		if( $profile->default )
		{
			$profile->makeDefault();
		}

		// Store the avatar for this profile.
		$file 	= JRequest::getVar( 'avatar' , '' , 'FILES' );

		// Try to upload the profile's avatar if required
		if( !empty( $file[ 'tmp_name' ] ) )
		{
			$profile->uploadAvatar( $file );
		}

		// Set the fields for this profile type.
		if( isset( $post[ 'fields'] ) )
		{
			$model 	= Foundry::model( 'Profiles' );
			$model->updateFields( $profile->id , $post[ 'fields' ] );
		}

		// Set the privacy for this profile type
		if( isset( $post[ 'privacy'] ) )
		{
			$privacyLib = Foundry::privacy();
			$resetMap 	= $privacyLib->getResetMap();

			$privacy = $post['privacy'];
			$ids     = $post['privacyID'];

			$requireReset = isset( $post['privacyReset'] ) ? true : false;

			$data = array();

			if( count( $privacy ) )
			{
				foreach( $privacy as $group => $items )
				{
					foreach( $items as $rule => $val )
					{
						$id = $ids[ $group ][ $rule ];

						$id = explode('_', $id);

						$obj = new stdClass();

						$obj->id 	= $id[0];
						$obj->mapid = $id[1];
						$obj->value = $val;
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

			$privacyModel 	= Foundry::model( 'Privacy' );
			$privacyModel->updatePrivacy( $profile->id , $data, SOCIAL_PRIVACY_TYPE_PROFILE );
		}

		$message = ( $isNew ) ? JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_CREATED_SUCCESSFULLY' ) : JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_UPDATED_SUCCESSFULLY' );

		// Set message.
		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ , $profile );
	}

	/**
	 * Method to process files that is being sent to store default avatars.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function uploadDefaultAvatars()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view.
		$view	= $this->getCurrentView();

		// Load a table mapping.
		$defaultAvatar 	= Foundry::table( 'DefaultAvatar' );

		// Set the unique id for this avatar item.
		$defaultAvatar->uid 	= JRequest::getInt( 'uid' );

		// Set the unique type for this avatar item.
		$defaultAvatar->type 	= JRequest::getVar( 'type' , SOCIAL_TYPE_PROFILES );

		// Set the default state of the avatar to be published.
		$defaultAvatar->state 	= SOCIAL_STATE_PUBLISHED;

		// Let's try to upload now.
		$file 	= JRequest::get( 'Files' );
		$state 	= $defaultAvatar->upload( $file );

		// There's an error when saving the images.
		if( !$state )
		{
			$view->setMessage( $defaultAvatar->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $defaultAvatar );
		}

		// Let's try to save the defaultAvatar now.
		$state 	= $defaultAvatar->store();

		// If we hit any errors, we should notify the user.
		if( !$state )
		{
			// Set the error to the view.
			$view->setMessage( $defaultAvatar->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $defaultAvatar );
		}

		return $view->call( __FUNCTION__ , $defaultAvatar );
	}

	/**
	 * Toggles a profile as default.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function toggleDefault()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get current cid to work on.
		$cid        = JRequest::getVar( 'cid' );
		$cid 		= Foundry::makeArray( $cid );

		// Get the current view object.
		$view 		= Foundry::view( 'Profiles' );

		// Get the profile object.
		$profile    = Foundry::table( 'Profile' );

		if( !$cid )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_DOES_NOT_EXIST' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// A single item can only be default at a time.
		$cid		= $cid[ 0 ];

		// Load the profile
		$profile->load( $cid );

		if( !$profile->id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_DOES_NOT_EXIST' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Try to publish the profile.
		$state 	= $profile->makeDefault();

		if( !$state )
		{
			$view->setMessage( $profile->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the message
		$message 	= JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_PROFILE_IS_NOW_DEFAULT_PROFILE' );

		// Set the message to view.
		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Publishes a profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function togglePublish()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get the current task
		$task 		= $this->getTask();

		// Get current cid to work on.
		$cid        = JRequest::getVar( 'cid' );
		$cid 		= Foundry::makeArray( $cid );

		// Get the current view object.
		$view 		= Foundry::view( 'Profiles' );

		foreach( $cid as $id )
		{
			// Get the profile object.
			$profile    = Foundry::table( 'Profile' );

			// Load the profile
			$profile->load( $id );

			if( !$profile->id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_DOES_NOT_EXIST' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Do not allow admin to unpublish a default profile
			if( $profile->default )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_PROFILES_UNABLE_TO_UNPUBLISH_DEFAULT_PROFILE' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Try to publish the profile.
			if( !$profile->$task() )
			{
				$view->setMessage( $profile->getError() , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		// Get the message
		$message 	= $task == 'publish' ? JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_PUBLISHED_SUCCESSFULLY' ) : JText::_( 'COM_EASYSOCIAL_PROFILES_PROFILE_UNPUBLISHED_SUCCESSFULLY' );

		// Set the message to view.
		$view->setMessage( $message , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allows a profile to be ordered down
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function moveDown()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the id from the request
		$ids 	= JRequest::getVar( 'cid' );

		// Ensure that they are in the array
		$ids 	= Foundry::makeArray( $ids );

		if( !$ids )
		{
			$view->setMessage( JText::_( 'Invalid profile id provided' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'move' );
		}

		foreach( $ids as $id )
		{
			$profile 	= Foundry::table( 'Profile' );
			$profile->load( $id );

			// Move direction up
			$profile->move( 1 );
		}

		$view->setMessage( JText::_( 'Profile re-ordered successfully.' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( 'move' );
	}

	/**
	 * Allows a profile to be ordered up
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function moveUp()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the id from the request
		$ids 	= JRequest::getVar( 'cid' );

		// Ensure that they are in the array
		$ids 	= Foundry::makeArray( $ids );

		if( !$ids )
		{
			$view->setMessage( JText::_( 'Invalid profile id provided' ) , SOCIAL_MSG_ERROR );
			return $view->call( 'move' );
		}

		foreach( $ids as $id )
		{
			$profile 	= Foundry::table( 'Profile' );
			$profile->load( $id );

			// Move direction up
			$profile->move( -1 );

			// $profile->store();
		}

		$view->setMessage( JText::_( 'Profile re-ordered successfully.' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( 'move' );
	}

	/**
	 * Updates the ordering of the profiles
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function updateOrdering()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Get all the inputs.
		$ids 	= JRequest::getVar( 'cid' , null , 'post' , 'array' );
		$order	= JRequest::getVar( 'order' , null , 'post' , 'array' );

		// Sanitize the input
		JArrayHelper::toInteger( $ids );
		JArrayHelper::toInteger( $order );

		$model	= Foundry::model( 'Profiles' );
		$model->saveOrder( $ids , $order );

		$view 	= $this->getCurrentView();

		return $view->call( __FUNCTION__ );
	}

	public function getFieldValues()
	{
		$fieldid		= JRequest::getInt( 'fieldid', 0 );
		$values	= '';

		if( $fieldid !== 0 )
		{
			$fields	= Foundry::table( 'field' );
			$fields->load( $fieldid );

			$values 	= Foundry::json()->decode( $fields->params );

			if( !is_object( $values ) )
			{
				$values = new stdClass();
			}

			$values->core_title = $fields->title;
			$values->core_display_title = (boolean) $fields->display_title;
			$values->core_description = $fields->description;
			$values->core_required = (boolean) $fields->required;
			$values->core_default = $fields->default;
		}

		Foundry::view( 'Profiles' )->call( __FUNCTION__, $values );
	}

	/**
	 * Retrieves the profile page configuration
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPageConfig()
	{
		$path		= SOCIAL_CONFIG_DEFAULTS . '/fields.header.json';
		$raw		= JFile::read( $path );

		$params		= Foundry::json()->decode( $raw );


		foreach( $params as $name => &$fields )
		{
			// Only try to JText the label field if it exists.
			if( isset( $fields->label ) )
			{
				$fields->label	= JText::_( $fields->label );
			}

			// Only try to JText the tooltip field if it exists.
			if( isset( $fields->tooltip ) )
			{
				$fields->tooltip	= JText::_( $fields->tooltip );
			}

			// Only try to JText the default value if default exist and it is a string
			if( isset( $fields->default ) && is_string( $fields->default ) )
			{
				$fields->default = JText::_( $fields->default );
			}

			// If there are options set, we need to jtext them as well.
			if( isset( $fields->option ) )
			{
				$fields->option 	= Foundry::makeArray( $fields->option );

				foreach( $fields->option as &$option )
				{
					$option->label 	= JText::_( $option->label );
				}
			}
		}


		$pageid = JRequest::getInt( 'pageid', 0 );

		$table = Foundry::table( 'FieldStep' );

		if( !empty( $pageid ) )
		{
			$table->load( $pageid );

			if( isset( $table->title ) )
			{
				$table->title = JText::_( $table->title );
			}

			if( isset( $table->description ) )
			{
				$table->description = JText::_( $table->description );
			}
		}
		else
		{
			foreach( $params as $name => &$field )
			{
				$table->$name = $field->default;
			}
		}

		// Convert table into registry format
		$values = Foundry::registry( $table );

		$theme = Foundry::themes();
		$theme->set( 'title', JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PAGE_CONFIGURATION' ) );
		$theme->set( 'params', $params );
		$theme->set( 'values', $values );


		$html = $theme->output( 'admin/profiles/form.fields.pageConfig' );

		Foundry::view( 'Profiles' )->call( __FUNCTION__, $params, $table, $html );
	}

	/**
	 * Save the custom fields.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function saveFields()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		$id			= JRequest::getInt( 'id', 0 );
		$data		= JRequest::getString( 'data', '' );
		$deleted	= JRequest::getVar( 'deleted', array() );

		// Perform deletion first
		foreach( $deleted as $deletedtype => $deletedids )
		{
			if( !empty( $deletedids ) )
			{
				$name = $deletedtype == 'pages' ? 'fieldstep' : 'field';

				foreach( $deletedids as $deletedid )
				{
					$table = Foundry::table( $name );
					$state = $table->load( $deletedid );

					if( $state )
					{
						$table->delete();
					}
				}
			}
		}

		// Data was sent as json string to preserve type validity
		$json 	= Foundry::json();
		$data 	= $json->decode( $data );

		// Get current view.
		$view 	= $this->getCurrentView();

		$sequence = 1;

		// Loop through each steps that is available in this form.
		foreach( $data as $step )
		{
			$stepTable = Foundry::table( 'fieldstep' );

			$state = false;

			if( !$step->newpage )
			{
				$state = $stepTable->load( $step->id );
			}

			// $state = $stepTable->loadBySequence( $id , SOCIAL_TYPE_PROFILES , $sequence );

			// If there's a problem retrieving this step, this is probably a new step.
			if( !$state )
			{
				$stepTable->uid			= $id;
				$stepTable->type		= SOCIAL_TYPE_PROFILES;
				$stepTable->state		= SOCIAL_STATE_PUBLISHED;
				$stepTable->created		= Foundry::date()->toMySQL();
			}

			$stepTable->sequence	= $sequence;

			// Set the step values
			$stepTable->processParams( $step );

			// Try to store the step.
			$state 	= $stepTable->store();

			// If there's a problem storing the state, we should log errors here.
			if( !$state )
			{
				Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Unable to store step for field id ' . $id . ' with the sequence of ' . $sequence );

				$view->setMessage( 'FIELDS: Unable to store step for field id ' . $id . ' with the sequence of ' . $sequence );

				return $view->call( __FUNCTION__, false );
			}

			// Assign back the id to pass back to client
			$step->id = $stepTable->id;

			$sequence++;

			// When there's no fields for this step, just skip the rest of processing.
			if( !isset( $step->fields ) )
			{
				continue;
			}

			// Reset the ordering for the fields.
			$ordering	= 0;

			// Load fields model
			$model 		= Foundry::model( 'Fields' );

			// Now let's go through the list of fields for this step.
			foreach( $step->fields as $field )
			{
				$appTable 	= Foundry::table( 'App' );
				$fieldTable	= Foundry::table( 'Field' );

				$appTable->load( $field->appid );

				if( !empty( $field->fieldid ) )
				{
					$fieldTable->load( $field->fieldid );
				}

				// Set the application id.
				$fieldTable->app_id				= $field->appid;

				// Set the step id since we now know the step id.
				$fieldTable->step_id			= $stepTable->id;

				// Let's process the params
				if( property_exists( $field, 'params' ) )
				{
					$fieldTable->processParams( $field->params );
				}

				// Set the ordering now.
				$fieldTable->ordering			= $ordering;

				// The core state would be dependent on the app's settings.
				$fieldTable->core				= $appTable->core;

				// Let's try to store the field now.
				$state 	= $fieldTable->store();

				// If there's any problems storing the state, we should log errors and not proceed.
				if( !$state )
				{
					Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Unable to store the field for the step id ' . $stepTable->id );

					$view->setMessage( 'FIELDS: Unable to store the field for the step id ' . $stepTable->id, SOCIAL_MSG_ERROR );

					return $view->call( __FUNCTION__, false );
				}

				// Carry out actions that can only be done after the initial store to generate field id here

				// Assign back the field id to pass back to client
				$field->fieldid = $fieldTable->id;

				// Check if unique key for this field is valid and assign it back for the client
				$field->unique_key = $fieldTable->checkUniqueKey();

				// Save any post actions
				$fieldTable->store();

				// Now we store other data such as choices after the field as been stored

				// Check for choices here
				if( property_exists( $field, 'choices' ) )
				{
					foreach( $field->choices as $name => $choices )
					{
						$origChoices = $fieldTable->getOptions( $name );

						$currentChoices = array();

						$choiceOrdering = 0;

						foreach( $choices as $choice )
						{
							$fieldoptionsTable = Foundry::table( 'FieldOptions' );
							if( !empty( $choice->id ) )
							{
								$fieldoptionsTable->load( $choice->id );
							}

							$fieldoptionsTable->parent_id	= $fieldTable->id;
							$fieldoptionsTable->key			= $name;
							$fieldoptionsTable->title		= $choice->title;
							$fieldoptionsTable->value		= $choice->value;
							$fieldoptionsTable->ordering	= $choiceOrdering;

							if( isset( $choice->default ) ) {
								$fieldoptionsTable->default = $choice->default;
							}

							if( !$fieldoptionsTable->store() )
							{
								Foundry::logError( __FILE__, __LINE__, 'FIELDS: Unable to store the choices for the field id ' . $fieldTable->id );

								$view->setMessage( 'FIELDS: Unable to store the choices for the field id ' . $fieldTable->id, SOCIAL_MSG_ERROR );

								return $view->call( __FUNCTION__, false );
							}

							// Assign back the options id to pass back to client
							$choice->id = $fieldoptionsTable->id;

							$currentChoices[] = $choice->id;

							$choiceOrdering++;
						}

						foreach( $origChoices as $origId => $origChoices )
						{
							if( !in_array( $origId, $currentChoices ) )
							{
								$origChoices->delete();
							}
						}
					}
				}

				$ordering++;
			}
		}

		return $view->call( __FUNCTION__, $data );
	}
}
