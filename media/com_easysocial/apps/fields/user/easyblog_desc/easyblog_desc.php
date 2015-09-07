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

$file 	= JPATH_ROOT . '/components/com_easyblog/helpers/helper.php';

if( JFile::exists( $file ) )
{
	require_once( $file );
}
/**
 * Field application for Joomla full name.
 *
 * @since	1.0
 * @author	Adelene Tea <adelene@stackideas.com>
 */
class SocialFieldsUserEasyBlog_Desc extends SocialFieldItem
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array		The post data.
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Adelene Tea <adelene@stackideas.com>
	 */
	public function onRegister( &$post , &$registration )
	{
		// Check for errors
		$error		= $registration->getErrors( $this->inputName );

		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// Set errors.
		$this->set( 'error', $error );
		$this->set( 'value', $this->escape( $value ) );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Adelene Tea <adelene@stackideas.com>
	 */
	public function onRegisterValidate( &$post )
	{
		$desc	 	= !empty( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		return $this->validateField( $desc );
	}

	/**
	 * Executes after a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Adelene Tea <adelene@stackideas.com>
	 */
	public function onRegisterAfterSave( &$post, &$user )
	{
		// We do not need to validate against reconfirm here
		$desc	 	= !empty( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		$table 	= EasyBlogHelper::getTable( 'Profile' );
		$table->load($user->id);
		$table->set('description', $desc);
		$table->store();

		// Remove the data from $post to prevent description saving in fields table
		unset( $post[$this->inputName] );

		return true;
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @return
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$blogProfile	= $this->getEasyBlogProfile( $user );

		$value			= !empty( $post[$this->inputName] ) ? $post[$this->inputName] : $blogProfile->description;

		$error = $this->getError( $errors );

		$this->set( 'value', $this->escape( $value ) );
		$this->set( 'error', $error );

		return $this->display();
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
		$desc	 	= !empty( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		return $this->validateField( $desc );
	}

	/**
	 * Executes before a user's edit is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The post data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Adelene Tea <adelene@stackideas.com>
	 */
	public function onEditAfterSave( &$post, &$user )
	{
		// We do not need to validate against reconfirm here
		$desc	 	= !empty( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		$blogProfile	= EasyBlogHelper::getTable( 'Profile' );
		$blogProfile->load( $user->id );

		$blogProfile->description	= $desc;

		// Store the data now
		$state 		= $blogProfile->store();

		// Remove the data from $post to prevent description saving in fields table
		unset( $post[$this->inputName] );

		return true;
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when their profile is viewed.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function onDisplay( $user )
	{
		$profile = $this->getEasyBlogProfile( $user );

		// Push variables into theme.
		$this->set( 'value'	, $this->escape( $profile->description ) );

		return $this->display( 'display' );
	}

	/**
	 * Validates the custom field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function validateField( $desc )
	{
		// Verify that the field are not empty
		if( empty( $desc ) && $this->isRequired() )
		{
			$this->setError( JText::_( 'PLG_FIELDS_JOOMLA_EASYBLOG_DESC_EMPTY_DESC' ) );

			return false;
		}

		return true;
	}

	/**
	 * Returns the profile object in EasyBlog
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser	The user's object
	 * @return	EasyBlogProfileTable
	 */
	private function getEasyBlogProfile( $user )
	{
		$blogProfile	= EasyBlogHelper::getTable( 'Profile' );
		$blogProfile->load( $user->id );

		return $blogProfile;
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	string	The html output.
	 *
	 * @author	Adelene Tea <adelene@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}
}
