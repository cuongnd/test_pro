<?php
include_once ('element.php');
$doc=JFactory::getDocument();


class elementChartHelper extends  elementHelper
{
    function initElement($TablePosition)
    {
        $path=$TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename=$pathInfo['filename'];
        $dirName=$pathInfo['dirname'];
        $doc=JFactory::getDocument();
        JHtml::_('jquery.framework');
        $doc->addScript(JUri::root().'/media/Highcharts-4.1.1/js/highcharts.js');
        $doc->addScript(JUri::root().'/media/Highcharts-4.1.1/js/modules/data.js');
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
        $website= JFactory::getWebsite();
        $params = new JRegistry;
        $params->loadString($block->params);
        $chartType=$params->get('chartType','line');
        $class=$params->get('class','');
        ob_start();
        $scriptId= "media_elements_ui_chart_{$website->website_id_element}_{$block->id}";
        $chartId="chart_{$website->website_id}_element_{$block->id}";
        ?>
        <script type="text/javascript" id="<?php echo $scriptId ?>">
            jQuery(document).ready(function ($) {
                var options_<?php echo $website->website_id ?>_element_<?php echo $block->id ?> = {
                    chart: {
                        type: "<?php echo $chartType ?>"
                    },
                    series: [{
                        name: 'Jane',
                        data: [1, 0, 4]
                    }]
                };
                $('#<?php echo $chartId ?>').highcharts(options_<?php echo $website->website_id ?>_element_<?php echo $block->id ?>);
            });
        </script>
        <?php
        $htmlScript=ob_get_clean();
        require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
        $htmlScript = str_get_html($htmlScript);
        $script= $htmlScript->find('script',0)->innertext;
        $doc->addScriptDeclaration($script,'text/javascript',$scriptId);
        $html='';

        ob_start();

        if($enableEditWebsite) {
            ?>
            <div class="control-element block-item" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"class="remove label label-danger remove-element" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
            <div style="width: 100%;height: 300px" id="<?php echo $chartId ?>"></div>
        <?php
        }else{
            ?>
            <div id="<?php echo $chartId ?>"></div>
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