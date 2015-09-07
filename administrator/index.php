<?php

/**
 * @package    Joomla.Administrator
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (version_compare(PHP_VERSION, '5.3.10', '<'))
{
	die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);



if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__);
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . '/includes/helper.php';
require_once JPATH_BASE . '/includes/toolbar.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('administrator');

// Execute the application.
//$app->execute();
function _compress( $data ) {
    require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
    require_once JPATH_ROOT.'/libraries/jsmin-php-master/lib/JSMin.php';

    $html = str_get_html($data);
    unset($data);
    foreach($html->find('script') as $e)
    {
        $innertext=$e->innertext;
        if(trim($innertext)!='')
        {
            $e->innertext=JSMin::minify($innertext);
        }
    }
    $html=preg_replace('/<!--(.|\s)*?-->/', '', $html);
    $supportsGzip = strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false;


    if ( $supportsGzip ) {
        $content = gzencode( trim( preg_replace( '/\s+/', ' ', $html ) ), 9);
    } else {
        $content = $html;
    }

    $offset = 60 * 60;
    $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";

    header('Content-Encoding: gzip');
    header("content-type: text/html; charset: UTF-8");
    header("cache-control: must-revalidate");
    header( $expire );
    header( 'Content-Length: ' . strlen( $content ) );
    header('Vary: Accept-Encoding');

    echo $content;


}
$debug=1;
if($debug==1)
{
    $app->execute();
}
else
{
    ob_start();
     $app->execute();
    $content=ob_get_contents();
    ob_end_clean();

    _compress(sanitize_output($content)); 
 }
function sanitize_output($buffer) {

    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1'
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}
