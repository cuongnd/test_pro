// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_listview = function (element, options) {




        // plugin's default options
        var defaults = {
            listview_option:{

            }
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var element_id=$element.attr('id');
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            listview_option=plugin.settings.listview_option;
            $element.kendoListView(listview_option);

        }
        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_listview = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_listview')) {
                var plugin = new $.ui_listview(this, options);
                $(this).data('ui_listview', plugin);

            }

        });

    }

})(jQuery);
