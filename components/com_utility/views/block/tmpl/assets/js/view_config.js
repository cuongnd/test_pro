jQuery(document).ready(function($){
    view_config={
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
        icon_option:{
            ajax: {
                url: this_host+"/index.php?option=com_menus&task=item.ajax_get_list_icon",
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
            formatResult: function (result, container, query, escapeMarkup){

                return '<span><i class="'+result.text+'"></i>'+result.text+'</span>';
            },

            formatSelection:function (data, container, escapeMarkup){

                return  '<span><i class="'+data.text+'"></i>'+data.text+'</span>';
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
        },
        access_option:{
            ajax: {
                url: this_host+"/index.php?option=com_users&task=user.ajax_get_list_group_user",
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
            formatResult: function (result, container, query, escapeMarkup){

                return '<span><i class="'+result.text+'"></i>'+result.text+'</span>';
            },

            formatSelection:function (data, container, escapeMarkup){

                return  '<span><i class="'+data.text+'"></i>'+data.text+'</span>';
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1
        },

        option_droppable:{
            accept: ".configupdate-item-table,.configupdate-item-field",
            greedy: true,
            drop: function(ev,ui){
                uiDraggable=$(ui.draggable);
                droppable=$(this);
                if(uiDraggable.hasClass('configupdate-item-table'))
                {
                    view_config.render_table_fields(uiDraggable,droppable);
                }else if(uiDraggable.hasClass('configupdate-item-field')){
                    view_config.render_table_field(uiDraggable,droppable);
                }
            }
        },
        option_nestable:{
            group: 1,
            maxDepth: 10,
            handleClass:'dd-handle-move',
            dragStop:function(e,el,dragEl){
                view_config.move_element(e,el,dragEl);
            }
        },
        move_element:function(e,el,dragEl){
            parent_menu=el.closest('.dd-list').closest('.dd-item');
            id=el.attr('data-id');
            parent_id=parent_menu.attr('data-id');
            menu_type_id=parent_menu.attr('data-menu_type_id');
            if(typeof parent_id=="undefined")
            {
                menu_root_id=el.closest('.a_menu_type').attr('data-menu_root_id');
                parent_id=menu_root_id;
            }
            if(typeof menu_type_id=="undefined")
            {
                menu_type_id=el.closest('.a_menu_type').attr('data-menu_type_id');
            }
            dd_list=el.closest('.dd-list');
            list_ordering={};
            dd_list.find('>li.dd-item').each(function(index){
                dd_item=$(this);
                list_ordering[index]=dd_item.attr('data-id');
            });
            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'item.ajax_update_item',
                        data:{
                            id:id,
                            parent_id:parent_id,
                            menu_type_id:menu_type_id

                        },
                        list_ordering:list_ordering

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
        init_config_nestable:function(){
            $('#field_block').nestable(view_config.option_nestable)
                .on('change', view_config.updateOutput);





        },
        init_view_config:function(){





            $(document).on('click','.add_node',function(){
                view_config.add_node($(this));
            });
            $(document).on('click','.add_sub_node',function(){
                view_config.add_sub_node($(this));
            });

            $(document).on('change','.menu_access_level',function(){
                var self=$(this);
                var access=self.val();
                var dd_item = self.closest('.dd-item');
                var id=dd_item.attr('data-id');
                dd_item.attr('data-access', access);
                dd_item.data('access', access);
                var list_keys_values={};
                list_keys_values.access=access;
                view_config.update_menu_item(id,list_keys_values);

            });

            view_config.set_auto_complete();
            // activate Nestable for list 1
            view_config.init_config_nestable();
            view_config.update_nestable();


        },
        show_more_options:function(self){
            self=$(self);
             if(self.is(":checked"))
             {
                 $('.dd-item .more_options').show();

             }
             else{
                 $('.dd-item .more_options').hide();
             }

        },
        save_fields:function(close){
            var fields=$('#field_block-output').val();
            control_id=$('#field_block-output').attr('control-id');
            fields= base64.encode(fields);
            ajax_web_design=$.ajax({
                type: "POST",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'block.ajax_save_field_params',
                        fields: fields,
                        control_id: control_id
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
                    if(response.e==1)
                    {
                        alert(response.r);
                    }else
                    {
                        alert(response.r);
                        if(close==1)
                        {
                            $('.panel.element.element-config').remove();
                        }
                    }



                }
            });
        },
        save_and_close:function()
        {
            view_config.save_fields(1);

        },
        save:function()
        {
            view_config.save_fields(0);
        },
        cancel:function()
        {
            $('.panel.element.element-config').remove();
        },
        add_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.data('id',0);
            li_clone.data('parent_id',0);
            li_clone.data('title','');
            li_clone.data('alias','');
            li_clone.data('icon','');
            li_clone.insertAfter(li);
            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();
            li_clone.removeData();
            li_clone.find(".icon").select2(view_config.icon_option);
            view_config.update_nestable();
            menu_type_id=li.attr('data-menu_type_id');
            id=li.attr('data-id');
        },
        add_sub_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.data('id',0);
            li_clone.data('parent_id',0);
            li_clone.data('title','');
            li_clone.data('alias','');
            li_clone.data('icon','');
            ol= self.find(' > ol');
            if(ol.length)
            {
                li_clone.insertAfter(ol);
            }else{
                ol=$('<ol class="dd-list"></ol>');
                ol.append(li_clone);
                ol.appendTo(li);
            }

            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();

            li_clone.find("input.icon").select2(view_config.icon_option);
            view_config.update_nestable();
            id=li.attr('data-id');
            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_menus',
                        task: 'item.ajax_add_sub_item_menu',
                        id:id

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
        update_data_column:function(self,key,type_input) {
            self = $(self);
            self_value = self.val();
            if(typeof type_input!=='undefined'&&type_input=='checkbox')
            {
                self_value = self.val();
            }else if(typeof type_input!=='undefined'&&type_input=='radio'){
                self_value = self.val();
            }


            dd_item = self.closest('.dd-item');
            dd_item.data(key, self_value);
            view_config.update_nestable();
            dd_item=self.closest('.dd-item');
            id=dd_item.attr('data-id');
            list_keys_values={};
            list_keys_values[key]=self_value;

        },
        call_on_change:function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').each(function(){
                view_config.update_data_column(this,'home','radio');
            });
        },
        set_auto_complete:function(){
            $(".icon_menu_item").select2(view_config.icon_option);
            $(".column_access").select2(view_config.access_option);
        },
        update_nestable:function(){
            view_config.updateOutput($('#field_block').data('output', $('#field_block-output')));


        },
        remove_item_nestable:function(self) {
            self = $(self);
            dd_item = self.closest('.dd-item');
            if ($('.dd-item').length > 1)
                dd_item.remove();
            view_config.update_nestable();
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
        }

    } ;



});