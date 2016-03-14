//huong dan su dung
/*
 $('.website_core').website_core();

 website_core=$('.website_core').data('website_core');
 console.log(website_core);
 */

// jQuery Plugin for SprFlat admin website_core
// Control options and basic function of website_core
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.website_core = function (element, options) {

        // plugin's default options
        var defaults = {
                option:'',
                view:''
            //main color scheme for website_core
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.set_event_task = function (self) {
            var option= plugin.settings.option;
            var view= plugin.settings.view;
            var $form = self.closest('form');
            var task = self.data('jtask');
            task=task.split('.');
            var controller=task[0];
            var task=task[1];
            $('<input type="hidden" name="option" value="'+option+'">').appendTo($form);
            $('<input type="hidden" name="view" value="'+view+'">').appendTo($form);
            $('<input type="hidden" name="controller" value="'+controller+'">').appendTo($form);
            $('<input type="hidden" name="task" value="'+task+'">').appendTo($form);
            $form.submit();

        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('[data-jtask]').click(function () {
                plugin.set_event_task($(this));
            });
        }

        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.website_core = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('website_core')) {
                var plugin = new $.website_core(this, options);
                $(this).data('website_core', plugin);

            }

        });

    }

})(jQuery);
