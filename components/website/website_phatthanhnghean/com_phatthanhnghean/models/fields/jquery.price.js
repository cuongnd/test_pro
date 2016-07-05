//huong dan su dung
/*
 $('.field_price').field_price();

 field_price=$('.field_price').data('field_price');
 console.log(field_price);
 */

// jQuery Plugin for SprFlat admin field_price
// Control options and basic function of field_price
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_price = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_price
            //be sure to be same as colors on main.css or custom-variables.less
            field:{
                name:''
            }

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
            $element.find('.price').autoNumeric('init', {

            }).change(function(){
                var price=$(this).autoNumeric('get');
                $element.find('input[name="'+plugin.settings.field.name+'"]').val(price);
            });
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_price = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_price')) {
                var plugin = new $.field_price(this, options);

                 $(this).data('field_price', plugin);

            }

        });

    }

})(jQuery);
