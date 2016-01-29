(function ($) {

    // here we go!
    $.ui_column = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            block_id:0,
            clone_config:{
                enble_clone_config:false,
                control_seletect_number:0,
                aria_clone_append:0
            },
            button_state:''
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
            var enble_clone_config=plugin.settings.clone_config.enble_clone_config;
            if(enble_clone_config==true)
            {
                plugin.make_clone();
            }

        }
        plugin.format_name=function(){
            var clone_config=plugin.settings.clone_config;
            var block_id=plugin.settings.block_id;
            var control_seletect_number=clone_config.control_seletect_number;
            var aria_clone_append=clone_config.aria_clone_append;

        }
        plugin.make_clone=function(){
            var clone_config=plugin.settings.clone_config;
            var block_id=plugin.settings.block_id;
            var control_seletect_number=clone_config.control_seletect_number;
            var aria_clone_append=clone_config.aria_clone_append;
            append_to='.block-item[data-block-id="'+aria_clone_append+'"]';
            $('select.block-item.block-item-select[data-block-id="'+control_seletect_number+'"]').change(function(){
                clone_number=$(this).val();
                var post_option={
                    option:"com_utility",
                    view:"blocks",
                    tpl:"ajaxloadblocks",
                    Itemid:menuItemActiveId,
                    tmpl:"ajax_json"
                };
                dataPost={
                    block_id:block_id,
                    clone_number:clone_number-1,
                    append_to:append_to
                }
                post_option= $.param(post_option);
                ajaxSavePropertyModule=$.ajax({
                    contentType: 'application/json',
                    type: "POST",
                    dataType: "json",
                    url: this_host+'/index.php?'+post_option,
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
                        Joomla.sethtmlfortag1(response);

                    }
                });

            });
        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_column = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_column')) {
                var plugin = new $.ui_column(this, options);
                $(this).data('ui_column', plugin);

            }

        });

    }

})(jQuery);



