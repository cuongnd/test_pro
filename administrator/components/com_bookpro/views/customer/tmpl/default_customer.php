<?php
$config=AFactory::getConfig();
?>


<fieldset><legend><?php echo JText::_('COM_BOOKPRO_CUSTOMER_DETAIL'); ?></legend>
	<div class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="customer"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME'); ?>
			</label>
			<div class="controls">
				<?php echo BookProHelper::formatName($this->customer); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->email; ?>
			</div>
		</div>

	<?php if($config->rsTelephone) {?>
		<div class="control-group">
			<label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_TELEPHONE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->telephone; ?>
			</div>
		</div>
	
	<?php  }?>
	<?php if($config->rsMobile) {?>
		<div class="control-group">
			<label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->mobile; ?>
			</div>
		</div>
	
	<?php  }?>
	<?php if($config->rsFax) {?>
		<div class="control-group">
			<label class="control-label" for="fax"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FAX'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->fax; ?>
			</div>
		</div>
	
	<?php  }?>
	<?php if($config->rsAddress) {?>
		<div class="control-group">
			<label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->address; ?>
			</div>
		</div>

	<?php  }?>
	<?php if($config->rsCity) {?>
		<div class="control-group">
			<label class="control-label" for="city"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CITY'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->city; ?>
			</div>
		</div>
	
	<?php  }?>
	<?php if($config->rsState) {?>
		<div class="control-group">
			<label class="control-label" for="states"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->states; ?>
			</div>
		</div>
	
	<?php  }?>
	<?php if($config->rsZip) {?>
		<div class="control-group">
			<label class="control-label" for="zip"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ZIP'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->zip; ?>
			</div>
		</div>
	
	<?php  }?>
	<?php if($config->rsCountry) {?>
		<div class="control-group">
			<label class="control-label" for="country_name"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->country_name; ?>
			</div>
		</div>

	<?php  }?>
		<div class="control-group">
			<label class="control-label" for="created"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CREATED_DATE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->customer->created; ?>
			</div>
		</div>
	</div>
	
</fieldset>
 


        				

