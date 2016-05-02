//huong dan su dung
/*
 $('.view_otheradmintool_default').view_otheradmintool_default();

 view_otheradmintool_default=$('.view_otheradmintool_default').data('view_otheradmintool_default');
 console.log(view_otheradmintool_default);
 */

// jQuery Plugin for SprFlat admin view_otheradmintool_default
// Control options and basic function of view_otheradmintool_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_otheradmintool_default = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_otheradmintool_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.fix_menu = function () {
            $element.find('.fix_menu').click(function(){
                var option_click= {
                    enable_load_component: 1,
                    option: 'com_supperadmin',
                    task: 'otheradmintool.fix_menu'
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
                            alert('fix menu success');


                        }else if(response.e==1){
                            alert(response.m);
                        }



                    }
                });

            });
        };
        plugin.fix_block = function () {
            $element.find('.fix_block').click(function(){
                var option_click= {
                    enable_load_component: 1,
                    option: 'com_supperadmin',
                    task: 'otheradmintool.fix_block'
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
                            alert('fix block success');


                        }else if(response.e==1){
                            alert(response.m);
                        }



                    }
                });

            });
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            plugin.fix_menu();
            plugin.fix_block();

        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_otheradmintool_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_otheradmintool_default')) {
                var plugin = new $.view_otheradmintool_default(this, options);

                $(this).data('view_otheradmintool_default', plugin);

            }

        });

    }

})(jQuery);
