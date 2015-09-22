jQuery(document).ready(function($){

    element_ui_button=$.extend({
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
    });

    $('.block-item.block-item-button[type="submit"]').click(function(){
        data_submit={};
        console.log(element_ui_button.list_function_run_befor_submit);
        for(i=0;i<element_ui_button.list_function_run_befor_submit.length;i++)
        {
            data_submit=element_ui_button.list_function_run_befor_submit[i](data_submit);
        }
        block_id=$(this).attr('data-block-id');

        parse_query= $.url(currentLink).param();
        data_submit.parse_query= parse_query;
        //data_submit.parser_url=parser_url;
        ajaxLoadFieldTypeOfModule=$.ajax({
            type: "GET",
            dataType: "json",
            url: this_host+'/index.php',
            data: (function () {

                dataPost = {
                    option: 'com_phpmyadmin',
                    task: 'datasource.ajax_save_data',
                    data:data_submit,
                    block_id:block_id

                };
                $.each( parse_query, function( index, value ){
                    index=index.toLowerCase();
                    if(index!='option'&&index!='task')
                    {
                        dataPost[index]=value;
                    }
                });
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

        /*item_id=$(this).attr('link-to-page');
        $(this).click(function(){
            window.location.href=this_host+'?Itemid='+item_id;
        });*/
    });





});