(function ($) {

    // here we go!
    $.ui_editor = function (element, options) {

        // plugin's default options
        var defaults = {
            select2_option:{}

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


            var editor_id=$element.attr('id');
            var prev_block_item_editor=editor_id;
            plugin.instance = CKEDITOR.instances[editor_id];
            if (!plugin.instance) {
                CKEDITOR.inline( editor_id );
            }


        }
        plugin.update_data=function(data_submit){
            $.each(CKEDITOR.instances, function( index, editor ) {
                textarea_id=index.toString();
                $('#'+textarea_id).val(editor.getData());

            });
            $('.block-item.block-item-editor').each(function(){
                self=$(this);
                name=self.attr('name');
                data_submit[name]=self.val();
            });
            return data_submit;
        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_editor = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_editor')) {
                var plugin = new $.ui_editor(this, options);
                $(this).data('ui_editor', plugin);

            }

        });

    }

})(jQuery);









