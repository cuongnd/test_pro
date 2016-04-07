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
    }

    function getHeaderHtml($block, $enableEditWebsite)
    {
        $path = $block->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'/media/elements/ui/tabcontent.less');
        if($enableEditWebsite)
            $doc->addScript(JUri::root() . '/media/elements/ui/divrow.js');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);
        $text = $params->get('element_config.title_tab_content', 'text_' . $block->id);
        $html = '';
        ob_start();
        if ($enableEditWebsite) {

            ?>
            <div class="control-element control-element-tabcontent tab-pane "  element-type="<?php echo $block->type ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" data-title="<?php echo $block->title ?>" data-tab-title="<?php echo $text ?>" role="tabpanel">


        <?php
            echo elementTabContentHelper::render_element($block, $enableEditWebsite);
        } else {
            echo elementTabContentHelper::render_element($block, $enableEditWebsite);
        }
        $html .= ob_get_clean();
        return $html;
    }
    public function render_element($block, $enableEditWebsite)
    {
        $params = new JRegistry;
        $params->loadString($block->params);
        $text = $params->get('element_config.title_tab_content', 'text_' . $block->id);
        $doc=JFactory::getDocument();
        $scriptId = "script_ui_tabcontent_" . $block->id;
        ob_start();
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-tabcontent[data-block-id="<?php echo $block->id ?>"]').ui_tabcontent({
                    tabcontent_option: {

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
    <div class="block-item block-item-tabcontent enable-create-drop-element ui_tabcontent" data-tab-title="<?php echo $text ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
    <?php if($enableEditWebsite){ ?>
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
    <?php } ?>



        <?php
        $html=ob_get_clean();
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