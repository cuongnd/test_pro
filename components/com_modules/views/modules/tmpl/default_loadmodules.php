<?php
require_once JPATH_ROOT . '/components/com_modules/helpers/modules.php';
$extensions = ModulesHelper::getModules(0);
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_modules/views/modules/tmpl/assets/js/loadmodules.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/position.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/button.js');
$doc->addStyleSheet(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css');

$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/sortable.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/resizable.js');


ob_start();
?>
<ul class="nav sub list-module">
    <?php foreach ($extensions as $extension) { ?>
        <li data-module-id="<?php echo $extension->id ?>" class="item-element module_item"><a
                href="javascript:void(0)"><i class="ec-pencil2" data-element-type="extension_module" data-element_path="<?php echo $extension->value ?>"></i><?php echo $extension->text ?></a></li>
    <?php } ?>
</ul>
<?php
$contents=ob_get_clean();
$response_array[] = array(
    'key' => '.load_modules',
    'contents' => $contents
);
echo  json_encode($response_array);
?>

