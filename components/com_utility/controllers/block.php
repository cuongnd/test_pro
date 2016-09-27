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
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class UtilityControllerBlock extends JControllerForm
{

    public static function fix_screen_size()
    {
        $app = JFactory::getApplication();
        $db=JFactory::getDbo();
        $menu_item_active_id = $app->input->get('menu_item_active_id', 0, 'int');
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('positionnested');
        $website = JFactory::getWebsite();
        $tablePosition->website_id = $website->website_id;
        $parentId = $tablePosition->get_root_id();
        $tablePosition->load($parentId);
        $screen_size_id=UtilityHelper::get_current_screen_size_id_editing();
        $tablePosition->screen_size_id=$screen_size_id;
        $ok=$tablePosition->parent_store();
        if(!$ok)
        {
            throw new Exception($tablePosition->getError());
        }
        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id')
            ->from('#__position_config AS position_config')
        ;
        $list_position_config=$db->setQuery($query)->loadObjectList();

        $children_position_config = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position_config[$pt] ? $children_position_config[$pt] : array();
            array_push($list, $v);
            $children_position_config[$pt] = $list;
        }
        $list_root_position_config = $children_position_config['list_root'];
        unset($children_position_config['list_root']);




        $update_screen_size=function($function_call_back, $position_config_id=0,$screen_size_id, $children_position_config, $level=0, $max_level=999){
            if(count($children_position_config[$position_config_id])) {
                foreach($children_position_config[$position_config_id] as $position_config)
                {
                    $table_position_config = JTable::getInstance('PositionNested');
                    $table_position_config->load($position_config->id);
                    $table_position_config->screen_size_id = $screen_size_id;
                    $ok = $table_position_config->store();
                    if (!$ok) {
                        throw new Exception($table_position_config->getError());
                    }
                    $position_config_1 = $position_config->id;
                    $function_call_back($function_call_back,$position_config_1, $screen_size_id,$children_position_config);
                }
            }
        };

        $update_screen_size($update_screen_size,$parentId,$screen_size_id,$children_position_config);
    }
    public static function fix_screen_size_by_website_id($website_id=0)
    {
        $app = JFactory::getApplication();
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('positionnested');
        $root_position_id = $tablePosition->get_root_id_by_website_id($website_id);
        $query = $db->getQuery(true);
        $query->select('position_config.id,position_config.menu_item_id,position_config.screensize,position_config.lft,position_config.rgt')
            ->from('#__position_config AS position_config')
            ->where('position_config.parent_id=' . (int)$root_position_id)
            ->where('position_config.id<>position_config.parent_id')
        ;
        $db->setQuery($query);
        $list_root_position = $db->loadObjectList();

        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id')
            ->from('#__position_config AS position_config');
        $db->setQuery($query);
        $list_position_config = $db->loadObjectList();
        $children_position = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position[$pt] ? $children_position[$pt] : array();
            array_push($list, $v);
            $children_position[$pt] = $list;
        }
        unset($children_position['list_root']);
        foreach($list_root_position as $root_position)
        {
            if($root_position->screensize) {
                $screensize = $root_position->screensize;
                $update_screen_size = function ($function_call_back, $root_position_id = 0, $screensize = '1280X768', $children_position = array(), $level = 0, $max_level = 999) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    if ($children_position[$root_position_id]) {
                        $level1 = $level + 1;
                        foreach ($children_position[$root_position_id] as $v) {
                            $query = $db->getQuery(true)
                                ->update('#__position_config')
                                ->set('screensize=' . $query->q($screensize))
                                ->where('id=' . (int)$v->id);
                            $ok=$db->setQuery($query)->execute();
                            if(!$ok)
                            {
                                throw new Exception($db->getErrorMsg());
                            }
                            $root_position_id1 = $v->id;
                            $function_call_back($function_call_back, $root_position_id1, $screensize, $children_position, $level1, $max_level);

                        }
                    }
                };
                $update_screen_size($update_screen_size, $root_position->id, $screensize, $children_position);
            }
            else{
                $query = $db->getQuery(true)
                    ->delete('#__position_config')
                    ->where('id=' . (int)$root_position->id);
                $ok=$db->setQuery($query)->execute();
                if(!$ok)
                {
                    throw new Exception($db->getErrorMsg());
                }
            }
        }
    }
    public static function rebuild_website_by_website_id($website_id=0)
    {
        $app = JFactory::getApplication();
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('positionnested');
        $root_position_id = $tablePosition->get_root_id_by_website_id($website_id);
        $query = $db->getQuery(true);
        $query->select('position_config.id,position_config.menu_item_id,position_config.screensize,position_config.lft,position_config.rgt')
            ->from('#__position_config AS position_config')
            ->where('position_config.parent_id=' . (int)$root_position_id)
            ->where('position_config.id<>position_config.parent_id')
        ;
        $db->setQuery($query);
        $list_root_position = $db->loadObjectList();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id')
            ->from('#__position_config AS position_config');
        $db->setQuery($query);
        $list_position_config = $db->loadObjectList();
        $children_position = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position[$pt] ? $children_position[$pt] : array();
            array_push($list, $v);
            $children_position[$pt] = $list;
        }
        unset($children_position['list_root']);
        foreach($list_root_position as $root_position)
        {
            $left=1;
            $rebuild_position_website = function ($function_call_back, $root_position_id = 0, &$left = 0, $children_position = array(), $level = 1, $max_level = 999) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                if ($children_position[$root_position_id]) {
                    $level1 = $level + 1;
                    foreach ($children_position[$root_position_id] as $v) {
                        $root_position_id1 = $v->id;
                        $function_call_back($function_call_back, $root_position_id1, $left, $children_position, $level1, $max_level);
                        $right=$left+1;
                        $query = $db->getQuery(true)
                            ->update('#__position_config')
                            ->set('lft=' . $query->q($left))
                            ->set('rgt=' . $query->q($right))
                            ->set('level = ' . (int) $level)
                            ->where('id=' . (int)$v->id);
                        $ok=$db->setQuery($query)->execute();
                        if(!$ok)
                        {
                            throw new Exception($db->getErrorMsg());
                        }

                    }
                }
            };
            $rebuild_position_website($rebuild_position_website, $root_position->id, $left, $children_position);
            $right=$left+1;
            $query = $db->getQuery(true)
                ->update('#__position_config')
                ->set('lft=' . $query->q($left))
                ->set('rgt=' . $query->q($right))
                ->where('id=' . (int)$root_position->id);
            $ok=$db->setQuery($query)->execute();
            if(!$ok)
            {
                throw new Exception($db->getErrorMsg());
            }
        }
    }

    function executeQuery()
    {
        $db=JFactory::getDbo();
        $input=JFactory::getApplication()->input;
        $query=$input->get('query','','string');
        $query=base64_decode($query);
        $db->setQuery($query);
        if($db->execute())
        {
            echo 1;
        }else
        {
            echo 0;
        }
        die;

    }



    function loadFile()
    {
    }
    public function aJaxSetPreview()
    {
        $app=JFactory::getApplication();
        $preview=$app->input->get('preview',0,'int');
        if($preview)
        {
            UtilityHelper::setStatePreview($preview);
        }
        die;
    }
    public function smart_auto_complete()
    {

    }
    public function ajax_update_block()
    {

        $app = JFactory::getApplication();
        $post = file_get_contents('php://input');
        $post = json_decode($post);
        foreach($post as $key=> $value)
        {
            $app->input->set($key,$value);
        }
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $block_id=$app->input->get('block_id',0,'int');
        $tablePosition->load($block_id);
        $type=$tablePosition->type;
        $ui_path=$tablePosition->ui_path;
        require_once JPATH_ROOT.'/media/elements/ui/element.php';
        require_once JPATH_ROOT.'/'.$ui_path;
        $element="element{$type}Helper";
        $element=new $element;
        $return=new stdClass();
        $return->e=0;
        if(method_exists($element,'update_block'))
        {
            $return->r=$element::update_block($tablePosition);
        }
        echo json_encode($return);
        die;
    }
    public function ajax_save_field_params()
    {
        $app=JFactory::getApplication();
        $fields=$app->input->get('fields','','string');
        $control_id=$app->input->get('control_id',0,'int');
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
        $table_control=new JTableUpdateTable($db,'control');
        $table_control->load($control_id);
        $table_control->fields=$fields;
        $response=new stdClass();
        $response->e=0;
        if($control_id)
        {
            if(!$table_control->store())
            {
                $response->e=1;
                $response->r=$table_control->getError();
            }else{
                $response->r="save success";
            }
        }else{
            $response->e=1;
            $response->r="control id is null";
        }
        echo json_encode($response);
        die;
    }
    public function ajax_copy_params_property_element()
    {
        $app=JFactory::getApplication();
        $ui_element=$app->input->get('ui_element','','string');
        $control_id=$app->input->get('control_id',0,'int');
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
        $table_control=new JTableUpdateTable($db,'control');
        $table_control->load(array('element_path'=>$ui_element));
        $fields=$table_control->fields;
        $table_control->load($control_id);
        $table_control->fields=$fields;
        $response=new stdClass();
        $response->e=0;
        if($control_id)
        {
            if(!$table_control->store())
            {
                $response->e=1;
                $response->r=$table_control->getError();
            }else{
                $response->r="copy success";
            }
        }else{
            $response->e=1;
            $response->r="control id is null";
        }
        echo json_encode($response);
        die;
    }
    public function ajaxDuplicateBlock()
    {
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $element_type=$app->input->get('element_type','','string');
        $modelPosition=$this->getModel();
        $a_listId=array();
        $modelPosition->duplicateBlock($block_id,$a_listId);
        $getDuplicateBlockId=reset($a_listId);
        $app->input->set('id',$getDuplicateBlockId);
        $this->display();
    }
    public function ajax_update_field_block()
    {
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $field=$app->input->get('field','','string');
        $field=trim($field);
        $field=strtolower($field);
        $value=$app->input->get('value','','string');
        $response=new stdClass();
        $response->e=0;
        $response->m="save success";
        if($field=='')
        {
            $response->e=1;
            $response->m='field is null';
            echo json_encode($response);
            die;
        }

        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $block=JTable::getInstance('positionnested');
        if(!$block->load($block_id))
        {
            $response->e=1;
            $response->m=$block->getError();
            echo json_encode($response);
            die;
        }
        if (strpos($field,'params.') !== false) {
            $key=str_replace('params.','',$field);
            $params = new JRegistry;
            $params->loadString($block->params);
            $params->set($key,$value);
            $block->params=$params->toString();
        }
        if(!$block->store())
        {
            $response->e=1;
            $response->m=$block->getError();
            echo json_encode($response);
            die;
        }
        echo json_encode($response);
        die;


    }
    public function update_menu_item_from_root_menu($list_position,$menu_item_active_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        if(count($list_position))
        {
            foreach($list_position as $position)
            {
                $query=$db->getQuery(true);
                $query->update('#__position_config')
                    ->set('menu_item_id='.(int)$menu_item_active_id)
                    ->where('id='.(int)$position->id)
                    ;
                $db->setQuery($query);
                $db->execute();

                $query=$db->getQuery(true);
                $query->select('position_config.id,position_config.menu_item_id')
                    ->from('#__position_config AS position_config')
                    ->where('position_config.parent_id='.(int)$position->id)
                ;
                $db->setQuery($query);
                $list_position1=$db->loadObjectList();
                UtilityControllerUtility::update_menu_item_from_root_menu($list_position1,$menu_item_active_id);
            }
        }

    }
    function getimages()
    {
        echo "hello image";
        die;
    }
    function switch_language()
    {
        $input=JFactory::getApplication()->input;
        $array_text=$input->get('array_text',array(),'array');
        $language_id=$input->get('language_id',0,'int');
        $config=JFactory::getConfig();
        $primaryLanguage=$config->get('primaryLanguage',14,'int');

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*');
        $query->from('#__language_google');
        $query->where('id='.$language_id);
        $db->setQuery($query);
        $language=$db->loadObject();
        $iso639code=$language->iso639code;
        $array_text=JUtility::googleTranslations($array_text,$iso639code);
        $session =JFactory::getSession();
        if(!is_array($array_text))
        {
            $session->set('language_id', $primaryLanguage);
            $result=array(
                'tolang'=>$language
                ,'translations'=>array()
            );
            die;
        }
        $session->set('language_id', $language_id);
        $result=array(
            'tolang'=>$language
            ,'translations'=>$array_text
        );
        echo json_encode($result);
        die;
    }
    function aJaxUpdateColumnInScreen()
    {
        $app=JFactory::getApplication();
        $post=$app->input->getArray($_POST);
        $columnId=$post['columnId'];
        $columnX=$post['columnX'];
        $columnY=$post['columnY'];
        $columnWidth=$post['columnWidth'];
        $columnHeight=$post['columnHeight'];
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::updateColumnInScreen($columnId,$columnX,$columnWidth,$columnY,$columnHeight);
        die;

    }
    function aJaxUpdateColumnsInScreen()
    {
        $app=JFactory::getApplication();
        $listColumn=$app->input->get('listColumn',array(),'array');
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::updateColumnsInScreen($listColumn);
        die;

    }
    function aJaxConvertToElementType()
    {
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $element_type=$app->input->get('element_type','');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($block_id);
        $tablePosition->type=$element_type;
        $tablePosition->ui_path='/media/elements/ui/'.$element_type.'.php';
        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();
        }
        echo 1;
        die;
    }
    function aJaxUpdateRowsInScreen()
    {
        $app=JFactory::getApplication();
        $listRow=$app->input->get('listRow',array(),'array');
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::updateRowsInScreen($listRow);
        die;

    }
    function aJaxUpdateElements()
    {
        $app=JFactory::getApplication();
        $listElement=$app->input->get('listElement',array(),'array');
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::updateRowsInScreen($listElement);
        die;

    }
    function aJaxInsertRow()
    {

        $app=JFactory::getApplication();
        $post=$app->input->getArray($_POST);
        $listBlock=$app->input->get('listBlock',array(),'array');
        $parentColumnId=$post['parentColumnId'];
        $menu_item_id=$post['menuItemActiveId'];
        $screen_size_id=$post['screen_size_id'];
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $newRowId=UtilityHelper::InsertRowInScreen($screen_size_id,$parentColumnId,$menu_item_id);
        UtilityHelper::updateListBlock($listBlock);

        echo $newRowId;
        die;

    }
    public function getModel($name = 'position', $prefix = 'UtilityModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function ajaxLoadFieldTypeOfBlock()
    {

        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $field=$app->input->get('field','','string');
        $modelPosition= $this->getModel();
        $modelPosition->setState('position.id',$block_id);
        $app->input->set('id',$block_id);
        $form=$modelPosition->getForm();
        ob_start();
        $respone_array=array();
        $contents = $form->getInput($field);

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.itemField .panel-body',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;
    }
    public function ajaxBuildLess()
    {
        $website = JFactory::getWebsite();
        $websiteTable = JTable::getInstance('Website', 'JTable');
        $websiteTable->load($website->website_id);
        $lessInput = JPATH_ROOT . "/layouts/website/less/$websiteTable->source_less";
        $lessInputInfo = pathinfo($lessInput);
        $cssOutput = JPATH_ROOT . '/layouts/website/css/' . $lessInputInfo['filename'] . '.css';
        JUtility::compileLess($lessInput, $cssOutput);
        die;
    }
    public function ajaxSaveBlockHtml()
    {
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($block_id);
        $content=$app->input->get('content','','string');
        $content=base64_decode($content);
        $tablePosition->fulltext=$content;
        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();
        }
        echo 1;
        die;
    }
    public function ajaxSavePropertyBlockByEnter()
    {
        die;
        $app=JFactory::getApplication();
        $input=$app->input;
        $block_id=$input->get('block_id',0);
        $text=$input->get('text','','string');
        $field=$input->get('field','','string');
/*        $field=explode('_',$field);

        for($i=0;$i<count($field);$i++)
        {

        }
        $fieldname=$field[0][$field[1]]
        ${$field}=$text;
        print_var_name(${$field});
        die;*/
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($block_id);

        echo 1;
        die;
    }
    public function readData2()
    {
        require_once JPATH_ROOT.'/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT.'/media/kendotest/php/lib/Kendo/Autoload.php';
        $db = new PDO('sqlite:'.JPATH_ROOT.'/media/kendotest/php/data/sample.db');
        // Create SQL SELECT statement
        $statement = $db->prepare('SELECT * FROM Products');

        // Execute the statement
        $statement->execute();

        // The result of the 'read' operation is all products from the Products table
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
        die;
    }

    public function readData1()
    {
        require_once JPATH_ROOT.'/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT.'/media/kendotest/php/lib/Kendo/Autoload.php';
        $result = new DataSourceResult('sqlite:'.JPATH_ROOT.'/media/kendotest/php/data/sample.db');
        $data = $result->read('Products', array('ProductID','ProductName', 'UnitPrice', 'UnitsInStock', 'Discontinued'));
        header('Content-Type: application/json');
        echo json_encode($data,JSON_NUMERIC_CHECK);
        die;

    }
    public function readData()
    {
        $config=JFactory::getConfig();
        require_once JPATH_ROOT.'/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT.'/media/kendotest/php/lib/Kendo/Autoload.php';
        $result = new DataSourceResult('mysql:host='.$config->get('host','localhost').'; dbname='.$config->get('db','test_db'), $config->get('user','root'), $config->get('password',''));
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($block_id);
        $dataSource=$tablePosition->datasource;
        $db=JFactory::getDbo();
        $db->setQuery($dataSource);
        $list=$db->loadObjectList();
        $data=new stdClass();
        $data->total=count($list);
        $data->data=$list;
/*        require_once JPATH_ROOT.'/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT.'/media/kendotest/php/lib/Kendo/Autoload.php';
        $result = new DataSourceResult('sqlite:'.JPATH_ROOT.'/media/kendotest/php/data/sample.db');
        $data = $result->read('Employees', array('EmployeeID', 'FirstName', 'LastName', 'Title', 'Country'));*/
        header('Content-Type: application/json');
        echo json_encode($data,JSON_NUMERIC_CHECK);
        die;

    }
    public function updateData()
    {

    }
    public function destroyData()
    {

    }
    public function ajaxSavePropertyBlock()
    {
        $app=JFactory::getApplication();
        $form=$app->input->get('jform',array(),'array');

        $block_id=$app->input->get('block_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($block_id);



        $params = new JRegistry;
        $params->loadString($tablePosition->params);


        $post_params=new JRegistry;
        $post_params->loadObject($form->params);
        $post_params->merge($params);
        $form->params=$post_params->toString();





        $tablePosition->bind($form);
        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();
        }

        die;
    }


    function aJaxInsertElement()
    {
        $app=JFactory::getApplication();
        $pathElement=$app->input->get('pathElement','','string');
        $addSubRow=$app->input->get('addSubRow',1,'int');
        require_once JPATH_ROOT.'/media/elements/ui/element.php';
        require_once JPATH_ROOT.'/'.$pathElement;
        $path_parts = pathinfo($pathElement);
        $type=$path_parts['filename'];
        $classElementHelper='element'.$type.'Helper';
        $classElementHelper=new $classElementHelper;
        $parentColumnId=$app->input->get('parentColumnId',0,'int');
        $menu_item_id=$app->input->get('menuItemActiveId',0,'int');
        $screenSize=$app->input->get('screenSize','','string');
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $newTablePosition=UtilityHelper::InsertElementInScreen($screenSize,$parentColumnId,$menu_item_id,$type,$pathElement);
        $block_id = $newTablePosition->id;
        if($addSubRow) {
            $newRowId = UtilityHelper::InsertRowInScreen($screenSize, $block_id, $menu_item_id);
        }
        $listPositionsSetting = UtilityHelper::getListPositionsSetting('',$menu_item_id);
        $children = array();
        if (!empty($listPositionsSetting)) {

            $children = array();

            // First pass - collect children
            foreach ($listPositionsSetting as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }
        $html = '';
        require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
        websiteHelperFrontEnd::treeRecurse($block_id, $html, $children, 99, 0, true);
        $arrayReturn=array();

        $arrayReturn['html']= $classElementHelper::getHeaderHtml($newTablePosition,true).$html.$classElementHelper::getFooterHtml($newTablePosition,true);
        $arrayReturn['blockId']= $block_id;
        echo json_encode($arrayReturn);
        die;


        $content=ob_get_clean();
        $client=$app->getClientId();
        $enableEditWebsite=UtilityHelper::getEnableEditWebsite();
        $blockId=0;
        $element=new stdClass();
        if ($client==0&& $enableEditWebsite) {
            echo  '<div data-module-id="'.$element->id.'" data-block-id="'.$blockId.'" class="element-content  element-grid-stack-item"  >
					<a href="#close" data-element-id="'.$element->id.'" data-block-id="'.$blockId.'"  class="remove label label-danger remove-element"><i class="glyphicon-remove glyphicon"></i> remove</a>
					<span class="drag label label-default element-move-sub-row " data-block-id="'.$blockId.'"><i class="glyphicon glyphicon-move "></i> drag</span>
					<div class="element-grid-stack-item-contentaa" data-block-id="'.$blockId.'">
						<div class="panel panel-setting-element-item panel-primary toggle  ">
                        	<div class="panel-heading" data-block-id="'.$blockId.'" data-element-id="'.$element->id.'">
                        		<h4>'.$element->title.'</h4>
                        		<div style="display:none" class="pull-left panel-controls-left" data-block-id="'.$blockId.'">
									<a href="#" data-block-id="'.$blockId.'" data-element-id="'.$element->id.'" class="panel-setting "><i class="im-settings"></i></a>
								</div>



							</div>
							<div class="panel-body" data-block-id="'.$blockId.'">

								' . $content . '
							</div>
                    		</div>
                    </div>
				</div>';
        } else
        {
            echo $content;
        }
        die;

    }
    public function ajaxSavePropertiesBlock()
    {
        $app=JFactory::getApplication();
        $form=$app->input->get('jform',array(),'array');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');

        $tablePosition->load($form['id']);
        $tablePosition->bind($form);
        $tablePosition->params=json_encode($form['params']);
        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();
        }
        require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
        $response=new stdClass();
        $blocksCss=websiteHelperFrontEnd::getBlocksCss();
        $website=JFactory::getWebsite();
        $doc=JFactory::getDocument();
        $menu=JMenu::getInstance('site');
        $menuItemActiveId=$menu->getActive()->id;
        jimport('joomla.filesystem.file');
        $fileStyle="style_{$website->website_id}_{$menuItemActiveId}.css";
        JFile::write(JPATH_ROOT.'/layouts/website/css/'.$fileStyle,$blocksCss);
        $doc->addStyleSheetVersion(JUri::root().'/layouts/website/css/'.$fileStyle, rtrim(base64_encode(md5(microtime())),"="));


    }
    public  function ajaxLoadPropertiesBlock()
    {

        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $app->input->set('id',$block_id);
        $modelPosition=JModelLegacy::getInstance('Position','UtilityModel');
        $form=$modelPosition->getForm();
        $options=$form->getFieldsets();
         ob_start();
        ?>
        <div class="properties block">
            <div class="panel-group" id="accordion<?php echo $block_id ?>" role="tablist" aria-multiselectable="true">
                <?php
                foreach($options as $key=>$option)
                {
                    $fieldSet = $form->getFieldset($key);
                ?>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading<?php echo $key ?>">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion<?php echo $block_id ?>" href="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
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
        $contents=ob_get_clean();
        $doc=JFactory::getDocument();
        $respone_array[] = array(
            'key' => '.block-properties .panel-body',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;
    }
    public function ajax_copy_block()
    {
        $app=JFactory::getApplication();
        $copy_object_id=$app->input->get('copy_object_id',0,'int');
        $copy_element_type=$app->input->get('copy_element_type',0,'string');
        $past_object_id=$app->input->get('past_object_id',0,'int');
        $past_element_type=$app->input->get('past_element_type',0,'string');
        UtilityHelper::copy_block($copy_object_id,$past_object_id);
        echo 1;
        die;
    }
    public function ajaxMoveBlock()
    {
        $app=JFactory::getApplication();
        $move_object_id=$app->input->get('move_object_id',0,'int');
        $move_element_type=$app->input->get('move_element_type',0,'string');
        $past_object_id=$app->input->get('past_object_id',0,'int');
        $past_element_type=$app->input->get('past_element_type',0,'string');
        UtilityHelper::moveBlock($move_object_id,$past_object_id);
        echo 1;
        die;
    }
    function aJaxRemoveColumn()
    {
        $app=JFactory::getApplication();
        $post=$app->input->getArray($_POST);
        $columnId=$post['columnId'];
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::removeColumnInScreen($columnId);
        die;

    }
    function aJaxRemoveElement()
    {
        $app=JFactory::getApplication();
        $blockId=$app->input->get('block_id',0,'int');
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::removeBlockInScreen($blockId);
        die;

    }
    function aJaxGetXmlElement()
    {
        $app=JFactory::getApplication();
        $pathXmlElement=$app->input->get('pathXmlElement','','string');
        $id=$app->input->get('id',0,'int');
        $modelPosition=JModelLegacy::getInstance('Position','UtilityModel');
        $item=$modelPosition->getItem($id);
        $form		=$modelPosition->getForm();
        echo "<pre>";
        $fieldSets = $form->getFieldsets('name');
        print_r($form);
        die;

        $xml = simplexml_load_file(JPATH_ROOT.'/'.$pathXmlElement);
        echo "<pre>";
        print_r($xml);
        die;

    }
    function aJaxRemoveRow()
    {
        $app=JFactory::getApplication();
        $post=$app->input->getArray($_POST);
        $rowId=$post['rowId'];
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        UtilityHelper::removeRowInScreen($rowId);
        die;

    }

    function aJaxInsertColumn()
    {
        $app=JFactory::getApplication();
        $post=$app->input->getArray($_POST);
        $parentRowId=$post['parentRowId'];
        $childrenColumnX=$post['childrenColumnX'];
        $childrenColumnY=$post['childrenColumnY'];
        $childrenColumnWidth=$post['childrenColumnWidth'];
        $childrenColumnHeight=$post['childrenColumnHeight'];
        $screenSize=$post['screenSize'];
        $menu_item_id=$post['menuItemActiveId'];
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $newColumnId=UtilityHelper::InsertColumnInScreen($screenSize,$parentRowId,$childrenColumnX,$childrenColumnWidth,$childrenColumnY,$childrenColumnHeight,$menu_item_id);
        echo $newColumnId;
        die;

    }
    function setSectionLanguage()
    {
        $input=JFactory::getApplication()->input;
        $language_id=$input->get('language_id',0,'int');
        $section=JFactory::getSession();
        $section->set('language_id',$language_id);
        die;
    }
    function Google_Service_Pagespeedonline()
    {
        //$url='https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=http://www.baomoi.com/&key=AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw&screenshot=true';
        //$page=JUtility::getCurl($url);
        //$page=json_decode($page);

        //$screenshot=$page->screenshot->data;
        //require_once JPATH_ROOT. '/libraries/google-api-php-client-master/src/Google/Client.php';
        //require_once JPATH_ROOT .'/libraries/google-api-php-client-master/src/Google/Service/Pagespeedonline.php';

       // $client = new Google_Client();
       // $client->setApplicationName('Google Translate PHP Starter Application');

// Visit https://code.google.com/apis/console?api=translate to generate your
// client id, client secret, and to register your redirect uri.
/*        $client->setDeveloperKey('AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw');
        $service = new Google_Service_Pagespeedonline($client);

        $psapi = $service->pagespeedapi;
        $result = $psapi->runpagespeed('http://code.google.com');
        $service->assertArrayHasKey('kind', $result);
*/


        // echo '<img src="' . base64_decode($screenshot) . '"  />';


        require_once JPATH_ROOT.'/libraries/google-api-php-client-master/tests/pagespeed/PageSpeedTest.php';
        $page=new PageSpeedTest();
        $page->testPageSpeed();
        die;
    }
}
