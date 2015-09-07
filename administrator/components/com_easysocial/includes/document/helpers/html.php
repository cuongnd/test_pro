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
 * HTML initialization here.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialDocumentHTML
{
	static $loaded = false;

	static $scriptsLoaded = false;

	static $stylesheetsLoaded = false;

	static $options = array();

	/**
	 * This loads a list of javascripts that are dependent throughout the whole component.
	 *
	 * @access	public
	 * @param	null
	 */
	public function init( $options = array() )
	{
		// @task: Only load when necessary and in html mode.
		if( self::$loaded )
		{
			return;
		}

		self::$options	= $options;
		
		$this->initScripts();
		$this->initStylesheets();


		self::$loaded	= true;
	}

	/**
	 * Initializes javascript on the head of the page.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function initScripts()
	{
		if( self::$scriptsLoaded )
		{
			return;
		}

		$config = Foundry::getInstance('Configuration');
		$config->attach();

		self::$scriptsLoaded = true;
	}

	/**
	 * Initializes all the stylesheet that needs to load on the page
	 *
	 * @since	1.0
	 * @access	public
	 * @return	
	 */
	public function initStylesheets()
	{
		static $profiles	= array();

		if( isset( self::$options[ 'processStylesheets' ] ) && self::$options[ 'processStylesheets' ] == false )
		{
			self::$loaded 	= true;
			return;
		}

		if( self::$stylesheetsLoaded )
		{
			return;
		}

		$app	    = JFactory::getApplication();
		$config 	= Foundry::config();

		// Build theme styles
		$location	= $app->isAdmin() ? 'admin' : 'site';
		$theme		= strtolower( $config->get( 'theme.' . $location ) );
		$doc 		= JFactory::getDocument();

		if( $location == 'site' )
		{
			$profile 	= Foundry::user()->getProfile();

			if( $profile && !isset( $profiles[ $profile->id ] ) )
			{
				$params 	= $profile->getParams();
				$override 	= $params->get( 'theme' );

				if( $override )
				{
					$theme	= $override;
				}

				$profiles[ $profile->id ]	= $theme;
			}

			if( $profile && isset( $profiles[ $profile->id ] ) )
			{
				$theme 	= $profiles[ $profile->id ];
			}
		}
		
		// Determine compiler mode
		$compilerMode 		= $config->get( 'theme.compiler.mode' );
		$environmentMode	= $config->get( 'general.environment' );

		// If environment mode is in development, we always force "cache" for compilation mode.
		if( $environmentMode == 'development' )
		{
			$compilerMode	= 'cache';
		}

		// If compilation mode is not off, we need to compile the less files into css files first.
		if( $compilerMode == 'cache' || $compilerMode == 'force' )
		{
			Foundry::call( 'Themes' , 'attachStyles' , array($location, $theme) );	
		}

		// Attach the css files to the head
		$initialAbsolutePath		= $location == 'admin' ? JPATH_ROOT . '/administrator' : JPATH_ROOT;
		$initialURI					= $location == 'admin' ? rtrim( JURI::root() , '/' ) . '/administrator' : rtrim( JURI::root() , '/' );

		// Get the joomla template
		$joomlaTemplate 			= JFactory::getApplication()->getTemplate();
		$templateOverrideExists		= false;

		// Check if there is any css files on the template override
		$path	= $initialAbsolutePath . '/templates/' . $joomlaTemplate . '/html/com_easysocial/styles';
		$uri 	= $initialURI . '/templates/' . $joomlaTemplate . '/html/com_easysocial/styles';

		$files	= array( $path . '/style.css' => $uri . '/style.css' , $path . '/style1.css' => $uri . '/style1.css' );
		
		jimport( 'joomla.filesystem.file' );

		foreach( $files as $location => $uri )
		{
			if( JFile::exists( $location ) )
			{
				$templateOverrideExists		= true;
				$doc->addStyleSheet( $uri );
			}
		}

		// If the template override does not exist, we try to load our own template's css file
		if( !$templateOverrideExists )
		{
			$uri 	= $initialURI . '/components/com_easysocial/themes/' . $theme . '/styles';

			$files	= array( $uri . '/style.css' , $uri . '/style1.css' );

			foreach( $files as $file )
			{
				$doc->addStyleSheet( $file );
			}
		}

		self::$stylesheetsLoaded = true;
	}
}
