jQuery(document).ready(function($){
    config_inputitem={
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
                    config_inputitem.render_table_fields(uiDraggable,droppable);
                }else if(uiDraggable.hasClass('configupdate-item-field')){
                    config_inputitem.render_table_field(uiDraggable,droppable);
                }
            }
        },
        option_nestable:{
            group: 1,
            maxDepth: 10,
            handleClass:'dd-handle-move'
        },
        init_config_nestable:function(){
            $('#config_inputitem').nestable(config_inputitem.option_nestable)
                .on('change', config_inputitem.updateOutput);




        },
        init_config_inputitem:function(){


            $(document).on('click','.add_node',function(){
                config_inputitem.add_node($(this));
            });
            $(document).on('click','.add_sub_node',function(){
                config_inputitem.add_sub_node($(this));
            });
            config_inputitem.set_auto_complete();
            // activate Nestable for list 1
            config_inputitem.init_config_nestable();
            $( ".configupdate-item-table" ).draggable(config_inputitem.option_draggable);
            config_inputitem.update_nestable();
            $('.dd-list-droppable').droppable(config_inputitem.option_droppable);
            $('.configupdate-item-table a.plus').click(function(){
                config_inputitem.get_list_field_table($(this));
            });
            config_inputitem.set_auto_complete_content_edit_able();

        },
        get_key_li_ol_root: function(li,list_key){
            if(li.length) {
                ol = li.closest('ol.dd-list');
                li_parent = ol.closest('.dd-item');

                var key = li_parent.find('span.key').html();
                if(typeof key!='undefined')
                {
                    list_key.push(key);
                }
                if (li_parent.length) {
                    config_inputitem.get_key_li_ol_root(li_parent,list_key);
                }
            }
        },
        get_list_var:function(list_key,var_default){
           if(list_key.length)
           {
               key=list_key.pop();
               var_default=var_default.key;
               config_inputitem.get_list_var(list_key,var_default);
           }else{
               return  var_default;
           }
        },
        set_auto_complete_content_edit_able:function(){
            $('.key').on('autocomplete:request', function(event, query, callback) {
                li=$(this).closest('.dd-item');
                var list_key=new Array();
                var var_default=[];
                config_inputitem.get_key_li_ol_root(li,list_key);
                list_key=list_key.reverse();
                var return_list_var=new Array();
                return_list_var=config_inputitem.get_list_var(list_key,var_default);
                suggestions=new Array();
                $.each( return_list_var, function( index, value ){

                    if (typeof value !== "function") {
                        suggestions.push(index);
                    }
                });
                query = query.toLowerCase();
                callback(suggestions.filter(function(country) {
                    return country.toLowerCase().indexOf(query) !== -1;
                }));
            });
            $('.value').on('autocomplete:request', function(event, query, callback) {
                var suggestions = config_inputitem.getSuggestionsValueArrayFor(query);
                callback(suggestions);
            });
            $('.key').on('autocomplete:select', function(event, selected) {
                console.log('selected item:', selected);
            });
            $('.value').on('autocomplete:select', function(event, selected) {
                console.log('selected item:', selected);
            });


        },
        getSuggestionsKeyArrayFor:function(query){

            return Array('key1','key2');
        },
        getSuggestionsValueArrayFor:function(query){
            return Array('value1','value2');
        },
        add_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.find('.dd-list').remove();
            li_clone.find('button[data-action="collapse"]').remove();
            li_clone.find('button[data-action="expand"]').remove();
            li_clone.insertAfter(li);
            li_clone.find('span[data-autocomplete=""]').each(function(){
                key= $(this).find('span.key');
                key.insertBefore($(this));
                key.attr('data-autocomplete-spy','');
                key.removeClass('contenteditable-block');
                value= $(this).find('span.value');
                value.insertBefore($(this));
                value.removeClass('contenteditable-block');
                value.attr('data-autocomplete-spy','');
                $(this).remove();
            });

            li_clone.find('.select2-container.table_name').remove();
            li_clone.find('input.table_name').removeClass('select2-offscreen').removeData();


            li_clone.find('.select2-container.column_name').remove();
            li_clone.find('input.column_name').removeClass('select2-offscreen').removeData();
             //clear data post name
            li_clone.find('.select2-container.post_name').remove();
            li_clone.find('input.post_name').removeClass('select2-offscreen').removeData();

            config_inputitem.over_wrire_data_id();
            li_clone.removeData();
            li_clone.find(".table_name").val('');
            li_clone.find(".column_name").val('');
            li_clone.find(".post_name").val('');
            li_clone.find("input.table_name").select2(config_inputitem.table_name_option);
            li_clone.find("input.column_name").select2(config_inputitem.column_name_option);
            li_clone.find("input.post_name").select2(config_inputitem.post_name_option).select2("data",config_inputitem.data_post,true);
            config_inputitem.set_auto_complete_content_edit_able();
            config_inputitem.update_nestable();
        },
        add_sub_node:function(self){
            li=self.closest('.dd-item');
            li_clone=li.clone(false);
            li_clone.find('.dd-list').remove();
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
            li_clone.find('span[data-autocomplete=""]').each(function(){
                key= $(this).find('span.key');
                key.insertBefore($(this));
                key.attr('data-autocomplete-spy','');
                key.removeClass('contenteditable-block');
                value= $(this).find('span.value');
                value.insertBefore($(this));
                value.removeClass('contenteditable-block');
                value.attr('data-autocomplete-spy','');
                $(this).remove();
            });


            config_inputitem.over_wrire_data_id();
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
            li_clone.find("input.table_name").select2(config_inputitem.table_name_option);
            li_clone.find("input.column_name").select2(config_inputitem.column_name_option);

            //set select 2 post name
            li_clone.find("input.post_name").select2(config_inputitem.post_name_option);
            config_inputitem.set_auto_complete_content_edit_able();
            config_inputitem.update_nestable();

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
            config_inputitem.update_nestable();
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
                config_inputitem.update_data_column(this,'primary_key','radio');
            });
        },
        set_auto_complete:function(){
            $(".table_name").select2(config_inputitem.table_name_option);
            $(".column_name").select2(config_inputitem.column_name_option);



            $(".post_name").select2(config_inputitem.post_name_option);

        },
        update_nestable:function(){
            config_inputitem.updateOutput($('#config_inputitem').data('output', $('#config_inputitem_output')));
        },
        remove_item_nestable:function(self){
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

            config_inputitem.update_nestable();
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
            //config_inputitem.update_nestable();
            dd_item.droppable(config_inputitem.option_droppable);
        },
        over_wrire_data_id:function(root){
            if(typeof root=='undefined')
            {
                root=$('.dd>.dd-list');
            }
            root.find('>li').each(function(index){
               self=$(this);
                self.attr('data-id',index+1);
                self.data('id',index+1);
                self.removeData('level');
                config_inputitem.over_wrire_data_id(self.find('>ol.dd-list'));
            });
        },
        updateOutput:function(e){

            if($(e.target).hasClass('key')||$(e.target).hasClass('value'))
            {

                self=$(e.target);
                html=self.html();
                dd_item=self.closest('.dd-item');
                if(self.hasClass('key'))
                {
                    dd_item.attr('data-key',html);
                    dd_item.data('key',html);
                    config_inputitem.update_nestable();
                }else{
                    dd_item.attr('data-value',html);
                    dd_item.data('value',html);
                    config_inputitem.update_nestable();
                }

            }
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