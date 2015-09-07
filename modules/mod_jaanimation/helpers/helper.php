<?php
/**
 * ------------------------------------------------------------------------
 * JA Animation module for Joomla 2.5 & 3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
//jimport('joomla.html.parameter');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.html.parameter');
/**
 * modJAAnimation class.
 */
class ModJAAnimation
{


    /**
     *
     * Get object instance of JA Animation
     */
    function &getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new ModJAAnimation();
        }
        return $instance;
    }


    /**
     *
     * Load profile and param config
     * @param object $params
     * @param string $modulename
     * @return object
     */
    public function loadConfig($params, $modulename = "mod_jaanimation")
    {
        $mainframe = JFactory::getApplication();
        $use_cache = $mainframe->getCfg("caching");
        if ($params->get('cache') == "1" && $use_cache == "1") {
            $cache = & JFactory::getCache();
            $cache->setCaching(true);
            $cache->setLifeTime($params->get('cache_time', 30) * 60);
            $params = $cache->get(array((new ModJAAnimation()), 'loadProfile'), array($params, $modulename));
        } else {
            $params = ModJAAnimation::loadProfile($params, $modulename);
        }
        return $params;
    }


    /**
     *
     * Load profile
     * @param object $params
     * @param string $modulename
     * @return object
     */
    public static function loadProfile($params, $modulename = "mod_jaanimation")
    {
        $mainframe = JFactory::getApplication();
        $profilename = $params->get('profile', 'hallowen2');
        if (!empty($profilename)) {

            $path = JPATH_ROOT . DS . "modules" . DS . $modulename . DS . "admin" . DS . "config.xml";
            $ini_file = JPATH_ROOT . DS . "modules" . DS . $modulename . DS . "profiles" . DS . $profilename . ".ini";
            $config_content = "";
            if (file_exists($ini_file)) {
                $config_content = JFile::read($ini_file);
            }
            if (empty($config_content)) {
                $ini_file_in_temp = JPATH_SITE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . $modulename . DS . $profilename . ".ini";
                if (is_file($ini_file_in_temp))
                    $config_content = JFile::read($ini_file_in_temp);
                else
                    $config_content = ModJAAnimation::loadIniDefault();
            }

            if (file_exists($path) && !empty($config_content)) {
                $params_new = new JRegistry($config_content);
                return $params_new;
            }
        }
        return $params;
    }


    /**
     *
     * Load ini profile content
     */
    public static function loadIniDefault()
    {
        $config_content = "";

        return $config_content;
    }
}