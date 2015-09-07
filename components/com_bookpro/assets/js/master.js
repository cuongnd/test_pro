

function checkUsername(){

    var usr = jQuery("#username").val();

    if(usr.length >= 4)
    {

        jQuery("#statusUSR").html('<img src="components/com_bookpro/assets/images/loader.gif" align="absmiddle">&nbsp;Checking availability...');

        jQuery.ajax({  
            type: "POST",  
            url: siteURL+"index.php?option=com_bookpro&controller=customer&task=checkusername&tmpl=component",
            data: "username="+ usr,  
            success: function(msg){  

                jQuery("#statusUSR").ajaxComplete(function(event, request, settings){

                    if(msg == 'OK' )
                    { 
                        jQuery("#username").removeClass('object_error'); // if necessary
                        jQuery("#username").addClass("inputbox");
                        jQuery(this).html('&nbsp;<img src="components/com_bookpro/assets/images/tick.png" align="absmiddle">');
                    }  
                    else
                    {			
                        jQuery("#username").removeClass('object_ok'); // if necessary
                        jQuery("#username").addClass("object_error");
                        jQuery(this).html(msg);			
                    }  

                });

            } 

        }); 

    }
}

function checkEmail(){
    var email = jQuery("#email").val();

    p = email.indexOf('@');
    p1= email.indexOf('.');

    if (p<1 || p==(email.length-1) || p1<1 || p1==(email.length-1))
    {
        //$("#statusEMAIL").html('<font color="red">Enter a valid email address.</font>');
        jQuery("#statusEMAIL").html('&nbsp;<img src="components/com_bookpro/assets/images/warning.png" align="absmiddle">');		
        jQuery("#email").removeClass('object_ok'); // if necessary
        jQuery("#email").addClass("object_error");
    }
    else
    {

        jQuery("#statusEMAIL").html('<img src="components/com_bookpro/assets/images/loader.gif" align="absmiddle">&nbsp;Checking availability...');

        jQuery.ajax({  
            type: "POST",  
            url: siteURL+"index.php?option=com_bookpro&controller=customer&task=checkemail&tmpl=component",  
            data: "email="+ email,  
            success: function(msg){  

                jQuery("#statusEMAIL").ajaxComplete(function(event, request, settings){ 

                    if(msg == 'OK')
                    { 
                        jQuery("#email").removeClass('object_error'); // if necessary
                        jQuery("#email").addClass("inputbox");

                        jQuery(this).html('&nbsp;<img src="components/com_bookpro/assets/images/tick.png" align="absmiddle">');
                    }  
                    else  
                    {  


                        jQuery("#email").removeClass('object_ok'); // if necessary
                        jQuery("#email").addClass("object_error");
                        jQuery(this).html(msg);
                    }  

                });

            } 

        }); 			

    }
}
function changecountry(callback) {
    var country_id = 0;
    var states = 0;
    jQuery(document).ready(function($) {
        $("select#country_id option:selected").each(function() {
            country_id = $(this).val();

        });
        $("select#states option:selected").each(function() {
            states = $(this).val();

        });

        $.ajax({
            type : 'POST',
            url : siteURL+'index.php?option=com_bookpro&controller=customer&task=getstates&format=raw&tmpl=component',
            data : '&country_id=' + country_id,
            beforeSend : function() {
                $("div#statescontrol")
                .html(
                    '<div align="center"><img src="components/com_bookpro/assets/images/loader.gif" /><div>');
            },
            success : function(data) {
                $('div#statescontrol').html(data);
                if ($.isFunction(callback)) {
                    callback.call();
                }
            }
        });

        $.ajax({
            type : 'POST',
            url : siteURL+'index.php?option=com_bookpro&controller=customer&task=getcity&format=raw&tmpl=component',
            data : '&country_id=' + country_id
            + '&state_id=' + states,
            beforeSend : function() {
                $("div#citycontrol")
                .html(
                    '<div align="center"><img src="components/com_bookpro/assets/images/loader.gif" /><div>');
            },
            success : function(data) {
                $('div#citycontrol').html(data);
                if ($.isFunction(callback)) {
                    callback.call();
                }
            }
        });

    });

}
function changestate(obj) {
    jQuery(document).ready(	function($) {
        $("select#country_id option:selected").each(function() {
            country_id = $(this).val();

        });
        $("select#states option:selected").each(function() {
            states = $(this).val();

        });
        if (obj.value) {
            $.ajax({
                type : 'POST',
                url : 'index.php?option=com_bookpro&controller=customer&task=getcity&format=raw&tmpl=component',
                data : '&country_id=' + country_id
                + '&state_id=' + states,
                beforeSend : function() {
                    $("div#citycontrol")
                    .html('<div align="center"><img src="components/com_bookpro/assets/images/loader.gif" /><div>');
                },
                success : function(data) {
                    $('div#citycontrol').html(data);
                }
            });
        }
    });
}