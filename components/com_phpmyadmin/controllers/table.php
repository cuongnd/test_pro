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
class phpMyAdminControllerTable extends PhpmyadminController
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
    public function ajax_render_field_table()
    {
        $app=JFactory::getApplication();
        $db=JFactory::getDbo();
        $table=$app->input->get('table','','string');
        $table='#__'.$table;
        if($table)
        {
            $fields=$db->getTableColumns($table);
            echo json_encode($fields);
        }
        die;
    }
    function aJaxInsertTable()
    {
        $app=JFactory::getApplication();
        $table=$app->input->get('table','','string');

        $db=JFactory::getDbo();
        $fields=$db->getTableKeys($table);
        $fields=JArrayHelper::pivot($fields,'Column_name');
        $fieldTypes=$db->getTableColumns($table);
        $html='';
        ob_start();
        ?>
        <div class="panel panel-primary panel-database-table panelMove toggle  panelRefresh panelClose diagram-item-table" data-table-name="<?php echo $table ?>">
            <!-- Start .panel -->
            <div class="panel-heading panel-heading-database-table" >
                <h4 class=panel-title><?php echo $table ?></h4>
            </div>
            <div class=panel-body>
                <div class="">
                    <div class="list-field" >
                        <?php foreach($fieldTypes as $columnName=>$type){ ?>
                            <div id="<?php echo $table ?>_<?php echo $columnName ?>"  class="item-field" data-table-field="<?php echo $columnName ?>" >
                                <div class="key-type pull-left"><i class="<?php echo strtolower($fields[$columnName]->Key_name)=='primary'?'im-key':'' ?>"></i></div><div class="pull-left column-name"><a><?php echo $columnName?> ( <?php echo substr($type,0,7) ?> )</a></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>



        <?php
        $html.=ob_get_clean();
        echo $html;
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

}
