<?php
class elementTextBoxHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();


    }
    function getHeaderHtml($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');


        $label=$params->get('label','');
        $name=$params->get('name','');
        $id=$params->get('id','');
        $class=$params->get('class','');
        $value=$params->get('value','');
        $bindingSource=$params->get('data')->bindingSource;
        if(!$value&&$bindingSource){
            $value=parent::getValueDataSourceByKey($bindingSource);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div style="display: block" class="control-element block-item enable-item-resizable div-column enable-create-drop-element item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <input  value="<?php echo $value ?>"  name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>">


        <?php
        }else{
            ?>
            <input class="col-md-<?php echo $size.' '.$class; ?>" name="<?php echo $name; ?>" id="<?php echo $id; ?>">

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