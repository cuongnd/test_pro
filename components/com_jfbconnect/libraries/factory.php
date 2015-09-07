<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if (!class_exists('JOauth1Client'))
{
    JLoader::register('JOauth1Client', JPATH_SITE . '/components/com_jfbconnect/libraries/joomla/oauth1/client.php');
}

JLoader::register('JFBConnectAuthenticationOauth2', JPATH_SITE . '/components/com_jfbconnect/libraries/authentication/oauth2.php');

JLoader::register('JFBConnectChannel', JPATH_SITE . '/components/com_jfbconnect/libraries/channel.php');
JLoader::register('JFBConnectProvider', JPATH_SITE . '/components/com_jfbconnect/libraries/provider.php');
JLoader::register('JFBConnectProfile', JPATH_SITE . '/components/com_jfbconnect/libraries/profile.php');
JLoader::register('JFBConnectWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/widget.php');
JLoader::register('JFBConnectProviderFacebookWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/facebook/widget.php');
JLoader::register('JFBConnectProviderPinterestWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/pinterest/widget.php');

JLoader::discover('JFBConnectProvider', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/');
JLoader::discover('JFBConnectProfile', JPATH_SITE . '/components/com_jfbconnect/libraries/profile/');

JLoader::discover('JFBConnectProviderFacebookWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/facebook/widget/');
JLoader::discover('JFBConnectProviderGoogleWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/google/widget/');
JLoader::discover('JFBConnectProviderLinkedInWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/linkedin/widget/');
JLoader::discover('JFBConnectProviderPinterestWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/pinterest/widget/');
JLoader::discover('JFBConnectProviderTwitterWidget', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/twitter/widget/');

include_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/usermap.php');

jimport('joomla.filesystem.folder');

class JFBCFactory
{
    public static function provider($name)
    {
        static $providers = array();
        if (!isset($providers[$name]))
        {
            $className = 'JFBConnectProvider' . $name;

            if (class_exists($className))
            {
                // Do not clean this up. It's ugly, but needs to be to prevent recursive loading
                $providers[$name] = new $className();
                $providers[$name]->setupAuthentication();
            }
        }
        if (isset($providers[$name]))
            return $providers[$name];
        else
            return null;
    }


    public static function getAllProviders()
    {
        static $allProviders;
        if (!isset($allProviders))
        {
            $allProviders = array();
            $files = JFolder::files(JPATH_SITE . '/components/com_jfbconnect/libraries/provider/');
            foreach ($files as $file)
            {
                $allProviders[] = self::provider(str_replace(".php", "", $file));
            }
        }
        return $allProviders;
    }

    public static function library($path)
    {
        static $loaded;
        if (!isset($loaded[$path]))
        {
            $parts = explode('.', $path);
            $path = implode('/', $parts);
            $className = 'JFBConnect' . implode('', array_map('ucfirst', $parts));
            require_once(JPATH_SITE . '/components/com_jfbconnect/libraries/' . $path . '.php');
            $loaded[$path] = new $className();
        }
        return $loaded[$path];
    }

    public static function model($name)
    {
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_jfbconnect/models');
        $model = JModelLegacy::getInstance($name, 'JFBConnectModel');
        return $model;
    }

    public static function config()
    {
        static $configModel = null;
        if (!isset($configModel))
        {
            require_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/config.php');
            $configModel = new JFBConnectModelConfig();
        }
        return $configModel;
    }

    // Return an instance of the usermap model, always creating it
    public static function usermap()
    {
        $userMapModel = new JFBConnectModelUserMap();
        return $userMapModel;
    }

    public static function widget($providerName, $name, $params = "")
    {
        $className = 'JFBConnectProvider' . ucfirst($providerName) . 'Widget' . ucfirst($name);
        $provider = self::provider($providerName);
        if(class_exists($className))
            $widget = new $className($provider, $params);
        else
            $widget = null;
        return $widget;
    }

    public static function getAllWidgetProviderNames()
    {
        $providers = JFolder::folders(JPATH_SITE . '/components/com_jfbconnect/libraries/provider/');
        return $providers;
    }

    public static function getAllWidgets($provider)
    {
        static $allWidgets;

        if(!$provider || $provider == 'provider')
            return array();

        if (!isset($allWidgets))
            $allWidgets = array();

        if (!isset($allWidgets[$provider]))
        {
            $allWidgets[$provider] = array();

            $widgetFolder = JPATH_SITE . '/components/com_jfbconnect/libraries/provider/' . $provider . '/widget/';
            if(JFolder::exists($widgetFolder))
            {
                $widgetFiles = JFolder::files($widgetFolder, '.xml');
                if ($widgetFiles && count($widgetFiles) > 0)
                {
                    foreach ($widgetFiles as $file)
                    {
                        $allWidgets[$provider][] = self::widget($provider, str_replace(".xml", "", $file));
                    }
                }
            }

        }
        return $allWidgets[$provider];
    }
}