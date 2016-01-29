(function ($) {

    // here we go!
    $.ui_h3 = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            block_id:0,
            trigger_change:[],
            element_name:'',

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
            $element.dblclick(function(e){
                if(plugin.settings.enable_edit_website==1) {
                    $element.attr('contenteditable', true);
                }
            });
            $element.keypress(function(e){
                var text=$(this).text();
                if(text.length>plugin.settings.max_character)
                {
                    e.preventDefault();
                }
                if(e.which==13) {
                    e.preventDefault();
                    if (typeof ajax_web_design !== 'undefined') {
                        ajax_web_design.abort();
                    }
                    ajax_web_design = $.ajax({
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        url: this_host + '/index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_utility',
                                task: 'block.ajax_update_field_block',
                                value: text,
                                field:'params.element_config.text',
                                block_id: plugin.settings.block_id
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
                            if(response.e==1)
                            {
                                alert(response.m);
                            }else{
                                alert(response.m);
                                $element.attr('contenteditable', false);
                            }

                        }
                    });

                }

            });


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_h3 = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_h3')) {
                var plugin = new $.ui_h3(this, options);
                $(this).data('ui_h3', plugin);

            }

        });

    }

})(jQuery);





