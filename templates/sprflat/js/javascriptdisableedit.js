jQuery(document).ready(function ($) {
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
                    editingWebsiteState: 1

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

                var grid = $('.grid-stack').data('gridstack');
                grid.remove_all();

                response = $.parseJSON(response);
                for (i = 0; i < response.length; i++) {
                    item = $(response[i]);
                    id = item.attr('data-position-id');
                    gs_x = item.attr('data-gs-x');
                    gs_y = item.attr('data-gs-y');
                    width = item.attr('data-gs-width');
                    height = item.attr('data-gs-height');
                    grid.add_widget(response[i], gs_x, gs_y, width, height, false);

                }
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
                /*
                 $( "#dialog_show_view" ).dialog({
                 width:800,
                 modal:true,
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
                 $(this).remove();
                 }
                 });
                 */


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