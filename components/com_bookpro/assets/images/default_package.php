<?php
AImporter::model('packagetypes', 'tourpackages');
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
?>
<form  id="tourpackage" name="tourpackage" method="post"  action="index.php">

    <div class="overview_nav_tabs">     
        <?php echo $this->loadTemplate('itinerary_head'); ?>                            
        <div class="div2_overview_nav_tabs text-left">
            <p class="text-left" style="color:#cc0000; text-transform:uppercase; font-weight:bold; font-size:14px;">DEPARTURES AND PRICES  </p>
            <div class="content_div2_overiew_nav_tabs">
                <div class="content_div2_date_private">
                    <ul class="nav nav-pills pull-right" style="margin-bottom:5px;">
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


                    <table class="table" style="margin-bottom:0px;">
                        <thead style="background:#95a5a5; color:#fff;">
                            <tr>
                                <th  style="text-align:center; font-size:16px;">GROUP SIZE  AND PRICE PER PERSON</th>
                            </tr>
                            <tr>
                                <th style="text-align:center; font-size:12px; font-weight:bold; color:#ffff00!important;line-height:8px;"><span class="span_checkin">(Price vallid from <?php echo JFactory::getDate()->format('d-m-Y') ?> onward)</span> </th>
                            </tr>
                        </thead>
                    </table>       
                    <div class="div_content_table_tours_class">
                        <?php echo $this->loadTemplate('package_' . $this->tour->stype . '_price') ?>

                    </div>
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
                    <p class="where_tour"><?php echo JText::_('COM_BOOKPRO_WHERE_TO_STAY_IN_TOUR'); ?></p>
                    <div class="content_tab_tours_class">
                        <table  class="table">
                            <thead class="where_to_stay_tour">
                                <tr>
                                    <th style="width:150px"><?php echo JText::_('COM_BOOKPRO_TOUR_CLASS'); ?></th>
                                    <th style="width:260px;"><?php echo JText::_('COM_BOOKPRO_HOTEL_NAME'); ?></th>
                                    <th style="width:65px;"><?php echo JText::_('COM_BOOKPRO_STAR_RATE'); ?></th>
                                    <th style="width:180px;text-align:center"><?php echo JText::_('COM_BOOKPRO_HOTEL_INFORMATION'); ?></th>
                                </tr>
                            </thead>
                            <?php
                            $itineraryModel = new BookProModelItineraries();
                            $lists = array('tour_id' => $this->tour->id);
                            $lists['order'] = 'ordering';
                            $lists['order_Dir'] = 'DESC';

                            $itineraryModel->init($lists);
                            $itineraries = $itineraryModel->getData();

                            $sl = 0;
                            $classpage = "toursl";
                            $pagelimit = ceil(count($itineraries) / 2);

                            if ($itineraries) {
                                for ($t = 0; $t < count($itineraries); $t++) {
                                    if ($t != 0 && $t % 2 == 0) {
                                        $sl++;
                                    }
                                    $packagetypes = TourHelper::getPackages($this->tour->id);

                                    $destModel = new BookProModelAirport();
                                    $destModel->setId($itineraries[$t]->dest_id);
                                    $dest = $destModel->getObject();

                                    if ($packagetypes) {
                                        ?>
                                        <tbody style="background:#f6f9fa;" class="<?php echo $classpage . $sl; ?> <?php echo $classpage; ?>">
                                            <tr>
<!--                                                <td colspan="4" class="firt_title"><?php echo $itineraries[$t]->title . ' - ' . $dest->title; ?></td>-->
                                                <td colspan="4" class="firt_title"><?php echo $itineraries[$t]->title; ?></td>
                                            </tr>
                                            <?php
                                            for ($i = 0; $i < count($packagetypes); $i++) {
                                                $first = 0;
                                                $packageHotels = TourHelper::getPackageHotelsByTou_idAndItinerary_idAndPackagetype_id($this->tour->id, $itineraries[$t]->id, $packagetypes[$i]->id);
                                                if ($packageHotels) {
                                                    foreach ($packageHotels as $keys => $packageHotel) {
                                                        $hotel = '';
                                                        if ($packageHotel->hotel_id) {
                                                            $hotelModel = new BookProModelHotel();
                                                            $hotelModel->setId($packageHotel->hotel_id);
                                                            $hotel = $hotelModel->getObject();
                                                        }
                                                        ?>
                                                        <tr>
                                                            <?php if ($hotel) { ?>
                                                                <td class="div1_tour_class"><?php
                                                                    if ($first == 0) {
                                                                        echo $packagetypes[$i]->title;
                                                                    } $first++;
                                                                    ?></td>     
                                                                <td class="hotel_name"><?php echo $hotel->title; ?></td>
                                                                <td>
                                                                    <?php
                                                                    for ($r = 0; $r < 5; $r++) {
                                                                        if ($r < $hotel->rank) {
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
                                                                    <span class="Facilitics"><?php echo JText::_('COM_BOOKPRO_FACILITIES'); ?></span>
                                                                    <a rel="{handler: 'iframe', size: {x: 980, y: 480}}" class="jbmodal" href="index.php?option=com_bookpro&view=showimagesforhotel&tmpl=component&id=<?php echo $hotel->id; ?>" title="Show Images">
                                                                        <span class="Photos"><?php echo JText::_('COM_BOOKPRO_PHOTOS'); ?></span>
                                                                    </a>      
                                                                </td>
                                                            <?php } else { ?> 
                                                                <td class="div1_tour_class" colspan="4"><?php echo $packagetypes[$i]->title; ?></td>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>  
                                        </tbody>  
                                        <?php
                                    }
                                }
                            }
                            ?>             
                        </table>
                    </div>   

                    <div class="pull-right" style="padding-bottom:10px; padding-right:10px;">
                        <div class="slide-title pull-left"><a href="javascript:void(0);" class="action_item" key="<?php echo $classpage; ?>all"><span style="color:#007799;text-transform:uppercase;font-weight:bold;"><?php echo JText::_('COM_BOOKPRO_ALL_HOTELS'); ?></span></a></div>
                        <div class="slide-up pull-left action_item <?php echo $classpage; ?>next" key="<?php echo $classpage; ?>next"></div>
                        <div class="slide-down pull-left action_item <?php echo $classpage; ?>previous" key="<?php echo $classpage; ?>previous" style="opacity: 0.2;"></div>    
                    </div>

                </div>          


                <div class="content_div3_date_private " style="padding-top:35px">
                    <p class="note_tour_price">The  flight time from Danang to Ho Chi Minh City is approx 1 hour and may departs Ho Chi Minh in early morning. Therefore , a breakfast box is required and pre-payement of personal services at hotel need to be paid the day before.  The transfer from Hoi An to the airport in Danang is 45 minutes on good road conditions, plus stop time to visit the Marble Mountain. </p>
                </div>
				

            </div>

        </div>
    </div>

    <script type="text/javascript">
        //paging
        jQuery(document).ready(function() {
            var <?php echo $classpage; ?>min = 0;
            var <?php echo $classpage; ?>max = <?php echo $pagelimit; ?>;
            var <?php echo $classpage; ?>i = 0;
            jQuery(".<?php echo $classpage; ?>").hide();
            jQuery(".<?php echo $classpage; ?>0").show();
            jQuery('.action_item').click(function() {
                var checksl = jQuery(this).attr('key');
                if (checksl == '<?php echo $classpage; ?>previous') {
<?php echo $classpage; ?>i--;
                }
                if (checksl == '<?php echo $classpage; ?>next') {
<?php echo $classpage; ?>i++;
                }
                if (checksl == '<?php echo $classpage; ?>all') {
                    jQuery(".<?php echo $classpage; ?>").show();
                } else {
                    if (<?php echo $classpage; ?>i < <?php echo $classpage; ?>min) {
<?php echo $classpage; ?>i = <?php echo $classpage; ?>min;
                    }
                    if (<?php echo $classpage; ?>i > (<?php echo $classpage; ?>max - 1)) {
<?php echo $classpage; ?>i = <?php echo $classpage; ?>max - 1;
                    }
                    jQuery(".<?php echo $classpage; ?>").hide();
                    jQuery(".<?php echo $classpage; ?>" + <?php echo $classpage; ?>i).show();
                }
                
                jQuery(".action_item").removeAttr('style');            
                if(<?php echo $classpage; ?>i == <?php echo $classpage; ?>min){
                        jQuery(".<?php echo $classpage; ?>previous").attr('style','opacity: 0.2;');
                       
                }else if(<?php echo $classpage; ?>i == <?php echo $classpage; ?>max - 1){
                       jQuery(".<?php echo $classpage; ?>next").attr('style','opacity: 0.2;');
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

        rendercalendar();
        function rendercalendar()
        {
            $('input.checkin').datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                minDate: new Date(),
                showOn: "button",
                buttonImageOnly: true,
                buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
                onSelect: function(selected) {
                    $('.span_checkin').html('(Price vallid from ' + selected.toString('dddd, MMMM Do YYYY') + ' onward)');
                    var selected = $(this).datepicker('getDate');
                    $('input.checkin').datepicker('setDate', selected);
                    getajax_showform_package_price();

                }
            });
        }
        function getajax_showform_package_price()
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tour',
                        tour_id: $('input[name="tour_id"]').val(),
                        checkin: $('input[name="checkin"]').val(),
                        task: 'getajax_showform_package_price'
                    }
                    $data = $.param($data);
                    $data1 = $('.frontTourForm.children_acommodation *').serialize();

                    $data = $data + '&' + $data1;
                    console.log($data);
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.div_content_table_tours_class').html($result);
                    rendercalendar();
                }
            });
        }
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
<style type="text/css">
    table.content_table_tours_class tr  td
    {
        padding: 5px;
        vertical-align: middle;
    }
</style>

