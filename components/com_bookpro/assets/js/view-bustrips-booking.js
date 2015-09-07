jQuery(document).ready(function($){
    $("#car-booking").validate();
    $("#pickup_date,#dropoff_date").datepicker({
        dateFormat: "dd-mm-yy",

        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: new Date(),
        showOn: "button",
        buttonText:'<span class="add-on"><i class="icon-calendar"></i></span>',

        onSelect: function () {
            getLayoutBookingSummary();
        }
    });
    $(document).on('change','.date-time.hours,.date-time.minutes',function(){
        getLayoutBookingSummary();
    });
    $(document).on('click','img.add-one-detail',function(){
        addone_id=$(this).attr('data-addone-id');
        $('tr.add-one-detail-'+addone_id).toggleClass('show');
    });
    function getLayoutBookingSummary()
    {
        pickup_date = $('#pickup_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        pickup_hours=$('select[name="pickup_hours"]').val();
        pickup_minutes=$('select[name="pickup_minutes"]').val();

        dropoff_date = $('#dropoff_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        drop_off_hours=$('select[name="drop_off_hours"]').val();
        drop_off_minutes=$('select[name="drop_off_minutes"]').val();
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro',
                    controller: 'bustrips',
                    task: 'ajaxGetLayoutBookingSummary',
                    pickup_date:pickup_date,
                    pickup_hours:pickup_hours,
                    pickup_minutes:pickup_minutes,
                    dropoff_date:dropoff_date,
                    drop_off_hours:drop_off_hours,
                    drop_off_minutes:drop_off_minutes
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        })



    }
    $(document).on('click','.input-submit-login',function(){
        post=$('.login-account').find(':input').serializeArray();
        if(post.length>0)
            post='&'+ $.param(post);
        $('.login-error').html('');
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                data = {
                    option: 'com_bookpro',
                    controller: 'bustrips',
                    task: 'quick_login'
                }
                return $.param(data)+post;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                respone_array = $.parseJSON(result);
                loginOk=respone_array.loginOk;
                msg=respone_array.msg;
                if(loginOk)
                {
                   fillInfoCustomer(msg)
                }
                else
                {
                    $('.login-error').html(msg);
                }

            }
        })

    });
    function fillInfoCustomer(msg)
    {
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                data = {
                    option: 'com_bookpro',
                    controller: 'bustrips',
                    task: 'fillInfoCustomer'
                }
                return $.param(data);
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').html('<div class="msg">'+msg+'</div>');
                $('.widgetbookpro-loading').css({
                    background:'none',
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);

            }
        })

    }
    $(document).on('click','.add.icon-checked',function(){
        plus=$(this).hasClass('uncheck')?1:0;
        $(this).toggleClass("uncheck");
        addOneId=$(this).attr('data-addone-id');
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro',
                    controller: 'bustrips',
                    task: 'ajaxSetAddOne',
                    addOneId:addOneId,
                    plus:plus
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        })

    });
    function sethtmlfortag(respone_array)
    {
        respone_array = $.parseJSON(respone_array);
        $.each(respone_array, function(index, respone) {

            $(respone.key.toString()).html(respone.contents);
        });
    }
});