<div class="row-fluid car-rental-offers">
    <div class="header row-fluid content-t3-sl-1">
        <div class="special-car-rental float-left"><?php echo JText::_('special car rental offers') ?></div>
        <div class="change-currency float-right"><?php echo JText::_('Chang currency') ?></div>
    </div>
    <div class="body row-fluid">
        <?php for ($i = 0; $i <count($this->listCarRentalOffers); $i++) { ?>
            <?php
            if($i==4){
                break;
            }?>
            <?php $item=$this->listCarRentalOffers[$i] ?>
            <div class="span3">
                <div class="item">
                    <div class="image"><img src="<?php echo JUri::root() ?>/components/com_bookpro/assets/images/car2-medium.png"></div>
                    <div class="title"><?php echo $item->title ?></div>
                    <div class="link"><a href="#">link</a></div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="footer"><div class="show-more"><?php echo JText::_('Show more vehicle') ?></div></div>
</div>
