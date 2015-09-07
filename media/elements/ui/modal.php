<?php
require_once JPATH_ROOT.'/media/elements/ui/element.php';
class elementModalHelper extends  elementHelper
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
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");
        $params = new JRegistry;
        $params->loadString($block->params);
        $css_class=$block->css_class;
        $css_class=explode(',',$css_class);
        $css_class=implode(' ',$css_class);
        $text=$params->get('text','text_'.$block->id);
        $id=$params->get('id','id_'.$block->id);
        $name=$params->get('name','name_'.$block->id);
        $modal_title=$params->get('modal_title','title_'.$block->id);
        $enable_sortable=$params->get('enable_sortable',1);

        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div  class="control-element control-element-modal   " data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_modal.init_modal();

                });
            </script>
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>"  data-block-parent-id="<?php echo $block->parent_id ?>" class="add label label-danger add-row"><i class="glyphicon glyphicon-plus"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <button id="<?php echo $id ?>" class="block-item block-item-modal <?php echo $css_class ?>" name="<?php echo $name ?>"  type="button" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  data-toggle="modal" data-target="#modal_block_<?php echo $block->id  ?>"  element-type="<?php echo $block->type ?>"><?php echo $text ?></button>
                <!-- Modal -->
                <div class="block-item-modal-content modal fade"  data-keyboard="false" data-backdrop="static" id="modal_block_<?php echo $block->id  ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><?php echo $modal_title ?></h4>
                        </div>
                        <div class="control-element control-element-modal modal-body" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
                            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
                            <a href="javascript:void(0)" data-block-id="<?php echo $block->id ?>" element-type="<?php echo $block->type ?>"  data-block-parent-id="<?php echo $block->parent_id ?>" class="add label label-danger add-row"><i class="glyphicon glyphicon-plus"></i></a>
                            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
                            <div class="block-item block-item-modal" <?php echo $enable_sortable?'enable-sortable="true"':'' ?> data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>" >

        <?php
        }else{
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    element_ui_modal.init_modal();

                });
            </script>

            <button id="<?php echo $id ?>" class="block-item block-item-modal <?php echo $css_class ?>" name="<?php echo $name ?>"  type="button" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"  data-toggle="modal" data-target="#modal_block_<?php echo $block->id  ?>"  element-type="<?php echo $block->type ?>"><?php echo $text ?></button>
                <!-- Modal -->
                <div class="modal fade"  data-keyboard="false" data-backdrop="static" id="modal_block_<?php echo $block->id  ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><?php echo $modal_title ?></h4>
                        </div>
                        <div class=" modal-body" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">



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
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </div>
        <?php
        }else{
        ?>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    <?php
    }
    $html.=ob_get_clean();
    return $html;
    }
    function getDevHtml($TablePosition)
    {
        $html='';
        ob_start();
        ?>
        <div class="tabs" data-block-id="<?php echo $TablePosition->id ?>" data-block-parent-id="<?php echo $TablePosition ->parent_id ?>">
            <ul id="myTab2" class="nav nav-tabs nav-justified">
                <li><a href="#home2" data-toggle="tab">Home</a></li>

            </ul>
            <div id="myTabContent2" class="tab-content">
                <div class="tab-pane fade active in" id="home2">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia, suscipit, autem sit natus deserunt officia error odit ea minima soluta ratione maxime molestias fugit explicabo aspernatur praesentium quisquam voluptatum fuga delectus quidem quas aliquam minus at corporis libero? Modi, aperiam, pariatur, sequi illum dolore consequuntur aspernatur eos hic officia doloribus magnam impedit autem maiores alias consectetur tempore explicabo. Ducimus, minima, suscipit unde harum numquam ipsa laboriosam cupiditate nemo repellendus at? Dolorum dicta nemo quaerat iusto.</p>
                </div>
            </div>
        </div>
        <?php
        $html.=ob_get_clean();
        return $html;
    }
}
?>