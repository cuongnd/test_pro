<?php
class elementTabsHelper extends  elementHelper
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
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/media/elements/ui/divrow.js");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $doc->addScript(JUri::root().'/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">

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
                            if(typeof innittab==="undefined")
                            {
                                var innittab=elementuitab.innittab();
                            }


                        });
                    }else{
                        if(typeof innittab==="undefined")
                        {
                            var innittab=elementuitab.innittab();
                        }
                    }



                });



            </script>
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

            <div class="tabs block-item block-item-tab <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {


                    if(typeof innittab==="undefined")
                    {
                        var innittab=elementuitab.innittab();
                    }
                });
            </script>
            <div  class="tabs block-item block-item-tab <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">

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
            </div></div>
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