<?php
class elementImageButtonHelper extends  elementHelper
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
        $doc->addScript(JUri::root().'/media/system/js/purl-master/purl-master/purl.js');
        $doc->addScript(JUri::root().'/media/system/js/uri/src/URI.js');
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        if(!$ajaxGetContent) {
            $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
            $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        }
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $link_to_page=$params->get('link_to_page',0);
        $icon=$params->get('icon','br-refresh');
        $name=$params->get('name','');
        $id=$params->get('id','');
        $imagebutton_type=$params->get('imagebutton_type','submit');
        $method_submit=$params->get('method_submit','get');
        $text=$params->get('text','text_'.$block->id);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data')->bindingSource;
        if(!$text&&$bindingSource){
            $text=parent::getValueDataSourceByKey($bindingSource);
        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-imagebutton enable-item-resizable " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <?php echo elementImageButtonHelper::render_element($block); ?>
        <?php
        }else{
        ?>
            <?php echo elementImageButtonHelper::render_element($block); ?>

        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    public  function  render_element($block)
    {
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $link_to_page=$params->get('link_to_page',0);
        $icon=$params->get('icon','br-refresh');
        $name=$params->get('name','');
        $id=$params->get('id','');
        $text=$params->get('text','text_'.$block->id);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data')->bindingSource;
        if(!$text&&$bindingSource){
            $text=parent::getValueDataSourceByKey($bindingSource);
        }

        $html='';
        ob_start();
        ?>
        <a href="javascript:void(0)"  link-to-page="<?php echo $link_to_page ?>" class="block-item block-item-imagebutton  <?php echo $css_class ?>" name="<?php echo $name ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><i class="<?php echo $icon ?>"></i></a>
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