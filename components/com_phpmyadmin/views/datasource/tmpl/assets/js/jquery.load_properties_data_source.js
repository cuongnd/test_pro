//huong dan su dung
/*
 $('.load_properties_data_source').load_properties_data_source();

 load_properties_data_source=$('.load_properties_data_source').data('load_properties_data_source');
 console.log(load_properties_data_source);
 */

// jQuery Plugin for SprFlat admin load_properties_data_source
// Control options and basic function of load_properties_data_source
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.load_properties_data_source = function(element, options) {

        // plugin's default options
        var defaults = {
            show_popup_control:false,
            //main color scheme for load_properties_data_source
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.save_data_property_data_source = function (self) {

            if(typeof ajaxSavePropertyDataSource !== 'undefined'){
                ajaxSavePropertyDataSource.abort();
            }
            post=$element.find('select,textarea, input:not([readonly])').serialize();
            ajaxSavePropertyDataSource=$.ajax({
                type: "POST",
                url: this_host+'/index.php?enable_load_component=1&option=com_phpmyadmin&task=datasource.ajaxSavePropertiesDataSource&screensize='+currentScreenSizeEditing,
                data: post,
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
                    alert('save successful');
                    //Joomla.sethtmlfortag(response);
                }
            });
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var show_popup_control=plugin.settings.show_popup_control;
            if(show_popup_control) {
                document.title = 'data source properties';
            }
            $element.find('.getFieldType').click(function(){
                plugin.getFieldTypeOfDataSource($(this));
            });

            $element.find('.save-data-source-properties').click(function(){
                plugin.save_data_property_data_source($(this));
            });
        }
        plugin.getFieldTypeOfDataSource=function(self){
            var show_popup_control=plugin.settings.show_popup_control;

            var field=self.attr('data-field');
            var add_on_id=$('input[name="jform[id]"]').val();
            if(show_popup_control)
            {
                $.open_popup_window({
                    scrollbars:1,
                    windowName:'data_source_show_property_data_source',
                    windowURL:'index.php?enable_load_component=1&option=com_phpmyadmin&view=datasource&add_on_id='+add_on_id+'&currenrt_url='+base64.encode(currentLink)+'&ajaxgetcontent=0&field='+field+'&tmpl=field&hide_panel_component=1',
                    centerBrowser:1,
                    width:'1000',
                    menubar:0,
                    scrollbars:1,
                    height:'1000',

                });

            }else{
                var ajaxLoadFieldTypeOfBlock=$.ajax({
                    type: "GET",
                    cache:false,
                    dataType: "json",
                    url: this_host+'/index.php',
                    data: (function () {

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_phpmyadmin',
                            view: 'datasource',
                            tmpl: 'ajax_json',
                            add_on_id:add_on_id,
                            field:field,
                            ajaxgetcontent: 1,
                            currenrt_url:base64.encode(currentLink)
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
                            html = $('<div class="panel itemField panel-primary field-config  panelMove toggle panelRefresh panelClose" data-block-id="'+add_on_id+'" data-block-property="datasource">' +
                                '<div class="panel-heading field-config-heading">' +
                                '<h4 class="panel-title">' + field + '</h4>' +

                                '</div>' +
                                '<div class="panel-body property"  data-block-id="'+add_on_id+'"></div>' +
                                '<div class="panel-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" data-block-id="'+add_on_id+'" type="button"><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" data-block-id="'+add_on_id+'" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" data-block-id="'+add_on_id+'" type="button"><i class="fa-save"></i>Cancel</button>' +
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
                        //sprFlat.panels();



                    }
                });

            }

        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.load_properties_data_source = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('load_properties_data_source')) {
                var plugin = new $.load_properties_data_source(this, options);

                $(this).data('load_properties_data_source', plugin);

            }

        });

    }

})(jQuery);
