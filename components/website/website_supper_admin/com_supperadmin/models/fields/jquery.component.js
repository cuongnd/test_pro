//huong dan su dung
/*
 $('.field_component').field_component();

 field_component=$('.field_component').data('field_component');
 console.log(field_component);
 */

// jQuery Plugin for SprFlat admin field_component
// Control options and basic function of field_component
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_component = function(element, options) {

        // plugin's default options
        var defaults = {
            field:{
                name:'',
                id:'',
            }
            //main color scheme for field_component
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
          };

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_component = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_component')) {
                var plugin = new $.field_component(this, options);

                 $(this).data('field_component', plugin);

            }

        });

    }

})(jQuery);
