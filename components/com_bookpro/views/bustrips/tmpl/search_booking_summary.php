<?php
AImporter::helper('currency');
$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
$cart->load();
?>
<div class="header row-fluid"><h3><?php echo JText::_('Booking Summary') ?></h3></div>
<div class="body row-fluid">
    <div class="sub-wrapper-content">
        <div class="row-fluid">
            <div class="pull-right service"><?php echo JText::_('Service') ?></div>
        </div>
        <div class="row-fluid car-car_type">
            <div class="image pull-left"><img class="item-image" src="<?php echo JUri::root() ?><?php echo $this->bookingBustrip->bus_image ?>"></div>
            <div class="car-type pull-left"><?php echo $this->bookingBustrip->bus_title ?></div>
        </div>
        <div class="row-fluid wapper-booking-detail">
            <div class="wapper depart pull-left">
                <div><?php echo $this->bookingBustrip->dest_from_parent_title ?></div>
                <div><?php echo $this->bookingBustrip->dest_from_title ?></div>
                <div>Sunday 31 Aug</div>
            </div>
            <div class="wapper arrival pull-right">
                <div><?php echo $this->bookingBustripdest_to_parent_title ?></div>
                <div><?php echo $this->bookingBustrip->dest_to_title ?></div>
                <div>Sunday 31 Aug</div>
            </div>
        </div>
        <div>
            <div class="round-car-bg row-fluid"><div class="wapper"><span class="icon-clock time"><?php echo $this->bookingBustrip->start_time ?></span><span class="space">|</span><span class="round-type"><?php echo  $this->bookingBustrip->roundtrip?'Round trip':'one trip' ?></span></div></div>
        </div>
        <div class="trip-details">
            <div class="header trip-details"><?php echo JText::_('Trip details') ?></div>
            <div class="body">
                <div class="row-fluid">
                    <div class="pull-right rental"><?php echo JText::_('Rental') ?></div>
                </div>
                <div>
                    <table class="trip-detail">
                        <tr>
                            <th style="width: 50%">
                                <div class="row-fluid">
                                    <div class="car-type  icon-question pull-left"><?php echo $this->bookingBustrip->bus_title ?></div>
                                    <div class="quality pull-right">1</div>
                                </div>
                            </th>
                            <td><?php echo CurrencyHelper::displayPrice($this->bookingBustrip->price,0); ?></td>
                        </tr>
                        <tr>
                            <th><div class="fee-surcharge icon-question">Free & Surcharge</div></th>
                            <td>US$ 0</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php echo JText::_('All price are quoted in USD. The total price include all mandatory taxes, toll fees, parking fees, driver and gaso-line') ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row-fluid main-price">
                                    <div class="must-pay pull-left"><div class="you pull-left"> <?php echo JText::_('You pay') ?></div><div class="price pull-left"> <?php echo CurrencyHelper::displayPrice($cart->total,0); ?></div></div>
                                    <div class="rental-rule pull-right"><?php echo JText::_('Rental rule') ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

