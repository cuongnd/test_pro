// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_select = function (element, options) {




        // plugin's default options
        var defaults = {
            enable_select2:1
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

            if(plugin.settings.enable_select2)
            {
                $element.select2(
                    {
                        width:'resolve'
                    }
                );
            }
            Joomla_post.list_function_run_befor_submit.push(plugin.update_data);
        }
        plugin.update_data=function(data_submit){
            var name_attr=$element.attr('name');
            if(!$.isArray(data_submit[name_attr]))
            {
                data_submit[name_attr]=[];
            }
            data_submit[name_attr].push($element.val());

        }

        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_select = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_select')) {
                var plugin = new $.ui_select(this, options);
                $(this).data('ui_select', plugin);

            }

        });

    }

})(jQuery);
