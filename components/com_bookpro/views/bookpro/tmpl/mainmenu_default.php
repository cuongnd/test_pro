<?php
$lessInput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/less/view-bookpro-mainmenu-default.less';
$cssOutput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/css/view-bookpro-mainmenu-default.css';
BookProHelper::compileLess($lessInput,$cssOutput);
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/administrator/components/com_bookpro/assets/css/view-bookpro-mainmenu-default.css');
$doc->addScript(JUri::root().'/administrator/components/com_bookpro/assets/js/view-bookpro-mainmenu-default.js');
?>
<div class="view-bookpro-mainmenu-default">

<?php
// Start Tabs
echo '<div class="tabbable_main_menu tabs-top">';
echo JHtml::_('bootstrap.startTabSet', 'tab_group_menu_bookpro', array('active' => 'tabs_dashboard'));
// Tab 1
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_dashboard', 'Dashboard');
echo <<<HTML

<div class="pull-right search-tour">
    <div class="input-prepend input-append">
        <span class="add-on">Tour name</span>
        <input class="input-medium" id="appendedPrependedInput" type="text">
        <span class="add-on">Go</span>
    </div>
</div>
<div class="pull-right quick">
    Quick
</div>
HTML;

echo JHtml::_('bootstrap.endTab');
// Tab 2
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_2a', 'System setup');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-genaral-setup"></i>Genaral Setup</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-template"></i>Template</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-api-setting"></i>API setting</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-seo-setting"></i>Seo setting</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-language"></i>Language</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-module"></i>Module</a>
        </li>

    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-payment"></i>Payment</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// Tab 3
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_3a', 'Logistic');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-country-geo"></i>Country Geo</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-state-province"></i>State/Province</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-city-area"></i>City/Area</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-group-size"></i>Group Size</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-class"></i>Tour Class</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-activities"></i>Activities</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-type"></i>Tour type</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-hotel"></i>Hotel</a>
        </li>

        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-physical-grade"></i>Physical Grade</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 4
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_4a', 'Tour Building');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-genneral-build"></i>Genneral Build</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-highlights"></i>Highlights</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-itinerary"></i>Itinerary</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-photos"></i>Photos</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-documents"></i>Documents</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-price"></i>Tour price</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-relations"></i>Relations</a>
        </li>

    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-hotel"></i>Hotel</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-faqs"></i>FAQs</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 5
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_5a', 'Tour Manager');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-listing"></i>Tour Listing</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-allocation"></i>Allocation</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-payment"></i>Payment</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-promotion"></i>Promotion</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-discount"></i>Discount</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-hotel-addon"></i>Hotel addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-stransfer-addon"></i>Stransfer addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-excursion-addon"></i>Excursion addon</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-sssign"></i>Assign</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 6
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_6a', 'Reservation');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-listing"></i>Tour Listing</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-allocation"></i>Allocation</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-payment"></i>Payment</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-promotion"></i>Promotion</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-discount"></i>Discount</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-hotel-addon"></i>Hotel addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-stransfer-addon"></i>Stransfer addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-excursion-addon"></i>Excursion addon</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-assign"></i>Assign</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 7
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_8a', 'Customer');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-listing"></i>Tour Listing</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-allocation"></i>Allocation</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-payment"></i>Payment</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-promotion"></i>Promotion</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-discount"></i>Discount</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-hotel-addon"></i>Hotel addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-stransfer-addon"></i>Stransfer addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-excursion-addon"></i>Excursion addon</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-assign"></i>Assign</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 8
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_9a', 'Tour Enquiry');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-tour-listing"></i>Tour Listing</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-allocation"></i>Allocation</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-payment"></i>Payment</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-promotion"></i>Promotion</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-discount"></i>Discount</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-hotel-addon"></i>Hotel addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-stransfer-addon"></i>Stransfer addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Excursion addon</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Assign</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 9
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_9a', 'Report');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Tour Listing</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Allocation</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Payment</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Promotion</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Discount</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Hotel addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Stransfer addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Excursion addon</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Assign</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
// Tab 10
echo JHtml::_('bootstrap.addTab', 'tab_group_menu_bookpro', 'tabs_10a', 'Support');
echo <<<HTML
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Tour Listing</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Allocation</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Payment</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Promotion</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Discount</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Hotel addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Stransfer addon</a>
        </li>
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Excursion addon</a>
        </li>
    </ul>
    <ul class="pull-left">
        <li>
            <a href="index.php?option=com_bookpro&view=tours"><i class="icon-loop"></i>Assign</a>
        </li>
    </ul>
HTML;

echo JHtml::_('bootstrap.endTab');
// End Tabs
echo JHtml::_('bootstrap.endTabSet');
echo '</div>';
?>
</div>