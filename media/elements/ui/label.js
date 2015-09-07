jQuery(document).ready(function($){

    element_ui_label={
        init_ui_label:function(){
            $('.control-element.control-element-label').each(function(){
                self=$(this);
                block_id=self.attr('data-block-id');
                block_parent_id=self.attr('data-block-parent-id');
                icon=$('label[data-block-id="'+block_id+'"][data-block-parent-id="'+block_parent_id+'"]');
                icon.insertAfter(self);
                self.hide();
            });


        },
        update_text:function(self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            data_text=properties.find('input[name="jform[params][data][text]"]');
            if(data_text.val().trim()=='')
                $('label[data-block-id="'+block_id+'"]').html(self.val());
        },
        change_to_column:function(self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            if(self.val()==1)
            {
                $('.block-item[data-block-id="'+block_id+'"]').addClass('div-column');
            }else
            {
                $('.block-item[data-block-id="'+block_id+'"]').removeClass('div-column');
            }

        },
        add_control_element:function(self){
            block_id = self.attr('data-block-id');
            block_parent_id = self.attr('data-block-parent-id');
            control_element = $('.control-element.control-element-label[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]');
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
    $(document).on('click','.block-item.block-item-label',function(e){

        block_id=$(this).attr('data-block-id');
        if (e.ctrlKey)
        {
            element_ui_label.add_control_element($(this));
        }else {
            $( '.config-block[data-block-id="'+block_id+'"]' ).trigger( "click" );
        }

    });




});