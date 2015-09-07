<?php

class elementTabContentHelper extends elementHelper
{
    function initElement($TablePosition)
    {
        $path = $TablePosition->path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . "/$dirName/$filename.less";
        $cssOutput = JPATH_ROOT . "/$dirName/$filename.css";
        JUtility::compileLess($lessInput, $cssOutput);

    }

    function getHeaderHtml($block, $enableEditWebsite)
    {
        $path = $block->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/elements/ui/divrow.js');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);
        $text = $params->get('text', 'text_' . $block->id);
        $html = '';
        ob_start();
        if ($enableEditWebsite) {

            ?>
            <div class="control-element control-element-tabcontent tab-pane fade" element-type="<?php echo $block->type ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" data-title="<?php echo $block->title ?>" data-tab-title="<?php echo $text ?>" role="tabpanel">

            <div class="block-item block-item-tabcontent enable-create-drop-element" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>"
               element-type="<?php echo $block->type ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="add label label-danger add-row"><i class="glyphicon glyphicon-plus"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="remove label label-danger remove-element" href="javascript:void(0)"><i
                    class="glyphicon-remove glyphicon"></i></a>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_tab_content.init_tab_content();

                });
            </script>

        <?php
        } else {
            ?>

            <div class="tab-pane fade block-item block-item-tabcontent" data-block-id="<?php echo $block->id ?>" data-tab-title="<?php echo $text ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }

    function getFooterHtml($block, $enableEditWebsite)
    {
        $html = '';
        ob_start();
        if ($enableEditWebsite) {

            ?>
            </div>
            </div>
        <?php
        } else {
            ?>
            </div>
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }

    function getDevHtml($TablePosition)
    {
        $html = '';
        ob_start();
        ?>
        <div class="tab-pane fade" role="tabpanel" data-block-id="<?php echo $TablePosition->id ?>"
             data-block-parent-id="<?php echo $TablePosition->parent_id ?>">

        </div>
        <?php
        $html .= ob_get_clean();
        return $html;
    }
}

?>