jQuery(document).ready(function($){

    element_ui_listcheckbox={
        init_ui_listcheckbox:function(){
            element_ui_button.list_function_run_befor_submit.push(element_ui_listcheckbox.update_data);
            $(".block-item.block-item-listcheckbox").element_list_checkbox();
        },
        update_data:function(data_submit){
            $(".block-item.block-item-listcheckbox").each(function(){
                self=$(this);
                checkbox_checked=self.find('input.block-item.block-item-listcheckbox-item[type="checkbox"]:checked');

                if(checkbox_checked.length)
                {
                    checkbox_checked.each(function(index){
                        this_checkbox=$(this);
                        checkbox_name=this_checkbox.attr('name');
                        if(typeof data_submit[checkbox_name]=='undefined')
                        {
                            data_submit[checkbox_name]=new Array();
                        }

                        data_submit[checkbox_name].push(this_checkbox.val());
                    });

                }
            });
            return data_submit;
        }

    };
    element_ui_listcheckbox.init_ui_listcheckbox();


});