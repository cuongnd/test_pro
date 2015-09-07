jQuery(document).ready(function($){

    element_ui_input={
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

        }

    };



});