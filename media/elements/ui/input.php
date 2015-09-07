<?php

class elementInputHelper extends elementHelper
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
        $ajaxGetContent = $app->input->get('ajaxgetcontent', 0, 'int');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/media/system/js/jquery.inputmask-3.x/js/inputmask.js");
        $doc->addScript(JUri::root() . "/media/system/js/jquery.inputmask-3.x/js/jquery.inputmask.js");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");

        $params = new JRegistry;
        $params->loadString($block->params);
        $css_class = $block->css_class;
        $css_class = explode(',', $css_class);
        $css_class = implode(' ', $css_class);
        $group_add_on_left = $params->get('group_add_on_left', '');

        $group_add_on_left = $group_add_on_left == 'none' ? '' : $group_add_on_left;

        $group_add_on_right = $params->get('group_add_on_right', '');
        $group_add_on_right = $group_add_on_right == 'none' ? '' : $group_add_on_right;
        $enable_submit = $params->get('enable_submit', 1);
        $name = $params->get('name', 'name_' . $block->id);
        $id = $params->get('id', 'id_' . $block->id);
        $text = trim($params->get('text', ''));
        $inputmask = trim($params->get('inputmask', ''));

        $placeholder = $params->get('placeholder', '');
        $data_text = $params->get('data')->text;

        if (!$text && $data_text) {
            $text = parent::getValueDataSourceByKey($data_text);
        }
        $html = '';
        ob_start();
        if ($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-input enable-item-resizable  <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <?php
            echo elementInputHelper::render_element($block);
        } else {
            echo elementInputHelper::render_element($block);
        }
        $html .= ob_get_clean();
        return $html;
    }

    public function render_element($block)
    {
        $params = new JRegistry;
        $params->loadString($block->params);
        $css_class = $block->css_class;
        $css_class = explode(',', $css_class);
        $css_class = implode(' ', $css_class);
        $group_add_on_left = $params->get('group_add_on_left', '');
        $group_add_on_left = $group_add_on_left == 'none' ? '' : $group_add_on_left;

        $group_add_on_right = $params->get('group_add_on_right', '');
        $group_add_on_right = $group_add_on_right == 'none' ? '' : $group_add_on_right;
        $enable_submit = $params->get('enable_submit', 1);
        $name = $params->get('name', 'name_' . $block->id);
        $id = $params->get('id', 'id_' . $block->id);
        $text = trim($params->get('text', ''));
        $inputmask = trim($params->get('inputmask', ''));

        $placeholder = $params->get('placeholder', '');
        $data_text = $params->get('data')->text;

        if (!$text && $data_text) {
            $text = parent::getValueDataSourceByKey($data_text);
        }
        ob_start();
        $html = '';
        ?>
        <?php if ($group_add_on_left || $group_add_on_right) { ?>
            <div class="input-group">
            <?php if ($group_add_on_left) { ?>
                <div class="input-group-addon"><i class="<?php echo $group_add_on_left ?>"></i></div>
            <?php } ?>
        <?php } ?>
        <input enable-submit="<?php echo $enable_submit ? 'true' : 'false' ?>" data-inputmask="<?php echo $inputmask ?>" type="text" class="block-item block-item-input form-control <?php echo $css_class ?>" value="<?php echo $text ?>" placeholder="<?php echo $placeholder ?>" name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>
        <?php if ($group_add_on_left || $group_add_on_right) { ?>
            <?php if ($group_add_on_right) { ?>
               <div class="input-group-addon"><i class="<?php echo $group_add_on_right ?>"></i></div>
            <?php } ?>
            </div>
        <?php } ?>
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