(function ($) {

    // here we go!
    $.ui_row = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            list_function_run_befor_submit:[],
            button_state:''
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


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_row = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_row')) {
                var plugin = new $.ui_row(this, options);
                $(this).data('ui_row', plugin);

            }

        });

    }

})(jQuery);



