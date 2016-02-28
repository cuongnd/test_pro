
<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_components/views/components/tmpl/assets/js/loadcomponents.js');
jimport('joomla.filesystem.folder');
$website=JFactory::getWebsite();
$db=JFactory::getDbo();
$query = $db->getQuery(true);
$query->from('#__components as component')
    ->select('component.element')
    ->where('type="component"')
    ->where('website_id=' . (int)$website->website_id)
    ->group('component.name');
$listComponent = $db->setQuery($query)->loadColumn();
$listLayOut = array();
foreach ($listComponent as $com) {
    $views = JFolder::folders(JPATH_ROOT . '/components/' . $com . '/views');
    foreach ($views as $view) {
        $layouts = JFolder::files(JPATH_ROOT . '/components/' . $com . '/views/' . $view . '/tmpl/', '.xml');
        if (count($layouts))
            $listLayOut[$com][$view] = $layouts;
    }

}
$user=JFactory::getUser();
$show_popup_control=$user->getParam('option.webdesign.show_popup_control',false);
$show_popup_control=JUtility::toStrictBoolean($show_popup_control);
$scriptId = "script_component_load_component";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('ul.list-component').load_components({
            show_popup_control:<?php echo json_encode($show_popup_control) ?>
        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);



?>
<ul class="nav sub list-plugin list-component">
    <?php foreach ($listLayOut as $com => $views) { ?>
        <li><a href="javascript:void(0)" class="notExpand link_javascript"><?php echo $com ?> <i class=im-paragraph-justify></i></a>
            <ul class="nav sub">
                <?php foreach ($views as $view => $layouts) { ?>
                    <li>
                        <a href="javascript:void(0)" class="notExpand link_javascript"><i
                                class=ec-pencil2></i><?php echo JString::sub_string(JText::_($view), 7) ?></a>
                        <ul class="nav sub">
                            <?php foreach ($layouts as $layout) { ?>
                                <li data-component="<?php echo $com ?>" data-view="<?php echo $view ?>"
                                    data-layout="<?php echo $layout ?>" title="<?php echo JText::_($layout) ?>"
                                    class="item-element view_item">
                                    <a href="javascript:void(0)"><i
                                            class="ec-pencil2 layout-config"></i><?php echo JString::sub_string(JText::_($layout), 7) ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>



                <?php } ?>
            </ul>
        </li>
    <?php } ?>
</ul>
