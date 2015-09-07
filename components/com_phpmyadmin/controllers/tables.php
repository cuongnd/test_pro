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
class phpMyAdminControllerTables extends PhpmyadminController
{
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
    /**
     * Proxy for getModel
     * @since   1.6
     */
    public function getModel($name = 'Tables', $prefix = 'phpMyAdminModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
    function aJaxInsertTable()
    {
        $app=JFactory::getApplication();
        $table=$app->input->get('table','','string');
        $db=JFactory::getDbo();
        $fields=$db->getTableColumns($table);
        ob_end_clean();
        $respone_bject=new stdClass();
        $respone_bject->fields=$fields;
        echo json_encode($respone_bject);
        die;
    }
    function ajaxGetRemoteTables()
    {

        $modelTable=$this->getModel();
        echo json_encode($modelTable->getRemoteTables());
        die;
    }
    function ajaxGetRemotePropertiesTables()
    {
        $modelTable=$this->getModel();
        echo json_encode($modelTable->getRemotePropertiesTables());
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
    function setSectionLanguage()
    {
        $input=JFactory::getApplication()->input;
        $language_id=$input->get('language_id',0,'int');
        $section=JFactory::getSession();
        $section->set('language_id',$language_id);
        die;
    }
    public function ajax_get_list_table()
    {
        $app=JFactory::getApplication();
        $db=JFactory::getDbo();
        $keyword=$app->input->get('keyword','','string');
        $keyword=str_replace('t__',$db->getPrefix(),$keyword);

        $table=$app->input->get('table','','string');
        if($table)
        {
            $item=new stdClass();
            $item->id=$table;
            $item->text=$table;
            header('Content-Type: application/json');
            echo json_encode(array($item),JSON_NUMERIC_CHECK);
            die;
        }

        $query=' show tables like "%'.$keyword.'%"';
        $db->setQuery($query);
        $list_table=$db->loadColumn();
        $list_result=array();
        $data=new stdClass();
        foreach($list_table as $key=>$table)
        {
             $item=new stdClass();
            $table=str_replace($db->getPrefix(),'',$table);
            $item->id=$table;
            $item->text=$table;
            $list_result[]=$item;
        }
        header('Content-Type: application/json');
        echo json_encode($list_result,JSON_NUMERIC_CHECK);
        die;

    }
    public function ajax_get_list_table_and_field()
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
        $keyword=str_replace('t__',$prefix_table,$keyword);
        $query=' show tables like "%'.$keyword.'%"';
        $db->setQuery($query);
        $list_table=$db->loadColumn();
        foreach($list_table as $key=>$table)
        {
            $result_list_table[$table]=$table;
        }
        foreach($result_list_table as $table)
        {
            $db->redirectPage(false);
            $fields=$db->getTableColumns($table);
            unset($result_list_table[$table]);
            $result_list_table[str_replace($prefix_table,'t__',$table)]=$fields;
        }
        header('Content-Type: application/json');
        echo json_encode($result_list_table,JSON_NUMERIC_CHECK);
        die;

    }
    public function ajax_get_list_flied_table()
    {
        $app=JFactory::getApplication();
        $table=$app->input->get('table_name','','string');

        $keyword=$app->input->get('keyword','','string');
        $field=$app->input->get('field','','string');
        if($field)
        {
            $item=new stdClass();
            $item->id=$field;
            $item->text=$field;
            header('Content-Type: application/json');
            echo json_encode(array($item),JSON_NUMERIC_CHECK);
            die;
        }
        $db=JFactory::getDbo();
        $query=' SHOW COLUMNS FROM #__'.$table.' like "%'.$keyword.'%"';
        $db->setQuery($query);
        $list_column=$db->loadColumn();
        $list_result=array();
        $data=new stdClass();
        foreach($list_column as $key=>$table)
        {
             $item=new stdClass();
            $table=str_replace($db->getPrefix(),'',$table);
            $item->id=$table;
            $item->text=$table;
            $list_result[]=$item;
        }
        header('Content-Type: application/json');
        echo json_encode($list_result,JSON_NUMERIC_CHECK);
        die;

    }
}
