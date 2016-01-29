<?php
class elementPassengersHelper extends  elementHelper
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
            <div  class="control-element control-element-passengers item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    file_js='<?php echo "/$dirName/$filename.js" ?>';
                    element_ui_element.load_file_js_then_call_back_function(file_js,"element_ui_passengers.init_ui_passengers",'');

                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="block-item block-item-passengers "  <?php echo $enable_droppable==1?'enabled-droppable="true"':'' ?> data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>



        <?php
            echo elementPassengersHelper::render_passengers();
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_passengers.init_ui_passengers();
                });
            </script>
            <div class="block-item block-item-passengers" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>

        <?php
            echo elementPassengersHelper::render_passengers();
        }
        $html.=ob_get_clean();
        return $html;
    }
    function render_passengers()
    {
        $html='';
        ob_start();
        ?>
        <div class="row form-group" >
            <div class="col-md-1">adult(18+)</div>
            <div class="col-md-2">
                <select disableChosen="true" name="total_adult" onchange="element_ui_passengers.change_total_passenger(this,element_ui_passengers.passengers.adult.text)">
                       <?php for($i=1;$i<10;$i++){ ?>
                           <option><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </div>
            <div class="col-md-1">teener(12-17)</div>
            <div class="col-md-2">
                <select disableChosen="true" name="total_teener" onchange="element_ui_passengers.change_total_passenger(this,element_ui_passengers.passengers.teener.text)">
                       <?php for($i=0;$i<10;$i++){ ?>
                           <option><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </div>
            <div class="col-md-1">child(3-11)</div>
            <div class="col-md-2 col-md-offset-3">
                <select disableChosen="true" name="total_child" onchange="element_ui_passengers.change_total_passenger(this,element_ui_passengers.passengers.child.text)">
                       <?php for($i=0;$i<10;$i++){ ?>
                           <option><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-1 col-md-offset-1">Gender</div>
            <div class="col-md-3">First name</div>
            <div class="col-md-3">Last name</div>
            <div class="col-md-3">date of birth</div>
        </div>
        <div class="group-passenger" data-passenger-type="adult">
            <div class="row form-group">
                <div class="col-md-1"><div class="title-passenger">Adult(18-99)</div></div>
            </div>
            <div class="row form-group row-item">
                <div class="col-md-1"><div class="oder-passenger">Adult:1</div></div>
                <div class="col-md-1">
                    <select data-name="title" disableChosen="true">
                        <option>Mr</option>
                        <option>Ms</option>
                        <option>Mrs</option>
                    </select>
                </div>
                <div class="col-md-3"><input data-name="first_name" name="first_name"/></div>
                <div class="col-md-3"><input data-name="last_name" name="last_name"/></div>
                <div class="col-md-3"><input data-type="date" data-name="date_of_birth" name="date_of_birth"/></div>
                <div class="col-md-1"><button value="btn" onclick="element_ui_passengers.remove_row(this)"><i class="im-remove2"></i></button></div>
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