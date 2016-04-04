jQuery(document).ready(function($){
    menu_ajax_loader={
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
                    menu_ajax_loader.render_table_fields(uiDraggable,droppable);
                }else if(uiDraggable.hasClass('configupdate-item-field')){
                    menu_ajax_loader.render_table_field(uiDraggable,droppable);
                }
            }
        },
        option_nestable:{
            group: 1,
            maxDepth: 10,
            handleClass:'dd-handle',
            dragStop:function(e,el,dragEl){
                menu_ajax_loader.move_element(e,el,dragEl);
            }
        },
        expand_item_nestable:function(self) {
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
                        enable_load_component:1,
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

                    if(response.e==1)
                    {
                        alert(response.m);
                    }else
                    {
                        alert(response.m);
                    }


                }
            });

        },
        init_config_nestable:function(){
            $('.a_menu_type').each(function(){
                id_menu_type=$(this).attr('id');
                $('#'+id_menu_type).nestable(menu_ajax_loader.option_nestable)
                    .on('change', menu_ajax_loader.updateOutput);
            });





        },
        create_new_menu_item: function (self) {
            var menu_type_id=self.closest('.menu_type_item').data('menuTypeId');
            var ajax_web_design=$.ajax({

                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    var dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'item.ajax_create_new_menu_item',
                        menu_type_id:menu_type_id

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
                        alert(response.m);
                    }else
                    {

                        alert(response.m);
                        var menu_clone=response.r;
                        location.reload();
/*
                        li_clone.data('id',menu_clone.id);
                        li_clone.data('parent_id',menu_clone.parent_id);
                        li_clone.data('title',menu_clone.title);
                        li_clone.data('alias',menu_clone.alias);
                        li_clone.find('.key_name:first').html(menu_clone.title + " ( " +menu_clone.id+','+ menu_clone.alias + " ) ");
*/
                        //do some thing
                    }



                }
            });

            console.log('dfgdfgdfgdfgfdgdf');
        },
        create_new_menu_type: function (self) {
            var menu_type_name=$('input[name="menu_type_name"]').val();
            if(menu_type_name.trim()=='')
            {
                alert('please input menu type');
                return false;
            }
            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'menu.ajax_create_new_menu_type',
                        menu_type_name:menu_type_name
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
                        alert(response.m);
                    }else
                    {
                        alert(response.m);
                        location.reload();
                    }




                }
            });

        },
        init_menu_ajax_loader:function(){





            $(document).on('click','.add_node',function(){
                menu_ajax_loader.add_node($(this));
            });
            $(document).on('click','.create_menu_type',function(){
                menu_ajax_loader.create_new_menu_type($(this));
            });
            $(document).on('click','.add_sub_node',function(){
                menu_ajax_loader.add_sub_node($(this));
            });
            $(document).on('click','button[name="create_new_menu_item"]',function(){
                menu_ajax_loader.create_new_menu_item($(this));
            });
            $(document).on('click','.rebuild_root_menu',function(){
                var menu_type_id=$(this).data('menu_type_id');
                var menu_item_id=$(this).data('menu_item_id');

                ajax_web_design=$.ajax({
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    url: this_host+'/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_menus',
                            task: 'menus.ajax_rebuild_root_menu',
                            menu_type_id:menu_type_id,
                            menu_item_id:menu_item_id
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
                            alert(response.m);
                        }else
                        {
                            alert(response.m);
                            location.reload();
                        }




                    }
                });


            });

            $(document).on('change','.menu_access_level',function(){
                var self=$(this);
                var access=self.val();
                var dd_item = self.closest('.dd-item');
                var id=dd_item.data('id');
                dd_item.attr('data-access', access);
                dd_item.data('access', access);
                var list_keys_values={};
                list_keys_values.access=access;
                menu_ajax_loader.update_menu_item(id,list_keys_values);

            });

            menu_ajax_loader.set_auto_complete();
            // activate Nestable for list 1
            menu_ajax_loader.init_config_nestable();
            menu_ajax_loader.update_nestable();


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
        save_fields:function(close)
        {
            list_menu_type={};
            $('input.menu_input').each(function(){
                self=$(this);
                list_menu_type[self.attr('data-menu-type-id')]= base64.encode(self.val());
            });
            ajax_web_design=$.ajax({
                type: "POST",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'menus.ajax_save_menu',
                        list_menu_type:list_menu_type
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
                            $('.panel.menus-config').remove();
                        }
                    }




                }
            });


        },
        save_and_close:function(self)
        {
            menu_ajax_loader.save_fields(1);

        },
        save:function(self)
        {
            menu_ajax_loader.save_fields(0);
        },
        cancel:function(self)
        {
            $('.panel.menus-config').remove();
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
            li_clone.find(".icon").select2(menu_ajax_loader.icon_option);
            menu_ajax_loader.update_nestable();
            menu_type_id=li.attr('data-menu_type_id');
            id=li.attr('data-id');

            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'item.ajax_clone_item_menu',
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
                    if(response.e==1)
                    {
                        alert(response.m);
                    }else
                    {

                        alert(response.m);
                        var menu_clone=response.r;
                        li_clone.data('id',menu_clone.id);
                        li_clone.data('parent_id',menu_clone.parent_id);
                        li_clone.data('title',menu_clone.title);
                        li_clone.data('alias',menu_clone.alias);
                        li_clone.find('.key_name:first').html(menu_clone.title + " ( " +menu_clone.id+','+ menu_clone.alias + " ) ");
                        //do some thing
                    }



                }
            });

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

            li_clone.find("input.icon").select2(menu_ajax_loader.icon_option);
            menu_ajax_loader.update_nestable();
            id=li.attr('data-id');
            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
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
                    if(response.e==1)
                    {
                        alert(response.m);
                    }else
                    {

                        alert(response.m);
                        //do some thing
                    }



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

            if (key == 'title' || key == 'alias') {
                var alias = dd_item.data('alias');
                var title = dd_item.data('title');
                var id = dd_item.data('id');
                dd_item.find('.key_name:first').html(title + " ( " + id+','+alias + " ) ");
            }


            menu_ajax_loader.update_nestable();
            dd_item=self.closest('.dd-item');
            id=dd_item.data('id');
            list_keys_values={};
            list_keys_values[key]=self_value;
            menu_ajax_loader.update_menu_item(id,list_keys_values);

        },
        update_menu_item:function(id,list_keys_values)
        {

            ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'item.ajax_update_item',
                        data:{
                            id:id

                        }

                    };
                    $.each(list_keys_values, function( index, value ) {
                        dataPost.data[index]=value;
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
                    if(response.e==1)
                    {
                        alert(response.m);
                    }else{
                        alert('saved successfully');
                    }




                }
            });

        },
        home_update_value:function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').val(0);
            self.val(1);
            var dd_item=self.closest('.dd-item');
            id=dd_item.data('id');
            var ajax_web_design=$.ajax({
                type: "GET",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_menus',
                        task: 'item.ajax_update_home_page',
                        data:{
                            id:id

                        }

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
                        alert(response.m);
                    }else{
                        alert('saved successfully');
                    }




                }
            });



        },
        call_on_change:function(self){
            self = $(self);
            name=self.attr('name');

        },
        set_auto_complete:function(){
            $(".icon_menu_item").select2(menu_ajax_loader.icon_option);
            $(".column_access").select2(menu_ajax_loader.access_option);
        },
        update_nestable:function(){
            $('.a_menu_type').each(function render_nestable (){
                id_menu_type=$(this).attr('id');
                menu_ajax_loader.updateOutput($('#'+id_menu_type).data('output', $('#'+id_menu_type+'_output')));
            });


        },
        remove_item_nestable:function(self) {
            var self = $(self);
            var dd_item = self.closest('.dd-item');
            var id=dd_item.attr('data-id');
            if ($('.dd-item').length > 1)
            {
                if (confirm('Are you sure you want delete this menu item?')) {
                    ajax_web_design=$.ajax({
                        type: "GET",
                        dataType: "json",
                        cache: false,
                        url: this_host+'/index.php',
                        data: (function () {

                            dataPost = {
                                enable_load_component:1,
                                option: 'com_menus',
                                task: 'item.ajax_remove_item_menu',
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
                            if(response.e==1)
                            {
                                alert(response.m);
                            }else
                            {

                                alert(response.m);
                                dd_item.remove();
                                menu_ajax_loader.update_nestable();
                                //do some thing
                            }



                        }
                    });

                } else {
                    return;
                }




            }

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