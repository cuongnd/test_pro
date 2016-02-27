//huong dan su dung
/*
 $('.field_change_view').field_change_view();

 field_change_view=$('.field_change_view').data('field_change_view');
 console.log(field_change_view);
 */

// jQuery Plugin for SprFlat admin field_change_view
// Control options and basic function of field_change_view
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_change_view = function(element, options) {

        // plugin's default options
        var defaults = {
            show_popup_control:false
            //main color scheme for field_change_view
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
            var show_popup_control=plugin.settings.show_popup_control;
            if(show_popup_control) {
                document.title = 'change view menu item';
            }
            $element.find('#collapseTypes').on('shown', function (event) {

            })
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_change_view = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_change_view')) {
                var plugin = new $.field_change_view(this, options);

                $(this).data('field_change_view', plugin);

            }

        });

    }

})(jQuery);
