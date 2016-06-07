<?php
ob_clean();
$app=JFactory::getApplication();
$menu=$app->getMenu();
$active_menu=$menu->getActive();
$mobile_response_type=$active_menu->mobile_response_type;
$doc=JFactory::getDocument();
$component =$doc->getBuffer('component');
require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
if($active_menu->mobile_response_type=='json'){
	$html = str_get_html($component);
    echo "<pre>";
    echo print_r($html);
    echo "</pre>";
    die;
}else{
	echo $component;
}
?>