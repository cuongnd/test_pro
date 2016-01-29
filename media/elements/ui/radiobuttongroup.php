<?php
include_once ('element.php');
class elementRadioButtonGroupHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();


    }
    function getHeaderHtml($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root() . "/$dirName/$filename.less");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");



        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element control-element-line"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <?php
            echo elementRadioButtonGroupHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementRadioButtonGroupHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $params = new JRegistry;
        $params->loadString($block->params);
        $style=$params->get('element_config.style','');
        if($style)
            $style='btn-'.$style;
        $trigger_change= $params->get('element_config.trigger_change','');
        if($trigger_change!='')
        {
            $trigger_change=explode(',',$trigger_change);
        }else{
            $trigger_change=array();
        }
        $doc=JFactory::getDocument();
        $scriptId = "script_ui_radiobuttongroup_" . $block->id;

        $name=$params->get('element_config.name','');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-radiobuttongroup[data-block-id="<?php echo $block->id ?>"]').ui_radiobuttongroup(
                    {
                        element_name:"<?php echo $name ?>",
                        trigger_change:<?php echo json_encode($trigger_change) ?>
                    }
                );


            });
        </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
    $items=array();
    $input_type=$params->get('input_type','datasource');
    switch($input_type)
    {
        case 'code_php':
            //code here
            break;
        case 'createitem':
            //code here
            break;
        default:
            $bindingSource = $params->get('data.bindingSource','');
            $key = $params->get('data.key','');
            $key = $key ? $key : 'id';
            $value = $params->get('data.value','');
            $value = $value ? $value : 'title';
            $items = parent::getValueDataSourceByKey($bindingSource);
            break;

    }
    $name=$params->get('element_config.name','');


    $html='';
    ob_start();
    ?>
        <div id="ui_radiobuttongroup_<?php echo $block->id ?>"  class="block-item block-item-radiobuttongroup" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <div class="btn-group" data-toggle="buttons">
                <?php foreach($items as $item){ ?>
                <button class="btn btn-default <?php echo $style ?>">
                    <input type="radio" value="<?php echo $item->$key ?>" class="noStyle" name="<?php echo $name ?>"><?php echo $item->$value ?></button>
                <?php } ?>
            </div>
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
    function getFooterHtml($block,$enableEditWebsite)
    {
        $params = new JRegistry;
        $params->loadString($block->params);
        $text=$params->get('text','text_'.$block->id);
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