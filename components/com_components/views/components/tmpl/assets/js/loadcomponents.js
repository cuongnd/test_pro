//huong dan su dung
/*
 $('.load_components').load_components();

 load_components=$('.load_components').data('load_components');
 console.log(load_components);
 */

// jQuery Plugin for SprFlat admin load_components
// Control options and basic function of load_components
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.load_components = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for load_components
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.layout_component_config=function(e_taget){
            var type=e_taget.data('type');
            var element_path=e_taget.data('element_path');
            if(type=='config_field_module'){
                var element_path='root_module';
            }
            var id=e_taget.data('id');

            var sprFlat=$('body').data('sprFlat');
            var show_popup_control=sprFlat.settings.show_popup_control;
            if(show_popup_control)
            {
                $.open_popup_window({
                    scrollbars:1,
                    windowName:'main_ralationship',
                    windowURL:'index.php?enable_load_component=1&option=com_modules&view=module&layout=config&id='+id+'&element_path='+element_path+'&tmpl=field&hide_panel_component=1',
                    centerBrowser:1,
                    width:'400',
                    menubar:0,
                    scrollbars:1,
                    height:'600',

                });
            }else {
                ajax_web_design=$.ajax({
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    url: this_host+'/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_modules',
                            view: 'module',
                            tmpl:'ajax_json',
                            layout:'config',
                            id:id,
                            element_path:element_path

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
                        var html='';
                        if(!$('.extension-module-config').length) {
                            html = $('<div class="panel module panel-primary extension-module-config  panelMove toggle panelRefresh panelClose"  >' +
                                '<div class="panel-heading module-handle">' +
                                '<h4 class="panel-title">module manager</h4>' +

                                '</div>' +
                                '<div class="panel-body module"></div>' +
                                '<div class="panel-footer module-handle-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" onclick="view_module_config.save_and_close(self)" ><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" onclick="view_module_config.save(self)" ><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" onclick="view_module_config.cancel(self)"><i class="fa-save"></i>Cancel</button>' +
                                '</div>'+
                                '</div>'
                            );
                            $('body').prepend(html);

                            html.draggable({
                                handle: '.module-handle,.module-handle-footer'
                            });
                        }
                        Joomla.sethtmlfortag1(response);



                    }
                });

            }

        }

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            $element.find(".list-module .item-element").draggable({
                appendTo: 'body',
                helper: "clone"
                /* revert:true,
                 proxy:'clone'*/
            });
            $element.find('.layout-config').click(function(){
                plugin.layout_component_config();
            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.load_components = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('load_components')) {
                var plugin = new $.load_components(this, options);

                $(this).data('load_components', plugin);

            }

        });

    }

})(jQuery);
