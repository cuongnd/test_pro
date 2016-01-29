// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.field_button = function (element, options) {




        // plugin's default options
        var defaults = {
            functionOnClick:''
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
            functionOnClick=plugin.settings.functionOnClick;
            $element.unbind('click');
            $properties=$element.closest('.properties.block');
            block_id=$properties.attr('data-object-id');
            $block_element=$('.block-item[data-block-id="'+block_id+'"]');
            element_type=$block_element.attr('element-type');
            instant_element=$block_element.data('ui_'+element_type);
            $element.click(function(){
                instant_element[functionOnClick]();
            });





        }
        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_button = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_button')) {
                var plugin = new $.field_button(this, options);
                $(this).data('field_button', plugin);

            }

        });

    }

})(jQuery);
