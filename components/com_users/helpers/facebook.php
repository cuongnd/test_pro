<?php
require_once  JPATH_ROOT.'/libraries/facebook-php-sdk-v4-master/src/Facebook/autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
class Facebook_helper
{


	public static function get_login_url()
	{
		$fb=self::get_facebook();
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email']; // Optional permissions
		return $helper->getLoginUrl(JUri::root().'index.php?option=com_users&task=user.facebook_login', $permissions);

	}

	public static function get_facebook()
	{
		$app_id='970288516370238';
		$app_secret='d97dd7ebef54402a5416a56ed982d943';
		$fb = new Facebook\Facebook([
			'app_id' => $app_id, // Replace {app-id} with your app id
			'app_secret' => $app_secret,
			'default_graph_version' => 'v2.2',
		]);
		return $fb;
	}
}
?>