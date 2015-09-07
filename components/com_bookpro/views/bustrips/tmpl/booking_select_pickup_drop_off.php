<div class="row-fluid select-pickup-drop-off">
    <div class="row-fluid header">
        <div class="span6"><h3><?php echo JText::_('Pickup info') ?></h3></div>
        <div class="span6"><h3><?php echo JText::_('Drop off info') ?></h3></div>
    </div>
    <div class="body row-fluid">
        <div class="row-fluid">
            <div class="sub-wrapper-content">
                <div class="span6 input-pickup-info">
                    <div class="sub-wrapper-content">
                        <div class="space-and">
                            <div class="radius-and"><?php echo JText::_('And') ?></div>
                            <div class="row-fluid"><div class="icon-place"><?php echo JText::_('Pick up place') ?></div></div>
                            <div class="row-fluid"><textarea name="pickUpPlace" class="input-place required"></textarea></div>
                            <div class="row-fluid">
                                <div class="span12 control-group">
                                        <label class="control-label" for="gender"><?php echo JText::_('Pick up time'); ?>
                                    </label>
                                    <div class="controls select-pickup-date-time">
                                        <div  style="display: none" class="input-append pull-left date-time"><input id="pickup_date" class=" date input-small  calendar-select-date-time" name="pickup_date" type="text"></div>
                                        <div class="pull-left">
                                            <select name="pickup_hours" class="input-small  date-time hours required">
                                                <option value=""><?php echo JText::_('Hours') ?></option>
                                                <?php for($i=1;$i<=24;$i++){ ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="pull-left">
                                            <select name="pickup_minutes" class="input-small pull-left date-time minutes required">
                                                <option value=""><?php echo JText::_('Minutes') ?></option>
                                                <?php for($i=1;$i<=60;$i++){ ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span6 input-drop-off-info">
                    <div class="sub-wrapper-content">
                        <div class="row-fluid"><div class="icon-place"><?php echo JText::_('Drop off place') ?></div></div>
                        <div class="row-fluid"><textarea name="DropOffPlace" class="input-place required"></textarea></div>
                        <div class="row-fluid">
                            <div class="span12 control-group">
                                <label class="control-label" for="gender"><?php echo JText::_('Drop off time'); ?>
                                </label>
                                <div class="controls select-pickup-date-time">
                                    <div style="display: none" class="input-append pull-left date-time"><input id="dropoff_date" class=" date input-small  calendar-select-date-time" name="dropoff_date" type="text"></div>
                                    <div class="pull-left">
                                        <select name="drop_off_hours" class="input-small pull-left date-time hours required">
                                            <option value=""><?php echo JText::_('Hours') ?></option>
                                            <?php for($i=1;$i<=24;$i++){ ?>
                                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="pull-left">
                                        <select name="drop_off_minutes" class="input-small pull-left date-time minutes required">
                                            <option value=""><?php echo JText::_('Minutes') ?></option>
                                            <?php for($i=1;$i<=60;$i++){ ?>
                                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            dfdfd
        </div>

    </div>
</div>