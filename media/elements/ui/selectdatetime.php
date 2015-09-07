<?php
class elementSelectDateTimeHelper extends  elementHelper
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
        JHtml::_('behavior.calendar');
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $doc->addScript(JUri::root().'/media/jui_front_end/jquery-ui-1.11.1/ui/datepicker.js');
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');
        $is_column=$params->get('is_column',true);

        $label=$params->get('label','');
        $name=$params->get('name','');
        $id=$params->get('id','');
        $class=$params->get('class','');
        $value=$params->get('value','');
        $placeholder=$params->get('placeholder','');
        $bindingSource=$params->get('data')->bindingSource;
        if(!$value&&$bindingSource){
            $value=parent::getValueDataSourceByKey($bindingSource);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-selectdatetime enable-item-resizable <?php echo $is_column?'div-column':'' ?>  item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_select_date_time.init_ui_select_date_time();
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <input class="block-item  block-item-selectdatetime"  type="text"   value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>"  name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>


        <?php
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_select_date_time.init_ui_select_date_time();
                });
            </script>
            <input class="block-item-selectdatetime "  value="<?php echo $value ?>" placeholder=""  name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>

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