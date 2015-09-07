jQuery(document).ready(function($){
    change_view={
        init_change_view:function(){

        },
        update_value:function(request)
        {
            request=base64.decode(request);
            request= $.parseJSON(request);
            console.log(request);
            title=request.title;
            request=request.request;
            if(request!=null)
            {
                request=$.param(request);
                $('input.input_link').val(request);
            }else{
                $('input.input_link').val(request);
            }
        }
    }
});