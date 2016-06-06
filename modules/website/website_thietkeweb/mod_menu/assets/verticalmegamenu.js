//huong dan su dung
/*
 $('.verticalmegamenu').verticalmegamenu();

 verticalmegamenu=$('.verticalmegamenu').data('verticalmegamenu');
 console.log(verticalmegamenu);
 */

// jQuery Plugin for SprFlat admin verticalmegamenu
// Control options and basic function of verticalmegamenu
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.verticalmegamenu = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for verticalmegamenu
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
            console.log('sdfsdfsdfds');
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.verticalmegamenu = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('verticalmegamenu')) {
                var plugin = new $.verticalmegamenu(this, options);

                $(this).data('verticalmegamenu', plugin);

            }

        });

    }

})(jQuery);
