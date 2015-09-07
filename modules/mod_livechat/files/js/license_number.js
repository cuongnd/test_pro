var Livechat_License_Number = function()
{
	var validate = function(use_alert)
	{

		if (/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i.test($('#livechat_account_email').val()) == false)
		{
			if (use_alert) alert ('Please enter a valid email address.');
			return false;
		}

		return true;
	}

	$('#livechat_license_no form').submit(function()
	{
		if (validate(true))
		{
			$('#ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');
			var url = 'https://api.livechatinc.com/license/number/'+$('#livechat_account_email').val()+'?callback=?';
			
			$.getJSON(url,
			function(data)
			{
				if (typeof data.number != 'undefined') {
					$('#jform_params_license_number').val(data.number);
					submitbutton('module.apply');
				}
				else {
					$('#ajax_message').removeClass('wait').addClass('message').html('This email does\'t exist. Please choose another email.');						
				}
			});
		}
		return false;
	});
};