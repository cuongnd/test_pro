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
            view_config.init_append_grid();
            view_config.update_nestable();


        },
        init_append_grid:function(){
            var columns= [
                { name: 'Album', display: 'Album', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '160px' }, onChange: function (evt, rowIndex) { alert('You have changed the value of `Album` at row ' + rowIndex); } },
                { name: 'Artist', display: 'Artist', type: 'text', ctrlAttr: { maxlength: 100 }, ctrlCss: { width: '100px'} },
                { name: 'Year', display: 'Year', type: 'text', ctrlAttr: { maxlength: 4 }, ctrlCss: { width: '40px'} },
                { name: 'Origin', display: 'Origin', type: 'select', ctrlOptions: { 0: '{Choose}', 1: 'Hong Kong', 2: 'Taiwan', 3: 'Japan', 4: 'Korea', 5: 'US', 6: 'Others'} },
                { name: 'Poster', display: 'With Poster?', type: 'checkbox', onClick: function (evt, rowIndex) { alert('You have clicked on the `With Poster?` at row ' + rowIndex); } },
                { name: 'Price', display: 'Price', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '50px', 'text-align': 'right' }, value: 0 }
            ];
            $('.tbl_append_grid').each(function(){
                self=$(this);
                var id=self.attr('id');
                var init_data=[
                    { 'Album': 'Dearest', 'Artist': 'Theresa Fu', 'Year': '2009', 'Origin': 1, 'Poster': true, 'Price': 168.9 },
                    { 'Album': 'To be Free', 'Artist': 'Arashi', 'Year': '2010', 'Origin': 3, 'Poster': true, 'Price': 152.6 },
                    { 'Album': 'Count On Me', 'Artist': 'Show Luo', 'Year': '2012', 'Origin': 2, 'Poster': false, 'Price': 306.8 },
                    { 'Album': 'Wonder Party', 'Artist': 'Wonder Girls', 'Year': '2012', 'Origin': 4, 'Poster': true, 'Price': 108.6 },
                    { 'Album': 'Reflection', 'Artist': 'Kelly Chen', 'Year': '2013', 'Origin': 1, 'Poster': false, 'Price': 138.2 }
                ];
                $('#'+id).appendGrid({
                    caption: 'My CD Collections',
                    initRows: 1,
                    columns: columns,
                    initData:init_data ,
                    rowDragging: true,
                    afterRowDragged: function (caller, rowIndex) {
                        var msg = 'You have dragged a row. The new row index is ' + rowIndex + '!';
                        $('#spnMessage').text(msg).css('background-color', '#ffff66').animate({
                            backgroundColor: '#ffffff'
                        }, 800);
                    },
                    hideButtons: { moveUp: true, moveDown: true }
                });

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


            li_clone.removeData();

            view_config.update_nestable();
            menu_type_id=li.attr('data-menu_type_id');
            id=li.attr('data-id');
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


            view_config.update_nestable();
            id=li.attr('data-id');



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