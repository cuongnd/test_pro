jQuery(document).ready(function($){
    hidden_field_config={
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
                    hidden_field_config.render_table_fields(uiDraggable,droppable);
                }else if(uiDraggable.hasClass('configupdate-item-field')){
                    hidden_field_config.render_table_field(uiDraggable,droppable);
                }
            }
        },
        option_nestable:{
            group: 1,
            maxDepth: 10,
            handleClass:'dd-handle',
            dragStop:function(e,el,dragEl){
                //hidden_field_config.move_element(e,el,dragEl);
            }
        },

        init_config_nestable:function(){
            $('#field_block').nestable(hidden_field_config.option_nestable)
                .on('change', hidden_field_config.updateOutput);





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
        init_hidden_field_config:function(){
            $('.add_node').click(function add_node_click(){
                hidden_field_config.add_node($(this));
            });
            $('.add_sub_node').click(function add_sub_node_click(){
                hidden_field_config.add_sub_node($(this));
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
                hidden_field_config.update_menu_item(id,list_keys_values);

            });

            hidden_field_config.set_auto_complete();
            // activate Nestable for list 1
            hidden_field_config.init_config_nestable();
            hidden_field_config.init_append_grid();
            hidden_field_config.update_nestable();


        },
        init_append_grid:function(){
            $('.tbl_append_grid').each(function(){
                self=$(this);
                var id=self.attr('id');
                var config_params=self.attr('data-config_params');
                config_params=$.base64Decode(config_params);
                config_params= $.parseJSON(config_params);
                hidden_field_config.append_grid_option.initData=config_params;
                $('#'+id).appendGrid( hidden_field_config.append_grid_option);

            });

            $('.tbl_append_grid_config_property').each(function(){
                self=$(this);
                var id=self.attr('id');
                var config_property=self.attr('data-config_property');
                config_property=$.base64Decode(config_property);
                config_property= $.parseJSON(config_property);
                hidden_field_config.append_grid_option_config_property.initData=config_property;
                $('#'+id).appendGrid( hidden_field_config.append_grid_option_config_property);

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
                data= $.base64Encode(data);
                dd_item = self.closest('.dd-item');
                dd_item.data('config_params', data);
                hidden_field_config.update_nestable();
            });

            $('.tbl_append_grid_config_property').each(function(){
                self=$(this);
                var id=self.attr('id');
                var data=$('#'+id).appendGrid('getAllValue');
                data=JSON.stringify(data);
                data= $.base64Encode(data);
                dd_item = self.closest('.dd-item');
                dd_item.data('config_property', data);
                hidden_field_config.update_nestable();
            });
        },
        save_fields:function(close){

            hidden_field_config.update_data_grid();
            var fields=$('#field_block-output').val();
            var element_path=$('#field_block-output').data('element_path');
            fields= $.base64Encode(fields);
            ajax_web_design=$.ajax({
                type: "POST",
                dataType: "json",
                cache: false,
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        enable_load_component:1,
                        option: 'com_utility',
                        task: 'utility.ajax_save_control_component_field_params',
                        fields: fields,
                        element_path: element_path
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
                            $('.panel.extension-module-config').remove();
                        }
                    }



                }
            });
        },
        save_and_close:function()
        {
            hidden_field_config.save_fields(1);

        },
        save:function()
        {
            hidden_field_config.save_fields(0);
        },
        cancel:function()
        {
            $('.panel.extension-module-config').remove();
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
            li_clone.find(".icon").select2(hidden_field_config.icon_option);

            li_clone.find('.select2-container.select_field_name').remove();
            li_clone.find('input.select_field_name').removeClass('select2-offscreen').removeData();
            li_clone.find(".select_field_name").select2(hidden_field_config.field_name_option);

            li_clone.find('.select2-container.field_type').remove();
            li_clone.find('input.field_type').removeClass('select2-offscreen').removeData();
            li_clone.find(".field_type").select2();

            hidden_field_config.reset_append_grid_option(li_clone);
            hidden_field_config.reset_append_grid_config_property(li_clone);


            li_clone.find('.add_node').click(function add_node_click(){
                hidden_field_config.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click(){
                hidden_field_config.add_sub_node($(this));
            });
            li_clone.removeData();
            hidden_field_config.update_nestable();
            menu_type_id=li.attr('data-menu_type_id');
            id=li.attr('data-id');
        },
        reset_append_grid_option:function(element){
            element.find('.config_params').empty();
            var id=  hidden_field_config.makeid();
            var table_grid=$('<table class="tbl_append_grid" data-config_params="" id="tblAppendGrid_'+id+'"></table>');
            element.find('.config_params').append($(table_grid));
            hidden_field_config.append_grid_option.initData=[];
            element.find('.tbl_append_grid').appendGrid(hidden_field_config.append_grid_option);

        },
        reset_append_grid_config_property:function(element){

            element.find('.config_property').empty();
            var id=  hidden_field_config.makeid();
            var table_grid=$('<table class="tbl_append_grid_config_property" data-config_property="" id="tblAppendGrid_config_property_'+id+'"></table>');
            element.find('.config_property').append($(table_grid));
            hidden_field_config.append_grid_option_config_property.initData=[];
            element.find('.tbl_append_grid_config_property').appendGrid(hidden_field_config.append_grid_option_config_property);

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
            li_clone.find("input.icon").select2(hidden_field_config.icon_option);

            li_clone.find('.select2-container.select_field_name').remove();
            li_clone.find('input.select_field_name').removeClass('select2-offscreen').removeData();
            li_clone.find("input.select_field_name").select2(hidden_field_config.field_name_option);


            li_clone.find('.select2-container.field_type').remove();
            li_clone.find('input.field_type').removeClass('select2-offscreen').removeData();
            li_clone.find("input.field_type").select2();

            hidden_field_config.reset_append_grid_option(li_clone);
            hidden_field_config.reset_append_grid_config_property(li_clone);


            li_clone.find('.add_node').click(function add_node_click(){
                hidden_field_config.add_node($(this));
            });
            li_clone.find('.add_sub_node').click(function add_sub_node_click(){
                hidden_field_config.add_sub_node($(this));
            });

            hidden_field_config.update_nestable();
            id=li.attr('data-id');



        },
        update_data_column:function(self,key,type_input) {
            self = $(self);
            var self_value = self.val();
            if(key=='addfieldpath')
            {

                var select2=self.data('select2');
                var element=select2.data().element[0];
                self_value=$(element).data('path');

            }
            if(typeof type_input!=='undefined'&&type_input=='checkbox')
            {
                self_value = self.val();
            }else if(typeof type_input!=='undefined'&&type_input=='radio'){
                self_value = self.val();
            }


            dd_item = self.closest('.dd-item');
            dd_item.data(key, self_value);
            hidden_field_config.update_nestable();
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
                        enable_load_component:1,
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
        call_on_change:function(self){
            self = $(self);
            name=self.attr('name');
            $('input[type="radio"][name="'+name+'"]').each(function(){
                hidden_field_config.update_data_column(this,'home','radio');
            });
        },
        set_auto_complete:function(){
            $(".select_field_name").select2(hidden_field_config.field_name_option);
            $(".icon_menu_item").select2(hidden_field_config.icon_option);
            $(".column_access").select2(hidden_field_config.access_option);
            $(".field_type").select2();
        },
        update_nestable:function(){
            hidden_field_config.updateOutput($('#field_block').data('output', $('#field_block-output')));


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
            hidden_field_config.update_nestable();
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