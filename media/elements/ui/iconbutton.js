(function ($) {

    // here we go!
    $.ui_button = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            list_function_run_befor_submit:[],
            button_state:'',
            option:'',
            task:'',
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
            var button_state= plugin.settings.button_state;
            var input=plugin.settings.input;
            var option=plugin.settings.option;
            var block_id=plugin.settings.block_id;
            var task=plugin.settings.task;
            option_click= {
                option: 'com_phpmyadmin',
                task: 'datasource.ajax_save_data',
                block_id:block_id

            };
            if(option!='')
            {
                option_click.option=option;
            }
            if(task!='')
            {
                option_click.task=task;
            }
            delete input.option;
            delete input.view;
            delete input.task;
            option_click = $.extend({}, input,option_click);
            console.log(option_click);
            option_click= $.param(option_click);
            $element.click(function(e){

                if(plugin.settings.button_state=='close')
                {
                    window.location.href=this_host+'?Itemid='+plugin.settings.link_to_page;
                }
                for(i=0;i<Joomla_post.list_function_run_validate_befor_submit.length;i++)
                {
                    instant_function=Joomla_post.list_function_run_validate_befor_submit[i];
                    if(!instant_function())
                    {
                        return false;
                    }
                }
                data_submit={};
                $.each(Joomla_post.list_function_run_befor_submit,function(index,instant_function){
                    instant_function(data_submit)
                });

                if (typeof ajax_web_design !== 'undefined') {
                    ajax_web_design.abort();
                }

                //option=  $.extend({}, input, option);

                ajax_web_design=$.ajax({
                    contentType: 'application/json',
                    type: "POST",
                    dataType: "json",
                    url: this_host+'/index.php?'+option_click,
                    data: JSON.stringify(data_submit),
                    beforeSend: function () {
                        $('.div-loading').css({
                            display: "block"


                        });
                    },
                    success: function (response) {
                        $('.div-loading').css({
                            display: "none"


                        });
                        if(response.e==0)
                        {
                            alert('Save success');
                            post_name=response.post_name;
                            main_key=response.main_key;
                            value_main_key=response[main_key];
                            var uri = new URI(currentLink);
                            uri.setSearch(post_name, value_main_key);
                            window.location.href = uri.toString();


                        }else if(response.e==1){
                            alert(response.m);
                        }



                    }
                });




            });


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_button = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_button')) {
                var plugin = new $.ui_button(this, options);
                $(this).data('ui_button', plugin);

            }

        });

    }

})(jQuery);



jQuery(document).ready(function($){

   /* element_ui_button=$.extend({
        list_function_run_befor_submit:new Array(),
        method_submit:'get',

        init_ui_button:function()
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
    //    element_ui_button.resizable($(this));
    //});
    $('.block-item.block-item-button[link-to-page!="0"]').each(function(){
        item_id=$(this).attr('link-to-page');
        $(this).click(function(){
            window.location.href=this_host+'?Itemid='+item_id;
        });
    });*/






});