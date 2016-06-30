<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * component controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminControlleruser extends JControllerForm
{
    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   3.2
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user = JFactory::getUser();
        $userId = $user->get('id');
        // Check general edit permission first.
        if ($user->authorise('core.edit', 'com_supperadmin.component.' . $recordId))
        {
            return true;
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }
    public function ajax_remove_component()
    {
        $app=JFactory::getApplication();
        $action_menu_item_id=$app->input->get('action_menu_item_id',0,'int');
        $current_screen_size_editing=$app->input->get('current_screen_size_editing','','string');
        $block_id=$app->input->get('block_id',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/libraries/legacy/table');
        $table_position=JTable::getInstance('PositionNested','JTable');
        $table_position->load($block_id);
        $data=array(
            'position'=>''
        );
        $table_position->bind($data);
        $response=new stdClass();
        $response->e=0;
        if(!$table_position->store())
        {
            $response->e=1;
            $response->r=$table_position->getError();
        }
        $response->r=JText::_('remove component complated');
        echo json_encode($response);
        die;
    }
    public function ajax_save_field_params()
    {
        $app=JFactory::getApplication();
        $website=JFactory::getWebsite();
        $fields=$app->input->get('fields','','string');
        $element_path=$app->input->get('element_path','','string');
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/supperadmin/com_phpmyadmin/tables/updatetable.php';
        $table_control=new JTableUpdateTable($db,'control');
        $table_control->load(array(
            'element_path'=>$element_path,
            'type'=>'component',
            'website_id'=>$website->website_id
        ));
		
        $table_control->website_id=$website->website_id;
        $table_control->element_path=$element_path;
        $table_control->type='component';
        $table_control->fields=$fields;
        $response=new stdClass();
        $response->e=0;
        if(!$table_control->store())
        {
            $response->e=1;
            $response->r=$table_control->getError();
        }else{
            $response->r="save success";
        }
        echo json_encode($response);
        die;
    }


}
