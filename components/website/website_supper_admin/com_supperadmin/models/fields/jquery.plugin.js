//huong dan su dung
/*
 $('.field_plugin').field_plugin();

 field_plugin=$('.field_plugin').data('field_plugin');
 console.log(field_plugin);
 */

// jQuery Plugin for SprFlat admin field_plugin
// Control options and basic function of field_plugin
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_plugin = function(element, options) {

        // plugin's default options
        var defaults = {
            field:{
                name:'',
                id:'',
            }
            //main color scheme for field_plugin
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
            var name=plugin.settings.field.name;
            var selector=$element.find('input[name="'+name+'"]');
            selector.inputmask({
                    mask: "plig_*************************************************",
                    placeholder: ''
            }
            );
          };

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_plugin = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_plugin')) {
                var plugin = new $.field_plugin(this, options);

                 $(this).data('field_plugin', plugin);

            }

        });

    }

})(jQuery);
