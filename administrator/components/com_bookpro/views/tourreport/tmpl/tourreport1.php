<?php 
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
AImporter::helper('contants','date','currency','report');
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(21);
$itemsCount = count($this->items);

$items = $this->items;
$pagination = &$this->pagination;
?>



<div class="width-20 fltlft">
<?php echo $this->loadTemplate('menu')?>
</div>

<div class="width-80 fltrt">
	<?php echo $this->loadTemplate('search') ?>
	<fieldset>
	 <legend><?php echo JText::_('Tours report')?></legend>
	
	
		<?php 
			$date_from = JRequest::getVar('filter_from',0);
			$date_to = JRequest::getVar('filter_to',0);
			
			if ($date_from == 0){
		
				$date_from = DateHelper::dateBeginMonth(time());
				$date_from = date('Y-m-d H:i:s',$date_from);
			}
			if ($date_to == 0){
				$date_to = DateHelper::dateEndMonth(time());
				$date_to = date('Y-m-d H:i:s',$date_to);
			}
			
			$tours = ReportHelper::getTours($date_from,$date_to,$this->lists['tour_id']);
			
			
			
			
			
			?>
		
			 <script type="text/javascript">
		      google.load("visualization", "1", {packages:["corechart"]});
		      google.setOnLoadCallback(drawChart);
		      function drawChart() {
		        var data = google.visualization.arrayToDataTable([
		          ['Tour', 'Total'],
		          <?php foreach ($tours as $k=>$v){ 
		          	$total = ReportHelper::getOrderTour($date_from, $date_to, $v->id);
		          	?>
		          <?php if ($k < count($tours)-1){ ?>
		          	['<?php echo $v->title; ?>',  <?php echo $total; ?>],
		          <?php }else{ ?>
		          	['<?php echo $v->title; ?>',<?php echo $total; ?>]
		          <?php } ?>
		          <?php } ?>
		          
		          
		        ]);
		
		        var options = {
		          title: '<?php JText::_('Tuor Report Line Chart'); ?>',
		          hAxis: {title: 'Line Chart',  titleTextStyle: {color: 'red'}}
		        };
		
		        var chart = new google.visualization.LineChart(document.getElementById('chart_line'));
		        chart.draw(data, options);
		      }
		    </script>
		    
			<div class="clr"></div>
		    <div id="chart_line" style="width: 100%; height: 500px;"></div>
		    
		    
		    	<script type="text/javascript">
		      google.load("visualization", "1", {packages:["corechart"]});
		      google.setOnLoadCallback(drawChart);
		      function drawChart() {
		        var data = google.visualization.arrayToDataTable([
		          ['Tuor', 'Total'],
		          <?php foreach ($tours as $k=>$v){ 
		          	$total = ReportHelper::getOrderTour($date_from, $date_to, $v->id);
		          	?>
		          <?php if ($k < count($tours)-1){ ?>
		          	['<?php echo $v->title; ?>',  <?php echo $total; ?>],
		          <?php }else{ ?>
		          	['<?php echo $v->title; ?>',<?php echo $total; ?>]
		          <?php } ?>
		          <?php } ?>
		          
		          
		        ]);
		
		        var options = {
		          title: '<?php echo JText::_('Tour Report Pie Chart') ?>',
		          hAxis: {title: 'Line Chart',  titleTextStyle: {color: 'red'}}
		        };
		
		        var chart = new google.visualization.PieChart(document.getElementById('chart_pie'));
		        chart.draw(data, options);
		      }
		    </script>
		    <div id="chart_pie" style="width: 100%; height: 500px;"></div>
		    
		
	 </fieldset>
	 <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?php echo JText::_('Tour') ?>');
        data.addColumn('number', '<?php echo JText::_('No. of Bookings') ?>');
        
        data.addRows([
          <?php 
          $i = 0;
          foreach($tours as $k=>$v){
			$total = ReportHelper::getOrderTour($date_from, $date_to, $v->id);
			 ?>
          <?php 
             if ($i < count($tours)-1){
           ?>
           ['<?php echo $v->title ?>',  {v: <?php echo $total ?>}],
           <?php }else{ ?>
           ['<?php echo $v->title ?>',  {v: <?php echo $total ?>}]
           <?php } ?>
          <?php 
			$i++;
			} ?>
             
         
        ]);
        

        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: false});
      }
    </script>
	 
	 <fieldset>
	 	<legend><?php echo JText::_('Tours Report Table') ?></legend>
	 	<div id="table_div" style="width:100%;height:500px;"></div>
	 </fieldset>
</div>