(function ($) {

    // here we go!
    $.field_fieldnamebindingsourceselect2 = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            select2_option:{},
            field_name:''


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
            var select2_option=plugin.settings.select2_option;
            var field_name=plugin.settings.field_name;
            $element.find('input[name="'+field_name+'"]').select2(select2_option);


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_fieldnamebindingsourceselect2 = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_fieldnamebindingsourceselect2')) {
                var plugin = new $.field_fieldnamebindingsourceselect2(this, options);
                $(this).data('field_fieldnamebindingsourceselect2', plugin);

            }

        });

    }

})(jQuery);





