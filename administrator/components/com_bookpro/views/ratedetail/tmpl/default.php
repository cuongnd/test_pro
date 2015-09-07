<?php 
AImporter::helper('currency');
?>
<?php foreach ($this->rates as $rate){ ?>
	<div class="row-fluid">
		<div class="span12">
			<fieldset>
				<legend><?php echo JText::_('COM_BOOKPRO_ROOMRATE_PRICE_TYPE_'.$rate->pricetype) ?></legend>
				<table class="table table-stripped">
					<thead>
						<tr>
							<th><?php echo JText::_('COM_BOOKPRO_ADULT'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_ADULT_ROUNDTRIP'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_ADULT_TAXES'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_ADULT_FEES'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_CHILD'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_CHILD_ROUNDTRIP'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_CHILD_TAXES'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_CHILD_FEES'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_INFANT') ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_INFANT_ROUNDTRIP'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_INFANT_TAXES'); ?></th>
							<th><?php echo JText::_('COM_BOOKPRO_INFANT_FEES'); ?></th>
						</tr>
						
					</thead>
					<tbody>
						<tr>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->adult); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->adult_roundtrip); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->adult_taxes); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->adult_fees); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->child); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->child_roundtrip); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->child_taxes); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->child_fees); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->infant); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->infant_roundtrip); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->infant_taxes); ?></td>
							<td align="center" class="text-center"><?php echo CurrencyHelper::displayPrice($rate->infant_fees); ?></td>
						</tr>
					</tbody>			
				</table>
				
			</fieldset>
		</div>
	</div>
<?php } ?>