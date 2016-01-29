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
$domainSupper = websiteHelperFrontEnd::getSupperAdminWebsite();
if (in_array($host, $domainSupper)) {
    // Sanitize the namespace.
    $fileConfig = JPATH_CONFIGURATION . '/configuration.php';
} else {
    $pathFileWebStore = JPATH_ROOT . '/webstore/' . $fileWebStore;
    if (!file_exists($pathFileWebStore)) {

        exit("File $fileWebStore not exists");
    }
    $myfile = fopen($pathFileWebStore, "r") or die("Unable to open file $fileWebStore !");
    $contentIni = fread($myfile, filesize($pathFileWebStore));
    fclose($myfile);
    $contentIni = trim($contentIni);
    if (!is_numeric($contentIni)) {
        exit("File $fileWebStore format incorrect");
    }
    define('WEBSITE_ID',          $contentIni);
    $fileConfig = JPATH_ROOT . '/configuration/configuration_' . $contentIni . '.php';

}

// System includes
require_once JPATH_LIBRARIES . '/import.legacy.php';

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
        error_reporting(E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);
        //error_reporting(E_ALL);
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

function tick_handler()
{
    global $backtrace;
    $backtrace = debug_backtrace();
    //$GLOBALS['dbg_stack'][] = debug_backtrace();
    //writeLog($backtrace);

}

require_once JPATH_ROOT . '/libraries/joomla/utilities/utility.php';
if (!function_exists('writeLog')) {
    function writeLog($stacks)
    {
        //$stacks=end($stacks);
        // $filePath=end($stacks['args']);
        //$filePath=str_replace(JPATH_ROOT,'',$filePath);
        $ouput = JUtility::printDebugBacktrace2($stacks);
        $flieLog = JPATH_ROOT . "/logs/hello.html";
        $fileSize = 0;
        if (file_exists($flieLog)) {
            $fileSize = filesize($flieLog);
            $fileSize = JUtility::byteToOtherUnit($fileSize, 'KB', false);
        }

        if ($fileSize > 5000) {
            echo "file log less then 500KB";
            die;
        }
        $fileHandler = fopen($flieLog, "a") or die("Unable to open file!");
        fwrite($fileHandler, $ouput . "\n");
        fclose($fileHandler);
    }
}


function shutdown()
{
    $a=error_get_last();
    if($a==null)
        echo "No errors";
    else
        print_r($a);

    global $backtrace;
    $output = "";
    $output .= "<hr /><div> Error" . '<br /><table border="1" cellpadding="2" cellspacing="2">';

    $stacks = $backtrace;

    $output .= "<thead><tr><th><strong>File</strong></th><th><strong>Line</strong></th><th><strong>Function</strong></th>" .
        "</tr></thead>";
    foreach ($stacks as $_stack) {
        if (!isset($_stack['file'])) $_stack['file'] = '[PHP Kernel]';
        if (!isset($_stack['line'])) $_stack['line'] = '';

        $output .= "<tr><td>{$_stack["file"]}</td><td>{$_stack["line"]}</td>" .
            "<td>{$_stack["function"]}</td></tr>";
    }
    $output .= "</table></div><hr /></p>";
    echo $output;


}


if (JDEBUG) {
    register_tick_function('tick_handler');
    register_shutdown_function('shutdown');
}