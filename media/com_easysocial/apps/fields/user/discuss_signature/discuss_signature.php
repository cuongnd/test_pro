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

// Include the fields library
Foundry::import( 'admin:/includes/fields/fields' );

/**
 * Field application for EasyDiscuss
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserDiscuss_Signature extends SocialFieldItem
{
	/**
	 * Determines if EasyDiscuss exists on the site.
	 */
	private $exists 	= false;

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{

		$file 	= JPATH_ROOT . '/components/com_easydiscuss/helpers/helper.php';

		if( JFile::exists( $file ) )
		{
			// Load EasyDiscuss language files
			JFactory::getLanguage()->load( 'com_easydiscuss' , JPATH_ROOT );

			$this->exists 	= true;
			require_once( $file );
		}

		parent::__construct();
	}

	/**
	 * Check if EasyDiscuss is exist
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool
	 *
	 * @author	Eric Tan <eric@stackideas.com>
	 */
	public function attachHeaders()
	{
		DiscussHelper::loadHeaders();
		$config 	= DiscussHelper::getConfig();

		DiscussHelper::loadStylesheet('site', $config->get( 'layout_site_theme' ) );
	}

	/**
	 * Retrieves EasyDiscuss profile table
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id.
	 * @return	DiscussTableProfile
	 */
	public function getProfile( $id )
	{
		static $ids 	= array();

		if( !isset( $ids[ $id ] ) )
		{
			$profile 	= DiscussHelper::getTable( 'Profile' );
			$profile->load( $id );

			$ids[ $id ]	= $profile;
		}

		return $ids[ $id ];
	}

	/**
	 * Validates the field when the user edits their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Adelene Tea <adelene@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		return $this->validate( $post );
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterValidate( &$post , SocialTableRegistration &$registration )
	{
		return $this->validate();
	}

	/**
	 * Validates the field
	 *
	 * @since	1.0
	 * @access	private
	 * @param	Array
	 * @return
	 */
	private function validate( $post )
	{
		$value 	= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		if( $this->isRequired() && empty( $value ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_TEXTAREA_VALIDATION_PLEASE_ENTER_SOME_VALUES' ) );
		}
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegister( &$post , &$registration )
	{
		// Check if EasyDiscuss exists on the system
		if( !$this->exists )
		{
			return;
		}

		// Load EasyDiscuss headers
		$this->attachHeaders();

		// Get EasyDiscuss profile object
		$profile = $this->getProfile( $user->id );

		$value	= $profile->signature;

		// Get the value from posted data if it's available.
		$value 	= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : $value;

		// Get any errors for this field.
		$error		= $registration->getErrors( $this->inputName );

		// Push to template
		$this->set( 'isEnabled' , $isEnabled );
		$this->set( 'error'		, $error );
		$this->set( 'value'		, $this->escape( $value ) );

		// Display the output.
		return $this->display();
	}

	/**
	 * Displays the field input for user on edit page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser	The user object who is editting
	 * @param	Array		The post data in array
	 * @param	Array		The errors in array
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		// Check if EasyDiscuss exists on the system
		if( !$this->exists )
		{
			return;
		}

		// Load EasyDiscuss headers
		$this->attachHeaders();

		// Get EasyDiscuss profile object
		$profile 	= $this->getProfile( $user->id );

		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : $profile->signature;

		$error		= $this->getError( $errors );

		$this->set( 'error'	 , $error );
		$this->set( 'value'	 , $this->escape( $value ) );

		return $this->display();
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		if( $this->exists && JFactory::getDocument()->getType() === 'html' )
		{
			$this->attachHeaders();
		}

		$this->set( 'exists' , $this->exists );

		if( !$this->exists )
		{
			return $this->display('error');
		}

		return $this->display();
	}

	/**
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onEditAfterSave( &$data , &$user )
	{
		$state	= $this->saveSignature( $data , $user->id , $this->inputName );

		// Remove the data from $post to prevent description saving in fields table
		unset( $data[$this->inputName] );
	}

	/**
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onRegisterAfterSave( &$data )
	{
		$user 	= Foundry::user();
		$state	= $this->saveSignature( $data , $user->id );

		// Remove the data from $post to prevent description saving in fields table
		unset( $data[$this->inputName] );
	}

	/**
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 * @author	Eric Tan <eric@stackideas.com>
	 */
	public function saveSignature( $data , $userId )
	{
		if( !$this->exists )
		{
			return;
		}

		// Get the profile object
		$profile 	= $this->getProfile( $userId );

		$profile->signature 	= $data[ $this->inputName ];

		$state 	= $profile->store();

		return $state;
	}
}
