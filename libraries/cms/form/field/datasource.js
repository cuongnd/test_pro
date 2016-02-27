(function ($) {

    // here we go!
    $.field_datasource = function (element, options) {

        // plugin's default options
        var defaults = {
            source_id: 0,
            ajaxgetcontent: 0,
            field_name: '',
            show_popup_control:false,
            list_table: []
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
            var show_popup_control=plugin.settings.show_popup_control;
            if(show_popup_control) {
                document.title = 'edit mysql data source';
            }
            var ajaxgetcontent= plugin.settings.ajaxgetcontent;

            plugin.kendo_grid_option= {
                height: 300,
                width: 1000,
                groupable: true,
                scrollable: true,
                pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
                }
            },
            $element.find('.umldrawer').popupWindow({
                scrollbars:1,
                windowName:'umldrawer',
                centerBrowser:1,
                width:'1200',
                scrollbars:1,
                height:'900'
            });
            $element.find('.main_ralationship').popupWindow({
                scrollbars:1,
                windowName:'main_ralationship',
                centerBrowser:1,
                width:'1200',
                scrollbars:1,
                height:'800'
            });
            $element.find('.project_ralationship').popupWindow({
                scrollbars:1,
                windowName:'project_ralationship',
                centerBrowser:1,
                width:'1200',
                height:'800'
            });
            $element.find('.datasourcerelation').popupWindow({
                scrollbars:1,
                windowName:'datasourcerelation',
                centerBrowser:1,
                width:'1200',
                height:'800'
            });
            $element.find('.field_data_source').popupWindow({
                scrollbars:1,
                windowName:'field_data_source',
                centerBrowser:1,
                width:'1200',
                height:'800'
            });

            if(show_popup_control)
            {
                var source_id=plugin.settings.source_id;
                var close=$(this).hasClass('save-block-property');
                $element.find('.save-block-property,.apply-block-property').click(function(){
                    plugin.savePropertyDataSource($element,source_id,close);
                });

                $(window).bind('keydown', function(event) {
                    if (event.ctrlKey || event.metaKey) {
                        switch (String.fromCharCode(event.which).toLowerCase()) {
                            case 's':
                                event.preventDefault();
                                $element.find('.apply-block-property').click();
                                break;
                            case 'f':
                                event.preventDefault();

                                break;
                            case 'g':
                                event.preventDefault();

                                break;
                        }
                    }
                });


            }
            $element.find('#grid_result').kendoGrid(plugin.kendo_grid_option);
            $element.find('#table-result a:first').tab('show');


            $element.find('#table-result a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                switch (target) {
                    case '#stander_query':
                        //code block here
                        query = window.editor.getValue();
                        ajaxGetStanderQuery = $.ajax({
                            type: "GET",
                            url: this_host + '/index.php',

                            data: (function () {

                                dataPost = {
                                    enable_load_component:1,
                                    option: 'com_phpmyadmin',
                                    task: 'datasource.ajaxGetStanderQuery',
                                    query: query

                                };
                                return dataPost;
                            })(),
                            beforeSend: function () {


                                // $('.loading').popup();
                            },
                            success: function (response) {

                                $('.stander_query').html(response);
                            }
                        });


                        break;
                    case '#result':
                        //code block
                        plugin.getDataByQuery();

                        break;
                    default:
                    //default code block
                }
            });
            var field_name = plugin.settings.field_name;

            plugin.textarea = $element.find('textarea[name="' + field_name + '"]');
            query = plugin.textarea.val();

            query =query!=''?query.replace("/^\s*|\s*$/g", ''):'';
            $element.find('textarea[name="' + field_name + '"]').val(query.trim());
            var mime = 'text/x-mysql';
            if (window.location.href.indexOf('mime=') > -1) {
                mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
            }
            ;
            var list_table = plugin.settings.list_table;
            window.editor = plugin.textarea.codemirror({
                mode: mime,
                indentWithTabs: true,
                smartIndent: true,
                lineNumbers: true,
                matchBrackets: true,
                fullScreen: false,
                autofocus: true,
                extraKeys: {
                    "'?'": "autocomplete1",
                    "Ctrl-Space": "autocomplete",
                    "Ctrl-F": "list_function"
                },
                hintOptions: {
                    tables: {
                        /*table__users: {name: null, score: null, birthDate: null},
                         countries: {name: null, population: null, size: null}*/
                    }
                },
                ajax_loader: {
                    enable_load_component:1,
                    ajax: true,
                    component: 'com_phpmyadmin',
                    task: 'tables.ajax_get_list_table_and_field',
                    func_success: function (response, cm) {
                        jQuery.each(response, function (index, table) {
                            cm.options.hintOptions.tables[index] = {};
                            jQuery.each(table, function (field, type) {
                                cm.options.hintOptions.tables[index][field] = null;
                            });

                        });
                    }
                },
                list_table: list_table
            });


            list_function = [
                'get_json_group_concat(id:id,title:title)',
                'LEFT JOIN t__t1 AS t1 ON t1.id=t2.t_id',
                'request(id,0)',
                'get_tree_node(field,id,parent_id,ordering,asign_name)',
                'in_tree_root(operator,field,table_parent_children,field_parent,field_children,request_val)'
            ];

            CodeMirror.commands.autocomplete1 = function (cm) {
                cm.showHint({hint: CodeMirror.hint.anyword});
            };
            CodeMirror.commands.list_function = function (cm) {
                cm.showHint({hint: CodeMirror.hint.list_function});
            };

            CodeMirror.registerHelper("hint", "list_function", function (editor, options) {
                var WORD = /[\w$]+/, RANGE = 500;
                var word = options && options.word || WORD;
                var range = options && options.range || RANGE;
                var cur = editor.getCursor(), curLine = editor.getLine(cur.line);
                var end = cur.ch, start = end;
                while (start && word.test(curLine.charAt(start - 1))) --start;
                var curWord = start != end && curLine.slice(start, end);

                var list = list_function, seen = {};
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


            window.getValueFromTextMirror = function (self) {
                self.val(window.editor.getValue());
                console.log(window.editor.getValue());
            };
            $element.find('.datasource-result').fseditor({
                overlay: true,
                disable_escape: true,
                expandOnFocus: false,
                transition: '', // 'fade', 'slide-in',
                placeholder: '',
                maxWidth: '', // maximum width of the editor on fullscreen mode
                maxHeight: '', // maximum height of the editor on fullscreen mode,
                onExpand: function () {
                }, // on switch to fullscreen mode callback
                onMinimize: function () {
                } // on switch to inline mode callback
            });

            $element.find('.list_table').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 1,
                    limit:10
                },
                {
                    name: 'datasource',
                    source: plugin.substringMatcher(plugin.settings.list_table),
                    limit:10
                });



        }
        plugin.savePropertyDataSource=function(property,add_on_id,close)
        {
            if(typeof ajaxSavePropertyBlock !== 'undefined'){
                ajaxSavePropertyBlock.abort();
            }
            plugin.save_data();
            var xml_output=$('#xml_output').val();
            var dataPost=property.find('select:not(.disable_post),textarea:not(.disable_post), input:not([readonly],.disable_post)').serializeObject();
            ajaxSavePropertyBlock=$.ajax({
                contentType: 'application/json',
                type: "POST",
                url: this_host+'/index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajaxSavePropertydatasource&add_on_id='+add_on_id+'&screensize='+currentScreenSizeEditing,
                data: JSON.stringify(dataPost),
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
                    response= $.parseJSON(response);
                    console.log(window['main_window']);
                    var html_dataset=response.html_dataset;
                    var data_source_id=response.data_source_id;
                    var data_source_title=response.title;
                    var curent_html_dataset=$('.data-set').find('.item-element[data-add-on-id="'+data_source_id+'"]');
                    if(curent_html_dataset.length)
                    {
                        curent_html_dataset.html(html_dataset);
                    }else
                    {

                        var new_data_set=$('<ul class="nav sub" id="dataset_'+data_source_id+'"></ul>');
                        new_data_set.append(html_dataset);
                        $('.data-set').append(new_data_set);
                    }

                    alert('save success');
                    var panelItemField=property.closest('.itemField');
                    panelItemField.find(':input[name*="jform"]').each(function(){
                        self=$(this);
                        name=self.attr('name');
                        $('.block-properties').find(':input[name="'+name+'"]').val(self.val());
                    });
                    if(close)
                        window.close();
                }
            });
        }

        plugin.save_data = function() {
            plugin.textarea.val(window.editor.getValue());
        };
        plugin.substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });

                cb(matches);
            };
        };

        plugin.getDataByQuery = function () {
            var source_id = plugin.settings.source_id;
            query = window.editor.getValue();
            ajaxGetStanderQuery = $.ajax({
                type: "GET",
                url: this_host + '/index.php',

                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_phpmyadmin',
                        task: 'datasource.ajaxGetDataByQuery',
                        query: query,
                        type: 'data_source',
                        source_id: source_id

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
                        $element.find('#grid_result_error').html(response.m).show();
                        $element.find('#grid_result').hide();
                    }
                    else {
                        $element.find('#grid_result').show();
                        var grid_result = $element.find('#grid_result').data("kendoGrid");
                        var columns = [];
                        $.each(response.r[0], function (key, value) {
                            var column = {};
                            column.field = key;
                            column.width = 150;
                            columns.push(column);
                        });

                        grid_result.setOptions({
                            columns: columns
                        });
                        grid_result.dataSource.data(response.r);
                    }
                }
            });

        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_datasource = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_datasource')) {
                var plugin = new $.field_datasource(this, options);
                $(this).data('field_datasource', plugin);

            }

        });

    }

})(jQuery);
