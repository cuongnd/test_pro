<?php
class elementlocationpickerHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $lessInput = JPATH_ROOT . "/$dirName/$filename.less";
        $cssOutput =  JPATH_ROOT . "/$dirName/$filename.css";
        JUtility::compileLess($lessInput, $cssOutput);

    }
    function getHeaderHtml($block,$enableEditWebsite)
    {

        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addScript(JUri::root().'/media/system/js/gmap3-master/src/google.maps.js');
        $doc->addScript(JUri::root().'/media/system/js/gmap3-master/assets/examples/clusters/mcdo.js');
        $doc->addScript(JUri::root().'/media/system/js/gmap3-master/assets/examples/context-menu/menu/gmap3-menu.js');
        $doc->addStyleSheet(JUri::root().'/media/system/js/gmap3-master/assets/examples/context-menu/menu/gmap3-menu.css');
        $doc->addScript(JUri::root().'/media/system/js/gmap3-master/dist/gmap3.js');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name=$params->get('name','');
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_sortable=$params->get('enable_sortable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','');
        $text=$params->get('text','');
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text=$params->get('data',new stdClass())->text;
        if($data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $max_text=$params->get('max_text',0);
        if($max_text)
        {
            $max_text=JString::sub_string($text,$max_text);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-div item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?> <?php echo $enable_sortable==1?'enabled-sortable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_locationpicker.init_ui_locationpicker();
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>"  data-block-parent-id="<?php echo $block->parent_id ?>" class="add label label-danger add-row"><i class="glyphicon glyphicon-plus"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

           <div id="test" style="width: 100%;height: 400px"></div>




        <?php
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_locationpicker.init_ui_locationpicker();
                });
            </script>
            <div id="test" style="width: 100%; height: 400px"></div>
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    function getFooterHtml($block,$enableEditWebsite)
    {
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            </div>
        <?php
        }else{
            ?>
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }



}
?>