<fieldset>

<legend><?php echo JText::_('Configuration status')?></legend>

<?php 

AImporter::helper('sms');
$sms=new SMSHelper();
$sms->sendSMS();

?>
</fieldset>