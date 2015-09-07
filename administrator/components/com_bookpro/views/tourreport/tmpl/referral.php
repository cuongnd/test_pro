<?php
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
AImporter::helper('contants','date','currency','report');
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(21);

?>
<div>
<div style="width:20%;float:left;"><?php echo $this->loadTemplate('menu')?></div>
<div style="width:80%;float:right;">
<?php 
	
	$chart = array();
	$cat = ReportHelper::getCategory();
	$datefrom = JRequest::getVar('filter_from');
	
	$dateto = JRequest::getVar('filter_to');
	if(!$datefrom){
		$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();
	}
	if(!$dateto){
		$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
	}
	
	
	$chart = array();
	foreach ($cat as $ct){
		$cUser = ReportHelper::getReferralCustomer($datefrom,$dateto,$ct->id);
		
		$chart[$ct->title] = $cUser;
		
	}
	
?>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Month", "Customer"],
          <?php 
          $i = 0;
          foreach ($chart as $k=>$v){ ?>
          <?php 
          if ($i < count($chart)-1){
          ?>
          ["<?php echo $k; ?>",  <?php echo $v ?> ],
          <?php }else{ ?>
          ["<?php echo $k; ?>",  <?php echo $v; ?> ]
          <?php } ?>
          <?php
          	$i++; 
			} ?>
        ]);

        var options = {
          title: 'Refferal Report'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_ref'));
        chart.draw(data, options);
      }
    </script>
    <?php echo $this->loadTemplate('search');?>
	 
	<fieldset>
	 <legend><?php echo JText::_('Referral report')?></legend>
	 	<div id="chart_ref" style="width: 100%; height: 500px;"></div>
	 
	 </fieldset>
	  <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?php echo JText::_('Source') ?>');
        data.addColumn('number', '<?php echo JText::_('No. of Bookings') ?>');
        
        data.addRows([
          <?php 
          $i = 0;
          foreach($chart as $k=>$v){ ?>
          <?php 
             if ($i < count($chart)-1){
           ?>
           ['<?php echo $k ?>',  {v: <?php echo $v ?>}],
           <?php }else{ ?>
           ['<?php echo $k ?>',  {v: <?php echo $v ?>}]
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
	 	<legend><?php echo JText::_('Referral Report Table') ?></legend>
	 	<div id="table_div" style="width:100%;height:500px;"></div>
	 </fieldset>
</div>
</div>