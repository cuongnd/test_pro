<?php 
defined('_JEXEC') or die('Restricted access');
$config=AFactory::getConfig();
AImporter::helper('tour');
$now = JHtml::date('now');
$date = new JDate($now);
$from_date = JFactory::getDate($date)->format('d-m-Y',true);

$date->add(new DateInterval('P30D'));
$to_date = JFactory::getDate($date)->format('d-m-Y',true);

?>
<table class="table">
			<thead>
				<tr>
					<th width="40%"><?php echo JText::_('COM_BOOKPRO_TOUR_TITLE') ?></th>
					<th ><?php echo JText::_('LENGTH'); ?></th>
					<th width="20%" valign="top"><?php echo JText::_('COM_BOOKPRO_COUNTRY'); ?></th>
					<th class="tours_price"><?php echo JText::_('COM_BOOKPRO_TOUR_PRICE'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($displayData)){ 
					foreach ($displayData as $tour){
						$country = TourHelper::getCountryByTour($tour->id);
						if ($tour->days == 0.5) {
							$days = "1/2 day";
						}else{
							$days = $tour->days.'&nbsp;days';
						}
					?>
					<tr>
						<td><a href="<?php echo JRoute::_('index.php?option=com_bookpro&controller=tour&view=tour&id='.$tour->id)?>"> <?php echo $tour->title; ?> </a></td>
						<td><p><?php echo $days; ?></p><?php echo $tour->length; ?></td>
						<td width="20%" style="color:#006699"><?php echo TourHelper::getHTMLCountryMultiLine($country); ?></td>					
						<td style="color:#003267;" ><?php echo CurrencyHelper::formatprice(TourHelper::getMinPriceTour($tour->id, $from_date, $to_date)); ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
