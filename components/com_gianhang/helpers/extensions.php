<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gianhang
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * gianhang component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_gianhang
 * @since       1.6
 */
class extensionsHelper
{
	public static $extension = 'com_gianhang';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string    The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		// No submenu for this component.
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions()
	{
		// Log usage of deprecated function
		JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');

		// Get list of actions
		$result = JHelperContent::getActions('com_gianhang');

		return $result;
	}

    public static function get_list_extension_by_website_and_type($website_id, $extension_type)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__extensions')
            ->where('website_id='.(int)$website_id)
            ->where('type='.(int)$query->q($extension_type))

        ;
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public static function get_website_id_by_extension_id($extension_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website_id')
            ->from('#__extensions')
            ->where('id='.(int)$extension_id)

        ;
        $db->setQuery($query);
        return $db->loadResult();
    }

    public function getComponentByWebsiteId($website_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__gianhang')
            ->where('website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public static function getWebsites()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__website');
        $query->select('id,title');
        $db->setQuery($query);
        $listWebsite=$db->loadObjectList();
        $query=$db->getQuery(true);
        $query->from('#__domain_website');
        $query->select('id,domain,website_id');
        $db->setQuery($query);
        $listWebsiteDomain=$db->loadObjectList();
        foreach($listWebsiteDomain as $domainWebsite)
        {
            foreach($listWebsite as $key=> $website)
            {
                if($website->id==$domainWebsite->website_id)
                {
                    $listWebsite[$key]->listSite[]=$domainWebsite->domain;
                }
            }
        }
        foreach($listWebsite as $key=>$website)
        {
            $listWebsite[$key]->title.='('.implode($website->listSite).')';
        }
        return $listWebsite;
    }

	/**
	 * Returns an array of standard published state filter options.
	 *
	 * @return  string    The HTML code for the select tag
	 */
	public static function publishedOptions()
	{
		// Build the active state filter options.
		$options = array();
		$options[] = JHtml::_('select.option', '1', 'JENABLED');
		$options[] = JHtml::_('select.option', '0', 'JDISABLED');

		return $options;
	}

	/**
	 * Returns an array of standard published state filter options.
	 *
	 * @return  string    The HTML code for the select tag
	 */
	public static function folderOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(folder) AS value, folder AS text')
			->from('#__extensions')
			->where($db->quoteName('type') . ' = ' . $db->quote('component'))
			->order('folder');

		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public function parseXMLTemplateFile($templateBaseDir, $templateDir)
	{
		$data = new JObject;

		// Check of the xml file exists
		$filePath = JPath::clean($templateBaseDir . '/templates/' . $templateDir . '/templateDetails.xml');
		if (is_file($filePath))
		{
			$xml = JInstaller::parseXMLInstallFile($filePath);

			if ($xml['type'] != 'template')
			{
				return false;
			}

			foreach ($xml as $key => $value)
			{
				$data->set($key, $value);
			}
		}

		return $data;
	}
}
