//huong dan su dung
/*
 $('.field_change_view').field_change_view();

 field_change_view=$('.field_change_view').data('field_change_view');
 console.log(field_change_view);
 */

// jQuery Plugin for SprFlat admin field_change_view
// Control options and basic function of field_change_view
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.field_change_view = function(element, options) {

        // plugin's default options
        var defaults = {
            show_popup_control:false,
            menu_item_id:0
            //main color scheme for field_change_view
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var show_popup_control=plugin.settings.show_popup_control;
            if(show_popup_control) {
                document.title = 'change view menu item';
            }
            $element.find('#collapseTypes').on('shown', function (event) {

            });
            $element.find('.save-view-config').click(function(){
                var choose_type=plugin.settings.choose_type;

                if(typeof choose_type!=="undefined")
                {
                    var config=choose_type.attr('data-seleted');
                    ajax_web_design = $.ajax({
                        type: "GET",
                        dataType: "json",
                        cache: false,
                        url: this_host + '/index.php',
                        data: (function () {
                            dataPost = {
                                enable_load_component:1,
                                option: 'com_menus',
                                task: 'item.save_config_link',
                                config: config,
                                menu_item_id: plugin.settings.menu_item_id,

                            };
                            return dataPost;
                        })(),
                        beforeSend: function () {
                            $('.div-loading').css({
                                display: "block"


                            });
                            // $('.loading').popup();
                        },
                        success: function (response) {
                            $('.div-loading').css({
                                display: "none"


                            });
                            if(response.e==1)
                            {
                                alert(response.m);
                            }else
                            {
                                alert(response.m);
                            }
                        }
                    });

                }else{
                    alert('please select choose type');
                }


            });
            $element.find('.choose_type').click(function(){
                $element.find('.choose_type').removeClass('selected');
                var choose_type=$(':focus');
                choose_type.addClass('selected');
                plugin.settings.choose_type = choose_type;
            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.field_change_view = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('field_change_view')) {
                var plugin = new $.field_change_view(this, options);

                $(this).data('field_change_view', plugin);

            }

        });

    }

})(jQuery);
