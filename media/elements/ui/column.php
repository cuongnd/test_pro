<?php
class elementColumnHelper extends  elementHelper
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

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <?php
            echo elementColumnHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementColumnHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $app=JFactory::getApplication();
        $doc=JFactory::getDocument();
        $doc->addScript(JUri::root().'/media/elements/ui/column.js');
        $params = new JRegistry;
        $params->loadString($block->params);
        $turn_on_clone_config=$params->get('advanced.clone_config.turn_on_clone_config',false);
        $turn_on_clone_config=JUtility::toStrictBoolean($turn_on_clone_config);

        $scriptId = "script_ui_column_" . $block->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.block-item.block-item-column[data-block-id="<?php echo $block->id; ?>"]').ui_column({
                    enable_edit_website:<?php echo json_encode($enableEditWebsite) ?>,
                    block_id:<?php echo $block->id ?>,
                    input:<?php echo json_encode($app->input->post) ?>,
                    clone_config:{
                        enble_clone_config:<?php echo json_encode($turn_on_clone_config) ?>,
                        control_seletect_number:<?php echo $params->get('advanced.clone_config.control_seletect_number',0) ?>,
                        aria_clone_append:<?php echo (int)$params->get('advanced.clone_config.aria_clone_append',0) ?>,
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