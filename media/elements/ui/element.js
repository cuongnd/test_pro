jQuery(document).ready(function($){

    element_ui_element={
        init_ui_element:function()
        {

        },
        store_css_class:function(block_id,css_class){
            element_ui_element.list_css[block_id]=css_class;

        },
        add_css_class:function(e,self){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            $('.block-item[data-block-id="'+block_id+'"]').addClass(e.object.text);
        },
        remove_class_css:function(e,self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            $('.block-item[data-block-id="'+block_id+'"]').removeClass(e.choice.text);

        },
        update_text:function(self,html){
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            data_text=properties.find('input[name="jform[params][data][text]"]');
            if(data_text.val().trim()=='')
            {   if(html)
                    $('.block-item[data-block-id="'+block_id+'"]').html(self.val());
                else
                $('.block-item[data-block-id="'+block_id+'"]').val(self.val());
            }
        },
        load_file_js_then_call_back_function:function(file_js,call_back_function,strParam)
        {
            if($('script[src="'+this_host+file_js+'"]').length==0)
            {
                $('head').append('<\script src="'+this_host+file_js+'" ?>" type="text/javascript"></\script>');
                $('script[src="'+this_host+file_js+'"]' ).load(function() {
                    var funcCall = call_back_function + "('" + strParam + "');";
                    var ret = eval(funcCall);
                    return ret;


                });
            }else{
                var funcCall = call_back_function + "('" + strParam + "');";
                var ret = eval(funcCall);
                return ret;
            }
        },
        convert_to_element:function(self)
        {
            properties=self.closest('.properties.block');
            block_id=properties.attr('data-object-id');
            element_type=self.val();

            aJaxConvertToElementType=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxConvertToElementType',
                        element_type: element_type,
                        block_id: block_id

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {
                     alert('convert ok please restart browser');


                }
            });

        }

    };
});