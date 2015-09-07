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

// Include helper lib
Foundry::import( 'fields:/user/email/helper' );

/**
 * Field application for Email
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserEmail extends SocialFieldItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $config = array() )
	{
		parent::__construct( $config );
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
		// Get the value of submitted input
		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// Get any errors for this field.
		$error		= $registration->getErrors( $this->inputName );

		$this->set( 'error', $error );
		$this->set( 'value', $this->escape( $value ) );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the normal form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterValidate( &$post )
	{
		return $this->validateEmail( $post );
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
	public function onEdit( &$post, &$user, $errors )
	{
		$value = !empty( $post[$this->inputName] ) ? $post[$this->inputName] : $this->value;

		$error = $this->getError( $errors );

		$this->set( 'value', $this->escape( $value ) );
		$this->set( 'error', $error );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditValidate( &$post, &$user )
	{
		return $this->validateEmail( $post , $user->email );
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}


	/**
	 * return formated string from the fields value
	 *
	 * @since	1.0
	 * @access	public
	 * @param	userfielddata
	 * @return	array array of objects with two attribute, ffriend_id, score
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onIndexer( $userFieldData )
	{
		if(! $this->field->searchable )
			return false;

		$content = trim( $userFieldData );
		if( $content )
			return $content;
		else
			return false;
	}

	/**
	 * return formated string from the fields value
	 *
	 * @since	1.0
	 * @access	public
	 * @param	userfielddata
	 * @return	array array of objects with two attribute, ffriend_id, score
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onIndexerSearch( $itemCreatorId, $keywords, $userFieldData )
	{
		if(! $this->field->searchable )
			return false;

		$data 		= trim( $userFieldData );

		$content 			= '';
		if( JString::stristr( $data, $keywords ) !== false )
		{
			$content = $data;
		}

		if( $content )
		{
			$my = Foundry::user();
			$privacyLib = Foundry::privacy( $my->id );

			if( ! $privacyLib->validate( 'core.view', $this->field->id, SOCIAL_TYPE_FIELD, $itemCreatorId ) )
			{
				return -1;
			}
			else
			{
				// okay this mean the user can view this fields. let hightlight the content.

				// building the pattern for regex replace
				$searchworda	= preg_replace('#\xE3\x80\x80#s', ' ', $keywords);
				$searchwords	= preg_split("/\s+/u", $searchworda);
				$needle			= $searchwords[0];
				$searchwords	= array_unique($searchwords);

				$pattern	= '#(';
				$x 			= 0;

				foreach ($searchwords as $k => $hlword)
				{
					$pattern 	.= $x == 0 ? '' : '|';
					$pattern	.= preg_quote( $hlword , '#' );
					$x++;
				}
				$pattern 		.= ')#iu';

				$content 	= preg_replace( $pattern , '<span class="search-highlight">\0</span>' , $content );
				$content 	= JText::sprintf( 'PLG_FIELDS_EMAIL_SEARCH_RESULT', $content );
			}
		}

		if( $content )
			return $content;
		else
			return false;
	}


	/**
	 * Validates the posted email
	 *
	 * @since	1.0
	 * @access	private
	 * @param	string
	 * @return	bool		True if valid, false otherwise.
	 */
	private function validateEmail( &$post )
	{
		$email      = !empty( $post[ $this->inputName ] ) ? trim( $post[ $this->inputName ] ) : '';

		// Check for empty email
		if( $this->isRequired() && empty( $email ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_EMAIL_VALIDATION_REQUIRED' ) );
			return false;
		}

		// Check for email validity
		if( !SocialFieldsUserEmailHelper::isValid( $email ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_EMAIL_VALIDATION_INVALID_EMAIL' ) );
			return false;
		}

		// Check for disallowed domains
		if( SocialFieldsUserEmailHelper::isDisallowed( $email , $this->params ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_EMAIL_VALIDATION_DOMAIN_IS_DISALLOWED' ) );
			return false;
		}

		// Check for forbidden words
		if( SocialFieldsUserEmailHelper::isForbidden( $email , $this->params ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_EMAIL_VALIDATION_CONTAINS_FORBIDDEN' ) );
			return false;
		}

		return true;
	}

	public function onDisplay( $user )
	{
		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		if( !$this->value )
		{
			return;
		}

		$this->set( 'value', $this->escape( $this->value ) );

		return $this->display();
	}
}
