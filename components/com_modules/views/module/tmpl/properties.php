<?php

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$app = JFactory::getApplication();
$modelPosition = JModelLegacy::getInstance('Module', 'ModulesModel');
$item = $modelPosition->getItem();

$module_id = $item->id;
$form = $this->form;
$doc=JFactory::getDocument();
$db = JFactory::getDbo();
$doc->addScript(JUri::root().'/media/system/js/clipboard.js-master/dist/clipboard.js');
require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
require_once JPATH_ROOT . '/libraries/joomla/form/field.php';
require_once JPATH_ROOT . '/libraries/joomla/form/fields/radioyesno.php';

$website=JFactory::getWebsite();
$website_name=JFactory::get_website_name();
$ui_path = $item->module;
$table_control = new JTableUpdateTable($db, 'control');
$element_path='modules/website/website_'.$website_name.'/' . $ui_path;

jimport('joomla.filesystem.folder');
if(!JFolder::exists(JPATH_ROOT.DS.$element_path))
{
    $element_path='modules/' . $ui_path;
}
$filter= array(
    "element_path" => $element_path,
    "type" => module_helper::ELEMENT_TYPE,
    'website_id'=>$website->website_id
);
$table_control->load(
    $filter
);
$fields = $table_control->fields;
$fields = base64_decode($fields);
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
require_once JPATH_ROOT.'/components/com_modules/helpers/module.php';
$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
$table_control->load(
    array(
        "element_path" => module_helper::MODULE_ROOT_NAME,
        "type" =>module_helper::ELEMENT_TYPE
    )
);
$main_fields = $table_control->fields;
$main_fields = base64_decode($main_fields);

require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$main_fields = (array)up_json_decode($main_fields, false, 512, JSON_PARSE_JAVASCRIPT);
$scriptId = "view_module_property";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var clipboard = new Clipboard('.copy_clipboard');
        clipboard.on('success', function(e) {
            alert('copy success');
        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


function stree_node_xml($fields, $module_id = 0, $key_path = '', $indent = '', $form, $maxLevel = 9999, $level = 0)
{
    if ($level <= $maxLevel) {

        ?>
        <div class="panel-group" id="accordion<?php echo $indent ?>" role="tablist" aria-multiselectable="true">
            <?php
            $i = 0;
            foreach ($fields as $item) {
                $indent1 = $indent != '' ? $module_id . '_' . $indent . '_' . $i : $module_id . '_' . $i;
                $key_path1 = $key_path != '' ? ($key_path . '.' . $item->name) : $item->name;
                if (is_array($item->children) && count($item->children) > 0) {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading<?php echo $indent1 ?>">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion<?php echo $indent1 ?>"
                                   href="#collapse<?php echo $indent1 ?>" aria-expanded="true"
                                   aria-controls="collapse<?php echo $indent1 ?>">
                                    <?php echo $item->name; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $indent1 ?>" class="panel-collapse collapse in" role="tabpanel"
                             aria-labelledby="heading<?php echo $indent1 ?>">
                            <div class="panel-body">
                                <?php stree_node_xml($item->children, $module_id, $key_path1, $indent1, $form, $maxLevel, $level++); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <?php
                    $group = explode('.', $key_path);

                    if (strtolower($group[0]) == 'option') {
                        $name = array_reverse($group);
                        array_pop($group);
                        $group = array_reverse($group);
                    }
                    $string_params = reset($group);
                    $group = implode('.', $group);

                    $name = strtolower($item->name);
                    $addfieldpath=JPATH_ROOT."/".$item->addfieldpath;
                    if(file_exists($addfieldpath))
                    {
                        $addfieldpath=dirname($item->addfieldpath);
                        $form->addFieldPath(JPATH_ROOT.'/'.$addfieldpath);
                    }
                    $item_field = $form->getField($item->name, $group);

                    if ($string_params == 'params') {

                        $setup_value_enable = $form->getData()->get($group.'.enable_' . $name );
                        $setup_value_enable = $setup_value_enable == 'on' ? 1 : 0;
                        $radio_yes_no = new JFormFieldRadioYesNo();
                        $string_radio_yes_no = <<<XML

<field
		name="enable_$name"
		type="radioyesno"
		class="btn-group btn-group-yesno"
		onchange="$item->onchange"
		default="1"
		label=""
		description="is publich">
	<option class="btn" value="1">JYES</option>
	<option class="btn" value="0">JNO</option>
</field>

XML;

                        $element_yes_no = simplexml_load_string($string_radio_yes_no);
                        $radio_yes_no->setup($element_yes_no, $setup_value_enable, 'jform.' . $group);
                        $radio_yes_no->show_title(false);

                        ?>
                        <?php if ($item_field) { ?>
                            <div class="form-horizontal property-item">

                                <div class="row">
                                    <div class="col-md-3">
                                        <?php echo $radio_yes_no->renderField(); ?>
                                        <br/>
                                        <span data-clipboard-text="<?php echo $key_path1 ?>" class="copy_clipboard"><?php echo $key_path1 ?></span>
                                        <br/>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <?php

                                            echo $item_field->renderField(array(), true);
                                            ?>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        <?php }
                    } else {
                        if ($item_field) { ?>
                            <div class="form-horizontal property-item">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <?php

                                            echo $item_field->renderField(array(), true);
                                            ?>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        <?php }
                    }
                }

                $i++;
            }
            ?>
        </div>
        <?php

    }

}

$doc = JFactory::getDocument();
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
    <?php echo stree_node_xml($main_fields, $module_id, '', '', $form); ?>
    <?php echo stree_node_xml($fields, $module_id, '', '', $form); ?>
</div>

<?php

$contents = ob_get_clean();
$tmpl = $app->input->get('tmpl', '', 'string');
if ($tmpl == 'field') {
    echo $contents;
    return;
}

$response_array[] = array(
    'key' => '.block-properties .panel-body',
    'contents' => $contents
);
$response_array[] = array(
    'key' => '.block-properties > .panel-heading > .panel-title',
    'contents' => "Module ($item->name) $item->title($item->id)"
);
echo json_encode($response_array);
?>



