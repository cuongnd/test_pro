<?php
ob_clean();
$app=JFactory::getApplication();
$menu=$app->getMenu();
$active_menu=$menu->getActive();
$mobile_response_type=$active_menu->mobile_response_type;
$doc=JFactory::getDocument();
$component =$doc->getBuffer('component');
if($active_menu->mobile_response_type=='json'){
    $dom = new DOMDocument();
    $dom->loadHTML($component);
    $json_html= JUtility::element_to_obj($dom->documentElement);
    echo json_encode($json_html);
}else{
	echo $component;
}
?>