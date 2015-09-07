<?php 
defined('_JEXEC') or die('Restricted access');
?>
<table class="adminlist" id="passenger">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?>
					</th>
					<th style="width: 120px"><?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME')?>
					</th>
					<th style="width: 120px"><?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME')?>
					</th>
					<th style="width: 150px"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?>
					</th>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_AGE')?>
					</th>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
					</th>
			</tr>
		</thead>
		<?php
		if (count($this->passengers)>0){
			foreach ($this->passengers as $pass)
			{
				?>
		    <tr>
			<td ><?php echo $pass->gender?"Male":"Female"; ?></td>
			<td ><?php echo $pass->firstname; ?></td>
			<td ><?php echo $pass->lastname; ?></td>
			<td ><?php echo $pass->passport; ?></td>
			<td ><?php
			
			switch ($pass->age) {
				case 1:
					echo "Adult";
					break;
				case 0:
					echo "Children";
					break;
				case 2:
					echo "Infant";
					break;
				default:
					;
				break;
			}

			?></td>
			
			<td ><?php echo BookProHelper::getCountryName($pass->country_id); ?></td>
		</tr>
		<?php
			}
		}
		?>
	</table>