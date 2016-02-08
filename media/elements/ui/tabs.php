<?php
class elementTabsHelper extends  elementHelper
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
        $doc->addScript(JUri::root().'/media/system/js/Zozo_Tabs_v.6.5/js/zozo.tabs.js');
        $doc->addStyleSheet(JUri::root().'/media/system/js/Zozo_Tabs_v.6.5/source/zozo.tabs.core.css');
        $doc->addStyleSheet(JUri::root().'/media/system/js/Zozo_Tabs_v.6.5/css/zozo.examples.min.css');
        $doc->addStyleSheet(JUri::root().'/media/system/js/Zozo_Tabs_v.6.5/css/zozo.tabs.min.css');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/media/elements/ui/divrow.js");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $doc->addScript(JUri::root().'/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

        <?php
                echo elementTabsHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementTabsHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $doc=JFactory::getDocument();
        $scriptId = "script_ui_tab_" . $block->id;
        ob_start();
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-tabs[data-block-id="<?php echo $block->id ?>"]').ui_tabs({
                    tabs_option: {

                    },
                    block_id:<?php echo $block->id ?>,
                    enableEditWebsite:<?php echo $enableEditWebsite ?>
                });

            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
        $html='';
        ob_start();
        ?>
        <div class="block-item block-item-tabs" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">

    <?php
        $html=ob_get_clean();
        return $html;
    }
    function getFooterHtml($block,$enableEditWebsite)
    {
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            </div></div>
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