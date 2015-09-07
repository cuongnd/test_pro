<?php 
defined('_JEXEC') or die('Restricted access');
$config=AFactory::getConfig();
?>
<table class="table">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_TITLE') ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_LENGTH'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_COUNTRY'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_PRICE'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($displayData)){ 
					foreach ($displayData as $tour){
										
					?>
					<tr>
						<td><?php echo $tour->title; ?></td>
						<td><?php echo $tour->length; ?></td>
						<td><?php echo $tour->country; ?></td>					
						<td><?php echo CurrencyHelper::formatprice($tour->price); ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
