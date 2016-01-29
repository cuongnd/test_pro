<?php
require_once JPATH_ROOT . '/components/com_modules/helpers/modules.php';
$extensions = ModulesHelper::getModules(0);
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_modules/views/modules/tmpl/assets/js/loadmodules.js');
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

