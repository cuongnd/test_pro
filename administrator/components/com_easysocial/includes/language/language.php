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

class SocialLanguage 
{
	protected $adapter		= null;
	private $string         = null;
	private $count          = true;
	private $number         = 0;
	
	public static function factory( $string = '' )
	{
		$obj 	= new self( $string );

		return $obj;
	}
	
	public function load( $type = 'com_easysocial' , $path )
	{
		static $languages = array();

		$index 	= md5( $type . $path );

		if( !isset( $languages[ $index ] ) )
		{
			$lang 	= JFactory::getLanguage();

			// // Load user's preferred language file.
			$lang->load( $type , $path , null , true );

			$languages[ $index ]	= true;		
		}

		return $languages[ $index ];
	}

	/**
	 * Loads a language file for an application
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The app group. E.g: user
	 * @param	string	The app element. E.g: blog
	 * @return	
	 */
	public function loadApp( $group , $element )
	{
		if( empty( $group ) || empty( $element ) )
		{
			return;
		}

		$namespace 	= 'plg_app_' . $group . '_' . $element;

		return $this->load( $namespace , SOCIAL_JOOMLA_ADMIN );
	}

	/**
	 * Loads a language file for custom fields
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The field group. E.g: user
	 * @param	string	The field element. E.g: text
	 * @return	
	 */
	public function loadField( $group , $element )
	{
		if( empty( $group ) || empty( $element ) )
		{
			return;
		}

		$namespace 	= 'plg_fields_' . $group . '_' . $element;

		return $this->load( $namespace , SOCIAL_JOOMLA_ADMIN );
	}


	public function __construct( $string = '' )
	{
	    if( empty( $string ) )
	    {
	        return $this;
		}
		$this->string   = $string;

		return $this;
	}

	public function pluralize( $count , $useCount = true )
	{
	    $this->count    = (boolean) $useCount;
	    $this->number   = (int) $count;
	    
	    if( $this->count )
	    {
	        $this->string   .= '_COUNT';
		}

	    if( $this->number !== 1 ) // 0 and > 1
	    {
	        $this->string   .= '_PLURAL';
	        return $this;
		}
		
		$this->string   .= '_SINGULAR';

		return $this;
	}

	public function getString()
	{
		return $this->string;
	}	
	
	public function __toString()
	{
	    if( $this->count )
	    {
	        return JText::sprintf( $this->string , $this->number );
		}
		
	    return JText::_( $this->string );
	}

}