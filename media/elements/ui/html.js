jQuery(document).ready(function($){
    elementuihtml={

        innitHtml:function(){
        },
        saveBlockHtml:function(self){
            content=CKEDITOR.instances.editor1.getData();
            content= base64.encode(content);
            block_id=self.attr('data-block-id');
            ajaxSaveBlockHtml=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.ajaxSaveBlockHtml',
                        block_id:block_id,
                        content:content

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {




                }
            });
        }
    };
    $(document).on('click','.save-block-html',function(){
        elementuihtml.saveBlockHtml($(this));
    });
    $(document).on('dblclick','.edit_html_content',function(){

        js_ckfinder='/ckfinder/ckfinder.js';
        if(!$('script[src="'+this_host+js_ckfinder+'"]').length)
        {
            $('head').append('<script src="'+this_host+js_ckfinder+'" type="text/javascript"></script>');
        }

        js_ckeditor='/media/editors/ckeditor/ckeditor.js';
        if(!$('script[src="'+this_host+js_ckeditor+'"]').length)
        {
            $('head').append('<script src="'+this_host+js_ckeditor+'" type="text/javascript"></script>');
        }

        js_adapters_jquery='/media/editors/ckeditor/adapters/jquery.js';
        if(!$('script[src="'+this_host+js_adapters_jquery+'"]').length)
        {
            $('head').append('<script src="'+this_host+js_adapters_jquery+'" type="text/javascript"></script>');
        }
        $(this).attr('contenteditable',true);
        $(this).ckeditor();

    });




});