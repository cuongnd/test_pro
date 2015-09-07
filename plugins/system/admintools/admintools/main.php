<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.plugin');

// If JSON functions don't exist, load our compatibility layer
if ((!function_exists('json_encode')) || (!function_exists('json_decode')))
{
	include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_admintools' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'jsonlib.php';
}

/**
 * This class acts as an aggregator of the two sub-plugins used by the core and professional versions of the component
 *
 * @author nicholas
 *
 */
class plgSystemAdmintools extends JPlugin
{
	private $plugins = array();

	public function __construct(& $subject, $config = array())
	{
		// Load the core and pro sub-plugins
		$basedir = dirname(__FILE__);
		$core = "$basedir/core.php";
		$pro = "$basedir/pro.php";

		if (file_exists($core))
		{
			require_once $core;
			$this->plugins[] = new plgSystemAdmintoolsCore($subject, $config);
		}

		if (file_exists($pro))
		{
			require_once $pro;
			$this->plugins[] = new plgSystemAdmintoolsPro($subject, $config);
		}
	}

	/**
	 * Hooks to the onAfterInitialize system event, the first time in the
	 * Joomla! page load workflow which fires a plug-in event
	 */
	public function onAfterInitialise()
	{
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				$plugin->onAfterInitialise();
			}
		}
	}

	public function onAfterRender()
	{
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				$plugin->onAfterRender();
			}
		}
	}

	public function onAfterDispatch()
	{
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				$plugin->onAfterDispatch();
			}
		}
	}

	public function onLoginFailure($response)
	{
		$m = __METHOD__;
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				if (method_exists($plugin, $m))
				{
					$plugin->$m($response);
				}
			}
		}
	}

	public function onLoginUser($user, $options)
	{
		$m = __METHOD__;
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				if (method_exists($plugin, $m))
				{
					$plugin->$m($user, $options);
				}
			}
		}
	}

	public function onUserAuthorisationFailure($authorisation)
	{
		$m = __METHOD__;
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				if (method_exists($plugin, $m))
				{
					$plugin->$m($authorisation);
				}
			}
		}
	}

	public function onUserLogin($user, $options)
	{
		$m = __METHOD__;
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				if (method_exists($plugin, $m))
				{
					$plugin->$m($user, $options);
				}
			}
		}
	}

	public function onUserLoginFailure($response)
	{
		$m = __METHOD__;
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				if (method_exists($plugin, $m))
				{
					$plugin->$m($response);
				}
			}
		}
	}

	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		$m = __METHOD__;
		if (!empty($this->plugins))
		{
			foreach ($this->plugins as $plugin)
			{
				if (method_exists($plugin, $m))
				{
					$plugin->$m($user, $isnew, $success, $msg);
				}
			}
		}
	}
}