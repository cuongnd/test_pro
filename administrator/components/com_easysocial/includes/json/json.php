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

class SocialJSON  
{
	private $json = null;

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialToolbar
	 */
	public static function getInstance()
	{
		static $instance = null;

		if( !$instance )
		{
			$instance 	= new self();
		}

		return $instance;
		return new self();
	}

	public function encode( $data , $loose = 0 )
	{
		return json_encode( $data );
	}

	/**
	 * Decodes a json string to an object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The json string data
	 * @return	
	 */
	public function decode( $data )
	{
		if( empty( $data ) )
		{
			return false;
		}

		$pattern 	= '#^\s*//.+$#m';
		$data 		= preg_replace( $pattern , '' , $data );
	
		$result = json_decode( $data );

		return $result;
	}
}