<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// Joomla system checks.
$host = $_SERVER['HTTP_HOST'];
$host = strtolower($host);
$host = str_replace('www.', '', $host);
$host = str_replace(':81', '', $host);
$fileWebStore = $host . '.ini';
$contentIni = '';

require_once JPATH_ROOT . '/components/com_website/helpers/website.php';
$pathFileWebStore = JPATH_ROOT . '/webstore/' . $fileWebStore;
if (!file_exists($pathFileWebStore)) {

    exit("File $fileWebStore not exists");
}
$myfile = fopen($pathFileWebStore, "r") or die("Unable to open file $fileWebStore !");
$contentIni = fread($myfile, filesize($pathFileWebStore));
fclose($myfile);
$contentIni = trim($contentIni);
if (!is_numeric($contentIni)) {

}
$contentIni=explode(':',$contentIni);
if(count($contentIni)!=2)
{
    exit("File $fileWebStore format incorrect");
}
$website_id=$contentIni[0];
$website_name=$contentIni[1];
define('WEBSITE_ID',          (int)$website_id);
if(!WEBSITE_ID)
{
    throw new Exception('can not found website');
}

$fileConfig = JPATH_ROOT . '/configuration/configuration_' . $website_name . '.php';
// System includes
require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_ROOT.'/includes/toolbar.php';
// Set system error handling
JError::setErrorHandling(E_NOTICE, 'message');
JError::setErrorHandling(E_WARNING, 'message');
JError::setErrorHandling(E_ERROR, 'callback', array('JError', 'customErrorPage'));

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Pre-Load configuration.

if (!file_exists($fileConfig)) {
    exit("File $fileConfig not exists");
}

require_once $fileConfig;

define('JPATH_FILE_CONFIG', $fileConfig);
$config = new JConfig();
// Set the error_reporting
switch ($config->error_reporting) {
    case 'default':
    case '-1':
        error_reporting(E_ALL);
        error_reporting(1);
        break;

    case 'none':
    case '0':
        error_reporting(E_ERROR);
        error_reporting(1);
        break;

    case 'simple':
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
        error_reporting(1);

        break;

    case 'maximum':
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
        error_reporting(1);

        break;

    case 'development':
        error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
        ini_set('display_errors', 1);
        break;

    default:
        error_reporting($config->error_reporting);
        ini_set('display_errors', 1);

        break;
}
define('JDEBUG', $config->debug);
// System profiler
if (JDEBUG) {
    $_PROFILER = JProfiler::getInstance('Application');
}

declare(ticks=10);

require_once JPATH_ROOT . '/libraries/joomla/utilities/utility.php';
if (!function_exists('writeLog')) {
    function writeLog($stacks)
    {
        JLog::addLogger(
            array(
                // Sets file name
                'text_file' => 'com_helloworld.all_but_debug.php'
            ),
            // Sets all but DEBUG log level messages to be sent to the file
            JLog::ALL & ~JLog::DEBUG,
            // The log category which should be recorded in this file
            array('com_helloworld')
        );
    }
}

function shutdown()
{
    $a=error_get_last();
    if($a==null)
        echo "No errors";
    else
    {
        print_r(JUtility::printDebugBacktrace());
    }


}
function process_error_backtrace($errno, $errstr, $errfile, $errline, $errcontext) {
    switch($errno) {
        case E_WARNING      :
        case E_USER_WARNING :
        case E_STRICT       :
        case E_NOTICE       :
        case E_USER_NOTICE  :
            $type = 'warning';
            $fatal = false;
            break;
        default             :
            $type = 'fatal error';
            $fatal = true;
            break;
    }
    if($fatal||$errno==null){
        echo 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
        print_r(JUtility::printDebugBacktrace());
        die;
    }
}

if (JDEBUG) {
    register_tick_function('writeLog');
    set_error_handler('process_error_backtrace');
    register_shutdown_function('shutdown');
}