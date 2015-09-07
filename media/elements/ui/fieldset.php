<?php
class elementFieldSetHelper extends  elementHelper
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
    function getHeaderHtml($block,$enableEditWebsite,$prevV=0)
    {
        $app=JFactory::getApplication();
        $path=$block->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $text=$params->get('text','text_'.$block->id);
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $classColumn=array();

        $offset=$block->gs_x-($prevV->gs_x+$prevV->width);
        $bootstrapColumnType=$block->bootstrap_column_type;
        $bootstrapColumnType=$bootstrapColumnType?$bootstrapColumnType:'col-md-';
        $classColumn[]=$bootstrapColumnType.$block->width;
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $classColumn[]=$bootstrapColumnType.'offset-'.$offset;
        $classColumn=' '.implode(' ',$classColumn);

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
               <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_field_set.init_field_set();
                });
            </script>

            <div  class="control-element control-element-fieldset item_control item_control_<?php echo $block->parent_id ?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <fieldset class="fieldset fieldset-border block-item block-item-fieldset   item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" <?php echo $enable_droppable==1?'enabled-droppable="true"':'' ?> data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

            <legend class="legend-border text"><?php echo $text ?></legend>
        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_field_set.init_field_set();
                });
            </script>
            <fieldset class="fieldset-border <?php echo $classColumn ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
            <legend class="legend-border"><?php echo $text ?></legend>

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

        </fieldset>
        </div>
        <?php
        }else{
        ?>
        </fieldset>
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