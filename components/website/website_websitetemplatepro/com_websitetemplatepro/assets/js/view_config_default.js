//huong dan su dung
/*
 $('.view_config_default').view_config_default();

 view_config_default=$('.view_config_default').data('view_config_default');
 console.log(view_config_default);
 */

// jQuery Plugin for SprFlat admin view_config_default
// Control options and basic function of view_config_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_config_default = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_config_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_config_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_config_default')) {
                var plugin = new $.view_config_default(this, options);

                $(this).data('view_config_default', plugin);

            }

        });

    }

})(jQuery);
