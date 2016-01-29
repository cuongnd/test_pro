jQuery(document).ready(function($){

    element_ui_link=$.extend({
        init_ui_link:function()
        {

        },
        update_text:function(self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            data_text=properties.find('input[name="jform[params][data][text]"]');
            if(data_text.val().trim()=='')
                $('a[data-block-id="'+block_id+'"]').html(self.val());
        }

    }, element_ui_element);

    element_ui_link.init_ui_link();




});