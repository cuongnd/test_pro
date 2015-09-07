<fieldset>

<legend><?php echo JText::_('Configuration status')?></legend>

<?php 
//check global configuration
$config=AFactory::getConfig();
if(!$config->currencySymbol || !$config->mainCurrency || !$config->images ){
	echo '<p style="color:red;">'.JText::_('Global configuration is not complete').'</p>';
}

foreach ($this->items as $item){
if($item->state==1) {	

	if(!$item->email_send_from || !$item->email_admin || !$item->email_customer_subject || !$item->email_admin_subject ){

		echo '<p style="color:red;">'.$item->code.': '.JText::_('Email configuration is not complete, go to application manager to check it').'</p>';
	
	}else {
		
		echo '<p style="color:blue;">'.$item->code.': '.JText::_('Configuration is OK').'</p>';
	}
 }
}

?>
</fieldset>