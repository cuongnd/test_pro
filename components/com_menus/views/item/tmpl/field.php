<?php
$app=JFactory::getApplication();
$block_id=$app->input->get('block_id',0,'int');
$field=$app->input->get('field','','string');
ob_start();
$response_array=array();
$requestString='/\[(.+?)\]/';

preg_match_all($requestString, $field, $parameter);
$parameter=$parameter[1];
array_reverse($parameter);
$field=array_pop($parameter);
array_reverse($parameter);

$group=implode('.',$parameter);





$contents = $this->form->getInput($field,$group);
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



