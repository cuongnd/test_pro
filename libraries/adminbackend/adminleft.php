<?php
$doc = JFactory::getDocument();

$doc->addScript(JUri::root() . '/media/system/js/sidr-master/src/jquery.sidr.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/sidr-master/dist/stylesheets/jquery.sidr.light.css');
$doc->addStyleSheet(JUri::root() . '/libraries/adminbackend/assets/admin_left.css');
$doc->addScript(JUri::root() . '/libraries/adminbackend/assets/admin_left.js');

require_once JPATH_ROOT.'/components/com_modules/helpers/modules.php';
$modules=ModulesHelper::getModules(0);


JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_menus/models');
$menuModel=JModelLegacy::getInstance('Items','MenusModel');


$doc->addScript(JUri::root().'/media/system/js/bootstrap-switch-master/dist/js/bootstrap-switch.js');
$doc->addScript(JUri::root().'/media/system/js/bootstrap-switch-master/docs/js/highlight.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css');
JHtml::_('formbehavior.chosen', 'select');
?>
<div class="div-loading"></div>
<ul id=sideNav class="nav nav-pills nav-stacked">
    <li class=top-search>
        <form>
            <input name=search placeholder="Search ...">
            <button type=submit><i class="ec-search s20"></i></button>
        </form>
    </li>
    <li><a href="index.php?option=com_bookpro">Dashboard <i class=im-screen></i></a></li>
    <li><a href=#><i class=ec-mail></i> Tour Manager</a>
        <ul class="nav sub">
            <li><a href="index.php?option=com_bookpro&view=tours"><i class=ec-archive></i> Tour Listing</a></li>
            <li><a href=email-read.html><i class=br-eye></i> Allocation</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Payment</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Promotion</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Discount</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Hotel addon</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Stransfer addon</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Excursion addon</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Assign</a></li>
        </ul>
    </li>

    <li><a href=#>Setup <i class=im-paragraph-justify></i></a>
        <ul class="nav sub">
            <li><a href=forms.html><i class=ec-pencil2></i> Genaral Setup</a></li>
            <li><a href=form-validation.html><i class=im-checkbox-checked></i> Template</a></li>
            <li><a href=form-wizard.html><i class=im-wand></i>API setting</a></li>
            <li><a href=wysiwyg.html><i class=fa-pencil></i> Seo setting</a></li>
            <li><a href=wysiwyg.html><i class=fa-pencil></i> Language</a></li>
            <li><a href=wysiwyg.html><i class=fa-pencil></i> Module</a></li>
            <li><a href=wysiwyg.html><i class=fa-pencil></i> Payment</a></li>
        </ul>
    </li>
    <li><a href=#>Logistic <i class=im-table2></i></a>
        <ul class="nav sub">
            <li><a href=index.php?option=com_bookpro&view=tourlogistics><i class=en-arrow-right7></i> Country Geo</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> State/Province</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> City/Area</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> Group Size</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> Tour Class</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> Activities</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> Tour type</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> Hotel</a></li>
            <li><a href=data-tables.html><i class=en-arrow-right7></i> Physical Grade</a></li>
        </ul>
    </li>
    <li><a href=#>Tour Building <i class=st-lab></i></a>
        <ul class="nav sub">
            <li><a href=notifications.html><i class=fa-bell></i> Genneral Build</a></li>
            <li><a href=panels.html><i class=br-window></i> Highlights</a></li>
            <li><a href=tiles.html><i class=im-windows8></i> Itinerary</a></li>
            <li><a href=elements.html><i class=st-cube></i> Photos</a></li>
            <li><a href=icons.html><i class=im-stack></i> Documents</a></li>
            <li><a href=buttons.html><i class=im-play2></i> Tour price</a></li>
            <li><a href=calendar.html><i class=im-calendar2></i> Relations</a></li>
            <li><a href=grid.html><i class=st-grid></i> Hotel</a></li>
            <li><a href=typo.html><i class=im-font></i> FAQs</a></li>
        </ul>
    </li>

    <li><a href=#><i class=ec-mail></i> Reservation</a>
        <ul class="nav sub">
            <li><a href="index.php?option=com_bookpro&view=reservation"><i class=ec-archive></i> Tour Listing</a></li>
            <li><a href=email-read.html><i class=br-eye></i> Allocation</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Payment</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Promotion</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Discount</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Hotel addon</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Stransfer addon</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Excursion addon</a></li>
            <li><a href=email-write.html><i class=ec-pencil2></i> Assign</a></li>
        </ul>
    </li>

</ul>








<style type="text/css">
    .input-title
    {
        border: 1px #ccc dotted;
    }
</style>





<style type="text/css">
    .div-loading
    {
        display: none;
        background: url("<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif") center center no-repeat;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%
    }
</style>