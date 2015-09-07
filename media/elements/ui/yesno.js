jQuery(document).ready(function($){

    element_ui_yesno={
        init_ui_yesno:function(){

            $('input.block-item.block-item-yesno').each(function(){
                self=$(this);
                self.bootstrapSwitch();
            });
            element_ui_button.list_function_run_befor_submit.push(element_ui_yesno.update_data);
        },
        update_data:function(data_submit)
        {
            $("input.block-item.block-item-yesno").each(function(){
                self=$(this);
                name= self.attr('name');
                data_submit[name]= self.bootstrapSwitch('state')?1:0;
            });
            return data_submit;

        }

    };
    element_ui_yesno.init_ui_yesno();


});