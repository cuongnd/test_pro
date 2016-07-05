//huong dan su dung
/*
 $('.view_general_default').view_general_default();

 view_general_default=$('.view_general_default').data('view_general_default');
 console.log(view_general_default);
 */

// jQuery Plugin for SprFlat admin view_general_default
// Control options and basic function of view_general_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_general_default = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_general_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var debug=plugin.settings.debug;
            if(debug)
            {

            }
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_general_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_general_default')) {
                var plugin = new $.view_general_default(this, options);

                $(this).data('view_general_default', plugin);

            }

        });

    }

})(jQuery);
