<?php
class elementFormWizardHelper extends  elementHelper
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
        if($enableEditWebsite)
            $doc->addScript(JUri::root() . "/media/elements/ui/divrow.js");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");

        $params = new JRegistry;
        $params->loadString($block->params);
        $title=$params->get('title','');
        $move=$params->get('move','');
        $refresh=$params->get('refresh','');
        $toggle=$params->get('toggle','');
        $close=$params->get('close','');

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element control-element-form-wizard element-form-wizard block-item " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">

            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div class="panel panel-primary <?php echo $move;?> <?php echo $refresh;?> <?php echo $close;?>"><!--panelMove toggle panelRefresh panelClose-->
                                                                                                             <!-- Start .panel -->
            <div class=panel-heading>
                <h4 class=panel-title><?php echo $title;?></h4>
            </div>
            <div class="panel-body">
        <?php
        }else{
            ?>
            <div class="panel panel-primary element-form-wizard <?php echo $move;?> <?php echo $refresh;?> <?php echo $toggle;?> <?php echo $close;?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
            <!-- Start .panel -->
            <div class=panel-heading>
                <h4 class=panel-title><?php echo $title;?></h4>
            </div>
            <div class="panel-body">
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }
    public function render_element($block,$enableEditWebsite)
    {

    }
    function getFooterHtml($block,$enableEditWebsite)
    {
        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>

            </div>
            <div class="panel-footer">
                <button type="button" disabled="disabled" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="btn btn-danger prev-from-wizard-content pull-left"><i class="fa-chevron-left"></i>Prev</button>
                <button type="button" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="btn btn-danger next-from-wizard-content pull-right">Next<i class="fa-chevron-right"></i></button>
            </div>
            </div>
        <?php
        }else{
            ?>


            </div>
            <div class="panel-footer">
                <button type="button" disabled="disabled" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="btn btn-danger prev-from-wizard-content pull-left"><i class="fa-chevron-left"></i>Prev</button>
                <button type="button" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="btn btn-danger next-from-wizard-content pull-right">Next<i class="fa-chevron-right"></i></button>
            </div>
            </div>
        <?php
        }
        $html.=ob_get_clean();
        return $html;
    }

}
?>