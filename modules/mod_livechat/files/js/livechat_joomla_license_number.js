var Livechat_Joomla_License_Number = function()
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
		var s = document.getElementsByTagName('script')[1];
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
		var s = document.getElementsByTagName('script')[1];
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
	enqueueScript('../modules/mod_livechat/files/js/license_number.js', function(){ return (typeof Livechat_License_Number != 'undefined'); });

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
		 * Creating HTML for get license number form
		 */
		html += '<div style="display:none">';
		html += '<div id="livechat_license_no">';

		html += '<form method="post" action="#">';
		html += '<h3>Get your LiveChat license number</h3>';
		html += '<table class="form-table">';
		html += '<tr>';
		html += '<td class="c1"><label for="livechat_account_email">E-mail:</label></td>';
		html += '<td><input type="text" class="text" name="livechat_account_email" id="livechat_account_email" maxlength="30"></td>';
		html += '</tr>';
		html += '</table>';
		html += '<p id="ajax_message">&nbsp;</p>';
		html += '<table class="form-table">';
		html += '<tr>';
		html += '<td class="submit">';
		html += '<input type="submit" value="Get license number" id="submit" class="button-primary">';
		html += '</td>';
		html += '</tr>';
		html += '</table>';
		html += '</form>';

		html += '</div>';
		html += '</div>';

		$container.after(html);
		$.colorbox({
			height: '300px',
			width: '500px',
			inline: true,
			href: '#livechat_license_no',
			onComplete: function()
			{
				$('#livechat_account_email').focus();
			}
		});

		// Bind license number form actions
		Livechat_License_Number();
	}

	loadScripts(function()
	{
		initForm();
	});
};