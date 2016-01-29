(function ($) {

    // here we go!
    $.view_tmpl_createitem = function (element, options) {

        // plugin's default options
        var defaults = {
            maxDepth: 1,
            element_ouput: '',
            list_menu: [],
            list_style: []
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            plugin.option_draggable = {
                appendTo: 'body',
                helper: 'clone',

                start: function (event, ui) {
                }

                ,
                drag: function (event, ui) {


                }
            }
            plugin.icon_option = {
                ajax: {
                    url: this_host + "/index.php?option=com_menus&task=item.ajax_get_list_icon",
                    dataType: 'json',
                    delay: 250,
                    data: function (term, page) {
                        return {
                            keyword: term
                        };
                    }

                    ,

                    results: function (data) {
                        return {results: data};
                    }
                    ,
                    cache: true

                }
                ,
                initSelection: function (element, callback) {
                    item = {
                        id: element.val(),
                        text: element.val()
                    };
                    return callback(item);
                }
                ,
                formatResult: function (result, container, query, escapeMarkup) {

                    return '<span><i class="' + result.text + '"></i>' + result.text + '</span>';
                }
                ,

                formatSelection: function (data, container, escapeMarkup) {

                    return '<span><i class="' + data.text + '"></i>' + data.text + '</span>';
                }
                ,
                escapeMarkup: function (markup) {
                    return markup;
                }
                , // let our custom formatter work
                minimumInputLength: 1
            }
            plugin.field_name_option = {
                tags: {},
                maximumSelectionSize: 1
            }
            plugin.access_option = {
                ajax: {
                    url: this_host + "/index.php?option=com_users&task=user.ajax_get_list_group_user",
                    dataType: 'json',
                    delay: 250,
                    data: function (term, page) {
                        return {
                            keyword: term
                        };
                    }

                    ,

                    results: function (data) {
                        return {results: data};
                    }
                    ,
                    cache: true

                }
                ,
                initSelection: function (element, callback) {
                    item = {
                        id: element.val(),
                        text: element.val()
                    };
                    return callback(item);
                }
                ,
                formatResult: function (result, container, query, escapeMarkup) {

                    return '<span><i class="' + result.text + '"></i>' + result.text + '</span>';
                }
                ,

                formatSelection: function (data, container, escapeMarkup) {

                    return '<span><i class="' + data.text + '"></i>' + data.text + '</span>';
                }
                ,
                escapeMarkup: function (markup) {
                    return markup;
                }
                , // let our custom formatter work
                minimumInputLength: 1
            }


            plugin.option_droppable = {
                accept: ".configupdate-item-table,.configupdate-item-field",
                greedy: true,
                drop: function (ev, ui) {
                    uiDraggable = $(ui.draggable);
                    droppable = $(this);
                    if (uiDraggable.hasClass('configupdate-item-table')) {
                        plugin.render_table_fields(uiDraggable, droppable);
                    } else if (uiDraggable.hasClass('configupdate-item-field')) {
                        plugin.render_table_field(uiDraggable, droppable);
                    }
                }
            }

            plugin.option_nestable = {
                group: 1,
                maxDepth: plugin.settings.maxDepth,
                handleClass: 'dd-handle',
                dragStop: function (e, el, dragEl) {
                    plugin.move_element(e, el, dragEl);
                }
            }


            $element.find('.remove_item_nestable').click(function remove_item_nestable() {
                plugin.remove_item_nestable($(this));
            });
            $element.find('.expand_item_nestable').click(function expand_item_nestable() {
                plugin.expand_item_nestable($(this));
            });
            $element.find('.add_node').click(function add_node_click() {
                plugin.add_node($(this));
            });
            $element.find('.add_sub_node').click(function add_sub_node_click() {
                plugin.add_sub_node($(this));
            });
            $element.find('.show_more_options').click(function show_more_options() {
                plugin.show_more_options($(this));
            });
            $element.find('.createitem-handle-footer .save_and_close').click(function save_and_close() {
                plugin.save_and_close($(this));
            });
            $element.find('.createitem-handle-footer .save').click(function save() {
                plugin.save($(this));
            });
            $element.find('.createitem-handle-footer .cancel').click(function cancel() {
                plugin.cancel($(this));
            });

            $element.find('.update_data_column').change(function update_data_column() {
                console.log('hello update_data_column');
                var property=$(this).data('property');
                plugin.update_data_column($(this), property);
            });


            plugin.set_auto_complete();
            // activate Nestable for list 1
            plugin.init_config_nestable();
            plugin.init_append_grid();
            plugin.update_nestable();




        }




        plugin.move_element = function (e, el, dragEl) {
            parent_menu = el.closest('.dd-list').closest('.dd-item');
            id = el.attr('data-id');
            parent_id = parent_menu.attr('data-id');
            menu_type_id = parent_menu.attr('data-menu_type_id');
            if (typeof parent_id == "undefined") {
                menu_root_id = el.closest('.a_menu_type').attr('data-menu_root_id');
                parent_id = menu_root_id;
            }
            if (typeof menu_type_id == "undefined") {
                menu_type_id = el.closest('.a_menu_type').attr('data-menu_type_id');
            }
            dd_list = el.closest('.dd-list');
            list_ordering = {};
            dd_list.find('>li.dd-item').each(function (index) {
                dd_item = $(this);
                list_ordering[index] = dd_item.attr('data-id');
            });
            ajax_web_design = $.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'item.ajax_update_item',
                        data: {
                            id: id,
                            parent_id: parent_id,
                            menu_type_id: menu_type_id

                        },
                        list_ordering: list_ordering

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    $('.div-loading').css({
                        display: "block"


                    });
                    // $('.loading').popup();
                },
                success: function (response) {
                    $('.div-loading').css({
                        display: "none"


                    });


                }
            });

        }

        plugin.init_config_nestable = function () {
            $element.find('#field_block').nestable(plugin.option_nestable)
                .on('change', plugin.updateOutput);


        }




        plugin.init_append_grid = function () {
            $element.find('.tbl_append_grid').each(function () {
                self = $(this);
                var id = self.attr('id');
                var config_params = self.attr('data-config_params');
                config_params = base64.decode(config_params);
                config_params = $.parseJSON(config_params);
                plugin.append_grid_option.initData = config_params;
                $element.find('#' + id).appendGrid(plugin.append_grid_option);

            });

            $element.find('.tbl_append_grid_config_property').each(function () {
                self = $(this);
                var id = self.attr('id');
                var config_property = self.attr('data-config_property');
                config_property = base64.decode(config_property);
                config_property = $.parseJSON(config_property);
                plugin.append_grid_option_config_property.initData = config_property;
                $element.find('#' + id).appendGrid(plugin.append_grid_option_config_property);

            });

        }


        plugin.show_more_options=function (self) {
                self = $(self);
                var field_block_nestable = $element.find('#field_block').data("nestable");
                console.log(field_block_nestable);
                if (self.is(":checked")) {
                    $element.find('.dd-item .more_options').show();

                }
                else {
                    $element.find('.dd-item .more_options').hide();
                }

            }

        plugin.update_data_grid = function () {
            $element.find('.tbl_append_grid').each(function () {
                self = $(this);
                var id = self.attr('id');
                var data = $element.find('#' + id).appendGrid('getAllValue');
                data = JSON.stringify(data);
                data = base64.encode(data);
                dd_item = self.closest('.dd-item');
                dd_item.data('config_params', data);
                plugin.update_nestable();
            });

            $element.find('.tbl_append_grid_config_property').each(function () {
                self = $(this);
                var id = self.attr('id');
                var data = $element.find('#' + id).appendGrid('getAllValue');
                data = JSON.stringify(data);
                data = base64.encode(data);
                dd_item = self.closest('.dd-item');
                dd_item.data('config_property', data);
                plugin.update_nestable();
            });
        }

        plugin.save_fields = function (close) {

            plugin.update_data_grid();
            var fields = $element.find('#field_block-output').val();
            $(plugin.settings.element_ouput).val(fields);
            if(close)
            {
                $('.panel.createitem-config').remove();
            }

            $(plugin.settings.element_ouput).trigger( "change" );


        }

        plugin.save_and_close = function () {
            plugin.save_fields(1);

        }

        plugin.save = function () {
            plugin.save_fields(0);
        }


        plugin.cancel = function () {
            $('.panel.createitem-config').remove();
        }

        plugin.add_node = function (self) {
            li = self.closest('.dd-item');
            li_clone = li.clone(false);
            li_clone.find('.dd-list').remove();
            li_clone.find('button[data-action="collapse"]').remove();
            li_clone.find('button[data-action="expand"]').remove();
            li_clone.data('id', 0);
            li_clone.data('parent_id', 0);
            li_clone.data('title', '');
            li_clone.data('alias', '');
            li_clone.data('icon', '');
            li_clone.insertAfter(li);


            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();
            li_clone.find(".icon").select2(plugin.icon_option);



            li_clone.find('.select2-container.field_type').remove();
            li_clone.find('input.field_type').removeClass('select2-offscreen').removeData();
            li_clone.find(".field_type").select2();

            plugin.reset_append_grid_option(li_clone);
            plugin.reset_append_grid_config_property(li_clone);


            li_clone.find('.add_node').click(function add_node_click() {
                plugin.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click() {
                plugin.add_sub_node($(this));
            });
            li_clone.removeData();
            plugin.update_nestable();
            menu_type_id = li.attr('data-menu_type_id');
            id = li.attr('data-id');
            $element.find('.update_data_column').change(function update_data_column() {
                var property=$(this).data('property');
                plugin.update_data_column($(this), property);
            });
        }

        plugin.reset_append_grid_option = function (element) {
            element.find('.config_params').empty();
            var id = plugin.makeid();
            var table_grid = $('<table class="tbl_append_grid" data-config_params="" id="tblAppendGrid_' + id + '"></table>');
            element.find('.config_params').append($(table_grid));
            plugin.append_grid_option.initData = [];
            element.find('.tbl_append_grid').appendGrid(plugin.append_grid_option);

        }

        plugin.reset_append_grid_config_property = function (element) {

            element.find('.config_property').empty();
            var id = plugin.makeid();
            var table_grid = $('<table class="tbl_append_grid_config_property" data-config_property="" id="tblAppendGrid_config_property_' + id + '"></table>');
            element.find('.config_property').append($(table_grid));
            plugin.append_grid_option_config_property.initData = [];
            element.find('.tbl_append_grid_config_property').appendGrid(plugin.append_grid_option_config_property);

        }

        plugin.append_grid_option = {
            caption: 'Option params',
            initRows: 0,
            columns: [
                {
                    name: 'param_key',
                    display: 'Key',
                    type: 'text',
                    ctrlAttr: {maxlength: 100},
                    ctrlCss: {width: '160px'}
                },
                {
                    name: 'param_value',
                    display: 'Value',
                    type: 'text',
                    ctrlAttr: {maxlength: 100},
                    ctrlCss: {width: '100px'}
                },
            ],
            initData: [],
            rowDragging: true,
            hideButtons: {
                moveUp: true, moveDown: true
            }
        }
        plugin.append_grid_option_config_property = {
            caption: 'Option config property',
            initRows: 0,
            columns: [
                {
                    name: 'property_key',
                    display: 'Key',
                    type: 'text',
                    ctrlAttr: {maxlength: 100},
                    ctrlCss: {width: '160px'}
                },
                {
                    name: 'property_value',
                    display: 'Value',
                    type: 'text',
                    ctrlAttr: {maxlength: 100},
                    ctrlCss: {width: '200px'}
                },
            ],
            initData: [],
            rowDragging: true,
            hideButtons: {
                moveUp: true, moveDown: true
            }
        }
        plugin.makeid = function makeid() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < 5; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

        plugin.add_sub_node = function (self) {
            li = self.closest('.dd-item');
            li_clone = li.clone(false);
            li_clone.find('.dd-list').remove();
            li_clone.data('id', 0);
            li_clone.data('parent_id', 0);
            li_clone.data('title', '');
            li_clone.data('alias', '');
            li_clone.data('icon', '');
            li_clone.find('button[data-action="collapse"]').remove();
            li_clone.find('button[data-action="expand"]').remove();
            ol = li.children('.dd-list');
            if (ol.length >= 1) {
                ol.append(li_clone);
            } else {
                ol = $element.find('<ol class="dd-list"></ol>');
                ol.append(li_clone);
                ol.appendTo(li);
                li.prepend('<button type="button" data-action="collapse">Collapse</button>' +
                    '<button type="button" data-action="expand" style="display: none;">Expand</button>').fadeIn('slow');
            }

            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();
            li_clone.find("input.icon").select2(plugin.icon_option);




            li_clone.find('.select2-container.field_type').remove();
            li_clone.find('input.field_type').removeClass('select2-offscreen').removeData();
            li_clone.find("input.field_type").select2();

            plugin.reset_append_grid_option(li_clone);
            plugin.reset_append_grid_config_property(li_clone);


            li_clone.find('.add_node').click(function add_node_click() {
                plugin.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click() {
                plugin.add_sub_node($(this));
            });

            plugin.update_nestable();
            id = li.attr('data-id');


        }


        plugin.update_data_column = function (self, key, type_input) {
            var self = $(self);
            var self_value = self.val();
            if (typeof type_input !== 'undefined' && type_input == 'checkbox') {
                self_value = self.val();
            } else if (typeof type_input !== 'undefined' && type_input == 'radio') {
                self_value = self.val();
            }


            var dd_item = self.closest('.dd-item');
            dd_item.data(key, self_value);
            var id = dd_item.attr('data-id');
            var list_keys_values = {};
            list_keys_values[key] = self_value;
            if (key == 'name' || key == 'label') {
                var name = dd_item.data('name');
                var label = dd_item.data('label');
                dd_item.find('.key_name:first').html(label + " ( " + name + " ) ");
            }
            if(key=='type')
            {
                var path=self.find(':selected').data('path');
                dd_item.data('path', path);
            }

            plugin.update_nestable();


        }

        plugin.update_atrribute_param_config = function (self) {
            var self = $(self);
            var self_value = self.val();
            self = self.select2('data');
            var path = $(self.element).data('path');
            var more_options = $(self.element).closest('.more_options');

            var append_grid_id = more_options.find('.tbl_append_grid_config_property').attr('id');
            ajax_web_design = $.ajax({
                type: "POST",
                dataType: "json",
                cache: false,
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'params.ajax_get_attribute_config',
                        path: path,
                        type: self_value
                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    $('.div-loading').css({
                        display: "block"


                    });
                    // $('.loading').popup();
                },
                success: function (response) {
                    $('.div-loading').css({
                        display: "none"


                    });
                    /*
                     { name: 'property_key', display: 'Key', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '160px' } },
                     { name: 'property_value', display: 'Value', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '200px'} },
                     */

                    var list_params = [];
                    $.each(response, function (index, value) {
                        var item = {};
                        item.property_key = index;
                        item.property_value = value;
                        list_params.push(item);
                    });
                    $element.find('#' + append_grid_id).appendGrid('load', list_params);


                }
            });
        }

        plugin.call_on_change = function (self) {
            self = $(self);
            name = self.attr('name');
            $element.find('input[type="radio"][name="' + name + '"]').each(function () {
                plugin.update_data_column(this, 'home', 'radio');
            });
        }

        plugin.set_auto_complete = function () {
            $(".icon_menu_item").select2(plugin.icon_option);
            $(".column_access").select2(plugin.access_option);
            $(".field_type").select2();
        }

        plugin.update_nestable = function () {
            plugin.updateOutput($element.find('#field_block').data('output', $element.find('#field_block-output')));


        }

        plugin.remove_item_nestable = function (self) {
            self = $(self);
            dd_item = self.closest('.dd-item');
            dd_list = self.closest('.dd-list');
            if (dd_list.find('>.dd-item').length == 1) {

                dd_item_parent = dd_list.parent('.dd-item');
                dd_item_parent.find('button[data-action="collapse"]').remove();
                dd_item_parent.find('button[data-action="expand"]').remove();
                dd_list.remove();
            }
            else {
                dd_item.remove();
            }
            plugin.update_nestable();
        }

        plugin.expand_item_nestable = function (self) {
            var self = $(self);
            var dd_item = self.closest('.dd-item');
            var more_options = dd_item.find('> .more_options');
            if (more_options.is(':visible')) {
                self.find('i.im-minus').addClass('im-plus').removeClass('im-minus');
                more_options.css({
                    display: "none"
                });
            } else {
                self.find('i.im-plus').addClass('im-minus').removeClass('im-plus');
                more_options.css({
                    display: "block"
                });
            }
        }


        plugin.updateOutput = function (e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (typeof output == "undefined")
                return;
            if (window.JSON) {
                value = list.nestable('serialize');
                output.val(cassandraMAP.stringify(value));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_tmpl_createitem = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_tmpl_createitem')) {
                var plugin = new $.view_tmpl_createitem(this, options);
                $(this).data('view_tmpl_createitem', plugin);

            }

        });

    }

})(jQuery);


