jQuery(document).ready(function ($) {

    element_ui_input = {
        init_ui_input: function () {
            element_ui_button.list_function_run_befor_submit.push(element_ui_input.update_data);
        },
        update_data:function(data_submit){
            $(".block-item.block-item-input").each(function(){
                input=$(this);
                name_input=input.attr('name');
                data_submit[name_input]=input.val();
            });
            return data_submit;
        }
    };
    element_ui_input.init_ui_input();

});