<?php
include_once ('element.php');
class elementH3Helper extends  elementHelper
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
            <div class="control-element control-element-h3"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementH3Helper::render_element($block,$enableEditWebsite);
        }else{
            echo elementH3Helper::render_element($block,$enableEditWebsite);
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
        if($data_text)
        {
            $text=parent::getValueDataSourceByKey($data_text);
        }

        $scriptId = "script_ui_h3_" . $block->id;

        $name=$params->get('element_config.name','');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-h3[data-block-id="<?php echo $block->id ?>"]').ui_h3(
                    {
                        block_id:<?php echo $block->id ?>,
                        enable_edit_website:<?php echo json_encode($enableEditWebsite) ?>,
                        element_name:"<?php echo $name ?>"
                    }
                );


            });
        </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);



    $html='';
        ob_start();
        ?>
        <h3 style="<?php echo $border_bottom_line ?>" class="block-item block-item-h3" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?></h3>

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