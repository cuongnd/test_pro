//huong dan su dung
/*
 $('.mod_menu').mod_menu();

 mod_menu=$('.mod_menu').data('mod_menu');
 console.log(mod_menu);
 */

// jQuery Plugin for SprFlat admin mod_menu
// Control options and basic function of mod_menu
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.mod_menu = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for mod_menu
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
            console.log($element);
            $element.find('.u-vmenu').vmenuModule({
                Speed: 200,
                autostart: false,
                autohide: true
            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.mod_menu = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('mod_menu')) {
                var plugin = new $.mod_menu(this, options);

                $(this).data('mod_menu', plugin);

            }

        });

    }

})(jQuery);
