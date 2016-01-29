<?php
include_once ('element.php');
class elementAutoCompleteKeyTextHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root().'/media/jui_front_end/css/select2.css');
        $doc->addScript(JUri::root().'/media/jui_front_end/js/select2.jquery.js');
        $doc->addScript(JUri::root().'/media/system/js/jquery.utility.js');

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
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);
        $text=$params->get('element_config.text','text_'.$block->id);
        $data_text=$params->get('data.text','');
        $border_bottom_line =$params->get('element_config.border_bottom_line','');
        $border_bottom_line=JUtility::toStrictBoolean($border_bottom_line);
        if($border_bottom_line)
        {
            $border_bottom_line=";border-bottom: 1px solid #ccc;padding: 5px;";
        }
        if($data_text)
        {
            $text=parent::getValueDataSourceByKey($data_text);
        }


        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element control-element-h4"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <?php
            echo elementAutoCompleteKeyTextHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementAutoCompleteKeyTextHelper::render_element($block,$enableEditWebsite);
            ?>

            <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $doc=JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $text=$params->get('element_config.text','text_'.$block->id);
        $data_text=$params->get('data.text','');
        $border_bottom_line =$params->get('element_config.border_bottom_line','');
        $border_bottom_line=JUtility::toStrictBoolean($border_bottom_line);
        if($border_bottom_line)
        {
            $border_bottom_line=";border-bottom: 1px solid #ccc;padding: 5px;";
        }
        if(trim($data_text)!='')
        {
            $text=parent::getValueDataSourceByKey($data_text);
        }



        $name=$params->get('element_config.name','');
        $items=$params->get('element_config.items','');
        $bindingSource=$params->get('data.bindingSource','');
        $key=$params->get('data.key','');
        $key=$key?$key:'id';
        $value=$params->get('data.value','');
        $value=$value?$value:'title';


        $enable_template=$params->get('data.enable_template',true);
        $enable_template=JUtility::toStrictBoolean($enable_template);
        if($enable_template) {
            $template = $params->get('data.template');
            $template = trim($template);
            $template = str_replace(array("\r", "\n"), '', $template);
        }



        $enable_template_selected=$params->get('data.enable_template_selected',true);
        $enable_template_selected=JUtility::toStrictBoolean($enable_template_selected);
        if($enable_template_selected) {
            $template_selected = $params->get('data.template_selected');
            $template_selected = trim($template_selected);
            $template_selected = str_replace(array("\r", "\n"), '', $template_selected);
        }
        $min_width=$params->get('min_widtd');
        $app=JFactory::getApplication();
        $data_value_selected=$params->get('data.selected');

        if($data_value_selected){
            $data_value_selected=(array)parent::getValueDataSourceByKey($data_value_selected);
            $data_value_selected=JArrayHelper::pivot($data_value_selected);
        }
        if(!$items&&$bindingSource){
            $items=parent::getValueDataSourceByKey($bindingSource);
        }
        if(!$items)
        {
            $items=array();
        }
        $scriptId = "script_ui_autocompletekeytext_" . $block->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-autocompletekeytext[data-block-id="<?php echo $block->id ?>"]').ui_autocompletekeytext({
                    enable_template:'<?php echo $enable_template ?>',
                    template:'<?php echo $template ?>',
                    enable_template_selected :'<?php echo $enable_template_selected ?>',
                    template_selected :'<?php echo $template_selected ?>',
                    min_width :'<?php echo $min_width ?>',
                    key :'<?php echo $key ?>',
                    value :'<?php echo $value ?>',
                    select2_option:{
                        data:<?php echo json_encode($items) ?>,
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
        <input id="ui_autocompletekeytext_<?php  echo $block->id ?>"  class="block-item block-item-autocompletekeytext" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" />

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
    function getDevHtml($TablePosition)
    {
        $html='';
        ob_start();
        ?>
        <div class="tabs" data-block-id="<?php echo $TablePosition->id ?>" data-block-parent-id="<?php echo $TablePosition ->parent_id ?>">
            <ul id="myTab2" class="nav nav-tabs nav-justified">
                <li><a href="#home2" data-toggle="tab">Home</a></li>

            </ul>
            <div id="myTabContent2" class="tab-content">
                <div class="tab-pane fade active in" id="home2">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia, suscipit, autem sit natus deserunt officia error odit ea minima soluta ratione maxime molestias fugit explicabo aspernatur praesentium quisquam voluptatum fuga delectus quidem quas aliquam minus at corporis libero? Modi, aperiam, pariatur, sequi illum dolore consequuntur aspernatur eos hic officia doloribus magnam impedit autem maiores alias consectetur tempore explicabo. Ducimus, minima, suscipit unde harum numquam ipsa laboriosam cupiditate nemo repellendus at? Dolorum dicta nemo quaerat iusto.</p>
                </div>
            </div>
        </div>
        <?php
        $html.=ob_get_clean();
        return $html;
    }
}
?>