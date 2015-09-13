jQuery(document).ready(function ($) {
    var editor,grid_result;
    coding_php_content_loader = {
        kendo_grid_option: {
            height: 300,
            groupable: true,
            scrollable: true,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            }
        },
        option_draggable: {
            appendTo: 'body',
            /*helper: function(){
             dd_list= $('.dd-list:first').clone(true);
             dd_list.empty();
             dd_list.addClass('dd-dragel');
             dd_item= $('.dd-item:last').clone(true);
             dd_list.append(dd_item);
             return dd_list;
             },*/
            helper: 'clone',

            start: function (event, ui) {
            },
            drag: function (event, ui) {


            }
        },
        icon_option: {
            ajax: {
                url: this_host + "/index.php?option=com_menus&task=item.ajax_get_list_icon",
                dataType: 'json',
                delay: 250,
                data: function (term, page) {
                    return {
                        keyword: term
                    };
                },

                results: function (data) {
                    return {results: data};
                },
                cache: true

            },
            initSelection: function (element, callback) {
                item = {
                    id: element.val(),
                    text: element.val()
                };
                return callback(item);
            },
            formatResult: function (result, container, query, escapeMarkup) {

                return '<span><i class="' + result.text + '"></i>' + result.text + '</span>';
            },

            formatSelection: function (data, container, escapeMarkup) {

                return '<span><i class="' + data.text + '"></i>' + data.text + '</span>';
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1
        },

        option_droppable: {
            accept: ".configupdate-item-table,.configupdate-item-field",
            greedy: true,
            drop: function (ev, ui) {
                uiDraggable = $(ui.draggable);
                droppable = $(this);
                if (uiDraggable.hasClass('configupdate-item-table')) {
                    coding_php_content_loader.render_table_fields(uiDraggable, droppable);
                } else if (uiDraggable.hasClass('configupdate-item-field')) {
                    coding_php_content_loader.render_table_field(uiDraggable, droppable);
                }
            }
        },
        option_nestable: {
            group: 1,
            maxDepth: 10,
            handleClass: 'dd-handle-move',
            dragStop: function (e, el, dragEl) {
                coding_php_content_loader.move_element(e, el, dragEl);
            }
        },
        getSelectedRange: function () {
            return {from: editor.getCursor(true), to: editor.getCursor(false)};
        },
        format_code: function (self) {
            CodeMirror.commands["selectAll"](editor);
            var range = coding_php_content_loader.getSelectedRange();
            editor.autoFormatRange(range.from, range.to);
        },
        createGridDataByQuery:function () {

            $('#grid_result').kendoGrid(coding_php_content_loader.kendo_grid_option);
    },

    getDataByQuery: function () {
            query = editor.getValue();
            query=base64.encode(query);
            ajaxGetStanderQuery = $.ajax({
                type: "GET",
                url: this_host + '/index.php',

                data: (function () {

                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'datasource.ajaxGetDataByQuery',
                        query: query,
                        type: "add_on",
                        use_type: 'code_php',
                        source_id:$('#coding_php_content').attr('data-source-id')

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
                    response = $.parseJSON(response);
                    if (response.e == 1) {
                        $('#grid_result_error').html(response.m).show();
                        $('#grid_result').hide();
                    }
                    else {
                        $('#grid_result').show();
                        var grid_result=$('#grid_result').data("kendoGrid");
                        var columns=[];
                        $.each(response.r[0], function( key, value ) {
                            var column={};
                            column.field=key;
                            column.width=150;
                            columns.push(column);
                        });

                        grid_result. setOptions({
                            columns: columns
                        });
                        grid_result.dataSource.data(response.r);
                    }
                }
            });

        },

        move_element: function (e, el, dragEl) {
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

        },
        init_config_nestable: function () {
            $('.a_menu_type').each(function () {
                id_menu_type = $(this).attr('id');
                $('#' + id_menu_type).nestable(coding_php_content_loader.option_nestable)
                    .on('change', coding_php_content_loader.updateOutput);
            });


        },
        init_php_content_ajax_loader: function () {
            var mode = $('#coding_php_content').attr('data-mode');
            mode = mode.toLowerCase().trim();
            switch (mode) {
                case 'javascript':
                    mode = 'text/javascript';
                    break;
                case 'html':
                    mode = 'text/html';
                    break;
                default:
                    mode = 'application/x-httpd-php';
            }

            $(".dropdown-toggle").dropdown();
            editor = CodeMirror.fromTextArea(document.getElementById("coding_php_content"), {
                mode: mode,
                lineNumbers: true,
                styleActiveLine: true,
                lineNumbers: true,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                trackContext: true,
                textHover: true,
                fixedGutter: true,
                extraKeys: {
                    "Ctrl-Space": "autocomplete",
                    "F11": function (cm) {
                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                    },
                    "Esc": function (cm) {
                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                    }
                },
                hintOptions: {
                    tables: {
                        /*table__users: {name: null, score: null, birthDate: null},
                         countries: {name: null, population: null, size: null}*/
                    }
                },
                ajax_loader: {
                    ajax: true,
                    component: 'com_phpmyadmin',
                    task: 'datasource.get_php',
                    func_success: function (response, cm) {
                        /*jQuery.each(response, function (index, table) {
                         editor.options.hintOptions.tables[index] = {};
                         jQuery.each(table, function (field, type) {
                         editor.options.hintOptions.tables[index][field] = null;
                         });

                         });*/
                        var php_joomla = [];
                        jQuery.each(response, function (index, value) {
                            php_joomla.push(index);
                        });
                        php_joomla = php_joomla.join(" ");
                        console.log(php_joomla);
                        CodeMirror.registerHelper("hintWords", "php", [php_joomla].join(" ").split(" "));
                    }
                },
                gutters: ["CodeMirror-lint-markers", "CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                textHover: true
            });
            editor.on("changes", function (cm, change) {
                $('#coding_php_content').val(base64.encode(cm.getValue()));
            });


            $('#tab_coding_php a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                var target = $(e.target).attr("href");
                switch (target) {
                    case '#result':
                        //code block
                        coding_php_content_loader.getDataByQuery();

                        break;
                    default:
                    //default code block
                }
            });
            grid_result = coding_php_content_loader.createGridDataByQuery();

        },
        show_more_options: function (self) {
            self = $(self);
            if (self.is(":checked")) {
                $('.dd-item .more_options').show();

            }
            else {
                $('.dd-item .more_options').hide();
            }

        },
        save_and_close: function () {
            list_menu_type = {};
            $('input.menu_input').each(function () {
                self = $(this);
                list_menu_type[self.attr('data-menu-type-id')] = base64.encode(self.val());
            });
            ajax_web_design = $.ajax({
                type: "POST",
                dataType: "json",
                cache: false,
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'menus.ajax_save_menu',
                        list_menu_type: list_menu_type
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


        },
        save: function () {
            console.log('hello save');
        },
        cancel: function () {
            console.log('hello cancel');
        },
        add_node: function (self) {
            li = self.closest('.dd-item');
            li_clone = li.clone(false);
            li_clone.data('id', 0);
            li_clone.data('parent_id', 0);
            li_clone.data('title', '');
            li_clone.data('alias', '');
            li_clone.data('icon', '');
            li_clone.insertAfter(li);
            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();
            li_clone.removeData();
            li_clone.find(".icon").select2(coding_php_content_loader.icon_option);
            coding_php_content_loader.update_nestable();
            menu_type_id = li.attr('data-menu_type_id');
            id = li.attr('data-id');

            ajax_web_design = $.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'item.ajax_clone_item_menu',
                        id: id

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

        },
        add_sub_node: function (self) {
            li = self.closest('.dd-item');
            li_clone = li.clone(false);
            li_clone.data('id', 0);
            li_clone.data('parent_id', 0);
            li_clone.data('title', '');
            li_clone.data('alias', '');
            li_clone.data('icon', '');
            ol = self.find(' > ol');
            if (ol.length) {
                li_clone.insertAfter(ol);
            } else {
                ol = $('<ol class="dd-list"></ol>');
                ol.append(li_clone);
                ol.appendTo(li);
            }

            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();

            li_clone.find("input.icon").select2(coding_php_content_loader.icon_option);
            coding_php_content_loader.update_nestable();
            id = li.attr('data-id');
            ajax_web_design = $.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'item.ajax_add_sub_item_menu',
                        id: id

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


        },
        update_data_column: function (self, key, type_input) {
            self = $(self);
            self_value = self.val();
            if (typeof type_input !== 'undefined' && type_input == 'checkbox') {
                self_value = self.val();
            } else if (typeof type_input !== 'undefined' && type_input == 'radio') {
                self_value = self.val();
            }


            dd_item = self.closest('.dd-item');
            dd_item.data(key, self_value);
            coding_php_content_loader.update_nestable();
            dd_item = self.closest('.dd-item');
            id = dd_item.attr('data-id');
            list_keys_values = {};
            list_keys_values[key] = self_value;
            coding_php_content_loader.update_menu_item(id, list_keys_values);

        },
        update_menu_item: function (id, list_keys_values) {

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
                            id: id

                        }

                    };
                    $.each(list_keys_values, function (index, value) {
                        dataPost.data[index] = value;
                    });
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
                    if (response.e == 1) {
                        alert(response.m);
                    } else {
                        alert('saved successfully');
                    }


                }
            });

        },
        home_update_value: function (self) {
            self = $(self);
            name = self.attr('name');
            $('input[type="radio"][name="' + name + '"]').val(0);
            self.val(1);
        },
        call_on_change: function (self) {
            self = $(self);
            name = self.attr('name');
            $('input[type="radio"][name="' + name + '"]').each(function () {
                coding_php_content_loader.update_data_column(this, 'home', 'radio');
            });
        },
        set_auto_complete: function () {
            $(".icon_menu_item").select2(coding_php_content_loader.icon_option);
        },
        update_nestable: function () {
            $('.a_menu_type').each(function render_nestable() {
                id_menu_type = $(this).attr('id');
                coding_php_content_loader.updateOutput($('#' + id_menu_type).data('output', $('#' + id_menu_type + '_output')));
            });


        },
        remove_item_nestable: function (self) {
            self = $(self);
            dd_item = self.closest('.dd-item');
            if ($('.dd-item').length > 1)
                dd_item.remove();
            coding_php_content_loader.update_nestable();
        },


        updateOutput: function (e) {
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

    };



});