<?php
$app=JFactory::getApplication();
$module_id=$app->input->get('module_id',0,'int');
$app->input->set('id',$module_id);
$modelModule=JModelLegacy::getInstance('Module','ModulesModel');
$form=$modelModule->getForm();
$item=$modelModule->getItem();
$options=$form->getFieldsets();
ob_start();
?>
<div class="form-horizontal ">
    <div class="form-group">
        <div class="col-xs-5 control-label">
            Filter
        </div>
        <div class="col-xs-7">
            <div class="input-group">
                <input class="form-control" value="" name="filter_label">
            </div>
        </div>
    </div>
</div>
<div class="properties module" data-object-id="<?php echo $module_id ?>">
    <div class="panel-group" id="accordion<?php echo $menuItemActiveId ?>" role="tablist" aria-multiselectable="true">
        <?php
        foreach($options as $key=>$option)
        {
            $fieldSet = $form->getFieldset($key);
            ?>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $key ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion<?php echo $menuItemActiveId ?>" href="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
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
$response_array[] = array(
    'key' => '.block-properties .panel-body',
    'contents' => $contents
);
$response_array[] = array(
    'key' => '.block-properties > .panel-heading > .panel-title',
    'contents' => "Module Properties $item->title($item->id)"
);

echo  json_encode($response_array);
?>
