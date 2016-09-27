// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_html = function (element, options) {
        // plugin's default options
        var defaults = {
            primary_key_of_table:"",
            value_primary_key_of_table:0
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
            $element.find('.edit_html_content').dblclick(function(){
                CKEDITOR.config.entities_latin=false;
                CKEDITOR.config.list_var=[];
                CKEDITOR.config.list_menu=[];
                CKEDITOR.config.list_style=[];

                CKEDITOR.config.extraPlugins = 'filebrowser';
                $(this).attr('contenteditable',true);

                $(this).ckeditor();

            });
            $element.find('.save-block-html').click(function(){
                plugin.saveBlockHtml($(this));
            });



        }
        plugin.saveBlockHtml = function (self) {
            var $block_item=self.closest('.control-element.block-item');
            var $edit_html_content=$block_item.find('.edit_html_content');
            var ckeditorInstance=$edit_html_content.data('ckeditorInstance');
            content=ckeditorInstance.getData();
            content= $.base64Encode(content);
            block_id=self.attr('data-block-id');
            ajaxSaveBlockHtml=$.ajax({
                type: "POST",
                dataType: "json",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.ajaxSaveBlockHtml',
                        enable_load_component:1,
                        block_id:block_id,
                        content:content

                    };
                    if(plugin.settings.primary_key_of_table!='')
                    {
                        dataPost[plugin.settings.primary_key_of_table]=plugin.settings.value_primary_key_of_table;
                    }
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {

                    if(response.e==1)
                    {
                        alert(response.m);
                    }else
                    {
                        alert(response.m);

                    }


                }
            });
        };


        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_html = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_html')) {
                var plugin = new $.ui_html(this, options);
                $(this).data('ui_html', plugin);

            }

        });

    }

})(jQuery);
