<?php
defined('_JEXEC') or die('Restricted access');
?>
<style type = "text/css">
    .tooltip {
        display: none;
        position: absolute;
        border: 1px solid #333;
        background-color: #161616;
        border-radius: 5px;
        padding: 10px;
        color: #fff;
        font-size: 12px Arial;
    }

    .content_facility {

    }

    .content_facility h3 {
        line-height: 20px;
        margin: 0px;
        color: #cb0000;
        text-transform: uppercase;
        padding-left: 10px;
        font-weight: normal;
        padding-top: 8px;
        border-bottom: 1px solid #cb0000;
        padding-bottom: 5px;
    }

    .content_facility .facilitiesicon li {
        float: none !important;
        display: inline;
    }

    .current .tabs {
        height: 260px;
        overflow-x: scroll;
    }

    #hotel_tab_group_id {
        margin: 0px;
    }

    .content_facility .facilities li:first-child {

    }

    .content_facility {
        padding: 0 8px;
    }

    #myCarousel {
        margin-bottom: 0px;
    }

    .carousel-indicators {
        height: 350px;
        overflow-y: scroll;
    }

    .carousel-indicators li {
        background-position: center center;
        text-indent: inherit;
        height: 66px;
        border-radius: 0;
        width: 66px;
        border: 2px solid #fff;
        margin: 0px;

    }

    .carousel.carousel-fade .item {
        -webkit-transition: opacity 0.5s ease-in-out;
        -moz-transition: opacity 0.5s ease-in-out;
        -ms-transition: opacity 0.5s ease-in-out;
        -o-transition: opacity 0.5s ease-in-out;
        transition: opacity 0.5s ease-in-out;
        opacity: 0;
    }

    .carousel.carousel-fade .active.item {
        opacity: 1;
    }

    .carousel.carousel-fade .active.left,
    .carousel.carousel-fade .active.right {
        left: 0;
        z-index: 2;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .carousel.carousel-fade .next,
    .carousel.carousel-fade .prev {
        left: 0;
        z-index: 1;
    }

    .carousel.carousel-fade .carousel-control {
        z-index: 3;
    }

    .carousel-indicators li:hover {
        border-color: #FFCB00;
    }

    .carousel-inner .item img {
        width: 100% !important;
        height: 350px !important;
    }

    .content_facility .facilities li {
        display: inline-block;
        height: 21px;
        margin: 5px 10px 0 0;
        padding: 0 7px 0 14px;
        white-space: nowrap;
        position: relative;
        background: -moz-linear-gradient(top, #fed970 0%, #febc4a 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fed970), color-stop(100%, #febc4a));
        background: -webkit-linear-gradient(top, #fed970 0%, #febc4a 100%);
        background: -o-linear-gradient(top, #fed970 0%, #febc4a 100%);
        background: linear-gradient(to bottom, #fed970 0%, #febc4a 100%);
        background-color: #FEC95B;
        color: #963;
        font: bold 11px/21px Arial, Tahoma, sans-serif;
        text-decoration: none;
        text-shadow: 0 1px rgba(255, 255, 255, 0.4);
        border-top: 1px solid #EDB14A;
        border-bottom: 1px solid #CE922E;
        border-right: 1px solid #DCA03B;
        border-radius: 1px 3px 3px 1px;
        box-shadow: inset 0 1px #FEE395, 0 1px 2px rgba(0, 0, 0, 0.21);
    }

    .content_facility .facilities li:before {
        content: '';
        position: absolute;
        top: 5px;
        left: -6px;
        width: 10px;
        height: 10px;
        background: -moz-linear-gradient(45deg, #fed970 0%, #febc4a 100%);
        background: -webkit-gradient(linear, left bottom, right top, color-stop(0%, #fed970), color-stop(100%, #febc4a));
        background: -webkit-linear-gradient(-45deg, #fed970 0%, #febc4a 100%);
        background: -o-linear-gradient(45deg, #fed970 0%, #febc4a 100%);
        background: linear-gradient(135deg, #fed970 0%, #febc4a 100%);
        background-color: #FEC95B;
        border-left: 1px solid #EDB14A;
        border-bottom: 1px solid #CE922E;
        border-radius: 0 0 0 2px;
        box-shadow: inset 1px 0 #FEDB7C, 0 2px 2px -2px rgba(0, 0, 0, 0.33);
    }

    .content_facility .facilities li:before {
        -webkit-transform: scale(1, 1.5) rotate(45deg);
        -moz-transform: scale(1, 1.5) rotate(45deg);
        -ms-transform: scale(1, 1.5) rotate(45deg);
        transform: scale(1, 1.5) rotate(45deg);
    }

    .content_facility .facilities li:after {
        content: '';
        position: absolute;
        top: 7px;
        left: 1px;
        width: 5px;
        height: 5px;
        background: #FFF;
        border-radius: 4px;
        border: 1px solid #DCA03B;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2), inset 0 1px 1px rgba(0, 0, 0, 0.21);
    }

    .content_facility .facilities li:hover {
        color: #FFF;
        text-shadow: -1px -1px 0 rgba(153, 102, 51, 0.3);
    }

    .facilities {
        margin: 0px !important;
        padding: 0 !important;
    }

    #tabhotelTabs > li > a {
        font-size: 12px !important;
        text-transform: none !important;
    }

    .hotel_desc #tabhotelTabs li a {
        background: #ffffff; /* Old browsers */
        background: -moz-linear-gradient(top, #ffffff 30%, #efefef 100%);
        /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(30%, #ffffff), color-stop(100%, #efefef)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #ffffff 30%, #efefef 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #ffffff 30%, #efefef 100%);
        /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #ffffff 30%, #efefef 100%);
        /* IE10+ */
        background: linear-gradient(to bottom, #ffffff 30%, #efefef 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#efefef', GradientType=0); /* IE6-9 */
        border: 1px solid #e1e1e1;
        font-weight: bold;
        color: #313131;
    }

    .hotel_desc span6 .nav-tabs > .active > a, .nav-tabs > .active > a:hover, .nav-tabs > .active > a:focus {
        background: #fff !important;
    }

    .nav-tabs > .active > a, .nav-tabs > .active > a:hover, .nav-tabs > .active > a:focus {
        background: #fff !important;
        border-bottom-color: transparent !important;
    }

    .hotel_desc #tabhotelTabs li a:hover {
        background: #fff;
    }

    #tab1.table th, .table td {
        line-height: 14px;
    }

    #tab1.table-bordered th, .table-bordered td {
        border: none !important;
    }

    .table-bordered {
        border: none !important;
    }

    #tab1.table {
        margin-top: 20px;
    }

    #tabhotelContent h4 {
        font-size: 12px;
    }

    #hotelBook .hoteltitle {

    }

    .hotel_desc p {
        font-size: 16px;
    }

    .contact_us tr td {
        font-size: 16px;
    }

    .table_hotel_desc tr td:first-child, .contact_us tr td:first-child {
        font-weight: bold;
    }

    .hotel_desc p {
        font-size: 16px;
        padding: 5px;
    }

    .carousel-indicators {
        top: 0;
        position: relative;
    }

    .contact_us tr td {
        font-size: 16px;
    }

</style>

<div class = "info">

    <div class = "row-fluid">
        <div class = "map_des_wapper">
            <div class = "span6 map">
                <?php
                require(JPATH_COMPONENT_FRONT_END_SITE . '/views/googlemap/tmpl/default.php');
                ?>
            </div>
            <div class = "span6 des"><?php echo htmlspecialchars_decode($this->hotel['HotelDetails']['propertyDescription']) ?></div>
        </div>
    </div>
    <div class = "row-fluid">
        <?php echo JHtml::_('bootstrap.startTabSet', 'hotel_tab', array('active' => 'hotel-detail')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'hotel_tab', 'hotel-detail', JText::_('COM_BOOKPRO_EXPEDIA_HOTEL_DETAIL')); ?>

        <h2><?php echo JText::_('COM_BOOKPRO_STAYBRIDGE_SUITES_TIMES_SQUARE_AMENITIES')?></h2>
        <?php if($propertyAmenity=$this->hotel['PropertyAmenities']['PropertyAmenity']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_GENERAL') ?></b></div>
            <div class = "span10 facilities_room facilities_category">
                <?php
                $layout = new JLayoutFile('expediahotelfacilitytext', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                $html = $layout->render($propertyAmenity);
                echo $html;
                ?>
                <?php //echo htmlspecialchars_decode($this->hotel['HotelDetails']['areaInformation']) ?></div>
        </div>
        <?php } ?>

        <?php
        $roomType=$this->hotel['RoomTypes']['RoomType'];

        $roomType=$roomType[0]?$roomType:array($roomType);
        $roomAmenity=$roomType[0]['roomAmenities']['RoomAmenity'];
        $roomAmenity=$roomAmenity[0]?$roomAmenity:array($roomAmenity);
        if($roomAmenity){ ?>
            <div class = "row-item row-fluid">
                <div class = "span2"><b><?php echo JText::_('Room Amenities') ?></b></div>
                <div class = "span10 facilities_room facilities_category">
                    <?php
                    $layout = new JLayoutFile('expediahotelfacilitytext', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                    $html = $layout->render($roomAmenity);
                    echo $html;
                    ?>
                </div>
            </div>
        <?php } ?>

        <?php if($areaInformation=$this->hotel['HotelDetails']['areaInformation']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_EXPEDIA_NEARBY_ATTRACTIONS_AREAS') ?></b></div>
            <div class = "span10 nearby-attractions">
                <?php echo htmlspecialchars_decode($areaInformation) ?>
            </div>
        </div>
        <?php } ?>

        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_EXPEDIA_CHECK_IN_CHECK_OUT') ?></b></div>
            <div class = "span10 Check-in-Check-out">
                <div><b><?php echo JText::_('Check-in') ?></b>:<?php echo $this->hotel['HotelDetails']['checkInTime'] ?></div>
                <div><b><?php echo JText::_('Check-out') ?></b>:<?php echo $this->hotel['HotelDetails']['checkOutTime'] ?></div>
            </div>
        </div>

        <?php if($hotelPolicy=$this->hotel['HotelDetails']['hotelPolicy']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('Policy') ?></b></div>
            <div class = "span10 nearby-attractions">
                <?php echo htmlspecialchars_decode($hotelPolicy) ?>
            </div>
        </div>
        <?php } ?>
        <?php if($roomInformation=$this->hotel['HotelDetails']['roomInformation']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_HOTEL_ROOM_INFORMATION') ?></b></div>
            <div class = "span10 nearby-attractions">
                <?php echo htmlspecialchars_decode($roomInformation) ?>
            </div>
        </div>
        <?php } ?>
        <?php if($drivingDirections=$this->hotel['HotelDetails']['drivingDirections']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_HOTEL_NEAREST_AIRPORT') ?></b></div>
            <div class = "span10 nearby-attractions">
                <?php echo htmlspecialchars_decode($drivingDirections) ?>
            </div>
        </div>
        <?php } ?>
        <?php if($checkInInstructions=$this->hotel['HotelDetails']['checkInInstructions']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_EXPEDIA_EXTRA_PERSON_FEES') ?></b></div>
            <div class = "span10 nearby-attractions">
                <?php echo htmlspecialchars_decode($checkInInstructions) ?>
            </div>
        </div>
        <?php } ?>
        <?php if($locationDescription=$this->hotel['HotelDetails']['locationDescription']){ ?>
        <div class = "row-item row-fluid">
            <div class = "span2"><b><?php echo JText::_('COM_BOOKPRO_EXPEDIA_LOCATION_DESCRIPTION') ?></b></div>
            <div class = "span10 nearby-attractions">
                <?php echo htmlspecialchars_decode($locationDescription) ?>
            </div>
        </div>
        <?php } ?>


        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'hotel_tab', 'customer-reviews', JText::_('Customer Reviews')); ?>
        content
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>


    <div class = "row-fluid">
        <div class = "content_facility">


        </div>
    </div>
</div>
<script>
    !function ($) {

        $(function () {
            $('#myCarousel').carousel({
                interval: 6000,
                pause: "hover"
            });
            // carousel demo


//             $('#myCarousel .carousel-inner .item').width(780);
//             $('#myCarousel .carousel-inner .item img').width(780);
//             $('#myCarousel .carousel-inner .item').height(330);
//             $('#myCarousel .carousel-inner .item img').height(330);

        });
    }(window.jQuery)
</script>

<script type = "text/javascript">
    jQuery(document).ready(function ($) {
    });
</script>
