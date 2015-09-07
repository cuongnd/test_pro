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

require_once( dirname( __FILE__ ) . '/library/Decoda.php' );

class BBCodeDecodaAdapter
{
	/**
	 * Processes a string with decoda library.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function parse( $message , $options = array() )
	{
		$decoda 	= new Decoda();
		$decoda->addFilter( new DefaultFilter() );

		// @TODO: Allow admin to configure to allow emoticon
		if( isset( $options[ 'emoticons' ] ) && $options[ 'emoticons' ] )
		{
			$decoda->addFilter( new ImageFilter() );
			$decoda->addHook( new EmoticonHook() );
		}
		$decoda->reset( $message );
		$message 	= $decoda->parse();
		
		return $message;
	}

	public function parseRaw( $string , $filters = array() )
	{
		$decoda 	= new Decoda();

		foreach( $filters as $filter )
		{
			$filterClass 	= ucfirst( $filter ) . 'Filter';

			if( class_exists( $filterClass ) )
			{
				$filterObj 	= new $filterClass();

				$decoda->addFilter( $filterObj );
			}
		}

		$decoda->reset( $string );
		$string 	= $decoda->parse();

		return $string;
	}
}
