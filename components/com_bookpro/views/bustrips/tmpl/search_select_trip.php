<div class="row-fluid vehicles">
    <div class="header row-fluid">
        <div class="warped-content">
            <div class="slect-trip pull-left"><?php echo JText::_('Select trip') ?>:</div>
        </div>
    </div>
    <div class="body">
        <div class="row-fluid">
            <div class="main-select-trip">
                <?php
                $app=JFactory::getApplication();
                $roundtrip=$app->getUserStateFromRequest($this->context.'.filter.roundtrip','filter.roundtrip');

                ?>

                <label class="radio"><input <?php echo $roundtrip===0?'checked':0 ?>  class="select-trip group-select-trip" name="roundtrip" value="0" type="radio">One way</label>
                <label class="radio"><input <?php echo $roundtrip==2?'checked':0 ?>  class="select-trip group-select-trip" name="roundtrip" value="2" type="radio">Both</label>
                <label class="radio"><input <?php echo $roundtrip==1?'checked':0 ?> class="select-trip group-select-trip" name="roundtrip" value="1" type="radio">Round trip</label>
            </div>
        </div>

    </div>
</div>
