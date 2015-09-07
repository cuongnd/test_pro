<?php
/**
 * @package		Foundry
 * @copyright	Copyright (C) 2012 StackIdeas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * Foundry is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

require_once( SOCIAL_FOUNDRY . '/joomla/compiler.php' );

class SocialCompiler {

	static $instance = null;

	public $resourceManifestFile;

	public function __construct()
	{
		$this->resourceManifestFile = SOCIAL_RESOURCES . '/default.json';
	}

	public static function getInstance()
	{
		if( is_null( self::$instance ) )
		{
			self::$instance	= new self();
		}

		return self::$instance;
	}	

	public function compile($minify=false) {

		$compiler = new FD31_FoundryCompiler();

		// Create a master manifest containing all the scripts
		$manifest = new stdClass();
		$manifest->adapter = 'EasySocial';
		$manifest->script = array();

		// Get a list of all the js files in the "scripts" folder
		jimport('joomla.filesystem.folder');
		$files = JFolder::files(SOCIAL_SCRIPTS, '.js$', true, true);

		// Go through each of the manifest files.
		foreach ($files as $file) {

			// Remove the absolute path from the file name
			$file = str_ireplace(SOCIAL_SCRIPTS . '/', '', $file);

			// Remove the .js extension from the file name.
			$file = str_ireplace('.js', '', $file);

			// Exclusion list
			if ($file=="easysocial.static" ||
				$file=="easysocial.static.min" ||
				$file=="easysocial.optimized" ||
				$file=="easysocial.optimized.min") continue;

			// Add to the master manifest
			$manifest->script[] = $file;
		}

		// Write the manifest to a file 
		$file     = SOCIAL_SCRIPTS . '/manifest.json';
		$contents = json_encode($manifest);
		$contents = str_ireplace('\\', '', $contents);
		$state    = JFile::write($file, $contents);

		// Set compiler options
		$options = array(
			"static"    => SOCIAL_SCRIPTS . '/easysocial.static',
			"optimized" => SOCIAL_SCRIPTS . '/easysocial.optimized',
			"resources" => SOCIAL_MEDIA   . '/resources/default',
			"minify"    => $minify
		);

		$compiler->exclude = array(
			"ui/draggable",
			"ui/sortable",
			"ui/droppable",
			"ui/datepicker",
			"ui/timepicker",
			"flot",
			"sparkline",
			"plupload",
			"redactor",
			"moment"
		);

		// Compiler scripts
		return $compiler->compile($file, $options);
	}

	public function getResources()
	{
		static $resource;

		if (!empty($resource)) return $resource;

		// Get manifest
		$manifest = $this->getResourcesManifest();

		// No manifest file found, use default js file.
		if (!$manifest) return $this->getDefaultResources();

		// Get current resource settings
		$settings = $this->getResourcesSettings();

		// Determine path based on the settings id
		$id   = $settings["id"];
		$path = SOCIAL_RESOURCES     . '/' . $id;
		$uri  = SOCIAL_RESOURCES_URI . '/' . $id;

		// Create resource object
		$resource = array(
			"id" => $id,
			"path" => $path . '.js',
			"uri"  => $uri  . '.js',
			"settings" => $settings
		);

		// Flag that determines if we can use this resource file
		$failed = false;

		// If the file hasn't been created
		$scriptFile   = $path . '.js';

		if (!JFile::exists($scriptFile)) {
			
			// Compile the script file on-the-fly.
			if ($this->compileResources($scriptFile)) {

				// Also save the settings into a json file
				$settingsFile = $path . '.settings.json';

				$jsonData 	= Foundry::json()->encode($settings);

				JFile::write( $settingsFile, $jsonData );

			// If unable to compile script
			} else {

				// Set flag to failed
				$failed = true;
			}
		}

		// If failed to compile resources
		if ($failed) {

			// Use default resource
			return $this->getDefaultResources();
		}

		return $resource;
	}

	public function getDefaultResources()
	{
		return array(
			"id"       => "default",
			"path"     => SOCIAL_RESOURCES     . '/default.js',
			"uri"      => SOCIAL_RESOURCES_URI . '/default.js',

			// TODO: Load from "default.settings.json"
			"settings" => null
		);
	}

	public function getResourcesManifest() {

		static $manifest;

		if (!empty($manifest)) return $manifest;

		$file = $this->resourceManifestFile;

		// Get list of dependencies
		if (JFile::exists($file)) {
			$data = JFile::read($file);
			$manifest = Foundry::json()->decode($data);
		} else {
			$manifest = false;
		}

		return $manifest;
	}

	public function getResourcesSettings() {

		$config = Foundry::config();
		$assets = Foundry::get('Assets');
		$themes = Foundry::get('Themes');
		$locations = $assets->locations();

		// Build a deterministic cache
		$settings = array(
			"language" => JFactory::getLanguage()->getTag(),
			"template" => array(
				"site"   => $config->get('theme.site' , 'wireframe'),
				"admin"  => $config->get('theme.admin', 'default')
			),
			"view" => array(),
			"modified" => filemtime($this->resourceManifestFile)
		);

		// Determine if there are template overrides
		if (JFolder::exists($locations['site_override'])) {
			$settings["template"]["site_override"]  = Foundry::call('Assets', 'getJoomlaTemplate', array('site'));
		}

		if (JFolder::exists($locations['admin_override'])) {
			$settings["template"]["admin_override"] = Foundry::call('Assets', 'getJoomlaTemplate', array('admin'));
		}

		// Get manifest
		$manifest = $this->getResourcesManifest();

		if(isset($manifest[0]->view) && $manifest[0]->view)
		{
			foreach ($manifest[0]->view as $view)
			{
				$original	= $view;
				$view		= 'themes:/' . $view;
				$path		= Foundry::resolve( $view . '.ejs' );

				// If the file still does not exist, we'll skip this
				if (!JFile::exists($path))
				{
					continue;
				}

				$settings["view"][] = array(
					"path"     => str_ireplace(JPATH_ROOT, '', $path),
					"modified" => filemtime($path)
				);
			}
		}

		// Build hash
		$settings["id"] = md5(serialize($settings));

		return $settings;
	}

	public function compileResources($file) {

		require_once(SOCIAL_FOUNDRY . '/joomla/compiler.php');

		$compiler = new FD31_FoundryCompiler();

		$manifest = $this->getResourcesManifest();
		$deps     = $compiler->getDependencies($manifest);

		$contents = $compiler->build('resources', $deps);

		$state = JFile::write($file, $contents);

		return $state;
	}

	public function purgeResources()
	{
        $files = JFolder::files(SOCIAL_RESOURCES, '.', true, true);

		foreach($files as $file) {
			if (strpos($file, 'default') !== false) continue;
			$state = JFile::delete( $file );
		}
	}
}