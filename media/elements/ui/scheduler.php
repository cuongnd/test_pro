<?php
class elementSchedulerHelper extends  elementHelper
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


        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/sources/dhtmlxscheduler.js');
        $doc->addScript(JUri::root() . '/media/system/js/jquery.base64.js');
        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_recurring.js');

        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_minical.js');

        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_year_view.js');

        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_agenda_view.js');

        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_grid_view.js');

        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_tooltip.js');
        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_serialize.js');
        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_units.js');
        $doc->addScript(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/ext/dhtmlxscheduler_limit.js');

        $doc->addStyleSheet(JUri::root() . '/media/system/js/dhtmlxScheduler_v4.3.1/codebase/dhtmlxscheduler_flat.css');
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");



        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name=$params->get('name','name_'.$block->id);
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','');
        $enable_submit=$params->get('enable_submit',1);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text=$params->get('data',new stdClass())->text;
        if($data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $binding_source=$params->get('data')->bindingSource;
        if($binding_source){
            $binding_source=parent::getValueDataSourceByKey($binding_source);
        }



        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-scheduler item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_scheduler.init_ui_scheduler();
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>


        <?php
        echo elementSchedulerHelper::render_scheduler($block,$enable_submit,$name,$binding_source);
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_scheduler.init_ui_scheduler();
                });
            </script>

            <?php
            echo elementSchedulerHelper::render_scheduler($block,$enable_submit,$name,$binding_source);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_scheduler($block,$enable_submit,$name,$binding_source){
        ob_start();
        ?>


        <div id="scheduler_here<?php echo $block->id ?>" class="block-item block-item-scheduler dhx_cal_container" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  style='width:100%; height:400px;'>
            <div class="dhx_cal_navline">
                <div class="dhx_cal_prev_button">&nbsp;</div>
                <div class="dhx_cal_next_button">&nbsp;</div>
                <div class="dhx_cal_today_button"></div>
                <div class="dhx_minical_icon" id="dhx_minical_icon" onclick="element_ui_scheduler.show_minical()">&nbsp;</div>
                <div class="dhx_cal_date"></div>
                <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
                <div class="dhx_cal_tab" name="agenda_tab" style="right:280px;"></div>
                <div class="dhx_cal_tab" name="year_tab" style="right:280px;"></div>
            </div>
            <div class="dhx_cal_header"></div>
            <div class="dhx_cal_data"></div>
            <input type="hidden" enable-submit="<?php echo $enable_submit?'true':'false' ?>" value="<?php echo base64_encode(json_encode($binding_source)) ?> "  name="<?php echo $name ?>" id="block_item_scheduler_<?php echo $block->id ?>" class="block-item block-item-scheduler "   data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>" />
        </div>

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