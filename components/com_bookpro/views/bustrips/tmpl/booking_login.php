<!-- Modal -->
<div class="modal fade" id="booking-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php JText::_('Login') ?></h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="control-group row-fluid">
                    <label class="control-label" for="nationality"><?php echo JText::_('User name'); ?>
                    </label>
                    <div class="controls">
                        <input class="input-medium" name="username">
                    </div>
                </div>

                <div class="control-group row-fluid">
                    <label class="control-label" for="nationality"><?php echo JText::_('Password'); ?>
                    </label>
                    <div class="controls">
                        <input class="input-medium" name="password">
                    </div>
                </div>
                <div class="row-fluid">
                    <input type="button" class="btn btn-primary" value="<?php echo JText::_('Login')  ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>