<?php
$doc=JFactory::getDocument();
?>
<?php
$numberItemOnOnePage=10;
$this->numberItemOnOnePage=$numberItemOnOnePage;
$js=<<<javascript
            var numberItemOnOnePage={$numberItemOnOnePage};
javascript;
$doc->addScriptDeclaration($js);
/*echo "<pre>";
print_r($this->bookingBustrip);
die;*/

?>
<div class="row-fluid car-rentals">
    <div class="row-fluid header">
        <div class="car-rental-left float-left">
            <div class="row-fluid title"><?php echo JText::_('car rentals') ?></div>
            <div class="row-fluid from-to"><span class="from"><?php echo $this->bookingBustrip->dest_from_parent_title ?></span><span class="from-to-icon"><img src="<?php echo JUri::root() ?>/components/com_bookpro/assets/images/icons/icon-from-to.png"></span><span class="to"><?php echo $this->bookingBustrip->dest_to_parent_title ?></span></div>
            <div class="row-fluid time">friday,29 aug,2014</div>
        </div>
        <div class="car-rental-right float-right">
            <div class="prev-day float-left">
                <div class="row-fluid title-prev-day"><a class="prev-day" href="javascript:void(0)"><?php echo JText::_('Prev day') ?></a></div>
                <div class="row-fluid price">0,123,456 $</div>
            </div>
            <div class="next-day float-left">
                <div class="row-fluid title-next-day"><a class="next-day" href="javascript:void(0)"><?php echo JText::_('Next day') ?></a></div>
                <div class="row-fluid price">1,345,678 $</div>
            </div>
            <div class="chart-map float-left"></div>
        </div>
    </div>
    <div id="search-car-rentals" class="row-fluid body data-car-rentals">
        <?php
        echo $this->loadTemplate('data_car_rentals');
        ?>
    </div>
    <div class="row-fluid footer">
        <div class="wapper-footer float-right">
            <div class="more-offers float-left"><?php echo JText::_('More offers') ?></div>
            <div class="control-top icon-  float-left"><a href="javascript:void(0)"><img src="<?php echo JUri::root() ?>/components/com_bookpro/assets/images/icons/icon-top.png"></a></div>
            <div class="control-down icon- icon-down float-left"><a href="javascript:void(0)"><img src="<?php echo JUri::root() ?>/components/com_bookpro/assets/images/icons/icon-down.png"></a></div>
        </div>
    </div>
</div>
