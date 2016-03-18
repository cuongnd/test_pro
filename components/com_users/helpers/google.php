<?php

class google_helper
{


	const REDIRECT_URI ='index.php?option=com_users&task=user.google_login' ;

	public static function get_login_url()
	{
		$redirect_uri=JUri::root().self::REDIRECT_URI;
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
		$google_developer_key 	= 'AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw';
		require_once JPATH_ROOT.'/libraries/google-api-php-client-master/src/Google/Client.php';
		$gClient = new Google_Client();
		$gClient->setApplicationName('gold-cycling-553');
		$gClient->setClientId($google_client_id);
		$gClient->setClientSecret($google_client_secret);
		$gClient->setDeveloperKey($google_developer_key);

		return $gClient;



	}
}
?>