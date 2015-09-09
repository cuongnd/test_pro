jQuery(document).ready(function($){

    element_ui_textarea={
        init_ui_textarea:function(){
            element_ui_button.list_function_run_befor_submit.push(element_ui_textarea.update_data);
        },
        update_data:function(data_submit){
            $(".block-item.block-item-textarea").each(function(){
                textarea=$(this);
                name_textarea=textarea.attr('name');
                data_submit[name_textarea]=textarea.val();
            });
            return data_submit;
        }



    };
    element_ui_textarea.init_ui_textarea();


});