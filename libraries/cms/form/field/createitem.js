(function ($) {

    // here we go!
    $.field_createitem = function (element, options) {

        // plugin's default options
        var defaults = {
            maxDepth: 1,
            element_ouput: '#field_config',
            field_name:''
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

            plugin.set_auto_complete();
            // activate Nestable for list 1
            plugin.option_nestable={
                group: 1,
                maxDepth: plugin.settings.maxDepth,
                //maxDepth: plugin.settings.maxDepth,
                handleClass:'dd-handle'
            },

            plugin.init_config_nestable();
            $( ".configupdate-item-table" ).draggable(plugin.option_draggable);
            plugin.update_nestable();
            $('.dd-list-droppable').droppable(plugin.option_droppable);
            $('.configupdate-item-table a.plus').click(function(){
                plugin.get_list_field_table($(this));
            });
            $element.find('.add_node').click(function(){
                plugin.add_node($(this));
            });


            $element.find('.add_sub_node').click(function(){
                plugin.remove_item_nestable($(this));
            });
            $('.panel-body.property.block_property').data('update_field',plugin.update_createitem);



            $element.find('.remove_item_nestable').click(function(){
                plugin.add_sub_node(this);
            });

            $element.find('.expand_item_nestable').click(function(){
                plugin.expand_item_nestable(this);
            });
            $element.find('.show_more_options').click(function show_more_options() {
                plugin.show_more_options($(this));
            });
            $element.find('.edit_item_nestable').click(function edit_item_nestable() {
                plugin.edit_item_nestable($(this));
            });
            $element.find('.save_and_close_item_nestable').click(function save_and_close_item_nestable() {
                plugin.save_item_nestable($(this),true);
            });
            $element.find('.cancel_item_nestable').click(function cancel_item_nestable() {
                plugin.cancel_item_nestable($(this));
            });
            $element.find('.save_item_nestable').click(function save_item_nestable() {
                plugin.save_item_nestable($(this));
            });

            $element.find(plugin.settings.element_ouput).change(function(){
                var items={};
                items.field_config=$(this).val();
                items.list=$('#config_update1-output').val();
                items=JSON.stringify(items);
                items=base64.encode(items);
                $('input[name="'+plugin.settings.field_name+'"]').val(items);
                var field_config=base64.encode($(this).val());
                $wapper_more_options=$element.find('.wapper_more_options');
                var random=plugin.makeid();
                $wapper_more_options.attr('wapper_more_options_random',random);
                var append='.wapper_more_options[wapper_more_options_random="'+random+'"]';


                ajax_web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    url: this_host + '/index.php',
                    data: (function () {
                        dataPost = {
                            option: 'com_utility',
                            view: 'fields',
                            tmpl: 'ajax_json',
                            field_config: field_config,
                            append: append

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
                        Joomla.sethtmlfortag1(response);


                    }
                });



            });

            $element.find('button.config-field-createitem').click(function () {
                var field_config =$element.find(plugin.settings.element_ouput).val();
                var field_config=base64.encode(field_config);
                var random=plugin.makeid();
                $(plugin.settings.element_ouput).attr('createitem-random',random);
                var element_ouput=plugin.settings.element_ouput+'[createitem-random="'+random+'"]';
                ajax_web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            view: 'params',
                            tmpl: 'ajax_json',
                            layout: 'createitem',
                            field_config: field_config,
                            maxDepth: 1,
                            element_ouput: element_ouput

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

                        if (!$('.panel.createitem-config').length) {
                            html = $(
                                '<div  class="panel params panel-primary createitem-config  panelMove toggle panelRefresh panelClose"  >' +
                                '<div class="panel-heading createitem-handle">' +
                                '<h4 class="panel-title">property manager</h4>' +
                                '</div>' +
                                '<div class="panel-body params"></div>' +
                                '</div>'
                            );
                            $('body').prepend(html);

                            html.css({
                                position: 'absolute'

                            }).draggable({
                                handle: '.createitem-handle,.createitem-handle-footer'
                            });
                        }
                        Joomla.sethtmlfortag1(response);


                    }
                });

            });


        }
        plugin.makeid = function makeid() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < 5; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

        plugin.option_draggable={
            appendTo: 'body',
                /*helper: function(){
                 dd_list= $('.dd-list:first').clone(true);
                 dd_list.empty();
                 dd_list.addClass('dd-dragel');
                 dd_item= $('.dd-item:last').clone(true);
                 dd_list.append(dd_item);
                 return dd_list;
                 },*/
                helper:'clone',

                start: function( event, ui ) {
            },
            drag: function( event, ui ) {


            }
        }
        plugin.update_createitem=function() {
            var items = {};
            items.field_config = $(plugin.settings.element_ouput).val();
            items.list = $('#config_update1-output').val();
            items = JSON.stringify(items);
            items = base64.encode(items);
            $('input[name="' + plugin.settings.field_name + '"]').val(items);
        }
        plugin.expand_item_nestable=function(self) {
            var self=$(self);
            var dd_item=self.closest('.dd-item');
            var more_options=dd_item.find('> .more_options');
            if(more_options.is(':visible'))
            {
                self.find('i.im-minus').addClass('im-plus').removeClass('im-minus');
                more_options.css({
                    display:"none"
                });
            }else{
                self.find('i.im-plus').addClass('im-minus').removeClass('im-plus');
                more_options.css({
                    display:"block"
                });
            }
        }
        plugin.table_name_option={
            ajax: {
                url: this_host+"/index.php?option=com_phpmyadmin&task=tables.ajax_get_list_table",
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
            initSelection: function(element, callback) {
                item={
                    id:element.val(),
                    text:element.val()
                };
                return callback(item);
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
        },
        plugin.column_name_option={
            ajax: {
                url: this_host+"/index.php?option=com_phpmyadmin&task=tables.ajax_get_list_flied_table",
                    dataType: 'json',
                    delay: 250,
                    data: function (term, page) {
                    dd_item=$(this).closest('li.dd-item');
                    return {
                        keyword: term,
                        table_name:function(){
                            table_name=dd_item.data('table_name');
                            return table_name;
                        }
                    };
                },

                results: function (data) {
                    return {results: data};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                item={
                    id:element.val(),
                    text:element.val()
                };
                return callback(item);
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0
        },
        plugin.post_name_option={
            data: function() {
                data_post={};
                $( ':input[enable-submit="true"],:selected[enable-submit="true"]').each(function(){
                    name=$(this).attr('name');
                    item={
                        id:name,
                        text:name
                    };
                    data_post[name]=item;

                });
                parser_url= $.url(currentLink).param();
                $.each(parser_url, function( index, value ) {

                    item={
                        id:index,
                        text:index
                    };
                    data_post[index]=item;
                });
                return_data_post=new Array();
                i=0;
                $.each(data_post, function( index, value ) {

                    return_data_post[i]=value;
                    i++;
                });
                return {results: return_data_post};
            },
            initSelection: function(element, callback) {
                item={
                    id:element.val(),
                    text:element.val()
                };
                return callback(item);
            }
        },
        plugin.option_droppable={
            accept: ".configupdate-item-table,.configupdate-item-field",
                greedy: true,
                drop: function(ev,ui){
                uiDraggable=$(ui.draggable);
                droppable=$(this);
                if(uiDraggable.hasClass('configupdate-item-table'))
                {
                    plugin.render_table_fields(uiDraggable,droppable);
                }else if(uiDraggable.hasClass('configupdate-item-field')){
                    plugin.render_table_field(uiDraggable,droppable);
                }
            }
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

        plugin.init_config_nestable=function(){
            $('#config_update1').nestable(plugin.option_nestable)
                .on('change', plugin.updateOutput);




        }
        plugin.edit_item_nestable=function(self){
            $more_options=self.closest('.more_options');

            $element.find('.wapper_more_options').appendTo($more_options);
            $element.find('.wapper_more_options').empty();
            field_config=$element.find(plugin.settings.element_ouput).val();
            var field_config=base64.encode(field_config);
            $wapper_more_options=$element.find('.wapper_more_options');
            var random=plugin.makeid();
            $wapper_more_options.attr('wapper_more_options_random',random);
            var append='.wapper_more_options[wapper_more_options_random="'+random+'"]';
            //get value view_wapper_more_options
            $view_wapper_more_options=$more_options.children('.view_wapper_more_options');

            var $dd_item=$more_options.closest('.dd-item');
            var data_dd_item=$dd_item.data();


            //setup data post
            var dataPost = {
                option: 'com_utility',
                view: 'fields',
                tmpl: 'ajax_json',
                field_config: field_config,
                append: append

            };
            dataPost=$.param( dataPost );
            //end setup data post

            if(typeof ajax_web_design !== 'undefined'){
                ajax_web_design.abort();
            }
            ajax_web_design = $.ajax({
                type: "POST",
                contentType: 'application/json',
                dataType: "json",
                cache: false,
                url: this_host + '/index.php?'+dataPost,
                data: JSON.stringify(data_dd_item),
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
                    Joomla.sethtmlfortag1(response);
                    $element.find('.view_wapper_more_options').removeClass('hide');
                    $element.find('.save_item_nestable').addClass('hide');
                    $element.find('.edit_item_nestable').removeClass('hide');
                    $more_options.find('.save_item_nestable').removeClass('hide');
                    $more_options.find('.edit_item_nestable').addClass('hide');


                    $element.find('.save_and_close_item_nestable').addClass('hide');
                    $more_options.find('.save_and_close_item_nestable').removeClass('hide');


                    $element.find('.cancel_item_nestable').addClass('hide');
                    $more_options.find('.cancel_item_nestable').removeClass('hide');


                    $view_wapper_more_options.addClass('hide');


                }
            });


        }
        plugin.save_item_nestable=function(self,close){
            $more_options=self.closest('.more_options');

            $wapper_more_options=$more_options.children('.wapper_more_options');
            $wapper_more_options.find(' :input').each(function(){
                name=$(this).attr('name');
                plugin.update_data_column($(this),name);
            });
            if(close)
            {
                $element.find('.save_and_close_item_nestable').addClass('hide');
                $element.find('.cancel_item_nestable').addClass('hide');
                $element.find('.save_item_nestable').addClass('hide');

                $element.find('.edit_item_nestable').removeClass('hide');
                $element.find('.wapper_more_options').empty();
                $view_wapper_more_options=$more_options.find('.view_wapper_more_options');
                $view_wapper_more_options.removeClass('hide');

                var field_config=$element.find(plugin.settings.element_ouput).val();
                field_config=cassandraMAP.parse(field_config);
                $table_list_field=$view_wapper_more_options.find('table.list_field');
                $table_list_field.empty();
                var $dd_item=$more_options.closest('.dd-item');
                var data_dd_item=$dd_item.data();
                $.each(field_config,function(index,field){
                    var $tr=$('<tr></tr>');
                    var $td=$('<td>'+field.label+'</td>');
                    $td.appendTo($tr);
                    value=data_dd_item[field.name];
                    if(typeof value=='undefined')
                    {
                        value='';
                    }
                    var $td=$('<td>'+value+'</td>');
                    $td.appendTo($tr);
                    $tr.appendTo($table_list_field);

                });
                console.log(field_config);
            }


        }
        plugin.cancel_item_nestable=function(self){
            $more_options=self.closest('.more_options');

            $element.find('.save_and_close_item_nestable').addClass('hide');
            $element.find('.cancel_item_nestable').addClass('hide');
            $element.find('.save_item_nestable').addClass('hide');
            $more_options.find('.view_wapper_more_options').removeClass('hide');
            $element.find('.edit_item_nestable').removeClass('hide');
            $element.find('.wapper_more_options').empty();


        }
        plugin.add_node=function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);

            li_clone.insertAfter(li);
            li_clone.find('.select2-container.table_name').remove();
            li_clone.find('input.table_name').removeClass('select2-offscreen').removeData();


            li_clone.find('.select2-container.column_name').remove();
            li_clone.find('input.column_name').removeClass('select2-offscreen').removeData();
            //clear data post name
            li_clone.find('.select2-container.post_name').remove();
            li_clone.find('input.post_name').removeClass('select2-offscreen').removeData();


            li_clone.removeData();
            li_clone.find(".table_name").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".post_name").val('');
            li_clone.find("input.table_name").select2(plugin.table_name_option);
            li_clone.find("input.column_name").select2(plugin.column_name_option);
            li_clone.find("input.post_name").select2(plugin.post_name_option).select2("data",plugin.data_post,true);
            plugin.update_nestable();
        },
        plugin.add_sub_node=function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            ol= self.find(' > ol');
            if(ol.length)
            {
                li_clone.insertAfter(ol);
            }else{
                ol=$('<ol class="dd-list"></ol>');
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

            li_clone.removeData();
            li_clone.find(".table_name").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".post_name").val('');
            level=li_clone.attr('data-level');
            level++;
            li_clone.attr('data-level',level);
            primary_key=li_clone.find('input[type="radio"].primary-key');
            primary_key.attr('name','primary_key_'+level);
            li_clone.find("input.table_name").select2(plugin.table_name_option);
            li_clone.find("input.column_name").select2(plugin.column_name_option);

            //set select 2 post name
            li_clone.find("input.post_name").select2(plugin.post_name_option);
            plugin.update_nestable();

        },
        plugin.update_data_column=function(self,key,type_input) {
            self = $(self);
            self_value = self.val();
            if(typeof type_input!=='undefined'&&type_input=='checkbox')
            {
                show = self.is(':checked');
                self_value= show?1:0;
            }else if(typeof type_input!=='undefined'&&type_input=='radio'){
                self_value = self.val();
            }
            dd_item = self.closest('.dd-item');
            dd_item.data(key, self_value);

            var id = dd_item.attr('data-id');
            if (key == 'name' || key == 'title') {
                var name = dd_item.data('name');
                var title = dd_item.data('title');
                dd_item.find('.key_name:first').html(title + " ( " + name + " ) ");
            }
            plugin.update_nestable();
        },
        plugin.primary_key_update_value=function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').val(0);
            self.val(1);
        },
        plugin.call_on_change=function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').each(function(){
                plugin.update_data_column(this,'primary_key','radio');
            });
        },
        plugin.set_auto_complete=function(){
            $(".table_name").select2(plugin.table_name_option);
            $(".column_name").select2(plugin.column_name_option);



            $(".post_name").select2(plugin.post_name_option);

        },
        plugin.update_nestable=function(){
            plugin.updateOutput($('#config_update1').data('output', $('#config_update1-output')));
        },
        plugin.remove_item_nestable=function(self){
            self=$(self);
            dd_item=self.closest('.dd-item');
            if($('.dd-item').length>1)
                dd_item.remove();
            plugin.update_nestable();
        },
        plugin.get_list_field_table=function(self){
            table=self.data('table');
            li_table=self.closest('li.table');
            if(typeof ajax_render_field_table !== 'undefined'){
                ajax_render_field_table.abort();
            }
            ajax_render_field_table=$.ajax({
                type: "GET",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'table.ajax_render_field_table',
                        table:table

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    // $('.loading').popup();
                },
                success: function (response) {
                    $.each(response, function( index, value ) {
                        field_name=index;
                        index=table+'.'+index;
                        dd_item=$('.dd-item:last').clone();
                        dd_item.data('id',index);
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
                        handleClass:'dd-handle-move',
                        setup_depth:false,
                        dragStop:function(e,el,dragRootEl){
                            if(!dragRootEl.hasClass('list-field')) {
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
        plugin.render_table_fields=function(uiDraggable,droppable){
            table=uiDraggable.data('table');
            if(typeof ajaxRederTable !== 'undefined'){
                ajaxRederTable.abort();
            }
            ajaxRederTable=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {
                    dataPost = {
                        option: 'com_phpmyadmin',
                        task: 'table.aJaxInsertTable',
                        table:table

                    };
                    return dataPost;
                })(),
                beforeSend: function () {
                    // $('.loading').popup();
                },
                success: function (response) {
                    response=$(response);
                    droppable.append(response);


                }
            });
        },
        plugin.render_table_field=function(uiDraggable,droppable){
            field=uiDraggable.attr('data-field');
            field= field.split(".");
            table_name=field[0];
            field_name=field[1];
            dd_item=$('.dd-item:last').clone();
            dd_item.data('id',table_name+'.'+field_name);
            dd_item.attr('data-id',table_name+'.'+field_name);
            dd_item.find('input.column_name').val(field_name);
            dd_item.find('span.dd_column_name').html(field_name);
            if(droppable.find('.dd-list').length>1)
            {
                droppable.find('> .dd-list').append(dd_item);
            }else{
                dd_list=$('<ol class="dd-list dd-list-droppable"></ol>');
                dd_list.append(dd_item);
                droppable.append(dd_list);
                droppable.find('.dd-empty').remove();
            }
            //plugin.update_nestable();
            dd_item.droppable(plugin.option_droppable);
        },
        plugin.updateOutput=function(e){
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (typeof output == "undefined")
                return;
            if (window.JSON) {
                value=list.nestable('serialize');
                output.val(cassandraMAP.stringify(value));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        },
        plugin.update_data_type=function(){
            self = $(self);
            data_type = self.val();
            dd_item = self.closest('.dd-item');
            dd_item.data('type', data_type);
            plugin.updateOutput($('#config_update1').data('output', $('#config_update1-output')));
            plugin.updateOutput($('#config_update2').data('output', $('#config_update2-output')));
        },
        plugin.update_data_editable=function(){

        },
        plugin.filter_table=function(self){
            text=$(self).val();
            $('.config-upate-table li').each(function(){
                title=$(this).text();
                title=title.toLowerCase();
                if(title.indexOf(text) != -1){
                    $(this).show();
                }
                else
                {
                    $(this).hide();
                }
            });
        }




        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_createitem = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_createitem')) {
                var plugin = new $.field_createitem(this, options);
                $(this).data('field_createitem', plugin);

            }

        });

    }

})(jQuery);

