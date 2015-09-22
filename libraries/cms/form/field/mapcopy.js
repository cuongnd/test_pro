jQuery(document).ready(function($){
    config_update={
        option_draggable:{
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
        },
        table_name_option:{
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
        column_name_option:{
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
        foreign_key_option:{
            ajax: {
                url: this_host+"/index.php?option=com_phpmyadmin&task=tables.ajax_get_list_flied_table",
                dataType: 'json',
                delay: 250,
                data: function (term, page) {
                    dd_list=$(this).closest('ol.dd-list');
                    dd_item=dd_list.closest('li.dd-item');
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
        post_name_option:{
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
        option_droppable:{
            accept: ".configupdate-item-table,.configupdate-item-field",
            greedy: true,
            drop: function(ev,ui){
                uiDraggable=$(ui.draggable);
                droppable=$(this);
                if(uiDraggable.hasClass('configupdate-item-table'))
                {
                    config_update.render_table_fields(uiDraggable,droppable);
                }else if(uiDraggable.hasClass('configupdate-item-field')){
                    config_update.render_table_field(uiDraggable,droppable);
                }
            }
        },
        option_nestable:{
            group: 1,
            maxDepth: 10,
            handleClass:'dd-handle-move'
        },
        init_config_nestable:function(){
            $('#config_update1').nestable(config_update.option_nestable)
                .on('change', config_update.updateOutput);




        },
        init_config_update:function(){


            $('.add_node').click(function add_node_click(){
                config_update.add_node($(this));
            });
            $('.add_sub_node').click(function add_sub_node_click(){
                config_update.add_sub_node($(this));
            });




            config_update.set_auto_complete();
            // activate Nestable for list 1
            config_update.init_config_nestable();
            $( ".configupdate-item-table" ).draggable(config_update.option_draggable);
            config_update.update_nestable();
            $('.dd-list-droppable').droppable(config_update.option_droppable);
            $('.configupdate-item-table a.plus').click(function(){
                config_update.get_list_field_table($(this));
            });


        },
        add_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.find('.dd-list').remove();
            li_clone.find('button[data-action="collapse"]').remove();
            li_clone.find('button[data-action="expand"]').remove();
            li_clone.insertAfter(li);
            li_clone.find('.select2-container.table_name').remove();
            li_clone.find('input.table_name').removeClass('select2-offscreen').removeData();


            li_clone.find('.select2-container.column_name').remove();
            li_clone.find('input.column_name').removeClass('select2-offscreen').removeData();
             //clear data post name
            li_clone.find('.select2-container.post_name').remove();
            li_clone.find('input.post_name').removeClass('select2-offscreen').removeData();
            //cleare foreign_key
            li_clone.find('.select2-container.foreign_key').remove();
            li_clone.find('input.foreign_key').removeClass('select2-offscreen').removeData();

            li_clone.find('.add_node').click(function add_node_click(){
                config_update.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click(){
                config_update.add_sub_node($(this));
            });
            li_clone.removeData();
            li_clone.find(".table_name").val('');
            li_clone.find(".foreign_key").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".post_name").val('');
            li_clone.find("input.table_name").select2(config_update.table_name_option);
            li_clone.find("input.column_name").select2(config_update.column_name_option);
            li_clone.find("input.foreign_key").select2(config_update.foreign_key_option);
            li_clone.find("input.post_name").select2(config_update.post_name_option).select2("data",config_update.data_post,true);
            config_update.update_nestable();
        },
        add_sub_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.find('.dd-list').remove();

            ol= li.children('.dd-list');
            if(ol.length>=1)
            {
                ol.append(li_clone);
            }else{
                ol=$('<ol class="dd-list"></ol>');
                ol.append(li_clone);
                ol.appendTo(li);
                li.prepend('<button type="button" data-action="collapse">Collapse</button>' +
                '<button type="button" data-action="expand" style="display: none;">Expand</button>').fadeIn('slow');
            }

            li_clone.find('.select2-container.table_name').remove();
            li_clone.find('input.table_name').removeClass('select2-offscreen').removeData();



            li_clone.find('.select2-container.column_name').remove();
            li_clone.find('input.column_name').removeClass('select2-offscreen').removeData();
            //cleare foreign_key
            li_clone.find('.select2-container.foreign_key').remove();
            li_clone.find('input.foreign_key').removeClass('select2-offscreen').removeData();

            //clear post name
            li_clone.find('.select2-container.post_name').remove();
            li_clone.find('input.post_name').removeClass('select2-offscreen').removeData();

            li_clone.removeData();
            li_clone.find(".table_name").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".foreign_key").val('');
            li_clone.find(".post_name").val('');
            level=li_clone.attr('data-level');
            level++;
            li_clone.attr('data-level',level);
            primary_key=li_clone.find('input[type="radio"].primary-key');
            primary_key.attr('name','primary_key_'+level);
            li_clone.find("input.table_name").select2(config_update.table_name_option);
            li_clone.find("input.column_name").select2(config_update.column_name_option);
            li_clone.find("input.foreign_key").select2(config_update.foreign_key_option);

            li_clone.find('.add_node').click(function add_node_click(){
                config_update.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click(){
                config_update.add_sub_node($(this));
            });
             //set select 2 post name
            li_clone.find("input.post_name").select2(config_update.post_name_option);
            config_update.update_nestable();

        },
        update_data_column:function(self,key,type_input) {
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
            config_update.update_nestable();
        },
        primary_key_update_value:function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').val(0);
            self.val(1);
        },
        call_on_change:function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').each(function(){
                config_update.update_data_column(this,'primary_key','radio');
            });
        },
        set_auto_complete:function(){
            $(".table_name").select2(config_update.table_name_option);
            $(".column_name").select2(config_update.column_name_option);
            $(".foreign_key").select2(config_update.foreign_key_option);



            $(".post_name").select2(config_update.post_name_option);

        },
        update_nestable:function(){
            config_update.updateOutput($('#config_update1').data('output', $('#config_update1-output')));
        },
        remove_item_nestable:function(self){
            self=$(self);
            dd_item=self.closest('.dd-item');
            if($('.dd-item').length>1)
                dd_item.remove();
            config_update.update_nestable();
        },
        get_list_field_table:function(self){
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
        render_table_fields:function(uiDraggable,droppable){
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
        render_table_field:function(uiDraggable,droppable){
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
            //config_update.update_nestable();
            dd_item.droppable(config_update.option_droppable);
        },
        updateOutput:function(e){
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
        update_data_type:function(){
            self = $(self);
            data_type = self.val();
            dd_item = self.closest('.dd-item');
            dd_item.data('type', data_type);
            config_update.updateOutput($('#config_update1').data('output', $('#config_update1-output')));
            config_update.updateOutput($('#config_update2').data('output', $('#config_update2-output')));
        },
        update_data_editable:function(){

        },
        filter_table:function(self){
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
    } ;



});