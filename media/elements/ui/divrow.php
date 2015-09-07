<?php
class elementDivRowHelper extends  elementHelper
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
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $params = new JRegistry;
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $params->loadString($block->params);
        $resizable=$params->get('resizable',0);
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element block-item block-item-row row div-row enable-create-drop-element <?php echo $css_class ?>" resizable="<?php echo $resizable?'true':'false'?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_div_row.init_div_row();


                });
            </script>
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle "><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>


        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_div_row.init_div_row();
                });
            </script>
            <div class="row block-item block-item-row <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >

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
        </div>
    <?php
        }
        $html.=ob_get_clean();
        return $html;
    }

}
?>