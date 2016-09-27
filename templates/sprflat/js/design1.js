jQuery(document).ready(function ($) {
    window.name = 'main_window';
    $('.rebuild_root_block').bind('click',function(){

        Joomla.design_website.rebuild_root_block();



    });







;
    setShowControl();






    var ajaxUpdateColumn;
    var ajaxUpdateColumns;
    ;
    var listBlock = {};


    var listBlockWhenResize = {};

    optionsGridIndex.updateColumns = updateColumns;
    optionsGridIndex.changeSizeGridParent = changeSizeGridParent;

    //createGridStack();

    var ajaxInsertColumn;



    //CKEDITOR.disableAutoInline = true;
    //CKEDITOR.inline( 'editor1' );

    //$(".module-custom-html").popline({position: "fixed"});


    var ajaxSaveModuleContent;
    /*var ajaxSaveModuleContent;
     $('.module-custom-html').on('contentChange', function(e) {
     var content = e.originalEvent.detail.content;
     currentTarget= e.currentTarget;
     module=$(currentTarget).closest('.module-content');
     module_id=module.attr('data-module-id');
     if(ajaxSaveModuleContent !== null && typeof ajaxSaveModuleContent === 'object'){

     ajaxSaveModuleContent.abort();
     }
     //console.log(listPositionSetting);
     ajaxSaveModuleContent=$.ajax({
     type: "GET",
     url: this_host+'/index.php',
     data: (function () {
     dataPost = {
     option: 'com_modules',
     task: 'module.aJaxSaveContent',
     module_id: module_id,
     content:content

     };
     return dataPost;
     })(),
     beforeSend: function () {


     // $('.loading').popup();
     },
     success: function (response) {



     }
     });


     });*/

    var ajaxUpdateRows;


    createSortableAddOn($('.list-add-one'));


;
    createShowElementTypeWhenHoverControlItem();



    var ajaxInsertRow;







    // ondragstart(event, ui)



    // ondragstop(event, ui)




    // onresizestart(event, ui)




    // onresizestop(event, ui)




    $(".item-element").draggable({
        appendTo: 'body',
        helper: "clone"
        /* revert:true,
         proxy:'clone'*/
    });
    createDroppable($(".grid-stack-item .grid-stack-item-content"));
    createDroppable($(".enable-create-drop-element"));


    var ajaxRederModuel;


    var ajaxRederDataSource;




    function reloadPage() {

    }


    function resizeGrid(gridItem) {
        /*     height = gridItem.find('.grid-stack-item-content .position-content').height();

         height = height / 80 + 2;
         var grid = $('.grid-stack').data('gridstack');
         grid.resize(gridItem, null, height);*/
    }

    $('#hide_module_item_setting').on('switchChange.bootstrapSwitch', function (event, state) {

        hideSettingPanel(state);

    });


    hideSetting = $('input[name="hide_setting"]').is(':checked');
    hideSettingPanel(hideSetting);

    $(document).on('click', '.remove-element', function () {
        if (confirm('Are you sure you want remove element ?')) {
            remove_element($(this));
        } else {
            return;
        }


    });
    var ajaxRemoveElement;



    $(document).on('click', '.remove-column', function () {
        if (confirm('Are you sure you want remove column ?')) {
            remove_column($(this));
        } else {
            return;
        }


    });

    $(document).on('click', '.add_widget', function () {

        add_block()

    });

    $(document).on('click', '#save-position', function (index) {
        if (typeof ajaxSaveColumn !== 'undefined') {

            ajaxSaveColumn.abort();
        }
        saveColumn();


    });

    $(document).on('change', '.disable_widget', function () {

        val = !$(this).is(':checked');

        disable_widget(val);
    });
    $(document).on('change', 'input[name="editing"]', function () {

        val = !$(this).is(':checked');

        hideSettingPanel(val);
    });
    $('#disable_widget').on('switchChange.bootstrapSwitch', function (event, state) {

        disable_widget(!state);

    });

    var hiden_position_setting = true;
    $('#hide_setting').on('switchChange.bootstrapSwitch', function (event, state) {

        hiden_position_setting = state;
        if (hiden_position_setting) {
            $('.module-setting').css({
                display: "none"
            });
        }
    });


    $(".scroll-div-screen-size").scroll(function () {
        $(".panel-screen-size .panel-body")
            .scrollLeft($(".scroll-div-screen-size").scrollLeft());
    });
    $(".panel-screen-size .panel-body").scroll(function () {
        $(".scroll-div-screen-size")
            .scrollLeft($(".panel-screen-size .panel-body").scrollLeft());
    });

    $(document).on('scroll', '.scroll-div-screen-size', function (event) {

    });
    var ajaxDeleteModule;


    var isLoadedModuleStyle = 0;

    $(document).on('click', '.apanel-setting-module-item .panel-setting', function () {
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    enable_load_component:1,
                    option: 'com_website',
                    task: 'modulestyle.AjaxGetModuleStyle',
                    isLoadedModuleStyle: isLoadedModuleStyle

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
                sethtmlfortag(response);
                $("#dialog_show_view").dialog({
                    width: 300,
                    modal: true,
                    buttons: {

                        close: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).hide();
                    }
                });
                isLoadedModuleStyle = 1;
            }
        });


    });
    $('#disable_border_module').on('switchChange.bootstrapSwitch', function (event, state) {

        if (state) {
            $('.grid-stack-item .grid-stack-item-content, .grid-stack-item .placeholder-content').css({
                "border": "1px solid " + $('#colorpickerFieldSelect').val(),
                "margin": "-1px"
            });


        } else {
            $('.grid-stack-item .grid-stack-item-content, .grid-stack-item .placeholder-content').css({
                "border": "none",
                "margin": 0
            });

        }
    });


    var heightScreenSize = 0;
    $('#full_height').on('switchChange.bootstrapSwitch', function (event, state) {
        screen_size_id = $('select[name="screen_size_id"] option:selected').val();
        screen_size_id = screen_size_id.toString();
        screen_size_id = screen_size_id.toLowerCase();
        screen_size_id = screen_size_id.split('x');
        heightScreenSize = screen_size_id[1];

        web_design = $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_users',
                    enable_load_component:1,
                    task: 'user.ajax_set_key_of_params',
                    key_params:'option.webdesign.full_height_state',
                    value_key_params:state

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
                if (state) {
                    $('.screen-layout').css({
                        "height": "auto",
                        "overflow-y": "hidden"
                    });
                } else {
                    $('.screen-layout').css({
                        "height": heightScreenSize,
                        "overflow-y": "scroll"
                    });
                }


            }
        });




    });
    //disableEditWidget=$('.disable_widget').is(':checked');

    //disable_widget(disableEditWidget);

    $(document).on('dblclick', '.custom.module-custom-html', function () {

        js_ckfinder = '/ckfinder/ckfinder.js';
        if (!$('script[src="' + this_host + js_ckfinder + '"]').length) {
            $('head').append('<script src="' + this_host + js_ckfinder + '" type="text/javascript"></script>');
        }

        js_ckeditor = '/media/editors/ckeditor/ckeditor.js';
        if (!$('script[src="' + this_host + js_ckeditor + '"]').length) {
            $('head').append('<script src="' + this_host + js_ckeditor + '" type="text/javascript"></script>');
        }

        js_adapters_jquery = '/media/editors/ckeditor/adapters/jquery.js';
        if (!$('script[src="' + this_host + js_adapters_jquery + '"]').length) {
            $('head').append('<script src="' + this_host + js_adapters_jquery + '" type="text/javascript"></script>');
        }
        $(this).attr('contenteditable', true);
        $(this).ckeditor();

    });

    var screenClass = "";
    $(document).on('change', '.smart-phone', function () {
        selected = $(this).val();
        changeLayout(selected);

    });
    var thisGridstackSite = undefined;
    showRowAvaible();



    changeBackground(currentScreenSizeEditing);

    $(document).on('click', '.website_properties', function () {
        ajax_web_design=$.ajax({
            type: "GET",
            dataType: "json",
            cache: false,
            url: this_host+'/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_menus',
                    view: 'item',
                    tmpl:'ajax_json',
                    layout:'default',
                    tpl:'config'

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
                if(!$('.menu-item-config').length) {
                    html = $('<div class="panel element panel-primary menu-item-config  panelMove toggle panelRefresh panelClose"  >' +
                        '<div class="panel-heading menu-item-handle">' +
                        '<h4 class="panel-title">menu-item manager</h4>' +

                        '</div>' +
                        '<div class="panel-body menu-item"></div>' +
                        '<div class="panel-footer menu-item-handle-footer">' +
                        '<button class="btn btn-danger save-block-property pull-right" onclick="view_config_menu_item.save_and_close(self)" ><i class="fa-save"></i>Save&close</button>&nbsp;&nbsp;' +
                        '<button class="btn btn-danger apply-block-property pull-right" onclick="view_config_menu_item.save(self)" ><i class="fa-save"></i>Save</button>&nbsp;&nbsp;' +
                        '<button class="btn btn-danger cancel-block-property pull-right" onclick="view_config_menu_item.cancel(self)"><i class="fa-save"></i>Cancel</button>' +
                        '</div>'+
                        '</div>'
                    );
                    $('body').prepend(html);

                    html.draggable({
                        handle: '.menu-item-handle,.menu-item-handle-footer'
                    });
                }
                Joomla.sethtmlfortag1(response);



            }
        });

    });
    $(document).on('click', '.calculator', function () {

        max_data_gs_x = 0;
        total_data_gs_width = 0;

        max_data_gs_y = 0;
        total_data_gs_height = 0;

        $('.grid-stack-item.ui-draggable').each(function (index) {
            data_gs_x = $(this).attr('data-gs-x');
            data_gs_width = $(this).attr('data-gs-width');
            data_gs_x = parseInt(data_gs_x);

            data_gs_width = parseInt(data_gs_width);
            total_data_gs_width += data_gs_width;
            if (max_data_gs_x < data_gs_x) {
                max_data_gs_x = data_gs_x;
            }

            data_gs_y = $(this).attr('data-gs-y');
            data_gs_height = $(this).attr('data-gs-height');
            data_gs_y = parseInt(data_gs_y);
            data_gs_height = parseInt(data_gs_height);
            total_data_gs_height += data_gs_height;
            if (max_data_gs_y < data_gs_y) {
                max_data_gs_y = data_gs_y;
            }
        });


    });
    $(document).on('click', '.turn_off_preview', function () {
        enable_edit_website = 0;
        $('<form>', {
            "id": "form_enable_edit_website",
            "html": '<input type="text" id="enable_edit_website" name="enable_edit_website" value="' + enable_edit_website + '" />' +
            '<input type="text" id="editing_state" name="editing_state" value="0" />',
            "action": document.URL,
            "method": 'post'

        }).appendTo(document.body).submit();
    });
    $(document).on('click', '.change_margin_widget', function () {
        html = '<p>' +
        '<label for="amount">Margin:</label>' +
        '<input type="text" class="margin_widget" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">' +
        '</p>' +
        '<div><div id="slider-range-min"></div></div>';
        $('.dialog_show_view_body').html(html);
        $("#dialog_show_view").dialog({
            width: 800,
            buttons: {
                Yes: function () {
                    // $(obj).removeAttr('onclick');
                    // $(obj).parents('.Parent').remove();

                    $(this).dialog("close");
                },
                No: function () {
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).hide();
            }
        });


        $("#slider-range-min").ionRangeSlider({
            min: 0,
            max: 10,
            from: 0,
            onChange: function (data) {
                fromNumber = data.fromNumber;
                $('.grid-stack .grid-stack-item .grid-stack-item-content').css({
                    "padding": fromNumber.toString() + 'px'
                });
            }
        });


    });

    $(document).on('click', '.change_background', function () {
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {

                dataPost = {
                    enable_load_component:1,
                    option: 'com_website',
                    task: 'background.ChangeBackground'

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
                sethtmlfortag(response);
                $("#dialog_show_view").dialog({
                    width: 800,
                    buttons: {
                        Yes: function () {
                            // $(obj).removeAttr('onclick');
                            // $(obj).parents('.Parent').remove();
                            SaveBackGround();
                            $(this).dialog("close");
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).hide();
                    }
                });

            }
        });

    });

    $(window).bind('resize', function (e) {
        window.resizeEvt;
        $(window).resize(function () {
            clearTimeout(window.resizeEvt);
            if ($(e.target).hasClass('grid-stack-item')) {
                return;
            }
            if (!$('input[name="editing"]').is(':checked')) {
                return;
            }
            if (enableEditWebsite == 1)
                return;
            // Javascript URL redirection

            window.resizeEvt = setTimeout(function () {
                //code to do after window is resized

                $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',
                    data: (function () {
                        var w = window,
                            d = document,
                            e = d.documentElement,
                            g = d.getElementsByTagName('body')[0],
                            x = w.innerWidth || e.clientWidth || g.clientWidth,
                            y = w.innerHeight || e.clientHeight || g.clientHeight;

                        dataPost = {
                            enable_load_component:1,
                            option: 'com_utility',
                            task: 'utility.aJaxChangeScreenSize',
                            screenSize: x.toString() + "X" + y.toString()

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


                    }
                });

            }, 250);
        });
    });

    $(document).on('click', '.btn-module-setting', function () {

        $("#dialog").dialog(
            {
                modal: true,
                resizable: true,
                open: function (event, ui) {
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {
                            dataPost = {
                                enable_load_component:1,
                                option: 'com_modules',
                                task: 'module.aJaxGetOptionModule',
                                id: 10

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

                            sethtmlfortag(response);
                        }
                    });

                }

            }
        );

    });

    //$('.dd').nestable({ /* config options */ });
    $(document).on('change', 'input[name="hide_module_item_setting"]', function () {
        hideSettingModuleItem = $(this).is(':checked');
        hideSettingPanelModule(hideSettingModuleItem);
    });
    hideSettingModuleItem = $('input[name="hide_module_item_setting"]').is(':checked');
    hideSettingPanelModule(hideSettingModuleItem);
    function hideSettingPanelModule(hideSetting) {
        if (hideSetting) {
            $('.panel-setting-module-item > .panel-heading').hide();
        }
        else {
            $('.panel-setting-module-item > .panel-heading').show();
        }
    }

    $(document).on('click', '.btn-module-remove', function () {
        var grid = $('.grid-stack').data('gridstack');
        module_setting_item = $(this).closest('.module-setting');
        data_position = module_setting_item.attr('data-position');
        grid_stack_item = $('.grid-stack-item[data-position="' + data_position + '"]');
        module_setting_item.hide();

        grid.remove_widget(grid_stack_item);
        if (typeof ajaxSaveColumn !== 'undefined') {
            ajaxSaveColumn.abort();
        }
        saveColumn();


    });
    $(document).on('click', '.position-remove', function () {
        data_position = $('.postion-header-setting').attr('data-position');
        var grid = $('.grid-stack').data('gridstack');
        grid_stack_item = $('.grid-stack').find('.grid-stack-item[data-position="' + data_position + '"]:not(.postion-header-setting)');

        grid.remove_widget(grid_stack_item);


    });
    /* $('.screen-layout .grid-stack-item').each(function(){
     el=$(this);
     height=$(this).find('.grid-stack-item-content .position-content').height();
     height=height/80+2;
     var grid = $('.grid-stack').data('gridstack');
     grid.resize(el, null,height);

     });*/

    $('#joyRideTipContent').joyride({
        autoStart: true,
        postStepCallback: function (index, tip) {

            if (index == 2) {

                $(this).joyride('set_li', false, 1);
            }
        },
        modal: true,
        expose: true
    });
    setWidthScrollScreenSize();
    function setWidthScrollScreenSize() {
        screen_layout_width = $('.iframelive').outerWidth();
        //screen_layout_width=1280;

        $('.scroll-div-screen-size .scroll-div1').width(screen_layout_width);
    }

});