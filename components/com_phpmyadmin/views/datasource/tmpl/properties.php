<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/jquery.popupWindow.js');
$doc->addScript(JUri::root() . '/components/com_phpmyadmin/views/datasource/tmpl/assets/js/jquery.load_properties_data_source.js');
$app = JFactory::getApplication();
$add_on_id = $app->input->get('add_on_id', 0, 'int');
$app->input->set('id', $add_on_id);
$modelDataSource = JModelLegacy::getInstance('DataSource', 'phpMyAdminModel');
JTable::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/tables');
$modelDataSource->setState('datasource.id', $add_on_id);

$form = $modelDataSource->getForm();
$options = $form->getFieldsets();
$user=JFactory::getUser();
$show_popup_control=$user->getParam('option.webdesign.show_popup_control',false);
$show_popup_control=JUtility::toStrictBoolean($show_popup_control);
$scriptId = "script_view_data_source_properties_" . $add_on_id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.properties.datasource.data_source_<?php echo $add_on_id ?>').load_properties_data_source({
            show_popup_control:<?php echo json_encode($show_popup_control) ?>
        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


ob_start();
?>


<div class="properties datasource data_source_<?php echo $add_on_id ?>">
    <div class="panel-group" id="accordion<?php echo $add_on_id ?>" role="tablist" aria-multiselectable="true">
        <?php
        foreach ($options as $key => $option) {
            $fieldSet = $form->getFieldset($key);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headinge<?php echo $key ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion<?php echo $add_on_id ?>"
                           href="#collapse<?php echo $key ?>" aria-expanded="true"
                           aria-controls="collapse<?php echo $key ?>">

                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $key ?>" class="panel-collapse collapse in" role="tabpanel"
                     aria-labelledby="heading<?php echo $key ?>">
                    <div class="panel-body">
                        <?php
                        foreach ($fieldSet as $field) {
                            ?>
                            <div class="form-horizontal">
                                <?php echo $field->renderField(array(), true); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-danger save-data-source-properties pull-right" type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;
            <button class="btn btn-danger cancel-block-properties pull-right" type="button"><i class="br-refresh"></i>Reset</button>
        </div>
    </div>
</div>

<?php
$contents = ob_get_clean(); // get the callback function
$tmpl = $app->input->get('tmpl', '', 'string');
if ($tmpl == 'field') {
    echo $contents;
    return;
}

$respone_array[] = array(
    'key' => '.block-properties .panel-body',
    'contents' => $contents
);
echo json_encode($respone_array);
?>



