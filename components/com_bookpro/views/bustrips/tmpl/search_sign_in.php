<div class="row-fluid sign-in">
    <div class="header row-fluid"><div class="content-header"><?php echo JText::_('Sign in') ?></div></div>
    <div class="body row-fluid">
        <div class="sub-wrapper-content">
            <div class="row-fluid"><input type="text" class="input-large"  placeholder="<?php echo JText::_('Enter email address') ?>"></div>
            <div class="row-fluid send-email"><?php echo JText::_('Your booking detail will be sent to this email address') ?></div>
            <div class="row-fluid">
                <div class="row-fluid">
                    <input type="checkbox" id="checkbox-1-1" class="regular-checkbox" /><label for="checkbox-1-1"></label>
                    <div class="tag">Checkbox Small</div>
                </div>
            </div>
            <div class="row-fluid phone-number">
                <div class="pull-left plus">+</div>
                <div class="pull-left prefix-number">91</div>
                <div class="pull-left suffix-number"><input type="text" class="input-medium"></div>
            </div>

            <div class="row-fluid"><input  type="submit" data-loading-text="Loading..." class="btn btn-primary input-large input-submit-booking" /></div>
        </div>
    </div>
</div>
