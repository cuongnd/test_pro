//huong dan su dung
/*
 $('.view_supperadminconfig_default').view_supperadminconfig_default();

 view_supperadminconfig_default=$('.view_supperadminconfig_default').data('view_supperadminconfig_default');
 console.log(view_supperadminconfig_default);
 */

// jQuery Plugin for SprFlat admin view_supperadminconfig_default
// Control options and basic function of view_supperadminconfig_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_supperadminconfig_default = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_supperadminconfig_default
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
                    option: 'com_supperadmin',
                    task: 'supperadminconfig.ajax_set_request_update_website'
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
                            alert('supper admin request update website success');


                        }else if(response.e==1){
                            alert(response.m);
                        }



                    }
                });

            });
        };
        plugin.set_request_update_supper_admin_website = function () {
            $element.find('.set_request_update_supper_admin_website').click(function(){
                var option_click= {
                    enable_load_component: 1,
                    option: 'com_supperadmin',
                    task: 'supperadminconfig.ajax_set_request_update_supper_admin_website'
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
                            alert('supper admin request update supper admin website success');


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
            plugin.set_request_update_supper_admin_website();

        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_supperadminconfig_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_supperadminconfig_default')) {
                var plugin = new $.view_supperadminconfig_default(this, options);

                $(this).data('view_supperadminconfig_default', plugin);

            }

        });

    }

})(jQuery);
