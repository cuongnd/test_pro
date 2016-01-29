<?php

class elementSelectHelper extends elementHelper
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
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");

        $params = new JRegistry;
        $params->loadString($block->params);

        $html = '';
        ob_start();
        if ($enableEditWebsite) {
            ?>
            <div style="display: block" class="control-element enable-item-resizable  item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i
                    class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="remove label label-danger remove-element" href="javascript:void(0)"><i
                    class="glyphicon-remove glyphicon"></i></a>
            <?php echo elementSelectHelper::render_element($block); ?>


        <?php
        } else {
            ?>
            <?php echo elementSelectHelper::render_element($block); ?>
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }

    public function render_element($block)
    {
        $params = new JRegistry;
        $params->loadString($block->params);
        $size = $params->get('size', '');
        $doc=JFactory::getDocument();

        $label = $params->get('label', '');
        $name = $params->get('element.name', 'name_' . $block->id);
        $id = $params->get('id', '');
        $class = $params->get('class', '');



        $on_change_by_code_php=$params->get('on_change_by_code_php',0);
        $on_change='';
        if($on_change_by_code_php==1) {
            $file_php = JPATH_ROOT . '/cache/event_on_change_select_' . $block->id . '.php';
            $on_change = JUtility::get_content_file($block, $file_php, parent::$table_position_name, 'params.on_change');
        }





        $enable_select2 = $params->get('element.enable_select2', 1);
        $enable_select2=JUtility::toStrictBoolean($enable_select2);
        $confirm = $params->get('confirm', 0);
        $enable_submit = $params->get('enable_submit', 1);
        $placeholder = $params->get('placeholder', 'placeholder_' . $block->id);
        $bindingSource = $params->get('data')->bindingSource;
        $key = $params->get('data')->key;
        $key = $key ? $key : 'id';
        $value = $params->get('data')->value;
        $value = $value ? $value : 'title';

        $data_value_selected = $params->get('data')->value_selected;


        if ($data_value_selected) {
            $data_value_selected = parent::getValueDataSourceByKey($data_value_selected);
        }
        $items = $params->get('items', '');
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $items = up_json_decode($items, false, 512, JSON_PARSE_JAVASCRIPT);
        ob_start();
        elementSelectHelper::create_html_select($items,$data_value_selected);
        $text_items=ob_get_clean();

        if (!$items && $bindingSource) {

            $items = parent::getValueDataSourceByKey($bindingSource);
        }
        $list_column=array();
        $use_selected_grid = $params->get('use_selected_grid', 0);
        if($use_selected_grid==1)
        {
            require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
            $gridselected = $params->get('gridselected', '');
            if($gridselected!='')
            {
                $gridselected = up_json_decode($gridselected, false, 512, JSON_PARSE_JAVASCRIPT);

                if (!empty($gridselected)) {
                    foreach ($gridselected as $key => $ob_value) {
                        $column=new stdClass();
                        if ($ob_value->template != '') {
                            $column->template=$ob_value->template;
                        } else {
                            $column->template='#:'.$ob_value->column_name.'#';
                        }
                        $list_column[] = $column;

                    }
                }



            }
        }
        $required=$params->get('element_config.element_required.required',false);

        $required=JUtility::toStrictBoolean($required);
        $required_message=$params->get('element_config.element_required.message','This field is required');
        $ajax_clone=false;
        $random_string='';
        if(parent::check_ajax_clone()==1)
        {
            $ajax_clone=true;
            $random_string=JUserHelper::genRandomPassword();
        }

        $scriptId = "ui_select_".$block->id.$random_string;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $(document).on('change','select.block-item.block-item-select[data-block-id="<?php echo $block->id ?>"]',function(){
                    var lastRole = $(this).data('lastValue');
                    var newRole = $(this).val();
                    <?php
                        if($confirm==1){
                        $confirm_msg = $params->get('confirm_msg', "are your sure change ?");
                        ?>
                    if (!confirm("<?php echo $confirm_msg ?>")) {
                        console.log(lastRole);
                        $(this).val(lastRole); //set back
                        return;                  //abort!
                    }
                    <?php
                    }
                ?>
                    <?php echo $on_change_by_code_php==1?$on_change:'' ?>
                });

                $('select.block-item.block-item-select[data-block-id="<?php echo $block->id ?>"]<?php echo $random_string!=''?'[random-string="'.$random_string.'"]':'' ?>').ui_select({
                    enable_select2:<?php echo  $enable_select2?true:false ?>
                });

            });
        </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);

    $group_add_on_left = $params->get('element.group_add_on_left', '');
    $group_add_on_left = $group_add_on_left == 'none' ? '' : $group_add_on_left;

    $group_add_on_right = $params->get('element.group_add_on_right', '');
    $group_add_on_right = $group_add_on_right == 'none' ? '' : $group_add_on_right;


    $html = '';

        ob_start();
        ?>

        <div class="wapper_select" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
            <?php if ($group_add_on_left || $group_add_on_right) { ?>
                <div class="input-group block-item-select">
                    <?php if ($group_add_on_left) { ?>
                        <div class="input-group-addon"><i class="<?php echo $group_add_on_left ?>"></i></div>
                    <?php } ?>
                    <?php } ?>
                    <select    enable-submit="<?php echo $enable_submit ? 'true' : 'false' ?>"
                             disableChosen="true"
                             class="block-item block-item-select" name="<?php echo $name ?>"
                             data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                             id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>" <?php echo $random_string!=''?'random-string="'.$random_string.'"':'' ?> >
                        <?php if ($bindingSource) { ?>
                            <?php foreach ($items as $item): ?>
                                <option <?php echo $data_value_selected == $item->$key ? 'selected' : '' ?>
                                    value="<?php echo $item->$key ?>"><?php echo $item->$value ?></option>
                            <?php endforeach ?>
                        <?php } else {
                            echo $text_items;
                        } ?>
                    </select>

                    <?php if ($group_add_on_left || $group_add_on_right) { ?>
                    <?php if ($group_add_on_right) { ?>
                        <div class="input-group-addon"><i class="<?php echo $group_add_on_right ?>"></i></div>
                    <?php } ?>
                </div>
            <?php } ?>
    </div>








        <?php
        $html = ob_get_clean();
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
    function create_html_select($nodes,$selected,$level=1)
    {
        foreach ($nodes as $column) {
            $childNodes = $column->children;
            ob_start();
            echo '<option '.($selected==$column->key?'selected':'').'   value="'.$column->key.'">'.$column->value;
            echo ob_get_clean();
            if (is_array($childNodes)&& count($childNodes) > 0) {
                echo '<optgroup>';
                $level=$level+1;
                elementSelectHelper::create_html_select($childNodes,$selected,$level);
                echo '</optgroup>';
            }
            echo "</option>";
        }
    }


}

?>