<?php
class elementgroupcontrolHelper extends  elementHelper
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
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        if(!$ajaxGetContent) {
            $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
            $doc->addScript(JUri::root() ."/$dirName/$filename.js");
        }
        $params = new JRegistry;
        $params->loadString($block->params);

        $label=$params->get('label','label_'.$block->id);
        $placeholder=$params->get('placeholder','placeholder_'.$block->id);
        $inputType=$params->get('input_type','input');
        $set_type=$params->get('set_type','text');
        $name=$params->get('name','name_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $colspan=$params->get('colspan','');
        $rowspan=$params->get('rowspan','');
        $value=$params->get('value','value');
        $value=parent::getValueDataSourceByKey($value);
        $title=$params->get('title','title_'.$block->id);
        $formType=$params->get('formtype','');
        $required=$params->get('required',true);

        $columnWidth=$params->get('column_width','');
        $columnWidth=explode(':',$columnWidth);
        $list_item_radio_checkbox=$params->get('list-radiocheckbox','');

        $html='';
        ob_start();
        if($enableEditWebsite) :
            ?>
            <div class="control-element block-item" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <?php if($columnWidth){ ?>
            <div class="form-group">
                <label class="<?php echo $columnWidth[0] ?> edit_content_group_control"  for="exampleInputEmail1"><?php echo $title ?></label>
                <div class="<?php echo $columnWidth[1] ?>">
                    <<?php echo $inputType ?> value="<?php echo $value ?>" name="<?php echo $name ?>"  type="<?php echo $set_type ?>" class="form-control" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>">
                </div>
            </div>
            <?php }else{ ?>
                <div class="form-group">
                    <label for="exampleInputEmail1" class="edit_content_group_control" ><?php echo $title ?></label>
                    <<?php echo $inputType ?> name="<?php echo $name ?>" value="<?php echo $value ?>" type="<?php echo $set_type ?>" class="form-control" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>">
                </div>
            <?php }
        else:
            ?>
            <?php if($columnWidth){ ?>
            <div class="form-group">
                <label class="<?php echo $columnWidth[0] ?> " for="exampleInputEmail1"><?php echo $title ?></label>
                <div class="<?php echo $columnWidth[1] ?>">
                    <<?php echo $inputType ?> name="<?php echo $name ?>" type="<?php echo $set_type ?>" class="form-control" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>">
                </div>
            </div>
        <?php }else{ ?>
            <div class="form-group">
                <label for="exampleInputEmail1" class=""><?php echo $title ?></label>
                <<?php echo $inputType ?> name="<?php echo $name ?>" type="<?php echo $set_type ?>" class="form-control" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>">
            </div>
        <?php }
        endif;
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