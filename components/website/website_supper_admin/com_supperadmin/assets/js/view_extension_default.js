//huong dan su dung
/*
 $('.view_extension_default').view_extension_default();

 view_extension_default=$('.view_extension_default').data('view_extension_default');
 console.log(view_extension_default);
 */

// jQuery Plugin for SprFlat admin view_extension_default
// Control options and basic function of view_extension_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_extension_default = function(element, options) {

        // plugin's default options
        var defaults = {
            extension:{
                type:""
            }
            //main color scheme for view_extension_default
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
            var module=plugin.settings.module;
            var element=$element.find('select[name="jform[extension_id]"] option[value="'+module+'"]').data('element');
            $element.find('input[name="jform[module]"]').val(element);
            $element.find('input[name="jform[title]"]').val(element);
        };
        plugin.format_input_mask = function () {
            var type = plugin.settings.extension.type;
            console.log(type);
            switch(type) {
                case 'module':
                    var selector=$element.find('input[name="jform[name]"],input[name="jform[element]"]');
                    selector.inputmask({
                        mask: "mod_*************************************************",
                        placeholder: ''
                    });
                    break;
                case 'component':
                    var selector=$element.find('input[name="jform[name]"],input[name="jform[element]"]');
                    selector.inputmask({
                        mask: "com_*************************************************",
                        placeholder: ''
                    });
                    break;

                case 'plugin':
                    //code block
                    break;
                default:
                //default code block
            }
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('select[name="jform[type]"]').change(function(){
                var type=$(this).val();
                plugin.settings.extension.type=type;
                plugin.format_input_mask();
            });

        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_extension_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_extension_default')) {
                var plugin = new $.view_extension_default(this, options);

                $(this).data('view_extension_default', plugin);

            }

        });

    }

})(jQuery);
