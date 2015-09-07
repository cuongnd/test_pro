
<?php 
AImporter::helper('transport','date');
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
		$items[$i]->telephone. "',{v:".
		$items[$i]->adult. ",f:'".$items[$i]->adult."'},'".
		$items[$i]->pickup. "','".
		$items[$i]->drop. "'])".PHP_EOL;
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
        data.addColumn('number', 'Pax');
        data.addColumn('string', 'Pickup Location');
        data.addColumn('string', 'Drop Location');
        <?php echo $row; ?>   
        
		

        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true});
      }
    </script>
  </head>

  <body>
  <div style="width:20%;float:left;"><?php echo $this->loadTemplate('menu')?></div>
<div style="width:80%;float:right;">

<?php echo $this->loadTemplate('search')?>;
<div class="abutton">
			<?php 	
			$filter_from = JRequest::getVar('filter_from',$datefrom);
					
			$filter_to = JRequest::getVar('filter_to',$dateto);	
			$datename = JRequest::getVar('datename','Month');
			?>
			<a style="float: right;" target="_blank"
				href="index.php?option=com_bookpro&view=transportreport&layout=printdriver&tmpl=component&datename=<?php echo $datename; ?>&filter_from=<?php echo $filter_from; ?>&filter_to=<?php echo $filter_to; ?>">
				<span style="height: 32px; width: 32px; display: block;"
				class="icon-32-print"></span> <?php echo JText::_('Print'); ?>
			</a>
			<div class="clr"></div>
		</div>
    <div id='table_div'></div>
 </div>   
  </body>
