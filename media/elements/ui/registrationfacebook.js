(function ($) {

    // here we go!
    $.ui_registrationfacebook = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            list_function_run_befor_submit:[],
            button_state:'',
            option:'',
            task:'',
            redirect_uri:'',
            block_id:0,
            input:[]
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
            $element.popupWindow({
                windowURL:url_root+'index.php?option=com_utility&view=utility&layout=loginfacebook1&tmpl=component',
                scrollbars:1,
                windowName:'Login facebook',
                centerBrowser:1,
                width:'1200',
                height:'800'
            });




        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_registrationfacebook = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_registrationfacebook')) {
                var plugin = new $.ui_registrationfacebook(this, options);
                $(this).data('ui_registrationfacebook', plugin);

            }

        });

    }

})(jQuery);



jQuery(document).ready(function($){

   /* element_ui_registrationfacebook=$.extend({
        list_function_run_befor_submit:new Array(),
        method_submit:'get',

        init_ui_registrationfacebook:function()
        {

        },
        update_text:function(self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            data_text=properties.find('input[name="jform[params][data][text]"]');
            if(data_text.val().trim()=='')
                $('button[data-block-id="'+block_id+'"]').html(self.val());
        }
    }, element_ui_element);
    //$(document).on('click','button.block-item',function(){
    //    element_ui_registrationfacebook.resizable($(this));
    //});
    $('.block-item.block-item-button[link-to-page!="0"]').each(function(){
        item_id=$(this).attr('link-to-page');
        $(this).click(function(){
            window.location.href=this_host+'?Itemid='+item_id;
        });
    });*/






});