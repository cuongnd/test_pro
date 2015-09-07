<?php
AImporter::model('packagetypes', 'tourpackages');
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$model_packagetype = new BookProModelPackageTypes();
$now=  JFactory::getDate();
$checkin = JFactory::getDate($now->format('Y-m-d'));

AImporter::model('tour');
$module_tour = new BookProModelTour();
$module_tour->setId(JRequest::getVar('id'));
$tour = $module_tour->getObject();
$db = JFactory::getDbo();


$arr_person = array();
$packagetypes = $model_packagetype->getData();
$tour_id = JRequest::getVar('id');
foreach ($packagetypes as $key_packagetype => $packagetype) {
    $lists = array();
    $lists['tour_id'] = $tour_id;
    $lists['packagetype_id'] = $packagetype->id;
    $modle_tourpackage = new BookProModelTourPackages();
    $modle_tourpackage->init($lists);
    $tourpackages = $modle_tourpackage->getData();
    foreach ($tourpackages as $key=>$tourpackage) {
        $query = $db->getQuery(true);
        $query->select('packagerate.adult');
        $query->from('#__bookpro_packagerate as packagerate');
        $query->where('packagerate.date='.$db->quote($checkin->toSql()));
        $query->where('packagerate.tourpackage_id='.$tourpackage->id);
        $db->setQuery($query);
       
        $result=$db->loadResult();
        $tourpackages[$key]->price=$result;
        if (!in_array($tourpackage->min_person, $arr_person)) {
            array_push($arr_person, $tourpackage->min_person);
        }
    }
    $packagetypes[$key_packagetype]->tourpackages = $tourpackages; 
}
?>
<form  id="tourpackage" name="tourpackage" method="post"  action="index.php">

    <div class="overview_nav_tabs">
        <div class="div1_overview_nav_tabs text-left">
            <p style="color:#333366; font-size:18px;font-weight:bold;">PASSION OF SOUTH-EASTERN ASIA  </p>
            <div class="row-fluid">
                <div class="span8">

                    <p class="text_div1_overview">Trip code : PSEA 120,  private departures</p>
                    <p class="text_div1_overview" style="padding-bottom:10px;">Country visited: Vietnam, Laos, Cambodia, Thailand  </p>
                    <span class="content_img">       
                        <?php echo $this->imagesAct; ?>       
                    </span>
                </div>
                <div class="span4">
                    <p class="text2_div1_overview">Tour length: 20 days  and 19 nights Minum price from US$ 1520/person </p>
                    <button type="button" class="btn button_div1_overview">CHECK THE AVAILABILITY</button>
                </div>
            </div>
        </div>
        <div class="div2_overview_nav_tabs text-left">
            <p class="text-left" style="color:#cc0000; text-transform:uppercase; font-weight:bold; font-size:14px;padding-top:10px; padding-left:10px;">DEPARTURES AND PRICES  </p>
            <div class="content_div2_overiew_nav_tabs">
                <div class="content_div2_date_private">
                    <ul class="nav nav-pills pull-right">
                        <li>
                            <a href="#" class="icon_g">
                                Guranteed Departure  
                            </a>
                        </li>
                        <li>
                            <a href="#" class="icon_r">
                                Request Place 
                            </a>
                        </li>
                        <li>
                            <a href="#" class="icon_a">
                                Available Place  
                            </a>
                        </li>
                        <li>
                            <a href="#" class="icon_c">
                                Close
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clr"></div>


                <div class="content_div2_date_private">
                    <input class="inputbox required checkin" type="text" name="checkin" value="" placeholder="<?php echo JText::_('COM_BOOKPRO_CHECKIN'); ?>" />

                  <table class="table" style="margin-bottom:0px;">
                        <thead style="background:#95a5a5; color:#fff;">
                            <tr>
                                <th  style="text-align:center; font-size:16px;">GROUP SIZE  AND PRICE PER PERSON</th>
                            </tr>
                            <tr>
                                <th style="text-align:center; font-size:12px; font-weight:bold; color:#ffff00;">(Price vallid from  7 April, 2013 onward) </th>
                            </tr>
                        </thead>
                  </table>       
                                     
                    <table class="table">
                        <thead style="background:#95a5a5; color:#fff;">
                            <tr>
                                <th style="padding-left:10px; font-size:14px;">TOUR CLASS</th>
                                <?php foreach ($arr_person as $array_person_item) { ?>
                                    <th> <?php echo $array_person_item ?>.</th>
                                <?php } ?>

                                <th colspan="2"> AMEND DATE</th>
                            </tr>
                        </thead>
                        <tbody style="background:#f7f7f7;">
                            <?php foreach ($packagetypes as $packagetype) { ?>

                                <tr style="border-bottom:2px solid #fff!important;">
                                    <td><?php echo $packagetype->title ?></td>
                                    <?php
                                    foreach ($arr_person as $array_person_item) {
                                        foreach ($packagetype->tourpackages as $tourpackage) {
                                            $price = 0;
                                            if ($tourpackage->min_person == $array_person_item) {
                                                $price = $tourpackage->price;
                                                break;
                                            }
                                            ?>

                                        <?php } ?>
                                        <td><?php echo $price ? $price : 'unable' ?>  </td>
                                    <?php } ?>


                                    <td><a class="bookingtourpackage" data="{&quot;packagetype_id&quot;:<?php echo $packagetype->id ?>,&quot;tour_id&quot;:<?php echo JRequest::getVar('id') ?>}" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></a> </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

                <div class="content_div3_date_private">
                    <p class="note_date_private">TO FIND THE ACCURATE RPICE FOR YOUR  TRAVEL DATE, YOU NEED TO  CLICK THE CALENDAR ICONTHE DEPARTURE WITH DISCOUNT PRICE: 28 OCT,  5 NOV,  8 DEC... BROWSE TO SEE THE PROMOTION    </p>
                </div>
                <div class="content_div3_date_private " style="padding-top:20px">
                    <p class="note_tour_price">The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                </div>
                <div class="content_div3_date_private ">
                    <p class="note_tour_price">The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                </div>

                <div class="content_div4_date_private ">
                    <p class="where_tour">WHERE TO STAY IN  TOUR ?</p>
                    <div class="content_tab_tours_class">
                        <table class="table">
                            <thead style="background:#95a5a5!important;text-transform:uppercase; color:#fff; font-weight:normal;">
                                <tr>
                                    <th>TOUR CLASS   </th>
                                    <th>HOTEL NAME</th>
                                    <th>STAR RATE </th>
                                    <th>HOTEL INFORMATION</th>
                                </tr>
                            </thead>
                            <?php
                            $tours = TourHelper::getTours();
                            if ($tours) {
                                $sl = 0;
                                for ($t = 0; $t < count($tours); $t++) {
                                    $tourPackageByTourId = TourHelper::getTourPackagesByTourId($tours[$t]->id);
                                    if ($tourPackageByTourId) {
                                        ?>
                                        <tbody style="background:#f6f9fa;" class="toursl<?php echo $sl; ?> toursl">
                                            <tr>
                                                <td colspan="4" class="firt_title"><?php echo $tours[$t]->title ?></td>
                                            </tr>
                                            <?php
                                            for ($i = 0; $i < count($tourPackageByTourId); $i++) {
                                                $hotelsByPackageId = TourHelper::getHotelByPackageId($tourPackageByTourId[$i]->id);

                                                if ($hotelsByPackageId) {
                                                    for ($j = 0; $j < count($hotelsByPackageId); $j++) {
                                                        ?>
                                                        <tr>
                                                            <td class="div1_tour_class"><?php
                                                                if ($j == 0) {
                                                                    echo $tourPackageByTourId[$i]->packagetitle;
                                                                }
                                                                ?></td>
                                                            <td class="hotel_name"><?php echo $hotelsByPackageId[$j]->title; ?></td>
                                                            <td>
                                                                <?php
                                                                for ($r = 0; $r < 5; $r++) {
                                                                    if ($r < $hotelsByPackageId[$j]->rank) {
                                                                        ?>
                                                                        <img src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/icon_star.png"; ?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <img src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/icon_star_white.png"; ?>">  
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <span class="Facilitics">FACILITIES</span>
                                                                <a rel="{handler: 'iframe', size: {x: 980, y: 480}}" class="jbmodal" href="index.php?option=com_bookpro&view=showimagesforhotel&tmpl=component&id=<?php echo $hotelsByPackageId[$j]->id; ?>" title="Show Images">
                                                                    <span class="Photos">PHOTOS </span>
                                                                </a>      
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>  
                                        </tbody>  
                                        <?php
                                        if ($t % 2 == 1) {
                                            $sl++;
                                        }
                                    }
                                }
                            }
                            ?>             
                        </table>
                    </div>   

                    <div class="pull-right" style="padding-top:10px;padding-bottom:10px; padding-right:10px;">
                        <div class="slide-title pull-left"><a href="javascript:void(0);" class="action_item" key="all"><span style="color:#007799;text-transform:uppercase;font-weight:bold;">All hotels</span></a></div>
                        <div class="slide-up pull-left action_item" key="previous"></div>
                        <div class="slide-down pull-left action_item" key="next"></div>

                    </div>
                </div>


                <div class="content_div3_date_private " style="padding-top:35px">
                    <p class="note_tour_price">The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                </div>


            </div>

        </div>
    </div>

    <script type="text/javascript">
        //paging WHERE TO STAY IN TOUR
        jQuery(document).ready(function() {
            var min = 0;
            var max = <?php echo ceil(count($tours) / 2); ?>;
            var i = 0;
            jQuery(".toursl").hide();
            jQuery(".toursl0").show();
            jQuery('.action_item').click(function() {
                var checksl = jQuery(this).attr('key');
                if (checksl == 'previous') {
                    i--;
                }
                if (checksl == 'next') {
                    i++;
                }
                if (checksl == 'all') { //i=0;
                    jQuery(".toursl").show();
                } else {
                    if (i < min) {
                        i = min;
                    }
                    if (i > (max - 1)) {
                        i = max - 1;
                    }
                    jQuery(".toursl").hide();
                    jQuery(".toursl" + i).show();
                }
            });
        });
    </script>

    <input type="hidden" name="option"	value="<?php echo JRequest::getVar('option') ?>" />
    <input type="hidden" name="<?php echo $this->token ?>" value="1" />
    <input type="hidden" name="task" value="">
    <input type="hidden" name="packagetype_id" value="">
    <input type="hidden" name="tour_id" value="<?php echo JRequest::getVar('id') ?>">
    <input type="hidden" name="controller" value="tourbook">
</form>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('input.checkin').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            showOn: "button",
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
//                        var selected = $('.hotel_search #checkin').datepicker('getDate');
//                        selected.setDate(selected.getDate() + 1);
//                        $('.hotel_search #checkout').datepicker('setDate', selected);
//                        $(".hotel_search #checkout").datepicker("option", {
//                            minDate: selected
//                        });
            }
        });
        $(document).on('click', '.bookingtourpackage', function() {
            submitbookingtourpackage($(this).attr('data'));
        });
        function submitbookingtourpackage($data)
        {
            if ($('input.checkin').val() == '')
            {
                alert('<?php echo JText::_('COM_BOOKPRO_CHECKIN_IS_REQUIMENT') ?>');
                $('input.checkin').focus();
                return false;
            }
            $data = $.parseJSON($data);
            $form = $("#tourpackage");
            $form.find('input[name="task"]').val('bookingtourpackage');
            $form.find('input[name="packagetype_id"]').val($data.packagetype_id);
            $form.find('input[name="tour_id"]').val($data.tour_id);

            $form.submit();


        }
    });

</script>
