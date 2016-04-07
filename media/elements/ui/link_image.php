<?php
class elementLink_ImageHelper extends  elementHelper
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
            <div  class="control-element control-element-link-image  " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementLink_ImageHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementLink_ImageHelper::render_element($block,$enableEditWebsite);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $link_to_page=$params->get('element_config.link_to_page',0);
        $id=$params->get('id','');
        $button_type=$params->get('element_config.button_type','submit');
        $method_submit=$params->get('element_config.method_submit','get');
        $link=$params->get('element_config.link',JUri::root());
        if($link_to_page!=0)
        {
            $link=JUri::root().'index.php?Itemid='.$link_to_page;
        }
        $image_source=$params->get('element_config.image_source','');

        $text=$params->get('text','');
        $placeholder=$params->get('element_config.placeholder','placeholder_'.$block->id);
        ob_start();
        ?>
        <a data-method-submit="<?php echo $method_submit ?>" link-to-page="<?php echo $link_to_page ?>" type="<?php echo $button_type ?>" class="block-item block-item-link-image <?php echo $css_class ?> " href="<?php echo $link ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>">
            <img src="<?php echo $image_source ?>" class="img-responsive">
            <?php if($text!=''){ ?><h5 style="text-align: center"><?php echo $text ?></h5><?php } ?>
        </a>
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