<?php
class elementBookingSummaryHelper extends  elementHelper
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
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css");
        $doc->addScript(JUri::root().'/media/jui_front_end/jquery-ui-1.11.1/ui/datepicker.js');
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');

        $label=$params->get('label','');
        $name=$params->get('name','');
        $enable_droppable=$params->get('enable_droppable',0);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('id','');
        $text=$params->get('text','');
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text=$params->get('data',new stdClass())->text;
        if($data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $max_text=$params->get('max_text',0);
        if($max_text)
        {
            $max_text=JString::sub_string($text,$max_text);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-bookingsummary item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    file_js='<?php echo "/$dirName/$filename.js" ?>';
                    element_ui_element.load_file_js_then_call_back_function(file_js,"element_ui_booking_summary.init_ui_booking_summary",'');

                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="block-item block-item-bookingsummary "  <?php echo $enable_droppable==1?'enabled-droppable="true"':'' ?> data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>



        <?php
            echo elementbookingsummaryHelper::render_bookingsummary();
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_booking_summary.init_ui_booking_summary();
                });
            </script>
            <div class="block-item block-item-bookingsummary" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>

        <?php
            echo elementbookingsummaryHelper::render_bookingsummary();
        }
        $html.=ob_get_clean();
        return $html;
    }
    function render_bookingsummary()
    {
        $html='';
        ob_start();
        ?>
    <div class="row  form-group">
        <div class="col-md-12"><h4 >Booking summary</h4></div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12"><button class="pull-right" >Detail</button></div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12"><h4>tour title</h4></div>
    </div>
    <div class="row  form-group">
        <div class="col-md-6">
            <span><i class=""></i>start date,city</span>
            <br/>
            <span>10 Nov, ha noi city, viet nam</span>
        </div>
        <div class="col-md-6">
            <span><i class=""></i>Finish date,city</span>
            <br/>
            <span>10 Nov, ha noi city, viet nam</span>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-6">
            20 day duration
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-6">
            tour type: jiont group
        </div>
        <div class="col-md-6">
            class service:stander
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <div class="line">line here</div>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <div><i class=""></i>Passenger Number: <span class="total_person">7 person</span></div>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <ul class="list_passenger">
                <li>1.sdfds(adult 34 year old)</li>
                <li>1.sdfds(adult 34 year old)</li>
                <li>1.sdfds(adult 34 year old)</li>
                <li>1.sdfds(adult 34 year old)</li>
                <li>1.sdfds(adult 34 year old)</li>
                <li>1.sdfds(adult 34 year old)</li>
                <li>1.sdfds(adult 34 year old)</li>
            </ul>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <div class="pull-right">service fee: US$345454</div>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <div class="line">line here</div>
        </div>
    </div>
    <div class="item-room-passenger">
        <div class="row  form-group">
            <div class="col-md-12">
                <div><i class=""></i><span class="room_type">single room<span></div>
            </div>
        </div>
        <div class="row  form-group">
            <div class="col-md-12">
                <ul class="list_passenger_for_room">
                    <li>1.sdfds(adult 34 year old)</li>
                    <li>1.sdfds(adult 34 year old)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <div class="pull-right">service fee: US$45454</div>
        </div>
    </div>
    <div class="row  form-group">
        <div class="col-md-12">
            <div class="pull-right">you pay </div>
        </div>
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