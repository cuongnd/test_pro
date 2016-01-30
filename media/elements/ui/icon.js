// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_icon = function (element, options) {
        // plugin's default options
        var defaults = {
            enable_edit_website:false,
            block_id:0,
            block_parent_id:0,
            icon_size:0,
            icon_color:'#000'
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
            var block_id=plugin.settings.block_id;
            var block_parent_id=plugin.settings.block_parent_id;
            var icon_size=plugin.settings.icon_size;
            var icon_color=plugin.settings.icon_color;
            var enable_edit_website=plugin.settings.enable_edit_website;
            $element.css({
                'font-size':icon_size,
                'color':icon_color
            });
            if(enable_edit_website)
            {
                var $control_element=$element.closest('.control-element.control-element-icon');
                $element.insertBefore($control_element);
                $element.click(function(e){
                    if (e.ctrlKey)
                    {
                        plugin.add_control_element($(this));
                    }else {
                        $( '.config-block[data-block-id="'+block_id+'"]' ).trigger( "click" );
                    }

                });

            }




        }
        plugin.add_control_element=function(self){
            var block_id=plugin.settings.block_id;
            var block_parent_id=plugin.settings.block_parent_id;
            var $control_element=$('.control-element.control-element-icon[data-block-id="'+block_id+'"]');
            var enable_add_control=$control_element.attr('enable-add-control');
            enable_add_control=(typeof enable_add_control=='undefined')?1:enable_add_control;

            if(enable_add_control=="1") {
                $control_element.append($element);
                $control_element.show();
                $control_element.attr('enable-add-control', '0');
            }else{
                $element.insertBefore($control_element);
                $control_element.hide();
                $control_element.attr('enable-add-control', '1');
            }

        }


        plugin.example_function = function (self) {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_icon = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_icon')) {
                var plugin = new $.ui_icon(this, options);
                $(this).data('ui_icon', plugin);

            }

        });

    }

})(jQuery);
