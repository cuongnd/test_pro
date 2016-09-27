<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
if (version_compare(PHP_VERSION, '5.3.10', '<'))
{
    die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);

if (file_exists(__DIR__ . '/defines.php'))
{
    include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
    define('JPATH_BASE', __DIR__);
    require_once JPATH_BASE . '/includes/defines.php';
}
require_once JPATH_BASE . '/includes/framework.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;


// Instantiate the application.
$app = JFactory::getApplication('site');
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
$isAdminSite = UtilityHelper::isAdminSite();
if(!$isAdminSite)
{
    $screen_size=$app->input->getString('screenSize','');
    if($screen_size!='')
    {
        UtilityHelper::setScreenSize($screen_size);
    }
    $screen_size = UtilityHelper::getScreenSize();

    if(!$screen_size)
    {
        $doc=JFactory::getDocument();
        $uri = JFactory::getURI();
        ?>
        <head>
            <script src="<?php echo JUri::root() ?>jquery.min.js"></script>
            <script src="<?php echo JUri::root() ?>media/system/js/uri/src/URI.js"></script>
        </head>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                function getScreenSize() {
                    var w = window,
                        d = document,
                        e = d.documentElement,
                        g = d.getElementsByTagName('body')[0],
                        x = w.innerWidth || e.clientWidth || g.clientWidth,
                        y = w.innerHeight || e.clientHeight || g.clientHeight;

                    var currentScreenSize= x + 'X' + y;
                    return currentScreenSize;
                }
                var currentScreenSize=getScreenSize();
                var uri_current_link =  new URI("<?php echo $uri->toString() ?>");
                uri_current_link.addQuery("screenSize", currentScreenSize);
                window.location.href =uri_current_link ;
            });
        </script>
        <?php

    }else{
        $app->execute();
    }
}else{
    $app->execute();
}
