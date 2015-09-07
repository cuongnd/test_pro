<?php
$app=JFactory::getApplication();
$add_on_id=$app->input->get('add_on_id',0,'int');
$field=$app->input->get('field','','string');
$modelDataSource= $this->getModel();
$modelDataSource->setState('datasource.id',$add_on_id);

$app->input->set('id',$add_on_id);
$form=$modelDataSource->getForm();



ob_start();
$response_array=array();
$requestString='/\[(.+?)\]/';

preg_match_all($requestString, $field, $parameter);
$parameter=$parameter[1];
$field=end($parameter);
$group='';
if(count($parameter)>1)
{
    $group=reset($parameter);
}

$contents = $form->getInput($field,$group);
ob_end_clean(); // get the callback function
$tmpl=$app->input->get('tmpl');
if(strtolower($tmpl)=='field')
{
    echo $contents;
    return;
}

$respone_array[] = array(
    'key' => '.itemField .panel-body',
    'contents' => $contents
);
echo json_encode($respone_array);
?>