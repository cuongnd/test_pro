jQuery(document).ready(function($){

    element_ui_div={
        init_ui_div:function(){
          if(enableEditWebsite)
          {
              element_ui_div.set_droppable($( '.block-item-div[enabled-droppable="true"]' ));
              element_ui_div.set_resizable_for_control($( '.control-element-div[enabled-resizable="true"]' ));

          }
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
                    element_ui_div.render_element($(this),ui.draggable);
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
            block= $('.block-item-div[data-block-id="'+block_id+'"]');
            if(self.val()==1)
            {
                element_ui_div.set_droppable(block);
                block.attr('enabled-droppable',true);
            }else
            {
                element_ui_div.unset_set_droppable(block);
                block.attr('enabled-droppable',false);
            }

        },
        on_off_resizable_for_control:function(self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            control= $('.control-element-div[data-block-id="'+block_id+'"]');
            if(self.val()==1)
            {
                element_ui_div.set_resizable_for_control(control);
                control.attr('enabled-resizable',true);
            }else
            {
                element_ui_div.unset_resizable_for_control(control);
                control.attr('enabled-resizable',false);
            }

        }

    };



});