<?php
$doc=JFactory::getDocument();

jimport('joomla.filesystem.folder');
$bgPath='images/stories/background';
$files=JFolder::files(JPATH_ROOT.'/'.$bgPath,'.png');

?>
<script src="<?php echo JUri::root().'/components/com_website/assets/js/view-background.js' ?>"></script>
<div class="row">
    <?php foreach($files as $file){ ?>
        <div class="pull-left">
            <img class="background-image" src="<?php echo JUri::root().$bgPath.'/'.$file ?>">
        </div>
    <?php } ?>
</div>