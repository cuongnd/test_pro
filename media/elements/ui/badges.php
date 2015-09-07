<?php
include_once ('element.php');
class elementBadgesHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
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
        $class=$params->get('class','');
        $title=$params->get('title','');
        $select_type=$params->get('select_type','');
        $link=$params->get('link','');


        $html='';
        ob_start();
        if($enableEditWebsite) {
            ?>
            <div class="control-element">
            <?php
            if($ajaxGetContent) {
                ?>
                <link href="<?php echo JUri::root()."$dirName/$filename.css" ?>" rel="stylesheet">
                <script src="<?php echo JUri::root()."$dirName/$filename.js" ?>"></script>
            <?php } ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                });
            </script>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>

            <?php
            if($select_type == 1) {
                ?>
                <a href="<?php echo $link ;?>"><?php echo $title;?> <span class="badge">32</span></a>
            <?php
            }else{
                ?>
                <button class="btn <?php echo $class ;?>" type="button"><?php echo $title ;?> <span class="badge">32</span></button>
            <?php }?>
        <?php
        }else{
            if($select_type == 1) {
                ?>
                <a href="<?php echo $link ;?>"><?php echo $title ;?> <span class="badge">32</span></a>
            <?php
            }else{
                ?>
                <button class="btn <?php echo $class ;?>" type="button"><?php echo $title ?> <span class="badge">32</span></button>
            <?php }?>

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


        <?php
        }else{
            ?>
            </div>
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