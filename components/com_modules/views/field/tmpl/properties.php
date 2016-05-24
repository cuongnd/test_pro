<?php

$app=JFactory::getApplication();
$module_id=$app->input->get('id',0,'int');
$field=$app->input->get('field','','string');
$db=JFactory::getDbo();
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_modules/models') ;
$modelModule=JModelLegacy::getInstance('Module','ModulesModel');
$modelModule->setState('module.id',$module_id);
$app->input->set('id',$module_id);
$form=$modelModule->getForm();
ob_start();
$response_array=array();
$requestString='/\[(.+?)\]/';
preg_match_all($requestString, $field, $parameter);
$parameter=$parameter[1];
array_reverse($parameter);
$field=array_pop($parameter);
array_reverse($parameter);
$group=implode('.',$parameter);
$contents = $form->getInput($field,$group);
$tmpl=$app->input->get('tmpl');
if(strtolower($tmpl)=='field')
{
    echo $contents;
    return;
}

$response_array[] = array(
    'key' => '.itemField .panel-body',
    'contents' => $contents,
    'base64_encode'=>0
);
ob_clean();
echo  json_encode($response_array);
?>



