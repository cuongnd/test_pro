<?php
$doc=JFactory::getDocument();
$response=new stdClass();
$response->scriptDeclaration= $doc->_scriptDeclaration;

$response->style= $doc->_style;
$response->html= $doc->getBuffer('component');
$response->html=json_decode($response->html);

$htmlScript=array();
foreach($response->script as $key=>$contentScript)
{
    $htmlScript[]='<script type="'.$key.'">';
    $htmlScript[]=$contentScript;
    $htmlScript[]='</script>';
}
$htmlScript[]="\n";
$scriptId= JUserHelper::genRandomPassword();
$sctipExcute='';
$sctipExcute.='<script type="text/javascript" id="'.$scriptId.'">';
$sctipExcute.='jQuery(document).ready(function($){';
foreach ($doc->_scriptAjaxCallFunction as $key=>$script) {

    $sctipExcute.="\n";
    $sctipExcute.="$key();";

}
$sctipExcute.='});';
$sctipExcute.='</script>';
$doc->addAjaxCallFunction($scriptId,$sctipExcute,$scriptId);
$response->scriptAjaxCallFunction= $doc->_scriptAjaxCallFunction;

$htmlScript=implode('',$htmlScript);
$htmlStyle=array();
foreach($response->style as $key=>$contentStyle)
{
    $htmlStyle[]='<style type="'.$key.'">';
    $htmlStyle[]=$contentStyle;
    $htmlStyle[]='</style>';
}
$htmlStyle=implode('',$htmlStyle);

if($response->html[0]->key=='')
{
    $response->html[0]->key='not';
}
$response->html[0]->contents.=$htmlScript.$htmlStyle;
$response->scripts= $doc->_scripts;

$response->styleSheets= $doc->_styleSheets;
$response->style= $doc->_style;
echo json_encode($response);
?>
