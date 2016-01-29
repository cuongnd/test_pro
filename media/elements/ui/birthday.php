<?php
class elementBirthdayHelper extends  elementHelper
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
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");

        $doc->addLessStyleSheet(JUri::root().'/media/system/js/bootstrap-datetimepicker-master/src/less/bootstrap-datetimepicker-build.less');
        $doc->addScript(JUri::root().'/media/system/js/bootstrap-daterangepicker-master/moment.js');

        $doc->addScript(JUri::root().'/media/system/js/bootstrap-datetimepicker-master/src/js/bootstrap-datetimepicker.js');
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name_from=$params->get('name_from','name_from_'.$block->id);
        $name_to=$params->get('name_to','name_to_'.$block->id);
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','');
        $text_from=$params->get('text_from','');
        $text_to=$params->get('text_to','');
        $enable_submit=$params->get('enable_submit',1);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text_from=$params->get('data',new stdClass())->text_from;
        if($data_text_from){
            $text_from=parent::getValueDataSourceByKey($data_text_from);
        }

        $data_text_to=$params->get('data',new stdClass())->text_to;
        if($data_text_to){
            $text_to=parent::getValueDataSourceByKey($data_text_to);
        }


        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-birthday item_control item_control_<?php echo $block->parent_id ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementBirthdayHelper::render_element($block,$enableEditWebsite);
        }else{
             echo elementBirthdayHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $doc=JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name_from=$params->get('name_from','name_from_'.$block->id);
        $name_to=$params->get('name_to','name_to_'.$block->id);
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','');
        $text_from=$params->get('text_from','');
        $text_to=$params->get('text_to','');
        $enable_submit=$params->get('enable_submit',1);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text_from=$params->get('data',new stdClass())->text_from;
        if($data_text_from){
            $text_from=parent::getValueDataSourceByKey($data_text_from);
        }

        $data_text_to=$params->get('data',new stdClass())->text_to;
        if($data_text_to){
            $text_to=parent::getValueDataSourceByKey($data_text_to);
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
                $('.block-item.block-item-birthday[data-block-id="<?php echo $block->id ?>"]<?php echo $random_string!=''?'[random-string="'.$random_string.'"]':'' ?>').ui_birthday({
                    enable_edit_website:<?php echo $enableEditWebsite ?>,
                    block_id:<?php echo $block->id ?>,
                    ajax_clone:<?php echo json_encode($ajax_clone) ?>,
                });


            });
        </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);


        ob_start();
        $html='';
        ?>

        <div   class="block-item block-item-birthday date input-group"   data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" <?php echo $random_string!=''?'random-string="'.$random_string.'"':'' ?>  element-type="<?php echo $block->type ?>" >
                <input type="text" value="" enable-submit="<?php echo $enable_submit?'true':'false' ?>" class="form-control"      data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>

            </div>
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