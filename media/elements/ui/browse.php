<?php
class elementBrowseHelper extends  elementHelper
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
        $doc->addScript(JUri::root().'/ckfinder/ckfinder.js');
        $params = new JRegistry;
        $params->loadString($block->params);
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $size=$params->get('size','');

        $name=$params->get('name','name_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $text=$params->get('text','browse');
        $placeholder=$params->get('placeholder','');
        $data_text=$params->get('data')->text;
        if(!$text&&$data_text){
            $text=parent::getValueDataSourceByKey($data_text);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-input enable-item-resizable  <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_browse.init_ui_browse();
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="input-group">
                <input type="text" class="block-item block-item-browse form-control"  value="<?php echo $text ?>" readonly   name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>
               <span class="input-group-btn">
                    <button class="btn btn-default browser-server" data-output="input[data-block-id='<?php echo $block->id ?>']" onclick="element_ui_browse.open_file_server(this)" type="button"><i class="en-browser"></i><?php echo $text ?></button>
               </span>
            </div>



        <?php
        }else{
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_browse.init_ui_browse();
                });
            </script>
            <div class="input-group">
                <input type="text" class="block-item block-item-browse form-control"  value="<?php echo $text ?>" readonly   name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"/>
               <span class="input-group-btn">
                    <button class="btn btn-default browser-server" data-output="input[data-block-id='<?php echo $block->id ?>']" onclick="element_ui_browse.open_file_server(this)" type="button"><i class="en-browser"></i><?php echo $text ?></button>
               </span>
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