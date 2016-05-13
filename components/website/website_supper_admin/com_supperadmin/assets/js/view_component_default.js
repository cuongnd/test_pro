//huong dan su dung
/*
 $('.view_component_default').view_component_default();

 view_component_default=$('.view_component_default').data('view_component_default');
 console.log(view_component_default);
 */

// jQuery Plugin for SprFlat admin view_component_default
// Control options and basic function of view_component_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_component_default = function(element, options) {

        // plugin's default options
        var defaults = {
            extension_id:0
            //main color scheme for view_component_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.set_data = function () {
            var extension_id=plugin.settings.extension_id;
            var extension_name=$element.find('select[name="jform[extension_id]"] option[value="'+extension_id+'"]').data('element');
            console.log($element.find('select[name="jform[extension_id]"] option[value="'+extension_id+'"]').data());
            $element.find('input[name="jform[name]"]').val(extension_name);
            $element.find('input[name="jform[title]"]').val(extension_name);
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('select[name="jform[extension_id]"]').change(function(){
                var extension_id=$(this).val();
                plugin.settings.extension_id=extension_id;
                plugin.set_data();
            });

        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_component_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_component_default')) {
                var plugin = new $.view_component_default(this, options);

                $(this).data('view_component_default', plugin);

            }

        });

    }

})(jQuery);
