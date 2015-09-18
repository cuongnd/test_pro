<?php
class elementSelect2DropdownHelper extends  elementHelper
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
        $css_class=$block->css_class;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $doc->addScript(JUri::root().'/media/system/js/x-editable-master/test/libs/mockjax/jquery.mockjax.js');
        $doc->addScript(JUri::root().'/media/jui_front_end/js/select2.jquery.js');
        $doc->addScript(JUri::root().'/media/system/js/x-editable-master/dist/bootstrap3-editable/js/bootstrap-editable.js');
        $doc->addStyleSheet(JUri::root().'/media/system/js/x-editable-master/dist/bootstrap3-editable/css/bootstrap-editable.css');
        $params = new JRegistry;
        $params->loadString($block->params);

        $text=$params->get('text','text_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $title=$params->get('title','title_'.$block->id);
        $name=$params->get('name','name_'.$block->id);
        $editable=$params->get('editable',true);
        $textSource=$params->get('data')->text;
        if($textSource)
            $text=parent::getValueDataSourceByKey($textSource);
        if(is_object($text)||is_array($text))
        {
            $text="object or array";
        }
        $data_source= $params->get('data.data_source','');
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element item_control item_control_<?php echo $block->parent_id ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">
                <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
                <?php echo elementselect2dropdownHelper::render_element($block,$enableEditWebsite); ?>
        <?php
        }else{
            ?>
            <?php echo elementselect2dropdownHelper::render_element($block,$enableEditWebsite); ?>

        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite=false)
    {
        $params = new JRegistry;
        $params->loadString($block->params);

        $text=$params->get('text','text_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $title=$params->get('title','title_'.$block->id);
        $name=$params->get('name','name_'.$block->id);
        $editable=$params->get('editable',true);
        $textSource=$params->get('data.bindingSource','');

        $source_key=$params->get('data.source_key','');
        $source_key=explode('.',$source_key);
        $source_key=end($source_key);


        $source_value=$params->get('data.source_value','');
        $source_value=explode('.',$source_value);
        $source_value=end($source_value);

        if($textSource)
            $text=parent::getValueDataSourceByKey($textSource);
        if(is_object($text)||is_array($text))
        {
            $text="object or array";
        }
        $data_source= $params->get('data.data_source','');

        $data_type= $params->get('data_type','text');
        $allow_clear= $params->get('allow_clear',true);

        $html='';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                if(typeof init_ui_select2_dropdown==="undefined")
                {
                    var init_ui_select2_dropdown=element_ui_select2_dropdown.init_ui_select2_dropdown();
                }
            });
        </script>

        <a
            data-type="select2"
            data-allow_clear="<?php echo $allow_clear ?>"
            data-pk="<?php echo $source_key ?>"
            data-value="<?php echo $text ?>"
            data-source_key="<?php echo $source_key ?>"
            data-source_value="<?php echo $source_value ?>"
            data-url="<?php echo JUri::root().'/index.php?option=com_phpmyadmin&task=datasource.update_data_by_editable&block_id='.$block->id ?>"
            id="<?php echo $id ?>" data-title="<?php echo $title  ?>" <?php echo $editable?'editable="true"':'' ?> class="block-item select2dropdown <?php echo $css_class ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >
            <?php echo $text ?>
        </a>

        <?php
        $html.=ob_get_clean();
        return  $html;
    }
    function getFooterHtml($block,$enableEditWebsite)
    {
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            </a></div>
        <?php
        }else{
            ?>
        </a>
    <?php
        }
        $html.=ob_get_clean();
        return $html;
    }

}
?>