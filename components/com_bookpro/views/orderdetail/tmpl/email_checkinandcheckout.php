
<div style="border-bottom: 1px dotted #738498">
    <div class="checkinandcheckout_title"><?php echo JText::_('COM_BOOKPRO_BOOK_AND_GO') ?></div>
    <h3 class="tour_title"><?php echo $this->tour->title ?></h3>
    <div class="right_level">
        <div>
            <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_START_DATE_AND_CITY') ?></div>
                <div class="right_bold">
                    <?php echo DateHelper::formatDate($this->info->start,'d M Y'); ?> 
                </div>
        </div>
        <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_FINISH_DATE_AND_CITY') ?></div>
            <div class="right_bold">
                <?php echo DateHelper::formatDate($this->info->end,'d M Y'); ?> 
             </div>
    </div>
</div>