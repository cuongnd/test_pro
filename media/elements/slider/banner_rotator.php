<?php

class elementBanner_RotatorHelper extends elementHelper
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
    public static function android_set_data_source(&$element,$params,&$data_source=array())
    {
        $app = JFactory::getApplication();
        $path_type = $params->get('path_type', 'items');
        $list_image = array();
        switch ($path_type) {
            case 'items':
                $items = $params->get('items');
                $items = base64_decode($items);
                $items = json_decode($items);
                $list_image = $items->list;
                break;
            case 'database':
                break;
            case 'folder':
                $folder = $params->get('folder', '');
                if ($folder == '')
                    break;
                jimport('joomla.filesystem.folder');
                $files = JFolder::files(JPATH_ROOT . '/images/stories/images/slideshows/' . $folder);
                if (count($files)) {
                    foreach ($files as $file) {
                        $item = new stdClass();
                        $item->title = basename($file);
                        $item->source = JUri::root() . '/images/stories/images/slideshows/' . $folder . '/' . $file;
                        $item->description = basename($file);
                        $list_image[] = $item;
                    }
                }
                break;
            default:
                break;
        }
        $element->list_image=$list_image;


    }

    function getHeaderHtml($block, $enableEditWebsite)
    {
        $app = JFactory::getApplication();
        $path = $block->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/media/system/js/purl-master/purl-master/purl.js');
        $doc->addScript(JUri::root() . '/media/system/js/URI.js-gh-pages/src/URI.js');
        $ajaxGetContent = $app->input->get('ajaxgetcontent', 0, 'int');
        $doc->addScript(JUri::root() . '/media/system/js/slider-master/js/jssor.js');
        $doc->addScript(JUri::root() . '/media/system/js/slider-master/js/jssor.slider.js');
        if (!$ajaxGetContent) {
            $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
            $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        }

        $params = new JRegistry;
        $params->loadString($block->params);

        $size = $params->get('size', '');
        $css_class = $block->css_class;
        $css_class = explode(',', $css_class);
        $css_class = implode(' ', $css_class);
        $link_to_page = $params->get('link_to_page', 0);
        $name = $params->get('name', '');
        $id = $params->get('id', '');
        $button_type = $params->get('button_type', 'submit');
        $method_submit = $params->get('method_submit', 'get');
        $is_booking = $params->get('is_booking', 1);
        $text = $params->get('text', 'text_' . $block->id);
        $placeholder = $params->get('placeholder', 'placeholder_' . $block->id);
        $bindingSource = $params->get('data')->bindingSource;
        if (!$text && $bindingSource) {
            $text = parent::getValueDataSourceByKey($bindingSource);
        }


        $html = '';
        ob_start();
        if ($enableEditWebsite) {

            ?>
            <div class="control-element control-element-button enable-item-resizable " data-block-id="<?php echo $block->id ?>"
            data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>"
            data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>"
            data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i
                    class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="remove label label-danger remove-element" href="javascript:void(0)"><i
                    class="glyphicon-remove glyphicon"></i></a>
            <?php echo elementBanner_RotatorHelper::render_elemenet($block, $enableEditWebsite); ?>


            <?php
        } else {
            echo elementBanner_RotatorHelper::render_elemenet($block, $enableEditWebsite);
        }
        $html .= ob_get_clean();
        return $html;
    }

    public function render_elemenet($block, $enableEditWebsite)
    {
        /*
         * image{
         * title:'',
         * source:'',
         * description:''
         * }
         */
        $doc = JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $path_type = $params->get('path_type', 'items');
        $list_image = array();
        switch ($path_type) {
            case 'items':
                $items = $params->get('items');
                $items = base64_decode($items);
                $items = json_decode($items);
                $list_image = $items->list;
                break;
            case 'database':
                break;
            case 'folder':
                $folder = $params->get('folder', '');
                if ($folder == '')
                    break;
                jimport('joomla.filesystem.folder');
                $files = JFolder::files(JPATH_ROOT . '/images/stories/images/slideshows/' . $folder);
                if (count($files)) {
                    foreach ($files as $file) {
                        $item = new stdClass();
                        $item->title = basename($file);
                        $item->source = JUri::root() . '/images/stories/images/slideshows/' . $folder . '/' . $file;
                        $item->description = basename($file);
                        $list_image[] = $item;
                    }
                }
                break;
            default:
                break;
        }

        $scriptId = "script_ui_banner_rotator_" . $block->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#element_slider1_container_<?php echo $block->id ?>').ui_banner_rotator({
                    slide_width: '<?php echo $params->get('slide_width','100%') ?>',
                    slide_height: '<?php echo $params->get('slide_heigt',400) ?>'
                });
            });
        </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
    ob_start();
    ?>
        <div id="element_slider1_container_<?php echo $block->id ?>"
             class="block-item block-item-banner_rotator container"
             data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
             style="position: relative; top: 0px; left: 0px; width: 600px; height: 300px;">
            <!-- Slides Container -->
            <div u="slides"
                 style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 600px; height: 300px;">
                <?php if (count($list_image)) { ?>
                    <?php foreach ($list_image as $item_image) { ?>
                        <div><img u="image"
                                  src="<?php echo $item_image->source ?>"/>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div><img u="image" src="<?php echo JUri::root() ?>/images/stories/no_image.png"/>
                    </div>
                    <div><img u="image" src="<?php echo JUri::root() ?>/images/stories/no_image.png"/>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php
        $html = ob_get_clean();
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


}

?>