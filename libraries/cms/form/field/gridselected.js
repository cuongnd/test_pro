jQuery(document).ready(function ($) {
    gridselected = {
        data_column: {},
        data_command: {},
        list_icon: {},
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
        table_name_option: {
            ajax: {
                url: this_host + "/index.php?option=com_phpmyadmin&task=tables.ajax_get_list_table",
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
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1
        },
        column_name_option: {
            data: function () {
                data_post = gridselected.data_column;

                return {results: data_post};
            },
            initSelection: function (element, callback) {
                item = {
                    id: element.val(),
                    text: element.val()
                };
                return callback(item);
            }
        },
        link_key_option: {
            data: function () {
                data_post = gridselected.data_column;

                return {results: data_post};
            },
            initSelection: function (element, callback) {
                item = {
                    id: element.val(),
                    text: element.val()
                };
                return callback(item);
            }
        },
        show_command_option: {
            tags: "true",
            data: function () {
                data_post = gridselected.data_command;

                return {results: data_post};
            },
            tokenSeparators	: [",", " "]
        },
        post_name_option: {
            data: function () {
                data_post = new Array();
                i = 0;
                $(':input[enable-submit="true"],:selected[enable-submit="true"]').each(function () {
                    name = $(this).attr('name');
                    item = {
                        id: name,
                        text: name
                    };
                    data_post[i] = item;
                    i++;

                });
                parser_url = $.url(currentLink).param();
                $.each(parser_url, function (index, value) {

                    item = {
                        id: index,
                        text: index
                    };
                    data_post[i] = item;
                    i++;


                });
                return {results: data_post};
            },
            initSelection: function (element, callback) {
                item = {
                    id: element.val(),
                    text: element.val()
                };
                return callback(item);
            }
        },
        option_droppable: {
            accept: ".configupdate-item-table,.configupdate-item-field",
            greedy: true,
            drop: function (ev, ui) {
                uiDraggable = $(ui.draggable);
                droppable = $(this);
                if (uiDraggable.hasClass('configupdate-item-table')) {
                    gridselected.render_table_fields(uiDraggable, droppable);
                } else if (uiDraggable.hasClass('configupdate-item-field')) {
                    gridselected.render_table_field(uiDraggable, droppable);
                }
            }
        },
        option_nestable1: {
            group: 1,
            maxDepth: 1,
            handleClass: 'dd-handle-move'
        },
        option_nestable2: {
            group: 2,
            maxDepth: 1,
            handleClass: 'dd-handle-move'
        },
        init_config_nestable: function () {
            $('#gridformartheader1').nestable(gridselected.option_nestable1)
                .on('change', gridselected.updateOutput);
            $('#gridformartheader2').nestable(gridselected.option_nestable2)
                .on('change', gridselected.updateOutput);


        },
        init_gridselected: function () {


            gridselected.set_auto_complete();
            // activate Nestable for list 1
            gridselected.init_config_nestable();
            $(".configupdate-item-table").draggable(gridselected.option_draggable);
            gridselected.update_nestable();
            $('.dd-list-droppable').droppable(gridselected.option_droppable);
            $('.configupdate-item-table a.plus').click(function () {
                gridselected.get_list_field_table($(this));
            });
            list_editor = {};
            $('.column_template_texarea').each(function (index) {

                self = $(this);
                key=self.attr('data-key');
                gridselected.set_code_mirror(self,key);
            });


        },
        change_data_source:function(self){

        },
        set_code_mirror: function (self,key) {
            attr_id = self.attr('id');
            var mixedMode = {
                name: "htmlmixed",
                scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                    mode: null},
                    {matches: /(text|application)\/(x-)?vb(a|script)/i,
                        mode: "vbscript"}]
            };
            query=$('#'+attr_id).val();
            query=query.replace("/^\s*|\s*$/g",'');
            $('#'+attr_id).val(query.trim());

            var editor_element = document.getElementById(attr_id);
            editor=CodeMirror.fromTextArea(editor_element,
                {
                    mode: mixedMode,
                    selectionPointer: true,
                    indentWithTabs: true,
                    smartIndent: true,
                    lineNumbers: true,
                    matchBrackets: true,
                    key_column:key,
                    fullScreen: false,
                    autofocus: true,
                    is_icon:true,
                    change: function (cm) {

                        alert('ok');
                    },
                    hintOptions: {tables: {
                        table__users: {name: null, score: null, birthDate: null},
                         countries: {name: null, population: null, size: null}
                    }},
                    ajax_loader:{
                        ajax:false,
                        component:'com_utility',
                        task:'utility.smart_auto_complete',
                        func_success:function(response,cm){
                            jQuery.each(response, function (index, table) {
                                cm.options.hintOptions.tables[index]= {};
                                jQuery.each(table, function (field, type) {
                                    cm.options.hintOptions.tables[index][field]=null;
                                });

                            });
                        }
                    },
                    onCursorActivity: function() {
                        editor.setLineClass(hlLine, null);
                        hlLine = editor.setLineClass(editor.getCursor().line, "activeline");

                    },
                    extraKeys: {
                        "Ctrl-J": "list_icon",
                        "Ctrl-M": "list_menu",
                        "Ctrl-B": "autocomplete1",
                        "Ctrl-Space": "autocomplete"
                    }

                }
            );

            list_field=new Array();
            list_field.push('{active_menu}');
            $.each(gridselected.list_menu, function( index, value ) {
                list_field.push(value);
            });


            $.each(gridselected.data_column, function( index, value ) {
                list_field.push('#:'+value.text+'#');
            });

            CodeMirror.registerHelper("hint", "list_field", function(editor, options) {
                var WORD = /[\w$]+/, RANGE = 500;
                var word = options && options.word || WORD;
                var range = options && options.range || RANGE;
                var cur = editor.getCursor(), curLine = editor.getLine(cur.line);
                var end = cur.ch, start = end;
                while (start && word.test(curLine.charAt(start - 1))) --start;
                var curWord = start != end && curLine.slice(start, end);

                var list = list_field, seen = {};
                var re = new RegExp(word.source, "g");
                for (var dir = -1; dir <= 1; dir += 2) {
                    var line = cur.line, endLine = Math.min(Math.max(line + dir * range, editor.firstLine()), editor.lastLine()) + dir;
                    for (; line != endLine; line += dir) {
                        var text = editor.getLine(line), m;
                        while (m = re.exec(text)) {
                            if (line == cur.line && m[0] === curWord) continue;
                            if ((!curWord || m[0].lastIndexOf(curWord, 0) == 0) && !Object.prototype.hasOwnProperty.call(seen, m[0])) {
                                seen[m[0]] = true;
                                list.push(m[0]);
                            }
                        }
                    }
                }
                return {list: list, from: CodeMirror.Pos(cur.line, start), to: CodeMirror.Pos(cur.line, end)};
            });
            var WORD = /[\w$]+/g, RANGE = 500;
            CodeMirror.registerHelper("hint","list_icon",function(cm,options){

                var word = options && options.word || WORD;
                var range = options && options.range || RANGE;
                var cur = editor.getCursor(), curLine = editor.getLine(cur.line);
                var start = cur.ch, end = start;
                while (end < curLine.length && word.test(curLine.charAt(end))) ++end;
                while (start && word.test(curLine.charAt(start - 1))) --start;
                var curWord = start != end && curLine.slice(start, end);

                var list = [], seen = {};
                function scan(dir) {

                    var line = cur.line;
                    var text = editor.getLine(line),m;
                    var text = curWord;


                    string = cm.getTokenAt(cm.getCursor()).string;
                    startPosAdj = string.slice(0,cm.getCursor().ch).length - string.slice(0,cm.getCursor().ch).lastIndexOf(' ') - 1;
                    nextSpace = string.slice(cm.getCursor().ch,string.length).indexOf(' ');
                    nextSpace > -1 ? endPosAdj = nextSpace : endPosAdj = string.length - cm.getCursor().ch;
                    text = string.slice(cm.getCursor().ch-startPosAdj,cm.getCursor().ch+endPosAdj);



                    $.each(gridselected.list_icon, function( index, value ) {
                        if(value.indexOf(text) != -1){
                            list.push(value);
                        }
                    });

                }
                scan(-1);
                scan(1);
                return {list: list, from: CodeMirror.Pos(cur.line, start), to: CodeMirror.Pos(cur.line, end)};

            });
            CodeMirror.registerHelper("hint","list_menu",function(cm,options){

                var word = options && options.word || WORD;
                var range = options && options.range || RANGE;
                var cur = editor.getCursor(), curLine = editor.getLine(cur.line);
                var start = cur.ch, end = start;
                while (end < curLine.length && word.test(curLine.charAt(end))) ++end;
                while (start && word.test(curLine.charAt(start - 1))) --start;
                var curWord = start != end && curLine.slice(start, end);

                var list = [], seen = {};
                function scan(dir) {

                    var line = cur.line;
                    var text = editor.getLine(line),m;
                    var text = curWord;
                    console.log(text);
                    $.each(gridselected.list_menu, function( index, value ) {
                        if(value.indexOf(text) != -1){
                            list.push(value);
                        }
                    });

                }

                scan(1);
                return {list: list, from: CodeMirror.Pos(cur.line, start), to: CodeMirror.Pos(cur.line, end)};

            });





            CodeMirror.commands.autocomplete1 = function(cm) {
                cm.showHint({hint: CodeMirror.hint.list_field});
            };
            CodeMirror.commands.list_icon = function(cm) {
                cm.showHint({hint: CodeMirror.hint.list_icon});
            };
            CodeMirror.commands.list_menu = function(cm) {
                cm.showHint({hint: CodeMirror.hint.list_menu});
            };



            editor.on("changes", function(cm, change) {
                self=$(cm.display.cursorDiv);
                gridselected.update_data_column_template(cm,self,cm.getValue());
            });


        },
        update_data_column_template: function (cm,self, value) {
            key_column=cm.options.key_column;
            value= base64.encode(value);
            dd_item = self.closest('.dd-item');
            dd_item.data(key_column, value);
            gridselected.update_nestable();
        },
        makeid:function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        },
        add_node: function (self, type) {
            self = $(self);
            li = self.closest('.dd-item');
            li_clone = li.clone(false);

            li_clone.insertAfter(li);
            li_clone.find('.select2-container.show_command').remove();
            li_clone.find('input.show_command').removeClass('select2-offscreen').removeData();


            li_clone.find('.select2-container.column_name').remove();
            li_clone.find('input.column_name').removeClass('select2-offscreen').removeData();
            //clear data post name
            li_clone.find('.select2-container.post_name').remove();
            li_clone.find('input.post_name').removeClass('select2-offscreen').removeData();


            li_clone.removeData();
            li_clone.find(".show_command").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".post_name").val('');
            li_clone.find("input.show_command").select2(gridselected.show_command_option);
            li_clone.find("input.column_name").select2(gridselected.column_name_option);
            li_clone.find("input.post_name").select2(gridselected.post_name_option).select2("data", gridselected.data_post, true);
            li_clone.find('input[type="checkbox"]').prop('checked', false);
            if (type == 'template') {
                column_template_texarea = li_clone.find('.column_template_texarea');
                column_template_texarea.empty();
                li_clone.find('.CodeMirror').remove();


                var random_number = gridselected.makeid();
                column_template_texarea.attr('id', random_number);
                gridselected.set_code_mirror(column_template_texarea);
            }
            gridselected.update_nestable();
        },
        add_sub_node: function (self) {
            li = self.closest('.dd-item');
            li_clone = li.clone(false);
            ol = self.find(' > ol');
            if (ol.length) {
                li_clone.insertAfter(ol);
            } else {
                ol = $('<ol class="dd-list"></ol>');
                ol.append(li_clone);
                ol.appendTo(li);
            }

            li_clone.find('.select2-container.table_name').remove();
            li_clone.find('input.table_name').removeClass('select2-offscreen').removeData();
            li_clone.find('.select2-container.column_name').remove();
            li_clone.find('input.column_name').removeClass('select2-offscreen').removeData();

            //clear post name
            li_clone.find('.select2-container.post_name').remove();
            li_clone.find('input.post_name').removeClass('select2-offscreen').removeData();
            li_clone.find('input[type="checkbox"]').prop('checked', false);
            li_clone.removeData();
            li_clone.find(".table_name").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".post_name").val('');
            level = li_clone.attr('data-level');
            level++;
            li_clone.attr('data-level', level);
            primary_key = li_clone.find('input[type="radio"].primary-key');
            primary_key.attr('name', 'primary_key_' + level);
            li_clone.find("input.table_name").select2(gridselected.table_name_option);
            li_clone.find("input.column_name").select2(gridselected.column_name_option);

            //set select 2 post name
            li_clone.find("input.post_name").select2(gridselected.post_name_option);
            gridselected.update_nestable();

        },
        update_data_column: function (self, key, type_input) {
            self = $(self);
            self_value = self.val();
            if (typeof type_input !== 'undefined' && type_input == 'checkbox') {
                show = self.is(':checked');
                self_value = show ? 1 : 0;
            } else if (typeof type_input !== 'undefined' && type_input == 'radio') {
                self_value = self.val();
            }

            dd_item = self.closest('.dd-item');
            dd_item.data(key, self_value);
            gridselected.update_nestable();
        },
        primary_key_update_value: function (self) {
            self = $(self);
            name = self.attr('name');
            $('input[type="radio"][name="' + name + '"]').val(0);
            self.val(1);
        },
        call_on_change: function (self) {
            self = $(self);
            name = self.attr('name');
            $('input[type="radio"][name="' + name + '"]').each(function () {
                gridselected.update_data_column(this, 'primary_key', 'radio');
            });
        },
        set_auto_complete: function () {
            $(".show_command").select2(gridselected.show_command_option);
            $(".column_name").select2(gridselected.column_name_option);
            $(".link_key").select2(gridselected.link_key_option);


            //$(".post_name").select2(gridselected.post_name_option);

        },
        update_nestable: function () {
            gridselected.updateOutput($('#gridselected').data('output', $('#gridselected-output')));
        },
        remove_item_nestable: function (self) {
            self = $(self);
            dd_item = self.closest('.dd-item');
            if ($('.dd-item').length > 1)
                dd_item.remove();
            gridselected.update_nestable();
        },
        get_list_field_table: function (self) {
            table = self.data('table');
            li_table = self.closest('li.table');
            if (typeof ajax_render_field_table !== 'undefined') {
                ajax_render_field_table.abort();
            }
            ajax_render_field_table = $.ajax({
                type: "GET",
                dataType: "json",
                url: this_host + '/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'table.ajax_render_field_table',
                        table: table

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    // $('.loading').popup();
                },
                success: function (response) {
                    $.each(response, function (index, value) {
                        field_name = index;
                        index = table + '.' + index;
                        dd_item = $('.dd-item:last').clone();
                        dd_item.data('id', index);
                        dd_item.find('.option').hide();
                        dd_item.find('.dd-handle-remove').hide();
                        li_table.find('ol.dd-list').append(dd_item);
                        dd_item.find('.dd_column_name').html(field_name);
                        dd_item.find('input.column_name').val(field_name);
                    });
                    li_table.find('.list-field').show().nestable({
                        group: 1,
                        maxDepth: 5,
                        helper: "clone",
                        handleClass: 'dd-handle-move',
                        setup_depth: false,
                        dragStop: function (e, el, dragRootEl) {
                            if (!dragRootEl.hasClass('list-field')) {
                                el.find('.option').show();
                                data_id = el.data('id');
                                el.find('.dd_column_name').html(data_id);
                                el.find('.dd-handle-remove').show();
                            }
                        }
                    });

                }
            });
        },
        render_table_fields: function (uiDraggable, droppable) {
            table = uiDraggable.data('table');
            if (typeof ajaxRederTable !== 'undefined') {
                ajaxRederTable.abort();
            }
            ajaxRederTable = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'table.aJaxInsertTable',
                        table: table

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    // $('.loading').popup();
                },
                success: function (response) {
                    response = $(response);
                    droppable.append(response);


                }
            });
        },
        render_table_field: function (uiDraggable, droppable) {
            field = uiDraggable.attr('data-field');
            field = field.split(".");
            table_name = field[0];
            field_name = field[1];
            dd_item = $('.dd-item:last').clone();
            dd_item.data('id', table_name + '.' + field_name);
            dd_item.attr('data-id', table_name + '.' + field_name);
            dd_item.find('input.column_name').val(field_name);
            dd_item.find('span.dd_column_name').html(field_name);
            if (droppable.find('.dd-list').length > 1) {
                droppable.find('> .dd-list').append(dd_item);
            } else {
                dd_list = $('<ol class="dd-list dd-list-droppable"></ol>');
                dd_list.append(dd_item);
                droppable.append(dd_list);
                droppable.find('.dd-empty').remove();
            }
            //gridselected.update_nestable();
            dd_item.droppable(gridselected.option_droppable);
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
        },
        update_data_type: function () {
            self = $(self);
            data_type = self.val();
            dd_item = self.closest('.dd-item');
            dd_item.data('type', data_type);
            gridselected.updateOutput($('#gridselected').data('output', $('#gridselected-output')));
        },
        update_data_editable: function () {

        },
        filter_table: function (self) {
            text = $(self).val();
            $('.config-upate-table li').each(function () {
                title = $(this).text();
                title = title.toLowerCase();
                if (title.indexOf(text) != -1) {
                    $(this).show();
                }
                else {
                    $(this).hide();
                }
            });
        }
    };


});