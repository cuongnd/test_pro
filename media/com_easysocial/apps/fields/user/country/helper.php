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
 * Helper file for country.
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserCountryHelper
{
	/**
	 * Retrieves a list of countries from the manifest file.
	 *
	 * @since	1.0
	 * @access	public
	 * @return 	Array	 An array of countries.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public static function getCountries()
	{
		static $countries = null;

		if( !$countries )
		{
			$file 		= Foundry::resolve( 'fields:/user/country/countries.json');
			$contents 	= JFile::read( $file );

			$json 		= Foundry::json();
			$countries 	= $json->decode( $contents );
		}

		return $countries;
	}

	/**
	 * Gets the country title given the code.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	Country code
	 * @return	string	Country name
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public static function getCountryName( $code )
	{
		$countries = self::getCountries();

		if( !isset( $countries->$code ) )
		{
			return false;
		}

		$value 	= $countries->$code;

		return $value;
	}

	public static function getHTMLContentCountries( $sort = 'code', $display = 'code' )
	{
		$countries = (array) self::getCountries();

		if( $sort === 'code' )
		{
			ksort( $countries );
		}

		if( $sort === 'name' )
		{
			asort( $countries );
		}

		if( $sort === 'rcode' )
		{
			krsort( $countries );
		}

		if( $sort === 'rname' )
		{
			arsort( $countries );
		}

		$data = array();

		foreach( $countries as $key => $value )
		{
			$row = new stdClass();
			$row->id = $key;

			if( $display === 'code' )
			{
				$row->title = $key;
			}

			if( $display === 'name' )
			{
				$row->title = $value;
			}

			$data[] = $row;
		}

		return $data;
	}
}
