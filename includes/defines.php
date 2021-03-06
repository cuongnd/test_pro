<?php
session_start();

/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
define('FOLDER_SYSTEM_CACHE_LONGTIME',         '_systemcachelongtime');


// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
// Defines.
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_SITE',          JPATH_ROOT);
define('JPATH_CONFIGURATION', JPATH_ROOT);
define('JPATH_ADMINISTRATOR', JPATH_ROOT . '/administrator');
define('JPATH_LIBRARIES',     JPATH_ROOT . '/libraries');
define('JPATH_PLATFORM',     JPATH_ROOT . '/libraries');
define('JPATH_PLUGINS',       JPATH_ROOT . '/plugins');
define('JPATH_INSTALLATION',  JPATH_ROOT . '/installation');
define('JPATH_THEMES',        JPATH_BASE . '/templates');
define('JPATH_CACHE',         JPATH_BASE . '/cache');
define('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . '/manifests');
