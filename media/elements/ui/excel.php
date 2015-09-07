<?php

class elementexcelHelper extends elementHelper
{
    function initElement($TablePosition)
    {
        $path = $TablePosition->ui_path;
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
        $app = JFactory::getApplication();
        $path = $block->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/handsontable-master/dist/handsontable.full.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/handsontable-ruleJS-master/src/handsontable.formula.css");

        $doc->addScript(JUri::root() . '/media/system/js/handsontable-master/dist/handsontable.full.js');


        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/lodash/dist/lodash.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/underscore.string/lib/underscore.string.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/bower_components/moment/moment.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/numeral/numeral.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/numericjs/numeric.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/js-md5/js/md5.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/jstat/dist/jstat.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/lib/formulajs/lib/formula.js');


        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/bower_components/ruleJS/dist/js/parser.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/bower_components/ruleJS/dist/js/ruleJS.js');
        $doc->addScript(JUri::root() . '/media/system/js/handsontable-ruleJS-master/src/handsontable.formula.js');



        $params = new JRegistry;
        $params->loadString($block->params);

        $size = $params->get('size', '');

        $label = $params->get('label', '');
        $name_from = $params->get('name_from', 'name_from_' . $block->id);
        $name_to = $params->get('name_to', 'name_to_' . $block->id);
        $enable_droppable = $params->get('enable_droppable', 0);
        $enable_resizable_for_control = $params->get('enable_resizable_for_control', 0);
        $id = $params->get('id', '');
        $text_from = $params->get('text_from', '');
        $text_to = $params->get('text_to', '');
        $enable_submit = $params->get('enable_submit', 1);
        $placeholder = $params->get('placeholder', 'placeholder_' . $block->id);
        $data_text_from = $params->get('data', new stdClass())->text_from;
        if ($data_text_from) {
            $text_from = parent::getValueDataSourceByKey($data_text_from);
        }

        $data_text_to = $params->get('data', new stdClass())->text_to;
        if ($data_text_to) {
            $text_to = parent::getValueDataSourceByKey($data_text_to);
        }
        $html = '';
        ob_start();
        if ($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-excell item_control item_control_<?php echo $block->parent_id ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i
                    class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="remove label label-danger remove-element" href="javascript:void(0)"><i
                    class="glyphicon-remove glyphicon"></i></a>
            <?php echo elementexcelHelper::render_element($block); ?>
        <?php
        } else {
            ?>
            <?php echo elementexcelHelper::render_element($block); ?>
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }

    public function render_element($block)
    {

        $doc = JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $name = $params->get('name', 'name_' . $block->id);
        $header_column = $params->get('header_column', array());
        $data_header_column = $params->get('data', new stdClass())->header_column;
        require_once(JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php');
        $table_position_name = '#__position_config';
        $app = JFactory::getApplication();
        $binding_source = $params->get('data.bindingSource','');
        if ($binding_source) {
            $binding_source = parent::getValueDataSourceByKey($binding_source);
        }
        $html='';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var afterChange = function (changes, source) {
                    if (source == 'edit') {
                        var self = this;
                        var rootElement = self.rootElement;
                        var data = self.getData();
                        console.log(data);
                        data = JSON.stringify(data);
                        data = $.base64.encode(data);
                        var excel_input_hidden = $(rootElement).find('.block-item.block-item-excel-input-hidden');
                        excel_input_hidden.val(data);
                    }
                };
                excel_block[<?php echo $block->id ?>].pluginHookBucket.afterChange.push(afterChange);
            });
        </script>

        <div class="block-item block-item-excel " id="block_item_excel_<?php echo $block->id ?>"
             data-block-id="<?php echo $block->id ?>"
             data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <input type="hidden" class="block-item block-item-excel-input-hidden " name="<?php echo $name ?>"
                   value="<?php echo base64_encode(json_encode($binding_source)) ?>" data-block-id="<?php echo $block->id ?>"
                   data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
        </div>
        <?php
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
        <?php
        } else {
            ?>
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }


}

?>