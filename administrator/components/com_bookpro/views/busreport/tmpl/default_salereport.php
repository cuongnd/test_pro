<?php 
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
AImporter::helper('contants','date','currency');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$items = $this->items;
$pagination = &$this->pagination;
?>
<div id="editcell">

<div class="width-20 fltlft">
	<fieldset class="adminform">
			<legend><?php echo JText::_('COM_BOOKPRO_SUMMARY') ?></legend>
			<table class="admintable">
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('COM_BOOKPRO_BUSREPORT_TODAY') ?></td>
					<td>
					<?php 
						$start_day = date('Y-m-d H:i:s',DateHelper::dateBeginDay(time()));
						$end_day = date('Y-m-d H:i:s',DateHelper::dateEndDay(time()));
						$total_today = BookProHelper::getSummary($start_day,$end_day); 
						echo CurrencyHelper::formatprice($total_today);
					?>
					</td>
				</tr>
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('COM_BOOKPRO_BUSREPORT_YESTERDAY') ?></td>
					<td>
						<?php 
						$start_yesterday = date('Y-m-d H:i:s',DateHelper::dateBeginDay(strtotime('yesterday')));
						$end_yesterday = date('Y-m-d H:i:s',DateHelper::dateEndDay(strtotime('yesterday')));
						$total_yesterday = BookProHelper::getSummary($start_yesterday,$end_yesterday);
						echo CurrencyHelper::formatprice($total_yesterday);
						?>
					</td>
				</tr>
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('COM_BOOKPRO_BUSREPORT_THIS_WEEK') ?></td>
					<td>
					<?php 
						$start_week = date('Y-m-d H:i:s',DateHelper::dateBeginWeek(time()));
						
						$end_week = date('Y-m-d H:i:s',DateHelper::dateEndWeek(time()));
						
						$total_week = BookProHelper::getSummary($start_week,$end_week);
						echo CurrencyHelper::formatprice($total_week);
					
					?>
						
					</td>
				</tr>
				<tr>
					<td class="key" style="width:100px;"><?php echo JText::_('COM_BOOKPRO_BUSREPORT_THIS_MONTH'); ?></td>
					<td><?php 
						$start_month = date('Y-m-d H:i:s',DateHelper::dateBeginMonth(time()));
							
						
						$end_month = date('Y-m-d H:i:s',DateHelper::dateEndMonth(time()));
						$total_month = BookProHelper::getSummary($start_month,$end_month);
						echo CurrencyHelper::formatprice($total_month);
					
					?>
					
					</td>
				</tr>
				
			</table>
	</fieldset>		
</div>
<div class="width-80 fltrt">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_BOOKPRO_BUSREPORT_NEW_ORDER') ?></legend>
<h2 class="titlePage"><?php echo JText::_('Order List'); ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th width="3%">#</th>
			<?php if (! $this->selectable) { ?>
			<th width="2%"><input type="checkbox" class="inputCheckbox"
				name="toggle" value=""
				onclick="checkAll(<?php echo $itemsCount; ?>);" /></th>
				<?php } ?>
			<th><?php echo JHTML::_('grid.sort', 'Customer', 'ufirstname', $orderDir, $order); ?>
			</th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_ORDER_NUMBER"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_ORDER_STATUS"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_TYPE"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_SUB_TOTAL"); ?></th>
			
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_TOTAL"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_PAY_METHOD"); ?></th>
			<th><?php echo JText::_("Payment status"); ?></th>
			
			<th width="20%"><?php echo JHTML::_('grid.sort',JText::_("COM_BOOKPRO_BUSREPORT_DATE_CREATED"), 'created', $orderDir, $order); ?>

			</th>
		

		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="13"><?php echo $pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php if ($itemsCount == 0) { ?>
		<tr>
			<td colspan="13" class="emptyListInfo"><?php echo JText::_('No reservations.'); ?></td>
		</tr>
		<?php } ?>
		<?php for ($i = 0; $i < $itemsCount; $i++) { ?>
		<?php $subject = &$this->items[$i]; ?>
		<?php

		?>

		<tr class="row<?php echo $i % 2; ?>">
			<td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
			<?php if (! $this->selectable) { ?>
			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
			<?php } ?>
			<td><a href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->user_id)); ?>"><?php echo $subject->ufirstname; ?></a></td>
			<td><a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $subject->order_number; ?></a></td>
			<td><?php echo $subject->order_status?></td>
			<td><?php echo $subject->type ?></td>
			<td><?php echo number_format($subject->subtotal,2) ?></td>
			
			
			<td><?php echo number_format($subject->total,2)?></td>
			<td><?php echo $subject->pay_method; ?></td>
			<td><?php echo $subject->pay_status;	?></td>

			
			<td><?php echo $subject->created; ?></td>
			
		</tr>
		<?php } ?>
	</tbody>
</table>
</fieldset>
</div>

</div>
<div style="clear:both;"></div>
<div>
<?php 
	$m = date('m',time());
	$y = date('Y',time());
	$num = cal_days_in_month(CAL_GREGORIAN, $m, $y);
	$start_date = DateHelper::dateBeginMonth(time());
	$days = array();
	for ($i = 0;$i < $num;$i++){
		$date = $start_date + $i*24*60*60;
		$date_start = date('Y-m-d H:i:s',DateHelper::dateBeginDay($date));
		$date_end = date('Y-m-d H:i:s',DateHelper::dateEndDay($date));
		$total = BookProHelper::getSummary($date_start,$date_end);
		if ($total == NULL){
			$total = 0;
		}
		$days[$i+1] = $total;
	}
	
		
	
	
	?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Sales'],
          <?php foreach ($days as $k=>$v){ ?>
          <?php if ($k < count($days)){ ?>
          	['<?php echo $k; ?>',  <?php echo $v; ?>],
          <?php }else{ ?>
          	['<?php echo $k; ?>',<?php echo $v; ?>]
          <?php } ?>
          <?php } ?>
          
          
        ]);

        var options = {
          title: 'Month Performance',
          hAxis: {title: 'Month',  titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_month'));
        chart.draw(data, options);
      }
    </script>
	
	<div id="chart_month" style="width: 100%; height: 500px;"></div>
	
</div>
<div>
	<?php
	$y = date('Y',time());
	$m = date('m',time());
	$num = cal_days_in_month(CAL_GREGORIAN, $m, $y); // 31
	$data = array();
	for ($i = 1;$i <=12;$i++){
		$start_date = DateHelper::startMonth($i,$y);
		$end_date = DateHelper::endMonth($num,$i,$y);
		$total = BookProHelper::getSummary($start_date,$end_date);
		if ($total == NULL){
			$total = 0;
		}
		$data[$i] = $total;
	} 
	
	
	?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales'],
          <?php foreach ($data as $k=>$v){ ?>
          <?php 
          if ($k < count($data)){
          ?>
          ['<?php echo $k; ?>',  <?php echo $v ?> ],
          <?php }else{ ?>
          ['<?php echo $k; ?>',  <?php echo $v; ?> ]
          <?php } ?>
          <?php } ?>
          
          
        ]);

        var options = {
          title: 'Year Performance'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	 <div id="chart_div" style="width: 100%; height: 500px;"></div>
	 
</div>
