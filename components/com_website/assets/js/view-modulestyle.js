jQuery(document).ready(function($){
    $(document).on('click','.change_style',function(){
        $.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_website',
                    task: 'modulestyle.AjaxGetModuleEditStyle'

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
                $( "#module_dialog_show_view" ).dialog({
                    width:300,
                    modal:true,
                    buttons: {

                        close: function () {
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
    $(document).on('click','.add_animation',function(){
        $.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_website',
                    task: 'modulestyle.AjaxGetModuleEditAnimation'

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
                $( "#module_dialog_show_view" ).dialog({
                    width:300,
                    modal:true,
                    buttons: {

                        close: function () {
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
    $(document).on('click','.edit_style_item',function(){
        $.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_website',
                    task: 'modulestyle.AjaxGetEditStyleItem'

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
                $( "#module_dialog_show_view" ).dialog({
                    width:300,
                    modal:true,
                    buttons: {

                        close: function () {
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

    function SaveBackGround()
    {
        $.ajax({
            type: "GET",
            url: this_host+'/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_website',
                    task: 'background.aJaxSaveBackground',
                    backgroundPath:"url('"+backgroundPath+"')"

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

    }
});
