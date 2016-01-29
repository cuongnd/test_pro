<?php
$app=JFactory::getApplication();
$field=$app->input->get('field','','string');
$modelModule= $this->getModel();
$form=$this->form;

ob_start();
$respone_array=array();
$contents = $form->getInput($field);

ob_end_clean(); // get the callback function
$respone_array[] = array(
    'key' => '.itemField .panel-body',
    'contents' => $contents
);
echo json_encode($respone_array);
?>