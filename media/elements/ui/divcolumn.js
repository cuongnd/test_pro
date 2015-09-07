jQuery(document).ready(function($){

    element_ui_div_column={

        init_div_column:function(){
            /*for(var i=0;i<=$('.enable-item-resizable[data-block-parent-id!="0"][data-block-id!="0"]').length;i++){
                item_resizable=$('.enable-item-resizable[data-block-parent-id!="0"][data-block-id!="0"]:eq('+i+')');
                block_id=item_resizable.attr('data-block-id');
                if(item_resizable.hasClass('set_resizable_'+block_id))
                    continue;
                item_resizable.addClass('set_resizable_'+block_id);
                item_resizable.resizable({
                    autoHide: true,
                    start: elementuiDivColumn.on_start_moving,
                    stop: elementuiDivColumn.on_end_moving
                });

            }*/


        },
        on_start_moving:function( event, ui ){
            console.log(ui);
        },
        on_end_moving:function(event, ui){
            console.log(ui);
        }
    };


});