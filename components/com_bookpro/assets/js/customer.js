

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
				url: "index.php?option=com_bookpro&controller=customer&task=checkemail&tmpl=component",  
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
