<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 * @version   $Id$
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

class AdmintoolsControllerCpanel extends F0FController
{
	public function execute($task)
	{
		if (!in_array($task, array('login', 'updategeoip', 'updateinfo')))
		{
			$task = 'browse';
		}

		$this->task = 'browse';

		parent::execute($task);
	}

	public function onBeforeBrowse()
	{
		$result = parent::onBeforeBrowse();
		if ($result)
		{
			$model2 = $this->getModel('Cpanel', 'AdmintoolsModel');

			$view = $this->getThisView();
			$view->setModel($model2, true);

			$this->getThisModel()->checkAndFixDatabase();
			$this->getThisModel()->autoMigrate();

			/** @var AdmintoolsModelUpdates $updateModel */
			$updateModel = F0FModel::getTmpInstance('Updates', 'AdmintoolsModel');
			$updateModel->refreshUpdateSite();

			$needDLID = $this->getThisModel()->needsDownloadID();
			$view->needsdlid = $needDLID;

			// Check the last installed version (only the Professional release)
			if (ADMINTOOLS_PRO)
			{
				$versionLast = null;
				if (file_exists(JPATH_COMPONENT_ADMINISTRATOR . '/admintools.lastversion.php'))
				{
					include_once JPATH_COMPONENT_ADMINISTRATOR . '/admintools.lastversion.php';
					if (defined('ADMINTOOLS_LASTVERSIONCHECK'))
					{
						$versionLast = ADMINTOOLS_LASTVERSIONCHECK;
					}
				}
				if (is_null($versionLast))
				{
					// FIX 2.1.13: Load the component parameters WITHOUT using JComponentHelper
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);

					$query->select(array($db->quoteName('params')))
						->from($db->quoteName('#__extensions'))
						->where($db->quoteName('type') . ' = ' . $db->Quote('component'))
						->where($db->quoteName('element') . ' = ' . $db->Quote('com_admintools'));
					$db->setQuery($query);
					$rawparams = $db->loadResult();
					$params = new JRegistry();
					if (version_compare(JVERSION, '3.0', 'ge'))
					{
						$params->loadString($rawparams, 'JSON');
					}
					else
					{
						$params->loadJSON($rawparams);
					}

					$versionLast = $params->get('lastversion', '');
				}
				if (version_compare(ADMINTOOLS_VERSION, $versionLast, 'ne') || empty($versionLast))
				{
					$this->setRedirect('index.php?option=com_admintools&view=postsetup');

					return true;
				}
			}
		}

		return $result;
	}

	public function login()
	{
		$model = $this->getModel('Masterpw');
		$password = $this->input->getVar('userpw', '');
		$model->setUserPassword($password);

		$url = 'index.php?option=com_admintools';
		$this->setRedirect($url);
	}

	public function updategeoip()
	{
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		// Load the GeoIP library if it's not already loaded
		if (!class_exists('AkeebaGeoipProvider'))
		{
			if (@file_exists(JPATH_PLUGINS . '/system/akgeoip/lib/akgeoip.php'))
			{
				if (@include_once JPATH_PLUGINS . '/system/akgeoip/lib/vendor/autoload.php')
				{
					@include_once JPATH_PLUGINS . '/system/akgeoip/lib/akgeoip.php';
				}
			}
		}

		$geoip = new AkeebaGeoipProvider();
		$result = $geoip->updateDatabase();

		$url = 'index.php?option=com_admintools';

		if ($result === true)
		{
			$msg = JText::_('ATOOLS_GEOBLOCK_MSG_DOWNLOADEDGEOIPDATABASE');
			$this->setRedirect($url, $msg);
		}
		else
		{
			$this->setRedirect($url, $result, 'error');
		}
	}

	public function updateinfo()
	{
		/** @var AdmintoolsModelUpdates $updateModel */
		$updateModel = F0FModel::getTmpInstance('Updates', 'AdmintoolsModel');
		$updateInfo = (object)$updateModel->getUpdates();

		$result = '';

		if ($updateInfo->hasUpdate)
		{
			$strings = array(
				'header'  => JText::sprintf('COM_ADMINTOOLS_CPANEL_MSG_UPDATEFOUND', $updateInfo->version),
				'button'  => JText::sprintf('COM_ADMINTOOLS_CPANEL_MSG_UPDATENOW', $updateInfo->version),
				'infourl' => $updateInfo->infoURL,
				'infolbl' => JText::_('COM_ADMINTOOLS_CPANEL_MSG_MOREINFO'),
			);

			$result = <<<ENDRESULT
	<div class="alert alert-warning">
		<h3>
			<span class="icon icon-exclamation-sign glyphicon glyphicon-exclamation-sign"></span>
			{$strings['header']}
		</h3>
		<p>
			<a href="index.php?option=com_installer&view=update" class="btn btn-primary">
				{$strings['button']}
			</a>
			<a href="{$strings['infourl']}" target="_blank" class="btn btn-small btn-info">
				{$strings['infolbl']}
			</a>
		</p>
	</div>
ENDRESULT;
		}

		echo '###' . $result . '###';

		// Cut the execution short
		JFactory::getApplication()->close();
	}
}
