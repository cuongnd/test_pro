jQuery(document).ready(function ($) {
    //$('[data-toggle="tooltip"]').tooltip();


    function sethtmlfortag(respone_array)
    {
        if(respone_array !== null && typeof respone_array !== 'object')
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
    $.validator.addMethod("domain",function(nname){
        name = nname.replace('http://','');
        nname = nname.replace('https://','');

        var mai = nname;
        var val = true;

        var dot = mai.lastIndexOf(".");
        var dname = mai.substring(0,dot);
        var ext = mai.substring(dot,mai.length);

        if(dot>2 && dot<57)
            return true;

        return false;

    }, 'Invalid domain name.');
    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[0-9a-zA-Z.-]+$/);
    },'You can input characters 0->9,a->z,A->Z, and character dot');
    $(document).on('click','input[name="own_domain"]',function(){

       if($(this).is(':checked'))
       {
           $('.field_your_domain').show();
           $('.field_suggestionyourdomain').show();
       }
        else
       {
           $('.field_your_domain').hide();
           $('.field_suggestionyourdomain').hide();
       }
    });


    $('#create-website').validate({
        rules:{
            your_domain:{
                required:true,
                alpha:true,
                domain:true,
                remote:{
                    url :url_root+'index.php',
                    type:   'post',
                    onsubmit: false,
                    data:{
                        option:'com_website',
                        task:"website.checkExistsDomain"

                    },
                    beforeSend:function(){
                        $('input.create-website').attr('disabled','disabled');
                        $('.div-loading').css({
                            display: "block",
                            position: "fixed",
                            "z-index": 1000,
                            top: 0,
                            left: 0,
                            height: "100%",
                            width: "100%"

                        });

                    },
                    timeout: 30000,
                    error: function(x, t, m) {

                        if(t==="timeout") {
                            //action when timeout
                            $('input.create-website').removeAttr('disabled');
                            $('.div-loading').css({
                                display: 'none'
                            });

                        } else {
                            $('input.create-website').removeAttr('disabled');
                            $('.div-loading').css({
                                display: 'none'
                            });
                        }
                    },
                    dataFilter: function(data) {
                        $('input.create-website').removeAttr('disabled');
                        $('.div-loading').css({
                            display: 'none'
                        });
                        data=$.parseJSON(data);
                        response = data.html;
                        sethtmlfortag(response);
                        return data.exists=='true'?false:true;
                    }
                }
            },
            sub_domain:{
                required:true,
                alpha:true,
                remote:{
                    url :url_root+'index.php',
                    type:   'post',
                    data:{
                        option:'com_website',
                        task:"website.checkExistsSubDomain",
                        sub_domain: function(){
                            return $('input[name="sub_domain"]').val()+this_host;
                        }
                    },
                    beforeSend:function(){

                        $('input.create-website').attr('disabled','disabled');
                        $('.div-loading').css({
                            display: "block",
                            position: "fixed",
                            "z-index": 1000,
                            top: 0,
                            left: 0,
                            height: "100%",
                            width: "100%"

                        });

                    },
                    timeout: 30000,
                    error: function(x, t, m) {
                        $('input.create-website').removeAttr('disabled');
                        $('.div-loading').css({
                            display: 'none'
                        });
                        if(t==="timeout") {
                            //action when timeout


                        } else {
                            //alert(t);
                        }
                    },

                    dataFilter: function(data) {
                        $('input.create-website').removeAttr('disabled');
                        $('.div-loading').css({
                            display: 'none'
                        });
                        data=$.parseJSON(data);
                        response = data.html;
                        sethtmlfortag(response);
                        return data.exists=='true'?false:true;
                    }
                }
            }
        },
        messages:{
            your_domain:{
                required:   "this is Required",
                remote: 'this domain exists'
            },
            sub_domain:{
                required:   "this is Required",
                remote: 'this sub domain exists'
            }

        },
        errorPlacement: function(label, element) {
            label.addClass('arrow');
            if(element.attr('name')=='sub_domain')
            {
                element=$('.input-group.sub_domain');
            }
            if(element.attr('name')=='your_domain')
            {
                element=$('.input-group.your_domain');
            }

            label.insertAfter(element);
        },
        wrapper: 'span'
    });


    $('input.create-website').click(function () {
        if ($('#create-website').valid()) {
            var btn = $(this);
            btn.button('loading');
            your_domain=$('input[name="your_domain"]').val();
            sub_domain=$('input[name="sub_domain"]').val();
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    dataPost = {
                        option: 'com_website',
                        task: 'website.ajaxCheckExistsYourDomainAndSubDomain',
                        your_domain:your_domain,
                        sub_domain: function(){
                            return $('input[name="sub_domain"]').val()+this_host;
                        }
                    }
                    return dataPost;
                })(),
                beforeSend: function() {
                    $('.div-loading').css({
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
                    result= $.parseJSON(result);
                    exits=result.exits;
                    if(exits==='true'||exits==1)
                    {
                        $('.div-loading').css({
                            display: 'none'
                        });
                        $('.error .content-error').html('error setup : exists your domain or exists sub domain in our system, please refresh your browser and input infomation');
                    }
                    else
                    {
                        $('.div-loading').css({
                            display: "block",
                            position: "fixed",
                            "z-index": 1000,
                            top: 0,
                            left: 0,
                            height: "100%",
                            width: "100%"

                        });
                        $('#create-website').submit();
                    }

                }
            });
        }


    })

});
