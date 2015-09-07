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

jimport('joomla.mail.helper');

/**
 * Helper class for this field.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserJoomlaEmailHelper
{
	/**
	 * Determines if a domain name is disallowed
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function isDisallowed( $email , &$params )
	{
		// Detect for disallowed domain names.
		$domains 	= trim( $params->get( 'disallowed' , '' ) );

		// If there's no domains set, return as false.
		if( empty( $domains ) )
		{
			return false;
		}

		// Ensure that it's an array.
		$domains 	= Foundry::makeArray( $domains );

		foreach( $domains as $domain )
		{
			$search 	= '@' . $domain;

			if( stristr( $email , $search ) !== false )
			{
				return true;
			}
		}

		// If nothing matched above, we just say it's invalid.
		return false;
	}

	/**
	 * Determines if an email is forbidden
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function isForbidden( $email , &$params )
	{
		// Detect forbidden words.
		$forbidden 	= trim( $params->get( 'forbidden' , '' ) );

		if( empty( $forbidden ) )
		{
			return false;
		}

		// Ensure that the text is in an array.
		$forbidden	= Foundry::makeArray( $forbidden );

		// Check for forbidden
		foreach( $forbidden as $word )
		{
			if( stristr( $email, $word ) !== false )
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Validates a provided email address.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The email address to verify.
	 * @return	bool	True if validates, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function isValid( $email )
	{
		if( empty( $email ) || !JMailHelper::isEmailAddress( $email ) )
		{
			return false;
		}

		return true;
	}

	/**
	 * Determines if an email already exist in the system.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The email to check against.
	 * @return	bool	True if exists, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function exists( $email , $currentEmail = '' )
	{
		// If current email is provided, they might not be setting any value for their email.
		if( $email === $currentEmail )
		{
			return false;
		}

		$db         = Foundry::db();
		$sql		= $db->sql();

		$sql->select( '#__users' )
			->where( 'email', $email );

		$db->setQuery( $sql->getTotalSql() );

		$exists		= $db->loadResult() > 0 ;

		return $exists;
	}
}
