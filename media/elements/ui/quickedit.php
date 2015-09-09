<?php
class elementQuickEditHelper extends  elementHelper
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
        $css_class=$block->css_class;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $doc->addScript(JUri::root().'/media/system/js/x-editable-master/dist/bootstrap3-editable/js/bootstrap-editable.js');
        $doc->addStyleSheet(JUri::root().'/media/system/js/x-editable-master/dist/bootstrap3-editable/css/bootstrap-editable.css');
        $params = new JRegistry;
        $params->loadString($block->params);

        $text=$params->get('text','text_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $title=$params->get('title','title_'.$block->id);
        $name=$params->get('name','name_'.$block->id);
        $editable=$params->get('editable',true);
        $textSource=$params->get('data')->text;
        if($textSource)
            $text=parent::getValueDataSourceByKey($textSource);
        if(is_object($text)||is_array($text))
        {
            $text="object or array";
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {

                    if(typeof init_ui_quick_edit==="undefined")
                    {
                        var init_ui_quick_edit=element_ui_quick_edit.init_ui_quick_edit();
                    }
                });
            </script>
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

            <a id="<?php echo $id ?>" <?php echo $editable?'editable="true"':'' ?> data-title="<?php echo $title  ?>" class="block-item block-item-quick_edit quick_edit <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>" data-block-parent-id="<?php echo $block->parent_id ?>" ><?php echo $text ?>
        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {

                    if(typeof init_ui_quick_edit==="undefined")
                    {
                        var init_ui_quick_edit=element_ui_quick_edit.init_ui_quick_edit();
                    }
                });
            </script>
            <a id="<?php echo $id ?>" data-title="<?php echo $title  ?>" <?php echo $editable?'editable="true"':'' ?> class="block-item quick_edit <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" ><?php echo $text ?>

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
            </a></div>
        <?php
        }else{
            ?>
        </a>
    <?php
        }
        $html.=ob_get_clean();
        return $html;
    }

}
?>