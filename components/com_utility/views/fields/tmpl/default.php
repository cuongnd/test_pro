<?php
$app = JFactory::getApplication();
$field_config = $app->input->get('field_config', '', 'string');
$append = $app->input->get('append', '', 'string');
$onchange = $app->input->get('onchange', '', 'string');

require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$field_config = base64_decode($field_config);
$field_config = (array)up_json_decode($field_config, false, 512, JSON_PARSE_JAVASCRIPT);
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';


$post = file_get_contents('php://input');
$post = json_decode($post);



ob_start();

?>

<?php if (count($field_config)): ?>
    <table class="table">
        <?php foreach ($field_config as $field) { ?>
            <tr>
                <?php
                $value=$post->{$field->name};
                $form_field = UtilityHelper::get_field($field,$value,$onchange);
                ?>
                <td><?php echo $field->label ?></td>
                <td><?php echo $form_field->renderField(); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php endif; ?>


<?php
$contents = ob_get_clean();
$tmpl = $app->input->get('tmpl');
if (strtolower($tmpl) == 'field') {
    echo $contents;
    return;
}

$response_array[] = array(
    'key' => $append,
    'contents' => $contents
);
echo json_encode($response_array);
?>



