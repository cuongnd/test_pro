<?php 
// load tooltip behavior
JHtml::_('behavior.modal');
AImporter::helper('date','currency');
BookProHelper::setSubmenu(21);
$itemsCount = count($this->items);
$items = $this->items;
$pagination = &$this->pagination;
?>

	<div style="width: 20%; float: left;">
		<?php echo $this->loadTemplate('menu')?>
	</div>
	<div style="width: 80%; float: right;">
		<?php echo $this->loadTemplate('search')?>
	
	<div class="width-100 fltrt">
		<fieldset class="adminform">
			<legend>
				<?php echo JText::_('New Order') ?>
			</legend>
			<h2 class="titlePage">
				<?php echo JText::_('Order List'); ?>
			</h2>
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
						<th><?php echo JText::_("Order Number"); ?></th>
						<th><?php echo JText::_("Order Status"); ?></th>
						<th><?php echo JText::_("Type"); ?></th>
						<th><?php echo JText::_("Sub Total"); ?></th>
						<th><?php echo JText::_("Total"); ?></th>
						<th><?php echo JText::_("Pay method"); ?></th>
						<th><?php echo JText::_("Payment status"); ?></th>
						<th width="20%"><?php echo JHTML::_('grid.sort', 'Date Created', 'created', $orderDir, $order); ?>
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
						<td colspan="13" class="emptyListInfo"><?php echo JText::_('No reservations.'); ?>
						</td>
					</tr>
					<?php } ?>
					<?php for ($i = 0; $i < $itemsCount; $i++) { ?>
					<?php $subject = &$this->items[$i]; ?>
					<?php
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?>
						</td>
						<?php if (! $this->selectable) { ?>
						<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
						</td>
						<?php } ?>
						<td><a
							href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->user_id)); ?>"><?php echo $subject->ufirstname; ?>
						</a></td>
						<td><a
							href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $subject->order_number; ?>
						</a></td>
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
	<div style="clear: both;"></div>
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
		$total = OrderHelper::getTotal($date_start,$date_end,'TRANSPORT');
		if ($total == NULL){
			$total = 0;
		}
		$days[$i+1] = $total;
	}




	?>

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
		$total = OrderHelper::getTotal($start_date,$end_date,OrderType::$TRANSPORT->getValue());		
		if ($total == NULL){
			$total = 0;
		}
		$data[$i] = $total;
	}


	?>
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
</div>
<div class="clr"></div>