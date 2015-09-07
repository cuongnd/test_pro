jQuery(document).ready(function($){

    element_ui_listradiobox={
        init_ui_listradiobox:function(){
            element_ui_button.list_function_run_befor_submit.push(element_ui_listradiobox.update_data);
        },
        update_data:function(data_submit){
            $(".block-item.block-item-listradiobox").each(function(){
                self=$(this);
                radio_checked=self.find('input[type="radio"]:checked');
                if(radio_checked.length)
                {
                    radio_name=radio_checked.attr('name');
                    data_submit[radio_name]=radio_checked.val();
                }
            });
            return data_submit;
        }

    };
    element_ui_listradiobox.init_ui_listradiobox();


});