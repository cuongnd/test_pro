<?php

class elementListviewHelper extends elementHelper
{
    function initElement($TablePosition)
    {
        $path = $TablePosition->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
    }

function getHeaderHtml($block, $enableEditWebsite)
{
    $doc=JFactory::getDocument();
    $app = JFactory::getApplication();
    $input = JFactory::getApplication()->input;
    $params = new JRegistry;
    $params->loadString($block->params);


    $html = '';
    ob_start();



if ($enableEditWebsite)
{

    ?>
    <div class="control-element block-item" data-block-id="<?php echo $block->id ?>"
         data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle">
                <i class="glyphicon glyphicon-move"></i></span>
        <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
           class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
        <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
           class="remove label label-danger remove-element" href="javascript:void(0)"><i
                class="glyphicon-remove glyphicon"></i></a>
        <?php
        echo elementListviewHelper::render_element($listview, $block, $enableEditWebsite);
        } else {
            echo elementListviewHelper::render_element($listview, $block, $enableEditWebsite);
        }

        $html .= ob_get_clean();
        return $html;
        }


        public function render_element($listview, $block, $enableEditWebsite = false)
        {
            $doc=JFactory::getDocument();
            $doc->addLessStyleSheet(JUri::root().'/media/elements/ui/listview.less');
            $params = new JRegistry;
            $params->loadString($block->params);
            $disable=$params->get('element.disable',false);
            $disable=JUtility::toStrictBoolean($disable);
            if($disable)
                return;
            require_once JPATH_ROOT . '/media/kendotest/php/lib/DataSourceResult.php';
            require_once JPATH_ROOT . '/media/kendotest/php/lib/Kendo/Autoload.php';
            $doc->addScript(JUri::root().'/media/elements/ui/listview.js');

            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.core.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.data.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.virtuallist.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.list.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.dropdownlist.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.pager.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.userevents.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.draganddrop.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.sortable.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.menu.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.columnmenu.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.popup.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.binder.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.filtermenu.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.editable.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.validator.js');
            $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.listview.js');



            $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.default.less');
            $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.common.less');
            $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.less');
            $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.default.less');




            $template=$params->get('config_listview.template','');
            $template=base64_decode($template);
            $template=str_replace('{:this_host:}',JUri::root(),$template);
            $website=JFactory::getWebsite();
            $db=JFactory::getDbo();
            //get menu select
            $website = JFactory::getWebsite();
            $query = $db->getQuery(true);
            $query->from('#__menu As menu');
            $query->select('menu.id as id,CONCAT("{",menu.alias,"}") as alias_name');
            $query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
            $query->where('menuType.website_id=' . (int)$website->website_id);
            $query->where('menuType.client_id=0');
            $query->where('menu.alias!="root"');
            $query->order('menu.title');

            $db->setQuery($query);
            $list_menu = $db->loadObjectList();
            //end get menu

            foreach ($list_menu as $menu) {
                $template = str_replace($menu->alias_name, "Itemid=$menu->id", $template);
            }
            //set style
            $column=$params->get('config_listview.column',1);

            ob_start();
            ?>
            <style type="text/css">
                #<?php echo $list_view_id ?>{
                -webkit-column-count: <?php echo $column ?>;
                -moz-column-count: <?php echo $column ?>;
                column-count: <?php echo $column ?>; /*<?php echo $column ?> in those rules is just placeholder -- can be anything*/
                }
                </style>
            <?php
            $style = ob_get_clean();
            $style = JUtility::remove_string_css($style);
            $doc->addStyleDeclaration($style);



            $colums=$params->get('config_listview.column',1);
            $template=str_replace(array("\r", "\n"), '', $template);
            $template=str_replace('{:this_host:}',JUri::root(),$template);
            $scriptId = "script_ui_listview_" . $block->id;
            ob_start();
            ?>

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    var dataSource = new kendo.data.DataSource({
                        transport: {
                            read: {
                                url: "<?php echo  JUri::root() ?>index.php?option=com_phpmyadmin&task=datasource.ajax_get_data_list_view&block_id=<?php echo $block->id ?>",
                                contentType: "application\/json",
                                type: "POST"
                            }
                        },
                        pageSize: 15,

                    });
                    $('.block-item.block-item-listview[data-block-id="<?php echo $block->id ?>"]').ui_listview({
                        listview_option: {
                            columns:<?php echo $colums ?>,
                            dataSource: dataSource,
                            template: '<?php echo $template ?>',
                            link_detail: "<?php echo $params->get('config_view_grid.link_detail', 0) ?>"
                        },
                        block_id:<?php echo $block->id ?>
                    });

                });
            </script>
            <?php
            $script = ob_get_clean();
            $script = JUtility::remove_string_javascript($script);
            $doc->addScriptDeclaration($script, "text/javascript", $scriptId);





            ob_start();
            $html = '';


            $params = new JRegistry;
            $params->loadString($block->params);

            $template_by_element = $params->get('template_by_element', '');
            if (trim($template_by_element) != '')
                $template_by_element = explode(',', $template_by_element);
            else
                $template_by_element = array();
            $mode_edit_row = $params->get('edit_row_type', 'inline');
            $image_template=$params->get('element.image_template','');
            $enabe_image_template=$params->get('element.enable_image_template',false);
            $enabe_image_template=JUtility::toStrictBoolean($enabe_image_template);
            if($enabe_image_template&&$image_template)
            {
                echo '<img border="0" src="'.$image_template.'" style="width:100%"  />';
            }else{
            ?>

            <div id="ui_listview_<?php echo $block->id ?>" data-block-id="<?php echo $block->id ?>" class="block-item block-item-listview"
                     data-block-parent-id="<?php echo $block->parent_id ?>">

                </div>

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
    <?php
} else {

}
    $html .= ob_get_clean();
    return $html;
}

    function getDevHtml($TablePosition)
    {
        $html = '';
        ob_start();
        ?>
        <div class="tabs" data-block-id="<?php echo $TablePosition->id ?>"
             data-block-parent-id="<?php echo $TablePosition->parent_id ?>">
            <ul id="myTab2" class="nav nav-tabs nav-justified">
                <li><a href="#home2" data-toggle="tab">Home</a></li>

            </ul>

            <div id="myTabContent2" class="tab-content">
                <div class="tab-pane fade active in" id="home2">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia, suscipit, autem sit natus
                        deserunt officia error odit ea minima soluta ratione maxime molestias fugit explicabo aspernatur
                        praesentium quisquam voluptatum fuga delectus quidem quas aliquam minus at corporis libero?
                        Modi, aperiam, pariatur, sequi illum dolore consequuntur aspernatur eos hic officia doloribus
                        magnam impedit autem maiores alias consectetur tempore explicabo. Ducimus, minima, suscipit unde
                        harum numquam ipsa laboriosam cupiditate nemo repellendus at? Dolorum dicta nemo quaerat
                        iusto.</p>
                </div>
            </div>
        </div>
        <?php
        $html .= ob_get_clean();
        return $html;
    }
}

?>