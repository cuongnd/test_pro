jQuery(document).ready(function($){

    element_ui_editor={
        init_ui_editor:function(){

            CKEDITOR.disableAutoInline = true;
            var prev_block_item_editor='dsfd';
            $('.block-item.block-item-editor').each(function(){
                editor_id=$(this).attr('id');
                prev_block_item_editor=editor_id;
                var instance = CKEDITOR.instances[editor_id];
                if (!instance) {
                    CKEDITOR.inline( editor_id );
                }



            });
            element_ui_button.list_function_run_befor_submit.push(element_ui_editor.update_data);

        },
        update_data:function(data_submit){
            $.each(CKEDITOR.instances, function( index, editor ) {
                textarea_id=index.toString();
                $('#'+textarea_id).val(editor.getData());

            });
            $('.block-item.block-item-editor').each(function(){
                self=$(this);
                name=self.attr('name');
                data_submit[name]=self.val();
            });
            return data_submit;
        }

    };



});