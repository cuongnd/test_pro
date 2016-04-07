<?php
$doc=JFactory::getDocument();
$uri=JFactory::getURI();
$url=JUri::root();
$is_website_supper_admin=JFactory::is_website_supper_admin();

?>
<div class=header-area-inner>
    <ul class="list-unstyled list-inline">
        <?php if($is_website_supper_admin){ ?>
        <li>
            <div class=shortcut-button><a href="<?php echo JUri::root() ?>/index.php?option=com_supperadmin"><i class=im-user></i> <span>Supper Admin</span></a></div>
        </li>
        <?php } ?>
        <li>
            <div class=shortcut-button><a href=#><i class=im-user3></i> <span>website config</span></a></div>
        </li>
        <li>
            <div class=shortcut-button><a href=#><i class=im-user3></i> <span>website config</span></a></div>
        </li>
        <li>
            <div class=shortcut-button><a href=#><i class="ec-images color-dark"></i> <span>Gallery</span></a></div>
        </li>
        <li>
            <div class=shortcut-button><a href=#><i class="en-light-bulb color-orange"></i> <span>Fresh ideas</span></a></div>
        </li>
        <li>
            <div class=shortcut-button><a href=#><i class="ec-link color-blue"></i> <span>Links</span></a></div>
        </li>
        <li>
            <div class=shortcut-button><a href=#><i class="ec-support color-red"></i> <span>Support</span></a></div>
        </li>
        <li>
            <div class=shortcut-button><a href=#><i class="st-lock color-teal"></i> <span>Lock area</span></a></div>
        </li>
        <?php
        $website_name=JFactory::get_website_name();
        $main_component="com_$website_name";
        $main_component_path=JPath::get_component_path($main_component);
        $file_admin_icon_top_path=$main_component_path.DS.'admin_icon_top.php';
        if(file_exists($file_admin_icon_top_path))
        {
            require_once $file_admin_icon_top_path;
        }
        ?>


    </ul>
</div>



