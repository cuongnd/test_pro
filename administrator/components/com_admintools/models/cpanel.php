<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 * @version   $Id$
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

/**
 * The Control Panel model
 *
 */
class AdmintoolsModelCpanels extends F0FModel
{
	/**
	 * Constructor; dummy for now
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function getPluginID()
	{
		static $id = null;

		if (empty($id))
		{
			$db = $this->getDBO();

			$query = $db->getQuery(true)
				->select($db->qn('id'))
				->from($db->qn('#__extensions'))
				->where($db->qn('enabled') . ' >= ' . $db->quote('1'))
				->where($db->qn('folder') . ' = ' . $db->quote('system'))
				->where($db->qn('element') . ' = ' . $db->quote('admintools'))
				->where($db->qn('type') . ' = ' . $db->quote('plugin'))
				->order($db->qn('ordering') . ' ASC');
			$db->setQuery($query);
			$id = $db->loadResult();
		}

		return $id;
	}

	/**
	 * Makes sure our system plugin is really the very first system plugin to execute
	 */
	public function reorderPlugin()
	{
		// Get our plugin's ID
		$id = $this->getPluginID();

		// Get a list of ordering values per ID
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select(array(
				$db->qn('id'),
				$db->qn('ordering'),
			))
			->from($db->qn('#__extensions'))
			->where($db->qn('type') . ' = ' . $db->q('plugin'))
			->where($db->qn('folder') . ' = ' . $db->q('system'))
			->order($db->qn('ordering') . ' ASC');
		$db->setQuery($query);
		$orderingPerId = $db->loadAssocList('id', 'ordering');

		$orderings = array_values($orderingPerId);
		$orderings = array_unique($orderings);
		$minOrdering = reset($orderings);

		$myOrdering = $orderingPerId[$id];

		reset($orderings);
		$sharedOrderings = 0;
		foreach ($orderingPerId as $fooid => $order)
		{
			if ($order > $myOrdering)
			{
				break;
			}

			if ($order == $myOrdering)
			{
				$sharedOrderings++;
			}
		}

		// Do I need to reorder the plugin?
		if (($myOrdering > $minOrdering) || ($sharedOrderings > 1))
		{
			$query = $db->getQuery(true)
				->update($db->qn('#__extensions'))
				->set($db->qn('ordering') . ' = ' . $db->q($minOrdering - 1))
				->where($db->qn('id') . ' = ' . $db->q($id));
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Automatically migrates settings from the component's parameters storage
	 * to our version 2.1+ dedicated storage table.
	 */
	public function autoMigrate()
	{
		// First, load the component parameters
		// FIX 2.1.13: Load the component parameters WITHOUT using JComponentHelper
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('params'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type') . ' = ' . $db->quote('component'))
			->where($db->qn('element') . ' = ' . $db->quote('com_admintools'));
		$db->setQuery($query);
		$rawparams = $db->loadResult();
		$cparams = new JRegistry();
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$cparams->loadString($rawparams, 'JSON');
		}
		else
		{
			$cparams->loadJSON($rawparams);
		}

		// Migrate parameters
		$allParams = $cparams->toArray();
		$safeList = array(
			'downloadid', 'lastversion', 'minstability',
			'scandiffs', 'scanemail', 'htmaker_folders_fix_at240',
			'acceptlicense', 'acceptsupport', 'sitename',
			'showstats', 'longconfigpage',
		);
		if (interface_exists('JModel'))
		{
			$params = JModelLegacy::getInstance('Storage', 'AdmintoolsModel');
		}
		else
		{
			$params = JModel::getInstance('Storage', 'AdmintoolsModel');
		}
		$modified = 0;
		foreach ($allParams as $k => $v)
		{
			if (in_array($k, $safeList))
			{
				continue;
			}
			if ($v == '')
			{
				continue;
			}

			$modified++;

			if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$cparams->set($k, null);
			}
			else
			{
				$cparams->setValue($k, null);
			}
			$params->setValue($k, $v);
		}

		if ($modified == 0)
		{
			return;
		}

		// Save new parameters
		$params->save();

		// Save component parameters
		$db = JFactory::getDBO();
		$data = $cparams->toString();

		$sql = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('params') . ' = ' . $db->q($data))
			->where($db->qn('element') . ' = ' . $db->q('com_admintools'))
			->where($db->qn('type') . ' = ' . $db->q('component'));

		$db->setQuery($sql);
		$db->execute();
	}

	public function needsDownloadID()
	{
		JLoader::import('joomla.application.component.helper');

		// Do I need a Download ID?
		$ret = false;
		$isPro = ADMINTOOLS_PRO;
		if (!$isPro)
		{
			$ret = true;
		}
		else
		{
			$ret = false;
			$params = JComponentHelper::getParams('com_admintools');
			if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$dlid = $params->get('downloadid', '');
			}
			else
			{
				$dlid = $params->getValue('downloadid', '');
			}
			if (!preg_match('/^([0-9]{1,}:)?[0-9a-f]{32}$/i', $dlid))
			{
				$ret = true;
			}
		}

		return $ret;
	}

	/**
	 * Checks if the download ID provisioning plugin for the updates of this extension is published. If not, it will try
	 * to publish it automatically. It reports the status of the plugin as a boolean.
	 *
	 * @return  bool
	 */
	public function isUpdatePluginEnabled()
	{
		// We can't be bothered about the plugin in Joomla! 2.5.0 through 2.5.19
		if (version_compare(JVERSION, '2.5.19', 'lt'))
		{
			return true;
		}

		// We can't be bothered about the plugin in Joomla! 3.x
		if (version_compare(JVERSION, '3.0.0', 'gt'))
		{
			return true;
		}

		$db = $this->getDBO();

		// Let's get the information of the update plugin
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__extensions'))
			->where($db->qn('folder') . ' = ' . $db->quote('installer'))
			->where($db->qn('element') . ' = ' . $db->quote('admintools'))
			->where($db->qn('type') . ' = ' . $db->quote('plugin'))
			->order($db->qn('ordering') . ' ASC');
		$db->setQuery($query);
		$plugin = $db->loadObject();

		// If the plugin is missing report it as unpublished (of course!)
		if (!is_object($plugin))
		{
			return false;
		}

		// If it's enabled there's nothing else to do
		if ($plugin->enabled)
		{
			return true;
		}

		// Otherwise, try to enable it and report false (so the user knows what he did wrong)
		$pluginObject = (object)array(
			'id' => $plugin->id,
			'enabled'      => 1
		);

		try
		{
			$result = $db->updateObject('#__extensions', $pluginObject, 'id');
			// Do not remove this line. We need to tell the user he's doing something wrong.
			$result = false;
		}
		catch (Exception $e)
		{
			$result = false;
		}

		return $result;
	}

	/**
	 * Checks the database for missing / outdated tables using the $dbChecks
	 * data and runs the appropriate SQL scripts if necessary.
	 *
	 * @return AdmintoolsModelCpanels
	 */
	public function checkAndFixDatabase()
	{
		// Install or update database
		$dbInstaller = new F0FDatabaseInstaller(array(
			'dbinstaller_directory' => JPATH_ADMINISTRATOR . '/components/com_admintools/sql/xml'
		));
		$dbInstaller->updateSchema();

		return $this;
	}
}