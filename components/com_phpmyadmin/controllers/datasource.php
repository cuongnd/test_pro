<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class phpMyAdminControllerDataSource extends PhpmyadminController
{
    public function aJaxInsertDataSource()
    {
        $app=JFactory::getApplication();

        //get array name datalist
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select("dataSource.name");
        $query->from('#__datasource as dataSource');
        $db->setQuery($query);
        $data= $db->loadColumn();

        $dataSourceName=JUtility::getDataSourceNameAvailable('datasource',$data);
        $position=$app->input->get('position','','string');
        $website=JFactory::getWebsite();
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');
        $tableDataSource->id=0;
        $tableDataSource->title=$dataSourceName;
        $tableDataSource->name=$dataSourceName;
        $tableDataSource->access=1;
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $screenSize=$app->input->get('screensize','480X320','string');
        $tableDataSource->screensize=$screenSize;
        $tableDataSource->website_id=$website->website_id;
        if(!$tableDataSource->store())
        {
            echo  $tableDataSource->getError();
            die;
        }
        $newDataSourceId=$tableDataSource->id;
        ob_end_clean();
        $respone_bject=new stdClass();
        $respone_bject->addOnContent='<div data-add-on-id="'.$newDataSourceId.'" class="add-on-item-content pull-left"><a  class="remove label label-danger remove-add-on" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a><a href="javascript:void(0)" data-add-on-id="'.$newDataSourceId.'" ><i class="br-database"></i>'.$dataSourceName.'</a></div>';
        echo json_encode($respone_bject);
        die;

    }
    function   read_data_by_editable()
    {
        $db=JFactory::getDbo();
        $config=JFactory::getConfig();
        require_once JPATH_ROOT.'/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT.'/media/kendotest/php/lib/Kendo/Autoload.php';
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $key_value=$app->input->get('key_value','','string');
        $keyword=$app->input->get('keyword','','string');
        $keyword=strtolower($keyword);
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('Position','JTable');
        $tablePosition->load($block_id);
        $params = new JRegistry;
        $params->loadString($tablePosition->params);
        $bindingSource=$params->get('data.data_source_editable','');
        $source_key=$params->get('data.source_key','');
        $source_key=explode('.',$source_key);
        $source_key=end($source_key);

        $source_value=$params->get('data.source_value','');
        $source_value=explode('.',$source_value);
        $source_value=end($source_value);




        $modalDataSources=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
        $list_item=$modalDataSources->getListDataSource($bindingSource,$tablePosition);

        $list=array();
        foreach($list_item as $key=>$item)
        {
            if($key_value!='') {
                  if($item->$source_key==$key_value)
                  {
                      $list[]=$item;
                      break;
                  }
            }elseif($keyword!=''){
                if (strpos(strtolower($item->$source_value),$keyword) !== false) {
                    $list[]=$item;
                }
            }
        }
        require_once JPATH_ROOT.'/libraries/upgradephp-19/upgrade.php';

        $mode_select_column_template=$params->get('mode_select_column_template','');
        $array_column=array();
        if($mode_select_column_template!='')
        {
            $mode_select_column_template=up_json_decode($mode_select_column_template,false, 512, JSON_PARSE_JAVASCRIPT);
            foreach($mode_select_column_template as $column)
            {
                $item=new stdClass();
                $item->max_character= $column->max_character;
                $item->type= $column->type;
                $item->column= $column->column_name;
                $array_column[$column->column_name]= $item;
            }
        }
        if(count($array_column))
        {
            foreach($list as $key=>$item)
            {

                foreach($array_column as $column)
                {
                    $max_character= $column->max_character;
                    $column_name= $column->column;
                    if($max_character && $column_name!='')
                    {
                        $list[$key]->{$column_name}=strip_tags(JString::truncate($item->{$column_name},$max_character,'...',false,true));
                    }
                    $type=$column->type;
                    if($type=='object' && $column_name!=''&&!is_object($item->{$column_name}))
                    {
                        $list[$key]->{$column_name}=new stdClass();
                    }
                }

            }
        }


        $data=new stdClass();
        $data->total=count($list);
        $data->data=$list;
        header('Content-Type: application/json');
        echo json_encode($list,JSON_NUMERIC_CHECK);
        die;
    }
    public function ajax_load_database()
    {
        $conn=JFactory::getDbo();
        $db = (isset($_GET["database"]) ? $_GET["database"] : "information_schema");
        $db = mysqli_real_escape_string($conn, $db);
        $xml = "";

        $arr = array();
        @ $datatypes = file("../../db/mysql/datatypes.xml");
        $arr[] = $datatypes[0];
        $arr[] = '<sql db="mysql">';
        for ($i=1;$i<count($datatypes);$i++) {
            $arr[] = $datatypes[$i];
        }

        $result = mysqli_query($conn, "SELECT * FROM TABLES WHERE TABLE_SCHEMA = '".$db."'");
        while ($row = mysqli_fetch_array($result)) {
            $table = $row["TABLE_NAME"];
            $xml .= '<table name="'.$table.'">';
            $comment = (isset($row["TABLE_COMMENT"]) ? $row["TABLE_COMMENT"] : "");
            if ($comment) { $xml .= '<comment>'.htmlspecialchars($comment).'</comment>'; }

            $q = "SELECT * FROM COLUMNS WHERE TABLE_NAME = '".$table."' AND TABLE_SCHEMA = '".$db."'";
            $result2 = mysqli_query($conn, $q);
            while ($row = mysqli_fetch_array($result2)) {
                $name  = $row["COLUMN_NAME"];
                $type  = $row["COLUMN_TYPE"];
                $comment = (isset($row["COLUMN_COMMENT"]) ? $row["COLUMN_COMMENT"] : "");
                $null = ($row["IS_NULLABLE"] == "YES" ? "1" : "0");

                if (preg_match("/binary/i",$row["COLUMN_TYPE"])) {
                    $def = bin2hex($row["COLUMN_DEFAULT"]);
                } else {
                    $def = $row["COLUMN_DEFAULT"];
                }

                $ai = (preg_match("/auto_increment/i",$row["EXTRA"]) ? "1" : "0");
                if ($def == "NULL") { $def = ""; }
                $xml .= '<row name="'.$name.'" null="'.$null.'" autoincrement="'.$ai.'">';
                $xml .= '<datatype>'.strtoupper($type).'</datatype>';
                $xml .= '<default>'.$def.'</default>';
                if ($comment) { $xml .= '<comment>'.htmlspecialchars($comment).'</comment>'; }

                /* fk constraints */
                $q = "SELECT
					REFERENCED_TABLE_NAME AS 'table', REFERENCED_COLUMN_NAME AS 'column'
					FROM KEY_COLUMN_USAGE k
					LEFT JOIN TABLE_CONSTRAINTS c
					ON k.CONSTRAINT_NAME = c.CONSTRAINT_NAME
					WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
					AND c.TABLE_SCHEMA = '".$db."' AND c.TABLE_NAME = '".$table."'
					AND k.COLUMN_NAME = '".$name."'";
                $result3 = mysqli_query($conn, $q);

                while ($row = mysqli_fetch_array($result3)) {
                    $xml .= '<relation table="'.$row["table"].'" row="'.$row["column"].'" />';
                }

                $xml .= '</row>';
            }

            /* keys */
            $q = "SELECT * FROM STATISTICS WHERE TABLE_NAME = '".$table."' AND TABLE_SCHEMA = '".$db."' ORDER BY SEQ_IN_INDEX ASC";
            $result2 =mysqli_query($this->getLink(),$q);
            $idx = array();

            while ($row = mysqli_fetch_array($result2)) {
                $name = $row["INDEX_NAME"];
                if (array_key_exists($name, $idx)) {
                    $obj = $idx[$name];
                } else {
                    $type = $row["INDEX_TYPE"];
                    $t = "INDEX";
                    if ($type == "FULLTEXT") { $t = $type; }
                    if ($row["NON_UNIQUE"] == "0") { $t = "UNIQUE"; }
                    if ($name == "PRIMARY") { $t = "PRIMARY"; }

                    $obj = array(
                        "columns" => array(),
                        "type" => $t
                    );
                }

                $obj["columns"][] = $row["COLUMN_NAME"];
                $idx[$name] = $obj;
            }

            foreach ($idx as $name=>$obj) {
                $xml .= '<key name="'.$name.'" type="'.$obj["type"].'">';
                for ($i=0;$i<count($obj["columns"]);$i++) {
                    $col = $obj["columns"][$i];
                    $xml .= '<part>'.$col.'</part>';
                }
                $xml .= '</key>';
            }
            $xml .= "</table>";
        }
        $arr[] = $xml;
        $arr[] = '</sql>';
        echo implode("\n",$arr);
        die;
    }



    public function ajax_save_content_php_code()
    {
        $app=JFactory::getApplication();
        $input=$app->input;
        $php_content=$input->get('php_content','','string');
        $binding_source_id=$input->get('binding_source_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $table_datasource_item = JTable::getInstance('DataSource','JTable');
        $table_datasource_item->load($binding_source_id);
        $table_datasource_item->php_content= $php_content;
        $result = new stdClass();
        if (!$table_datasource_item->store()) {
            $result->e = 1;
            $result->m = $table_datasource_item->getError();
        } else
        {
            $result->e=0;
        }
        echo json_encode($result);
        die;
    }

    public function ajaxGetStanderQuery()
    {
        $app=JFactory::getApplication();
        $stringQuery=$app->input->get('query','','string');
        $stringQuery=str_replace('t__','#__',$stringQuery);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);

        $query->setQuery($stringQuery);
        echo $query->dump();
        die;

    }
    public function ajax_update_Data()
    {
        $app=JFactory::getApplication();
        $db=JFactory::getDbo();
        $models=json_decode(file_get_contents('php://input'));
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $block_id=$app->input->get('block_id',0);
        $type=$app->input->get('type','');
        $table_block=JTable::getInstance('Position','JTable');
        $table_block->load($block_id);
        $params = new JRegistry;
        $params->loadString($table_block->params);
        $table_name_update=$params->get('table_update','');
        $config_update_data=$params->get('config_update_data','');

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $config_update_data = up_json_decode($config_update_data, false, 512, JSON_PARSE_JAVASCRIPT);
        $table_key=$params->get('table_key','');
        $table_update=JTable::getInstance('UpdateTable','JTable',array('dbo'=>$db,'table'=>$table_name_update,'key'=>$table_key));
        if(strtolower($table_block->type)=='grid')
        {

             foreach($models->models as $key=> $item)
             {


                 if($item->$table_key=='')
                    $item->$table_key=0;

                 foreach($item as $key1=>$value)
                 {
                     if(is_object($value))
                     {
                        if($value->key_update&&$value->key_value)
                        {
                            $item->{$value->key_update}=trim($value->{$value->key_value});
                        }
                     }
                 }
                 $data_bind=array();
                 foreach($item as $key1=>$value)
                 {
                     if(!is_object($value))
                     {
                         $data_bind[$key1]=trim($value);
                     }
                 }

                 $table_update->bind((array)$data_bind);

                 if($type=='destroy'&&!$table_update->delete())
                 {
                     echo  $table_update->getError();
                     die;
                 }else if(!$table_update->store())
                 {
                     echo  $table_update->getError();
                     die;
                 }
                 $models->models[$key]->{$table_key}= $table_update->{$table_key};

             }

            header('Content-Type: application/json');
            if($type=="create")
            {
                $data = new stdClass;
                $data->data = $models->models;
                echo json_encode($data,JSON_NUMERIC_CHECK);
                die;
            }elseif ($type=="destroy") {
                echo json_encode($models,JSON_NUMERIC_CHECK);
            }else{
                echo json_encode($models,JSON_NUMERIC_CHECK);
            }

            die;
        }else
        {

        }
    }
    public function ajax_get_data_by_data_source_id()
    {
        $db=JFactory::getDbo();
        $app=JFactory::getApplication();
        $data_source_id=$app->input->get('data_source_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');
        $tableDataSource->load($data_source_id);
        $query= $tableDataSource->datasource;
        $use_type=$tableDataSource->use_type;

        //if use code php
        if($use_type=='code_php')
        {
            $file_php = JPATH_ROOT . '/cache/get_data_by_data_source_' . $tableDataSource->id . '.php';
            $list = JUtility::get_content_file($tableDataSource, $file_php, '#__datasource', 'php_content');
            header('Content-Type: application/json');
            echo json_encode($list,JSON_NUMERIC_CHECK);
            die;

        }else {
            require_once JPATH_ROOT . '/components/com_phpmyadmin/helpers/datasource.php';
            $stringQuery = DataSourceHelper::OverWriteDataSource($query);
            $query = $db->getQuery(true);
            $query->setQuery($stringQuery);
            $data = $db->setQuery($query)->loadObjectList();
            header('Content-Type: application/json');
            echo json_encode($data, JSON_NUMERIC_CHECK);
            die;
        }

    }
    public function ajax_update_ordring_grid()
    {
        echo "1";
        die;
    }
    public function check_data_post($data,$config_update_data){

    }
    public function update_data_post(&$data,$config_update_data,$foreign_key='',$value_foreign_key=0,$level=1){

        $db=JFactory::getDbo();
        if(count($config_update_data)) {
            $primary_key='';
            $table_name_update='';
            $post_name='';
            foreach($config_update_data as $config)
            {
                $type=$config->type;
                if($config->primary_key==1)
                {
                    $primary_key=$config->column_name;
                    $table_name_update=$config->table_name;
                    $post_name=$config->post_name;
                }

            }
            $table_update=new JTableUpdateTable($db,$table_name_update,$primary_key);

            if($level==1)
            {
                $parse_query=$data['parse_query'];
                $table_update->$primary_key=$parse_query[$post_name];
            }

            if($level>1)
            {
                $table_update->$primary_key=0;
                $data[$foreign_key]=$value_foreign_key;
                $data[$primary_key]=0;
            }
            $table_update->bind($data);
            if(!$table_update->store())
            {
                $data['e']=1;
                $data['m']= $table_update->getError();
                return $data;
            }else {

                $data['e'] = 0;
                $data['main_key'] = $primary_key;
                $data['post_name'] = $post_name;
                $data[$primary_key] = $table_update->$primary_key;
            }
            foreach($config_update_data as $config)
            {
                if(count($config->children))
                {
                    switch ($config->type) {
                        case 'array':
                            $post_name=$config->post_name;
                            $foreign_key=$config->column_name;
                            if(!$data[$post_name])
                                continue;
                            $data_post=count($data[$post_name])?$data[$post_name]:array($data[$post_name]);
                            $foreign_table=$config->table_name;
                            $query=$db->getQuery(true);
                            $query->delete('#__'.$foreign_table)
                                ->where($foreign_key.'='.$table_update->$primary_key);
                            $db->setQuery($query);
                            if(!$db->execute())
                            {
                                $data['e']=1;
                                $data['m']= $db->getErrorMsg();
                                return $data;
                            }
                            $second_column=reset($config->children)->column_name;

                            foreach($data_post as $value) {
                                $query->clear();
                                $query->insert('#__'.$foreign_table)
                                    ->columns($foreign_key.','.$second_column)
                                    ->values($table_update->$primary_key.','.$value)
                                ;
                                if(!$db->execute())
                                {
                                    $data['e']=1;
                                    $data['m']= $db->getErrorMsg();
                                    return $data;
                                };
                            }
                            break;
                        case 'json':
                            $post_name=$config->post_name;
                            $foreign_key=$config->column_name;
                            $data_post=count($data[$post_name])?$data[$post_name]:array($data[$post_name]);
                            $foreign_table=$config->table_name;
                            $query=$db->getQuery(true);
                            $query->delete('#__'.$foreign_table)
                                ->where($foreign_key.'='.$table_update->$primary_key);
                            $db->setQuery($query);
                            if(!$db->execute())
                            {
                                $data['e']=1;
                                $data['m']= $db->getErrorMsg();
                                return $data;
                            }

                            foreach($data_post as $post) {
                                $sub_level = $level + 1;
                                phpMyAdminControllerDataSource::update_data_post($post, $config->children,$foreign_key, $table_update->$primary_key, $sub_level);
                            }
                            break;
                        default:
                            echo "i equals 2";
                            break;
                    }


                }
            }
        }
        return $data;
    }
    public function get_php()
    {
        $app=JFactory::getApplication();
        $db=JFactory::getDbo();
        $prefix_table= $db->getPrefix();
        $all_text=$app->input->get('all_text','','string');

        $requestString='/(.*?)t__(.*?) /s';
        preg_match_all($requestString, $all_text, $array_table);
        $array_table=$array_table[2];
        $result_list_table=array();
        foreach($array_table as $key=>$table)
        {
            $result_list_table[$prefix_table.$table]=$prefix_table.$table;
        }
        $keyword=$app->input->get('keyword','','string');
        $keyword=strtolower($keyword);
        $keyword=str_replace('t__',$prefix_table,$keyword);
        $query=' show tables like "%'.$keyword.'%"';
        $db->setQuery($query);
        $list_table=$db->loadColumn();
        foreach($list_table as $key=>$table)
        {
            $result_list_table[$table]=$table;
        }
        $line=$app->input->get('line','','string');
        $line=explode(' ',$line);
        $last_sub_string_line=end($line);
        if (strpos($last_sub_string_line,'::') !== false) {
            $last_sub_string_line=explode('::',$last_sub_string_line);
            $class=$last_sub_string_line[0];
            $class = new ReflectionClass($class);
            $methods = $class->getMethods();
            foreach ($methods as $method) {
                $function= $method->name;
                $list_parameter=array();

                foreach($method->getParameters() as $parameter)
                {

                    array_push($list_parameter,$parameter->name);
                }
                $parameters=implode(',',$list_parameter);
                $function.="( $parameters )";
                $result_list_table[$function]=$function;

            }
        }else {

            foreach ($result_list_table as $table) {
                $db->redirectPage(false);
                $fields = $db->getTableColumns($table);
                unset($result_list_table[$table]);
                $result_list_table[str_replace($prefix_table, 't__', $table)] = $fields;
            }
            $classes = get_declared_classes();
            foreach ($classes as $class) {
                $class_to_lower = strtolower($class);
                if (strpos($class_to_lower, $keyword) !== false) {
                    $result_list_table[$class] = $class;
                }

            }
        }

        header('Content-Type: application/json');
        echo json_encode($result_list_table,JSON_NUMERIC_CHECK);
        die;

    }
    function action_map_copy($mapcopy,$key_value=0,$foreign_table='',$maxLevel = 9999, $level = 0)
    {
        if($level<=$maxLevel)
        {
            foreach ($mapcopy as $item) {

                $table_name = $item->table_name;
                if(!$table_name)
                {
                    echo "table must not empty";
                    die;
                }
                $column_name = $item->column_name;
                if(!$column_name)
                {
                    echo "table $table_name not config column_name";
                    die;
                }
                $foreign_key = $item->foreign_key;
                if($level>0&&!$foreign_key)
                {
                    echo "table $table_name not config foreign_key";
                    die;
                }
                $db = JFactory::getDbo();
                $item_table = new JTableUpdateTable($db, $table_name);
                $item_table->load(array($column_name => $key_value));
                $item_table->$column_name = 0;
                $item_table->parent_id = $key_value;
                $item_table->create_by_booking = 1;
                $item_table->store();
                if($level>0&&is_object($foreign_table)) {
                    $foreign_table->$foreign_key = $item_table->$column_name;
                    $foreign_table->store();
                }
                $key_value1 = $item_table->$column_name;
                if(is_array($item->children)&&count($item->children)>0 ) {
                    $level1=$level+1;
                    phpMyAdminControllerDataSource::action_map_copy($item->children,$key_value1,$item_table, $maxLevel,$level1);
                }
            }

        }

    }
    public  function ajax_save_data()
    {
        $app=JFactory::getApplication();
        $db=JFactory::getDbo();
        $data= $app->input->get('data',array(),'array');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $block_id=$app->input->get('block_id',0);
        $table_block=JTable::getInstance('Position','JTable');
        $table_block->load($block_id);
        $params = new JRegistry;

        $params->loadString($table_block->params);

        $is_booking=$params->get('is_booking',1);
        if($is_booking==1)
        {
            $mapcopy=$params->get('mapcopy','');
            require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
            $mapcopy = (array)up_json_decode($mapcopy, false, 512, JSON_PARSE_JAVASCRIPT);
            require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
            phpMyAdminControllerDataSource::action_map_copy($mapcopy,8);
            die;

        }

        $process_type=$params->get('process_type','auto');
        $config_update_data= $params->get('config_update_data','');
        if($config_update_data!='') {
            require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
            $config_update_data = up_json_decode($config_update_data, false, 512, JSON_PARSE_JAVASCRIPT);
            //check data type first
            //phpMyAdminControllerDataSource::check_data_post($data,array($config_update_data));
            require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
            if($process_type=='code_php')
            {
                $code_php = $params->get('code_process', '');
                $code_php=trim($code_php);
                if (base64_encode(base64_decode($code_php, true)) === $code_php) {
                    $code_php = base64_decode($code_php);
                } else {
                    $code_php = '';
                }

                jimport('joomla.filesystem.file');
                $file_php = JPATH_ROOT . '/cache/' . JUserHelper::genRandomPassword() . '.php';

                JFile::write($file_php, $code_php);

                $data = (array)include_once($file_php);

                JFile::delete($file_php);

            }else
            {
                $data=phpMyAdminControllerDataSource::update_data_post($data,$config_update_data);
            }
            echo json_encode($data);
            die;
        }else{
            echo '$config_update_data is null';
        }
        die;
    }
    public  function update_data_by_editable()
    {
        $app=JFactory::getApplication();
        $db=JFactory::getDbo();
        $data= $app->input->get('data',array(),'array');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $block_id=$app->input->get('block_id',0);
        $table_block=JTable::getInstance('Position','JTable');
        $table_block->load($block_id);
        $params = new JRegistry;

        $params->loadString($table_block->params);
        $process_type=$params->get('process_type','auto');
        $config_update_data= $params->get('config_update_data','');
        if($config_update_data!='') {
            require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
            $config_update_data = up_json_decode($config_update_data, false, 512, JSON_PARSE_JAVASCRIPT);
            //check data type first
            //phpMyAdminControllerDataSource::check_data_post($data,array($config_update_data));
            require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
            if($process_type=='code_php')
            {
                $code_php = $params->get('code_process', '');
                $code_php=trim($code_php);
                if (base64_encode(base64_decode($code_php, true)) === $code_php) {
                    $code_php = base64_decode($code_php);
                } else {
                    $code_php = '';
                }

                jimport('joomla.filesystem.file');
                $file_php = JPATH_ROOT . '/cache/' . JUserHelper::genRandomPassword() . '.php';

                JFile::write($file_php, $code_php);

                $data = (array)include_once($file_php);

                JFile::delete($file_php);

            }else
            {
                $data=phpMyAdminControllerDataSource::update_data_post($data,$config_update_data);
            }
            echo json_encode($data);
            die;
        }else{
            echo '$config_update_data is null';
        }
        die;
    }
    public function ajaxGetDataByQuery()
    {
        $app=JFactory::getApplication();
        $stringQuery=$app->input->get('query','','string');
        $source_id=$app->input->get('source_id',0,'int');
        $type=$app->input->get('type','','string');

        $use_type=$app->input->get('use_type','','string');
        if($use_type=='code_php')
        {
            if (base64_encode(base64_decode($stringQuery, true)) === $stringQuery) {
                $stringQuery = base64_decode($stringQuery);
            } else {
                    $stringQuery = '';
            }
            jimport('joomla.filesystem.file');
            $file_php = JPATH_ROOT . '/cache/get_data_'.$type.'_by_query_' . $source_id . '.php';
            JFile::write($file_php, $stringQuery);
            $list=include_once($file_php);
            JFile::delete($file_php);

            $arrayReturn=array();
            if(!$list)
            {
                $arrayReturn['e']=1;
                $arrayReturn['m']='error';
            }else
            {
                $arrayReturn['e']=0;
                $arrayReturn['r']=$list;
            }
            echo json_encode($arrayReturn);
            die;
        }
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_phpmyadmin/helpers/datasource.php';
        $stringQuery=DataSourceHelper::OverWriteDataSource($stringQuery);
        $query=$db->getQuery(true);
        $query->setQuery($stringQuery);
        $db->setQuery($query);
        if(trim($query)=='')
        {
            return array();
        }
        $db->redirectPage(false);
        $list=$db->loadObjectList();
        $arrayReturn=array();
        if(!$list)
        {
            $arrayReturn['e']=1;
            $arrayReturn['m']=$db->getErrorMsg();
        }else
        {
            $arrayReturn['e']=0;
            $arrayReturn['r']=$list;
        }
        echo json_encode($arrayReturn);
        die;
    }
    public function aJaxRemoveAddOn()
    {
        $app=JFactory::getApplication();
        $addOnId=$app->input->get('addOnId',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');
        if($tableDataSource->delete($addOnId))
            echo 1;
        else
            echo   0;
        die;
    }
    public function getTable($name = 'DataSource', $prefix = 'JTable', $config = array())
    {
        return parent::getTab($name, $prefix, array('ignore_request' => true));
    }

    public function readData()
    {
        $db=JFactory::getDbo();
        $config=JFactory::getConfig();
        require_once JPATH_ROOT.'/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT.'/media/kendotest/php/lib/Kendo/Autoload.php';
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('Position','JTable');
        $tablePosition->load($block_id);
        $params = new JRegistry;
        $params->loadString($tablePosition->params);
        $bindingSource=$params->get('data')->bindingSource;




        $modalDataSources=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
        $list=$modalDataSources->getListDataSource($bindingSource,$tablePosition);

        require_once JPATH_ROOT.'/libraries/upgradephp-19/upgrade.php';

        $mode_select_column_template=$params->get('mode_select_column_template','');
        $array_column=array();
        if($mode_select_column_template!='')
        {
            $mode_select_column_template=up_json_decode($mode_select_column_template,false, 512, JSON_PARSE_JAVASCRIPT);
            foreach($mode_select_column_template as $column)
            {
                $item=new stdClass();
                $item->max_character= $column->max_character;
                $item->type= $column->type;
                $item->column= $column->column_name;
                $array_column[$column->column_name]= $item;
            }
        }
        if(count($array_column))
        {
            foreach($list as $key=>$item)
            {
                 foreach($array_column as $column)
                 {
                     $max_character= $column->max_character;
                     $column_name= $column->column;
                      if($max_character && $column_name!='')
                     {
                         $list[$key]->{$column_name}=strip_tags(JString::truncate($item->{$column_name},$max_character,'...',false,true));
                     }
                     $type=$column->type;
                     if($type=='object' && $column_name!=''&&!is_object($item->{$column_name}))
                     {
                         $list[$key]->{$column_name}=new stdClass();
                     }
                 }
            }
        }


        $data=new stdClass();
        $data->total=count($list);
        $data->data=$list;
        header('Content-Type: application/json');
        echo json_encode($data,JSON_NUMERIC_CHECK);
        die;

    }

    public function ajaxSavePropertiesDataSource()
    {
        $app=JFactory::getApplication();
        $form=$app->input->get('jform',array(),'array');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');

        $tableDataSource->load($form['id']);
        $tableDataSource->bind($form);
        $tableDataSource->params=json_encode($form['params']);
        if(!$tableDataSource->store())
        {
            echo $tableDataSource->getError();
        }
        die;
    }
    public function getModel($name = 'datasource', $prefix = 'phpMyAdminModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function ajaxSavePropertyDataSource()
    {
        $app=JFactory::getApplication();
        $form=$app->input->get('jform',array(),'array');
        $doc=JFactory::getDocument();
        $add_on_id=$app->input->get('add_on_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');
        $tableDataSource->load($add_on_id);
        $params = new JRegistry;
        $params->loadString($tableDataSource->params);

        foreach($form['params'] as $keyParam=>$valueParam)
        {
            $params->set($keyParam,trim($valueParam));
        }
        $form['params']=json_encode($params);


        $tableDataSource->bind($form);
        if(!$tableDataSource->store())
        {
            echo $tableDataSource->getError();
        }
        $query=$tableDataSource->datasource;
        $db=JFactory::getDbo();
        $db->redirectPage(false);
        $db->setQuery($query);
        $list=$db->loadObjectList();
        $returnObject=new stdClass();
        $returnObject->data_source_id=$tableDataSource->id;
        $dataset=reset($list);
        $html=array();
        $html[]='<a href=#><i class=st-files></i>'.$tableDataSource->title.'</a>';
        $html[]=' <ul class="nav sub">';
        foreach($dataset as $key=>$item)
        {
            $html[]='<li><a><i class="st-files"></i>'.$key.'</a></li>';
        }
        $html[]=' </ul>';
        $html=implode('',$html);

        $returnObject->html_dataset=$html ;
        $returnObject->title=$tableDataSource->title ;
        echo json_encode($returnObject);
        die;
    }



    public  function ajaxLoadPropertiesAddOn()
    {

        $app=JFactory::getApplication();
        $add_on_id=$app->input->get('add_on_id',0,'int');
        $app->input->set('id',$add_on_id);
        $modelDataSource=JModelLegacy::getInstance('DataSource','phpMyAdminModel');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $modelDataSource->setState('datasource.id',$add_on_id);

        $form=$modelDataSource->getForm();
        $options=$form->getFieldsets();
        ob_start();
        ?>


        <div class="properties datasource">
            <div class="panel-group" id="accordion<?php echo $add_on_id ?>" role="tablist" aria-multiselectable="true">
                <?php
                foreach($options as $key=>$option)
                {
                    $fieldSet = $form->getFieldset($key);
                ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headinge<?php echo $key ?>">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion<?php echo $add_on_id ?>" href="#collapse<?php echo $key ?>" aria-expanded="true"
                                   aria-controls="collapse<?php echo $key ?>">
                                    <?php echo $option->label ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $key ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $key ?>">
                            <div class="panel-body">
                                <?php
                                foreach ($fieldSet as $field)
                                {
                                ?>
                                <div class="form-horizontal">
                                    <?php echo $field->renderField(array(),true); ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php
        $contents=ob_get_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.block-properties .panel-body',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;
    }

}
