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
 * Class to manipulate images.
 *
 * @since	1.0
 */
class SocialBBCode
{

	/**
	 * Stores the current adapter.
	 * @var	Object
	 */
	private $adapter    = null;

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @param	string	The image driver to use.
	 * @access	public
	 */
	public function __construct()
	{
		// For now, we'll hardcode it to use decoda.

		require_once( dirname( __FILE__ ) . '/adapters/decoda/decoda.php' );

		$this->adapter	= new BBCodeDecodaAdapter();
	}

	/**
	 * This class uses the factory pattern.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string			The image driver to use.
	 * @return	SocialImage		Returns itself for chaining.
	 */
	public static function factory()
	{
		$decoda 	= new self();

		return $decoda;
	}


	/**
	 * Processes a string with decoda library.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function parse( $string , $options = array() )
	{
		return $this->adapter->parse( $string , $options );
	}

	public function parseRaw( $string , $filters = array() )
	{
		return $this->adapter->parseRaw( $string , $filters );
	}
}
