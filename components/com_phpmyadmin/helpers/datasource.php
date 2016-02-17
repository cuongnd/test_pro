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
    public static function tree_node_data($stringQuery, $list)
    {
        $request = DataSourceHelper::get_tree_node($stringQuery, true);
        if ($request == '')
            return $list;
        $request = explode(',', $request);
        $space = $request[5];
        $assign = $request[4];
        /*'node_id';
        'node_parent_id';
        'node_title';
        'node_ordering';*/
        $list_node_parent = array();
        foreach ($list as $key => $node) {
            if (!$node->node_parent_id) {
                $node->$assign = $node->node_title;
                $list_node_parent[] = $node;
                unset($list[$key]);
            }
        }
        usort($list_node_parent, function ($item1, $item2) {
            if ($item1->node_ordering == $item2->node_ordering) return 0;
            return $item1->node_ordering < $item2->node_ordering ? -1 : 1;
        });
        $return_list = array();
        foreach ($list_node_parent as $node_parent) {
            $return_list[] = $node_parent;
            DataSourceHelper::create_node_list($return_list, $assign, $space, $node_parent->node_id, $list, 0);
        }
        foreach ($return_list as $key => $item) {
            unset($return_list[$key]->node_id);
            unset($return_list[$key]->node_parent_id);
            unset($return_list[$key]->node_title);
            unset($return_list[$key]->node_ordering);
        }
        return $return_list;
    }

    function create_node_list(&$return_list = array(), $assign, $space, $parent_id, $list_item, $level = 0)
    {
        $list_item1 = array();
        foreach ($list_item as $key => $item) {
            if ((int)$item->node_parent_id == (int)$parent_id) {
                $list_item1[] = $item;
                unset($list_item[$key]);
            }
        }
        usort($list_item1, function ($item1, $item2) {
            if ($item1->node_ordering == $item2->node_ordering) return 0;
            return $item1->node_ordering < $item2->node_ordering ? -1 : 1;
        });

        $level1 = $level + 1;
        foreach ($list_item1 as $item) {
            $item->$assign = str_repeat($space, $level1) . $item->node_title;
            $return_list[] = $item;
            DataSourceHelper::create_node_list($return_list, $assign, $space, $item->node_id, $list_item, $level1);
        }
    }

    private static function in_tree_root($query_string = '')
    {
        if ($query_string == '')
            return $query_string;
        $app = JFactory::getApplication();
        $requestString = '/(.*?)in_tree_root(\(|\'|)(.*?)(\)|\'| )/s';
        preg_match_all($requestString, $query_string, $requests);
        $requests = $requests[3][0];
        if (!$requests) {
            return $query_string;
        }
        $a_request = $requests;
        $requests = explode(',', $requests);
        $operator = $requests[0];
        $field = $requests[1];
        $tree_table = $requests[2];
        $parent_id = $requests[3];
        $children_id = $requests[4];
        $request = $requests[5];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($tree_table);

        $list_node = $db->setQuery($query)->loadObjectList();

        $children = array();

        // First pass - collect children
        foreach ($list_node as $v) {
            $pt = $v->$parent_id;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }

        $list_id = array();
        $id = $app->input->get($request, 0);
        $list_id[]=$id;
        //static::treeReCurseCategories($id, $children_id, $list_id, $children);

        $requests = '';
        if (count($list_id)) {
            $list_id = implode(',', $list_id);
            $requests = " $operator $field IN($list_id) ";
        }
        $a_request = 'in_tree_root(' . $a_request . ')';
        $query_string = str_ireplace($a_request, $requests, $query_string);
        return $query_string;
    }

    public static function treeReCurseCategories($parent_id, $children_id, &$list, &$children, $maxlevel = 9999, $level = 0)
    {
        if (@$children[$parent_id] && $level <= $maxlevel) {
            foreach ($children[$parent_id] as $v) {
                $parent_id = $v->$children_id;
                $list[] = $parent_id;
                $level1 = $level + 1;
                static::treeReCurseCategories($parent_id, $children_id, $list, $children, $maxlevel, $level1);
            }
        }
    }


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

    public function read_data_by_block_id($block_id)
    {
        $db = JFactory::getDbo();
        $config = JFactory::getConfig();
        require_once JPATH_ROOT . '/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT . '/media/kendotest/php/lib/Kendo/Autoload.php';
        $app = JFactory::getApplication();
        JTable::addIncludePath(JPATH_ROOT . '/libraries/legacy/table');
        $tablePosition = JTable::getInstance('PositionNested', 'JTable');
        $tablePosition->load($block_id);
        $params = new JRegistry;
        $params->loadString($tablePosition->params);
        $bindingSource = $params->get('data')->bindingSource;


        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/models');
        $modalDataSources = JModelLegacy::getInstance('DataSources', 'phpMyAdminModel');
        $list = $modalDataSources->getListDataSource($bindingSource, $tablePosition);

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';

        $mode_select_column_template = $params->get('mode_select_column_template', '');
        $array_column = array();
        if ($mode_select_column_template != '') {
            $mode_select_column_template = up_json_decode($mode_select_column_template, false, 512, JSON_PARSE_JAVASCRIPT);
            foreach ($mode_select_column_template as $column) {
                $item = new stdClass();
                $item->max_character = $column->max_character;
                $item->type = $column->type;
                $item->column = $column->column_name;
                $array_column[$column->column_name] = $item;
            }
        }
        if (count($array_column)) {
            foreach ($list as $key => $item) {
                foreach ($array_column as $column) {
                    $max_character = $column->max_character;
                    $column_name = $column->column;
                    if ($max_character && $column_name != '') {
                        $list[$key]->{$column_name} = strip_tags(JString::truncate($item->{$column_name}, $max_character, '...', false, true));
                    }
                    $type = $column->type;
                    if ($type == 'object' && $column_name != '' && !is_object($item->{$column_name})) {
                        $list[$key]->{$column_name} = new stdClass();
                    }
                }
            }
        }


        $data = new stdClass();
        $data->total = count($list);
        $data->data = $list;
        return $data;
    }

    public function read_data_list_view_by_block_id($block_id)
    {

        $db = JFactory::getDbo();
        $config = JFactory::getConfig();
        require_once JPATH_ROOT . '/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT . '/media/kendotest/php/lib/Kendo/Autoload.php';
        $app = JFactory::getApplication();
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('PositionNested', 'JTable');
        $tablePosition->load($block_id);
        $params = new JRegistry;
        $params->loadString($tablePosition->params);


        $input_type = $params->get('input_type', 'items');

        if ($input_type == 'items') {
            $items = $params->get('items');
            $items = base64_decode($items);
            $items = json_decode($items);
            return $items;
            $data = new stdClass();
            $data->total = count($items);
            $data->data = $items;
            return $data;
        }
        $bindingSource = $params->get('data.bindingSource');


        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/models');
        $modalDataSources = JModelLegacy::getInstance('DataSources', 'phpMyAdminModel');
        $list = $modalDataSources->getListDataSource($bindingSource, $tablePosition);

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';

        $mode_select_column_template = $params->get('mode_select_column_template', '');
        $array_column = array();
        if ($mode_select_column_template != '') {
            $mode_select_column_template = up_json_decode($mode_select_column_template, false, 512, JSON_PARSE_JAVASCRIPT);
            foreach ($mode_select_column_template as $column) {
                $item = new stdClass();
                $item->max_character = $column->max_character;
                $item->type = $column->type;
                $item->column = $column->column_name;
                $array_column[$column->column_name] = $item;
            }
        }
        if (count($array_column)) {
            foreach ($list as $key => $item) {
                foreach ($array_column as $column) {
                    $max_character = $column->max_character;
                    $column_name = $column->column;
                    if ($max_character && $column_name != '') {
                        $list[$key]->{$column_name} = strip_tags(JString::truncate($item->{$column_name}, $max_character, '...', false, true));
                    }
                    $type = $column->type;
                    if ($type == 'object' && $column_name != '' && !is_object($item->{$column_name})) {
                        $list[$key]->{$column_name} = new stdClass();
                    }
                }
            }
        }

        return $list;
        $data = new stdClass();
        $data->total = count($list);
        $data->data = $list;
        return $data;
    }

    public function get_data_source_by_function($function = '')
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('data_source.id')
            ->from('#__datasource AS data_source')
            ->where('data_source.name=' . $query->q($function));
        $data_source_id = $db->setQuery($query)->loadResult();
        JTable::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/tables');
        $tableDataSource = JTable::getInstance('DataSource', 'JTable');
        $tableDataSource->load($data_source_id);
        if ($tableDataSource->use_type == "code_php") {
            $file_php = JPATH_ROOT . '/cache/get_data_by_data_source_' . $tableDataSource->id . '.php';
            $list = JUtility::get_content_file($tableDataSource, $file_php, '#__datasource', 'php_content');
            return $list;

        }
        $query = $tableDataSource->datasource;
        $stringQuery = DataSourceHelper::OverWriteDataSource($query);
        $query = $db->getQuery(true);
        $query->setQuery($stringQuery);
        $data = $db->setQuery($query)->loadObjectList();
        return $data;

    }

    public static function OverWriteDataSource($datasource)
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
            if (strtolower($request[0]) == 'website_id') {

                $website_id = $input->get('website_id', 0);
                if (!$website_id) {
                    $website = JFactory::getWebsite();
                    $website_id = $website->website_id;
                }
                $listRequest[] = $website_id;
            } else {
                $listRequest[] = $input->get($request[0], $request[1]);
            }
        }
        $listRequest2 = array();
        foreach ($requests as $request) {
            $listRequest2[] = 'request(' . $request . ')';
        }
        $datasource = str_ireplace($listRequest2, $listRequest, $datasource);

        $datasource = DataSourceHelper::get_json_group_concat($datasource);
        $datasource = DataSourceHelper::get_tree_node($datasource);
        $datasource = DataSourceHelper::in_tree_root($datasource);


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

    public function get_tree_node($query_string, $get_tree_node = false)
    {
        //replate get_json_group_concat
        //id:tour.id,tour_name:tour.title
        //get_tree_node(field,id,parent_id,ordering,asign_name,---)
        //to field,id,parent_id,ordering
        $requestString = '/(.*?)get_tree_node(\(|\'|)(.*?)(\)|\'| )/s';
        preg_match_all($requestString, $query_string, $requests);
        $requests = $requests[3][0];
        $a_request = $requests;
        if ($get_tree_node) {
            return $a_request;
        }
        $requests = explode(',', $requests);
        $requests[0] = $requests[0] . ' AS node_title';
        $requests[1] = $requests[1] . ' AS node_id';
        $requests[2] = $requests[2] . ' AS node_parent_id';
        $requests[3] = $requests[3] . ' AS node_ordering';
        array_pop($requests);
        array_pop($requests);
        $requests = implode(',', $requests);
        $a_request = 'get_tree_node(' . $a_request . ')';
        $query_string = str_ireplace($a_request, $requests, $query_string);
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