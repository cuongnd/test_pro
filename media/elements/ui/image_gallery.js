// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_image_gallery = function (element, options) {



        defaults={

        };
        // plugin's default options

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var element_id=$element.attr('id');
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            var slide_width=plugin.settings.slide_width;
            if($.isNumeric(slide_width))
            {
                slide_width=slide_width+'px';
            }
            if(slide_width=='100%')
            {
                $element_parent=$element.parent();
                slide_width=$element_parent.width();
                slide_width=slide_width+'px';
            }
            var image_height=plugin.settings.image_height;
            if($.isNumeric(image_height))
            {
                image_height=image_height+'px';
            }


        }
        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_image_gallery = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_image_gallery')) {
                var plugin = new $.ui_image_gallery(this, options);
                $(this).data('ui_image_gallery', plugin);

            }

        });

    }

})(jQuery);
