(function ($) {

    // here we go!
    $.ui_forminput = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            list_function_run_befor_submit:[],
            block_templates:[],
            block_id:0
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            enable_edit_website= plugin.settings.enable_edit_website;
            block_templates= plugin.settings.block_templates;
            block_id= plugin.settings.block_id;
            if(enable_edit_website==0)
            {
                $wapper_content=$('<div class="wapper-content wapper-content_0" data-block-id="'+block_id+'"></div>');
                $.each(block_templates,function(index,block_id){
                    var $template=$('.block-item[data-block-id="'+block_id+'"]');
                    $template.appendTo($wapper_content);
                    var cnt = $template.contents();
                    $template.replaceWith(cnt);

                });
                $wapper_content.appendTo($element);

            }


        }

        plugin.update_data_by_key=function(list_key_value){
            block_id= plugin.settings.block_id;

            if (typeof ajax_update_list_check_box !== 'undefined') {
                ajax_update_list_check_box.abort();
            }
            dataPost=list_key_value;
            ajax_update_list_check_box=$.ajax({
                contentType: 'application/json',
                type: "POST",
                dataType: "json",
                url: this_host+'/index.php?option=com_utility&task=block.ajax_update_block&block_id='+block_id,
                data: JSON.stringify(dataPost),
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
                    plugin.update(response);
                }
            });

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_forminput = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_forminput')) {
                var plugin = new $.ui_forminput(this, options);
                $(this).data('ui_forminput', plugin);

            }

        });

    }

})(jQuery);



