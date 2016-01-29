<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UtilityHelper
{
    public function renderFile()
    {

        $app = JFactory::getApplication();
        $input = $app->input;
        $input->set('tmpl', 'component');
        $uri = JFactory::getURI();
        $filePath = $uri->getPath();
        $content_tytpe = UtilityHelper::get_content_type(JPATH_ROOT . '/' . $filePath);
        $data = file_get_contents(JPATH_ROOT . '/' . $filePath);

        UtilityHelper::_compress($data, $content_tytpe);

    }
    function get_content_type($file)
    {
        // Determine Content-Type based on file extension
        // Default to text/html
        $info = pathinfo($file);
        $content_types = array(
            'css' => 'text/css; charset=UTF-8',
            'html' => 'text/html; charset=UTF-8',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'js' => 'text/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'txt' => 'text/plain',
            'xml' => 'application/xml');
        if (empty($content_types[$info['extension']]))
            return 'text/html; charset=UTF-8';
        return $content_types[$info['extension']];
    }

    function _compress($data, $content_type)
    {
        $supportsGzip = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;


        if ($supportsGzip) {
            $content = gzencode(trim(preg_replace('/\s+/', ' ', $data)), 9);
        } else {
            $content = $data;
        }

        $offset = 60 * 60;
        $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";

        header('Content-Encoding: gzip');
        header("content-type: $content_type; charset: UTF-8");
        header("cache-control: must-revalidate");
        header($expire);
        header('Content-Length: ' . strlen($content));
        header('Vary: Accept-Encoding');

        echo $content;
        die;

    }


}