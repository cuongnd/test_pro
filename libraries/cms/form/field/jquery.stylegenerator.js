// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.stylegenerator = function (element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for template
            //be sure to be same as colors on main.css or custom-variables.less
            onchange: function (style) {

            },
            input:''
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
            $('head').append('<style id="dynamic-styles" type="text/css"></style>');
            plugin.updateOnPageLoad();
            $element.hover(function(){
                plugin.settings.first_run=1;
            });
            $element.find('button.config-field-stylegenerator').click(function () {
                var element_path = $(this).data('element_path');
                ajax_web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            view: 'params',
                            tmpl: 'ajax_json',
                            layout: 'config',
                            element_path: element_path

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

                        if (!$('.panel.params-config').length) {
                            html = $(
                                '<div  class="panel params panel-primary params-config  panelMove toggle panelRefresh panelClose"  >' +
                                '<div class="panel-heading params-handle">' +
                                '<h4 class="panel-title">params manager</h4>' +
                                '</div>' +
                                '<div class="panel-body params"></div>' +
                                '<div class="panel-footer params-handle-footer">' +
                                '<button class="btn btn-danger save-block-property pull-right" onclick="params_view_config.save_and_close(self)" ><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger apply-block-property pull-right" onclick="params_view_config.save(self)" ><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                                '<button class="btn btn-danger cancel-block-property pull-right" onclick="params_view_config.cancel(self)"><i class="fa-save"></i>Cancel</button>' +
                                '</div>' +
                                '</div>'
                            );
                            $('body').prepend(html);

                            html.css({
                                position: 'absolute'

                            }).draggable({
                                handle: '.params-handle,.params-handle-footer'
                            });
                        }
                        Joomla.sethtmlfortag1(response);


                    }
                });

            });


            /**
             * Accordion functionality
             */
;

            // click a "more" link

        }
        plugin.convert_object_style_to_style = function (list_string_syle, list_style, level, max_level) {
            if (level < max_level) {
                $.each(list_style, function (key, item) {

                    if (typeof item === 'object') {
                        plugin.convert_object_style_to_style(list_string_syle, item, level++, max_level);
                    } else {
                        list_string_syle[key] = item;
                    }
                });
            }
        }
        plugin.updateOnPageLoad=function(){
            $element.find('select,textarea,input').each(function () {

                var name = $(this).attr('name');
                if (typeof name !== "undefined") {
                    name = name.toLowerCase();
                    $(this).attr('name', name);
                }
            });
            var list_style = $element.find('select,textarea, input').serializeObject();
            var input_var= JSON.stringify(list_style);
            input_var= base64.encode(input_var);
            $(plugin.settings.input).val(input_var);

            var list_string_syle = {};
            console.log(list_style);
            plugin.convert_object_style_to_style(list_string_syle, list_style.jform.params, 0, 999);


            list_string_syle1={};
            $.each(list_string_syle, function (key, item) {
                var string_enable=key.substring(0, 6);
                if(string_enable=='enable')
                {
                    var push_key=key.substring(7);
                    list_string_syle1[push_key]=list_string_syle[push_key];
                }

            });
            // build the CSS string
            if (typeof web_design !== 'undefined') {
                web_design.abort();
            }

            //console.log(listPositionSetting);
            dataPost={
                list_style:list_string_syle1
            };
            web_design = $.ajax({
                type: "POST",
                dataType: "json",
                contentType: 'application/json',
                url: this_host + '/index.php?option=com_utility&task=utility.ajax_get_style',
                data: JSON.stringify(dataPost),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {
                    var list_syle=response;
                    var styles='';
                    var none_hover='';
                    var hover=[];
                    //--------------------------
                    styles+='<div class="code-row">.demo_button{</div>';

                    $.each(list_syle.none_hover, function (key, value) {

                        styles+='<div class="code-row">     ' + key+':'+value + ';</div>';
                    });
                    styles+='<div class="code-row">}</div>';


                    styles+='<div class="code-row">.demo_button:hover{</div>';

                    $.each(list_syle.hover, function (key, value) {

                        styles+='<div class="code-row">     ' + key+':'+value + ';</div>';
                    });
                    styles+='<div class="code-row">}</div>';

                    $('#css-display').html('<pre>' + styles + '</pre>');
                    styles=$('#css-display').text();
                    var styles = '<style id="dynamic-styles" type="text/css">' + styles + '</style>';
                    // replace the head styles
                    $('#dynamic-styles').replaceWith(styles);
                    styles=$('#css-display').text();
                    // update the styles code view
                    plugin.settings.onchange(styles);

                }
            });
        }
        plugin.updateStyles = function () {
            console.log(plugin.settings.first_run);
            if(plugin.settings.first_run==1)
            {
                plugin.updateOnPageLoad();
            }


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.stylegenerator = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('stylegenerator')) {
                var plugin = new $.stylegenerator(this, options);
                $(this).data('stylegenerator', plugin);

            }

        });

    }

})(jQuery);
