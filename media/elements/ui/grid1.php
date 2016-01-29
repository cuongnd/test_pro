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

        $doc->addStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.default.less','text/css','',array('rel'=>'stylesheet/less'));
        $doc->addStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.common.less','text/css','',array('rel'=>'stylesheet/less'));
        $doc->addStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.less','text/css','',array('rel'=>'stylesheet/less'));
        $doc->addStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.default.less','text/css','',array('rel'=>'stylesheet/less'));
        $doc->addScript(JUri::root().'/media/Kendo_UI_Professional_Q2_2015/src/build/less-js/dist/less-1.5.0.js');






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


        $html = '';
        ob_start();


        $transport = new \Kendo\Data\DataSourceTransport();

        $create = new \Kendo\Data\DataSourceTransportCreate();
        $create->url(JUri::root() . 'index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=create&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST');

        $read = new \Kendo\Data\DataSourceTransportRead();

        $read->url(JUri::root() . 'index.php?option=com_phpmyadmin&task=datasource.readData&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST')

        ;

        $update = new \Kendo\Data\DataSourceTransportUpdate();

        $update->url(JUri::root() . 'index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=update&block_id=' . $block->id . $url)
            ->contentType('application/json')
            ->type('POST');

        $destroy = new \Kendo\Data\DataSourceTransportDestroy();

        $destroy->url(JUri::root() . 'index.php?option=com_phpmyadmin&task=datasource.ajax_update_Data&type=destroy&block_id=' . $block->id . $url)
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


        $mode_select_column_template = $params->get('mode_select_column_template', '');
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

        $attr_id = 'grid_' . $block->id;
        $grid = new \Kendo\UI\Grid($attr_id);
        $block->enable_select_item_by_checked = false;
        $listAddColumn = array();
        if (!empty($mode_select_column_template)) {
            $listAddColumn = elementGridHelper::add_column_to_header_grid($mode_select_column_template, $block, $list_menu);

        }
        if (count($listAddColumn)) {
            $grid->addArrayColumn($listAddColumn);
        }
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
        $toolbarCommands = $params->get('toolbar_commands', array());
        if (count($toolbarCommands)) {
            $listToolbarCommand = array();
            foreach ($toolbarCommands as $command) {
                if ($command == 'add') {
                    $listToolbarCommand[] = new \Kendo\UI\GridToolbarItem('create');
                } else
                    if ($command == 'pdf') {
                        $listToolbarCommand[] = new \Kendo\UI\GridToolbarItem('pdf');
                    } else
                        if ($command == 'csv') {
                            $listToolbarCommand[] = new \Kendo\UI\GridToolbarItem('csv');
                        }
            }
            $grid->addToolbarArrayItem($listToolbarCommand);

        }
        //
        $grid_pageable = new \Kendo\UI\GridPageable();
        $grid_pageable->input(true);
        $grid_pageable->pageSizes(array(10, 20, 30, 40, 50, 100, 200, 300, 400, 500, 1000));
        $grid_pageable->info(true);
        $grid_pageable->refresh(true);
        $grid_pageable->buttonCount(5);



        if ($use_file_template_row) {
            $grid->rowTemplateId(str_replace('.php', '', $file_template_row))
                ->altRowTemplateId('alt_' . str_replace('.php', '', $file_template_row));
        }
        $editAble = new \Kendo\UI\GridEditable();
        if ($use_template_edit_row == 1) {

            $editAble->templateId(str_replace('.php', '', $template_edit_row_file));
        }

        $mode_edit_row = $params->get('edit_row_type', 'inline');
        $editAble->mode($mode_edit_row);
        $editAble->confirmation(JText::_('do you want delete this item ?'));
        $grid
            ->dataSource($dataSource)
            ->height($grid_height)
            ->columnMenu($columnMenu)
            ->filterable($filterable)
            ->sortable($sortable)
            ->editable($editAble)
            ->autoBind($enable_ajax_load_data == 1 ? true : false)
            ->scrollable(true)
            ->pageable($grid_pageable)
            ->groupable($groupable)
            ->dataBound('onDataBound_'.$block->id)
            ->dataBinding('onDataBinding_'.$block->id)
            ->change('onChange_'.$block->id)
            ->edit("edit_row_".$block->id)
            ->save("save_row_".$block->id)
            ->cancel("cancel_row_".$block->id)
        ;

        //render editor


        if ($enableEditWebsite) {

            ?>
            <div class="control-element block-item" data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>" element-type="<?php echo $block->type ?>">
            <span data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
                  class="drag label label-default  element-move-handle"><i class="glyphicon glyphicon-move"></i></span>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="menu label config-block label-danger menu-list" href="javascript:void(0)"><i class="im-menu2"></i></a>
            <a data-block-id="<?php echo $block->id ?>" data-block-parent-id="<?php echo $block->parent_id ?>"
               class="remove label label-danger remove-element" href="javascript:void(0)"><i
                    class="glyphicon-remove glyphicon"></i></a>
            <?php echo elementGridHelper::render_element($grid, $block,$enableEditWebsite); ?>
            <?php ?>
            <?php


        } else {
            echo elementGridHelper::render_element($grid, $block,$enableEditWebsite);
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

    public function add_column_to_header_grid($mode_select_column_template, $block, $list_menu)
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


            if (is_array($ob_value->children)&&count($ob_value->children)>0) {
                $column = new \Kendo\UI\GridColumn();
                $mode_select_column_template1 = $ob_value->children;
                $columns=elementGridHelper::add_column_to_header_grid($mode_select_column_template1, $block, $list_menu);

                $column->title($ob_value->column_title)->add_args_column($columns);
                if ($ob_value->show == 1) {
                    $listAddColumn[] = $column;
                }
            }else {
                $column = new \Kendo\UI\GridColumn();
                $column->field($ob_value->column_name);
                if ($ob_value->show_command) {
                    $show_command = explode(',', $ob_value->show_command);
                    foreach ($show_command as $command) {
                        $column->addCommandItem($command);
                    }
                }


                if ($ob_value->column_title) {
                    $column->title($ob_value->column_title);
                } else {
                    $column->title(str_replace('_', ' ', $ob_value->column_name));
                }
                if ($ob_value->button_checked) {
                    $block->enable_select_item_by_checked = true;
                    $column->title("<div class='checkbox'><label><input id='check_all_" . $block->id . "', type='checkbox', class='check_box_all' /><span>".$ob_value->column_title."</span></label></div>");
                }

                if (is_numeric($ob_value->column_width)) {
                    $column->width((int)$ob_value->column_width);
                }else
                {
                    $column->width(100);
                }
                if ($ob_value->show_command) {
                    $column->width(300);
                }
                if ($ob_value->template != '') {
                    $template = base64_decode($ob_value->template);
                    $template = str_replace('{active_menu}', "Itemid=$active_menu->id", $template);
                    $template = str_replace('{this_host}', JUri::root(), $template);

                    foreach ($list_menu as $menu) {
                        $template = str_replace($menu->alias, "Itemid=$menu->id", $template);
                    }

                    $column->template($template);


                }
                if ($ob_value->button_checked) {
                    $column->template("<div class='checkbox'><label><input class='check_box' value='#:".$ob_value->column_name."#' type=\"checkbox\" /><span>#:".$ob_value->column_name."#</span></label></div>");
                }
                $editor_type = strtolower($ob_value->editor_type);
                $link_key = $ob_value->link_key;
                switch ($editor_type) {
                    case 'dropdownlist':
                        $function = elementGridHelper::render_dropdown_list("dropdown_$ob_value->column_name", $ob_value, $block);
                        $column->editor($function);
                        break;
                    case 'multiselect':
                        $function = elementGridHelper::render_multiselect("multiselect_$ob_value->column_name", $ob_value, $block);
                        $column->editor($function);
                        break;
                    case 'editor':
                        break;
                    case 'browser_image':
                        $function = elementGridHelper::render_browser_image("render_browser_image_$ob_value->column_name", $ob_value, $block);
                        $column->editor($function);
                        break;
                    default:
                }
                if ($ob_value->sortable == 1) {
                    $column->sortable(true);
                } else {
                    $column->sortable(false);
                }
                if (strtolower($ob_value->column_name) == 'ordering') {
                    $column->sortable(true);
                    $column->title(($ob_value->column_title ? $ob_value->column_title : 'ordering') . "<a onclick=" . '' . " href='javascript:void(0)'><i  class='en-bolt' /><a/>");

                }
                if ($ob_value->filterable == 1) {
                    $column->filterable(true);
                } else {
                    $column->filterable(false);
                }

                if ($ob_value->menu == 1) {
                    $column->menu(true);
                } else {
                    $column->menu(false);
                }
                if ($ob_value->show == 1) {
                    $listAddColumn[] = $column;
                }
                if ($ob_value->locked == 1) {
                    $column->locked(true);
                }
            }



        }
        return $listAddColumn;

    }

    public function render_element($grid, $block,$enableEditWebsite=false)
    {
        ob_start();
        $html = '';

        $grid->attr('data-block-id', $block->id);
        $grid->attr('data-block-parent-id', $block->parent_id);
        $grid->attr('data-enable-select-item-by-checked', $block->enable_select_item_by_checked);

        $params = new JRegistry;
        $params->loadString($block->params);

        $template_by_element = $params->get('template_by_element', '');
        if(trim($template_by_element)!='')
            $template_by_element=explode(',',$template_by_element);
        else
            $template_by_element=array();
        $mode_edit_row = $params->get('edit_row_type', 'inline');
        echo $grid->render();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                element_ui_grid.int_ui_grid();
                <?php
               if($mode_edit_row=="popup" && !$enableEditWebsite && count($template_by_element) )
               {
                   foreach($template_by_element as $element_id)
                   {
                       $block_item='.block-item[data-block-id="'.$element_id.'"]';
                       ?>
                jQuery('<?php echo $block_item ?>').css( {
                    display:"none"
                });
                <?php
            }
        }
        ?>

            });
            function onChange_<?php echo $block->id ?>(arg) {

                console.log("Selected: " + selected.length + " item(s), [" + selected.join(", ") + "]");
            }

            function onDataBound_<?php echo $block->id ?>(arg) {
                console.log("Grid data bound");
            }

            function onDataBinding_<?php echo $block->id ?>(arg) {
                console.log("Grid data binding");
            }
            function edit_row_<?php echo $block->id ?>(e) {

                <?php
                 if($mode_edit_row=="popup" && count($template_by_element))
                {
                    ?>
                var popupWindow = e.container.getKendoWindow();
                popupWindow.setOptions({
                    width: 1000,
                    autosize:true
                });
                popup_content=popupWindow.element.find('.k-popup-content');
                popup_content.empty();
                <?php
            }
            if($mode_edit_row=="popup" && count($template_by_element))
            {
                foreach($template_by_element as $element_id)
                {
                    $block_item='.block-item[data-block-id="'.$element_id.'"]';
                    ?>

                jQuery('<?php echo $block_item ?>').wrap( '<div wrap-block-id="<?php echo $element_id ?>"></div>' );
                jQuery('<?php echo $block_item ?>').css( {
                    display:"block"
                });
                jQuery('<?php echo $block_item ?>').appendTo(popup_content);
                <?php
            }
        }
        ?>


            }
            function save_row_<?php echo $block->id ?>(e) {

                <?php
                if($mode_edit_row=="popup" && count($template_by_element))
                {
                ?>
                var popupWindow = e.container.getKendoWindow();
                console.log(popupWindow);
                <?php
                    foreach($template_by_element as $element_id)
                    {
                        $block_item='.block-item[data-block-id="'.$element_id.'"]';
                        ?>
                jQuery('<?php echo $block_item ?>').appendTo(jQuery('div[wrap-block-id="<?php echo $element_id ?>"]'));
                jQuery('<?php echo $block_item ?>').css( {
                    display:"none"
                });
                jQuery('<?php echo $block_item ?>').unwrap();
                <?php
            }
        }
        ?>



            }
            function cancel_row_<?php echo $block->id ?>(e) {
                var popupWindow = e.container.getKendoWindow();
                console.log(popupWindow);
                <?php
                if(count($template_by_element))
                {
                    foreach($template_by_element as $element_id)
                    {
                        $block_item='.block-item[data-block-id="'.$element_id.'"]';
                        ?>
                jQuery('<?php echo $block_item ?>').appendTo(jQuery('div[wrap-block-id="<?php echo $element_id ?>"]'));
                jQuery('<?php echo $block_item ?>').css( {
                    display:"none"
                });
                jQuery('<?php echo $block_item ?>').unwrap();
                <?php
            }
        }
        ?>



            }
        </script>


        <?php

        $html .= ob_get_clean();
        return $html;
    }

    public function render_dropdown_list($js_function_name, $ob_value, $block)
    {
        $doc = JFactory::getDocument();
        $data_source_template_item = $ob_value->data_source_template_item;
        if (trim($data_source_template_item) != '') {
            if (base64_encode(base64_decode($data_source_template_item, true)) === $data_source_template_item) {
                $data_source_template_item = base64_decode($data_source_template_item);
            } else {
                $data_source_template_item = '';
            }

            $data_source_template_item = str_replace('{this_host}', JUri::root(), $data_source_template_item);
        }

        $data_source_template_item_select = $ob_value->data_source_template_item_select;
        if (trim($data_source_template_item_select) != '') {
            if (base64_encode(base64_decode($data_source_template_item_select, true)) === $data_source_template_item_select) {
                $data_source_template_item_select = base64_decode($data_source_template_item_select);
            } else {
                $data_source_template_item_select = '';
            }

            $data_source_template_item_select = str_replace('{this_host}', JUri::root(), $data_source_template_item_select);
        }
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">

            function <?php echo $js_function_name ?>(container, options) {

                var link_key = "<?php echo $ob_value->link_key?$ob_value->link_key:'test' ?>";
                var key_data_source = "<?php echo $ob_value->key_data_source?$ob_value->key_data_source:'test' ?>";
                $ = jQuery;
                var option_kendoDropDownList = {
                    index: -1,
                    autoBind: true,
                    dataSource: {
                        transport: {
                            read: function (options) {
                                $.ajax({
                                    url: this_host + "/index.php?option=com_phpmyadmin&task=datasource.ajax_get_data_by_data_source_id&data_source_id=<?php echo $ob_value->data_source_id ?>",
                                    dataType: "json",
                                    success: function (result) {
                                        result.reverse();
                                        result.push({
                                            "<?php echo $ob_value->key_data_source ?>": 0,
                                            "<?php echo $ob_value->text_data_source ?>": 'None'
                                        });
                                        result.reverse();

                                        $.each(result, function (index, value) {

                                            result[index].key_update = link_key;
                                            result[index].key_value = key_data_source;
                                            result[index].<?php echo $ob_value->link_key ?> = value[key_data_source];
                                        });
                                        options.success(result);
                                    }
                                });

                            }
                        }
                    }


                };
                var data_source_template_item = '<?php echo $data_source_template_item ?>';
                if (data_source_template_item != '') {
                    data_source_template_item = data_source_template_item.trim();
                    option_kendoDropDownList.template = data_source_template_item;
                }
                var data_source_template_item_select = '<?php echo $data_source_template_item_select ?>';
                if (data_source_template_item_select != '') {
                    data_source_template_item_select = data_source_template_item_select.trim();
                    option_kendoDropDownList.valueTemplate = data_source_template_item_select;
                }
                $('<input    data-text-field="<?php echo $ob_value->text_data_source ?>"   data-value-field="<?php echo $ob_value->link_key ?>" data-bind="value:' + options.field + '"/>')
                    .appendTo(container)
                    .kendoDropDownList(option_kendoDropDownList);
            }

        </script>
        <?php
        $js_content .= ob_get_clean();
        $js_content = JUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        return $js_function_name;
    }

    public function render_browser_image($js_function_name, $ob_value, $block)
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . '/ckfinder/ckfinder.js');
        $html = '';
        ob_start();
        ?>
        <div class="input-group">
            <input type="text" class="block-item block-item-browse form-control" value="" readonly/>
               <span class="input-group-btn">
                    <button class="btn btn-default browser-server" type="button" data-output="input"
                            onclick="element_ui_browse.open_file_server(this)">
                        <i class="en-browser"></i><?php echo JText::_('choosen image') ?>
                    </button>
                </span>
        </div>
    <?php
    $html .= ob_get_clean();
    require_once JPATH_ROOT . '/libraries/simplehtmldom_1_5/simple_html_dom.php';
    $html = str_get_html($html);
    $js_content = '';

    ob_start();
    ?>
        <script type="text/javascript">

            function <?php echo $js_function_name ?>(container, options) {

                $ = jQuery;
                $('<?php echo $html ?>').appendTo(container);

            }

        </script>
        <?php
        $js_content .= ob_get_clean();
        $js_content = JUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        return $js_function_name;
    }

    public function render_multiselect($js_function_name, $ob_value, $block)
    {
        $doc = JFactory::getDocument();
        $js_content = '';
        ob_start();
        ?>
        <script type="text/javascript">


            var select_<?php echo $js_function_name ?> = '';

            function <?php echo $js_function_name ?>(container, options) {

                $ = jQuery;
                jQuery(' <select id="kendoMultiSelect_<?php echo $block->id ?>_<?php echo $js_function_name ?>" data_function_update_model="update_modal_<?php  echo $js_function_name ?>"  name="' + options.field + '" for="' + options.field + '" id="' + options.field + '" data-text-field="<?php echo $ob_value->text_data_source ?>"   data-value-field="<?php echo $ob_value->key_data_source ?>" data-bind="value:<?php echo $ob_value->link_key ?>" multiple="multiple" data-placeholder="Select attendees..."></select>')
                    .appendTo(container)
                    .kendoMultiSelect({
                        index: -1,
                        autoBind: false,
                        dataSource: {
                            transport: {
                                read: this_host + "/index.php?option=com_phpmyadmin&task=datasource.ajax_get_data_by_data_source_id&data_source_id=<?php echo $ob_value->data_source_id ?>"
                            }
                        },
                        select: function (e) {

                            var dataItem = this.dataSource.view()[e.item.index()];
                            var values = this.value();

                            if (dataItem.<?php echo $ob_value->text_data_source ?> === "ALL") {
                                this.value("");
                            } else if (values.indexOf("ALL") !== -1) {
                                values = $.grep(values, function (value) {
                                    return value !== "ALL";
                                });
                                this.value(values);
                            }

                        },
                        open: function (e) {

                        },

                        change: function (e) {
                            list_selected = this.value();
                            key_select_<?php echo $js_function_name ?> = list_selected;


                            text = new Array();
                            $.each(this.listView.selectedDataItems(), function (index, item) {

                                text.push(item.<?php echo $ob_value->text_data_source ?>);
                            });
                            select_<?php echo $js_function_name ?> = text.join(',');
                            console.log(select_<?php echo $js_function_name ?>);
                        }


                    });
            }
            ;
            function update_modal_<?php  echo $js_function_name ?>(model) {
                if (typeof select_<?php echo $js_function_name ?> !== "undefined") {
                    model.<?php echo $ob_value->column_name ?> = select_<?php echo $js_function_name ?>;
                }
                if (typeof key_select_<?php echo $js_function_name ?> !== "undefined") {
                    model.<?php echo $ob_value->link_key ?> = key_select_<?php echo $js_function_name ?>;
                }
                return model;

            }
        </script>
        <?php
        $js_content .= ob_get_clean();
        $js_content = JUtility::remove_string_javascript($js_content);
        $doc->addScriptDeclaration($js_content);
        return $js_function_name;
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
            ?>
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