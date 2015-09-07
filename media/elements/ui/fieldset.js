jQuery(document).ready(function($){

    element_ui_field_set={

        init_field_set:function(){
            element_ui_field_set.set_droppable($( '.block-item-fieldset[enabled-droppable="true"]' ));
            element_ui_field_set.set_resizable_for_control($( '.control-element-fieldset[enabled-resizable="true"]' ));


        },
        update_text:function(self){
            block_id=self.closest('.properties.block').attr('data-object-id');
            $('.fieldset[data-block-id="'+block_id+'"]').find('.legend-border.text').html(self.val());
        },
        scroll:function(self){
            block_id=self.closest('.properties.block').attr('data-object-id');
            if(self.val()==1)
            {
                 if(typeof pluginNS=='undefined') {
                      $('head').append($('<script src="'+this_host+'/media/system/js/malihu-custom-scrollbar-plugin-3.0.7/js/uncompressed/jquery.mCustomScrollbar.js'+'" type="text/javascript"></script>'));
                      $('head').append($('<link href="'+this_host+'/media/system/js/malihu-custom-scrollbar-plugin-3.0.7/jquery.mCustomScrollbar.min.css'+'" media="screen" type="text/css" rel="stylesheet">'));
                 }
                $('fieldset[data-block-id="' + block_id + '"]').mCustomScrollbar();
            }else{
                $('fieldset[data-block-id="' + block_id + '"]').mCustomScrollbar("destroy");

            }

        },
        add_row:function(self){
            object_id=self.closest('.properties.block').attr('data-object-id');
            ajaxInsertElement=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertRow',
                        parentColumnId:object_id,
                        menuItemActiveId:menuItemActiveId,
                        ajaxgetcontent:1,
                        screenSize:screenSize

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {





                }
            });
        },
        render_element:function(self,ui){
            parentColumnId = self.attr('data-block-id');
            pathElement = ui.attr('data-element-path');
            ajaxInsertElement = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertElement',
                        parentColumnId: parentColumnId,
                        screenSize: screenSize,
                        addSubRow: 0,
                        ajaxgetcontent: 1,
                        menuItemActiveId: menuItemActiveId,
                        pathElement: pathElement

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
                    html = $('<div>' + response.html + '</div');
                    html = $(html);
                    self.append(html.html());


                }
            });

        },
        set_droppable:function(self){
            self.droppable({
                accept: ".item-element",
                greedy: true,
                drop: function( event, ui ) {
                    element_ui_field_set.render_element($(this),ui.draggable);
                }
            });
        },
        unset_set_droppable:function(self){
            self.droppable( "destroy" );
        },
        set_resizable_for_control:function(self){
            self.resizable({

            });
        },
        unset_resizable_for_control:function(self){
            self.resizable( "destroy" );
        },
        on_off_droppable:function(self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            block= $('.block-item-fieldset[data-block-id="'+block_id+'"]');
            if(self.val()==1)
            {
                element_ui_field_set.set_droppable(block);
                block.attr('enabled-droppable',true);
            }else
            {
                element_ui_field_set.unset_set_droppable(block);
                block.attr('enabled-droppable',false);
            }

        },
        on_off_resizable_for_control:function(self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            control= $('.control-element-fieldset[data-block-id="'+block_id+'"]');
            if(self.val()==1)
            {
                element_ui_field_set.set_resizable_for_control(control);
                control.attr('enabled-resizable',true);
            }else
            {
                element_ui_field_set.unset_resizable_for_control(control);
                control.attr('enabled-resizable',false);
            }

        }
    };
    //$('.tab_ui .remove-tab-content').click(function(){
    $(document).delegate(".tab_ui .remove-tab-content","click",function(e){
        element_ui_field_set.remove_tab($(this));
    });


});