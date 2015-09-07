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
            $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
            $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        }

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element block-item" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <?php
            if($ajaxGetContent) {
                ?>
                <link href="<?php echo JUri::root()."$dirName/$filename.css" ?>" rel="stylesheet">
                <script src="<?php echo JUri::root()."$dirName/$filename.js"  ?>"></script>
            <?php } ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {


                    if(typeof innitHtml==="undefined")
                    {
                        var innitHtml=elementuihtml.innitHtml();
                    }
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

            <div class="edit_html_content <?php echo $css_class ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >
            <?php echo $block->fulltext?$block->fulltext:'please click here edit to edit content' ?>
        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {


                    if(typeof innitHtml==="undefined")
                    {
                        var innitHtml=elementuihtml.innitHtml();
                    }
                });
            </script>
            <div class="html <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >
            <?php echo $block->fulltext ?>
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
            <button  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" type="button" class="btn btn-danger save-block-html pull-right"><i class="fa-save"></i>Save</button>
            </div>
        <?php
        }else{
            ?>
            </div>
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }

}
?>