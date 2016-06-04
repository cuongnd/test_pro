//huong dan su dung
/*
 $('.field_currency').field_currency();

 field_currency=$('.field_currency').data('field_currency');
 console.log(field_currency);
 */

// jQuery Plugin for SprFlat admin field_currency
// Control options and basic function of field_currency
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_currency = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_currency
            //be sure to be same as colors on main.css or custom-variables.less
            field:{
                name:''
            },
            list_currency:[]

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
            var list_currency=plugin.settings.list_currency;
            $element.find('input[name="'+plugin.settings.field.name+'"]').select2({
                data:list_currency
            });
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_currency = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_currency')) {
                var plugin = new $.field_currency(this, options);

                 $(this).data('field_currency', plugin);

            }

        });

    }

})(jQuery);
