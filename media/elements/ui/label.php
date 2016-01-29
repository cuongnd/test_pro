<?php
class elementLabelHelper extends  elementHelper
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
        $list_param=array(
            'group_add_on_left,element_config.group_add_on_left',
            'group_add_on_right,element_config.group_add_on_right',
            'data.text,data.bindingSource',
            'text,element_config.text',
            'name,element_config.name',
            'placeholder,element_config.placeholder',
            'inputmask,element_config.config_update_inputmask'
        );
        parent::merge_param($list_param,$block->id);

        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $css_class=$block->css_class;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-label  enable-item-resizable   item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementLabelHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementLabelHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite){
        $doc=JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $id=$params->get('id','id_'.$block->id);
        $float=$params->get('element_config.float','');
        $text=$params->get('element_config.text','text_'.$block->id);

        $data_text=$params->get('data')->text;
        if($text=='text_'.$block->id&&$data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $ajax_clone=false;
        $random_string='';
        if($this->ajax_clone)
        {
            $ajax_clone=true;
            $random_string=JUserHelper::genRandomPassword();
        }
        $scriptId = "script_ui_label_" . $block->id.$random_string;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-label[data-block-id="<?php echo $block->id ?>"]<?php echo $random_string!=''?'[random-string="'.$random_string.'"]':'' ?>').ui_label({
                    enable_edit_website:<?php echo $enableEditWebsite ?>,
                    block_id:<?php echo $block->id ?>,
                    ajax_clone:<?php echo json_encode($ajax_clone) ?>,
                    float:"<?php echo $float ?>"
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
        <label  id="ui_label_<?php echo $block->id ?>" class="block-item block-item-label control-label" id="<?php echo $id ?>" <?php echo $random_string!=''?'random-string="'.$random_string.'"':'' ?> data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"><?php echo $text ?></label>
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