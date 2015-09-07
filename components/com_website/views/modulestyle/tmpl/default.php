<?php
$doc = JFactory::getDocument();
require_once JPATH_ROOT . '/components/com_website/helpers/website.php';
$lessInput = JPATH_ROOT . '/components/com_website/assets/less/view-modulestyle.less';
$cssOutput = JPATH_ROOT . '/components/com_website/assets/css/view-modulestyle.css';
websiteHelperFrontEnd::compileLess($lessInput, $cssOutput);

?>
<div class="edit-style">
    <script src="<?php echo JUri::root() . '/components/com_website/assets/js/view-modulestyle.js' ?>"></script>
    <link href="<?php echo JUri::root() . '/components/com_website/assets/css/view-modulestyle.css' ?>"
          rel="stylesheet">
    <div class="row">
        Change the color and style and place text and images inside it.
    </div>
    <div class="row">
        <button type="button" class="btn btn-primary btn-lg btn-block change_style">Change style</button>
        <button type="button" class="btn btn-default btn-lg btn-block add_animation">Add Animation</button>
    </div>
    <div id="module_dialog_show_view" style="display: none;" title="Basic dialog">
        <div id="module_dialog_show_view_body" class="module_dialog_show_view_body">
            <?php echo JText::_('this dialog show view module') ?>
        </div>
    </div>
    <div id="module_dialog_show_view" style="display: none;" title="Basic dialog">
        <div id="module_dialog_show_view_body" class="module_dialog_show_view_body">
            <?php echo JText::_('this dialog show view module') ?>
        </div>
    </div>
</div>