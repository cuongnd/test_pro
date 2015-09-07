		<?php  defined('_JEXEC') or die('Restricted access'); ?>
		
		
		<h2>
			<span><?php echo JText::_('COM_BOOKPRO_CUSTOMER_INFORMATION')?> </span>
		</h2>
		
			<table class="customer_detail">
			
				<tbody>
					<tr>
						<th  style="width: 120px"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME')?></th>
						<td ><?php  echo $this->customer->firstname ?>
						</td>
						
						<th  style="width: 120px"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME')?></th>
						<td >
						<?php echo $this->customer->lastname ?></td>
					</tr>
					<tr>
						<th ><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL')?></th>
						<td > <?php echo $this->customer->email?>
						</td>
						<th ><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE')?></th>
						<td ><?php echo $this->customer->telephone?>
						</td>
					</tr>
					
					<tr>
						<th ><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS')?></th>
						<td ><?php echo JText::sprintf('COM_BOOKPRO_CUSTOMER_FULL_ADDRESS',$this->customer->address,$this->customer->city,$this->customer->states,$this->customer->zip,$this->customer->country_name) ?>
						</td>
						<th></th>
						<td>
						</td>
					</tr>
					
				</tbody>
			</table>
			
		