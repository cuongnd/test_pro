<?php
class elementYesNoHelper extends  elementHelper
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
        $doc->addStyleSheet(JUri::root() . "/media/system/js/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css");
        $doc->addScript(JUri::root().'/media/system/js/bootstrap-switch-master/dist/js/bootstrap-switch.js');
        $params = new JRegistry;
        $params->loadString($block->params);
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-yesno "    data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" data-gs-x="<?php echo $block->gs_x ?>" data-gs-y="<?php echo $block->gs_y ?>" data-gs-width="<?php echo $block->width ?>" data-gs-height="<?php echo $block->height ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle element-move-handle_<?php echo $block->parent_id ?>"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>


        <?php
            echo elementYesNoHelper::render_element($block);
        }else{
            echo elementYesNoHelper::render_element($block);
        ?>

        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block)
    {
        $params = new JRegistry;
        $params->loadString($block->params);
        $enable_submit=$params->get('enable_submit',1);
        $name=$params->get('name','name_'.$block->id);
        $id=$params->get('id','');
        $checked=(int)$params->get('checked',0);
        $label=$params->get('label','');

        $placeholder=$params->get('placeholder','');
        $switch_change_by_code_php=$params->get('switch_change_by_code_php',0);
        $switch_change='';
        if($switch_change_by_code_php==1) {
            $switch_change = $params->get('switch_change', '');


            if (base64_encode(base64_decode($switch_change, true)) === $switch_change) {
                $switch_change = base64_decode($switch_change);
            } else {
                $switch_change = '';
            }

            jimport('joomla.filesystem.file');
            $file_php = JPATH_ROOT . '/cache/' . JUserHelper::genRandomPassword() . '.php';
            JFile::write($file_php, $switch_change);
            ob_start();
             include_once($file_php);
            $switch_change=ob_get_clean();
            JFile::delete($file_php);
        }
        $data_checked=$params->get('data',new stdClass())->checked;
        if($data_checked){

            $checked=(bool)parent::getValueDataSourceByKey($data_checked);
        }
        $html='';

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('input[name="<?php echo $name ?>"]').on('switchChange.bootstrapSwitch', function(event, state) {
                    <?php echo $switch_change_by_code_php==1?$switch_change:'' ?>
                });
            });
        </script>
        <input enable-submit="<?php echo $enable_submit?'true':'false' ?>" class="block-item block-item-yesno noStyle"   data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  element-type="<?php echo $block->type ?>" type="checkbox" name="<?php echo $name ?>" <?php echo $checked?'checked':'' ?>  >

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