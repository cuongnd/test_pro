jQuery(document).ready(function($){

    element_ui_icon={

        init_ui_icon:function(){
            $('.control-element.control-element-icon').each(function(){
                self=$(this);
                block_id=self.attr('data-block-id');
                block_parent_id=self.attr('data-block-parent-id');
                icon=$('i[data-block-id="'+block_id+'"][data-block-parent-id="'+block_parent_id+'"]');
                icon.insertAfter(self);
                self.hide();
            });


        },
        add_control_element:function(self){
            block_id = self.attr('data-block-id');
            block_parent_id = self.attr('data-block-parent-id');
            control_element = $('.control-element.control-element-icon[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]');
            enable_add_control=self.attr('enable-add-control');

            enable_add_control=(typeof enable_add_control=='undefined')?1:enable_add_control;

            if(enable_add_control=="1") {
                control_element.append(self);
                control_element.show();
                self.attr('enable-add-control', '0');
            }else{
                self.insertAfter(control_element);
                control_element.hide();
                self.attr('enable-add-control', '1');
            }

        }



    };

    $(document).on('click','.block-item.block-item-icon',function(e){

        block_id=$(this).attr('data-block-id');
        if (e.ctrlKey)
        {
            element_ui_icon.add_control_element($(this));
        }else {
            $( '.config-block[data-block-id="'+block_id+'"]' ).trigger( "click" );
        }

    });
});