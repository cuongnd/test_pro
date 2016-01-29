jQuery(document).ready(function($){
    view_config={
        setting:{
        },
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
        field_name_option:{
            tags: {},
            maximumSelectionSize: 1
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
            handleClass:'dd-handle',
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
            $('.add_node').click(function add_node_click(){
                view_config.add_node($(this));
            });
            $('.add_sub_node').click(function add_sub_node_click(){
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
            view_config.init_append_grid();
            view_config.update_nestable();

           $('.copy-property-this-element').click(function(){
                var ui_element=$('select[name="ui_element"]').val();
               if(ui_element=="")
               {
                   alert("please select element first");
                   return false;
               }
               if (confirm('Are you sure you copy properties this element ?')) {
                   var  control_id=$('#field_block-output').attr('control-id');
                   ajax_web_design=$.ajax({
                       type: "POST",
                       dataType: "json",
                       cache: false,
                       url: this_host+'/index.php',
                       data: (function () {

                           var dataPost = {
                               option: 'com_utility',
                               task: 'block.ajax_copy_params_property_element',
                               ui_element: ui_element,
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
                           }



                       }
                   });
               } else {
                   return;
               }




           });


        },
        init_append_grid:function(){
            $('.tbl_append_grid').each(function(){
                self=$(this);
                var id=self.attr('id');
                var config_params=self.attr('data-config_params');
                config_params=base64.decode(config_params);
                config_params= $.parseJSON(config_params);
                view_config.append_grid_option.initData=config_params;
                $('#'+id).appendGrid( view_config.append_grid_option);

            });

            $('.tbl_append_grid_config_property').each(function(){
                self=$(this);
                var id=self.attr('id');
                var config_property=self.attr('data-config_property');
                config_property=base64.decode(config_property);
                config_property= $.parseJSON(config_property);
                view_config.append_grid_option_config_property.initData=config_property;
                $('#'+id).appendGrid( view_config.append_grid_option_config_property);

            });

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
        update_data_grid:function(){
            $('.tbl_append_grid').each(function(){
                self=$(this);
                var id=self.attr('id');
                var data=$('#'+id).appendGrid('getAllValue');
                data=JSON.stringify(data);
                data= base64.encode(data);
                dd_item = self.closest('.dd-item');
                dd_item.data('config_params', data);
                view_config.update_nestable();
            });

            $('.tbl_append_grid_config_property').each(function(){
                self=$(this);
                var id=self.attr('id');
                var data=$('#'+id).appendGrid('getAllValue');
                data=JSON.stringify(data);
                data= base64.encode(data);
                dd_item = self.closest('.dd-item');
                dd_item.data('config_property', data);
                view_config.update_nestable();
            });
        },
        save_fields:function(close){

            view_config.update_data_grid();
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
            li_clone.find('.dd-list').remove();
            li_clone.find('button[data-action="collapse"]').remove();
            li_clone.find('button[data-action="expand"]').remove();
            li_clone.data('id',0);
            li_clone.data('parent_id',0);
            li_clone.data('title','');
            li_clone.data('alias','');
            li_clone.data('icon','');
            li_clone.insertAfter(li);


            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();
            li_clone.find(".icon").select2(view_config.icon_option);

            li_clone.find('.select2-container.select_field_name').remove();
            li_clone.find('input.select_field_name').removeClass('select2-offscreen').removeData();
            li_clone.find(".select_field_name").select2(view_config.field_name_option);

            li_clone.find('.select2-container.field_type').remove();
            li_clone.find('input.field_type').removeClass('select2-offscreen').removeData();
            li_clone.find(".field_type").select2();

            view_config.reset_append_grid_option(li_clone);
            view_config.reset_append_grid_config_property(li_clone);


            li_clone.find('.add_node').click(function add_node_click(){
                view_config.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click(){
                view_config.add_sub_node($(this));
            });
            li_clone.removeData();
            view_config.update_nestable();
            menu_type_id=li.attr('data-menu_type_id');
            id=li.attr('data-id');
        },
        reset_append_grid_option:function(element){
            element.find('.config_params').empty();
            var id=  view_config.makeid();
            var table_grid=$('<table class="tbl_append_grid" data-config_params="" id="tblAppendGrid_'+id+'"></table>');
            element.find('.config_params').append($(table_grid));
            view_config.append_grid_option.initData=[];
            element.find('.tbl_append_grid').appendGrid(view_config.append_grid_option);

        },
        reset_append_grid_config_property:function(element){

            element.find('.config_property').empty();
            var id=  view_config.makeid();
            var table_grid=$('<table class="tbl_append_grid_config_property" data-config_property="" id="tblAppendGrid_config_property_'+id+'"></table>');
            element.find('.config_property').append($(table_grid));
            view_config.append_grid_option_config_property.initData=[];
            element.find('.tbl_append_grid_config_property').appendGrid(view_config.append_grid_option_config_property);

        },
        append_grid_option:{
            caption: 'Option params',
            initRows: 0,
            columns: [
                { name: 'param_key', display: 'Key', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '160px' } },
                { name: 'param_value', display: 'Value', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '100px'} },
            ],
            initData:[] ,
            rowDragging: true,
            hideButtons: { moveUp: true, moveDown: true }
        },
        append_grid_option_config_property:{
            caption: 'Option config property',
            initRows: 0,
            columns: [
                { name: 'property_key', display: 'Key', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '160px' } },
                { name: 'property_value', display: 'Value', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '200px'} },
            ],
            initData:[] ,
            rowDragging: true,
            hideButtons: { moveUp: true, moveDown: true }
        },
        makeid:function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        },
        add_sub_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.find('.dd-list').remove();
            li_clone.data('id',0);
            li_clone.data('parent_id',0);
            li_clone.data('title','');
            li_clone.data('alias','');
            li_clone.data('icon','');
            li_clone.find('button[data-action="collapse"]').remove();
            li_clone.find('button[data-action="expand"]').remove();
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

            li_clone.find('.select2-container.icon').remove();
            li_clone.find('input.icon').removeClass('select2-offscreen').removeData();
            li_clone.find("input.icon").select2(view_config.icon_option);

            li_clone.find('.select2-container.select_field_name').remove();
            li_clone.find('input.select_field_name').removeClass('select2-offscreen').removeData();
            li_clone.find("input.select_field_name").select2(view_config.field_name_option);


            li_clone.find('.select2-container.field_type').remove();
            li_clone.find('input.field_type').removeClass('select2-offscreen').removeData();
            li_clone.find("input.field_type").select2();

            view_config.reset_append_grid_option(li_clone);
            view_config.reset_append_grid_config_property(li_clone);


            li_clone.find('.add_node').click(function add_node_click(){
                view_config.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click(){
                view_config.add_sub_node($(this));
            });

            view_config.update_nestable();
            id=li.attr('data-id');



        },
        update_atrribute_param_config:function(self){
            var self = $(self);
            var  self_value = self.val();
            self=self.select2('data');
            var path=$(self.element).data('path');
            var more_options=$(self.element).closest('.more_options');

            var append_grid_id=more_options.find('.tbl_append_grid_config_property').attr('id');
            ajax_web_design=$.ajax({
                type: "POST",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
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

                    var list_params=[];
                    $.each(response, function( index, value ) {
                        var item={};
                        item.property_key=index;
                        item.property_value=value;
                        list_params.push(item);
                    });
                    $('#'+append_grid_id).appendGrid('load',list_params);


                }
            });
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
            if(key=='name'||key=='label')
            {
                var name=dd_item.data('name');
                var label=dd_item.data('label');
                dd_item.find('.key_name:first').html(label+" ( "+name+" ) ");
            }
            if(key=='name')
            {
                var name=dd_item.data('name');
                dd_item.find('span.path_name:first').html(name);
            }


        },
        call_on_change:function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').each(function(){
                view_config.update_data_column(this,'home','radio');
            });
        },
        set_auto_complete:function(){
            $(".select_field_name").select2(view_config.field_name_option);
            $(".icon_menu_item").select2(view_config.icon_option);
            $(".column_access").select2(view_config.access_option);
            $(".field_type").select2();
        },
        update_nestable:function(){
            view_config.updateOutput($('#field_block').data('output', $('#field_block-output')));


        },
        remove_item_nestable:function(self) {
            self=$(self);
            dd_item=self.closest('.dd-item');
            dd_list=self.closest('.dd-list');
            if(dd_list.find('>.dd-item').length==1)
            {

                dd_item_parent=dd_list.parent('.dd-item');
                dd_item_parent.find('button[data-action="collapse"]').remove();
                dd_item_parent.find('button[data-action="expand"]').remove();
                dd_list.remove();
            }
            else{
                dd_item.remove();
            }
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