(function () {
    // Localize jQuery variable
    if (typeof jQuery == 'undefined') {
        // jQuery is not loaded
        // alert('jQuery is not loaded');
        var script = document.createElement('script');
        script.type = "text/javascript";
        script.src = "http://code.jquery.com/jquery-1.9.1.js";
        document.getElementsByTagName('head')[0].appendChild(script);
        var script_ui = document.createElement('script');
        script_ui.type = "text/javascript";
        script_ui.src = "http://code.jquery.com/ui/1.10.3/jquery-ui.js";
        document.getElementsByTagName('head')[0].appendChild(script_ui); 
        if (script.readyState && script_ui.readyState) {
            script.onreadystatechange = function () { // For old versions of
                // IE
                if (this.readyState == 'complete' || this.readyState == 'loaded') {
                    scriptLoadHandler();
                }
            };
        } else { // Other browsers
            script.onload = scriptLoadHandler;
        }
    } else if (typeof jQuery.ui === 'undefined') {
        // ui plugin DOES NOT exist
        // alert('ui plugin DOES NOT exist');
        var script_ui = document.createElement('script');
        script_ui.type = "text/javascript";
        script_ui.src = "http://code.jquery.com/ui/1.10.3/jquery-ui.js";
        document.getElementsByTagName('head')[0].appendChild(script_ui);
        

        if (script_ui.readyState) {
            script_ui.onreadystatechange = function () { // For old versions
                // of
                // IE
                if (this.readyState == 'complete' || this.readyState == 'loaded') {
                    scriptLoadHandler();
                }
            };
        } else { // Other browsers
            script_ui.onload = scriptLoadHandler;
        }
    } else {
        jQuery = window.jQuery;
        main();
    } /** ****** Called once jQuery has loaded ***** */

    function scriptLoadHandler() {
        // Restore $ and window.jQuery to their previous values and store the
        // new jQuery in our local jQuery variable
        jQuery = window.jQuery.noConflict(true);
        // Call our main function
        main();
    } /** ****** Our main function ******* */

    function main() {
        var widgetbookpro_css = document.createElement('link');
        widgetbookpro_css.rel = "stylesheet";
        widgetbookpro_css.href = remote_url + "components/com_bookpro/assets/js/widgetbookpro/widgetbookpro.css";
        document.getElementsByTagName('head')[0].appendChild(widgetbookpro_css);


        var ui_css = document.createElement('link');
        ui_css.rel = "stylesheet";
        ui_css.href = "http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css";
        document.getElementsByTagName('head')[0].appendChild(ui_css);                     

		
        var jquery_validate = document.createElement('script');
        jquery_validate.type = "text/javascript";
        jquery_validate.src =remote_url + "components/com_bookpro/assets/js/widgetbookpro/jquery-validate.js";
        document.getElementsByTagName('head')[0].appendChild(jquery_validate);  

        
        var jquery_base64 = document.createElement('script');
        jquery_base64.type = "text/javascript";
        jquery_base64.src =remote_url + "components/com_bookpro/assets/js/widgetbookpro/jquery.base64.js";
        document.getElementsByTagName('head')[0].appendChild(jquery_base64);  
        
        
        jQuery(document).ready(
            function ($) {
                $('<div/>', {
                    id: 'iDivdialog',
                    class:'iDivdialog'
                }).appendTo('#widgetbookpro');

                $('<div/>', {
                    id: 'widgetbookpro-loading',
                    class:'widgetbookpro-loading'
                }).appendTo('#widgetbookpro');
                $('<div/>', {
                    id: 'wap-content',
                    class:'wap-content'
                }).appendTo('#widgetbookpro');

                var a_url = "index.php";
                // the url of the script where we send the
                // asynchronous call
                $.ajax({
                    type: "GET",

                    url: remote_url + a_url,
                    data:{
                        option:'com_bookpro',
                        controller:'widgethotelbooking',
                        task:'showmodulehotelsearch',
                        joombookpro_account_id:joombookpro_account_id
                    },
                    beforeSend: function() {
                        $('#widgetbookpro .widgetbookpro-loading').css({
                            display: "block",
                            "z-index": 1000,
                            height: "150px",
                            width: "150px"
                        });
                        // $('.loading').popup();
                    },
                    crossDomain: true,
                    async: false,
                    dataType: "jsonp",
                    contentType: "application/json",
                    success: function (data) {
                        $('#widgetbookpro .widgetbookpro-loading').css({
                            display:"none"
                        });
                        $('#widgetbookpro #wap-content').html(data);

                    }
                });
        });
    }
})(); // We call our anonymous function immediately