<?php
require_once JPATH_ROOT.'/libraries/facebook-php-sdk-v4-master/src/Facebook/autoload.php';
$fb = new Facebook\Facebook(array(
    'app_id' => '970288516370238',
    'app_secret' => 'd97dd7ebef54402a5416a56ed982d943',
    'default_graph_version' => 'v2.2',
));

$helper = $fb->getRedirectLoginHelper();

$permissions = array('email'); // Optional permissions
$loginUrl = $helper->getLoginUrl(JUri::root().'index.php?option=com_utility&view=utility&layout=loginfacebook&tmpl=component', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>