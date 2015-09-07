

<?php 
defined('_JEXEC') or die('Restricted access');

$config=AFactory::getConfig();

?>
<table class="table table-striped">
			<thead>
				<tr>
					 <?php if ($config->psGender){?>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?>
					</th>
					<?php } ?>
                    <?php if ($config->psFirstname){?>
					<th ><?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME')?>
					</th>
                    <?php }?>
                    <?php if ($config->psLastname){?>
					<th ><?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME')?>
					</th>
                    <?php }?>
                    <?php if ($config->psBirthday){?>
					<th ><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
					</th>
                    <?php }?>
                    <?php if ($config->psPassport){?>
					<th ><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?>
					</th>
                    <?php }?>
                    <?php if ($config->psPassportValid){?>
					<th ><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT_EXPIRED')?>
					</th>
                    <?php }?>

                    <?php if ($config->psCountry){?>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
					</th>
                    <?php }?>
                    
                     <?php if ($config->psGroup){?>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP')?>
					</th>
                    <?php }?>
                     <th><?php echo JText::_('Seat') ?></th>
                    <?php if ($this->lists['roundtrip']) {
						
					 ?>
                    <th><?php echo JText::_('Return Seat') ?></th>
                    <?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<$this->lists['adult']; $i++):?>
				<tr>
				 <?php if ($config->psGender){?>
					<td><?php echo JHtml::_('select.genericlist',BookProHelper::getGender(), 'pGender[]','class="inputbox input-small"','value','text',1) ?>
					</td>
					<?php } ?>
					 <?php if ($config->psFirstname){?>
					<td><input type="text" name="pFirstname[]" class="inputbox input-small" />
					</td>
                    <?php }?>
					<?php if ($config->psLastname){?>
					<td><input type="text" name="pMiddlename[]" class="inputbox input-small" />
					</td>
                    <?php }?>
					  <?php if ($config->psBirthday){?>
					<td><?php echo JHtml::_('calendar','', 'pBrithday[]','pBrithday'.$i, '%d-%m-%Y' , array('readonly'=>'true','class'=>'date input-small'));?> 
					</td>
                     <?php } ?>
                      <?php if ($config->psPassport){?>
					<td><input  type="text" name="pPassport[]" class="inputbox input-small" size="12" /></td>
                     <?php } ?>
					  <?php if ($config->psPassportValid){?>
					<td><?php echo JHtml::_('calendar','', 'pPassportValid[]', 'pPassportValid'.$i, '%d-%m-%Y' , array('readonly'=>'true','class'=>'date input-small'));?> 
					</td>
                     <?php } ?>
                     
					 <?php if ($config->psCountry){?>
					<td><?php echo BookProHelper::getCountryList('pCountry[]', 0,'')?> 
                    	<input id="age" type="hidden" name="age[]" value="1">
					</td>
					<?php } ?>
					 <?php if ($config->psGroup){?>
					<td><?php echo BookProHelper::getGroupList('pGroup[]', 0,'class="input-small"')?> 
					</td>
					<?php } ?>
					<td>
						<select class="input-small passenger-seat" name="pSeat[]">
							<option value="0"><?php echo JText::_('Select Seat') ?></option>
						</select>
					</td>
					<?php if ($this->lists['roundtrip']) {
						
					 ?>
					<td>
						<select class="passenger-return-seat input-small" name="pReturnSeat[]">
							<option value="0"><?php echo JText::_('Select Return Seat') ?></option>
						</select>
					</td>
					<?php } ?>
				</tr>
				<?php endfor;?>
				
			</tbody>
		</table>