(function ($) {

    // here we go!
    $.ui_birthday = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:false,
            max_character:20,
            ajax_clone:false,
            block_id:0,
            float:'',
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            float=plugin.settings.float;
            ajax_clone=plugin.settings.ajax_clone;
            $element.datetimepicker({
                defaultDate: "11/1/2013",
                disabledDates: [
                    moment("12/25/2013"),
                    new Date(2013, 11 - 1, 21),
                    "11/22/2013 00:53"
                ]
            });





        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_birthday = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_birthday')) {
                var plugin = new $.ui_birthday(this, options);
                $(this).data('ui_birthday', plugin);

            }

        });

    }

})(jQuery);





