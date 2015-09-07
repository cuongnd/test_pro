jQuery(document).ready(function($){

    element_ui_popover={
        init_ui_popover:function()
        {
            $('.module-config,.config-block').each(function () {
                $(this).popover({
                    html: true,
                    placement: 'top',
                    title: function () {
                        block_id = $(this).attr('data-block-id');
                        block_parent_id = $(this).attr('data-block-parent-id');
                        return $('.block-item[data-block-id="' + block_id + '"][data-block-parent-id="' + block_parent_id + '"]').attr('element-type') + '(' + block_id + ')';
                    },
                    trigger: 'hover ',
                    container: 'body',
                    content: function () {
                        return 'you can right click to copy,cut element, left click go to properties this element';
                    }
                });
            });

        }
    };



});