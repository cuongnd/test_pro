<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * websitetemplatepro component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.6
 */
class templateHelper
{
    public static $extension = 'com_websitetemplatepro';

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
        $result = JHelperContent::getActions('com_websitetemplatepro');

        return $result;
    }

    public static function get_list_website()
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__website')
            ->where('created_by='.(int)$user->id)
            ->select('*');
        $db->setQuery($query);
        $list_website = $db->loadObjectList();
        return $list_website;
    }

    public static function set_website_id_for_list_template_website(&$list_template_website)
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website.id,domain_website.domain')
            ->from('#__website as website')
            ->leftJoin('#__domain_website AS domain_website ON domain_website.website_id=website.id')
            ->leftJoin('#__webtempro_products AS product ON product.website_id=website.id')
            ->where('product.website_id=0 OR product.website_id IS NULL')
            ->where('website.created_by_website_id='.(int)$website->website_id)
            ->where('domain_website.domain NOT LIKE '.$query->q('%admin%'))
            ;
        $db->setQuery($query);
        $list_website_unset=$db->loadObjectList();
        foreach($list_template_website as &$template_website)
        {

            if(!$template_website->website_id) {
                $website=array_pop($list_website_unset);
                if($website) {
                    $query = $db->getQuery(true);
                    $query->clear()
                        ->update('#__webtempro_products')
                        ->set('website_id=' . (int)$website->id)
                        ->where('id=' . (int)$template_website->id);
                    $db->setQuery($query);
                    $ok = $db->execute();
                    if (!$ok) {
                        throw new Exception($db->getErrorMsg());
                    }
                    $template_website->website_id = $website->id;
                    $template_website->link_demo = $website->domain;
                }
            }
        }
    }
    public static function set_website_id_for_template(&$template)
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website.id,domain_website.domain')
            ->from('#__website as website')
            ->leftJoin('#__domain_website AS domain_website ON domain_website.website_id=website.id')
            ->leftJoin('#__webtempro_products AS product ON product.website_id=website.id')
            ->where('product.website_id=0 OR product.website_id IS NULL')
            ->where('website.created_by_website_id='.(int)$website->website_id)
            ->where('domain_website.domain NOT LIKE '.$query->q('%admin%'))
            ;
        $db->setQuery($query);
        $website=$db->loadObject();
        if($website && !$template->website_id) {
            $query = $db->getQuery(true);
            $query->clear()
                ->update('#__webtempro_products')
                ->set('website_id=' . (int)$website->id)
                ->where('id=' . (int)$template->id);
            $db->setQuery($query);
            $ok = $db->execute();
            if (!$ok) {
                throw new Exception($db->getErrorMsg());
            }
            $template->website_id = $website->id;
            $template->link_demo = $website->domain;
        }

    }


    public function getComponentByWebsiteId($website_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__websitetemplatepro')
            ->where('website_id=' . (int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();

    }

    public static function gettemplate()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__website');
        $query->select('id,title');
        $db->setQuery($query);
        $listWebsite = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->from('#__domain_website');
        $query->select('id,domain,website_id');
        $db->setQuery($query);
        $listWebsiteDomain = $db->loadObjectList();
        foreach ($listWebsiteDomain as $domainWebsite) {
            foreach ($listWebsite as $key => $website) {
                if ($website->id == $domainWebsite->website_id) {
                    $listWebsite[$key]->listSite[] = $domainWebsite->domain;
                }
            }
        }
        foreach ($listWebsite as $key => $website) {
            $listWebsite[$key]->title .= '(' . implode($website->listSite) . ')';
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
            ->from('#__plugins')
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
            ->order('folder');

        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $options;
    }

    public function parseXMLTemplateFile($templateBaseDir, $templateDir)
    {
        $data = new JObject;

        // Check of the xml file exists
        $filePath = JPath::clean($templateBaseDir . '/templates/' . $templateDir . '/templateDetails.xml');
        if (is_file($filePath)) {
            $xml = JInstaller::parseXMLInstallFile($filePath);

            if ($xml['type'] != 'template') {
                return false;
            }

            foreach ($xml as $key => $value) {
                $data->set($key, $value);
            }
        }

        return $data;
    }
}
