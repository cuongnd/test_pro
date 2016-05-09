jQuery(document).ready(function($){
    $(document).on('click','.setup .next',function(){

        next($(this));
    });
    $('input[name="autoSetup"]').prop('checked', true);
    $('.setup .next').trigger( "click" );
    function sethtmlfortag(respone_array)
    {
        respone_array = $.parseJSON(respone_array);
        $.each(respone_array, function(index, respone) {
            if(typeof(respone.type) !== 'undefined')
            {
                $(respone.key.toString()).val(respone.contents);
            }else {
                $(respone.key.toString()).html(respone.contents);
            }
        });
    }


    function next(thisObject)
    {
        $('.setup button').attr('disabled','disabled');
        currentStep=$('input[name="currentStep"]').val();
        if(currentStep=='Finish')
        {
            website=$('input[name="website"]').val();
            window.location.assign("http://"+website);
            return;
        }
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function () {
                dataPost = {
                    option: 'com_website',
                    task: 'website.nextStep',
                    currentStep:currentStep
                };
                return dataPost;
            })(),
            timeout: 30000,
            error: function(x, t, m) {
                $('.setup button').removeAttr('disabled');
                if(t==="timeout") {
                    //some action when timeout
                    $('.setup button').removeAttr('disabled');


                } else {
                    $('.setup button').removeAttr('disabled');
                }
            },
            beforeSend: function () {
            },
            success: function (result) {
                sethtmlfortag(result);
                result= $.parseJSON(result);

                $('.setup button').removeAttr('disabled');
                currentStep=$('input[name="currentStep"]').val();
                if(currentStep!='Finish'&&$('input[name="autoSetup"]').is(':checked'))
                    next(thisObject);
                if(currentStep=='Finish')
                {

                    $('.setup button.back').remove();
                    $('.setup button.cancel').remove();
                    $('.autosetup').remove();
                    $('.setup button.next').html('Finish');
                }
                progress_success=$('input[name="progress_success"]').val();
                progress_success=progress_success==0?2:progress_success;
                $('#setup_website_progress_bar').attr('aria-valuenow',progress_success);
                $('#setup_website_progress_bar').css({
                    width:progress_success.toString()+'%'
                });

            }
        });

    }
});