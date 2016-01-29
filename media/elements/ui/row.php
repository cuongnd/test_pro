<?php
class elementRowHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'/media/elements/ui/row.less');


    }
    function getHeaderHtml($block,$enableEditWebsite)
    {
        $css_class = $block->css_class;
        $css_class = explode(',', $css_class);
        $css_class = implode(' ', $css_class);
        $params = new JRegistry;
        $params->loadString($block->params);
        $axis = $params->get('axis', 'false');
        $float = $params->get('float', 'none');
        $cell_height = $params->get('cell_height', 80);
        $vertical_margin = $params->get('vertical_margin', 0);
        $amount_of_columns = $params->get('amount_of_columns', 12);
        $setClass = $block->css_class ? ' ' . $block->css_class . ' ' : '';
        $is_template=$block->is_template;
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="row-content block-item <?php echo ($block->menu_item_id!=$block->active_menu_item_id||$block->is_main_frame&&$block->only_page==0?' main_frame ':'') ?>  show-grid-stack-item <?php echo $setClass ?> <?php echo  $css_class ?> <?php echo  $is_template==1?' is_template ':'' ?>" style="<?php echo ($block->block_level == 0 ? 'display:none;' : '') ?>" data-screensize="<?php echo $block->screensize ?>" data-ordering="<?php echo $block->ordering ?>" data-block-parent-id="<?php echo  $block->parent_id ?>" data-bootstrap-type="<?php echo  $block->type ?>" data-block-id="<?php echo  $block->id ?>" element-type="<?php echo  $block->type ?>">
                            <div data-block-parent-id="<?php echo  $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" class="item-row">row</div>
                            <span class="drag label label-default <?php echo ($block->block_level == 0 ? ' move-row ' : ' move-sub-row ') ?>" data-block-parent-id="<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>"><i class="glyphicon glyphicon-move"></i></span>
                            <a href="javascript:void(0)" class="add label label-danger add-column-in-row" data-block-parent-id="<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>"><i class="glyphicon glyphicon-plus"></i></a>
                            <a href="javascript:void(0)" class="remove label label-danger remove-row" data-block-parent-id="<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>"><i class="glyphicon-remove glyphicon"></i></a>
                            <a href="javascript:void(0)" class="menu label label-danger menu-list config-block" data-block-parent-id="<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>"><i class="im-menu2"></i></a>
                            <div class="grid-stack <?php echo ($enableEditWebsite ? ' control-element ' : '') ?>" data-grird-stack-item="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-screensize="<?php echo $block->screensize ?>" cell-height="<?php echo $cell_height ?>" vertical-margin="<?php echo $vertical_margin ?>" amount-of-columns="<?php echo $amount_of_columns ?>">
            <?php


        }else{

            ?>
            <div data-screensize="<?php echo $block->screensize ?>" class=" block-item block-item-<?php echo $block->type ?> <?php echo $setClass ?> <?php echo $block->type ?>  row row-bootstrap form-group  " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  data-block-type="<?php echo ($block->position == 'position-component' ? 'block-component' : '') ?>" >
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