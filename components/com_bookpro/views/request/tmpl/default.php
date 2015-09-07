<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.calendar');
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
AImporter::js('jquery.datepick', 'jquery.ui.datepicker');
JHtmlBehavior::formvalidation();

JPluginHelper::importPlugin('captcha');
$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger('onInit','dynamic_recaptcha_1');

?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {

	

	
    var form = document.adminForm;

    form.task.value = task;

    form.submit();

    return;
}
</script>
<h3>YOUR WORLD-WE TELL IT LIKE IT IS</h3>
<p>If you have recently returned from an Exodus adventure and want to help other travellers with your feedback then simply complete the form below to post your holiday review now
</p>
<form name="adminForm" action="" method="post">
    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_REQUEST_FIRST_NAME'); ?></label>

            <div class="controls">
                <input type="text" class="text-are" name="firstname" id="firstname" value="<?php echo $this->obj->firstname;?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_REQUEST_LAST_NAME'); ?></label>

            <div class="controls">
                <input type="text" class="text-are" name="lastname" id="lastname" value="<?php echo $this->obj->lastname;?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_REQUEST_EMAIL'); ?></label>

            <div class="controls">
                <input type="text" class="text-are" name="email" id="email" value="<?php echo $this->obj->email;?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_REVIEW_YOUR_TRIP'); ?></label>

            <div class="controls">
                <?php echo $this->tour; ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_COUNTRY'); ?></label>

            <div class="controls">
                <?php echo $this->country; ?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_REVIEW_DATE_OF_TRAVEL'); ?></label>

            <div class="controls">
                <?php echo JHtml::calendar($this->obj->date, 'date', 'date', '%Y-%m-%d'); ?>
            </div>
        </div>

       <div class="control-group">
            <label class="control-label">
            </label>
            <div class="controls">
                <div id="dynamic_recaptcha_1"></div>
            </div>
        </div>
    
        <div class="control-group">
            <label class="control-label">
            </label>
            <div class="controls">
                <a href="javascript:void(0);" onclick="document.adminForm.submit();">
			    	<input type="button" name="button" value="Submit" class="btn btn-success">
			    </a>
            </div>
        </div>
    </div>
                
    <input type="hidden" name="option" value="com_bookpro" />
    <input type="hidden" name="controller" value="request" />
    <input type="hidden" name="task" value="request" />
    
</form>
