(function ($) {

    // here we go!
    $.field_editor = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            list_var: [],
            list_menu: [],
            list_style: []
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
            CKEDITOR.config.entities_latin=false;
            CKEDITOR.config.list_var=plugin.settings.list_var;
            CKEDITOR.config.list_menu=plugin.settings.list_menu;
            CKEDITOR.config.list_style=plugin.settings.list_style;
            plugin.ckeditor=$element.find('textarea.editor').ckeditor({


            });
            $('.panel-body.property.block_property').data('update_field',plugin.update_editor);

        }

        plugin.update_editor=function()
        {
            var content=$element.find('textarea.editor').val();
            $.base64.utf8encode = true;
            content= $.base64.encode(content);
            $element.find('input[name="'+plugin.settings.input_name+'"]').val(content);
        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_editor = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_editor')) {
                var plugin = new $.field_editor(this, options);
                $(this).data('field_editor', plugin);

            }

        });

    }

})(jQuery);
