<?php
class elementTitleHelper extends  elementHelper
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
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");

        $params = new JRegistry;
        $params->loadString($block->params);
        $class=$params->get('class','');
        $value=$params->get('value','');

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element block-item " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="tile magenta" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
                <!-- tile start here -->
                <div class=tile-icon><i class="<?php echo $class;?>"></i></div><!--ec-share s64  3548 Posts shared-->
                <div class=tile-content>
                    <div class=number><?php echo $value;?></div>
                    <h3><?php echo $block->title ?></h3>
                </div>
            </div>
        <?php
        }else{
            ?>
            <div class="tile magenta" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
                <!-- tile start here -->
                <div class=tile-icon><i class="<?php echo $class;?>"></i></div>
                <div class=tile-content>
                    <div class=number><?php echo $value;?></div>
                    <h3><?php echo $block->title ?></h3>
                </div>
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