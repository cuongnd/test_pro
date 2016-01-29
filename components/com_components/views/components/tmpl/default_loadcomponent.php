
<?php
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


?>
<ul class="nav sub list-plugin">
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
                                            class=ec-pencil2></i><?php echo JString::sub_string(JText::_($layout), 7) ?>
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
