(function ($) {

    // here we go!
    $.mod_virtuemart_category_verticalmegamenu = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website: 0,
            module_id: 0,

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
            var loaded=plugin.settings.loaded;
            var module_id=plugin.settings.module_id;
            loaded=true;
            plugin.dcVerticalMegaMenu();
            if(loaded==false)
            {
                plugin.settings.loaded=true;
                var option= {
                    option: 'com_modules',
                    task: 'module.ajax_get_render_module'
                };
                option= $.param(option);
                data_submit={
                    module_id:module_id
                };
                ajax_web_design = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: this_host + '/index.php?'+option,
                    data: JSON.stringify(data_submit),
                    beforeSend: function () {

                    },
                    success: function (response) {

                        plugin.dcVerticalMegaMenu();

                    }
                });


            }



        };

        plugin.dcVerticalMegaMenu=function(){
            $element.find('#mega-1').dcVerticalMegaMenu({
                rowItems: '5',
                speed: 'fast',
                effect: 'fade',
                direction: 'right'
            });

        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.mod_virtuemart_category_verticalmegamenu = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('mod_virtuemart_category_verticalmegamenu')) {
                var plugin = new $.mod_virtuemart_category_verticalmegamenu(this, options);
                $(this).data('mod_virtuemart_category_verticalmegamenu', plugin);

            }

        });

    }

})(jQuery);


