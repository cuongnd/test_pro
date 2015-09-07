jQuery.noConflict();
if(typeof(BTLJ)=='undefined') var BTLJ = jQuery;
if(typeof(btTimeOut)=='undefined') var btTimeOut;
if(typeof(requireRemove)=='undefined') var requireRemove = true;

// AJAX LOGIN
function loginAjax(){
	if(BTLJ("#btl-input-username").val()=="") {
		BTLJ("#btl-login-error").html(btlOpt.REQUIRED_USERNAME)
		BTLJ("#btl-login-error").show();
		return false;
	}
	if(BTLJ("#btl-input-password").val()==""){
		BTLJ("#btl-login-error").html(btlOpt.REQUIRED_PASSWORD);
		BTLJ("#btl-login-error").show();
		return false;
	}
	var token = BTLJ('.btl-buttonsubmit input:last').attr("name");
	var value_token = BTLJ('.btl-buttonsubmit input:last').val(); 
	var datasubmit= "task=login&username="+BTLJ("#btl-input-username").val()
	+"&passwd=" + BTLJ("#btl-input-password").val()
	+ "&"+token+"="+value_token
	+"&return="+ BTLJ("#btl-return");
	
	if(BTLJ("#btl-input-remember").is(":checked")){
		datasubmit += '&remember=yes';
	}
	BTLJ.ajax({
	   type: "POST",
	   beforeSend:function(){
		   BTLJ("#btl-login-in-process").show();
		   BTLJ("#btl-login-in-process").css('height',BTLJ('#btl-content-login').outerHeight()+'px');
		   
	   },
	   async: true,
	   url: btlOpt.BT_AJAX,
	   data: datasubmit,
	   success: function(html){
		  
		  if(html == "1" || html == 1){
			   window.location.href=btlOpt.BT_RETURN;
		   }else{
			   BTLJ("#btl-login-in-process").hide();
			   BTLJ("#btl-login-error").html(btlOpt.E_LOGIN_AUTHENTICATE);
			   BTLJ("#btl-login-error").show();
		   }
	   },
	   error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus + ': please check file ajax.php (Permisisons should be set to 755)');
	   }
	});
	return false;
}

