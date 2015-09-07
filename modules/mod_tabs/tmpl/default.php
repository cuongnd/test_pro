<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/modules/mod_tabs/assets/js/mod_tabs.js');
?>
<script src="<?php echo  JUri::root().'/modules/mod_tabs/assets/js/mod_tabs.js' ?>"></script>
<div class="tabs">
    <ul id="tab_<?php echo $module->id ?>" class="nav nav-tabs nav-justified">
        <li><a href=""#home2" data-toggle="tab">Home</a></li>
        <li><a href=""#profile2" data-toggle="tab">Profile</a></li>
    </ul>
    <div id="tab_content_<?php echo $module->id ?>" class="tab-content">
        <?php foreach($contentInTabs as $content){ ?>
        <div data-enable-add-sub-element="true" class="tab-pane fade active in" id="home2">
            <?php echo $content ?>
        </div>
        <?php } ?>
    </div>
</div>