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
$current_url=JUri::getInstance();
$current_host= $current_url->getHost();
$list_script=array();
foreach($doc->_scripts as $key=>$item)
{
    $uri = JUri::getInstance($key);
    $host = $uri->getHost();
    if ($current_host==$host||$host=='') {
        $path=$uri->getPath();
        $path=explode('/',$path);

        $path1=array();
        foreach($path as $item1)
        {
            if(trim($item1)!='')
            {
                $path1[]=$item1;
            }
        }
        $path1=implode('/',$path1);
        $path1=$current_url->toString(array('scheme','host','port')).'/'.$path1;
        $list_script[$path1]=$item;
    }else{
        $list_script[$key]=$item;
    }

}

$response->html[0]->contents.=$htmlScript.$htmlStyle;
$response->scripts= $list_script;

$response->styleSheets= $doc->_styleSheets;
$response->style= $doc->_style;
echo json_encode($response);
?>
