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

class ThemesHelperString
{
	public static function escape( $string )
	{
		return Foundry::get( 'String' )->escape( $string );
	}

	/**
	 * Formats a given date string with a given date format
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The current timestamp
	 * @param	string		The language string format or the format for Date
	 * @param	bool		Determine if it should be using the appropriate offset or GMT
	 * @return	
	 */
	public static function date( $timestamp , $format = '' , $withOffset = true )
	{
		// Get the current date object based on the timestamp provided.
		$date 	= Foundry::date( $timestamp , $withOffset );

		// If format is not provided, we should use DATE_FORMAT_LC2 by default.
		$format	= empty( $format ) ? 'DATE_FORMAT_LC2' : $format;

		// Get the proper format.
		$format	= JText::_( $format );
		
		$dateString 	= $date->toFormat( $format );

		return $date->toFormat( $format );
	}

	/**
	 * Truncates a string at a centrain length and add a more link
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public static function truncater( $text , $maxLength )
	{
		$theme 	= Foundry::themes();

		$length	= JString::strlen( $text );
		$uid 	= uniqid();

		$theme->set( 'uid'	, $uid );
		$theme->set( 'length', $length );
		$theme->set( 'text' , $text );
		$theme->set( 'max'	, $maxLength );

		$output 	= $theme->output( 'admin/html/string.truncater' );

		return $output;
	}
}