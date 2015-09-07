jQuery(document).ready(function ($) {
    var editor;
    ajax_php_content_loader = {
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
                    ajax_php_content_loader.render_table_fields(uiDraggable, droppable);
                } else if (uiDraggable.hasClass('configupdate-item-field')) {
                    ajax_php_content_loader.render_table_field(uiDraggable, droppable);
                }
            }
        },
        option_nestable: {
            group: 1,
            maxDepth: 10,
            handleClass: 'dd-handle-move',
            dragStop: function (e, el, dragEl) {
                ajax_php_content_loader.move_element(e, el, dragEl);
            }
        },
        getSelectedRange:function () {
            return { from: editor.getCursor(true), to: editor.getCursor(false) };
        },
        format_code:function(self){
            CodeMirror.commands["selectAll"](editor);
            var range = ajax_php_content_loader.getSelectedRange();
            editor.autoFormatRange(range.from, range.to);
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
                $('#' + id_menu_type).nestable(ajax_php_content_loader.option_nestable)
                    .on('change', ajax_php_content_loader.updateOutput);
            });


        },
        init_php_content_ajax_loader: function () {
            $(".dropdown-toggle").dropdown();

            var mime = 'application/javascript';
            if (window.location.href.indexOf('mime=') > -1) {
                mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
            }
            ;


            // completion for xdmp module
            CodeMirror.XQuery.defineModule({"prefix":"xdmp","namespace":"http://marklogic.com/xdmp","functions":[{"name":"access","as":"xs:boolean","params":[{"name":"uri","as":"xs:string"},{"name":"action","as":"xs:string"}]},{"name":"add-response-header","as":"empty-sequence()","params":[{"name":"name","as":"xs:string"},{"name":"value","as":"xs:string"}]},{"name":"amp","as":"xs:integer","params":[{"name":"namespace","as":"xs:string"},{"name":"localname","as":"xs:string"},{"name":"module-uri","as":"xs:string"},{"name":"database","as":"xs:unsignedLong"}]},{"name":"amp-roles","as":"xs:unsignedLong*","params":[{"name":"namespace-uri","as":"xs:string"},{"name":"localname","as":"xs:string"},{"name":"document-uri","as":"xs:string"},{"name":"database-id","as":"xs:unsignedLong"}]},{"name":"apply","as":"item()*","params":[{"name":"function","as":"xdmp:function"}]},{"name":"apply","as":"item()*","params":[{"name":"function","as":"xdmp:function"},{"name":"params-1-to-N","as":"item()*"}]},{"name":"architecture","as":"xs:string"},{"name":"base64-decode","as":"xs:string","params":[{"name":"encoded","as":"xs:string"}]},{"name":"base64-encode","as":"xs:string","params":[{"name":"plaintext","as":"xs:string"}]},{"name":"can-grant-roles","as":"empty-sequence()","params":[{"name":"roles","as":"xs:string*"}]},{"name":"castable-as","as":"xs:boolean","params":[{"name":"namespace-uri","as":"xs:string"},{"name":"local-name","as":"xs:string"},{"name":"item","as":"item()"}]},{"name":"collation-canonical-uri","as":"xs:string","params":[{"name":"collation-uri","as":"xs:string"}]},{"name":"collection-delete","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"}]},{"name":"collection-locks","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"}]},{"name":"collection-locks","as":"document-node()*"},{"name":"collection-properties","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"}]},{"name":"collection-properties","as":"document-node()*"},{"name":"database","as":"xs:unsignedLong","params":[{"name":"name","as":"xs:string"}]},{"name":"database","as":"xs:unsignedLong"},{"name":"database-backup","as":"xs:unsignedLong","params":[{"name":"forestIDs","as":"unsignedLong*"},{"name":"pathname","as":"xs:string"}]},{"name":"database-backup-cancel","as":"xs:boolean","params":[{"name":"jobid","as":"unsignedLong"}]},{"name":"database-backup-purge","as":"empty-sequence()","params":[{"name":"dir","as":"xs:string"},{"name":"keep-num-backups","as":"xs:unsignedLong"}]},{"name":"database-backup-status","as":"element()","params":[{"name":"jobid","as":"unsignedLong"}]},{"name":"database-backup-status","as":"element()","params":[{"name":"jobid","as":"unsignedLong"},{"name":"hostid","as":"unsignedLong"}]},{"name":"database-backup-validate","as":"element()","params":[{"name":"forestIDs","as":"unsignedLong*"},{"name":"pathname","as":"xs:string"}]},{"name":"database-forests","as":"xs:unsignedLong*","params":[{"name":"database","as":"xs:unsignedLong"}]},{"name":"database-name","as":"xs:string","params":[{"name":"id","as":"xs:unsignedLong"}]},{"name":"database-restore","as":"xs:unsignedLong","params":[{"name":"forestIDs","as":"unsignedLong*"},{"name":"pathname","as":"xs:string"}]},{"name":"database-restore","as":"xs:unsignedLong","params":[{"name":"forestIDs","as":"unsignedLong*"},{"name":"pathname","as":"xs:string"},{"name":"restoreToTime","as":"xs:dateTime?"}]},{"name":"database-restore-cancel","as":"xs:boolean","params":[{"name":"jobid","as":"unsignedLong"}]},{"name":"database-restore-status","as":"element()","params":[{"name":"jobid","as":"unsignedLong"}]},{"name":"database-restore-validate","as":"element()","params":[{"name":"forestIDs","as":"unsignedLong*"},{"name":"pathname","as":"xs:string"}]},{"name":"database-restore-validate","as":"element()","params":[{"name":"forestIDs","as":"unsignedLong*"},{"name":"pathname","as":"xs:string"},{"name":"restoreToTime","as":"xs:dateTime"}]},{"name":"databases","as":"xs:unsignedLong*"},{"name":"default-collections","as":"element()*","params":[{"name":"uri","as":"xs:string"}]},{"name":"default-collections","as":"element()*"},{"name":"default-permissions","as":"element()*","params":[{"name":"uri","as":"xs:string"}]},{"name":"default-permissions","as":"element()*"},{"name":"describe","as":"xs:string","params":[{"name":"item","as":"item()*"}]},{"name":"describe","as":"xs:string","params":[{"name":"item","as":"item()*"},{"name":"maxSequenceLength","as":"xs:unsignedInt?"}]},{"name":"describe","as":"xs:string","params":[{"name":"item","as":"item()*"},{"name":"maxSequenceLength","as":"xs:unsignedInt?"},{"name":"maxItemLength","as":"xs:unsignedInt*"}]},{"name":"diacritic-less","as":"xs:string","params":[{"name":"string","as":"xs:string"}]},{"name":"directory","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"}]},{"name":"directory","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"},{"name":"depth","as":"xs:string?"}]},{"name":"directory-create","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"}]},{"name":"directory-create","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"}]},{"name":"directory-create","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"}]},{"name":"directory-create","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"}]},{"name":"directory-create","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"},{"name":"forest-ids","as":"xs:unsignedLong*"}]},{"name":"directory-delete","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"}]},{"name":"directory-locks","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"}]},{"name":"directory-locks","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"},{"name":"depth","as":"xs:string?"}]},{"name":"directory-properties","as":"document-node()*","params":[{"name":"uri","as":"xs:string"}]},{"name":"directory-properties","as":"document-node()*","params":[{"name":"uri","as":"xs:string"},{"name":"depth","as":"xs:string?"}]},{"name":"document-add-collections","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"collections","as":"xs:string*"}]},{"name":"document-add-permissions","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"}]},{"name":"document-add-properties","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"props","as":"element()*"}]},{"name":"document-delete","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"}]},{"name":"document-forest","as":"xs:integer?","params":[{"name":"uri","as":"xs:string"}]},{"name":"document-forest","as":"xs:integer?","params":[{"name":"uri","as":"xs:string"},{"name":"forest-ids","as":"xs:unsignedLong*"}]},{"name":"document-get","as":"node()","params":[{"name":"location","as":"xs:string"}]},{"name":"document-get","as":"node()","params":[{"name":"location","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"document-get-collections","as":"xs:string*","params":[{"name":"uri","as":"xs:string"}]},{"name":"document-get-permissions","as":"element()*","params":[{"name":"uri","as":"xs:string"}]},{"name":"document-get-properties","as":"element()*","params":[{"name":"uri","as":"xs:string"},{"name":"property","as":"xs:QName"}]},{"name":"document-get-quality","as":"xs:integer?","params":[{"name":"uri","as":"xs:string"}]},{"name":"document-insert","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"root","as":"node()"}]},{"name":"document-insert","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"root","as":"node()"},{"name":"permissions","as":"element(sec:permission)*"}]},{"name":"document-insert","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"root","as":"node()"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"}]},{"name":"document-insert","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"root","as":"node()"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"}]},{"name":"document-insert","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"root","as":"node()"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"},{"name":"forest-ids","as":"xs:unsignedLong*"}]},{"name":"document-load","as":"empty-sequence()","params":[{"name":"location","as":"xs:string"}]},{"name":"document-load","as":"empty-sequence()","params":[{"name":"location","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"document-locks","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"}]},{"name":"document-locks","as":"document-node()*"},{"name":"document-properties","as":"document-node()*","params":[{"name":"uri","as":"xs:string*"}]},{"name":"document-properties","as":"document-node()*"},{"name":"document-remove-collections","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"collections","as":"xs:string*"}]},{"name":"document-remove-permissions","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"}]},{"name":"document-remove-properties","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"property-names","as":"xs:QName*"}]},{"name":"document-set-collections","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"collections","as":"xs:string*"}]},{"name":"document-set-permissions","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"permissions","as":"element(sec:permission)*"}]},{"name":"document-set-properties","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"props","as":"element()*"}]},{"name":"document-set-property","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"prop","as":"element()"}]},{"name":"document-set-quality","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"quality","as":"xs:int"}]},{"name":"elapsed-time","as":"xs:dayTimeDuration"},{"name":"element-content-type","as":"xs:string","params":[{"name":"element","as":"element()"}]},{"name":"email","as":"empty-sequence()","params":[{"name":"message","as":"node()"}]},{"name":"estimate","as":"xs:integer","params":[{"name":"expression","as":"item()*"}]},{"name":"estimate","as":"xs:integer","params":[{"name":"expression","as":"item()*"},{"name":"maximum","as":"xs:double?"}]},{"name":"eval","as":"item()*","params":[{"name":"xquery","as":"xs:string"}]},{"name":"eval","as":"item()*","params":[{"name":"xquery","as":"xs:string"},{"name":"vars","as":"item()*"}]},{"name":"eval","as":"item()*","params":[{"name":"xquery","as":"xs:string"},{"name":"vars","as":"item()*"},{"name":"options","as":"node()?"}]},{"name":"eval-in","as":"item()*","params":[{"name":"xquery","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"}]},{"name":"eval-in","as":"item()*","params":[{"name":"xquery","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"}]},{"name":"eval-in","as":"item()*","params":[{"name":"xquery","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"},{"name":"modules","as":"xs:unsignedLong?"}]},{"name":"eval-in","as":"item()*","params":[{"name":"xquery","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"},{"name":"modules","as":"xs:unsignedLong?"},{"name":"root","as":"xs:string?"}]},{"name":"excel-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"}]},{"name":"excel-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"exists","as":"xs:integer","params":[{"name":"expression","as":"item()*"}]},{"name":"filesystem-directory","as":"element(dir:directory)","params":[{"name":"pathname","as":"xs:string"}]},{"name":"filesystem-file","as":"xs:string","params":[{"name":"pathname","as":"xs:string"}]},{"name":"forest","as":"xs:unsignedLong","params":[{"name":"name","as":"xs:string"}]},{"name":"forest-backup","as":"empty-sequence()","params":[{"name":"forestID","as":"unsignedLong"},{"name":"pathname","as":"xs:string"}]},{"name":"forest-clear","as":"empty-sequence()","params":[{"name":"forestIDs","as":"unsignedLong*"}]},{"name":"forest-counts","as":"element(forest-counts)","params":[{"name":"forest-id","as":"xs:unsignedLong"}]},{"name":"forest-counts","as":"element(forest-counts)","params":[{"name":"forest-id","as":"xs:unsignedLong"},{"name":"show-elements","as":"xs:string*"}]},{"name":"forest-databases","as":"xs:unsignedLong","params":[{"name":"forest","as":"xs:unsignedLong"}]},{"name":"forest-name","as":"xs:string","params":[{"name":"id","as":"xs:unsignedLong"}]},{"name":"forest-restart","as":"empty-sequence()","params":[{"name":"forestID","as":"unsignedLong"}]},{"name":"forest-restore","as":"empty-sequence()","params":[{"name":"forestID","as":"unsignedLong"},{"name":"pathname","as":"xs:string"}]},{"name":"forest-status","as":"element(forest-status)","params":[{"name":"forest-id","as":"xs:unsignedLong"}]},{"name":"forests","as":"xs:unsignedLong*"},{"name":"from-json","as":"item()*","params":[{"name":"arg","as":"xs:string"}]},{"name":"function","as":"xdmp:function","params":[{"name":"function","as":"xs:QName"}]},{"name":"function","as":"xdmp:function","params":[{"name":"function","as":"xs:QName"},{"name":"module-path","as":"xs:string?"}]},{"name":"function-module","as":"xs:string","params":[{"name":"function","as":"xdmp:function"}]},{"name":"function-name","as":"xs:QName","params":[{"name":"function","as":"xdmp:function"}]},{"name":"get","as":"node()","params":[{"name":"path","as":"xs:string"}]},{"name":"get","as":"node()","params":[{"name":"path","as":"xs:string"},{"name":"default-namespace","as":"xs:string?"}]},{"name":"get","as":"node()","params":[{"name":"path","as":"xs:string"},{"name":"default-namespace","as":"xs:string?"},{"name":"options","as":"xs:string*"}]},{"name":"get-current-roles","as":"xs:unsignedLong*"},{"name":"get-current-user","as":"xs:string"},{"name":"get-request-body","as":"item()*","params":[{"name":"format","as":"xs:string?"}]},{"name":"get-request-body","as":"item()*"},{"name":"get-request-client-address","as":"xs:string?"},{"name":"get-request-client-certificate","as":"xs:string?"},{"name":"get-request-field","as":"xs:string*","params":[{"name":"name","as":"xs:string"}]},{"name":"get-request-field","as":"xs:string*","params":[{"name":"name","as":"xs:string"},{"name":"default","as":"xs:string?"}]},{"name":"get-request-field-content-type","as":"xs:string*","params":[{"name":"field-name","as":"xs:string"}]},{"name":"get-request-field-filename","as":"xs:string*","params":[{"name":"field-name","as":"xs:string"}]},{"name":"get-request-field-names","as":"xs:string*"},{"name":"get-request-header","as":"xs:string*","params":[{"name":"name","as":"xs:string"}]},{"name":"get-request-header","as":"xs:string*","params":[{"name":"name","as":"xs:string"},{"name":"default","as":"xs:string?"}]},{"name":"get-request-header-names","as":"xs:string*"},{"name":"get-request-method","as":"xs:string"},{"name":"get-request-path","as":"xs:string"},{"name":"get-request-protocol","as":"xs:string?"},{"name":"get-request-url","as":"xs:string"},{"name":"get-request-user","as":"xs:unsignedLong"},{"name":"get-request-username","as":"xs:string"},{"name":"get-response-code","as":"item()*"},{"name":"get-response-encoding","as":"xs:string"},{"name":"get-session-field","as":"item()*","params":[{"name":"name","as":"xs:string"}]},{"name":"get-session-field","as":"item()*","params":[{"name":"name","as":"xs:string"},{"name":"default","as":"item()*"}]},{"name":"get-session-field-names","as":"xs:string*"},{"name":"group","as":"xs:unsignedLong","params":[{"name":"name","as":"xs:string"}]},{"name":"group","as":"xs:unsignedLong"},{"name":"group-hosts","as":"xs:unsignedLong*","params":[{"name":"name","as":"xs:unsignedLong"}]},{"name":"group-hosts","as":"xs:unsignedLong*"},{"name":"group-name","as":"xs:string","params":[{"name":"name","as":"xs:unsignedLong"}]},{"name":"group-name","as":"xs:string"},{"name":"group-servers","as":"xs:unsignedLong*","params":[{"name":"name","as":"xs:unsignedLong"}]},{"name":"group-servers","as":"xs:unsignedLong*"},{"name":"groups","as":"xs:unsignedLong*"},{"name":"has-privilege","as":"xs:boolean","params":[{"name":"privileges","as":"xs:string*"},{"name":"kind","as":"xs:string"}]},{"name":"hash32","as":"xs:unsignedInt","params":[{"name":"string","as":"xs:string"}]},{"name":"hash64","as":"xs:unsignedLong","params":[{"name":"string","as":"xs:string"}]},{"name":"hex-to-integer","as":"xs:integer","params":[{"name":"hex","as":"xs:string"}]},{"name":"host","as":"xs:unsignedLong","params":[{"name":"name","as":"xs:string"}]},{"name":"host","as":"xs:unsignedLong"},{"name":"host-name","as":"xs:string","params":[{"name":"ID","as":"xs:unsignedLong"}]},{"name":"host-status","as":"element(host-status)","params":[{"name":"host-id","as":"xs:unsignedLong"}]},{"name":"hosts","as":"xs:unsignedLong*"},{"name":"http-delete","as":"item()+","params":[{"name":"uri","as":"xs:string"}]},{"name":"http-delete","as":"item()+","params":[{"name":"uri","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"http-get","as":"item()+","params":[{"name":"uri","as":"xs:string"}]},{"name":"http-get","as":"item()+","params":[{"name":"uri","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"http-head","as":"item()+","params":[{"name":"uri","as":"xs:string"}]},{"name":"http-head","as":"item()+","params":[{"name":"uri","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"http-options","as":"item()+","params":[{"name":"uri","as":"xs:string"}]},{"name":"http-options","as":"item()+","params":[{"name":"uri","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"http-post","as":"item()+","params":[{"name":"uri","as":"xs:string"}]},{"name":"http-post","as":"item()+","params":[{"name":"uri","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"http-put","as":"item()+","params":[{"name":"uri","as":"xs:string"}]},{"name":"http-put","as":"item()+","params":[{"name":"uri","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"integer-to-hex","as":"xs:string","params":[{"name":"val","as":"xs:integer"}]},{"name":"integer-to-octal","as":"xs:string","params":[{"name":"val","as":"xs:integer"}]},{"name":"invoke","as":"item()*","params":[{"name":"path","as":"xs:string"}]},{"name":"invoke","as":"item()*","params":[{"name":"path","as":"xs:string"},{"name":"vars","as":"item()*"}]},{"name":"invoke","as":"item()*","params":[{"name":"path","as":"xs:string"},{"name":"vars","as":"item()*"},{"name":"options","as":"node()?"}]},{"name":"invoke-in","as":"item()*","params":[{"name":"uri","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"}]},{"name":"invoke-in","as":"item()*","params":[{"name":"uri","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"}]},{"name":"invoke-in","as":"item()*","params":[{"name":"uri","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"},{"name":"modules","as":"xs:unsignedLong?"}]},{"name":"invoke-in","as":"item()*","params":[{"name":"uri","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"},{"name":"modules","as":"xs:unsignedLong?"},{"name":"root","as":"xs:string?"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"},{"name":"permissions","as":"element(sec:permission)*"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"},{"name":"default-namespace","as":"xs:string?"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"},{"name":"default-namespace","as":"xs:string?"},{"name":"options","as":"xs:string*"}]},{"name":"load","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"uri","as":"xs:string?"},{"name":"permissions","as":"element(sec:permission)*"},{"name":"collections","as":"xs:string*"},{"name":"quality","as":"xs:int?"},{"name":"default-namespace","as":"xs:string?"},{"name":"options","as":"xs:string*"},{"name":"forest-ids","as":"xs:unsignedLong*"}]},{"name":"lock-acquire","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"}]},{"name":"lock-acquire","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"scope","as":"xs:string?"}]},{"name":"lock-acquire","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"scope","as":"xs:string?"},{"name":"depth","as":"xs:string?"}]},{"name":"lock-acquire","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"scope","as":"xs:string?"},{"name":"depth","as":"xs:string?"},{"name":"owner","as":"item()?"}]},{"name":"lock-acquire","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"},{"name":"scope","as":"xs:string?"},{"name":"depth","as":"xs:string?"},{"name":"owner","as":"item()?"},{"name":"timeout","as":"xs:unsignedLong?"}]},{"name":"lock-release","as":"empty-sequence()","params":[{"name":"uri","as":"xs:string"}]},{"name":"log","as":"empty-sequence()","params":[{"name":"msg","as":"item()*"}]},{"name":"log","as":"empty-sequence()","params":[{"name":"msg","as":"item()*"},{"name":"level","as":"xs:string?"}]},{"name":"log-level","as":"xs:string"},{"name":"login","as":"xs:boolean","params":[{"name":"name","as":"xs:string"}]},{"name":"login","as":"xs:boolean","params":[{"name":"name","as":"xs:string"},{"name":"password","as":"xs:string?"}]},{"name":"login","as":"xs:boolean","params":[{"name":"name","as":"xs:string"},{"name":"password","as":"xs:string?"},{"name":"set-session","as":"xs:boolean?"}]},{"name":"logout","as":"empty-sequence()"},{"name":"md5","as":"xs:string","params":[{"name":"encoded","as":"xs:string"}]},{"name":"merge","as":"empty-sequence()","params":[{"name":"options","as":"node()?"}]},{"name":"merge","as":"empty-sequence()"},{"name":"merge-cancel","as":"empty-sequence()","params":[{"name":"forest-ID","as":"xs:unsignedLong"},{"name":"merge-ID","as":"xs:unsignedLong"}]},{"name":"merging","as":"xs:unsignedLong*"},{"name":"modules-database","as":"xs:unsignedLong"},{"name":"modules-root","as":"xs:string"},{"name":"node-database","as":"xs:unsignedLong?","params":[{"name":"node","as":"node()"}]},{"name":"node-delete","as":"empty-sequence()","params":[{"name":"old","as":"node()"}]},{"name":"node-insert-after","as":"empty-sequence()","params":[{"name":"sibling","as":"node()"},{"name":"new","as":"node()"}]},{"name":"node-insert-before","as":"empty-sequence()","params":[{"name":"sibling","as":"node()"},{"name":"new","as":"node()"}]},{"name":"node-insert-child","as":"empty-sequence()","params":[{"name":"parent","as":"node()"},{"name":"new","as":"node()"}]},{"name":"node-kind","as":"xs:string","params":[{"name":"node","as":"node()?"}]},{"name":"node-replace","as":"empty-sequence()","params":[{"name":"old","as":"node()"},{"name":"new","as":"node()"}]},{"name":"node-uri","as":"xs:string?","params":[{"name":"node","as":"node()"}]},{"name":"octal-to-integer","as":"xs:integer","params":[{"name":"octal","as":"xs:string"}]},{"name":"path","as":"xs:string","params":[{"name":"node","as":"node()"}]},{"name":"path","as":"xs:string","params":[{"name":"node","as":"node()"},{"name":"include-document","as":"xs:boolean?"}]},{"name":"pdf-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"}]},{"name":"pdf-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"permission","as":"element()","params":[{"name":"role","as":"xs:string"},{"name":"capability","as":"xs:string"}]},{"name":"platform","as":"xs:string"},{"name":"powerpoint-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"}]},{"name":"powerpoint-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"pretty-print","as":"xs:string","params":[{"name":"xquery","as":"xs:string"}]},{"name":"privilege","as":"xs:integer","params":[{"name":"action","as":"xs:string"},{"name":"kind","as":"xs:string"}]},{"name":"privilege-roles","as":"xs:unsignedLong*","params":[{"name":"action","as":"xs:string"},{"name":"kind","as":"xs:string"}]},{"name":"product-edition","as":"xs:string"},{"name":"query-meters","as":"element()"},{"name":"query-trace","as":"empty-sequence()","params":[{"name":"enabled","as":"xs:boolean"}]},{"name":"quote","as":"xs:string","params":[{"name":"arg","as":"item()*"}]},{"name":"quote","as":"xs:string","params":[{"name":"arg","as":"item()*"},{"name":"options","as":"node()?"}]},{"name":"random","as":"xs:unsignedLong","params":[{"name":"max","as":"xs:unsignedLong"}]},{"name":"random","as":"xs:unsignedLong"},{"name":"redirect-response","as":"empty-sequence()","params":[{"name":"name","as":"xs:string"}]},{"name":"request","as":"xs:unsignedLong"},{"name":"request-cancel","as":"empty-sequence()","params":[{"name":"hostID","as":"xs:unsignedLong"},{"name":"serverID","as":"xs:unsignedLong"},{"name":"requestID","as":"xs:unsignedLong"}]},{"name":"request-status","as":"element(request-status)","params":[{"name":"host-id","as":"xs:unsignedLong"},{"name":"server-id","as":"xs:unsignedLong"},{"name":"request-id","as":"xs:unsignedLong"}]},{"name":"request-timestamp","as":"xs:unsignedLong?"},{"name":"restart","as":"empty-sequence()","params":[{"name":"hostIDs","as":"unsignedLong*"},{"name":"reason","as":"xs:string"}]},{"name":"rethrow","as":"empty-sequence()"},{"name":"role","as":"xs:integer","params":[{"name":"role","as":"xs:string"}]},{"name":"role-roles","as":"xs:unsignedLong*","params":[{"name":"name","as":"xs:string"}]},{"name":"save","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"node","as":"node()"}]},{"name":"save","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"node","as":"node()"},{"name":"options","as":"node()?"}]},{"name":"schema-database","as":"xs:unsignedLong"},{"name":"security-assert","as":"empty-sequence()","params":[{"name":"privileges","as":"xs:string*"},{"name":"kind","as":"xs:string"}]},{"name":"security-database","as":"xs:unsignedLong"},{"name":"server","as":"xs:unsignedLong+","params":[{"name":"name","as":"xs:string"}]},{"name":"server","as":"xs:unsignedLong+"},{"name":"server-name","as":"xs:string","params":[{"name":"id","as":"xs:unsignedLong"}]},{"name":"server-status","as":"element(server-status)","params":[{"name":"host-id","as":"xs:unsignedLong"},{"name":"server-id","as":"xs:unsignedLong"}]},{"name":"servers","as":"xs:unsignedLong*"},{"name":"set","as":"empty-sequence()","params":[{"name":"variable","as":"item()*"},{"name":"expr","as":"item()*"}]},{"name":"set-request-time-limit","as":"empty-sequence()","params":[{"name":"time-limit","as":"xs:unsignedInt"}]},{"name":"set-request-time-limit","as":"empty-sequence()","params":[{"name":"time-limit","as":"xs:unsignedInt"},{"name":"hostID","as":"xs:unsignedLong?"}]},{"name":"set-request-time-limit","as":"empty-sequence()","params":[{"name":"time-limit","as":"xs:unsignedInt"},{"name":"hostID","as":"xs:unsignedLong?"},{"name":"serverID","as":"xs:unsignedLong?"}]},{"name":"set-request-time-limit","as":"empty-sequence()","params":[{"name":"time-limit","as":"xs:unsignedInt"},{"name":"hostID","as":"xs:unsignedLong?"},{"name":"serverID","as":"xs:unsignedLong?"},{"name":"requestID","as":"xs:unsignedLong?"}]},{"name":"set-response-code","as":"empty-sequence()","params":[{"name":"code","as":"xs:integer"},{"name":"message","as":"xs:string"}]},{"name":"set-response-content-type","as":"empty-sequence()","params":[{"name":"name","as":"xs:string"}]},{"name":"set-response-encoding","as":"empty-sequence()","params":[{"name":"encoding","as":"xs:string"}]},{"name":"set-session-field","as":"item()*","params":[{"name":"name","as":"xs:string"},{"name":"value","as":"item()*"}]},{"name":"shutdown","as":"empty-sequence()","params":[{"name":"hostIDs","as":"unsignedLong*"},{"name":"reason","as":"xs:string"}]},{"name":"sleep","as":"empty-sequence()","params":[{"name":"msec","as":"xs:unsignedInt"}]},{"name":"spawn","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"}]},{"name":"spawn","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"vars","as":"item()*"}]},{"name":"spawn","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"vars","as":"item()*"},{"name":"options","as":"node()?"}]},{"name":"spawn-in","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"}]},{"name":"spawn-in","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"}]},{"name":"spawn-in","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"},{"name":"modules","as":"xs:unsignedLong?"}]},{"name":"spawn-in","as":"empty-sequence()","params":[{"name":"path","as":"xs:string"},{"name":"ID","as":"xs:unsignedLong"},{"name":"vars","as":"item()*"},{"name":"modules","as":"xs:unsignedLong?"},{"name":"root","as":"xs:string?"}]},{"name":"strftime","as":"xs:string","params":[{"name":"format","as":"xs:string"},{"name":"value","as":"xs:dateTime"}]},{"name":"subbinary","as":"binary()","params":[{"name":"source","as":"binary()"},{"name":"starting-location","as":"xs:double"}]},{"name":"subbinary","as":"binary()","params":[{"name":"source","as":"binary()"},{"name":"starting-location","as":"xs:double"},{"name":"length","as":"xs:double"}]},{"name":"tidy","as":"node()+","params":[{"name":"doc","as":"xs:string"}]},{"name":"tidy","as":"node()+","params":[{"name":"doc","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"to-json","as":"xs:string","params":[{"name":"item","as":"item()*"}]},{"name":"trace","as":"empty-sequence()","params":[{"name":"name","as":"xs:string"},{"name":"value","as":"item()*"}]},{"name":"triggers-database","as":"xs:unsignedLong"},{"name":"unpath","as":"item()*","params":[{"name":"expr","as":"xs:string"}]},{"name":"unquote","as":"document-node()+","params":[{"name":"arg","as":"xs:string"}]},{"name":"unquote","as":"document-node()+","params":[{"name":"arg","as":"xs:string"},{"name":"default-namespace","as":"xs:string?"}]},{"name":"unquote","as":"document-node()+","params":[{"name":"arg","as":"xs:string"},{"name":"default-namespace","as":"xs:string?"},{"name":"options","as":"xs:string*"}]},{"name":"uri-content-type","as":"xs:string","params":[{"name":"uri","as":"xs:string"}]},{"name":"uri-format","as":"xs:string","params":[{"name":"uri","as":"xs:string"}]},{"name":"uri-is-file","as":"xs:boolean?","params":[{"name":"uri","as":"xs:string"}]},{"name":"url-decode","as":"xs:string","params":[{"name":"encoded","as":"xs:string"}]},{"name":"url-encode","as":"xs:string","params":[{"name":"plaintext","as":"xs:string"}]},{"name":"url-encode","as":"xs:string","params":[{"name":"plaintext","as":"xs:string"},{"name":"noSpacePlus","as":"xs:boolean?"}]},{"name":"user","as":"xs:integer","params":[{"name":"user","as":"xs:string"}]},{"name":"user-last-login","as":"element(last-login)?","params":[{"name":"user","as":"xs:unsignedLong"}]},{"name":"user-roles","as":"xs:unsignedLong*","params":[{"name":"name","as":"xs:string"}]},{"name":"value","as":"item()*","params":[{"name":"expr","as":"xs:string"}]},{"name":"version","as":"xs:string"},{"name":"with-namespaces","as":"item()*","params":[{"name":"nsbindings","as":"xs:string*"},{"name":"expr","as":"item()*"}]},{"name":"word-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"}]},{"name":"word-convert","as":"node()*","params":[{"name":"doc","as":"node()"},{"name":"filename","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"xquery-version","as":"xs:string"},{"name":"zip-create","as":"binary()","params":[{"name":"manifest","as":"node()"},{"name":"nodes","as":"node()+"}]},{"name":"zip-get","as":"node()+","params":[{"name":"zipfile","as":"binary()"},{"name":"name","as":"xs:string"}]},{"name":"zip-get","as":"node()+","params":[{"name":"zipfile","as":"binary()"},{"name":"name","as":"xs:string"},{"name":"options","as":"node()?"}]},{"name":"zip-manifest","as":"node()external;","params":[{"name":"zipfile","as":"binary()"}]}]});
            // completion for map module
            CodeMirror.XQuery.defineModule({"prefix":"map","namespace":"http://marklogic.com/xdmp/map","functions":[{"name":"clear","as":"empty-sequence()","params":[{"name":"map","as":"map:map"}]},{"name":"count","as":"xs:unsignedInt","params":[{"name":"map","as":"map:map"}]},{"name":"delete","as":"empty-sequence()","params":[{"name":"map","as":"map:map"},{"name":"key","as":"xs:string"}]},{"name":"get","as":"item()*","params":[{"name":"map","as":"map:map"},{"name":"key","as":"xs:string"}]},{"name":"keys","as":"xs:string*","params":[{"name":"map","as":"map:map"}]},{"name":"map","as":"map:map","params":[{"name":"map","as":"element(map:map)"}]},{"name":"map","as":"map:map"},{"name":"put","as":"empty-sequence()","params":[{"name":"map","as":"map:map"},{"name":"key","as":"xs:string"},{"name":"value","as":"item()*"}]}]});

            function passAndHint(cm) {
                setTimeout(function() {cm.execCommand("autocomplete");}, 100);
                return CodeMirror.Pass;
            }



            function displayHintStatus(annotations, annotationsGroupedByLine, cm) {
                CodeMirror.statusLint(annotations, 'hintStatus', cm);
            }

            editor = CodeMirror.fromTextArea(document.getElementById("php_content"), {
                mode: "application/x-httpd-php",
                lineNumbers: true,
                styleActiveLine: true,
                lineNumbers: true,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                trackContext : true,
                textHover: true,
                fixedGutter:true,
                extraKeys: {
                    "':'": passAndHint,
                    "'$'": passAndHint,
                    "Ctrl-Space": "autocomplete",
                    "F11": function(cm) {
                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                    },
                    "Esc": function(cm) {
                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                    }
                },
                ajax_loader: {
                    ajax: false,
                    component: 'com_phpmyadmin',
                    task: 'tables.ajax_get_list_table_and_field',
                    func_success: function (response, cm) {
                        jQuery.each(response, function (index, table) {
                            editor.options.hintOptions.tables[index] = {};
                            jQuery.each(table, function (field, type) {
                                editor.options.hintOptions.tables[index][field] = null;
                            });

                        });
                    }
                },
                gutters: ["CodeMirror-lint-markers", "CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                lintWith : {
                    "getAnnotations" : CodeMirror.remotingValidator,
                    "onUpdateLinting" : displayHintStatus,
                    "async" : true,
                    "url" : "jaxrs/lint/annotations"
                },
                foldGutter: {
                    rangeFinder : new CodeMirror.fold.combine(CodeMirror.fold.brace, CodeMirror.fold.comment, CodeMirror.fold.xml)
                },
                textHover: true
            });
            editor.on("changes", function(cm, change) {
                $('#php_content').val(cm.getValue());
            });
            function run() {
                CodeMirror.remotingExecutor(editor,
                    {"url" : "jaxrs/execute/run",
                        "onResult" : function(data, isError) {
                            var result = document.getElementById('result');
                            if (isError) {
                                result.className="cm-s-default error";
                            } else {
                                result.className="cm-s-default";
                            }
                            CodeMirror.runMode(data, "application/xml",
                                result);
                        }
                    });
            }



            /*editor = CodeMirror.fromTextArea(document.getElementById('php_content'), {
                mode: mime,
                indentWithTabs: true,
                smartIndent: true,
                lineNumbers: true,
                matchBrackets: true,
                fullScreen: false,
                autofocus: true,
                textHover: true,
                styleActiveLine: true,
                theme : "eclipse",
                gutters: ["CodeMirror-linenumbers"],
                extraKeys: {
                    //"'?'": "autocomplete1",
                    "Ctrl-Space": "autocomplete",
                    "Ctrl-F": "list_function"
                },
                hintOptions: {
                    tables: {
                        *//*table__users: {name: null, score: null, birthDate: null},
                         countries: {name: null, population: null, size: null}*//*
                    }
                },
                ajax_loader: {
                    ajax: false,
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
                list_table: []


            });
            list_function = [
                'get_json_group_concat(id:id,title:title)',
                'request(id,0)'
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
            editor.on("changes", function(cm, change) {
                $('#php_content').val(cm.getValue());
            });
*/

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
            li_clone.find(".icon").select2(ajax_php_content_loader.icon_option);
            ajax_php_content_loader.update_nestable();
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

            li_clone.find("input.icon").select2(ajax_php_content_loader.icon_option);
            ajax_php_content_loader.update_nestable();
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
            ajax_php_content_loader.update_nestable();
            dd_item = self.closest('.dd-item');
            id = dd_item.attr('data-id');
            list_keys_values = {};
            list_keys_values[key] = self_value;
            ajax_php_content_loader.update_menu_item(id, list_keys_values);

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
                ajax_php_content_loader.update_data_column(this, 'home', 'radio');
            });
        },
        set_auto_complete: function () {
            $(".icon_menu_item").select2(ajax_php_content_loader.icon_option);
        },
        update_nestable: function () {
            $('.a_menu_type').each(function render_nestable() {
                id_menu_type = $(this).attr('id');
                ajax_php_content_loader.updateOutput($('#' + id_menu_type).data('output', $('#' + id_menu_type + '_output')));
            });


        },
        remove_item_nestable: function (self) {
            self = $(self);
            dd_item = self.closest('.dd-item');
            if ($('.dd-item').length > 1)
                dd_item.remove();
            ajax_php_content_loader.update_nestable();
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