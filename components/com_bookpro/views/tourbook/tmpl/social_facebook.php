<?php
$registration_social=JPluginHelper::getPlugin('bookpro','registration_social');
print_r($registration_social);
$OauthOptions = new JRegistry();
$OauthOptions->loadString($registration_social->params);
$OauthOptions->def('sendheaders',1);
$OauthOptions->def('redirecturi',JURI::base().'index.php?option=com_bookpro&controller=customer&task=fbregister');
$OauthOptions->def('clientid',$OauthOptions->get(fbclient_id));
$OauthOptions->def('clientsecret',$OauthOptions->get(fbsecret));

$array = array(

		"scope" => "email"
);
$OauthOptions->set('requestparams',$array);

//Build the JFacebookOAuthobject
$facebookOauth = new JFacebookOAuth($OauthOptions);

//Authenticate. Will redirect to facebook if there is no correct code.
try{

	$facebookOauth->authenticate();


}catch(RuntimeException $e){

	$response->status                = JAuthentication::STATUS_FAILURE;
	$response->error_message = $e->getMessage();

	return false;
}

//If here, then we have a correct tokken. Proceed to create a JFacebook object.
$facebook = new JFacebook($facebookOauth);
//Take the user information from facebook
$user = $facebook->__get("user");
$json = $user->get("me");
print_r($user);
exit();
?>