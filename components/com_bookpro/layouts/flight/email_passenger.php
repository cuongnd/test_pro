<?php 
defined('_JEXEC') or die('Restricted access');
$config=AFactory::getConfig();
?>
<div style="margin: 0 10px;">
<table style="width:100%;border-bottom: 1px solid #ccc;">
	<thead>
		<tr style="border-bottom: 1px solid #ccc;">
			<?php if ($config->psGender){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?></b>
			</th>
			<?php } ?>
			<?php if ($config->psFirstname){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME')?></b>
			</th>
			<?php }?>
			<?php if ($config->psLastname){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME')?></b>
			</th>
			<?php }?>
			<?php if ($config->psBirthday){?>
			<th align="left">
			<b>
			<?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?></b>
			</th>
			<?php }?>
			
			<?php if ($config->psDocumenttype){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_DOCUMENT_TYPE')?></b>
			</th>
			<?php }?>
		
			<?php if ($config->psPassport){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?></b>
			</th>
			<?php }?>
			<?php if ($config->psPassportValid){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT_EXPIRED')?></b>
			</th>
			<?php }?>

			<?php if ($config->psCountry){?>
			<th align="left">
			<b>
			<?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?></b>
			</th>
			<?php }?>

			<?php if ($config->psGroup){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP')?></b>
			</th>
			<?php }?>
			
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_PRICE')?></b>
			</th>
			<?php if ($config->psBag){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_BAGGAGE')?></b>
			</th>
			<?php } ?>
			<?php if ($config->psBag){?>
			<th align="left"><b><?php echo JText::_('COM_BOOKPRO_PASSENGER_BAGGAGE_PRICE')?></b>
			</th>
			<?php } ?>
			
		</tr>
	</thead>
	<?php
	if (count($displayData->passengers)>0){
			foreach ($displayData->passengers as $pass)
			{
				?>
	<tr>
		<?php if ($config->psGender){?>
		<td align="left"><?php echo $pass->gender?"Male":"Female"; ?></td>
		<?php } ?>
		<?php if ($config->psFirstname){?>
		<td align="left"><?php echo $pass->firstname; ?></td>
		<?php }?>
		<?php if ($config->psLastname){?>
		<td align="left"><?php echo $pass->lastname; ?></td>
		<?php }?>
		
		<?php if ($config->psBirthday){?>
		<td align="left"><?php echo JHtml::_('date',$pass->birthday,'d-m-Y'); ?></td>
		<?php }?>
		
		<?php if ($config->psDocumenttype){?>
		
		<td align="left"><?php echo BookProHelper::getNameDocumentType($pass->documenttype) ; ?></td>
		<?php }?>

		<?php if ($config->psPassport){?>
		<td align="left"><?php echo $pass->passport; ?></td>
		<?php }?>
		<?php if ($config->psPassportValid){?>
		<td align="left"><?php echo  $pass->ppvalid	; ?></td>
		<?php }?>
		<?php if ($config->psCountry){?>
		<td align="left"><?php echo  $pass->country; ?></td>
		<?php }?>
		<?php if ($config->psGroup){?>
		<td align="left"><?php echo BookProHelper::formatAge($pass->group_id) ;?></td>
		<?php }?>
		
		<td align="left">
		<?php if(!$displayData->return){
				echo CurrencyHelper::formatPrice($pass->price);
			}else{
				echo CurrencyHelper::formatPrice($pass->return_price);
				
			}	
				?>
		</td>
		<?php if($config->psBag){ 
			?>
			<td align="left">
			<?php 
			if(!$displayData->return){
				echo $pass->bag_qty;
			}else{
				echo $pass->return_bag_qty;
			
			}
			?>
		</td>
		<?php } ?>
		<?php if($config->psBag){ 
			?>
			<td align="left">
			<?php 
			if(!$displayData->return){
				echo CurrencyHelper::displayPrice($pass->price_bag);
			
		}else{
				
				echo CurrencyHelper::displayPrice($pass->return_price_bag);
			
			}
			?>
		</td>
		<?php } ?>
	</tr>
	<?php
			}
		}
		?>
</table>
<div>