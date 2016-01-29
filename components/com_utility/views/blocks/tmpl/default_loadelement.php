<?php
jimport('joomla.filesystem.folder');
$foldersElement = JFolder::folders(JPATH_ROOT . '/media/elements');
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_utility/views/blocks/tmpl/assets/js/loadelement.js');
ob_start();
?>
<ul class="nav sub hide">
    <?php foreach ($foldersElement as $element) { ?>
        <li>
            <?php
            $listFileElement = JFolder::files(JPATH_ROOT . '/media/elements/' . $element, '.php');
            ?>
            <a   href="javascript:void(0)" class="notExpand link_javascript"><i class=ec-pencil2></i> <?php echo $element ?></a>
            <ul class="nav sub hide">
                <?php foreach ($listFileElement as $fileElement) { ?>
                    <li class="item-element item-element-ui"
                        data-element-type="<?php echo str_replace('.php', '', $fileElement) ?>"
                        data-element-path="media/elements/<?php echo $element . '/' . $fileElement ?>">
                        <?php
                        $path_parts = pathinfo($fileElement);
                        ?>
                        <a href="javascript:void(0)"><i class=ec-pencil2></i> <?php echo $path_parts['filename'] ?><span class="ui-property-manager"><i element-config="<?php echo $path_parts['filename'] ?>"  class="fa-list-alt"></i></span></a>

                    </li>
                <?php } ?>
            </ul>

        </li>
    <?php } ?>
</ul>
<?php
$contents=ob_get_clean();
$response_array[] = array(
    'key' => '.load_element',
    'contents' => $contents
);
echo  json_encode($response_array);
?>
