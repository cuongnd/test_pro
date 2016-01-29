// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_tabcontent = function (element, options) {




        // plugin's default options
        var defaults = {
            tabcontent_option:{

            }
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
            tabcontent_option=plugin.settings.tabcontent_option;





        }
        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_tabcontent = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_tabcontent')) {
                var plugin = new $.ui_tabcontent(this, options);
                $(this).data('ui_tabcontent', plugin);

            }

        });

    }

})(jQuery);





jQuery(document).ready(function($){

    element_ui_tab_content={
        init_tab_content:function(){
            $('.control-element.control-element-tabcontent .block-item.block-item-tabcontent').each(function(){
                self=$(this);
                self.sortable({
                    handle: ".move-sub-row",
                    axis: "y",
                    items: "> .row-content.block-item",
                    stop: function (event, ui) {
                        var screenSize = $('select[name="smart_phone"] option:selected').val();
                        screenSize = screenSize.toLowerCase();
                        var list_row = {};
                        $('.block-item.block-item-tabcontent[data-block-id="' + ui.item.attr('data-block-parent-id') + '"] > .row-content.show-grid-stack-item:visible').each(function (index) {

                            list_row[$(this).attr('data-block-id')] = {
                                ordering: index,
                                screenSize: screenSize
                            }

                        });

                        if (typeof ajax_web_design !== 'undefined') {
                            ajax_web_design.abort();
                        }
                        ajax_web_design = $.ajax({
                            type: "GET",
                            url: this_host + '/index.php',
                            data: (function () {

                                dataPost = {
                                    option: 'com_utility',
                                    task: 'utility.aJaxUpdateRowsInScreen',
                                    listRow: list_row,
                                    menuItemActiveId: menuItemActiveId

                                };
                                return dataPost;
                            })(),
                            beforeSend: function () {

                                // $('.loading').popup();
                            },
                            success: function (response) {


                            }
                        });


                    }

                });
            });

        }
    }
});