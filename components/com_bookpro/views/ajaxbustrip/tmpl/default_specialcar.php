<?php
$listBusTrip= $displayData;
?>
<h3 class = "title"><?php echo JText::_('COM_BOOKPRO_BUS_SPECIAL_CAR_RENTAL_OFFERS') ?></h3>

<div class = "modspecial">
    <div class = "row-fluid mod2">

        <img src = "http://localhost/etravelservice/components/com_bookpro/assets/images/search.png">
        <h3 class = "title2">SPECIAL IRFARE BY SAME CARIER</h3>
        <div id = 'change'>
            <h3 class = "title3"><span>CHANGE CURY</span><i class = 'iconshowmore'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i></h3>

        </div>
    </div>
    <div class = "row-fluid mod3">
        <?php foreach($listBusTrip as $bustrip){ ?>
        <div class = "span<?php echo 12/count($listBusTrip) ?>">
            <a href = "#"><img src="<?php echo $bustrip->bus_image ?>"></a>
            <label class = "title4"><?php echo $bustrip->fromName ?> <?php echo $bustrip->toName ?> </label>
            <label class = "title5"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_SEDAN')  ?>:</label>
            <ul>
                            <?php foreach($bustrip->bus_facilities as $facility){ ?>
                                <li><a title="<?php echo $facility->title ?>" href = "#"> <img src = "<?php echo $facility->image ?>">
                            </a></li>
                            <?php } ?>
                        </ul>
            <label class = "title6"><?php echo CurrencyHelper::displayPrice($bustrip->price,0) ?> for <?php echo $bustrip->duration2 ?></label>
        </div>
        <?php } ?>
    </div>
    <div class = "showmore"> <a href = "#">Show More Airlines </a></div>
</div>


