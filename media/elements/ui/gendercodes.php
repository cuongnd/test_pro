<?php
class elementGenderCodesHelper extends  elementHelper
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
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        if(!$ajaxGetContent) {
            $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
            $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        }
        require_once JPATH_ROOT.'/libraries/joomla/user/helper.php';
        $params = new JRegistry;
        $params->loadString($block->params);
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $size=$params->get('size','');

        $name=$params->get('name','name_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $text=$params->get('text','');
        $placeholder=$params->get('placeholder','');
        $bindingSource=$params->get('data')->bindingSource;
        if(!$text&&$bindingSource){
            $text=parent::getValueDataSourceByKey($bindingSource);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-input enable-item-resizable  <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    file_js='<?php echo "/$dirName/$filename.js" ?>';
                    element_ui_element.load_file_js_then_call_back_function(file_js,"element_ui_gendercodes.int_ui_gender_codes",'');

                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="input-group">
                <input type="text" class="block-item block-item-gendercodes form-control"  value="<?php echo JUserHelper::genRandomPassword() ?>" readonly   name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>
               <span class="input-group-btn">
                    <button class="btn btn-default" onclick="" type="button"><i class="fa-refresh"></i></button>
               </span>
            </div>


        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_gendercodes.int_ui_gender_codes();
                });
            </script>
            <div class="input-group">
                <input type="text" class="block-item block-item-gendercodes form-control"  value="<?php echo JUserHelper::genRandomPassword() ?>" readonly   name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>
               <span class="input-group-btn">
                    <button class="btn btn-default" onclick="" type="button"><i class="fa-refresh"></i></button>
               </span>
            </div>


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

        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }



}
?>