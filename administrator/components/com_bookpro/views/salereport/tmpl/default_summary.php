<?php
AImporter::helper('date','currency','report','order');
?>
<fieldset class="adminlist">
			<legend><?php echo JText::_('Summary') ?></legend>
			<table class="adminlist">
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('Today') ?></td>
					<td>
					<?php 
						$start_day = date('Y-m-d H:i:s',DateHelper::dateBeginDay(time()));
						$end_day = date('Y-m-d H:i:s',DateHelper::dateEndDay(time()));
						$total_today = OrderHelper::getTotal($start_day,$end_day,'TOUR'); 
						echo CurrencyHelper::formatprice($total_today);
					?>
					</td>
				</tr>
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('Yesterday') ?></td>
					<td>
						<?php 
						$start_yesterday = date('Y-m-d H:i:s',DateHelper::dateBeginDay(strtotime('yesterday')));
						$end_yesterday = date('Y-m-d H:i:s',DateHelper::dateEndDay(strtotime('yesterday')));
						$total_yesterday = OrderHelper::getTotal($start_yesterday,$end_yesterday,'TOUR'); 
						echo CurrencyHelper::formatprice($total_yesterday);
						?>
					</td>
				</tr>
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('This Week') ?></td>
					<td>
					<?php 
						$start_week = date('Y-m-d H:i:s',DateHelper::dateBeginWeek(time()));
						$end_week = date('Y-m-d H:i:s',DateHelper::dateEndWeek(time()));						$total_week = OrderHelper::getTotal($start_week,$end_week,'TOUR');						echo CurrencyHelper::formatprice($total_week);
					
					?>
						
					</td>
				</tr>
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('This Month'); ?></td>
					<td><?php 
						$start_month = date('Y-m-d H:i:s',DateHelper::dateBeginMonth(time()));
						$end_month = date('Y-m-d H:i:s',DateHelper::dateEndMonth(time()));
						$total_month = OrderHelper::getTotal($start_month,$end_month,'TOUR');
						echo CurrencyHelper::formatprice($total_month);
					?>
					
					</td>
				</tr>
				
			</table>
	</fieldset>	