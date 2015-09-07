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
	 <legend><?php echo JText::_('Tour report')?></legend>
	
	
		<?php 
			$m = date('m',time());
			
			$y = date('Y',time());
			$num = cal_days_in_month(CAL_GREGORIAN, $m, $y);
			
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
			
			
			$start_month = DateHelper::dateBeginMonth(time());
			$start_month = date('Y-m-d H:i:s',$start_month);
			$end_month = DateHelper::dateEndMonth(time());
			$end_month = date('Y-m-d H:i:s',$end_month);
			$start_date = DateHelper::dateBeginMonth(time());
			
			$ctours = ReportHelper::getTours($start_month,$end_month,$this->lists['tour_id']);
			
			$arrChart = array();
			
			$arrChart[] = JText::_('Month');
			
			foreach ($ctours as $ctour){
				$arrChart[] = $ctour->title;
			}
			
			$chart = array();
			$chart[] = $arrChart;
			for ($i = 1;$i <= $num;$i++){
				
				$arChart = array();
				
				$date = $start_date + $i*24*60*60;
			
				$date_start = date('Y-m-d H:i:s',DateHelper::dateBeginDay($date));
			
				$date_end = date('Y-m-d H:i:s',DateHelper::dateEndDay($date));
			
				//$dtours = ReportHelper::getTours($date_start,$date_end,$this->lists['tour_id']);
				
				$arChart[] = $i;
				
				foreach ($ctours as $ctour){
						$total = ReportHelper::getOrderTour($date_start, $date_end, $ctour->id);
						if ($total == NULL){
							$total = 0;
						}
						$arChart[] = $total;
				}
				
				$chart[] = $arChart;
			
				
			
			}
			
			
			?>
		
			 <script type="text/javascript">
		      google.load("visualization", "1", {packages:["corechart"]});
		      google.setOnLoadCallback(drawChart);
		      function drawChart() {
		        var data = google.visualization.arrayToDataTable([
		       
		          <?php 
		          foreach ($chart as $ckey=>$cvalue){
		          ?>
		          [
					<?php 
						if ($ckey == 0){
					?>	
						<?php foreach ($cvalue as $ck=>$cv){ ?>
							'<?php echo $cv ?>'
							<?php if ($ck < count($cvalue) -1){ ?>,
							<?php } ?>
						<?php } ?>
					<?php }else{ ?>
						<?php foreach ($cvalue as $ck=>$cv){ ?>
							<?php echo $cv ?>
							<?php if ($ck < count($cvalue) -1){ ?>,
							<?php } ?>
						<?php } ?>
					<?php } ?>
			      ]<?php if ($ckey < count($chart) - 1){ ?>,<?php } ?>
		          <?php } ?>
		          
		        ]);
		
		        var options = {
		          title: '<?php JText::_('Line Chart'); ?>',
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
		          title: '<?php echo JText::_('Pie Chart') ?>',
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
        data.addColumn('number', '<?php echo JText::_('No. of Bookings,') ?>');
        data.addColumn('number', '<?php echo JText::_('Sales') ?>');
        
        data.addRows([
          <?php 
          $i = 0;
          foreach($tours as $k=>$v){
			$total = ReportHelper::getOrderTour($date_from, $date_to, $v->id);
			$count = ReportHelper::getOrderCountTour($date_from, $date_to, $v->id);
			 ?>
          <?php 
             if ($i < count($tours)-1){
           ?>
           ['<?php echo $v->title ?>', {v:<?php echo $count; ?>}, {v: <?php echo $total ?>,f:'<?php echo CurrencyHelper::formatprice($total); ?>'}],
           <?php }else{ ?>
           ['<?php echo $v->title ?>',{v:<?php echo $count; ?>},  {v: <?php echo $total ?>,f:'<?php echo CurrencyHelper::formatprice($total); ?>'}]
           <?php } ?>
          <?php 
			$i++;
			} ?>
             
         
        ]);
        

        var table = new google.visualization.Table(document.getElementById('table_div'));
       
        var runOnce = google.visualization.events.addListener(table, 'ready', function () {
            document.getElementById('toCSV').onclick = function () {
                var tempData = data;
                var csvData = [];
                var tmpArr = [];
                var tmpStr = '';
                for (var i = 0; i < tempData.getNumberOfColumns(); i++) {
                    // replace double-quotes with double-double quotes for CSV compatibility
                    tmpStr = tempData.getColumnLabel(i).replace(/"/g, '""');
                    tmpArr.push('"' + tmpStr + '"');
                }
                csvData.push(tmpArr);
                for (var i = 0; i < tempData.getNumberOfRows(); i++) {
                    tmpArr = [];
                    for (var j = 0; j < tempData.getNumberOfColumns(); j++) {
                        switch(data.getColumnType(j)) {
                            case 'string':
                                // replace double-quotes with double-double quotes for CSV compatibility
                                tmpStr = tempData.getValue(i, j).replace(/"/g, '""');
                                tmpArr.push('"' + tmpStr + '"');
                                break;
                            case 'number':
                                tmpArr.push(tempData.getValue(i, j));
                                break;
                            case 'boolean':
                                tmpArr.push((tempData.getValue(i, j)) ? 'True' : 'False');
                                break;
                            case 'date':
                                // decide what to do here, as there is no universal date format
                                break;
                            case 'datetime':
                                // decide what to do here, as there is no universal date format
                                break;
                            case 'timeofday':
                                // decide what to do here, as there is no universal date format
                                break;
                            default:
                                // should never trigger
                        }
                    }
                    csvData.push(tmpArr.join(','));
                }
                var output = csvData.join('\n');
                var uri = 'data:text/csv;charset=UTF-8,' + encodeURIComponent(output);
                alert('You may need to rename the downloaded file with a ".csv" extension to open it.');
                window.open(uri);
            };
            google.visualization.events.removeListener(runOnce);
        });
      
        
        table.draw(data, {showRowNumber: false});
        
      }
    </script>
	 
	 <fieldset>
	 	<legend><?php echo JText::_('Report Table') ?></legend>
	 	<div id="table_div" style="width:90%;"></div>
	 	<input type="button" id="toCSV" value="Click to download data as CSV" />
	 </fieldset>
</div>