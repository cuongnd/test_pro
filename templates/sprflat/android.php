<?php
ob_clean();
$app=JFactory::getApplication();
$menu=$app->getMenu();
$active_menu=$menu->getActive();
$mobile_response_type=$active_menu->mobile_response_type;
$doc=JFactory::getDocument();
require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
if($active_menu->mobile_response_type=='json'){
    echo "<android_response>".json_encode($doc->android_response)."</android_response>";
}else{
    $component =$doc->getBuffer('component');
    echo "<android_response>".$component."</android_response>";
}
?>