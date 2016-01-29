<?php
class elementHtmlHelper extends  elementHelper
{
    function initElement($TablePosition)
    {

        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $enableEditWebsite = UtilityHelper::getEnableEditWebsite();
        if($enableEditWebsite) {
            $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
            $doc->addScriptNotCompile(JUri::root() . '/ckfinder/ckfinder.js');
            $doc->addScriptNotCompile(JUri::root() . '/media/editors/ckeditor/ckeditor.js');
            $doc->addScriptNotCompile(JUri::root() . '/media/editors/ckeditor/adapters/jquery.js');
            $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.js");
            $doc->addScript(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/show-hint.js");
            $doc->addScript(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/html-hint.js");
            $doc->addScript(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/xml-hint.js");
            $doc->addStyleSheet(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/show-hint.css");
        }

        $lessInput = JPATH_ROOT . "/$dirName/$filename.less";
        $cssOutput =  JPATH_ROOT . "/$dirName/$filename.css";
        JUtility::compileLess($lessInput, $cssOutput);

    }
    function getHeaderHtml($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $css_class=$block->css_class;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        if(!$ajaxGetContent) {

        }
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element block-item" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
        echo elementHtmlHelper::render_html($block,$enableEditWebsite);
        }else{
        echo elementHtmlHelper::render_html($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_html($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $params = new JRegistry;
        $params->loadString($block->params);
        $bindingSource = $params->get('data.bindingSource', '');
        $binding_source_key = $params->get('data.binding_source_key', '');
        $primary_key_of_table= $params->get('data.primary_key_of_table', '');
        if ($bindingSource&&$binding_source_key) {
            $bindingSource = parent::getValueDataSourceByKey($bindingSource);
            $bindingSource=$bindingSource[0];
            $params_binding_source = new JRegistry;
            $params_binding_source->loadObject($bindingSource);
            $content=$params_binding_source->get($binding_source_key);
            $block->fulltext=base64_decode($content);
        }



        $doc=JFactory::getDocument();
        $scriptId = "ui_html_".$block->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $('.block-item.block-item-html[data-block-id="<?php echo $block->id ?>"]').ui_html({
                    primary_key_of_table:"<?php echo $primary_key_of_table ?>",
                    value_primary_key_of_table:<?php echo  $app->input->get($primary_key_of_table,'0','int'); ?>,
                    enableEditWebsite:<?php echo $enableEditWebsite ?>
                });

            });
        </script>
        <?php
        $fulltext=base64_decode($block->fulltext);
        if($enableEditWebsite)
        {
            $fulltext=$fulltext!=''?$fulltext:'please double click here edit to edit content';
        }

        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);

        ob_start();
            ?>



        <div class="html block-item block-item-html  <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >
            <?php if($enableEditWebsite){ ?>
            <div class="edit_html_content">
                <?php echo $fulltext ?>
            </div>
            <button  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" type="button" class="btn btn-danger save-block-html pull-right"><i class="fa-save"></i>Save</button>
            <?php }else{
                echo $fulltext;
            }?>
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