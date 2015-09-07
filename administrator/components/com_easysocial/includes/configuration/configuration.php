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

require_once( SOCIAL_FOUNDRY_CONFIGURATION );

class SocialConfiguration extends FD31_FoundryComponentConfiguration {

	static $attached = false;
	static $instance = null;

	public function __construct()
	{
		$config = Foundry::config();

		$this->fullName    = "EasySocial";
		$this->shortName   = "es";
		$this->environment = $config->get('general.environment');
		$this->mode        = $config->get('general.mode');
		$this->version     = Foundry::getLocalVersion();
		$this->baseUrl     = Foundry::getBaseUrl();
		$this->token       = Foundry::token();

		parent::__construct();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance	= new self();
		}

		return self::$instance;
	}

	public function update()
	{
		// We need to call parent's update method first
		// because they will automatically check for
		// url overrides, e.g. es_env, es_mode.
		parent::update();

		switch ($this->environment) {

			case 'static':
			default:
				$this->scripts = array(
					'easysocial.static'
				);
				break;

			case 'optimized':
				$this->scripts = array(
					'easysocial.optimized'
				);
				break;

			case 'development':
				$this->scripts = array(
					'easysocial'
				);
				break;
		}
	}

	public function attach()
	{
		if (self::$attached) return;

		parent::attach();

		// Get resources
		$compiler = Foundry::getInstance('Compiler');
		$resource = $compiler->getResources();
		
		// Attach resources
		if (!empty($resource)) {
		
			$scriptTag = $this->createScriptTag($resource["uri"]);

			$document = JFactory::getDocument();
			$document->addCustomTag($scriptTag);
		}
		
		self::$attached = true;		
	}
}