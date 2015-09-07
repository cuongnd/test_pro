<div>
    <h3 style="text-transform: uppercase; color: #9A0000; text-align: right">
        <?php echo JText::_('COM_BOOKPRO_TOTAL_TRIP_PRICE') ?>&nbsp;
        <span class="total_discount">
            <?php echo $this->cart->total_discount!=0?CurrencyHelper::formatprice($this->cart->total-$this->cart->total_discount):'' ?>
        </span>
        <span class="total_price_person <?php echo $this->cart->total_discount != 0 ? ' discount ' : '' ?>">
            <?php echo CurrencyHelper::formatprice($this->cart->total); ?>
        </span>
    </h3>

</div>