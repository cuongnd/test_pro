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

jimport( 'joomla.filesystem.file' );

class SocialModules  
{
	/**
	 * The name of the module.
	 * @var	string
	 */
	private $name 	= '';

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function factory( $name )
	{
		$instance 	= new self( $name );

		return $instance;
	}

	public function loadComponentScripts()
	{
		Foundry::document()->initScripts();
	}

	public function loadComponentStylesheets()
	{
		Foundry::document()->initStylesheets();
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function __construct( $name )
	{
		$this->name 	= $name;
	}

	/**
	 * Initializes files that are required by the module
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function addDependency( $dependencies = array() )
	{
		$dependencies	= Foundry::makeArray( $dependencies );

		if( !$dependencies )
		{
			return false;
		}

		// Determine what dependencies are required
		foreach( $dependencies as $dependency )
		{
			if( !method_exists( $this , $dependency ) )
			{
				continue;
			}

			$this->$dependency();
		}
	}

	/**
	 * Start of the module
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function start()
	{

	}

	/**
	 * Sugar method to allow module to easily attach script files to the document header
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function loadScript( $filename )
	{
		// Construct the path to the script file
		$file	= JPATH_ROOT . '/modules/' . $this->name . '/scripts/' . $filename;

		jimport( 'joomla.filesystem.file' );

		if( JFile::exists( $file ) )
		{
			$file 	= rtrim( JURI::root() , '/' ) . '/modules/' . $this->name . '/scripts/' . $filename;
			$doc 	= JFactory::getDocument();
			$doc->addScript( $file );

			return true;
		}

		return false;
	}

	/**
	 * Builds the css for the module
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function css()
	{
		// Load our themes helper
		$result 	= Foundry::call( 'Themes' , 'attachStyles' , array( 'module' , $this->name ) );

		// Check for template overrides here
		$template 	= JFactory::getApplication()->getTemplate();
		$doc 		= JFactory::getDocument();

		$path 		= JPATH_ROOT . '/templates/' . $template . '/html/' . $this->name . '/styles';
		$uri 		= rtrim( JURI::root() , '/' ) . '/templates/' . $template . '/html/' . $this->name . '/styles';

		jimport( 'joomla.filesystem.file' );

		// Test for css overrides
		if( JFile::exists( $path . '/style.css' ) )
		{
			$doc->addStyleSheet( $uri . '/style.css' );
		}

		// Test if the normal css file exists
		$path 		= JPATH_ROOT . '/modules/' . $this->name . '/styles';
		$uri 		= rtrim( JURI::root() , '/' ) . '/modules/' . $this->name . '/styles';

		if( JFile::exists( $path . '/style.css' ) )
		{
			$doc->addStyleSheet( $uri . '/style.css' );
		}
	}

	/**
	 * Resolves a namespace path
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public static function resolve( $name )
	{
		$path 	= JPATH_ROOT . '/modules/' . $name;
	
		return $path;
	}

	/**
	 * Load helpers for the module
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function html()
	{
		$theme 	= Foundry::themes();
		$args 	= func_get_args();

		return call_user_func_array( array( $theme , 'html' ) , $args );
	}
}
