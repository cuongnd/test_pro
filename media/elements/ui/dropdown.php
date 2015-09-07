<?php
class elementDropdownHelper extends  elementHelper
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
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() ."/media/elements/ui/dropdown.js");
        $params = new JRegistry;
        $params->loadString($block->params);
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $size=$params->get('size','');

        $name=$params->get('name','name_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $text=trim($params->get('text',''));

        $placeholder=$params->get('placeholder','');
        $data_text=$params->get('data')->text;
        $icon=$params->get('icon','br-refresh');
        if(!$text&&$data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-dropdown   <?php echo $css_class ?>"   data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_dropdown.init_ui_dropdown();
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="block-content" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
                <a class="block-item block-item-dropdown  a_drop_down" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" href="javascript:void(0)"  type="button">
                    <i  class="<?php echo $icon ?>   <?php echo $css_class ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"></i>
                </a>
                <div class="control-element block-item block-item-dropdown view_item_drop_down" style="display: none" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
                    <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default move-dropdown  ui-draggable-handle"><i class="glyphicon glyphicon-move "></i></span>
                    <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                    <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>"  data-block-parent-id="<?php echo $block->parent_id ?>" class="add label label-danger add-row" onclick="element_ui_dropdown.add_row(this)"><i class="glyphicon glyphicon-plus"></i></a>
                    <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
                    <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="hide-element-dropdow" href="javascript:void(0)"><i class="im-exit"></i></a>




        <?php
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_dropdown.init_ui_dropdown();
                });
            </script>
            <a class="dropdown-toggle dropdown" data-block-id="<?php echo $block->id ?>" data-toggle="dropdown" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" href="javascript:void(0)"  type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                <i  class="<?php echo $icon ?> block-item block-item-dropdown  <?php echo $css_class ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"></i>
            </a>
            <div class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                sdfsdfsdfds


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
                </div>
            </div>
        <?php
        }else{
            ?>
            </div>
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }



}
?>