<?php
$pivot_packagetypes = JArrayHelper::pivot($this->packagetypes, 'id');
?>
<div style="border-bottom: 1px dotted #738498">
    <div class="checkinandcheckout_title"><?php echo JText::_('COM_BOOKPRO_BOOK_AND_GO') ?></div>
    <h3 class="tour_title"><?php echo $this->tour->title ?></h3>
    <div class="right_level">
        <div>
            <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_START_DATE_AND_CITY') ?></div>
            <div class="right_bold">
                <?php echo DateHelper::formatDate($this->cart->checkin_date, 'd M Y'); ?>, <?php echo reset($this->list_destination_of_tour)->title ?>
            </div>
        </div>
        <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_FINISH_DATE_AND_CITY') ?></div>
        <div class="right_bold">
            <?php echo DateHelper::formatDate($this->cart->checkout_date, 'd M Y'); ?>, <?php echo end($this->list_destination_of_tour)->title ?>
        </div>
        <?php if ($this->cart->packagetype_id) { ?>
            <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_CATRGORY') ?></div>
            <div class="right_bold">
                <?php echo $pivot_packagetypes[$this->cart->packagetype_id]->title ?>
            </div>
        <?php } ?>
    </div>
</div>