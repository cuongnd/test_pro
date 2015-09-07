<?php
defined('_JEXEC') or die('Restricted access');
AImporter::js('master');
$config=AFactory::getConfig();
if(!$config->anonymous)
	$this->customer=AFactory::getCustomer();
?>

	<div class="form-horizontal">   
		<div class="control-group">
			<label class="control-label" for="firstname"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_FIRSTNAME' ); ?>
			</label>
			<div class="controls">
				<input class="inputbox required" type="text" id="firstname"
				name="firstname" id="firstname" size="30" maxlength="50"
				value="<?php echo $this->customer->firstname ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_FIRSTNAME' ); ?>" />
			</div>
		</div>

		<?php if ($config->rsLastname){?>
			<div class="control-group">
				<label class="control-label" for="lastname"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_LASTNAME' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="lastname"
					id="lastname" size="30" maxlength="50"
					value="<?php echo $this->customer->lastname ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_LASTNAME' ); ?>"/>
				</div>
			</div>
		<?php } ?>
		<?php if ($config->rsAddress){?>
			
			<div class="control-group">
				<label class="control-label" for="address"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_ADDRESS' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="address"
					id="address" size="30" maxlength="50"
					value="<?php echo $this->customer->address ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_ADDRESS' ); ?>"/>
				</div>
			</div>
		
		
		<?php } ?>
		<?php if ($config->rsCity) { ?>
			<div class="control-group">
				<label class="control-label" for="city"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_CITY' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="city"
					id="city" size="30" maxlength="50"
					value="<?php echo $this->customer->city ?>"
					placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_CITY' ); ?>"/>
				</div>
			</div>
		

		<?php } ?>
		<?php if ($config->rsState) {?>
			<div class="control-group">
				<label class="control-label" for="states"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_STATES' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="states"
					id="states" size="30" maxlength="50"
					value="<?php echo $this->customer->states ?>"
					placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_STATES' ); ?>"/>
				</div>
			</div>
		
			<?php } ?>
			
			<?php if ($config->rsZip){ ?>
				<div class="control-group">
					<label class="control-label" for="zip"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_ZIP' ); ?>
					</label>
					<div class="controls">
						<input class="inputbox required" type="text" name="zip" id="zip"
						size="30" maxlength="50" value="<?php echo $this->customer->zip ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_ZIP' ); ?>"/>
					</div>
				</div>
		
		
		<?php } ?>
		<?php if ($config->rsCountry){ ?>
				<div class="control-group">
					<label class="control-label" for="country_id"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_COUNTRY' ); ?>
					</label>
					<div class="controls">
						<?php echo BookProHelper::getCountryList('country_id','placeholder="'.JText::_( 'COM_BOOKPRO_CUSTOMER_COUNTRY' ).'"' ,$this->customer->country_id,'')?>
					</div>
				</div>
		
		<?php } ?>
		
		<?php if ($config->rsMobile) {  ?>
			<div class="control-group">
				<label class="control-label" for="mobile"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_MOBILE' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="mobile"
				id="mobile" size="30" maxlength="50"
				value="<?php echo $this->customer->mobile ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_MOBILE' ); ?>" />
				</div>
			</div>
		
		<?php } ?>
		
		<?php if ($config->rsTelephone) { ?>
			<div class="control-group">
				<label class="control-label" for="telephone"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PHONE' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="telephone"
				id="telephone" size="30" maxlength="50"
				value="<?php echo $this->customer->telephone ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PHONE' ); ?>" />
				</div>
			</div>
		
		<?php } ?>
			<div class="control-group">
				<label class="control-label" for="email"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_EMAIL' ); ?>
				</label>
				<div class="controls">
					<input class="inputbox required" type="text" name="email" id="email"
				size="30" maxlength="30"
				value="<?php echo $this->customer->email ?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_EMAIL' ); ?>" />
				</div>
			</div>
			</div>

