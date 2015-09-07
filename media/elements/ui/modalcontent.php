<?php
require_once JPATH_ROOT.'/media/elements/ui/element.php';
class elementModalContentHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path = $TablePosition->path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . "/$dirName/$filename.less";
        $cssOutput = JPATH_ROOT . "/$dirName/$filename.css";
        JUtility::compileLess($lessInput, $cssOutput);

    }

    function getHeaderHtml($block, $enableEditWebsite)
    {
        $path = $block->path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");

        $html = '';
        ob_start();
        if ($enableEditWebsite) {

            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                });
            </script>

            <div class="modal fade" id="modal_block_<?php echo $block->parent_id  ?>" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
               <div  class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">


        <?php
        } else {
            ?>

            <div class="tabs" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" >
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }

    function getFooterHtml($block, $enableEditWebsite)
    {
        $html = '';
        ob_start();
        if ($enableEditWebsite) {

            ?>

            </div>
            <div class="modal-footer">
                <div  class="btn btn-default" data-dismiss="modal">Close</div>
                <div  class="btn btn-primary">Save changes</div>
            </div>
            </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
            </div>
        <?php
        } else {
            ?>
            </div>
        <?php
        }
        $html .= ob_get_clean();
        return $html;
    }

    function getDevHtml($TablePosition)
    {
        $html = '';
        ob_start();
        ?>
        <div class="tab-pane fade" role="tabpanel" data-block-id="<?php echo $TablePosition->id ?>"
             data-block-parent-id="<?php echo $TablePosition->parent_id ?>">

        </div>
        <?php
        $html .= ob_get_clean();
        return $html;
    }
}

?>