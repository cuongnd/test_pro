(function ($) {

    // here we go!
    $.javascriptdisableedit = function (element, options) {

        // plugin's default options
        var defaults = {
            maxDepth: 1,
            element_ouput: '',
            list_menu: [],
            list_style: [],
            current_screen_size:'',
            menuItemActiveId:0,
            currentScreenSize:"",
            currentLink:"",
            listPositionsSetting:[]
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.create_animation=function(list_position){

        };
        plugin.set_animation_block = function () {
            var listPositionsSetting=plugin.settings.listPositionsSetting;
            $.each(listPositionsSetting,function(index,position){
                var animation=position.animation;
                var block_id=position.id;
                var parent_id=position.parent_id;
                if(animation!='') {
                    var html_block='*[data-block-parent-id="' + parent_id + '"][data-block-id="' + block_id + '"]';
                    var $block=$('*[data-block-parent-id="' + parent_id + '"][data-block-id="' + block_id + '"]');
                    $block.appear();
                    $(document.body).on('appear', html_block, function(e, $affected) {
                        $(this).addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                            $(this).removeClass('animation');
                            $(this).removeClass('animated');
                        });
                    });




                }
            });
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var currentScreenSize=plugin.settings.currentScreenSize;
            plugin.set_animation_block();
            plugin.reload_page_when_screen_size_null(currentScreenSize);
            plugin.set_current_screen_size();
            this.check_is_loaded_position();
            var uri_current_link = $.url(currentLink);
            var Itemid=uri_current_link.data.param.query.Itemid;
            if(typeof Itemid!='undefined')
            {
                uri_current_link.data.param.query.Itemid=menuItemActiveId;
            }
            console.log(uri_current_link);

            $('.edit_website').sidr({
                timing: 'ease-in-out',
                speed: 500,
                side:'right'
            });

            $(document).bind('keypress', function(event) {
                //shift+q
                if( event.which === 81 && event.shiftKey ) {
                    plugin.auto_build_less_again();
                    var find = '.css';
                    var reg_find = new RegExp(find, 'g');
                    var file_source_css = source_less.replace(reg_find, '');
                    var $style=$('style[id*="'+file_source_css+'"][type="text/css"]');
                    $style.remove();

                }
            });



        }
        plugin.reload_page_when_screen_size_null=function(currentScreenSize){
            if(currentScreenSize=="")
            {
                var currentLink=plugin.settings.currentLink;
                var uri_current_link =  new URI(currentLink);

                var w = window,
                    d = document,
                    e = d.documentElement,
                    g = d.getElementsByTagName('body')[0],
                    x = w.innerWidth || e.clientWidth || g.clientWidth,
                    y = w.innerHeight || e.clientHeight || g.clientHeight;

                currentScreenSize= x + 'X' + y;
                uri_current_link.addQuery("screenSize", currentScreenSize);
                //uri_current_link.data.param.query.screenSize=currentScreenSize;
                //window.location.href=uri_current_link.toString();
                console.log(uri_current_link.toString());


            }

        }
        plugin.set_current_screen_size=function(){
            var current_screen_size=plugin.settings.current_screen_size;
            if (current_screen_size == '') {

                var w = window,
                    d = document,
                    e = d.documentElement,
                    g = d.getElementsByTagName('body')[0],
                    x = w.innerWidth || e.clientWidth || g.clientWidth,
                    y = w.innerHeight || e.clientHeight || g.clientHeight;

                plugin.settings.current_screen_size= x + 'X' + y;
            }

        }


        plugin.check_is_loaded_position=function(){
            var menuItemActiveId=plugin.settings.menuItemActiveId;

            var current_screen_size= plugin.settings.current_screen_size;

            if($('.main-container .block-item').length==0)
            {
                web_design = $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: this_host + '/index.php',
                    data: (function () {

                        dataPost = {
                            option: 'com_utility',
                            task: 'blocks.ajax_get_list_block',
                            ajaxgetcontent: 1,
                            Itemid:menuItemActiveId,
                            screenSize:current_screen_size

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


                        }

                    }
                });

            }
        }


        plugin.auto_build_less_again= function () {
            $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.ajaxBuildLess'
                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {

                    plugin.reloadStylesheets('/layouts/website/css/'+source_less);

                }
            });

        }

        plugin.reloadStylesheets=function (href) {
            var queryString = '?reload=' + new Date().getTime();
            //href=href.replace(/\?.*|$/, queryString);
            //console.log(href);
            $('link[rel="stylesheet"][href="'+this_host+href+'"]').remove();
            $('link[rel="stylesheet"][data-source="'+this_host+href+'"]').remove();
            $('head').append('<link href="'+this_host+href.replace(/\?.*|$/, queryString)+'" data-source="'+this_host+href+'" type="text/css" rel="stylesheet">');
        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.javascriptdisableedit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('javascriptdisableedit')) {
                var plugin = new $.javascriptdisableedit(this, options);
                $(this).data('javascriptdisableedit', plugin);

            }

        });

    }

})(jQuery);





jQuery(document).ready(function ($) {

    javascriptdisableedit={
        /**
         * Forces a reload of all stylesheets by appending a unique query string
         * to each stylesheet URL.
         */

    };
    var screenX = 0;
    var sprFlat=$('body').sprFlatFrontEnd();


    function getScreenSize() {
        var w = window,
            d = document,
            e = d.documentElement,
            g = d.getElementsByTagName('body')[0],
            x = w.innerWidth || e.clientWidth || g.clientWidth,
            y = w.innerHeight || e.clientHeight || g.clientHeight;

        screenSize = new Array();
        screenSize[0] = x;
        screenSize[1] = y;
        return screenSize;
    }

    if (currentScreenSize == '') {
        screenSize = getScreenSize();
        screenSizeXY = screenSize[0] + 'X' + screenSize[1];
        screenX = screenSize[0];
        //changeLayout(screenSizeXY);


    }
    var jqxhrLayout;

    function changeLayout(screenSize) {

        jqxhrLayout = $.ajax({
            type: "GET",
            url: currentLink,
            data: (function () {


                dataPost = {
                    ajaxgetcontent: 1,
                    screenSize: screenSize,
                    editingWebsiteState: 0

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


                disableResizableAndMovable();
                changeSizeComponent();

            }
        });
    }

    function createScrollbarPosition() {
        $('.grid-stack-item .grid-stack-item-content').each(function () {
            $(this).mCustomScrollbar({
                theme: "minimal-dark",
                horizontalScroll: false,
                mouseWheelPixels: 1000,
                SCROLLINERTIA: "easeOutCirc"

            });
        });
    }
    function getAvailScreenSize(screenSizeX,listScreenSizeX)
    {
        var screenSizeAvaible=0;
        totalItemScreenSize=listScreenSizeX.length;
        for(var i=0;i<totalItemScreenSize;i++)
        {
            selectScreenSizeX=listScreenSizeX[i];
            selectScreenSizeNextX=listScreenSizeX[i+1];
            console.log('----------------------');
            console.log('i:'+i);
            console.log('screenSizeX:'+screenSizeX);
            console.log('selectScreenSizeX:'+selectScreenSizeX);
            console.log('selectScreenSizeNextX:'+selectScreenSizeNextX);
            console.log('----------------------');
            if(i==0&&screenSizeX<=selectScreenSizeX)
            {
                screenSizeAvaible=selectScreenSizeX;
                break;
            }
            else if(screenSizeX>selectScreenSizeX&&screenSizeX<selectScreenSizeNextX)
            {
                screenSizeAvaible=selectScreenSizeX;
                break;
            }else if(i==totalItemScreenSize-1&&screenSizeX>=selectScreenSizeX)
            {
                screenSizeAvaible=selectScreenSizeX;
                break;
            }
        }
        return screenSizeAvaible;
    }
    var thisGridstackSite=undefined;
    function getCurrentListScreenSize()
    {
        listScreenSizeX=new Array();
        $('.main-container>.row-bootstrap').each(function(){
            data_screensize=$(this).attr('data-screensize');
            data_screensize=data_screensize.toLowerCase();
            array_data_screensize = data_screensize.split("x");
            nowScreenSizeX=array_data_screensize[0];
            var indexOf = listScreenSizeX.indexOf(nowScreenSizeX);
            if(indexOf==-1)
            {
                listScreenSizeX.push(nowScreenSizeX);
            }
        });
        listScreenSizeX.sort(function (a, b) {
            return a - b;
        });
        console.log(listScreenSizeX);
        return listScreenSizeX;
    }
    disableResizableAndMovable();
    function disableResizableAndMovable() {
        screenSize = getScreenSize();
        screenSizeX=screenSize[0];
        listScreenSizeX=getCurrentListScreenSize();
        screenAvail=getAvailScreenSize(screenSizeX,listScreenSizeX);


        $('.main-container>.row-bootstrap').each(function(){
            data_screensize = $(this).attr('data-screensize');
            data_screensize=data_screensize.toLowerCase();
            array_data_screensize = data_screensize.split("x");
            nowScreenSizeX = array_data_screensize[0];
            console.log('nowScreenSizeX:'+nowScreenSizeX+'-screenAvail:'+screenAvail);
            if (nowScreenSizeX != screenAvail) {
                $(this).hide();
            }else
            {
                $(this).show();
            }

        });
    }


    $(document).on('click', '.edit_website', function () {

        return;
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_website',
                    task: 'utility.aJaxCheckEnableEditWebsite'

                };
                return dataPost;
            })(),
            beforeSend: function () {



                // $('.loading').popup();
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.return == 0) {
                    showAjaxFormLogin();
                }
                //sethtmlfortag(response);


            }
        });


        /* enable_edit_website = 1;
         $('<form>', {
         "id": "form_enable_edit_website",
         "html": '<input type="text" id="enable_edit_website" name="enable_edit_website" value="' + enable_edit_website + '" />' +
         '<input type="text" id="editing_state" name="editing_state" value="1" />',
         "action": document.URL,
         "method": 'post'

         }).appendTo(document.body).submit();*/
    });
    function showAjaxFormLogin() {
        console.log(currentLink);
        $.ajax({
            type: "GET",
            url: this_host + '/index.php',
            data: (function () {
                dataPost = {
                    option: 'com_users',
                    task: 'login.aJaxGetFormLogin',
                    currentLink: currentLink

                };
                return dataPost;
            })(),
            beforeSend: function () {
                $("#dialog_show_view").dialog({
                    width: 800,
                    modal: true,
                    buttons: {
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });


                // $('.loading').popup();
            },
            success: function (response) {

                sethtmlfortag(response);


            }
        });

    }

    function sethtmlfortag(respone_array) {
        if (respone_array !== null && typeof respone_array !== 'object')
            respone_array = $.parseJSON(respone_array);
        $.each(respone_array, function (index, respone) {
            if (typeof(respone.type) !== 'undefined') {
                $(respone.key.toString()).val(respone.contents);
            } else {
                $(respone.key.toString()).html(respone.contents);
            }
        });
    }

    $(window).bind('resize', function (e) {
        window.resizeEvt;
        $(window).resize(function () {
            clearTimeout(window.resizeEvt);
            if ($(e.target).hasClass('grid-stack-item')) {
                return;
            }


            // Javascript URL redirection

            window.resizeEvt = setTimeout(function () {
                if (typeof jqxhrLayout !== 'undefined') {
                    jqxhrLayout.abort();
                }
                screenSize = getScreenSize();
                currentScreenX = screenSize[0];
                console.log('screenX:' + screenX + ',currentScreenX:' + currentScreenX);
                if (screenX != currentScreenX) {
                    if (screenX < listScreenSizeX[0])
                        for (var i = 0; i < listScreenSizeX.length; i++) {

                        }

                    screenX = currentScreenX;

                    if ($.inArray(screenX, listScreenSizeX)) {

                    }
                    console.log(listScreenSizeX);
                    screenSizeXY = screenSize[0] + 'X' + screenSize[1];
                    disableResizableAndMovable();
                    //code to do after window is resized
                    //changeLayout(screenSizeXY);
                }
            }, 250);
        });
    });






});