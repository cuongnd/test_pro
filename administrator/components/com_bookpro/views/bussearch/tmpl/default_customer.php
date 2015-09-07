<?php
defined('_JEXEC') or die('Restricted access');
AImporter::js('master');
$config=AFactory::getConfig();
?>

<div id="profile" class="profile">
	<dl>
		<dt>
			 <?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_FIRSTNAME' ); ?>
			
		</dt>
		<dd>
			<input class="inputbox required" type="text" id="firstname"
				name="customer[firstname]" id="firstname" size="30" maxlength="50"
				value="<?php echo $this->customer->firstname ?>" />
		</dd>
		<?php if ($config->rsLastname){?>
		<dt>
			<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_LASTNAME' ); ?>
			
		</dt>
		
		<dd>
			<input class="inputbox required" type="text" name="customer[lastname]"
				id="lastname" size="30" maxlength="50"
				value="<?php echo $this->customer->lastname ?>" />
		</dd>
		<?php } ?>
		<?php if ($config->rsAddress){?>
		<dt>
			 <?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_ADDRESS' ); ?>
			
		</dt>
		<dd>
			<input class="inputbox required" type="text" name=customer[address]"
				id="address" size="30" maxlength="50"
				value="<?php echo $this->customer->address ?>" />
		</dd>
		
		<?php } ?>
		<?php if ($config->rsCity) { ?>
		<dt>
				<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_CITY' ); ?>:
			</dt>
			<dd>
				<input class="inputbox required" type="text" name="customer[city]"
					id="city" size="30" maxlength="50"
					value="<?php echo $this->customer->city ?>"
					style="width: 250px;" />
			</dd>

			<?php } ?>
			<?php if ($config->rsState) {?>
		<dt>
				<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_STATES' ); ?>:
			</dt>
			<dd>
				<input class="inputbox required" type="text" name="customer[states]"
					id="states" size="30" maxlength="50"
					value="<?php echo $this->customer->states ?>"
					style="width: 250px;" />
			</dd>
			<?php } ?>
			<?php if ($config->rsZip){ ?>
		
		<dt>
			<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_ZIP' ); ?>:
		</dt>
		
		<dd>
			<input class="inputbox required" type="text" name="customer[zip]" id="zip"
				size="30" maxlength="50" value="<?php echo $this->customer->zip ?>" />
		</dd>
		<?php } ?>
		<?php if ($config->rsCountry){ ?>
		<dt>
			 <?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_COUNTRY' ); ?>
			
		</dt>
		<dd>
			<?php echo BookProHelper::getCountryList('country_id', $this->customer->country_id,'')?>
		</dd>
		<?php } ?>
		
		<?php if ($config->rsMobile) {  ?>
		<dt>
			<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_MOBILE' ); ?>:
			

		</dt>

		<dd>
			<input class="inputbox required" type="text" name="customer[mobile]"
				id="mobile" size="30" maxlength="50"
				value="<?php echo $this->customer->mobile ?>" />

		</dd>
		<?php } ?>
		
		<?php if ($config->rsTelephone) { ?>
		<dt>
			 <?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PHONE' ); ?>
			

		</dt>

		<dd>
			<input class="inputbox required" type="text" name="customer[telephone]"
				id="telephone" size="30" maxlength="50"
				value="<?php echo $this->customer->telephone ?>" />

		</dd>
		<?php } ?>
		
		<dt>
			 <?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_EMAIL' ); ?>
		</dt>
		<dd>
			<input class="inputbox required" type="text" name="customer[email]" id="email"
				size="30" maxlength="30"
				value="<?php echo $this->customer->email ?>" /> <span
				id="statusEMAIL"></span>
		</dd>
		<dt><?php echo JText::_('Notes') ?></dt>
		<dd>
			<textarea rows="10" cols="20" name="notes"></textarea>
		</dd>

	</dl>
</div>
