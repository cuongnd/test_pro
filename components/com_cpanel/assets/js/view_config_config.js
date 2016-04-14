//huong dan su dung
/*
 $('.view_config_config').view_config_config();

 view_config_config=$('.view_config_config').data('view_config_config');
 console.log(view_config_config);
 */

// jQuery Plugin for SprFlat admin view_config_config
// Control options and basic function of view_config_config
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_config_config = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_config_config
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.set_request_update_website = function () {
            $element.find('.set_request_update_website').click(function(){
                var option_click= {
                    enable_load_component: 1,
                    option: 'com_cpanel',
                    task: 'config.ajax_set_request_update_website'
                };
                option_click= $.param(option_click);
                var data={};
                var ajax=$.ajax({
                    contentType: 'application/json',
                    type: "POST",
                    dataType: "json",
                    url: this_host+'/index.php?'+option_click,
                    data: JSON.stringify(data),
                    beforeSend: function () {
                        $('.div-loading').css({
                            display: "block"


                        });
                    },
                    success: function (response) {
                        $('.div-loading').css({
                            display: "none"


                        });
                        if(response.e==0)
                        {
                            alert('setup request update website success');


                        }else if(response.e==1){
                            alert(response.m);
                        }



                    }
                });

            });
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            plugin.set_request_update_website();

        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_config_config = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_config_config')) {
                var plugin = new $.view_config_config(this, options);

                $(this).data('view_config_config', plugin);

            }

        });

    }

})(jQuery);
