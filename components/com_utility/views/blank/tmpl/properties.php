<?php
$app=JFactory::getApplication();
$block_id=$app->input->get('block_id',0,'int');
$field=$app->input->get('field','','string');
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_utility/models') ;

$modelPosition=JModelLegacy::getInstance('Position','UtilityModel');
$modelPosition->setState('position.id',$block_id);
$app->input->set('id',$block_id);
$form=$modelPosition->getForm();
ob_start();
$response_array=array();

$contents = $form->getInput($field);
$tmpl=$app->input->get('tmpl');
if(strtolower($tmpl)=='field')
{
    echo $contents;
    return;
}
$response_array[] = array(
    'key' => '.itemField .panel-body',
    'contents' => $contents
);
echo  json_encode($response_array);
?>



