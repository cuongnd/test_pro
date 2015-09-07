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

Foundry::import( 'admin:/includes/less/parsers/lessc.inc' );

class SocialLess extends SocialLessc
{
	public $force 					= false;
	public $allowTemplateOverride	= true;

	// Stores constants for compilation modes
	const COMPILE_FORCE 	= 'force';
	const COMPILE_OFF 		= 'off';
	const COMPILE_CACHE 	= 'cache';

	public static function factory()
	{
		$obj 	= new self();

		return $obj;
	}

	/**
	 * Clears the cache files from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function clear()
	{
		$folders 	= array( 
								JPATH_ROOT . '/administrator/components/com_easysocial/themes',
								JPATH_ROOT . '/components/com_easysocial/themes',
								JPATH_ROOT . '/modules',
								JPATH_ROOT . '/templates',
								JPATH_ROOT . '/plugins'
						);
		$count 		= 0;

		foreach( $folders as $folder )
		{
			$cacheFiles 	= JFolder::files( $folder , 'style.less.cache' , true , true );

			if( $cacheFiles )
			{
				foreach( $cacheFiles as $cacheFile )
				{
					JFile::delete( $cacheFile );

					$count 	+= 1;
				}
			}
		}
		
		return $count;
	}

	/**
	 * Compiles all the stylesheet through CLI
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of the addon that we would like to compile. (E.g: module/template)
	 * @param	string	The location of the addon that we would like to compile (E.g: admin/site )
	 * @param	string	The name of the addon that we would like to compile
	 * @return	
	 */
	public function compileFromCLI( $type , $location , $item = array() )
	{
		$items	= array();

		// If provided item is a string, we convert it to an array
		$items 	= is_string( $item ) ? array( $item ) : $items;

		// Construct the initial path
		$path 	= $location == 'site' ? JPATH_ROOT : JPATH_ROOT . '/administrator';

		// Get a list of items that we should be compiling
		if( empty( $items ) )
		{
			jimport( 'joomla.filesystem.folder' );
			if( $type == 'templates' )
			{
				// We add the template prefix now
				$path 	= $path . '/components/com_easysocial/themes/';

				$items 	= JFolder::folders( $path );
			}
	
			if( $type == 'modules' )
			{
				$path 	= $path . '/modules';

				$items 	= JFolder::folders( $path );
			}
		}

		// If there's nothing to compile, skip this.
		if( empty( $items ) )
		{
			return false;
		}

		// Go through each of the items now so that we can compile them.
		foreach( $items as $item )
		{
			$method 	= 'compile' . ucfirst( $type ) . 'FromCLI';

			$this->$method( $location , $item ); 
		}
	}

	public function compileCLI( $in , $out , $rootURI , $settings )
	{
		// If the less file doesn't exist, ignore this
		if( !JFile::exists( $in ) )
		{
			return;
		}

		// Default settings
		$defaultSettings = array(
									"importDir" => array(
										"media"   => SOCIAL_MEDIA . '/styles',
										"foundry" => SOCIAL_FOUNDRY . '/styles'
									),

									"variables" => array(
										"root"     => "'" . 'file://' . JPATH_ROOT . "'",
										"root_uri" => "'" . $rootURI . "'"
									)
							);

		// Common locations
		$locations = array( "admin", "admin_base","site","site_base","media","foundry" );

		// This creates a pair of variables for each location,
		// one of itself, one of the uri counterpart.
		foreach ($locations as $type )
		{
			if( $type == 'admin' || $type == 'admin_base' )
			{
				$relativeURI	= $rootURI . 'administrator/components/com_easysocial/themes/default/styles';
				$absolutePath 	= 'file://' . JPATH_ROOT . '/administrator/components/com_easysocial/themes/default/styles';
			}

			if( $type == 'site' || $type == 'site_base' )
			{
				$relativeURI	= '.';
				$absolutePath 	= 'file://' . JPATH_ROOT . '/components/com_easysocial/themes/wireframe/styles';
			}

			if( $type == 'media' )
			{
				$relativeURI	= $rootURI . 'media/com_easysocial/styles';
				$absolutePath	= 'file://' . JPATH_ROOT . '/media/com_easysocial/styles';
			}

			if( $type == 'foundry' )
			{
				$relativeURI	= $rootURI . 'media/foundry/3.0/styles';
				$absolutePath	= 'file://' . JPATH_ROOT . '/media/foundry/3.0/styles';
			}

			$defaultSettings["variables"][$type]          = "'" . $absolutePath . "'";
			$defaultSettings["variables"][$type . '_uri'] = "'" . $relativeURI . "'";
		}

		// Mixin settings
		$settings = array_merge_recursive($settings, $defaultSettings);

		$this->setImportDir($settings["importDir"]);

		$this->setVariables($settings["variables"]);

		// Compile stylesheet
		try
		{
			$this->cachedCompileFile( $in , $out , true );
		}
		catch (Exception $ex)
		{
			return false;
		}

		return true;
	}

	public function compileTemplatesFromCLI( $location , $name )
	{
		// All themes would be stored in this location
		$path 	= $location == 'site' ? JPATH_ROOT : JPATH_ROOT . '/administrator';
		$path 	= $path . '/components/com_easysocial/themes/' . strtolower( $name ) . '/styles';

		// Prepare items to compile
		$in 	= $path . '/style.less';
		$out 	= $path . '/style.css';

		// Build settings
		$settings		= array( "importDir" => array( dirname( $in ) ) );
		$rootURI 		= $location == 'admin' ? './../../../../../../' : './../../../../../';

		return $this->compileCLI( $in , $out , $rootURI , $settings );
	}

	public function compileModulesFromCLI( $location , $name )
	{
		// All themes would be stored in this location
		$path 	= $location == 'site' ? JPATH_ROOT : JPATH_ROOT . '/administrator';
		$path 	= $path . '/modules/' . strtolower( $name ) . '/styles';

		// Prepare items to compile
		$in 	= $path . '/style.less';
		$out 	= $path . '/style.css';

		// If incoming file does not exist, stop.
		$exists 	= JFile::exists( $in );

		// Add the wireframe theme so modules can seek for files in this folder
		$site 		= SOCIAL_SITE_THEMES . '/wireframe/styles';
		$site_base	= SOCIAL_SITE_THEMES . '/wireframe/styles';

		// modules
		// components/themes
		$importDir = array( $module , $site , $site_base );

		// Used to build relative uris
		$moduleURI 	= $location == 'admin' ? './../../../../' : './../../../';

		// Variables
		$variables = array();
		$variables["module"]     = "'file://".$path."'";
		$variables["module_uri"] = "'". $moduleURI ."'";

		// Build settings
		$settings = array(
			"importDir" => $importDir,
			"variables" => $variables,
			'module' => true
		);

		return $this->compileCLI( $in , $out , $moduleURI , $settings );
	}



	/**
	 * Compiles a less stylesheet into a css file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The path to the less input file
	 * @param	string		The path to the css output file
	 * @return	
	 */
	public function compileStylesheet( $in , $out , $settings=array() )
	{
		$config		= Foundry::config();
		$assets		= Foundry::get('Assets');
		$info   	= Foundry::info();

		// Prepare result object
		$result				= new stdClass();
		$result->in     	= $in;
		$result->in_uri  	= $assets->toUri($in);
		$result->out     	= $out;
		$result->out_uri 	= $assets->toUri($out);
		$result->cache   	= null;
		$result->failed  	= false;

		// If incoming file does not exist, stop.
		$exists 	= JFile::exists( $result->in );

		if( !$exists )
		{
			$result->failed		= true;
			$result->message 	= JText::_( 'Could not open up main stylesheet file <b><u>style.less</u></b>' );

			return $result;
		}

		// Force compile when target file does not exist.
		// This prevents less from failing to compile when
		// the css file was deleted but the cache file still retains.
		$exists 	= JFile::exists( $result->out );

		if( !$exists || $this->compileMode == self::COMPILE_FORCE )
		{
			$this->force = true;
		}

		// Used to build relative uris
		$out_folder 	= dirname( $result->out_uri );

		// Used to ensure uris are absolute
		$out_ext 		= '';

		if( $config->get( 'theme.compiler.use_absolute_uri' ) )
		{
			$out_ext	= $out_folder;
		}

		// Default settings
		$defaultSettings = array(

			"importDir" => array(
				"media"   => SOCIAL_MEDIA . '/styles',
				"foundry" => SOCIAL_FOUNDRY . '/styles'
			),

			"variables" => array(
				"root"     => "'" . $assets->fileUri("root") . "'",
				"root_uri" => "'" . $out_ext . $assets->relativeUri($assets->uri('root'), $out_folder) . "'"
			)
		);

		// Common locations
		$locations = array( "admin", "admin_base", "site", "site_base", "media","foundry" );

		// This creates a pair of variables for each location,
		// one of itself, one of the uri counterpart.
		foreach ($locations as $location)
		{
			$defaultSettings["variables"][$location]          = "'" . $assets->fileUri($location, 'styles') . "'";
			$defaultSettings["variables"][$location . '_uri'] = "'" . $out_ext . $assets->relativeUri( $assets->uri($location, 'styles'), $out_folder) . "'";
		}

		// Mixin settings
		$settings = array_merge_recursive($settings, $defaultSettings);
 
		$this->setImportDir($settings["importDir"]);

		$this->setVariables($settings["variables"]);

		// Compile stylesheet
		try
		{
			$result->cache = $this->cachedCompileFile( $in , $out , $this->force);

			// If there are more than 1 css files generated we need to tell the caller that there are multiple files.
			if( count( $result->cache['blocks' ] ) > 1 )
			{
				$blocks 	= $result->cache[ 'blocks' ];
				$i 			= 0;
				$tmpOut 	= $result->out;
				$tmpOutUri	= $result->out_uri;

				$result->out 		= array();
				$result->out_uri	= array();

				foreach( $blocks as $block )
				{
					$pathUri	= dirname( $tmpOutUri );
					$path 		= dirname( $tmpOut );

					$file 		= basename( $tmpOut );
					$file 		= $i != 0 ? str_ireplace( '.css' , $i . '.css' , $file ) : $file;

					$result->out[]		= $path . '/' . $file;
					$result->out_uri[]	= $pathUri . '/' . $file;

					$i++;
				}
			}
		}
		catch (Exception $ex)
		{
			$result->failed = true;
			$result->message = 'compiler error: ' . $ex->getMessage();

			$info->set($result->message, SOCIAL_MSG_ERROR);
		}

		return $result;
	}


	/**
	 * Compiles the front end less files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The theme name that is currently used
	 * @return	
	 */
	public function compileSiteStylesheet($theme_name)
	{
		// Not using this because it only returns the current theme
		$site	= SOCIAL_SITE_THEMES . '/' . strtolower($theme_name) . '/styles';

		$in		= $site . '/style.less';
		$out	= $site . '/style.css';

		$importDir 				= array( $site );

		// Build settings
		$settings				= array( "importDir" => $importDir );

		// Compile
		$result					= $this->compileStylesheet( $in , $out , $settings );

		return $result;
	}

	/**
	 * Compiles the admin's less files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function compileAdminStylesheet($theme_name)
	{
		// Not using this because it only returns the current theme
		$admin = SOCIAL_ADMIN_THEMES . '/' . strtolower($theme_name) . '/styles';

		$in  = $admin . '/style.less';
		$out = $admin . '/style.css';

		$importDir = array( $admin );

		// Build settings
		$settings = array( "importDir" => $importDir );

		// Compile
		$result = $this->compileStylesheet($in, $out, $settings);

		return $result;
	}

	/**
	 * Compiles the module stylesheet on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The module's name
	 * @return	
	 */
	public function compileModuleStylesheet( $moduleName )
	{
		$app    = JFactory::getApplication();
		$assets = Foundry::get('Assets');
		$config = Foundry::config();

		$module 	= JPATH_ROOT  . '/modules/' . $moduleName . '/styles';
		$module     = $assets->path('module', $moduleName) . '/styles';

		$theme_name		= $config->get( 'theme.site' );
		
		$site          = SOCIAL_SITE_THEMES . '/' . rtrim( strtolower($theme_name) , '/' ) . '/styles';
		$site_base     = $assets->path('site_base', 'styles');

		$in  = $module . '/style.less';
		$out = $module . '/style.css';

		// modules
		// components/themes
		$importDir = array( $module , $site , $site_base );

		// Used to build relative uris
		$out_folder = dirname($assets->toUri($out));

		// Used to ensure uris are absolute
		$out_ext = ($config->get('theme.compiler.use_absolute_uri')) ? $out_folder : "";

		// Variables
		$variables = array();
		$variables["module"]     = "'file://".$module."'";
		$variables["module_uri"] = "'".$out_ext.$assets->relativeUri( JPATH_ROOT , dirname( $module ) )."'";

		// Build settings
		$settings = array(
			"importDir" => $importDir,
			"variables" => $variables,
			'module' => true
		);

		// Compile
		$result = $this->compileStylesheet($in, $out, $settings);

		return $result;
	}

	public function getCachePath($in)
	{
		return dirname($in) . '/' . basename($in) . '.cache';
	}

	public function getExistingCacheStructure($in)
	{
		// Get cache file for the provided source
		$cachePath = $this->getCachePath($in);

		// If cache file exists, retrieve cache structure.
		if (JFile::exists($cachePath))
		{
			$cacheContent	= JFile::read($cachePath);
			$cacheStructure = unserialize($cacheContent);

			return $cacheStructure;
		}
		
		return null;
	}

	/**
	 * Loads up any .css file that it finds in the provided path.
	 * Used when theme.compile.mode is off
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getStaticFiles( $paths )
	{
		$assets 	= Foundry::get( 'Assets' );

		// Ensure that it's an array
		$paths 		= Foundry::makeArray( $paths );

		// Build the standard return result
		$result 			= new stdClass();
		$result->out		= array();
		$result->out_uri 	= array();
		$result->failed 	= false;

		jimport( 'joomla.filesystem.folder' );

		foreach( $paths as $path )
		{
			if( !JFolder::exists( $path ) )
			{
				continue;
			}

			// Get a list of .css files in this folder
			$files 		= JFolder::files( $path , '.css' , false , true );

			if( $files )
			{
				foreach( $files as $file )
				{
					$result->out[]		= $file;
					$result->out_uri[]	= $assets->toUri( $file );
				}
			}
		}

		return $result;
	}

	/**
	 * Parses all the files in the queue and return a valid css file.
	 *
	 * @access	public
	 * @param	Array		An array of options. ( 'destination' => '/path/to/css/file' )
	 * @return	string		The compiled output.
	 */
	public function cachedCompileFile( $in , $out , $force = false )
	{
		// Check if source file exists
		if (!JFile::exists($in))
		{
			$this->throwError('compiler error: source file does not exist.');
			return null;
		}

		// Get the current cache path
		$cachePath		= $this->getCachePath($in);

		// Try to get existing cache structure
		$cacheStructure = $this->getExistingCacheStructure($in);

		// If it doesn't exist, just pass in the source path
		if( is_null($cacheStructure) )
		{
			$cacheStructure = $in;
		}

		// Compile stylesheet
		$newCacheStructure	= $this->cachedCompile( $cacheStructure , $force );

		if( !is_array($cacheStructure) || $newCacheStructure['updated'] > $cacheStructure['updated'])
		{
			$cacheContent = serialize($newCacheStructure);

			// Write cache file & stylesheet file.
			JFile::write($cachePath, $cacheContent);

			// Determines how many blocks there is
			$blocks 		= $newCacheStructure[ 'blocks' ];

			$i 	= 0;
			foreach( $blocks as $block )
			{
				if( $i != 0 )
				{
					$out 	= str_ireplace( '.css' , $i . '.css' , $out );
				}

				JFile::write( $out , $block );

				$i++;
			}
		}

		// Return cache structure
		return $newCacheStructure;
	}
}
