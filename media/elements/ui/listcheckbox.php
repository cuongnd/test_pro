<?php
class elementListCheckBoxHelper extends  elementHelper
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
        $doc->addScript(JUri::root() ."/$dirName/$filename.jquery.js");
        $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);


        $advanced_params = new JRegistry;
        $advanced_params->loadString($block->advanced_params);
        $size=$params->get('size','');

        $label=$params->get('label','');
        $name=$params->get('name','list_checkbox_'.$block->id);
        $id=$params->get('id','');
        $class=$params->get('class','');
        $value=$params->get('value','');
        $enable_submit=$params->get('enable_submit',1);
        $items=$params->get('items','');
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data')->bindingSource;
        $key=$params->get('data')->key;
        $key=$key?$key:'id';
        $value=$params->get('data')->value;
        $value=$value?$value:'title';

        $data_value_selected=$params->get('data')->value_selected;


        if($data_value_selected){
            $data_value_selected=(array)parent::getValueDataSourceByKey($data_value_selected);
            $data_value_selected=JArrayHelper::pivot($data_value_selected);
        }

        if(!$items&&$bindingSource){
            $items=parent::getValueDataSourceByKey($bindingSource);
        }
        //check enable control
        $enable=$params->get('enable','');

        $advanced_params_enable= $advanced_params->get('params.enable',0);
        if($advanced_params_enable){
            $enable=(bool)parent::getValueDataSourceByKey($advanced_params_enable);

        }
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-listcheckbox  " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <?php  echo  elementListCheckBoxHelper::render_element($block); ?>

        <?php
        }else{
            echo  elementListCheckBoxHelper::render_element($block);
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block)
    {
        $params = new JRegistry;
        $params->loadString($block->params);


        $advanced_params = new JRegistry;
        $advanced_params->loadString($block->advanced_params);
        $size=$params->get('size','');
        $is_column=$params->get('is_column',true);

        $label=$params->get('label','');
        $name=$params->get('name','list_checkbox_'.$block->id);
        $id=$params->get('id','');
        $class=$params->get('class','');
        $value=$params->get('value','');
        $enable_submit=$params->get('enable_submit',1);
        $items=$params->get('items','');
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data')->bindingSource;
        $key=$params->get('data')->key;
        $key=$key?$key:'id';
        $value=$params->get('data')->value;
        $value=$value?$value:'title';

        $data_value_selected=$params->get('data')->value_selected;


        if($data_value_selected){
            $data_value_selected=(array)parent::getValueDataSourceByKey($data_value_selected);
            $data_value_selected=JArrayHelper::pivot($data_value_selected);
        }

        if(!$items&&$bindingSource){
            $items=parent::getValueDataSourceByKey($bindingSource);
        }
        //check enable control
        $enable=$params->get('enable',1);

        $advanced_params_enable= $advanced_params->get('params.enable',0);
        if($advanced_params_enable){
            $enable=(bool)parent::getValueDataSourceByKey($advanced_params_enable);
        }
        $html='';
        ob_start();
        ?>
        <div class="block-item block-item-listcheckbox "    data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>">
            <?php for($i=0;$i<count($items);$i++ ){ ?>
                <?php $item=$items[$i] ?>
                <label class="checkbox-inline">
                    <input class="block-item block-item-listcheckbox-item noStyle" <?php echo $enable?'':'disabled' ?>   enable-submit="<?php echo $enable_submit?'true':'false' ?>" <?php echo array_key_exists($item->$key,$data_value_selected)?'checked':'' ?>  type="checkbox"  id="checkbox_<?php echo $i ?>" name="<?php echo $name ?>" value="<?php echo $item->$key ?>"> <?php echo $item->$value ?>
                </label>
            <?php } ?>
        </div>

        <?php
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