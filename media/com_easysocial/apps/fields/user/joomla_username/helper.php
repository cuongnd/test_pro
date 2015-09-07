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

/**
 * Helper file.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserJoomlaUsernameHelper
{
	/**
	 * Determines if the username is allowed
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The username to check against.
	 * @param	JRegistry	The field's registry.
	 * @return	bool	True if username is allowed, false otherwise.
	 */
	public static function allowed( $username , &$params )
	{
		$disallowed 	= trim( $params->get( 'disallowed' , '' ) );

		// If nothing is defined as allowed
		if( empty( $disallowed ) )
		{
			return true;
		}

		$disallowed 	= Foundry::makeArray( $disallowed , ',' );

		if( empty( $disallowed ) )
		{
			return true;
		}


		if( !in_array( $username , $disallowed ) )
		{
			return true;
		}

		return false;
	}

	/**
	 * Determines if a username already exist in the system.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The username to check against.
	 * @return	bool	True if exists, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function exists( $username, $current = '' )
	{
		if( !empty( $current ) && $username === $current )
		{
			return false;
		}

		$db 		= Foundry::db();
		$sql		= $db->sql();

		$sql->select( '#__users' )
			->where( 'username', $username );

		$db->setQuery( $sql->getTotalSql() );
		$exists 	= $db->loadResult();

		return (bool) $exists;
	}


	/**
	 * Validates a username for proper syntax.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The username string.
	 * @return	bool	True if valid, false otherwise.
	 */
	public static function isValid( $username, $params )
	{
		if( empty( $username ) || preg_match( "#[<>\"'%;()&]#i", $username ) )
		{
			return false;
		}

		if( $params->get( 'regex_validate' ) )
		{
			$format = preg_quote( $params->get( 'regex_format' ) );

			$modifier = $params->get( 'regex_modifier' );

			$pattern = '/' . $format . '/' . $modifier;

			$result = preg_match( $pattern, $username );

			if( empty( $result ) )
			{
				return false;
			}
		}

		return true;
	}
}
