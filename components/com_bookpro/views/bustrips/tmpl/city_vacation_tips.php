<div class="main-vacation-tip">
    <h3><?php echo JText::_('COM_BOOKPRO_BUSTRIPS_VACATION_TIP') ?></h3>
    <ul class="vacation-tip">
    <?php foreach($this->vacation_tip as $vacation_tip){ ?>
        <li><?php echo $vacation_tip->bus_title ?> <?php echo $vacation_tip->bus_seat ?></li>
    <?php } ?>
    </ul>
</div>