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

require_once( SOCIAL_LIB . '/template/template.php' );

class SocialThemes extends SocialTemplate
{
	// Static has higher precendence of instance
	public static $_inlineScript	 = true;
	public static $_inlineStylesheet = true;

	public $inlineScript     = true;
	public $inlineStylesheet = true;

	public $mode = 'php';

	public function __construct()
	{
		// Pre-render all modules
		parent::__construct();
	}

	/**
	 * This is the factory method to ensure that this class is always created all the time.
	 * Usage: Foundry::get( 'Template' );
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public static function factory()
	{
		return new self();
	}

	/**
	 * Resolve a given POSIX path.
	 *
	 * <code>
	 * <?php
	 * // This would translate to administrator/components/com_easysocial/themes/CURRENT_THEME/users/default.php
	 * Foundry::resolve( 'themes:/admin/users/default' );
	 *
	 * // This would translate to components/com_easysocial/themes/CURRENT_THEME/dashboard/default.php
	 * Foundry::resolve( 'themes:/site/dashboard/default' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The posix path to lookup for.
	 * @return	string		The translated path
	 */
	public static function resolve( $namespace )
	{
		static $profiles	= array();

		$path		= '';
		$parts 		= explode( '/' , $namespace );
		$config 	= Foundry::config();

		// Get and remove the location from parts.
		$location	= array_shift( $parts );

		// Get the current theme.
		$theme 		= $config->get( 'theme.' . $location, 'wireframe' );


		if( $location == 'apps' || $location == 'fields' )
		{
			// @TODO: For apps, we need a proper way to detect the apps theme configuration.
			$theme 		= 'default';

			$path 		= $location == 'apps' ? SOCIAL_APPS : SOCIAL_FIELDS;

			// Get and remove the group.
			$group		= array_shift( $parts );

			// Get and remove the element.
			$element	= array_shift( $parts );

			$path 		= $path . '/' . $group . '/' . $element . '/themes/' . $theme . '/' . implode( '/' , $parts );

			return $path;
		}

		$default = 'default';

		// Get the absolute path of the initial location
		if( $location == 'admin' )
		{
			$path 		= SOCIAL_ADMIN;
			$default	= $config->get( 'theme.admin_base' );
		}

		if( $location == 'site' || $location == 'emails' )
		{
			// Retrieve the user's theme.
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

			$path 		= SOCIAL_SITE;
			$default	= $config->get( 'theme.site_base' );
		}
		
		jimport( 'joomla.filesystem.file' );

		// Determine if there's a joomla template override.
		$currentTemplate 	= Foundry::assets()->getJoomlaTemplate();
		$override 			= JPATH_ROOT . '/templates/' . $currentTemplate . '/html/com_easysocial/' . implode( '/' , $parts );

		if( JFile::exists( $override ) )
		{
			return $override;
		}

		// Test if the file really exists
		$file 	= $path . '/themes/' . $theme . '/' . implode( '/' , $parts );

		// If the file doesn't exist, always revert to the original base theme
		if( !JFile::exists( $file ) ) 
		{
			$file 	= $path . '/themes/' . $default . '/' . implode( '/' , $parts );
		}

		return $file;
	}

	/**
	 * Static method to attach theme stuffs into the page heading.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function attachStyles($location, $name)
	{
		$document = JFactory::getDocument();
		$config   = Foundry::config();
		$less     = Foundry::get( 'Less' );
		$info     = Foundry::info();

		$configuration = Foundry::getInstance('Configuration');

		if ($configuration->environment=='development')
		{
			$less->compileMode = 'cache';
		}
		else
		{
			$less->compileMode = $config->get('theme.compiler.mode');
		}

		// If compile mode is off, we shouldn't do anything here
		if( $less->compileMode == 'off' )
		{
			return;
		}

		$less->allowTemplateOverride = $config->get('theme.compiler.allow_template_override');

		switch ($location)
		{
			case "admin":
				$result = $less->compileAdminStylesheet($name);
				break;

			case "site":
				$result = $less->compileSiteStylesheet($name);
				break;

			case "module":
				$result = $less->compileModuleStylesheet($name);
				break;

			default:
				return;
		}

		// If there is no result back, we should throw an error here.
		if( !isset($result) )
		{
			$message 	= JText::sprintf( 'Could not compile stylesheet for <b><u>%1s</u></b>' , $name );
			$info->set( $message , SOCIAL_MSG_ERROR );
			return;
		}


		// Ensure that the output files are in an array.
		$out 	= $result->out;
		$out 	= Foundry::makeArray( $out );

		// Always ensure that it's in an array form
		$result->out_uri 	= Foundry::makeArray( $result->out_uri );

		for( $i = 0; $i < count( $out ); $i++ )
		{
			$outputFile = $out[ $i ];
			$exists 	= JFile::exists( $outputFile );

			// Test if the output file exists
			if( $exists )
			{
				if( isset( $result->failed ) && $result->failed )
				{
					$info->set('Could not compile stylesheet for ' . $name , SOCIAL_MSG_ERROR);
				}
			}
			else
			{
				// Try to use the failsafe file.
				$exists 	= JFile::exists( $result->failsafe );

				if( $exists )
				{
					if( isset( $result->failed ) && $result->failed )
					{
						$info->set('Could not compile stylesheet for ' . $name , SOCIAL_MSG_ERROR);
					}
					else
					{
						$info->set('Could not locate compiled stylesheet for ' . $name , SOCIAL_MSG_ERROR);
					}
				}
				else
				{
					$info->set('Unable to load stylesheet for ' . $name , SOCIAL_MSG_ERROR);
				}
			}
		}



		return $result;
	}

	/**
	 * Outputs the data from a template file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The template file name.
	 * @param	Array		An array of arguments.
	 * @param	string		If required, caller can override the document type.
	 *
	 * @return	string		The output of the theme file.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function output( $tpl = null , $args = null )
	{
		// Import joomla's filesystem library.
		jimport( 'joomla.filesystem.file' );

		// Try to get the template data.
		$template	= $this->getTemplate( $tpl );

		// Template
		$this->file		= $template->file;

		$output			= $this->parse( $args );

		// Stylesheet
		if( JFile::exists( $template->stylesheet ) )
		{
			$stylesheet			= Foundry::get('Stylesheet');
			$stylesheet->file	= $template->stylesheet;
			$stylesheet->vars	= $this->vars;

			if( !self::$_inlineStylesheet || !$this->inlineStylesheet )
			{
				$stylesheet->attach();
			}
			else
			{
				$stylesheet->$styleTag = true;
				$output .= $stylesheet->parse( $args );
			}
		}

		// Script
		if( JFile::exists( $template->script ) )
		{
			$script			= Foundry::get('Script');
			$script->file	= $template->script;
			$script->vars	= $this->vars;

			if( !self::$_inlineScript || !$this->inlineScript )
			{
				$script->attach();
			}
			else
			{
				$script->scriptTag	= true;
				$output .= $script->parse( $args );
			}
		}

		return $output;
	}

	public function json_encode( $value )
	{
		return Foundry::json()->encode( $value );
	}

	public function json_decode( $value )
	{
		return Foundry::json()->decode( $value );
	}

	/*
	 * Returns a JSON encoded string for the current theme request.
	 *
	 * @param	null
	 * @return	string	JSON encoded string.
	 */
	public function toJSON()
	{
		return $this->json_encode( $this->vars );
	}

	/**
	 * Get's the current URI for callback
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCallback()
	{
		return FRoute::current( true );
	}

	/**
	 * Renders the widget items
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function render()
	{
		$args 		= func_get_args();

		// Get the type of the widget from the first parameter.
		$type 		= array_shift( $args );
		$method 	= 'render' . ucfirst( $type );

		if( !method_exists( $this , $method ) )
		{
			return;
		}

		return call_user_func_array( array( $this , $method ) , $args );
	}

	/**
	 * Renders module output on a theme file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function renderModule( $position , $wrapper = null , $attributes = array() , $content = null )
	{
		$fields		= Foundry::fields();

		$doc		= JFactory::getDocument();
		$tmp 		= $doc->getType();

		// If this is loaded from an ajax call, we need to reset it to html
		if( $doc->getType() != 'html' )
		{
			$doc->setType( 'html' );
		}

		// For Joomla 2.5, we need to include the module
		jimport( 'joomla.application.module.helper' );

		$renderer	= $doc->loadRenderer( 'module' );
		$contents	= '';
		$modules	= JModuleHelper::getModules( $position );

		// Once it is rendered, we need to reset the type back
		if( $tmp != 'html' )
		{
			$doc->setType( $tmp );
		}

		// If there's nothing to load, just skip this
		if( !$modules )
		{
			// We cannot return false here otherwise the theme will echo as 0.
			return;
		}

		$output 	= array();

		// Use a standard module style if no style is provided
		if( !isset( $attributes[ 'style' ] ) )
		{
			$attributes[ 'style' ]	= 'xhtml';
		}

		foreach( $modules as $module )
		{
			$theme 	= Foundry::themes();

			$theme->set( 'position' , $position );
			$theme->set( 'output'	, $renderer->render( $module , $attributes , $content ) );

			$contents		= $theme->output( 'site/structure/modules' );

			// Determines if we need to add an additional wrapper to surround it
			if( !is_null( $wrapper ) )
			{
				$theme		= Foundry::themes();
				$registry	= Foundry::registry( $module->params );

				$theme->set( 'module'	, $module );
				$theme->set( 'params'	, $registry );
				$theme->set( 'contents' , $contents );
				$contents	= $theme->output( $wrapper );
			}

			$output[]	= $contents;
		}

		$output 	= implode( '' , $output );

		return $output;
	}

	/**
	 * Renders custom field output on a theme file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function renderFields( $group , $view , $position )
	{
		$fields = Foundry::fields();
		$args 	= func_get_args();

		$args	= isset( $args[ 3 ] ) ? $args[ 3 ] : array();

		return $fields->renderWidgets( $group , $view , $position , $args );
	}

	/**
	 * Renders widget output on a theme file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function renderWidgets( $group , $view , $position )
	{
		$apps 	= Foundry::apps();
		$args 	= func_get_args();

		$args	= isset( $args[ 3 ] ) ? $args[ 3 ] : array();

		return $apps->renderWidgets( $group , $view , $position , $args );
	}
}
