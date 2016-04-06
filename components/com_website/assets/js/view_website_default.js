//huong dan su dung
/*
 $('.view_website_default').view_website_default();

 view_website_default=$('.view_website_default').data('view_website_default');
 console.log(view_website_default);
 */

// jQuery Plugin for SprFlat admin view_website_default
// Control options and basic function of view_website_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_website_default = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_website_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.sethtmlfortag=function(respone_array)
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

        plugin.create_website = function (self) {
            if ($element.find('#create-website-form').valid()) {
                var btn = self;
                //btn.button('loading');
                your_domain= $element.find('input[name="your_domain"]').val();
                sub_domain= $element.find('input[name="sub_domain"]').val();
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    data: (function() {
                        dataPost = {
                            option: 'com_website',
                            task: 'website.ajaxCheckExistsYourDomainAndSubDomain',
                            your_domain:your_domain,
                            sub_domain: function(){
                                return  $element.find('input[name="sub_domain"]').val()+this_host;
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
                            $('#create-website-form').submit();
                        }

                    }
                });
            }

        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
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
            $element.find('input[name="own_domain"]').click(function(){
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

            $.validator.setDefaults({ ignore: ":hidden:not(select)" });
            plugin.create_form=$element.find('#create-website-form').validate({
                ignore: ":hidden:not(select)",
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
                                $element.find('input.create-website').attr('disabled','disabled');
                                $element.find('.div-loading').css({
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
                                    $element.find('input.create-website').removeAttr('disabled');
                                    $('.div-loading').css({
                                        display: 'none'
                                    });

                                } else {
                                    $element.find('input.create-website').removeAttr('disabled');
                                    $('.div-loading').css({
                                        display: 'none'
                                    });
                                }
                            },
                            dataFilter: function(data) {
                                $element.find('input.create-website').removeAttr('disabled');
                                $('.div-loading').css({
                                    display: 'none'
                                });
                                data=$.parseJSON(data);
                                response = data.html;
                                plugin.sethtmlfortag(response);
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
                                enable_load_component:1,
                                task:"website.checkExistsSubDomain",
                                domain_id:function(){
                                    var domain_id=$element.find('select[name="domain"]').val();
                                    return domain_id;
                                },
                                sub_domain: function(){
                                    return  $element.find('input[name="sub_domain"]').val()+this_host;
                                }
                            },
                            beforeSend:function(){

                                $element.find('input.create-website').attr('disabled','disabled');
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
                                $element.find('input.create-website').removeAttr('disabled');
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
                                $element.find('input.create-website').removeAttr('disabled');
                                $('.div-loading').css({
                                    display: 'none'
                                });
                                data=$.parseJSON(data);
                                response = data.html;
                                plugin.sethtmlfortag(response);
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
                        element= $element.find('.input-group.sub_domain');
                    }
                    if(element.attr('name')=='your_domain')
                    {
                        element= $element.find('.input-group.your_domain');
                    }

                    label.insertAfter(element);
                },
                wrapper: 'span'
            });


            $element.find('input.create-website').click(function () {
                plugin.create_website($(this));


            })



        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_website_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_website_default')) {
                var plugin = new $.view_website_default(this, options);

                $(this).data('view_website_default', plugin);

            }

        });

    }

})(jQuery);
