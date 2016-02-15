(function ($) {

    // here we go!
    $.ui_fillterbutton = function (element, options) {

        // plugin's default options
        var defaults = {
            enable_edit_website:0,
            list_function_run_befor_submit:[],
            fillterbutton_state:'',
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


            var input=plugin.settings.input;
            var option=input.option;
            var view=input.view;
            var Itemid=input.Itemid;
            var block_id=plugin.settings.block_id;
            var form_filter=plugin.settings.form_filter;
            $( '.block-item[data-block-id="'+form_filter+'"]' ).wrap( '<form method="POST"  id="form_'+form_filter+'" action="'+url_root+'index.php?option='+option+'&view='+view+'&Itemid='+Itemid+' " ></form>' );
            $element.click(function(e){
                var config_fillter= plugin.settings.config_fillter;


                if(plugin.settings.fillterbutton_state=='close')
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
                data_submit=[];
                $.each(config_fillter,function(index,item){
                    var post_name=item.post_name;
                    var value_of_item=$('*[name="'+item.post_name+'"]').val();
                    var item_post={};
                    item_post[post_name]=value_of_item;
                    data_submit.push(item_post);
                });

                console.log(data_submit);
                $('#form_'+form_filter).submit();
                if (typeof ajax_web_design !== 'undefined') {
                    ajax_web_design.abort();
                }

                //option=  $.extend({}, input, option);

/*
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
*/




            });


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_fillterbutton = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_fillterbutton')) {
                var plugin = new $.ui_fillterbutton(this, options);
                $(this).data('ui_fillterbutton', plugin);

            }

        });

    }

})(jQuery);



jQuery(document).ready(function($){

   /* element_ui_fillterbutton=$.extend({
        list_function_run_befor_submit:new Array(),
        method_submit:'get',

        init_ui_fillterbutton:function()
        {

        },
        update_text:function(self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            data_text=properties.find('input[name="jform[params][data][text]"]');
            if(data_text.val().trim()=='')
                $('fillterbutton[data-block-id="'+block_id+'"]').html(self.val());
        }
    }, element_ui_element);
    //$(document).on('click','fillterbutton.block-item',function(){
    //    element_ui_fillterbutton.resizable($(this));
    //});
    $('.block-item.block-item-fillterbutton[link-to-page!="0"]').each(function(){
        item_id=$(this).attr('link-to-page');
        $(this).click(function(){
            window.location.href=this_host+'?Itemid='+item_id;
        });
    });*/






});