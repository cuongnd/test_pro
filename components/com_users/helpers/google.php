<?php

class google_helper
{


	public static function get_login_url()
	{
		$redirect_uri=JUri::root();
		$client=self::get_google();
		$client->setRedirectUri($redirect_uri);
		$client->addScope("https://www.googleapis.com/auth/urlshortener");
		$authUrl = $client->createAuthUrl();
		return $authUrl;
	}

	public static function get_google()
	{
		$google_client_id 		= '256006136278-0s4q40gkrohn1a1bd9hek7948dtuk657.apps.googleusercontent.com';
		$google_client_secret 	= 'iVlAN8XVfK02BO3BiSIwr9LG';
		$google_redirect_url 	= 'http://demo.trinhtuantai.com/login-with-google/'; //path to your script
		$google_developer_key 	= 'AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw';
		require_once JPATH_ROOT.'/libraries/google-api-php-client-master/src/Google/Client.php';


		$gClient = new Google_Client();
		$gClient->setApplicationName('Login to demo.trinhtuantai.com');
		$gClient->setClientId($google_client_id);
		$gClient->setClientSecret($google_client_secret);
		$gClient->setRedirectUri($google_redirect_url);
		$gClient->setDeveloperKey($google_developer_key);

		return $gClient;



	}
}
?>