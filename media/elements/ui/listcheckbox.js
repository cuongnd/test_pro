(function ($) {

    // here we go!
    $.ui_listcheckbox = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:false,
            block_id:0,
            trigger_change:[]
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


        }
        plugin.update_data_by_key=function(list_key_value){
            block_id= plugin.settings.block_id;
            if (typeof ajax_update_list_check_box !== 'undefined') {
                ajax_update_list_check_box.abort();
            }
            dataPost=list_key_value;
            ajax_update_list_check_box=$.ajax({
                contentType: 'application/json',
                type: "POST",
                dataType: "json",
                url: this_host+'/index.php?option=com_utility&task=block.ajax_update_block&block_id='+block_id,
                data: JSON.stringify(dataPost),
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
                    plugin.update(response);
                }
            });

        }
        plugin.update=function(response)
        {
            $result=$(response.r);
            $element.html($result.html());

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_listcheckbox = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_listcheckbox')) {
                var plugin = new $.ui_listcheckbox(this, options);
                $(this).data('ui_listcheckbox', plugin);

            }

        });

    }

})(jQuery);










jQuery(document).ready(function($){

    element_ui_listcheckbox={
        init_ui_listcheckbox:function(){
            Joomla_post.list_function_run_befor_submit.push(element_ui_listcheckbox.update_data);
            $(".block-item.block-item-listcheckbox").element_list_checkbox();
        },
        update_data:function(data_submit){
            $(".block-item.block-item-listcheckbox").each(function(){
                self=$(this);
                checkbox_checked=self.find('input.block-item.block-item-listcheckbox-item[type="checkbox"]:checked');

                if(checkbox_checked.length)
                {
                    checkbox_checked.each(function(index){
                        this_checkbox=$(this);
                        checkbox_name=this_checkbox.attr('name');
                        if(typeof data_submit[checkbox_name]=='undefined')
                        {
                            data_submit[checkbox_name]=new Array();
                        }

                        data_submit[checkbox_name].push(this_checkbox.val());
                    });

                }
            });
            return data_submit;
        }

    };
    element_ui_listcheckbox.init_ui_listcheckbox();


});