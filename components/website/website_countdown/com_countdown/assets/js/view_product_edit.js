//huong dan su dung
/*
 $('.view_product_edit').view_product_edit();

 view_product_edit=$('.view_product_edit').data('view_product_edit');
 console.log(view_product_edit);
 */

// jQuery Plugin for SprFlat admin view_product_edit
// Control options and basic function of view_product_edit
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_product_edit = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_product_edit
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.auto_genera = function () {
            var class_event='event_auto_genera';
            var auto_genera_function=function(){
                $element.find(':input').each(function(){
                    var auto_genera_type=$(this).data('auto_genera_type');
                    var auto_genera_character=$(this).data('auto_genera_character');
                    if(auto_genera_type)
                    {
                        $(this).delorean({ type: auto_genera_type, amount: 1, character: auto_genera_character, tag:  '' }).trigger('change');
                    }
                });
            };
            if(!$element.find('.auto-genera').hasClass(class_event))
            {
                $element.find('.auto-genera').click(function(){
                    auto_genera_function();
                }).addClass(class_event);
            }

        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var debug=plugin.settings.debug;
            if(debug)
            {
                plugin.auto_genera();
                plugin.auto_genera();

            }
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_product_edit = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_product_edit')) {
                var plugin = new $.view_product_edit(this, options);

                $(this).data('view_product_edit', plugin);

            }

        });

    }

})(jQuery);
