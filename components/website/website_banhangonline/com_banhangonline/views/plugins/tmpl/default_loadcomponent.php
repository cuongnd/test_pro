
<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/supperadmin/com_banhangonline/views/supperadmin/tmpl/assets/js/loadsupperadmin.js');
jimport('joomla.filesystem.folder');
$website=JFactory::getWebsite();
$db=JFactory::getDbo();
$query = $db->getQuery(true);
$query->from('#__supperadmin as component')
    ->select('component.*')
    ->leftJoin('#__plugins AS plugins ON plugins.id=component.extension_id')
    ->where('component.type='.$query->q('component'))
    ->where('plugins.website_id=' . (int)$website->website_id)
    ->group('component.name')
;
$listComponent = $db->setQuery($query)->loadObjectList();
$listLayOut = array();
$website = JFactory::getWebsite();
foreach ($listComponent as $com) {
    $is_private_component=true;
    $folder_view=JPATH_ROOT . '/supperadmin/website/website_'.$website->website_id.'/' . $com->element . '/views';
    if(!JFolder::exists($folder_view))
    {
        $is_private_component=false;
        $folder_view=JPATH_ROOT . '/supperadmin/' . $com->element . '/views';
    }
    $views = JFolder::folders($folder_view);
    foreach ($views as $view) {
        $layouts = JFolder::files($folder_view . '/' . $view . '/tmpl/', '.xml');
        if (count($layouts))
        {
            $item_layout=new stdClass();
            $item_layout->paths=$layouts;
            $item_layout->component_id=$com->id;
            $item_layout->is_private_component=$is_private_component;
            $listLayOut[$com->element][$view] = $item_layout;
        }
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
        $('ul.list-component').load_supperadmin({
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
<ul class="nav sub list-plugin list-component">
    <?php foreach ($listLayOut as $com => $views) { ?>
        <li><a href="javascript:void(0)" class="notExpand link_javascript"><?php echo $com ?> <i class=im-paragraph-justify></i></a>
            <ul class="nav sub">
                <?php foreach ($views as $view => $item_layout) { ?>
                    <li>
                        <a href="javascript:void(0)" class="notExpand link_javascript"><i
                                class=ec-pencil2></i><?php echo JString::sub_string(JText::_($view), 7) ?></a>
                        <ul class="nav sub">
                            <?php foreach ($item_layout->paths as $layout) { ?>

                                <li  data-component-id="<?php echo $item_layout->component_id ?>" data-component="<?php echo $com ?>" data-view="<?php echo $view ?>"
                                    data-layout="<?php echo $layout ?>" title="<?php echo JText::_($layout) ?>"
                                    class="item-element view_item">
                                    <a href="javascript:void(0)"><i
                                            class="ec-pencil2 layout-config" data-id="<?php echo $item_layout->component_id ?>" data-element-type="extension_component" data-element_path="<?php echo $item_layout->is_private_component?"supperadmin/website/website_$website->website_id/$com/views/$view/tmpl/$layout":"supperadmin/$com/views/$view/tmpl/$layout" ?>" ></i><?php echo JString::sub_string(JText::_($layout), 7) ?>
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

<?php
$contents=ob_get_clean();
$response_array[] = array(
    'key' => '.load_component',
    'contents' => $contents
);
echo  json_encode($response_array);
?>
