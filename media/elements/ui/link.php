<?php
class elementLinkHelper extends  elementHelper
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
        $doc->addScript(JUri::root().'/media/system/js/URI.js-gh-pages/src/URI.js');
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
        $id=$params->get('id','');
        $button_type=$params->get('button_type','submit');
        $method_submit=$params->get('method_submit','get');
        $link=$params->get('link','');
        $link=JUri::getInstance($link);
        $link->setVar('Itemid',$link_to_page);
        $link=$link->toString();
        $text=$params->get('text','link_'.$block->id);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-link  " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <a data-method-submit="<?php echo $method_submit ?>" link-to-page="<?php echo $link_to_page ?>" type="<?php echo $button_type ?>" class="block-item block-item-link <?php echo $css_class ?> " href="<?php echo $link ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"> <i class="fa-circle-blank"></i><?php echo $text ?></a>


        <?php
        }else{
        ?>
            <a type="<?php echo $button_type ?>" link-to-page="<?php echo $link_to_page ?>" class="block-item block-item-link  <?php echo $css_class ?>"  data-block-id="<?php echo $block->id ?>" href="<?php echo $link ?>"  data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>"><i class="fa-circle-blank"></i><?php echo $text ?></a>

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