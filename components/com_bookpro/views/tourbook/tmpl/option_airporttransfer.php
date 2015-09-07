<div class="span12 airport_transfer">
    <h3 class="title minusimage slidetoggle"><?php echo $this->airport_transfer; ?></h3>
    <div class="content row-fluid">
        <div class="description row-fluid">
            <div class="span8 div_description">
                <?php echo $this->airport_transfer_description; ?>
            </div>
            <div class="price_booknow" >
                <div><?php echo Jtext::_('COM_BOOKPRO_PRICE') ?>:US$ 30/person</div>
                <div class="booknow booknow_item minusimage"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></div>
            </div>
        </div>
        <div class="row-fluid form-content">
            <div class="form-horizontal">
                <div class="colse btn_close a_btn_close"></div>
                <div><?php echo JText::_('COM_BOOKPRO_PASSENGERS') ?></div>
                <?php echo $this->loadTemplate("airporttransferitem") ?>
            </div>
        </div>
    </div>
</div>



