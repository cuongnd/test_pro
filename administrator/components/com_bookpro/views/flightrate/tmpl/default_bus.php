<fieldset>
			
	<legend><?php echo JText::_('COM_BOOKPRO_FLIGHT_BUS_PRICE') ?></legend>
	<input type="hidden" name="frate[bus][pricetype]" value="BUS" />
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_BOOKPRO_FLIGHT_ADULT_PRICE') ?></label>
		<div class="controls">
			<input class="input-medium required" type="text" name="frate[bus][adult]" id="adult" maxlength="255" value="" placeholder="<?php echo JText::_('COM_BOOKPRO_ADULT'); ?> " />
			
			<input class="input-medium required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_ADULT_ROUNDTRIP'); ?>" name="frate[bus][adult_roundtrip]" id="adult_roundtrip" size="60" maxlength="255"
			value="" />
			<input class="input-small required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_ADULT_TAXES'); ?>" name="frate[bus][adult_taxes]" id="adult_taxes" size="60" maxlength="255"
			value="" />
			<input class="input-small required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_ADULT_FEES'); ?>" name="frate[bus][adult_fees]" id="adult_fees" size="60" maxlength="255"
			value="" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">
			<?php echo JText::_('COM_BOOKPRO_FLIGHT_CHILD_PRICE'); ?>
		</label>
		<div class="controls">
			<input class="input-medium required" type="text" name="frate[bus][child]" placeholder="<?php echo JText::_('COM_BOOKPRO_CHILD'); ?>" id="child" size="60" maxlength="255" value="" />
			<input class="input-medium required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_CHILD_ROUNDTRIP'); ?>"
			name="frate[bus][child_roundtrip]" id="child_roundtrip" maxlength="255"
			value="" />
			<input class="input-small required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_CHILD_TAXES'); ?>" name="frate[bus][child_taxes]" id="child_taxes" size="60" maxlength="255"
			value="" />
			<input class="input-small required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_CHILD_FEES'); ?>" name="frate[bus][child_fees]" id="child_fees" size="60" maxlength="255"
			value="" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">
			<?php echo JText::_('COM_BOOKPRO_FLIGHT_INFANT_PRICE') ?>
		</label>
		<div class="controls">
			<input class="input-medium required" type="text" name="frate[bus][infant]" placeholder="<?php echo JText::_('COM_BOOKPRO_INFANT') ?>"
			id="infant" maxlength="255" value="" />
			<input class="input-medium required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_INFANT_ROUNDTRIP'); ?>"
			name="frate[bus][infant_roundtrip]" id="infant_roundtrip" size="60" maxlength="255"
			value="" />
			<input class="input-small required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_INFANT_TAXES'); ?>" name="frate[bus][infant_taxes]" id="infant_taxes" size="60" maxlength="255"
			value="" />
			<input class="input-small required" type="text" placeholder="<?php echo JText::_('COM_BOOKPRO_INFANT_FEES'); ?>" name="frate[bus][infant_fees]" id="child_fees" size="60" maxlength="255"
			value="" />
		</div>
	</div>
</fieldset>