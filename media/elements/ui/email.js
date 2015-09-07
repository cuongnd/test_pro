jQuery(document).ready(function ($) {

    element_ui_email = {
        init_ui_email: function () {
            element_ui_button.list_function_run_befor_submit.push(element_ui_email.update_data);
        },
        update_data:function(data_submit){
            $(".block-item.block-item-input").each(function(){
                input=$(this);
                name_email=input.attr('name');
                data_submit[name_email]=input.val();
            });
            return data_submit;
        }
    };
    element_ui_email.init_ui_email();

});