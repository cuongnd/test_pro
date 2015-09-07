<?php
class elementRoomHelper extends  elementHelper
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
        $set_time_out_update_data=$params->get('set_time_out_update_data',0);

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
            <div  class="control-element control-element-room item_control item_control_<?php echo $block->parent_id ?>" get-data-from="<?php  echo $data_text?'datasource':'text'?>" <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    file_js='<?php echo "/$dirName/$filename.js" ?>';
                    element_ui_element.load_file_js_then_call_back_function(file_js,"element_ui_room.init_ui_room",'');

                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="block-item block-item-room "  <?php echo $enable_droppable==1?'enabled-droppable="true"':'' ?> data-block-id="<?php echo $block->id ?>" time-out-update-data="<?php echo $set_time_out_update_data ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>



        <?php
            echo elementRoomHelper::render_room();
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_room.init_ui_room();
                });
            </script>
            <div class="block-item block-item-room" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" time-out-update-data="<?php echo $set_time_out_update_data ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><?php echo $text ?>

        <?php
            echo elementRoomHelper::render_room();
        }
        $html.=ob_get_clean();
        return $html;
    }
    function render_room()
    {
        $html='';
        ob_start();
        ?>
        <div class="room-item">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="room_order pull-left">Room 1</h3><button class="pull-left" onclick="element_ui_room.remove_room(this)"><i class="im-remove2"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            Single
                            <br/>
                            <input class="noStyle" data-room-type="Single" checked value="1" onclick="element_ui_room.change_room_type(this)" data-name="room_type" name="room_type" type="radio">
                        </div>
                        <div class="col-md-3">
                            double
                            <br/>
                            <input class="noStyle" data-room-type="Double" value="2" onclick="element_ui_room.change_room_type(this)" data-name="room_type" name="room_type" type="radio">
                        </div>
                        <div class="col-md-3">
                            Twin
                            <br/>
                            <input class="noStyle"  data-room-type="Twin" value="3" onclick="element_ui_room.change_room_type(this)" data-name="room_type" name="room_type" type="radio">
                        </div>
                        <div class="col-md-3">
                            Triple
                            <br/>
                            <input class="noStyle" value="4" data-room-type="Triple" onclick="element_ui_room.change_room_type(this)" data-name="room_type" name="room_type" type="radio">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Adult/Teener</label>
                                <select disableChosen="true">
                                    <option>1</option>
                                    <option>3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            R-MATE
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Child/infant</label>
                                <select disableChosen="true">
                                    <option>1</option>
                                    <option>3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                          <div class="col-md-12">
                              <textarea style="width: 100%" placeholder="your notes ?" ></textarea>
                          </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>Select your room and mates</div>
                    <select class="passenger_room" disableChosen="true">
                        <option value="0">select passenger</option>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="pull-right" onclick="element_ui_room.add_more_room(this)"><i class="im-remove2"></i>Add more room</button>
                </div>
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