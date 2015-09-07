<?php
$app=JFactory::getApplication('site');
$modelWebsite=$this->getModel();

$website=JFactory::getWebsite();
$website_id=$website->website_id;
$app->input->set('id',$website_id);
$modelWebsite->setState('website.id',$website_id);
$form=$modelWebsite->getForm();
$options=$form->getFieldsets();
$item=$modelWebsite->getItem($website_id);
ob_start();
?>
<div class="properties website" data-object-id="<?php echo $website_id ?>">
    <div class="panel-group" id="accordion<?php echo $website_id ?>" role="tablist" aria-multiselectable="true">
        <?php
        foreach($options as $key=>$option)
        {
            $fieldSet = $form->getFieldset($key);
            ?>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $key ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion<?php echo $website_id ?>" href="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
                            <?php echo $option->label ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $key ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $key ?>">
                    <div class="panel-body">
                        <?php
                        foreach ($fieldSet as $field)
                        {
                            ?>
                            <div class="form-horizontal">
                                <?php echo $field->renderField(array(),true); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<?php
$contents=ob_get_clean();
$tmpl=$app->input->get('tmpl','','string');
if($tmpl=='field')
{
    echo $contents;
    return;
}

$response_array[] = array(
    'key' => '.block-properties .panel-body',
    'contents' => $contents
);
echo  json_encode($response_array);
?>



