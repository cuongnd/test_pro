<?php
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(21);
AImporter::helper('contants','date','currency','report');

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

$cats = ReportHelper::getCategoryTour();
foreach ($cats AS $cat){
	$tours = ReportHelper::getTourCatIds($cat->id);
	
	$total = ReportHelper::getToursCat($date_from, $date_to, $tours);
	$cat->total = $total;
}


?>

<div class="width-20 fltlft">
<?php echo $this->loadTemplate('menu'); ?>
</div>
<div class="width-80 fltlft">
<?php echo $this->loadTemplate('search') ?>
<fieldset>
	 <legend><?php echo JText::_('Category report')?></legend>
	 <script type="text/javascript">
		      google.load("visualization", "1", {packages:["corechart"]});
		      google.setOnLoadCallback(drawChart);
		      function drawChart() {
		        var data = google.visualization.arrayToDataTable([
		          ['Tuor', 'Total'],
		          <?php foreach ($cats as $k=>$cat){ 
		          	
		          	?>
		          <?php if ($k < count($cats)-1){ ?>
		          	['<?php echo $cat->title; ?>',  <?php echo $cat->total; ?>],
		          <?php }else{ ?>
		          	['<?php echo $cat->title; ?>',<?php echo $cat->total; ?>]
		          <?php } ?>
		          <?php } ?>
		          
		          
		        ]);
		
		        var options = {
		          title: '<?php echo JText::_('Category Report Pie Chart') ?>',
		          hAxis: {title: 'Pie Chart',  titleTextStyle: {color: 'red'}}
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
        data.addColumn('string', '<?php echo JText::_('Category') ?>');
        data.addColumn('number', '<?php echo JText::_('No. of Bookings') ?>');
        
        data.addRows([
          <?php 
          $i = 0;
          foreach($cats as $k=>$v){ ?>
          <?php 
             if ($i < count($cats)-1){
           ?>
           ['<?php echo $v->title ?>',  {v: <?php echo $v->total ?>}],
           <?php }else{ ?>
           ['<?php echo $v->title ?>',  {v: <?php echo $v->total ?>}]
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
	 	<legend><?php echo JText::_('Category Report Table') ?></legend>
	 	<div id="table_div" style="width:100%;height:500px;"></div>
	 </fieldset>
</div>