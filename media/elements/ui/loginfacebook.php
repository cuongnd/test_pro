<?php
class elementLoginFacebookHelper extends  elementHelper
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
            'link_to_page,element_config.link_to_page',
            'LoginFacebook_type,element_config.LoginFacebook_type',
            'method_submit,element_config.method_submit',
            'text,element_config.text',
            'data.text,data.bindingSource',
            'placeholder,element_config.placeholder',
        );
        parent::merge_param($list_param,$block->id);


        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();

        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $link_to_page=$params->get('element_config.link_to_page',0);
        $name=$params->get('name','');
        $id=$params->get('id','');
        $LoginFacebook_type=$params->get('element_config.LoginFacebook_type','submit');
        $method_submit=$params->get('element_config.method_submit','get');
        $is_booking=$params->get('is_booking',1);
        $text=$params->get('text','text_'.$block->id);
        $placeholder=$params->get('element_config.placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data.bindingSource');
        if(!$text&&$bindingSource){
            $text=parent::getValueDataSourceByKey($bindingSource);
        }
        $buttom_position=$params->get('element_config.buttom_position','');
        if($buttom_position=='right')
        {
            $pull_right='pull-right';
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-loginfacebook enable-item-resizable <?php echo $pull_right ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementLoginFacebookHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementLoginFacebookHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $doc=JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $menu=$app->getMenu();
        $active_menu_item=$menu->getActive();
        $size=$params->get('size','');
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $link_to_page=$params->get('element_config.link_to_page',0);
        $name=$params->get('name','');
        $id=$params->get('id','');
        $LoginFacebook_type=$params->get('element_config.LoginFacebook_type','submit');
        //LoginFacebook state
        $LoginFacebook_state=$params->get('LoginFacebookstate','');
        $method_submit=$params->get('element_config.method_submit','get');
        $is_booking=$params->get('is_booking',1);
        $text=$params->get('element_config.text','text_'.$block->id);
        $placeholder=$params->get('element_config.placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data.bindingSource');
        if(!$text&&$bindingSource){
            $text=parent::getValueDataSourceByKey($bindingSource);
        }
        $buttom_position=$params->get('element_config.buttom_position','');
        if($buttom_position=='right')
        {
            $pull_right='pull-right';
        }


        $icon_left = $params->get('element_config.icon_left', '');
        $icon_left = $icon_left == 'none' ? '' : $icon_left;
        $icon_right = $params->get('element_config.icon_right', '');
        $icon_right = $icon_right == 'none' ? '' : $icon_right;
        //link to page
        $link_to_page=$params->get('element_config.link_to_page',$active_menu_item->id);

        $class_LoginFacebook = $params->get('element_config.class_LoginFacebook', '');
        if($class_LoginFacebook)
            $class_LoginFacebook="btn-$class_LoginFacebook";

        $option=$params->get('element_config.option','');
        $enable_option=$params->get('element_config.enable_option',false);
        $enable_option=JUtility::toStrictBoolean($enable_option);
        if(!$enable_option)
        {
            $option='';
        }

        $task=$params->get('element_config.task','');
        $enable_task=$params->get('element_config.enable_task',false);
        $enable_task=JUtility::toStrictBoolean($enable_task);
        if(!$enable_task)
        {
            $task='';
        }
        $scriptId = "script_ui_LoginFacebook_" . $block->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#ui_LoginFacebook_<?php echo $block->id; ?>').ui_LoginFacebook({
                    enable_edit_website:<?php echo $enableEditWebsite ?>,
                    input:<?php echo json_encode($app->input->getArray()) ?>,
                    LoginFacebook_state:"<?php echo $LoginFacebook_state ?>",
                    link_to_page:<?php echo $link_to_page ?>,
                    option:"<?php echo $option ?>",
                    task:"<?php echo $task ?>",
                    block_id:<?php echo $block->id ?>,
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
        <LoginFacebook id="ui_LoginFacebook_<?php echo $block->id; ?>"  type="LoginFacebook" link-to-page="<?php echo $link_to_page ?>" class="block-item block-item-LoginFacebook btn btn-default <?php echo $class_LoginFacebook ?> <?php echo $pull_right ?>" name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>">
            <?php if($icon_left){ ?>
            <i class="<?php echo $icon_left ?>"></i>
            <?php } ?>
            <span class="span-text">
                <?php echo $text ?>
            </span>
            <?php if($icon_right){ ?>
                <i class="<?php echo $icon_right ?>"></i>
            <?php } ?>
        </LoginFacebook>

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