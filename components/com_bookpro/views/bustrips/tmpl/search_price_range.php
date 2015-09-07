<div class="row-fluid price-range">
    <div class="header"><?php echo  JText::_('price ranges') ?> :</div>
    <div class="body">
        <div class="slider-range">
            <?php
            $app=JFactory::getApplication();
            $minRate=$app->getUserState('bustrip_filter_minRate',0);
            $maxRate=$app->getUserState('bustrip_filter_maxRate',5000);
            $minRate=CurrencyHelper::displayPrice($minRate,0);
            $maxRate=CurrencyHelper::displayPrice($maxRate,0);
            ?>
            <div id="price-slider-range-amount"><?php echo $minRate ?> - <?php echo $maxRate ?></div>
            <div id="price-slider-range"></div>
        </div>
    </div>
</div>
