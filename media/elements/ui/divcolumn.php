<?php
class elementDivColumnHelper extends  elementHelper
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
    function getHeaderHtml($block,$enableEditWebsite,$prevV)
    {
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $css_class=$block->css_class;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $classColumn=array();

        $offset=$block->gs_x-($prevV->gs_x+$prevV->width);
        $bootstrapColumnType=$block->bootstrap_column_type;
        $bootstrapColumnType=$bootstrapColumnType?$bootstrapColumnType:'col-md-';
        $classColumn[]=$bootstrapColumnType.$block->width;
        $classColumn[]=$bootstrapColumnType.'offset-'.$offset;
        $classColumn=' '.implode(' ',$classColumn);

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div style="display: block" class="control-element block-item enable-item-resizable div-column enable-create-drop-element item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x?$block->gs_x:0 ?>" data-gs-y="<?php echo $block->gs_y?$block->gs_y:0 ?>" data-gs-width="<?php echo $block->width?$block->width:3 ?>" data-gs-height="<?php echo $block->height?$block->height:1 ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    if($('link[href="'+this_host+'<?php echo "/$dirName/$filename.css" ?>"]').length==0)
                    {
                        $('head').append('<link href="'+this_host+'<?php echo "/$dirName/$filename.css" ?>" type="text/css" rel="stylesheet"/>');
                    }
                    if($('script[src="'+this_host+'<?php echo "/$dirName/$filename.js" ?>"]').length==0)
                    {
                        $('head').append('<\script src="'+this_host+'<?php echo "/$dirName/$filename.js" ?>" type="text/javascript"></\script>');
                        $('script[src="'+this_host+'<?php echo "/$dirName/$filename.js" ?>"]' ).load(function() {
                            // Handler for .load() called.
                            element_ui_div_column.init_div_column();
                        });
                    }else{
                        element_ui_div_column.init_div_column();
                    }



                });
            </script>
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_div_column.init_div_column();


                });
            </script>
            <div class="block-item <?php echo $css_class ?> <?php echo $classColumn ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  >

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
        </div>
    <?php
        }
        $html.=ob_get_clean();
        return $html;
    }

}
?>