<div style="margin: 0 10px;">
<?php 

$flights=$displayData->flights;

for($i=0; $i< (count($flights)); $i++):
	
?>					
					<div style="width:100%;margin:0;text-align:left"><strong><?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_DEPART_ON',JHtml::_('date',$flights[$i]->depart_date,'l, F j Y'))?></strong></div>
					
						<table style="margin:0 !important;width:100%;border-bottom: 1px solid #ccc;">
							<tr>
								<td width="20%"><strong><?php echo $flights[$i]->from_title ?></strong><br/><?php echo $flights[$i]->from_code ?>&nbsp;<?php echo $flights[$i]->start ?>
								</td>
								<td width="5%"> &#10137;</td>
								<td width="20%" style="text-align:left;"><strong><?php echo $flights[$i]->to_title ?></strong><br/> <?php echo $flights[$i]->to_code ?>&nbsp;<?php echo $flights[$i]->end ?>
								</td>
								<td width="55%"><strong><?php echo $flights[$i]->airline_name; ?><br/>
												<?php echo $flights[$i]->flightnumber ?></strong>
								</td>
							</tr>
						</table>
						<br>
						
				<?php endfor;?>
				<div style="font-weight: bold;"><?php echo JText::_('COM_BOOKPRO_FLIGHT_PACKAGE_TXT',FlightHelper::formatPackage($flights[$i]->pricetype))?></div>
			
</div>