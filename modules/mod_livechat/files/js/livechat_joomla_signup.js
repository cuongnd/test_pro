var Livechat_Joomla_Signup = function()
{
	/**
	 * Loads CSS file from given URL
	 */
	var loadCSS = function(url)
	{
		var sc = document.createElement('link');
		sc.rel = 'stylesheet';
		sc.type = 'text/css';
		sc.href = url + '?rand='+Math.random();
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(sc, s);
	}

	/**
	 * Loads JS library from given URL
	 */
	var scriptsQueue = [];

	var enqueueScript = function(url, callback)
	{
		scriptsQueue.push({'url': url, 'callback': callback});
	};

	var loadScripts = function(callback)
	{
		if (scriptsQueue.length == 0)
		{
			callback();
			return true;
		};

		var currentScript = scriptsQueue.shift();
		if (!currentScript)
		{
			callback();
			return true;
		};

		var sc = document.createElement('script');
		sc.type = 'text/javascript';
		sc.async = false;
		sc.src = currentScript.url + '?rand='+Math.random();
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(sc, s);

		var callback = callback;

		if (typeof currentScript.callback == 'undefined')
		{
			loadScripts(callback);
			return true;
		}

		if (currentScript.callback() == false)
		{
			var currentScript = currentScript;
			setTimeout(function()
			{
				if (currentScript.callback() == true)
				{
					loadScripts(callback);
				}
			}, 100);

			return false;
		};
	}
	loadCSS('../modules/mod_livechat/files/css/livechat.css');
	if ((typeof jQuery == 'undefined')) {
		enqueueScript('../modules/mod_livechat/files/js/jquery-1.4.2.min.js', function(){ return (typeof jQuery != 'undefined'); });
		enqueueScript('../modules/mod_livechat/files/js/jquery.colorbox-min.js', function(){ return (typeof jQuery.fn.colorbox != 'undefined'); });
	}
	enqueueScript('../modules/mod_livechat/files/js/signup.js', function(){ return (typeof Livechat_Signup != 'undefined'); });

	var onJSLoaded = function(callback)
	{
		if (JSIsLoaded() == false)
		{
			setTimeout(function() { onJSLoaded(callback); }, 100);
			return false;;
		}

		callback();
	}

	var initForm = function()
	{
		var $container = $('#cboxContent'),
		    html = '';

		/**
		 * Creating HTML for new license form
		 */
		html += '<div style="display:none">';
		html += '<div id="livechat_new_account">';
		html += '<form method="post" action="#">';
		html += '<h3>Create new LiveChat account</h3>';
		html += '<p>All fields are required.</p>';
		html += '<table class="form-table">';
		html += '<tr>';
		html += '<td class="c1"><label for="livechat_account_full_name">Full name:</label></td>';
		html += '<td><input type="text" class="text" name="livechat_account_full_name" id="livechat_account_full_name" maxlength="30"></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td class="c1"><label for="livechat_account_email">E-mail:</label></td>';
		html += '<td><input type="text" class="text" name="livechat_account_email" id="livechat_account_email" maxlength="70"></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td class="c1"><label for="livechat_account_password">Password:</label></td>';
		html += '<td><input type="password" class="text" name="livechat_account_password" id="livechat_account_password" maxlength="70" value=""></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td class="c1"><label for="livechat_account_password_retype">Retype password:</label></td>';
		html += '<td><input type="password" class="text" name="livechat_account_password_retype" id="livechat_account_password_retype" maxlength="70" value=""></td>';
		html += '</tr>';
		html += '</table>';

		html += '<p id="ajax_message">&nbsp;</p>';

		html += '<table class="form-table">';
		html += '<tr>';
		html += '<td class="submit">';
		html += '<input type="hidden" name="livechat_account_timezone" value="US/Pacific" id="livechat_account_timezone">';
		html += '<input type="submit" value="Create account" id="submit" class="button-primary">';
		html += '</td>';
		html += '</tr>';
		html += '</table>';

		html += '</form>';
		html += '</div>';
		html += '</div>';

		$container.after(html);
		$.colorbox({
			height: '500px',
			width: '500px',
			inline: true,
			href: '#livechat_new_account',
			onComplete: function()
			{
				$('#livechat_account_full_name').focus();
			}
		});

		// Bind singup form actions
		Livechat_Signup();
	}

	loadScripts(function()
	{
		initForm();
	});
};