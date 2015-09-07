<?php
class elementListRadioBoxHelper extends  elementHelper
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
        $params = new JRegistry;
        $params->loadString($block->params);

        $size=$params->get('size','');
        $is_column=$params->get('is_column',true);

        $label=$params->get('label','');
        $name=$params->get('name','');
        $id=$params->get('id','');
        $class=$params->get('class','');
        $value=$params->get('value','');
        $enable_submit=$params->get('enable_submit',1);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $bindingSource=$params->get('data')->bindingSource;
        $key=$params->get('key','id');
        $value=$params->get('value','title');
        $items=$params->get('items','');
        if(!$items&&$bindingSource){
            $items=parent::getValueDataSourceByKey($bindingSource);
        }
        $data_value_selected=$params->get('data')->value_selected;


        if($data_value_selected){
            $data_value_selected=parent::getValueDataSourceByKey($data_value_selected);
        }

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-listradiobox enable-item-resizable <?php echo $is_column?'div-column':'' ?>  item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="block-item block-item-listradiobox "    data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>">
                <?php for($i=0;$i<count($items);$i++ ){ ?>
                    <?php $item=$items[$i] ?>
                <label class="checkbox-inline">
                    <input type="radio" <?php echo $data_value_selected==$item->$key?'checked':'' ?> enable-submit="<?php echo $enable_submit?'true':'false' ?>" class="noStyle" id="radio_<?php echo $i ?>" name="<?php echo $name ?>" value="<?php echo $item->$key ?>"> <?php echo $item->$value ?>
                </label>
                <?php } ?>
            </div>


        <?php
        }else{
        ?>
            <div class="block-item block-item-listradiobox "    data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" id="<?php echo $id; ?>" element-type="<?php echo $block->type ?>">
                <?php for($i=0;$i<count($items);$i++ ){ ?>
                    <?php $item=$items[$i] ?>
                    <label class="checkbox-inline">
                        <input type="radio" enable-submit="<?php echo $enable_submit?'true':'false' ?>" <?php echo $data_value_selected==$item->$key?'checked':'' ?> class="noStyle" id="radio_<?php echo $i ?>" name="<?php echo $name ?>" value="<?php echo $item->$key ?>"> <?php echo $item->$value ?>
                    </label>
                <?php } ?>
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