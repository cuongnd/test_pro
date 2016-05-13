//huong dan su dung
/*
 $('.field_selectextension').field_selectextension();

 field_selectextension=$('.field_selectextension').data('field_selectextension');
 console.log(field_selectextension);
 */

// jQuery Plugin for SprFlat admin field_selectextension
// Control options and basic function of field_selectextension
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_selectextension = function(element, options) {

        // plugin's default options
        var defaults = {
            extension_type:"",
            extension:{
                id:0,
                name:'',
                extension_id:0
            },
            element_name:'',
            element_id:'',
            list_extension:[]
            //main color scheme for field_selectextension
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.update_extension = function () {
            var list_extension=plugin.settings.list_extension;
            var extension=plugin.settings.extension;
            var element_name=plugin.settings.element_name;
            var $select_list_extension=$element.find('select[name="'+element_name+'"]');
            $select_list_extension.empty();
            var $option = '<option value="">please select extension</option>';
            $select_list_extension.append($option);
            $.each(list_extension, function (index, item) {
                var $option = '<option  '+(item.id==extension.extension_id?' selected ':'') +' value="' + item.id + '">' + item.element + '</option>';
                $select_list_extension.append($option);
            });
            $select_list_extension.trigger('change');

        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var element_name=plugin.settings.element_name;
            $element.find('select[name="'+element_name+'"]').select2({
                width: 'resolve'
            });
            $element.find('select.list-website').select2({
                width: 'resolve'
            }).change(function(){





                var option_click= {
                    option: 'com_supperadmin',
                    task: 'extension.ajax_get_list_extension_by_website_and_type'

                };

                option_click= $.param(option_click);
                var data_submit={};
                data_submit.extension_type=plugin.settings.extension_type;
                data_submit.website_id=$(this).val();
                $.ajax({
                    contentType: 'application/json',
                    type: "POST",
                    dataType: "json",
                    url: this_host+'/index.php?'+option_click,
                    data: JSON.stringify(data_submit),
                    beforeSend: function () {
                        $('.div-loading').css({
                            display: "block"


                        });
                    },
                    success: function (list_extension) {
                        $('.div-loading').css({
                            display: "none"


                        });
                        plugin.settings.list_extension=list_extension;
                        plugin.update_extension();



                    }
                });

            });
        };

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_selectextension = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_selectextension')) {
                var plugin = new $.field_selectextension(this, options);

                 $(this).data('field_selectextension', plugin);

            }

        });

    }

})(jQuery);
