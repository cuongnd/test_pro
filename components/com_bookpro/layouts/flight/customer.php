<?php
$config=AFactory::getConfig();
?>
<h2 class="customer-title"> <?php echo JText::_('COM_BOOKPRO_LIFHGT_CONTACT_INFOMATION'); ?></h2>
	<div class="customer-box">
		<div class="customer-center">
			
			<div class="row-fluid">
				<div class="span4">
					<label><?php echo JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_PHONE') ?></label>
					<div class="row-fluid">
						<div class="span6">
							<?php 
								echo FlightHelper::phoneType($displayData->phone_type, 'customer[phone_type]','required="true" class="select span12"');
							?>
						</div>
						<div class="span6">
							<?php echo FlightHelper::getCountryCode($displayData->phone_code, 'customer[phone_code]','required="true" class="select span12"'); ?>
						</div>
						
					</div>
				</div>
				<div class="span4">
					<label>&nbsp;</label>
					
					<input type="text" required name="customer['phone_number']" placeholder="<?php echo JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_PHONE_NUMBER') ?>" value="<?php $displayData->phone_code; ?>" class="inputbox span12" />
				</div>
				<div class="span4">
					<label><?php echo JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_FIRSTNAME') ?><sup>*</sup></label>
					<input type="text" required name="customer['firstname']"  value="<?php $displayData->firstname; ?>" class="inputbox span12" />
				</div>
			</div>
			<div class="row-fluid">
			<div class="span4">
				<label><?php echo JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_LASTNAME') ?><sup>*</sup></label>
					<input type="text" required name="customer['lastname']"  value="<?php $displayData->lastname; ?>" class="inputbox span12" />
			</div>
			<div class="span4">
				<label><?php echo JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_EMAIL'); ?><sup>*</sup></label>
				<input type="text" required name="customer['email']"  value="<?php $displayData->email; ?>" class="inputbox span12" />
			</div>
			<div class="span4">
				<label><?php echo JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_CONFIRM_EMAIL'); ?><sup>*</sup></label>
				<input type="text" required name="customer['verify-email']"  value="" class="inputbox span12" />
			</div>
		</div>
		</div>
		
	</div>
	
	
 


        				

