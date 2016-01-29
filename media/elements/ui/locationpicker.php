<?php

class elementlocationpickerHelper extends elementHelper
{
    function initElement($TablePosition)
    {
        $path = $TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . "/$dirName/$filename.less";
        $cssOutput = JPATH_ROOT . "/$dirName/$filename.css";
        JUtility::compileLess($lessInput, $cssOutput);

    }
function getHeaderHtml($block, $enableEditWebsite)
{

    $app = JFactory::getApplication();
    $path = $block->ui_path;
    $pathInfo = pathinfo($path);
    $filename = $pathInfo['filename'];
    $dirName = $pathInfo['dirname'];
    $doc = JFactory::getDocument();


    $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
    $doc->addScript(JUri::root() . "/$dirName/$filename.js");
    $params = new JRegistry;
    $params->loadString($block->params);

    $size = $params->get('size', '');

    $label = $params->get('label', '');
    $name = $params->get('name', '');
    $enable_droppable = $params->get('enable_droppable', 0);
    $enable_sortable = $params->get('enable_sortable', 0);
    $enable_resizable_for_control = $params->get('enable_resizable_for_control', 0);
    $id = $params->get('id', '');
    $text = $params->get('text', '');
    $placeholder = $params->get('placeholder', 'placeholder_' . $block->id);
    $data_text = $params->get('data', new stdClass())->text;
    if ($data_text) {
        $text = parent::getValueDataSourceByKey($data_text);
    }
    $max_text = $params->get('max_text', 0);
    if ($max_text) {
        $max_text = JString::sub_string($text, $max_text);
    }
    $scriptId = "ui_locationpicker_" . $block->id;
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.block-item.block-item-locationpicker[data-block-id="<?php echo $block->id ?>"]').ui_locationpicker({});

        });
    </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);


    $html = '';
    ob_start();
if ($enableEditWebsite)
{
    ?>
    <div class="control-element control-element-div item_control item_control_<?php echo $block->parent_id ?>"
         get-data-from="<?php echo $data_text ? 'datasource' : 'text' ?>" <?php echo $enable_resizable_for_control == 1 ? 'enabled-resizable="true"' : '' ?> <?php echo $enable_sortable == 1 ? 'enabled-sortable="true"' : '' ?>
         data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
         element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>"
         data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>"
         data-gs-height="<?php echo $block->height ?>">
        <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
              class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i
                class="glyphicon glyphicon-move"></i></span>
        <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
           class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
        <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>"
           data-block-parent-id="<?php echo $block->parent_id ?>" class="add label label-danger add-row"><i
                class="glyphicon glyphicon-plus"></i></a>
        <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
           class="remove label label-danger remove-element" href="javascript:void(0)"><i
                class="glyphicon-remove glyphicon"></i></a>




        <?php
            echo elementlocationpickerHelper::render_element($block,$enableEditWebsite);
        } else {
            echo elementlocationpickerHelper::render_element($block,$enableEditWebsite);
            ?>
            <?php
        }
        $html .= ob_get_clean();
        return $html;
        }
        function getFooterHtml($block, $enableEditWebsite)
        {
                $html = '';
                ob_start();
                if ($enableEditWebsite) {
                ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <?php
                }
            $html .= ob_get_clean();
            return $html;
        }
    public function render_element($block,$enableEditWebsite)
    {
    $doc=JFactory::getDocument();
     $params = new JRegistry;
    $params->loadString($block->params);
    $map_style=$params->get('map_style','style1');
    $group_add_on_left = $params->get('group_add_on_left', '');
    $group_add_on_left = $group_add_on_left == 'none' ? '' : $group_add_on_left;

    $group_add_on_right = $params->get('group_add_on_right', '');
    $group_add_on_right = $group_add_on_right == 'none' ? '' : $group_add_on_right;
    if($map_style=='style1'){
     $doc->addScript(JUri::root() . '/media/system/js/gmap3-master/src/google.maps.js');
    $doc->addScript(JUri::root() . '/media/system/js/gmap3-master/dist/gmap3.js');
    $doc->addScript(JUri::root() . '/media/system/js/gmap3-master/assets/examples/context-menu/menu/gmap3-menu.js');

    $doc->addStyleSheet(JUri::root() . '/media/system/js/gmap3-master/assets/examples/context-menu/menu/gmap3-menu.css');

    }else{
        $doc->addStyleSheet(JUri::root().'/media/system/js/Bootstrap-WebUI-Popover/src/jquery.webui-popover.css');
        $doc->addScript(JUri::root().'/media/system/js/Bootstrap-WebUI-Popover/src/jquery.webui-popover.js');
        $doc->addScript(JUri::root().'/media/system/js/jquery-locationpicker-plugin-master/src/google_map_sensor.js');
        $doc->addScript(JUri::root().'/media/system/js/jquery-locationpicker-plugin-master/dist/locationpicker.jquery.js');
    }
        $htmlcontrol='';
        ob_start();
         if($map_style=='style1'){
        ?>
        <div data-map_style="<?php echo $map_style ?>" id="ui_locationpicker_<?php echo $block->id ?>" data-block-id="<?php echo $block->id ?>"
             data-block-parent-id="<?php echo $block->parent_id ?>" class="block-item block-item-locationpicker"
             style="width: 100%;height: 400px"></div>
              <div id="directions"></div>
        <?php }else{ ?>
       <div id="ui_locationpicker_<?php echo $block->id ?>" data-target="webuiPopover_<?php echo $block->id ?>" class="block-item block-item-locationpicker " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >
       <input type="text" class="form-control address"  style="width: 150px" />
                <div id="webuiPopover_<?php echo $block->id ?>"    style="position: absolute; visibility: hidden; width: 300px; height: 300px"/>
                <input type="hidden" class="radius"/>
                <input type="hidden" class="lat"/>
                <input type="hidden" class="lon"/>
                </div>
        </div>


        <?php
        }
        $htmlcontrol=ob_get_clean();


         ob_start();
        $html = '';
        ?>
        <?php if ($group_add_on_left || $group_add_on_right) { ?>
            <div class="input-group block-item-locationpicker">
            <?php if ($group_add_on_left) { ?>
                <div class="input-group-addon"><i class="<?php echo $group_add_on_left ?>"></i></div>
            <?php } ?>
        <?php } ?>
        <?php echo $htmlcontrol ?>
        <?php if ($group_add_on_left || $group_add_on_right) { ?>
            <?php if ($group_add_on_right) { ?>
               <div class="input-group-addon"><i class="<?php echo $group_add_on_right ?>"></i></div>
            <?php } ?>
            </div>
        <?php } ?>
        <?php
        $html .= ob_get_clean();

        return $html;

    }

}

?>