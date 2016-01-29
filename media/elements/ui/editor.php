<?php
class elementEditorHelper extends  elementHelper
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
        $doc->addScriptNotCompile(JUri::root().'/ckfinder/ckfinder.js');
        $doc->addScriptNotCompile(JUri::root().'/media/editors/ckeditor/ckeditor.js');
        $doc->addScriptNotCompile(JUri::root().'/media/editors/ckeditor/adapters/jquery.js');
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-div item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?> <?php echo $enable_sortable==1?'enabled-sortable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>"  data-block-parent-id="<?php echo $block->parent_id ?>" class="add label label-danger add-row"><i class="glyphicon glyphicon-plus"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementEditorHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementEditorHelper::render_element($block,$enableEditWebsite);

        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $doc=JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $disable_element =$params->get('element.disable',true);
        $disable_element=JUtility::toStrictBoolean($disable_element);
        if($disable_element)
        {
            return false;
        }

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name=$params->get('name','');
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_sortable=$params->get('enable_sortable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','id_'.$block->id);
        $text=$params->get('text','');
        $enable_submit=$params->get('enable_submit',1);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text=$params->get('data',new stdClass())->text;
        if($data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $max_text=$params->get('max_text',0);
        if($max_text)
        {
            $max_text=JString::sub_string($text,$max_text);
        }

        $scriptId = "script_ui_editor_" . $block->id;

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-editor[data-block-id="<?php echo $block->id ?>"]').ui_editor({
                    editor_option:{
                    }
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
        <textarea enable-submit="<?php echo $enable_submit?'true':'false' ?>" name="<?php echo $name ?>" id="ui_editor_<?php echo $block->id ?>" contenteditable="true" class="block-item block-item-editor "   data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>"><?php echo $text ?></textarea>

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