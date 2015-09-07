jQuery(document).ready(function($){

    element_ui_select_date_time={
        init_ui_select_date_time:function(){
            $('.block-item-selectdatetime').datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                maxDate: new Date(),
                buttonImageOnly: true,
                buttonImage: this_host+'components/com_bookpro/assets/images/callender.png',
                onSelect: function(selected) {
                }
            });

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

        }

    };



});