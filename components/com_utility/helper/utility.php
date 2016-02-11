<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class UtilityHelper
{

    protected  static  $list_position=null;
    public static function  getScreenSize()
    {
        $session=JFactory::getSession();
        $screenSize=$session->get('screenSize');
        return $screenSize;
    }

    public function setScreenSize($screenSize)
    {
        $session=JFactory::getSession();
        $session->set('screenSize',$screenSize);
        return $screenSize;
    }

      static function  getCurrentScreenSizeEditing()
    {
        $session=JFactory::getSession();
        $screenSizeEditing=$session->get('screenSizeEditing');
        if(!$screenSizeEditing)
        {
            $listScreenSize=UtilityHelper::getListScreenSize();
            $screenSizeEditing=reset($listScreenSize);
            UtilityHelper::setCurrentScreenSizeEditing($screenSizeEditing);
        }
        return $screenSizeEditing;
    }
    function setCurrentScreenSizeEditing($screenSizeEditing)
    {
        if(!$screenSizeEditing)
            return;
        $session=JFactory::getSession();
        $session->set('screenSizeEditing',$screenSizeEditing);
        return $screenSizeEditing;
    }

    public function getEditingState()
    {
        $session=JFactory::getSession();
        $editingState=$session->get('editingState');
        if(!$editingState)
            $editingState=UtilityHelper::setEditingState(0);
        return $editingState;
    }

    public function setEditingState($editingState)
    {
        $session=JFactory::getSession();
        $session->set('editingState',$editingState);
        return $editingState;
    }

    public function validate_by_on($list_style){
        $return_style= new stdClass();
        foreach($list_style as $key=>$value)
        {
            $string_enable=substr($key,0, 6);
            if($string_enable=='enable'){
                $push_key=substr($key,7);

                $return_style->$push_key=$list_style->$push_key;
            }
        }
        return $return_style;
    }

    public function get_build_css($main_menu_style_item)
    {
        $main_menu_style_item = base64_decode($main_menu_style_item);
        $registry_item_form = new JRegistry;

        $registry_item_form->loadString($main_menu_style_item);
        jimport('joomla.utilities.objecthelper');
        $list_style = new stdClass();
        JObjectHelper::toObject($registry_item_form->toObject(), $list_style);
        require_once JPATH_ROOT . '/components/com_utility/helper/utility.php';
        $list_style = UtilityHelper::validate_by_on($list_style);
        $list_style_main_menu_style_item =UtilityHelper::build_css($list_style);
        return $list_style_main_menu_style_item;

    }
    public function build_css($item_style)
    {
        $pixel = array(
            'font_size',
            'font_weight',
            'min_width',
            'max_width',
            'width',
            'min_height',
            'max_height',
            'height',
            'left',
            'right',
            'top',
            'bottom',
            'border_top',
            'border-left',
            'border-right',
            'border_bottom',
            'border_radius',
            'padding',
            'padding_top',
            'padding_right',
            'padding_bottom',
            'padding_left',
            'margin',
            'margin_top',
            'margin_right',
            'margin_bottom',
            'margin_left',
            'font_weight',
            'font_variant',
            'line_height',
            'letter_spacing',
            'word_spacing',
            'border_all_radius_width',
            'border_radius_top_left',
            'border_radius_top_right',
            'border_radius_buttom_left',
            'border_radius_buttom_right',
            'border_all_width',
            'border_top_width',
            'border_right_width',
            'border_buttom_width',
            'border_left_width',
            'blur_radius',
            'spread_radius',
            'horizontal_length',
            'vertical_length',
            'text_align'
        );
        $none_hover = new stdClass();
        $hover = new stdClass();
        $item_style1 = new stdClass();
        foreach ($item_style as $key => $value) {
            $item_hover = substr($key, -5);
            if ($item_hover == 'hover') {
                $key1 = substr($key, 0, -6);
            } else {
                $key1 = $key;
            }
            if (in_array($key1, $pixel)) {
                if (is_numeric($value)) {

                    $value = $value . 'px';
                    $item_style1->$key = $value;
                } else {
                    $value = '0px';
                    $item_style1->$key = $value;
                }
            } else {
                $item_style1->$key = $value;
            }
        }
        foreach ($item_style1 as $key => $value) {
            $item_hover = substr($key, -5);
            if ($item_hover == 'hover') {
                $key1 = substr($key, 0, -6);
                $hover->$key1 = $value;
            } else {
                $none_hover->$key = $value;
            }
        }
        //setup style
        //border
        $border_all_width = $none_hover->border_all_width;
        $border_all_style = $none_hover->border_all_style;
        $border_all_color = $none_hover->border_all_color;
        $none_hover->border = "$border_all_width $border_all_style $border_all_color";
        unset($none_hover->border_all_width);
        unset($none_hover->border_all_style);
        unset($none_hover->border_all_color);


        $border_top_width = $none_hover->border_top_width;
        $border_top_style = $none_hover->border_top_style;
        $border_top_color = $none_hover->border_top_color;
        $none_hover->border_top = "$border_top_width $border_top_style $border_top_color";
        unset($none_hover->border_top_width);
        unset($none_hover->border_top_style);
        unset($none_hover->border_top_color);


        $border_right_width = $none_hover->border_right_width;
        $border_right_style = $none_hover->border_right_style;
        $border_right_color = $none_hover->border_right_color;
        $none_hover->border_right = "$border_right_width $border_right_style $border_right_color";
        unset($none_hover->border_right_width);
        unset($none_hover->border_right_style);
        unset($none_hover->border_right_color);

        $border_bottom_width = $none_hover->border_bottom_width;
        $border_bottom_style = $none_hover->border_bottom_style;
        $border_bottom_color = $none_hover->border_bottom_color;
        $none_hover->border_bottom = "$border_bottom_width $border_bottom_style $border_bottom_color";
        unset($none_hover->border_bottom_width);
        unset($none_hover->border_bottom_style);
        unset($none_hover->border_bottom_color);

        $border_left_width = $none_hover->border_left_width;
        $border_left_style = $none_hover->border_left_style;
        $border_left_color = $none_hover->border_left_color;
        $none_hover->border_left = "$border_left_width $border_left_style $border_left_color";
        unset($none_hover->border_left_width);
        unset($none_hover->border_left_style);
        unset($none_hover->border_left_color);


        $border_all_width = $hover->border_all_width;
        $border_all_style = $hover->border_all_style;
        $border_all_color = $hover->border_all_color;
        $hover->border = "$border_all_width $border_all_style $border_all_color";
        unset($hover->border_all_width);
        unset($hover->border_all_style);
        unset($hover->border_all_color);


        //end border


        //boder radius
        $border_all_radius_width = $none_hover->border_all_radius_width;
        $none_hover->border_radius = "$border_all_radius_width";
        unset($none_hover->border_all_radius_width);

        if (!$none_hover->border_radius_top_left) {
            $none_hover->border_radius_top_left = $border_all_radius_width;
        }
        if (!$none_hover->border_radius_top_right) {
            $none_hover->border_radius_top_right = $border_all_radius_width;
        }
        if (!$none_hover->border_radius_buttom_right) {
            $none_hover->border_radius_buttom_right = $border_all_radius_width;
        }
        if (!$none_hover->border_radius_buttom_left) {
            $none_hover->border_radius_buttom_left = $border_all_radius_width;
        }

        $border_radius_top_left = $none_hover->border_radius_top_left;
        unset($none_hover->border_radius_top_left);
        $border_radius_top_right = $none_hover->border_radius_top_right;
        unset($none_hover->border_radius_top_right);
        $border_radius_buttom_left = $none_hover->border_radius_buttom_left;
        unset($none_hover->border_radius_buttom_left);
        $border_radius_buttom_right = $none_hover->border_radius_buttom_right;
        unset($none_hover->border_radius_buttom_right);


        $none_hover->border_radius = "$border_radius_top_left $border_radius_top_right  $border_radius_buttom_right $border_radius_buttom_left";


        $border_all_radius_width = $hover->border_all_radius_width;
        $hover->border_radius = "$border_all_radius_width";
        unset($hover->border_all_radius_width);
        if (!$hover->border_radius_top_left) {
            $hover->border_radius_top_left = $border_all_radius_width;
        }
        if (!$hover->border_radius_top_right) {
            $hover->border_radius_top_right = $border_all_radius_width;
        }
        if (!$hover->border_radius_buttom_right) {
            $hover->border_radius_buttom_right = $border_all_radius_width;
        }
        if (!$hover->border_radius_buttom_left) {
            $hover->border_radius_buttom_left = $border_all_radius_width;
        }


        $border_radius_top_left = $hover->border_radius_top_left;
        unset($hover->border_radius_top_left);
        $border_radius_top_right = $hover->border_radius_top_right;
        unset($hover->border_radius_top_right);
        $border_radius_buttom_left = $hover->border_radius_buttom_left;
        unset($hover->border_radius_buttom_left);
        $border_radius_buttom_right = $hover->border_radius_buttom_right;
        unset($hover->border_radius_buttom_right);


        $hover->border_radius = "$border_radius_top_left $border_radius_top_right  $border_radius_buttom_right $border_radius_buttom_left";


        //end border ridius


        //change _ to -
        foreach ($none_hover as $key => $value) {
            $key1 = str_replace('_', '-', $key);
            $none_hover->$key1 = $value;
        }
        foreach ($none_hover as $key => $value) {
            if (strpos($key, '_') !== false) {
                unset($none_hover->$key);
            }
        }
        foreach ($hover as $key => $value) {
            $key1 = str_replace('_', '-', $key);
            $hover->$key1 = $value;
        }
        foreach ($hover as $key => $value) {
            if (strpos($key, '_') !== false) {
                unset($hover->$key);
            }
        }


        $return_list_style = array(
            'none_hover' => $none_hover,
            'hover' => $hover,
        );
        return $return_list_style;
    }
    public function get_field($field,$node_value,$onchange)
    {
        $path=JPATH_ROOT.'/'.$field->path;
        if(file_exists($path))
        {
            require_once $path;
        }else
        {
            return false;
        }
        $class_name='JFormField'.$field->type;
        $form_field = new $class_name;
        $config_params=$field->config_params;
        $config_params=base64_decode($config_params);
        $config_params=json_decode($config_params);
        $options=array();
        if(count($config_params))
        {
            foreach($config_params as $property)
            {
                $options[]='<option value="'.$property->value.'">'.$property->key.'</option>';
            }
        }
        $options=implode('',$options);
        if($onchange)
        {
            $field->onchange=$onchange;
        }

        $config_property=$field->config_property;

        $config_property=base64_decode($config_property);
        $config_property = (array)up_json_decode($config_property, false, 512, JSON_PARSE_JAVASCRIPT);

        $config_property_params=array();
        if(count($config_property))
        {
            foreach($config_property as $property)
            {
                $config_property_params[]=' '.$property->property_key.'="'.$property->property_value.'" ';
            }
        }
        $config_property_params=implode(' ',$config_property_params);





        $xml_form_field = <<<XML

<field
		name="$field->name"
		type="$field->type"

		onchange="$field->onchange"
		default="$field->default"
		label="$field->label"
		description="$field->description" $config_property_params >

	$options
</field>

XML;
        $xml_form_field = simplexml_load_string($xml_form_field);
        $form_field->setup($xml_form_field,$node_value);
        return $form_field;
    }
    public static function getStatePreview()
    {
        $session=JFactory::getSession();
        $preview=$session->get('preview');
        if(!$preview)
            $preview=UtilityHelper::setEditingState(0);
        return $preview;
    }

    public function setStatePreview($preview)
    {
        $session=JFactory::getSession();
        $session->set('preview',$preview);
        return $preview;
    }
    public static function isAdminSite()
    {
        $uri=JFactory::getURI();
        $host=$uri->getHost();
        $host=strtolower($host);
        $host=str_replace('www.','',$host);
        $admin=substr($host,0,6);
        $admin=strtolower($admin);
        if($admin=='admin.')
        {
            return 1;
        }else
        {
            return 0;
        }
    }
    public static function  getEnableEditWebsite()
    {
        $app=JFactory::getApplication();

        $isAdminSite=UtilityHelper::isAdminSite();
        if(!$isAdminSite)
            return 0;
        $user=JFactory::getUser();
        if($user->id==0)
        {
            return 0;
        }
        return 1;
        $preview=$app->input->get('preview',0,'int');
        if($preview)
        {
            UtilityHelper::setStatePreview($preview);
            return 0;
        }
        else
        {
            UtilityHelper::setStatePreview(0);
        }


        $allow = $user->authorise('core.edit.website', 'com_website.component');

        $session=JFactory::getSession();
        $enableEditingState=$session->get('enableEditingState');
        if(!$enableEditingState)
        {
            $enableEditingState=UtilityHelper::setEnableEditWebsite(1);
        }
        return $enableEditingState;
    }

    public function setEnableEditWebsite($enableEditingState)
    {
        $session=JFactory::getSession();
        $session->set('enableEditingState',$enableEditingState);
        return $enableEditingState;
    }
    public function InsertRowInScreen($screen_size,$parentColumnId,$menu_item_id)
    {

        $db=JFactory::getDbo();
        $website=JFactory::getWebsite();
        if (!$screen_size) {
            $screen_size = UtilityHelper::getScreenSize();
        }
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');
        $tablePosition=JTable::getInstance('positionnested');

        $website=JFactory::getWebsite();

        $tablePosition->website_id=$website->website_id;
        $tablePosition->parent_id=(int)$parentColumnId;
        $tablePosition->menu_item_id=(int)$menu_item_id;
        $tablePosition->type='row';
        $tablePosition->screensize=$screen_size;
        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();
        }
        return $tablePosition->id;
    }
    public function InsertElementInScreen($screenSize,$parentColumnId,$menu_item_id,$type,$pathElement)
    {
        $db=JFactory::getDbo();
        $website=JFactory::getWebsite();
        if (!$screenSize) {
            $screenSize = UtilityHelper::getScreenSize();
        }

        $tablePosition=JTable::getInstance('positionnested');
        $website=JFactory::getWebsite();

        $tablePosition->website_id=$website->website_id;
        $tablePosition->parent_id=(int)$parentColumnId;
        $tablePosition->menu_item_id=(int)$menu_item_id;
        $tablePosition->type=$type;
        $tablePosition->ui_path=$pathElement;
        $tablePosition->screenSize=$screenSize;

        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();
        }
        return $tablePosition;
    }
    public function removeColumnInScreen($columnId)
    {
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($columnId);
        if(!$tablePosition->delete())
        {

            echo $tablePosition->getError();

        }
    }
    public function copy_block( $copy_object_id,$past_object_id)
    {
        $app=JFactory::getApplication();
        $block_id=$app->input->get('block_id',0,'int');
        $element_type=$app->input->get('element_type','','string');
        $modelPosition=$this->getModel();
        $a_listId=array();
        $modelPosition->duplicateBlock($copy_object_id,$a_listId,$past_object_id);
        $getDuplicateBlockId=reset($a_listId);
        $app->input->set('id',$getDuplicateBlockId);
        $this->display();
    }
    public function moveBlock( $move_object_id,$past_object_id)
    {
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($move_object_id);
        $tablePosition->parent_id=$past_object_id;
        if(!$tablePosition->store())
        {
            echo $tablePosition->getError();

        }
    }
    public function removeBlockInScreen($blockId)
    {
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($blockId);
        if(!$tablePosition->delete())
        {

            echo $tablePosition->getError();

        }
    }
    public function removeRowInScreen($rowId)
    {
        $db=JFactory::getDbo();
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');
        $tablePosition=JTable::getInstance('positionnested');
        $website=JFactory::getWebsite();
        $tablePosition->load($rowId);

        if(!$tablePosition->delete())
        {

            echo $tablePosition->getError();

        }

    }
    function updateListBlock($listBlock=array())
    {
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');


        foreach($listBlock as $blockId=>$block)
        {
            $tablePosition=JTable::getInstance('positionnested');
            $tablePosition->load((int)$blockId);
            $tablePosition->gs_x=(int)$block['x'];
            $tablePosition->gs_y=(int)$block['y'];
            $tablePosition->width=(int)$block['width'];
            $tablePosition->height=(int)$block['height'];
            if(!$tablePosition->store())
            {
                return false;
            }
        }
    }
    public function InsertColumnInScreen($screenSize,$parentRowId,$childrenColumnX,$childrenColumnWidth,$childrenColumnY,$childrenColumnHeight,$menu_item_id)
    {
        $db=JFactory::getDbo();
        $website=JFactory::getWebsite();
        if (!$screenSize) {
            $screenSize = UtilityHelper::getScreenSize();
        }
        $screenXY=strtolower($screenSize);
        $screenXY=explode('x',$screenXY);
        $screenX=$screenXY[0];
        $bootstrapColumnType='col-md-';
        $arrayColumnOfScreenSize=array(
            '750'=>array('size'=>750,'columnType'=>'col-sm-'),
            '970'=>array('size'=>970,'columnType'=>'col-md-'),
            '1170'=>array('size'=>1170,'columnType'=>'col-lg-')
        );
        if($screenX<750)
        {
            $bootstrapColumnType='col-xs-';
        }
        else if(750<=$screenX&&$screenX<970)
        {
            $bootstrapColumnType='col-sm-';
        }
        else if(970<=$screenX&&$screenX<1170)
        {
            $bootstrapColumnType='col-md-';
        }
        else
        {
            $bootstrapColumnType='col-lg-';
        }
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables/');
        $tablePosition=JTable::getInstance('positionnested');
        $website=JFactory::getWebsite();
        $tablePosition->website_id=$website->website_id;
        $tablePosition->parent_id=(int)$parentRowId;
        $tablePosition->type='column';
        $tablePosition->screenSize=$screenSize;
        $tablePosition->bootstrap_column_type=$bootstrapColumnType;
        $tablePosition->gs_x=(int)$childrenColumnX;
        $tablePosition->gs_y=(int)$childrenColumnY;
        $tablePosition->width=(int)$childrenColumnWidth;
        $tablePosition->height=(int)$childrenColumnHeight;
        $tablePosition->menu_item_id=(int)$menu_item_id;
        if(!$tablePosition->store())
        {

        }
        return $tablePosition->id;
    }
    public function updateColumnInScreen($columnId,$columnX,$columnWidth,$columnY,$columnHeight)
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__position_config')
            ->set('gs_x = '.(int)$columnX)
            ->set('gs_y = '.(int)$columnY)
            ->set('width = '.(int)$columnWidth)
            ->set('height = '.(int)$columnHeight)
            ->set('website_id = '.(int)$website->website_id)
            ->where('id = '.(int)$columnId)
        ;
        $db->setQuery($query);
        $db->execute();
    }
    public function updateColumnsInScreen($listColumn)
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        foreach($listColumn as $id=> $column) {
            $query = $db->getQuery(true);
            $query->update('#__position_config')
                ->set('gs_x = ' . (int)$column['x'])
                ->set('gs_y = ' . (int)$column['y'])
                ->set('width = ' . (int)$column['width'])
                ->set('height = ' . (int)$column['height'])
                ->set('ordering = ' . (int)$column['ordering'])
                ->set('website_id = ' . (int)$website->website_id)
                ->where('id = ' . (int)$id);
            $db->setQuery($query);
            $db->execute();
        }
    }
    public function updateRowsInScreen($listRow)
    {
        $app=JFactory::getApplication();
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        foreach($listRow as $id=> $row) {
            $query = $db->getQuery(true);
            $query->update('#__position_config')
                ->set('ordering = ' . (int)$row['ordering'])
                ->set('website_id = ' . (int)$website->website_id)
                ->where('id = ' . (int)$id);
            $db->setQuery($query);
            $db->execute();
        }
        $menuItemActiveId=$app->input->get('menuItemActiveId',0);
        foreach($listRow as $id=> $row) {
            $query = $db->getQuery(true);
            $query->delete('#__menu_item_id_position_id_ordering')
                ->where('menu_item_id = ' . (int)$menuItemActiveId)
                ->where('position_id = ' . (int)$id)
            ;
            $db->setQuery($query);
            $db->execute();
            $query->clear()
                ->insert('#__menu_item_id_position_id_ordering')
                ->set('menu_item_id = ' . (int)$menuItemActiveId)
                ->set('position_id = ' . (int)$id)
                ->set('website_id = ' . (int)$website->website_id)
                ->set('ordering='.(int)$row['ordering'])
            ;
            $db->setQuery($query);
            $db->execute();
        }
    }
    public function updateElementInScreen($listElement)
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        foreach($listElement as $id=> $element) {
            $query = $db->getQuery(true);
            $query->update('#__position_config')
                ->set('ordering = ' . (int)$element['ordering'])
                ->set('website_id = ' . (int)$website->website_id)
                ->where('id = ' . (int)$id);
            $db->setQuery($query);
            $db->execute();
        }
    }
    public static function getListPositions()
    {
        $listPositions = array(
            'header1'
            ,'header2'
            ,'header3'
            ,'header4'
            ,'header5'
            ,'header6'
            ,'header7'
            ,'header8'
            , 'top1'
            , 'top2'
            , 'top3'
            , 'top4'
            , 'top5'
            , 'top6'
            , 'top7'
            , 'top8'
            , 'breadcrumb'
            , 'left1'
            , 'left2'
            , 'left3'
            , 'left4'
            , 'left5'
            , 'left6'
            , 'left7'
            , 'left8'
            , 'right1'
            , 'right2'
            , 'right3'
            , 'right4'
            , 'right5'
            , 'right6'
            , 'right7'
            , 'right8'
            , 'banner1'
            , 'banner2'
            , 'banner3'
            , 'banner4'
            , 'banner5'
            , 'banner6'
            , 'banner7'
            , 'banner8'
            , 'bottom1'
            , 'bottom2'
            , 'bottom3'
            , 'bottom4'
            , 'bottom5'
            , 'bottom6'
            , 'bottom7'
            , 'bottom8'
            , 'footer1'
            , 'footer2'
            , 'footer3'
            , 'footer4'
            , 'footer5'
            , 'footer6'
            , 'footer7'
            , 'footer8'
            , 'user1'
            , 'user2'
            , 'user3'
            , 'user4'
            , 'user5'
            , 'user6'
            , 'user7'
            , 'user8'
            ,'component-position'
        );
        return $listPositions;
    }
    public function getListPositionsSetting($screenSize='',$menuItemActiveId=0)
    {

        $screenSize=strtolower($screenSize);
        $app=JFactory::getApplication();
        $client_id=$app->getClientId();
        $menu=$app->getMenu();
        $menuItemActiveId=$menuItemActiveId?$menuItemActiveId:$menu->getActive()->id;
        $db=JFactory::getDbo();
        $website=JFactory::getWebsite();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__position_config AS poscon')
            ->where($screenSize?'LOWER(poscon.screensize)='.$query->q(strtolower($screenSize)):'1=1')
            ->where('client_id='.(int)$client_id)
            ->where('id!=parent_id')
            ->where('menu_item_id='.(int)$menuItemActiveId)
            ->where('poscon.website_id='.(int)$website->website_id)
            ->order('ordering')
        ;
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public function removePositionOnlyPage(&$listPositionsSetting,$position_parent_id=0)
    {
        if ($position_parent_id == 0) {
            $list_position_deleted = array();
            foreach ($listPositionsSetting as $key => $position) {
                if ($position->only_page==1) {
                    unset($listPositionsSetting[$key]);
                    $list_position_deleted[] = $position->id;

                }
            }
            foreach ($list_position_deleted as $position_parent_id) {
                UtilityHelper::removePositionOnlyPage($listPositionsSetting, $position_parent_id);
            }
        } else {
            $list_position_children_deleted = array();
            foreach ($listPositionsSetting as $key => $position) {
                if ($position->only_page && $position->parent_id == $position_parent_id) {
                    unset($listPositionsSetting[$key]);
                    $list_position_children_deleted[] = $position->id;
                }
            }
            foreach ($list_position_children_deleted as $position_parent_id) {
                UtilityHelper::removePositionOnlyPage($listPositionsSetting, $position_parent_id);
            }
        }
    }
    public function remove_position_is_template(&$listPositionsSetting,$position_parent_id=0)
    {
        if ($position_parent_id == 0) {
            $list_position_deleted = array();
            foreach ($listPositionsSetting as $key => $position) {
                if ($position->is_template==1) {
                    unset($listPositionsSetting[$key]);
                    $list_position_deleted[] = $position->id;

                }
            }
            foreach ($list_position_deleted as $position_parent_id) {
                UtilityHelper::remove_position_is_template($listPositionsSetting, $position_parent_id);
            }
        } else {
            $list_position_children_deleted = array();
            foreach ($listPositionsSetting as $key => $position) {
                if ($position->is_template==1 && $position->parent_id == $position_parent_id) {
                    unset($listPositionsSetting[$key]);
                    $list_position_children_deleted[] = $position->id;
                }
            }
            foreach ($list_position_children_deleted as $position_parent_id) {
                UtilityHelper::remove_position_is_template($listPositionsSetting, $position_parent_id);
            }
        }
    }
    public function getPositionByPage($enableEditWebsite=1)
    {

        $app=JFactory::getApplication();
        if ($enableEditWebsite) {
            $currentScreenSize = UtilityHelper::getCurrentScreenSizeEditing();
        } else {
            $currentScreenSize = UtilityHelper::getScreenSize();
            $currentScreenSize="1280X768";
        }
        $os = $app->input->get('os', '', 'String');
        if($os!='')
        {
            $currentScreenSize=$app->input->get('screenSize','');
        }

        if(!$currentScreenSize)
        {
            $currentScreenSize= $app->input->get('screenSize','','string');
            UtilityHelper::setScreenSize($currentScreenSize);
        }
        $currentScreenSize = UtilityHelper::getSelectScreenSize($currentScreenSize);
        //$listPositionsSetting=UtilityHelper::getListPositionsSetting($currentScreenSize);
        $menu=$app->getMenu();
        $menuItemActive=$menu->getActive()?$menu->getActive():$menu->getDefault();

        $website=JFactory::getWebsite();
        $params=$menuItemActive->params;
        if(!$params)
        {
            throw new Exception(JText::_('the are no active menu'), 404);

        }
        $use_main_frame=$params->get('use_main_frame',0);
        $listPositionsSetting=array();
        $rebuid=$app->input->get('rebuid',0,'int');
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->webisite_id=$website->website_id;
        $parentId = $tablePosition->getRootId();
        $tablePosition->load($parentId);
        $db=JFactory::getDbo();
        if(!$enableEditWebsite) {
            //set position config exists
            $query = $db->getQuery(true);
            $query->select('poscon.screensize')
                ->from('#__position_config AS poscon')
                ->where('lft>' . (int)$tablePosition->lft . ' AND  rgt<' . (int)$tablePosition->rgt)
                ->group('poscon.screensize');
            if ($use_main_frame) {
                $query->where('poscon.menu_item_id=' . (int)$menuItemActive->id . ' OR poscon.menu_item_id=' . $use_main_frame);
            } else {
                $query->where('poscon.menu_item_id=' . (int)$menuItemActive->id);
            }


            $a_list_position_config = $db->setQuery($query)->loadColumn();
            $currentScreenSize = UtilityHelper::getSelectScreenSize($currentScreenSize, $a_list_position_config);
            //end set position config exists
        }


        if($rebuid||$tablePosition->rgt==0)
        {
            $tablePosition->rebuild();
        }

        $query=$db->getQuery(true);
        $query->select('poscon.*')
            ->select('(CASE WHEN menu_item_id_position_id_ordering.ordering!=0 THEN menu_item_id_position_id_ordering.ordering ELSE poscon.ordering END) AS ordering ')
            ->from('#__position_config AS poscon')
            ->where('lft>'.(int)$tablePosition->lft.' AND  rgt<'.(int)$tablePosition->rgt)
            ->leftJoin('#__menu_item_id_position_id_ordering AS menu_item_id_position_id_ordering ON menu_item_id_position_id_ordering.position_id=poscon.id AND menu_item_id_position_id_ordering.menu_item_id='.(int)$menuItemActive->id)
            ->where('LOWER(poscon.screensize)='.$query->q(strtolower($currentScreenSize)))
            ->order('ordering')


        ;
        if($use_main_frame)
        {
            $query->where('poscon.menu_item_id='.(int)$menuItemActive->id.' OR poscon.menu_item_id='.$use_main_frame);
        }else{
            $query->where('poscon.menu_item_id='.(int)$menuItemActive->id);
        }
        $listPositionsSetting=$db->setQuery($query)->loadObjectList();
        //UtilityHelper::getListPositionsSetting2('',$use_main_frame,$menuItemActive->id,$listPositionsSetting,0,1,9999);
        if($use_main_frame)
        {
            UtilityHelper::removePositionOnlyPage($listPositionsSetting,0);
            UtilityHelper::removePositionOtherPage($use_main_frame,$menuItemActive->id,$listPositionsSetting,0,1,9999);

        }
        if(!$enableEditWebsite)
        {
            UtilityHelper::remove_position_is_template($listPositionsSetting,0);
        }

        return  $listPositionsSetting;
    }
    public function removePositionOtherPage($use_main_frame,$menuActiveId,&$listPositionsSetting,$positionId=0,$level,$maxLevel)
    {
        if($level<$maxLevel)
        {
            if(!$positionId)
            {

                foreach($listPositionsSetting as $position)
                {


                    if($position->menu_item_id!=$use_main_frame&&$position->menu_item_id!=$menuActiveId)
                    {

                        UtilityHelper::removePositionOtherPage($use_main_frame,$menuActiveId,$listPositionsSetting,$position->id,$level,$maxLevel);
                    }
                }
            }else
            {
                foreach($listPositionsSetting as $key=> $position)
                {

                    if($position->id==$positionId){
                        unset($listPositionsSetting[$key]);
                    }
                    if($position->parent_id==$positionId)
                    {
                        UtilityHelper::removePositionOtherPage($use_main_frame,$menuActiveId,$listPositionsSetting,$position->id,$level+1,$maxLevel);
                    }
                }
            }
        }else
        {
            echo $level;
            die;
        }
    }
    public function getListPositionsSetting2($screenSize='',$menuItemActiveId=0,$menuItemId2=0,&$listPosition=array(),$block_parent_id=0,$level=1,$maxLevel=9999)
    {
        if($level<$maxLevel) {
            $app = JFactory::getApplication();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('poscon.*');
            $query->from('#__position_config AS poscon');
            if ($level == 1) {
                $screenSize = strtolower($screenSize);
                $client_id = $app->getClientId();
                $menu = $app->getMenu();
                $menuItemActiveId = $menuItemActiveId ? $menuItemActiveId : $menu->getActive()->id;
                $website = JFactory::getWebsite();
                $query->where($screenSize!='' ? 'LOWER(poscon.screensize)=' . $query->q(strtolower($screenSize)) : '1=1')
                    ->where('client_id=' . (int)$client_id)
                    ->where('id=parent_id')
                    ->where('poscon.website_id=' . (int)$website->website_id)
                    ;

            }elseif($level==2){
                $query->where('((poscon.menu_item_id=' . (int)$menuItemActiveId .' AND poscon.only_page=0 ) OR poscon.menu_item_id='.(int)$menuItemId2.')');
                $query->where('poscon.parent_id=' . (int)$block_parent_id);
            }
            else {
                $query->where('poscon.parent_id=' . (int)$block_parent_id);
            }
            $query->order('poscon.ordering');
            $query->group('poscon.id');
/*            if($level==1)
            {
                echo $query->dump();
                die;
            }*/
            $db->setQuery($query);
            $list = $db->loadObjectList();
            foreach ($list as $position) {
                if($level>1){
                    $listPosition[] = $position;
                }
                UtilityHelper::getListPositionsSetting2($screenSize, $menuItemActiveId,$menuItemId2, $listPosition, $position->id, $level + 1, $maxLevel);
            }
        }

    }
        public static function getListScreenSize()
    {
        $listScreenSize=array(
            '480X320'
            ,'800X480'
            ,'854X480'
            ,'960X640'
            ,'1136X640'
            ,'1280X768'
            ,'1920X1080'
        );
        return $listScreenSize;
    }
    public function getSelectScreenSize($currentScreenSize='',$listScreenSize=array())
    {
        if(!$currentScreenSize)
        {
            $currentScreenSize=UtilityHelper::getScreenSize();
        }
        if(empty($listScreenSize))
        {
            $listScreenSize=UtilityHelper::getListScreenSize();
        }
        $listScreenX=array();
        for($i=0;$i<count($listScreenSize);$i++)
        {
            $screenSize=$listScreenSize[$i];
            $screenSize=strtolower($screenSize);
            $screenSize=explode('x',$screenSize);
            $screenX=$screenSize[0];
            $item=new stdClass();
            $item->x=$screenX;
            $item->index=$i;
            $listScreenX[$screenX]=$item;

        }
        ksort($listScreenX);
        $currentScreenSize=strtolower($currentScreenSize);
        $currentScreenSize=explode('x',$currentScreenSize);
        $currentScreenSizeX=$currentScreenSize[0];
        $availableScreenX=$currentScreenSize[0];
        $availableScreen=0;
        $prevKey=0;
        foreach($listScreenX as $key=>$screenX)
        {

            if($availableScreenX<$key)
            {
                $prevItem=$listScreenX[$prevKey];
                if($prevItem) {
                    $availableScreen = $listScreenSize[$prevItem->index];
                }
                else
                {
                    $availableScreen = $listScreenSize[0];
                }
                break;
            }
            $prevKey=$key;
        }
        if($availableScreen==0)
        {
            $lastScreenX=end($listScreenX);
            $availableScreen=$listScreenSize[$lastScreenX->index];
        }
        return $availableScreen;
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
    public function aJaxChangeScreenSize()
    {
        $app=JFactory::getApplication();
        $session=JFactory::getSession();
        $screenSize=$app->input->get('screenSize','');
        if($screenSize)
        {
            $session->set('screenSize',$screenSize);
        }
        die;
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
