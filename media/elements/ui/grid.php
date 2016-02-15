<?php

class elementGridHelper extends elementHelper
{
    function initElement($TablePosition)
    {
        $path = $TablePosition->ui_path;
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
        $app = JFactory::getApplication();
        $input = JFactory::getApplication()->input;
        $datasource = $block->datasource;

        $params = new JRegistry;
        $params->loadString($block->params);
        $use_file_template_row = $params->get('use_file_template_row', 0);
        $file_template_row = $params->get('file_template_row', '');
        $enable_ajax_load_data = $params->get('enable_ajax_load_data', 1);
        $linkDetail = $params->get('link_detail', '');
        $width_column_action = $params->get('width_column_action', '');
        $width_column_action = $width_column_action != '' ? $width_column_action : 100;
        $data = $params->get('data', new stdClass());
        $menu = $app->getMenu('site');
        $active_menu = $menu->getActive();
        $filter_bys = $data->filter_by;
        $filter_bys = explode(',', $filter_bys);
        $url = '';
        if (count($filter_bys)) {
            foreach ($filter_bys as $filter_by) {
                $url .= '&' . $filter_by . '=' . $input->get($filter_by, '');
            }
        }
        require_once JPATH_ROOT . '/media/kendotest/php/lib/DataSourceResult.php';
        require_once JPATH_ROOT . '/media/kendotest/php/lib/Kendo/Autoload.php';


        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        //kendo all


        // $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.all.js');


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

        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.combobox.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.selectable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.groupable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.columnsorter.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.resizable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.window.js');
        //$doc->addScript(JUri::root().'/media/kendotest/php/data/products.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.grid.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.multiselect.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.numerictextbox.js');


        $doc->addScript(JUri::root() . 'media/Kendo_UI_Professional_Q2_2015/src/js/kendo.editor.js');

        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.default.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.common.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.default.less');

        $doc->addStyleSheet(JUri::root() . '/media/elements/ui/grid.css');
        $db = JFactory::getDbo();
        $db->setQuery($datasource);


        $path = $block->ui_path;
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['filename'];
        $dirName = $pathInfo['dirname'];
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root() . "/$dirName/$filename.css");
        $doc->addScript(JUri::root() . "/$dirName/$filename.js");



        $html = '';
        ob_start();


        $transport = new \Kendo\Data\DataSourceTransport();

        $create = new \Kendo\Data\DataSourceTransportCreate();
        $create->url(JUri::root() . 'index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajax_update_Data&type=create&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST');

        $read = new \Kendo\Data\DataSourceTransportRead();

        $read->url(JUri::root() . 'index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.readData&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST');

        $update = new \Kendo\Data\DataSourceTransportUpdate();

        $update->url(JUri::root() . 'index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajax_update_Data&type=update&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST');

        $destroy = new \Kendo\Data\DataSourceTransportDestroy();

        $destroy->url(JUri::root() . 'index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajax_update_Data&type=destroy&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST');

        $transport->create($create)
            ->read($read)
            ->update($update)
            ->destroy($destroy)
            ->parameterMap('function(data) {
              return kendo.stringify(data);
          }');

        $model = new \Kendo\Data\DataSourceSchemaModel();

        $show_column = $params->get('show_column', 'array');
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';


        $db = JFactory::getDbo();
        JTable::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/tables');
        $tableDataSource = JTable::getInstance('DataSource', 'JTable');
        $mode_select_column = array();
        $data_source_id = $params->get('data.bindingSource', 0);
        if ($data_source_id) {
            $select_column = parent::getValueDataSourceByKey($data_source_id);
        }


        $mode_select_column = $params->get('mode_select_column', '');
        if ($mode_select_column == '') {

            foreach ($select_column as $key => $column) {
                $item = new stdClass();
                $item->id = $key;
                $item->type = 'string';
                $item->editable = $key == 'id' ? 0 : 1;
                $mode_select_column[] = $item;
            }
        } else {
            $mode_select_column = up_json_decode($mode_select_column, false, 512, JSON_PARSE_JAVASCRIPT);
        }


        $listAddModelColumn = array();


        if (!empty($mode_select_column)) {
            foreach ($mode_select_column as $key => $ob_value) {
                $column = new \Kendo\Data\DataSourceSchemaModelField($ob_value->column_name);
                $validation = new \Kendo\Data\DataSourceSchemaModelFieldValidation();
                $validation->required(false);
                $column->validation($validation);
                if ($ob_value->type) {
                    if ($ob_value->type == 'text') {
                        $ob_value->type = 'string';
                    }
                    $column->type($ob_value->type);
                }

                if ($ob_value->editable == 1) {

                    $column->editable(true);
                } else {
                    $column->editable(false);
                }

                if ($ob_value->required == 1) {
                    //$column->editable(true);
                } else {
                    //$column->editable(false);
                }

                if ($ob_value->type == 'object') {
                    $column->defaultValue(array());
                } else if ($ob_value->type == 'number') {
                    $column->defaultValue(0);
                }

                if ($ob_value->primary_key == 1) {
                    $model->id($ob_value->column_name);
                }

                $listAddModelColumn[] = $column;

            }
        }

        if (count($listAddModelColumn)) {
            $model->addArrayField($listAddModelColumn);
        }
        $schema = new \Kendo\Data\DataSourceSchema();
        $schema->data('data')
            ->model($model)
            ->total('total');

        $page_size = $params->get('page_size', 10);
        $dataSource = new \Kendo\Data\DataSource();
        $dataSource->transport($transport)
            ->batch(true)
            ->pageSize($page_size)
            ->schema($schema)
            ->autoSync(false);


        $attr_id = 'grid_' . $block->id;

        $block->enable_select_item_by_checked = false;
        //template edit row
        $use_template_edit_row = $params->get('use_template_edit_row', 0);
        $template_edit_row_file = $params->get('template_edit_row_file', '');


        $grid_height = $params->get('grid_height', '700');
        $columnMenu = $params->get('columnMenu', true);
        $template_by_element = $params->get('template_by_element', 0);
        $sortable = $params->get('sortable', 1);
        $scrollable = (bool)$params->get('scrollable', 0);
        if ($grid_height == '100%') {
            $scrollable = 0;
        }
        $filterable = $params->get('filterable', true);
        $pageable = $params->get('pageable', 1);

        //config gird toolbar command
        $config_grid_buttom = $params->get('config_view_grid.config_grid_buttom', '');


        //render editor


        if ($enableEditWebsite) {

            ?>
            <div class="control-element block-item" data-block-id="<?php echo $block->id ?>"
            data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="remove label label-danger remove-element" href="javascript:void(0)"><i
                    class="glyphicon-remove glyphicon"></i></a>
            <?php echo elementGridHelper::render_element($block, $enableEditWebsite); ?>


            <?php


        } else {
            echo elementGridHelper::render_element($block, $enableEditWebsite);
            ?>

            <?php
        }
        if ($use_file_template_row) {
            include JPATH_ROOT . "/layouts/kendo_grid_layout/$file_template_row";
        }

        if ($use_template_edit_row) {
            include JPATH_ROOT . "/layouts/kendo_grid_layout/$template_edit_row_file";
        }
        $html .= ob_get_clean();
        return $html;
    }


    public function render_element($block, $enableEditWebsite = false)
    {
        $doc = JFactory::getDocument();
        $params = new JRegistry;
        $params->loadString($block->params);
        $gridformartheader = $params->get('config_view_grid.formart_header', '');
        $gridformartheader = base64_decode($gridformartheader);
        $gridformartheader = json_decode($gridformartheader);
        $db=JFactory::getDbo();
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        //get menu select
        $website = JFactory::getWebsite();
        $query = $db->getQuery(true);
        $query->from('#__menu As menu');
        $query->select('menu.id as id,CONCAT("{",menu.alias,"}") as title');
        $query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
        $query->where('menuType.website_id=' . (int)$website->website_id);
        $query->where('menuType.client_id=0');
        $query->where('menu.alias!="root"');
        $query->order('menu.title');

        $db->setQuery($query);
        $list_menu = $db->loadObjectList();
        //end get menu

        $mode_select_column_template = $gridformartheader->mode_select_column_template;
        if ($mode_select_column_template == '') {
            $mode_select_column_template = array();
            foreach ($select_column as $key => $column) {
                $item = new stdClass();
                $item->column_name = $key;
                $item->title = str_replace('_', ' ', $key);
                $item->type = 'string';
                $mode_select_column_template[] = $item;
            }
        } else {
            $mode_select_column_template = up_json_decode($mode_select_column_template, false, 512, JSON_PARSE_JAVASCRIPT);
        }
        $mode_select_column_template_stander = array();
        if (!empty($mode_select_column_template)) {
            elementGridHelper::add_column_to_header_grid($mode_select_column_template, $mode_select_column_template_stander, $block, $list_menu);

        }
        $template_by_element = $params->get('template_by_element', '');
        if (trim($template_by_element) != '')
            $template_by_element = explode(',', $template_by_element);
        else
            $template_by_element = array();

        $hide_footer =$params->get('config_view_grid.hide_footer',false);
        $input=JFactory::getApplication()->input;
        $post_data=$input->getArray();
        unset($post_data['option']);
        unset($post_data['view']);
        $string_post_data=http_build_query($post_data);
        //setup script grid
        $scriptId = "script_ui_grid_" . $block->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#ui_grid_<?php echo $block->id ?>').ui_grid({
                    mode_select_column_template:<?php echo json_encode($mode_select_column_template_stander) ?>,
                    hide_footer:<?php echo json_encode($hide_footer) ?>,
                    grid_option: {
                        dataSource: {
                            transport: {
                                create: {
                                    url: "<?php echo  JUri::root() ?>index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajax_update_Data&type=create&block_id=<?php echo $block->id ?>",
                                    contentType: "application\/json",
                                    type: "POST"
                                },
                                read: {
                                    url: "<?php echo  JUri::root() ?>index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.readData&block_id=<?php echo $block->id ?>&<?php echo $string_post_data ?>",
                                    contentType: "application\/json",
                                    type: "POST"
                                },
                                update: {
                                    url: "<?php echo  JUri::root() ?>index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajax_update_Data&block_id=<?php echo $block->id ?>",
                                    contentType: "application\/json",
                                    type: "POST"
                                },
                                destroy: {
                                    url: "<?php echo  JUri::root() ?>index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajax_update_Data&block_id=<?php echo $block->id ?>",
                                    contentType: "application\/json",
                                    type: "POST"
                                },
                                parameterMap: function (data) {
                                    return kendo.stringify(data);
                                }
                            },
                            batch: true,
                            pageSize: 10,
                            schema: {
                                data: "data",
                                model: {
                                    fields: [{
                                        field: null,
                                        validation: {required: false},
                                        type: "string",
                                        editable: false
                                    }]
                                },
                                total: "total"
                            },
                            autoSync: false
                        },
                        height: "700",
                        columnMenu: true,
                        filterable: true,
                        sortable: 1,
                        editable: {
                            mode: "<?php echo $params->get('config_view_grid.edit_row_type', 'inline') ?>",
                            confirmation: "do you want delete this item ?"
                        },
                        autoBind: true,
                        scrollable: true,
                        pageable: {
                            input: true,
                            pageSizes: [10, 20, 30, 40, 50, 100, 200, 300, 400, 500, 1000],
                            info: true,
                            refresh: true,
                            buttonCount: 5
                        },
                        groupable: false,
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
        //end setup script grid


        $html = '';
        ob_start();
        ?>
        <div id="ui_grid_<?php echo $block->id ?>" class="block-item block-item-grid"></div>
        <?php

        $html .= ob_get_clean();
        return $html;
    }

    public function add_column_to_header_grid($mode_select_column_template, &$mode_select_column_template_stander = array(), $block, $list_menu)
    {
        $listAddColumn = array();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $levels = $user->getAuthorisedViewLevels();
        $menu = $app->getMenu('site');
        $active_menu = $menu->getActive();
        foreach ($mode_select_column_template as $key => $ob_value) {
            $access = $ob_value->access;
            if ($access && !in_array($access, $levels)) {
                continue;
            }
            $column = new stdClass();
            if ($ob_value->column_title) {
                $column->title=$ob_value->column_title;
            } else {
                $column->title=str_replace('_', ' ', $ob_value->column_name);
            }
            if ($ob_value->column_name) {
                $column->field=$ob_value->column_name;
            }
            if ($ob_value->checked==1) {
                $column->title="<div class='checkbox'><label><input id='check_all_" . $block->id . "', type='checkbox', class='check_box_all' /><span>" . $ob_value->column_title . "</span></label></div>";
            }

            if (is_numeric($ob_value->column_width)) {
                $column->width=(int)$ob_value->column_width;
            } else {
                $column->width=100;
            }
            if ($ob_value->template != '') {
                $template = base64_decode($ob_value->template);
                $template = str_replace('{active_menu}', "Itemid=$active_menu->id", $template);
                $template = str_replace('{this_host}', JUri::root(), $template);
                foreach ($list_menu as $menu) {

                    $template = str_replace($menu->title, "Itemid=$menu->id", $template);
                }


                $column->template=$template;


            }
            if ($ob_value->checked==1) {
                $column->template="<div class='checkbox'><label><input class='check_box' value='#:" . $ob_value->column_name . "#' type=\"checkbox\" /><span>#:" . $ob_value->column_name . "#</span></label></div>";
            }
            $editor_type = strtolower($ob_value->editor_type);

            $link_key = $ob_value->link_key;
            if ($ob_value->sortable == 1) {
                $column->sortable=true;
            } else {
                $column->sortable=false;
            }
            if (strtolower($ob_value->column_name) == 'ordering') {
                $column->sortable=true;
                $column->title=($ob_value->column_title ? $ob_value->column_title : 'ordering') . "<a onclick=" . '' . " href='javascript:void(0)'><i  class='en-bolt' /><a/>";

            }
            if ($ob_value->filterable == 1) {
                $column->filterable=true;
            } else {
                $column->filterable=false;
            }

            if ($ob_value->menu == 1) {
                $column->menu=true;
            } else {
                $column->menu=false;
            }

            if ($ob_value->locked == 1) {
                $column->locked=true;
            }
            if ($ob_value->show_command) {
                $show_command = explode(',', $ob_value->show_command);
                $column->command=array();
                foreach ($show_command as $command) {
                    $column->command[]=$command;
                }
            }
            if (is_array($ob_value->children) && count($ob_value->children) > 0) {
                $mode_select_column_template1 = $ob_value->children;
                unset($column->field);
                unset($column->command);
                elementGridHelper::add_column_to_header_grid($mode_select_column_template1, $column->columns, $block, $list_menu);
            }
            $mode_select_column_template_stander[]=$column;



        }

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
        return $html;
    }
}

?>