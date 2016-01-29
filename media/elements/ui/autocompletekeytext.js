(function ($) {

    // here we go!
    $.ui_autocompletekeytext = function (element, options) {

        // plugin's default options
        var defaults = {
            select2_option:{

            }

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({},defaults,options );


            select2_option=plugin.settings.select2_option;

            enable_template=plugin.settings.enable_template;
            enable_template_selected=plugin.settings.enable_template_selected;
            min_width =plugin.settings.min_width;
            key =plugin.settings.key;
            value =plugin.settings.value;
            select2_option.dropdownCss={};
            if(min_width!='' )
            {
                select2_option.dropdownCss['min-width']=min_width;
            }else{
                select2_option.dropdownCss['min-width']='500px';
            }
            if(enable_template==1)
            {
                select2_option.formatResult=function(item) {
                    item=$.tree_object(item);
                    template = plugin.settings.template;
                    if(typeof item!=="undefined") {
                        $.each(item, function (key, value) {

                            var find = '#:' + key + '#';
                            template = template.replace(new RegExp(find, 'g'), value);
                        });
                        var find = '#:(.*?)/#';
                        template = template.replace(new RegExp(find, 'g'), '');
                    }
                    return template;
                };
            }else{
                select2_option.formatResult=function(item) {
                    return item[key];
                }
            }
            if(enable_template_selected==1)
            {
                select2_option.formatSelection= function(item) {
                    item=$.tree_object(item);
                    template_selected = plugin.settings.template_selected;
                    if(typeof item!=="undefined") {
                        $.each(item, function (key, value) {

                            var find = '#:' + key + '#';
                            template_selected = template_selected.replace(new RegExp(find, 'g'), value);
                        });
                        var find = '#:(.*?)/#';
                        template_selected = template_selected.replace(new RegExp(find, 'g'), '');
                    }
                    return template_selected;
                }
            }else{
                select2_option.formatSelection= function(item) {
                    return item[value]
                }
            }


            $element.select2(select2_option);
        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_autocompletekeytext = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_autocompletekeytext')) {
                var plugin = new $.ui_autocompletekeytext(this, options);
                $(this).data('ui_autocompletekeytext', plugin);

            }

        });

    }

})(jQuery);





