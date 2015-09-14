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
class DataSourceHelper
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
    public function get_data_source_by_function($function='')
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('data_source.id')
            ->from('#__datasource AS data_source')
            ->where('data_source.name='.$query->q($function))
            ;
        $data_source_id=$db->setQuery($query)->loadResult();
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');
        $tableDataSource->load($data_source_id);
        if($tableDataSource->use_type=="code_php")
        {
            $file_php = JPATH_ROOT . '/cache/get_data_by_data_source_' . $tableDataSource->id . '.php';
            $list = JUtility::get_content_file($tableDataSource, $file_php, '#__datasource', 'php_content');
            return $list;

        }
        $query= $tableDataSource->datasource;
        $stringQuery = DataSourceHelper::OverWriteDataSource($query);
        $query = $db->getQuery(true);
        $query->setQuery($stringQuery);
        $data = $db->setQuery($query)->loadObjectList();
        return  $data;

    }
    public function OverWriteDataSource($datasource)
    {
        $datasource = str_replace('t__', '#__', $datasource);
        $input = JFactory::getApplication()->input;
        //$datasource="SELECT * from #__sdfsd WHERE test=request(block_id,6)";
        //$datasource="SELECT * from #__sdfsd WHERE test=3";
        $requestString = '/(.*?)request(\(|\'|)(.*?)(\)|\'| )/s';
        preg_match_all($requestString, $datasource, $requests);
        $requests = $requests[3];
        $listRequest = array();
        foreach ($requests as $request) {
            $request = explode(',', $request);
            $listRequest[] = $input->get($request[0], $request[1]);
        }
        $listRequest2 = array();
        foreach ($requests as $request) {
            $listRequest2[] = 'request(' . $request . ')';
        }
        $datasource = str_ireplace($listRequest2, $listRequest, $datasource);

        $datasource=DataSourceHelper::get_json_group_concat($datasource);


        //get_function_user_id_login
        $user = JFactory::getUser();
        $datasource = str_replace('get_function_user_id_login', (int)$user->id, $datasource);


        return $datasource;
    }
    public function get_json_group_concat($query_string)
    {
        //replate get_json_group_concat
        //id:tour.id,tour_name:tour.title
        //group_concat(CONCAT('{','"id"',':','"',tour.id,'"',) )
        $requestString = '/(.*?)get_json_group_concat(\(|\'|)(.*?)(\)|\'| )/s';
        preg_match_all($requestString, $query_string, $requests);
        $requests = $requests[3];
        $listRequest = array();
        foreach ($requests as $request) {
            $a_request = explode(',', $request);
            $itemjson = array();
            $itemjson[] = '"{"';
            $total_a_request = count($a_request);
            for ($i = 0; $i < $total_a_request; $i++) {
                $field = $a_request[$i];
                $string_json = array();

                $a_field = explode(':', $field);

                $string_json[] = '\'"\'';
                $string_json[] = '"' . $a_field[0] . '"';
                $string_json[] = '\'"\'';
                $string_json[] = '":"';
                $string_json[] = '\'"\'';
                $string_json[] = $a_field[1];
                $string_json[] = '\'"\'';

                if ($i < $total_a_request - 1) {
                    $string_json[] = '","';
                }

                $b_field = implode(',', $string_json);
                $itemjson[] = $b_field;
            }
            $itemjson[] = '"}"';
            $string_item_json = implode(',', $itemjson);
            $string_item_json = 'GROUP_CONCAT(CONCAT(' . $string_item_json . '))';
            $listRequest[] = $string_item_json;
        }
        $listRequest2 = array();
        foreach ($requests as $request) {
            $listRequest2[] = 'get_json_group_concat(' . $request . ')';
        }
        $query_string = str_ireplace($listRequest2, $listRequest, $query_string);
        return $query_string;
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