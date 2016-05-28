//huong dan su dung
/*
 $('.field_code').field_code();

 field_code=$('.field_code').data('field_code');
 console.log(field_code);
 */

// jQuery Plugin for SprFlat admin field_code
// Control options and basic function of field_code
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_code = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for field_code
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
            $element.find('.generator-code').click(function(){
                $element.find('input[name="'+plugin.settings.field.name+'"]').val(plugin.makeid());

            });
        }
        plugin.makeid=function()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_code = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_code')) {
                var plugin = new $.field_code(this, options);

                 $(this).data('field_code', plugin);

            }

        });

    }

})(jQuery);
