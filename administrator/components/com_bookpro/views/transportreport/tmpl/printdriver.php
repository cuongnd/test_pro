
<?php 
AImporter::helper('transport','date');
AImporter::css('invoice');
BookProHelper::setSubmenu($set);
$datefrom=JRequest::getVar('filter_from',null);
$dateto=JRequest::getVar('filter_to','');
if(!$datefrom){
	$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();
}
if(!$dateto){
	$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
}
$items=TransportHelper::buildDriverReport($datefrom, $dateto);


for ($i = 0; $i < count($items); $i++) {
	$start=DateHelper::formatDate($items[$i]->start,'d-m-Y');
	
	$row.="data.addRow(['".DateHelper::formatDate($items[$i]->start,'d-m-Y')."','".
		DateHelper::formatDate($items[$i]->start,'H:i:s')."','".
		$items[$i]->fullname. "','".
		BookProHelper::formatGender($items[$i]->gender,true). "','".
		$items[$i]->telephone. "','".
		$items[$i]->adult. "','".
		$items[$i]->pickup. "','".
		$items[$i]->drop. "'])".PHP_EOL;
}
$config = AFactory::getConfig();
$filter_from = JFactory::getDate($datefrom)->format('d/m/Y');
$filter_to = JFactory::getDate($dateto)->format('d/m/Y');
$datename = JRequest::getVar('datename','');
$str = " ".$datename.", ";
if ($filter_from == $filter_to) {
	$str.="(".$filter_from.")";
}else{
	$str.="(".$filter_from." - ".$filter_to.")";
}

?>
 <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Depart date');
        data.addColumn('string', 'Depart time');
        data.addColumn('string', 'Full Name');
        data.addColumn('string', 'M/F');
        data.addColumn('string', 'Phone');
        data.addColumn('string', 'Pax');
        data.addColumn('string', 'Pickup Location');
        data.addColumn('string', 'Drop Location');
        <?php echo $row; ?>   
        
		

        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true});
      }
    </script>
  

  
  <div class="wrapper" align="center">
	<div class="wrapper-center">
	
		<?php echo $config->invoice_header; ?>
		<div class="clr"></div>
		
		<div class="main">
			<h2 class="titlePage" style="text-align: left;"><?php echo JText::_('AIRPORT Driver Report'); ?>:<?php echo $str;?></h2>
			<div id='table_div'></div>
		</div>
		<?php echo $config->invoice_footer; ?>
	</div>
  </div>		
    
    
  
