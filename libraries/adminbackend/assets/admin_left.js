jQuery(document).ready(function($) {





    $(document).on('change','#colorpickerFieldSelect',function(){
        color=$(this).val();
        $('.grid-stack-item .grid-stack-item-content, .grid-stack-item .placeholder-content').css({
            "border-color":color
        });
    });
    $(document).on('input','#colorpickerFieldSelect',function(){
        color=$(this).val();
        $('.grid-stack-item .grid-stack-item-content, .grid-stack-item .placeholder-content').css({
            "border-color":color
        });
    });

    $("#disable_widget,#editing,#hide_setting,#hide_module_item_setting,#full_height,#disable_border_module").bootstrapSwitch();
    $('input.plugin_item').bootstrapSwitch();
    $('input.plugin_item').on('switchChange.bootstrapSwitch', function(event, state) {
        plugin_item=$(this).closest('li.plugin_item');
        data_plugin_id=plugin_item.attr('data-plugin-id');
        enablePlugin(state,data_plugin_id);

    });
    function enablePlugin(state,data_plugin_id)
    {
        $.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {


                dataPost = {
                    option: 'com_plugins',
                    task: 'plugin.enablePlugin',
                    enablePlugin:state,
                    data_plugin_id:data_plugin_id

                };
                return dataPost;
            })(),
            beforeSend: function () {
                $('.div-loading').css({
                    display: "block"


                });
                // $('.loading').popup();
            },
            success: function (response) {
                $('.div-loading').css({
                    display: "none"


                });
            }
        });


    }


});

