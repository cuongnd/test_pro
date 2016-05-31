<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>"
      lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">


<?php

require_once JPATH_ROOT . '/components/com_phpmyadmin/helpers/datasource.php';
require_once JPATH_ROOT . '/components/com_utility/helper/utility.php';
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$scriptId = "script_website_core";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('body').website_core({
            option:"<?php echo  $app->input->getString('option','') ?>",
            view:"<?php echo $app->input->getString('view','') ?>"
        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);



$this->debugScreen = 0;


$isAdminSite = UtilityHelper::isAdminSite();
$enableEditWebsite = UtilityHelper::getEnableEditWebsite();
$ajaxGetContent = $app->input->get('ajaxgetcontent', 0, 'int');

$menu = JMenu::getInstance('site');
$menuItemActiveId = $menu->getActive()->id;

$menuItemActiveId = $menuItemActiveId ? $menuItemActiveId : 0;
$menuItemActive = $menu->getItem($menuItemActiveId);
$lessContent = $menuItemActive->lesscontent;
if (trim($lessContent) != '') {
    require_once JPATH_ROOT . '/libraries/f0f/less/less.php';
    $less = new F0FLess;
    $doc->addStyleDeclaration($less->compile($lessContent));
}
//get property website
$website = JFactory::getWebsite();
$websiteTable = JTable::getInstance('Website', 'JTable');
$websiteTable->load($website->website_id);
require_once JPATH_ROOT . '/templates/sprflat/helper/template.php';
templateSprflatHelper::init_website();
if ($isAdminSite && !$enableEditWebsite) {
    $app->redirect('index.php?option=com_users&view=login&template=system');
    return;
}

$doc = JFactory::getDocument();
$user=JFactory::getUser();
$doc->addScript(JUri::root() . '/media/system/js/jquery.website_core.js');
$show_popup_control=$user->getParam('option.webdesign.show_popup_control',false);
$show_popup_control=JUtility::toStrictBoolean($show_popup_control);

if ($enableEditWebsite) {
    $preview = UtilityHelper::getStatePreview();
    $preview = $preview != '' ? $preview : 0;
    $user = JFactory::getUser();
    $uri = JFactory::getURI();
    $listScreenSize1 = UtilityHelper::getListScreenSize();
    $currentScreenSize = UtilityHelper::getCurrentScreenSizeEditing();
    $listScreenSize = array();
    $listScreenSizeX = array();
    foreach ($listScreenSize1 as $screenSize) {
        $screenSize1 = explode('x', strtolower($screenSize));
        $item = new stdClass();
        $item->width = $screenSize1[0];
        $item->height = $screenSize1[1];
        $listScreenSize[$screenSize] = $item;
        $listScreenSizeX[] = $screenSize1[0];
    }
    $this->listPositions = UtilityHelper::getListPositions();
    $host = $uri->toString(array('scheme', 'host', 'port'));
    $scriptId = "script_index_" . JUserHelper::genRandomPassword();
    ob_start();
    ?>
    <script type="text/javascript">
        Joomla_post = {};
        Joomla_post.list_function_run_befor_submit = [];
        Joomla_post.list_function_run_validate_befor_submit = [];
        var url_root = "<?php echo JUri::root() ?>";
        var preview =<?php echo $preview ?>;
        var this_host = "<?php echo $host ?>";
        var currentLink = "<?php echo $uri->toString()?>";
        jQuery.noConflict();
        var listPositions =<?php echo json_encode($this->listPositions) ?>;
        var menuItemActiveId =<?php echo $menuItemActiveId?>;
        var currentScreenSizeEditing = "<?php echo $currentScreenSize ?>";
        var listScreenSizeX =<?php echo json_encode($listScreenSizeX) ?>;
        var listScreenSize =<?php echo json_encode($listScreenSize) ?>;
        var currentLink = "<?php echo $uri->toString() ?>";
        var enableEditWebsite = "<?php echo ($enableEditWebsite ? $enableEditWebsite : 0) ?>";
        var optionsGridIndex = {
            cell_height: 80,
            vertical_margin: 0,
            placeholder_class: "holder-and-move"


        };
        var source_less = "<?php echo str_replace('.less','.css',$websiteTable->source_less) ?>";
        jQuery(document).ready(function ($) {
            $('body').sprFlat({
                show_popup_control:<?php echo json_encode($show_popup_control) ?>,
                menu_item_active:<?php echo json_encode($menuItemActive) ?>
            });
        });
    </script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


JHTML::_('behavior.core');
require_once JPATH_ROOT . '/components/com_website/helpers/website.php';
require_once JPATH_ROOT . '/templates/sprflat/helper/template.php';
JHtml::_('jquery.framework');


//JHtml::_('jquery.ui', array('core','widget', 'sortable'));
if (!$ajaxGetContent) {
    //$doc->addScript(JUri::root() . '/media/system/js/firebug-lite/build/firebug-lite-debug.js');
    $doc->addScript(JUri::root() . '/media/system/js/contextmenueditwebsite.js');
    $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/position.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/button.js');
    $doc->addScript(JUri::root() . '/media/system/js/base64.js');
    $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
    $doc->addScript(JUri::root() . "/media/system/js/jquery.utility.js");

    $doc->addScript(JUri::root() . '/media/system/js/popline-master/scripts/jquery.popline.js');

    $doc->addStyleSheet(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css');

    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/sortable.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/resizable.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/dialog.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/droppable.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/js/jquery.ui.touch-punch.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/libs/excanvas.min.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/libs/html5.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/libs/respond.min.js');
    $doc->addScript(JUri::root() . '/media/system/js/jQuery.serializeObject-master/jquery.serializeObject.js');


    /*        $doc->addScript(JUri::root().'/ckfinder/ckfinder.js');
            $doc->addScript(JUri::root().'/media/editors/ckeditor/ckeditor.js');
            $doc->addScript(JUri::root().'/media/editors/ckeditor/adapters/jquery.js');*/
    /*
    //kendo editor
    $doc->addScript(JUri::root().'/media/kendotest/kendo.all.js');
    $doc->addScript(JUri::root().'/media/kendotest/kendo.core.js');
    $doc->addScript(JUri::root().'/media/kendotest/kendo.web.js');
    $doc->addScript(JUri::root().'/media/kendotest/kendo.editor.js');*/

    /* $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.common.min.css');
     $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.default.min.css');

     $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.material.min.css');
     $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.material.mobile.min.css');
     $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.min.css');
     $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.default.min.css');*/


    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.core.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.data.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.virtuallist.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.list.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.dropdownlist.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.pager.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.userevents.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.draganddrop.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.sortable.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.menu.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.columnmenu.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.popup.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.binder.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.filtermenu.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.editable.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.validator.js');

    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.combobox.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.selectable.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.groupable.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.columnsorter.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.resizable.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.window.js');
    //$doc->addScript(JUri::root().'/media/kendotest/php/data/products.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.grid.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.multiselect.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.numerictextbox.js');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.editor.js');


    $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.default.less');
    $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.common.less');
    $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.less');
    $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.default.less');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/build/less-js/dist/less-1.5.0.js');


    $doc->addStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/styles/kendo.rtl.min.css');


    JHtml::_('formbehavior.chosen', 'select');
    //$doc->addScript(JUri::root().'/media/system/js/jquery.ba-bbq.js');
    $doc->addScript(JUri::root() . '/media/system/js/core-uncompressed.js');
    $doc->addScript(JUri::root() . '/media/system/js/lodash.min.js');
    $doc->addScript(JUri::root() . '/media/system/js/gridstack/src/gridstack.js');
    //$doc->addScript(JUri::root().'/media/system/js/Nestable-master/jquery.nestable.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/js/jquery.editstyle.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jRespond.min.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/quicksearch/jquery.quicksearch.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/misc/countTo/jquery.countTo.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/forms/icheck/jquery.icheck.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.min.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.horizontal.min.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/charts/sparklines/jquery.sparkline.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jquery.sprFlat.js');
    $doc->addScript(JUri::root() . '/media/system/js/purl-master/purl-master/purl.js');
    $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');
    //$doc->addScript(JUri::root().'/media/system/js/ion.rangeSlider-1.9.1/js/ion-rangeSlider/ion.rangeSlider.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/js/design.js');
    $doc->addScript(JUri::root() . '/media/system/js/joyride-master/jquery.joyride-2.1.js');
    $doc->addStyleSheet(JUri::root() . '/media/system/js/joyride-master/joyride-2.1.css');
    $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/build/less-js/dist/less-1.5.0.js');
    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/csswheneditsite.less');
    if(JFile::exists(JPATH_ROOT. "/layouts/website/less/" . $websiteTable->source_less))
    {
        $doc->addLessStyleSheetTest(JUri::root() . "/layouts/website/less/" . $websiteTable->source_less);
    }



    $doc->addLessStyleSheet(JUri::root() . '/media/system/js/jquery-neon-border/less/jquery.neon_border.less');


    $doc->addLessStyleSheet(JUri::root() . "/media/system/js/gridstack/less/gridstack.less");
    //end css for gridstack

    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/main.less');

    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/icons.less');


    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/plugins.less');
    $doc->addLessStyleSheet(JUri::root() . '/media/jui_front_end/bootstrap-3.3.0/less/bootstrap.less');
    require_once JPATH_ROOT . '/libraries/less.php-master/lessc.inc.php';


    $doc->addLessStyleSheet("$this->baseurl/templates/$this->template/less/custom.less");
    $doc->addStyleSheet(JUri::root().'/media/jui_front_end/css/jquery.searchtools.css');

}


} else {

$this->listPositions = UtilityHelper::getListPositions();
$listScreenSize1 = UtilityHelper::getListScreenSize();

$listScreenSizeX = array();
foreach ($listScreenSize1 as $screenSize) {
    $screenSize1 = explode('x', strtolower($screenSize));

    $listScreenSizeX[] = $screenSize1[0];
}

$uri = JFactory::getURI();
$currentScreenSize = UtilityHelper::getScreenSize();
$this->currentScreenSize = $currentScreenSize;


$scriptId = "script_index_" . JUserHelper::genRandomPassword();
ob_start();
?>
    <script type="text/javascript">
        Joomla_post = {};
        Joomla_post.list_function_run_befor_submit = [];
        Joomla_post.list_function_run_validate_befor_submit = [];
        var url_root = "<?php echo JUri::root() ?>";
        var this_host = "<?php echo $host ?>";
        var currentLink = "<?php echo $uri->toString()?>";
        jQuery.noConflict();
        var listPositions =<?php echo json_encode($this->listPositions) ?>;
        var menuItemActiveId =<?php echo $menuItemActiveId?>;

        var currentScreenSize = "<?php echo $currentScreenSize ?>";
        var listScreenSizeX =<?php echo json_encode($listScreenSizeX) ?>;
        var listScreenSize =<?php echo json_encode($listScreenSize) ?>;
        var currentLink = "<?php echo $uri->toString() ?>";
        var enableEditWebsite = "<?php echo ($enableEditWebsite ? $enableEditWebsite : 0) ?>";
        var optionsGridIndex = {
            cell_height: 80,
            vertical_margin: 0,
            placeholder_class: "holder-and-move"


        };
        var source_less = "<?php echo str_replace('.less','.css',$websiteTable->source_less) ?>";
    </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);






    JHtml::_('jquery.framework');
    JHtml::_('bootstrap.framework');
    JHtml::_('formbehavior.chosen', 'select');
    //$doc->addScript(JUri::root() . '/media/system/js/firebug-lite/build/firebug-lite-debug.js');
    $doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/position.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/button.js');
    $doc->addScript(JUri::root() . '/media/system/js/bootstrap-notify-master/bootstrap-notify.js');

    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/resizable.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/dialog.js');
    $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/droppable.js');
    $doc->addScript(JUri::root() . '/media/system/js/purl-master/purl-master/purl.js');
    $doc->addScript(JUri::root() . '/media/system/js/sidr-master/src/jquery.sidr.js');
    $doc->addStyleSheet(JUri::root().'/media/jui_front_end/css/jquery.searchtools.css');
    $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
    require_once JPATH_ROOT . '/components/com_website/helpers/website.php';

    JHtml::_('jquery.framework');
    JHtml::_('jquery.ui', array('core', 'sortable'));
    $doc->addLessStyleSheet(JUri::root() . "/templates/$this->template/less/custom.less");
    $doc->addLessStyleSheet(JUri::root() . "/media/system/js/sidr-master/dist/stylesheets/jquery.sidr.light.css");




    $doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
    $doc->addScript(JUri::root() . '/media/system/js/jquery.appear-master/jquery.appear.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jRespond.min.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/quicksearch/jquery.quicksearch.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/misc/countTo/jquery.countTo.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/forms/icheck/jquery.icheck.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.min.js');
    $doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.horizontal.min.js');

    $doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jquery.sprFlatFrontEnd.js');
    $doc->addScript(JUri::root() . '/media/system/js/purl-master/purl-master/purl.js');
    $doc->addScript(JUri::root() . '/media/system/js/URI.js-gh-pages/src/URI.js');
    $doc->addScriptNotCompile(JUri::root() . '/templates/sprflat/js/javascriptdisableedit.js');

    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/disableedit.less');

    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/mainFrontEnd.less');
    //$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/template_backend.less');
    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/icomoon.less');
    if(JFile::exists(JPATH_ROOT. "/layouts/website/less/" . $websiteTable->source_less))
    {
        $doc->addLessStyleSheetTest(JUri::root() ."/layouts/website/less/" . $websiteTable->source_less);
    }



    $doc->addLessStyleSheet(JUri::root() . "/media/jui_front_end/bootstrap-3.3.0/less/bootstrap.less");

    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/icons.less');
    $doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/plugins.less');
}
?>
<?php
if ($ajaxGetContent) {
    echo websiteHelperFrontEnd::displayLayout($this, $enableEditWebsite);
    return;

}
?>
<head>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
    <jdoc:include type="head"/>
</head>
<body class="">

<?php if ($enableEditWebsite) {

    ?>

    <?php
    include JPATH_ROOT . '/templates/sprflat/html/layouts/joomla/system/contextmenu.php';
    ?>

    <!-- Start #header -->
    <div id=header>
        <div class=container-fluid>
            <div class=navbar>
                <div class=navbar-header><a class=navbar-brand href="index.php?option=com_bookpro"><i
                            class="im-windows8 text-logo-element animated bounceIn"></i><span
                            class=text-logo>Asian</span><span class=text-slogan>Venture</span></a></div>
                <nav class=top-nav role=navigation>
                    <ul class="nav navbar-nav pull-left">
                        <li id=toggle-sidebar-li><a href=# id=toggle-sidebar><i class=en-arrow-left2></i></a></li>
                        <li><a href=# class=full-screen><i class=fa-fullscreen></i></a></li>
                        <li class=dropdown><a href=# data-toggle=dropdown><i class=ec-cog></i><span class=notification>10</span></a>
                            <ul class=dropdown-menu role=menu>
                                <li><a href=#><i class=st-bag></i> Orders <span
                                            class="notification purple">12</span></a></li>
                            </ul>
                        </li>
                        <li class=dropdown><a href=# data-toggle=dropdown><i class=ec-mail></i><span class=notification>4</span></a>
                            <ul class="dropdown-menu email" role=menu>
                                <li class=mail-head>
                                    <div class=clearfix>
                                        <div class=pull-left><a href=email-inbox.html><i class=ec-archive></i></a></div>
                                        <span>Inbox</span>

                                        <div class=pull-right><a href=email-inbox.html><i class=st-pencil></i></a></div>
                                    </div>
                                </li>
                                <li class=search-email>
                                    <form>
                                        <input name=search placeholder="Search for emails">
                                        <button type=submit><i class=ec-search></i></button>
                                    </form>
                                </li>
                                <li class="mail-list clearfix"><a href=#><img
                                            src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/128.jpg
                                            class="mail-avatar pull-left" alt=avatar>

                                        <p class=name><span class=status><i class=en-dot></i></span> Jason Rivera <span
                                                class=notification>2</span> <span class=time>12:30 am</span></p>

                                        <p class=msg>I contact you regarding my account please can you set up my pass
                                            ...</p>
                                    </a></li>

                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <li><a class="rebuild_root_block" href="javascript:void(0)" title="rebuid block"><i
                                    class="im-spinner5"></i></a></li>
                        <li><a class="reload-website" href="javascript:void(0)"><i class=im-spinner6></i></a></li>
                        <li><a href=# id=toggle-header-area><i class=ec-download></i></a></li>
                        <li><a href=# id="preview"><i class="im-eye turn_off_preview"></i></a></li>
                        <li class=dropdown><a href=# data-toggle=dropdown><i class=br-alarm></i> <span
                                    class=notification>5</span></a>
                            <ul class="dropdown-menu notification-menu right" role=menu>
                                <li class=clearfix><i class=ec-chat></i> <a href=# class=notification-user>Ric Jones</a>
                                    <span class=notification-action>replied to your</span> <a href=#
                                                                                              class=notification-link>comment</a>
                                </li>

                            </ul>
                        </li>
                        <li class=dropdown><a href=# data-toggle=dropdown><img class=user-avatar
                                                                               src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg
                                                                               alt="<?php echo $user->name ?>"> <?php echo $user->name ?>
                            </a>
                            <ul class="dropdown-menu right" role=menu>

                                <li>
                                    <a href="<?php echo jRoute::_('index.php?option=com_users&task=user.logout&' . jSession::getFormToken() . '=1&return=' . base64_encode('index.php?option=com_users&view=login')) ?>"><i
                                            class=im-exit></i> Logout</a></li>
                            </ul>
                        </li>
                        <li id=toggle-right-sidebar-li><a href=# id=toggle-right-sidebar><i
                                    class="fa-list-alt"></i> <span
                                    class=notification>3</span></a></li>

                    </ul>
                </nav>
            </div>
            <!-- Start #header-area -->
            <div id=header-area class=fadeInDown>
                <?php

                include JPATH_ROOT.'/libraries/adminfrontend/admintop.php';

                ?>

            </div>
            <!-- End #header-area -->
        </div>
        <!-- Start .header-inner -->
    </div>
    <!-- End #header -->
    <!-- Start #sidebar -->


    <div id=sidebar>
        <!-- Start .sidebar-inner -->
        <div class=sidebar-inner>
            <!-- Start #sideNav -->
            <?php

            include JPATH_ROOT . '/libraries/adminfrontend/adminleft.php';

            ?>
            <!-- End #sideNav -->

        </div>
        <!-- End .sidebar-inner -->
    </div>
    <!-- End #sidebar -->
    <!-- Start #right-sidebar -->
    <?php
    // layer same photoshop
    include JPATH_ROOT.'/libraries/adminfrontend/rightsidebar.php';
    ?>

    <!-- End #right-sidebar -->
    <!-- Start #content -->
    <div id=content>
        <!-- Start .content-wrapper -->
        <div class=content-wrapper>
            <div class="row" style="padding-top: 50px">
                <div class="col-lg-12">
                    <div class="panel panel-primary panel-teal toggle  panelRefresh panel-screen-size">
                        <!-- Start .panel -->
                        <div class=panel-heading>
                            <h4 class=panel-title><i class="en-screen" title="pointer"></i><a class="website_properties"
                                                                                              href="javascript:void(0)"><i
                                        class="im-globe" title="properties"></i></a> <a class="page-properties"
                                                                                        href="javascript:void(0)"><?php echo $menuItemActive->title ?>(<?php echo $menuItemActive->menu_type_title ?>)</a>(<a
                                    class="page-properties" target="_blank"
                                    href="<?php echo str_replace('admin.', '', JUri::root()) ?>?Itemid=<?php echo $menuItemActive->id ?>"><?php echo $menuItemActive->title ?></a>)
                            </h4>
                        </div>
                        <div class="scroll-div-screen-size">
                            <div class="scroll-div1">
                            </div>
                        </div>
                        <div class="panel-body" style="overflow: auto">

                            <div class="iframelive">

                                <div class="screen-layout">
                                    <?php
                                    if($menuItemActiveId)
                                    {
                                        echo websiteHelperFrontEnd::displayLayout($this, $enableEditWebsite);
                                    }
                                    ?>
                                    <?php
                                    include JPATH_ROOT . '/components/com_utility/views/module/tmpl/default_item.php';
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class=row>
                <div class="col-lg-offset-2 col-lg-8">
                    <div class="panel panel-primary panel-teal toggle  panelRefresh panel-screen-size">
                        <!-- Start .panel -->
                        <div class="panel-body" style="overflow: auto">
                            <?php
                            include_once JPATH_ROOT . '/templates/sprflat/html/layouts/joomla/system/data_source.php';
                            ?>
                        </div>

                    </div>


                </div>
            </div>

        </div>
        <!-- End .content-wrapper -->
        <div class=clearfix></div>
    </div>
    <div id="dialog" title="Basic dialog">
        <div id="dialog-body" class="dialog-body"></div>
    </div>
    <div id="dialog_show_view" style="display: none;" title="Basic dialog">
        <div id="dialog_show_view_body" class="dialog_show_view_body">
            <?php echo JText::_('this dialog show view') ?>
        </div>
    </div>
    <div class="module-item-template">
        <?php
        include JPATH_ROOT . '/components/com_utility/views/module/tmpl/default_template.php';
        ?>
    </div>


    <!-- End #content -->
<?php } else { ?>

    <?php

    if($menuItemActiveId)
    {
        echo websiteHelperFrontEnd::displayLayout($this, 0);
    }
    $menu_supper_dashboard_item_id=MenusHelperFrontEnd::get_dashboard_menu_supper_admin_id();
    $menu_dashboard_item_id=MenusHelperFrontEnd::get_menu_dashboard_item_id();
    $menu_user_dashboard_item_id=MenusHelperFrontEnd::get_menu_user_dashboard_item_id();

    ?>

    <div class="edit_website"><i class="im-cog"></i></div>
    <div id="sidr">
        <ul>
            <li><a class="smooth" href="<?php echo JUri::root() ?>/?Itemid=<?php echo $menu_supper_dashboard_item_id ?>">Supper admin dashboard</a></li>
            <li><a class="smooth" href="<?php echo JUri::admin_current() ?>">Admin design</a></li>
            <li><a class="smooth" href="<?php echo JUri::root() ?>/?Itemid=<?php echo $menu_dashboard_item_id ?>">Admin dashboard</a></li>
            <li><a class="smooth" href="<?php echo JUri::root() ?>/?Itemid=<?php echo $menu_user_dashboard_item_id ?>">User dashboard</a></li>
            <li><a class="smooth" href="<?php echo JUri::root() ?>">Site</a></li>
        </ul>
    </div>

<?php } ?>
<!-- Javascripts -->
<!-- Load pace first -->
<?php
if ($enableEditWebsite) {
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            Joomla = window.Joomla || {};
            Joomla.design_website.init_design_website();
        });

    </script>
<?php
$script_content = ob_get_clean();
$script_content = JUtility::remove_string_javascript($script_content);
$doc->addScriptDeclaration($script_content,"text/javascript",'script_design_website');
}else
{
$listPositionsSetting= websiteHelperFrontEnd::$listPositionsSetting;
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            console.log('javascriptdisableedit');
            $('body').javascriptdisableedit({
                menuItemActiveId:<?php echo $menuItemActiveId?>,
                currentScreenSize: "<?php echo $currentScreenSize ?>",
                currentLink: "<?php echo $uri->toString() ?>",
                listPositionsSetting:<?php echo json_encode($listPositionsSetting) ?>

            });
        });

    </script>
    <?php
    $script_content = ob_get_clean();
    $script_content = JUtility::remove_string_javascript($script_content);
    $doc->addScriptDeclaration($script_content,"text/javascript",'script_javascriptdisableedit');

}
?>
<?php echo templateSprflatHelper::$DIV_CONSOLR ?>

<jdoc:include type="modules" name="debug"/>


<?php
if (JDEBUG) {
    $doc = JFactory::getDocument();
    $doc->addScript(JUri::root() . '/media/system/js/jumper-master/js/jumper.js');
    $scriptId = "plugin_debug";
    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".j-link").jumper({});

        });
    </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);

}

?>

<div class="div-loading"></div>
<style type="text/css">
    .div-loading {
        display: none;
        background: url("<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif") center center no-repeat;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 9999;
    }
</style>
</body>
</html>