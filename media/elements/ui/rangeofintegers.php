<?php
class elementRangeOfIntegersHelper extends  elementHelper
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
        $doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css");
        $doc->addScript(JUri::root().'/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');


        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-passengers item_control item_control_<?php echo $block->parent_id ?>"  <?php echo $enable_resizable_for_control==1?'enabled-resizable="true"':'' ?>  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
        <?php
            echo elementRangeOfIntegersHelper::render_element($block,$enableEditWebsite);
        }else{
            echo elementRangeOfIntegersHelper::render_element($block,$enableEditWebsite);
        ?>

        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {
        $list_param=array(
            'name_from,element_config.name_from',
            'name_to,element_config.name_to',
            'step,element_config.step',
            'min,element_config.min',
            'max,element_config.max',
            'data.text,data.bindingSource',
            'text,element_config.text',
            'name,element_config.name',
            'placeholder,element_config.placeholder',
            'inputmask,element_config.config_update_inputmask'
        );
        parent::merge_param($list_param,$block->id);


        $params = new JRegistry;
        $params->loadString($block->params);


        $name_from=$params->get('element_config.name_from','name_from_'.$block->id);
        $name_to=$params->get('element_config.name_to','name_to_'.$block->id);
        $enable_resizable_for_control=$params->get('enable_resizable_for_control',0);
        $id=$params->get('element_config.id','');
        $step=(int)$params->get('element_config.step',1);
        $type=$params->get('element_config.type','double');
        $min=(int)$params->get('element_config.min',0);
        $max=(int)$params->get('element_config.max',100);
        $text_from=(int)$params->get('element_config.text_from',0);
        $text_to=(int)$params->get('element_config.text_to',100);
        $enable_submit=$params->get('element_config.enable_submit',1);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $data_text_from=$params->get('element_config.data.text_from',0);
        $data_text_to=$params->get('data.text_to',100);
        if($data_text_from){

            $text_from=parent::getValueDataSourceByKey($data_text_from);
        }
        if($data_text_to){
            $text_to=(int)parent::getValueDataSourceByKey($data_text_to);
        }
        $html='';
        ob_start();
        ?>
        <div   class="block-item block-item-rangeofintegers " data-step="<?php echo $step ?>" data-min="<?php echo $min ?>" data-max="<?php echo $max ?>" data-type="<?php echo $type ?>"   data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>" >
            <input type="hidden" value="<?php echo $text_from ?>" enable-submit="<?php echo $enable_submit?'true':'false' ?>" class="block-item block-item-rangeofintegers-from" name="<?php echo $name_from ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>" />
            <input type="hidden"  value="<?php echo  $text_to ?>" enable-submit="<?php echo $enable_submit?'true':'false' ?>" class="block-item block-item-rangeofintegers-to" name="<?php echo $name_to ?>"  data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>" />

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