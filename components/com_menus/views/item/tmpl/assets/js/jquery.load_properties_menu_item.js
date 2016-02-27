//huong dan su dung
/*
 $('.load_properties_menu_item').load_properties_menu_item();

 load_properties_menu_item=$('.load_properties_menu_item').data('load_properties_menu_item');
 console.log(load_properties_menu_item);
 */

// jQuery Plugin for SprFlat admin load_properties_menu_item
// Control options and basic function of load_properties_menu_item
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.load_properties_menu_item = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for load_properties_menu_item
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
            document.title = 'properties-menu-item';

            $element.find('.getFieldType').click(function(){
                plugin.getFieldTypeOfComponent($(this));
            });

        }
        plugin.getFieldTypeOfComponent=function(self){
            var show_popup_control=plugin.settings.show_popup_control;
            var field=self.attr('data-field');
            var menu_id=$element.find('input[name="jform[id]"]').val();

            if(show_popup_control)
            {
                $.open_popup_window({
                    scrollbars:1,
                    windowName:'menu item properties field edit',
                    windowURL:'index.php?enable_load_component=1&option=com_menus&view=item&&layout=field&id='+menu_id+'&ajaxgetcontent=0&field='+field+'&tmpl=field&hide_panel_component=1',
                    centerBrowser:1,
                    width:'1000',
                    menubar:0,
                    scrollbars:1,
                    height:'1000',

                });

            }else{
                ajaxLoadFieldTypeOfBlock=$.ajax({
                    type: "POST",
                    dataType: "json",
                    url: this_host+'/index.php',
                    data: (function () {
                        dataPost = {
                            enable_load_component:1,
                            option: 'com_menus',
                            view: 'item',
                            layout: 'field',
                            tmpl: 'ajax_json',
                            id: menu_id,
                            field: field
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
                        if(!$('.itemField').length) {
                            html = $('<div class="panel itemField panel-primary field-config  panelMove toggle panelRefresh panelClose" data-block-id="'+menu_id+'" data-block-property="component">' +
                                '<div class="panel-heading field-config-heading">' +
                                '<h4 class="panel-title">' + field + '</h4>' +

                                '</div>' +
                                '<div class="panel-body property"  data-block-id="'+menu_id+'"></div>' +
                                '<div class="panel-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" data-block-id="'+menu_id+'" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" data-block-id="'+menu_id+'" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" data-block-id="'+menu_id+'" type="button"><i class="fa-save"></i>Cancel</button>' +
                                '</div>'+
                                '</div>'
                            );
                            $('body').prepend(html);

                            html.draggable({
                                handle: '.field-config-heading'
                            });
                        }
                        $('.itemField .panel-title').html(field);
                        Joomla.sethtmlfortag1(response);
                        sprFlat=$('body').data('sprFlat');
                        sprFlat.panels();
                        var source= $('.itemField[data-module-id="'+menu_id+'"] .panel-body[data-block-id="'+menu_id+'"]').find('.source');
                        if(source.length) {
                            source.kendoEditor({});
                            var content = source.data('kendoEditor');
                        }



                    }
                });

            }

        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.load_properties_menu_item = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('load_properties_menu_item')) {
                var plugin = new $.load_properties_menu_item(this, options);

                $(this).data('load_properties_menu_item', plugin);

            }

        });

    }

})(jQuery);
