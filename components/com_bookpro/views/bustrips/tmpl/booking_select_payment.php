<fieldset class="form-horizontal select-payment fieldset">
    <legend class="title select-payment"><?php echo JText::_('Select payment') ?></legend>
    <div class="control-group row-fluid select-time-payment">
        <label class="control-label " for="selectpayment"><?php echo JText::_('Select Payment'); ?>
        </label>
        <div class="controls">
            <div class="row-fluid">
                <div class="payment pay-now pull-left"><label class="radio"><input name="payNow" class="required" type="radio"><?php echo JText::_('Pay now') ?></label></div>
                <div class="payment pay-last pull-left"><label class="radio"><input name="payNow" class="required" type="radio"><?php echo JText::_('Pay last') ?></label></div>
            </div>
        </div>
    </div>
    <div class="control-group row-fluid card-type">
        <label class="control-label" for="cartype"><?php echo JText::_('Car type'); ?>
        </label>
        <div class="controls">
            <div class="row-fluid">
                <div class="card-type-item visa-card pull-left"><label class="radio"><input name="cardType" class="required" type="radio"><?php echo JText::_('Visa card') ?></label></div>
                <div class="card-type-item master-card pull-left"><label class="radio"><input name="cardType" class="required" type="radio"><?php echo JText::_('Master card') ?></label></div>
                <div class="card-type-item amex-card pull-left"><label class="radio"><input name="cardType" class="required" type="radio"><?php echo JText::_('Amex card') ?></label></div>
            </div>
        </div>
    </div>
    <div class="control-group row-fluid">
        <label class="control-label" for="cardnumber"><?php echo JText::_('Card number'); ?>
        </label>
        <div class="controls">
            <input type="text" class="input-medium required" name="cardnumber" >
        </div>
    </div>
    <div class="control-group row-fluid">
        <label class="control-label" for="firstname"><?php echo JText::_('Exp card'); ?>
        </label>
        <div class="controls moth-year">
            <div class="pull-left">
                <select name="expCardMonth" class="pull-left input-small required">
                    <option value="">Month</option>
                    <?php for($i=1;$i<=12;$i++){ ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php
            $now=JFactory::getDate()->year;
            ?>
            <div class="pull-left">
                <select name="expCardYear" class="input-small required">
                    <option value="">Month</option>
                    <?php for($i=$now;$i<=$now+12;$i++){ ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php } ?>
                </select>
            </div>
            <label class="pull-left"><?php echo JText::_('CW') ?><img class="question" src="<?php echo JUri::root() ?>/components/com_bookpro/assets/images/icons/icon-question.png"></label class="checkbox"><input type="text" class="pull-left input-small required">
        </div>
    </div>
    <div class="row-fluid"><label class="checkbox"><input name="confirm" type="checkbox" class="required"><?php echo JText::_('I confirm i have read and understand ') ?><a class="booking-condition" data-toggle="modal" data-target="#booking-condition" href="javascript:void(0)"><?php echo JText::_('The booking condition') ?></a></label></div>
    <div class="row-fluid">
        <input class="btn btn-primary pull-right" type="submit"  value="<?php echo JText::_('Pay now') ?>">
    </div>
</fieldset>