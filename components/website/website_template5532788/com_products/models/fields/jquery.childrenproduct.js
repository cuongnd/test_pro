//huong dan su dung
/*
 $('.field_childrenproduct').field_childrenproduct();

 field_childrenproduct=$('.field_childrenproduct').data('field_childrenproduct');
 console.log(field_childrenproduct);
 */

// jQuery Plugin for SprFlat admin field_childrenproduct
// Control options and basic function of field_childrenproduct
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_childrenproduct = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_childrenproduct
            //be sure to be same as colors on main.css or custom-variables.less
            field:{
                name:''
            },
            list_product_category:[]

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
            var list_product_category=plugin.settings.list_product_category;
            $element.find('input[name="'+plugin.settings.field.name+'"]').select2({
                data:list_product_category
            });
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_childrenproduct = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_childrenproduct')) {
                var plugin = new $.field_childrenproduct(this, options);

                 $(this).data('field_childrenproduct', plugin);

            }

        });

    }

})(jQuery);
