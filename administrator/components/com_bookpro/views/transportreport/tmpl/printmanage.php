<?php 
defined('_JEXEC') or die('Restricted access');
AImporter::helper('transport','date','currency');
AImporter::css('invoice');
BookProHelper::setSubmenu(21);
$datefrom=JRequest::getVar('filter_from',null);
$dateto=JRequest::getVar('filter_to','');
if(!$datefrom){	$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();}
if(!$dateto){
	$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
}
$filter_from = JFactory::getDate($datefrom)->format('d/m/Y');
$filter_to = JFactory::getDate($dateto)->format('d/m/Y');
$datename = JRequest::getVar('datename','');

$str = $datename.", "; 
if ($filter_from == $filter_to) {
	$str .= "(".$filter_from.")";	
}else {
	$str .= "(".$filter_from."-".$filter_to.")";
}

$items=TransportHelper::buildAdminReport($datefrom, $dateto);
$itemsCount=count($this->items);

for ($i = 0;$i < count($items);$i++){
	
	$pax = $items[$i]->adult+$items[$i]->child;
	$row .= "data.addRow(['".$items[$i]->ordNo."','".
	DateHelper::formatDate($items[$i]->receiveDate,'d-m-Y')."','".
	DateHelper::formatDate($items[$i]->start,'d-m-Y')."','".
	$items[$i]->fullname."','".
	$items[$i]->telephone."','".
	
	$pax."','".
	CurrencyHelper::formatprice($items[$i]->total)."','".
	
	$items[$i]->order_status."','".
	$items[$i]->pay_status."','".
	$items[$i]->notes."'])".PHP_EOL;
	
}
$config = AFactory::getConfig();
?>
<script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Bk No.');
        data.addColumn('string', 'REC Date');
        data.addColumn('string', 'Depart Date');
        data.addColumn('string', 'Full Name');
        data.addColumn('string', 'Phone');
        
        data.addColumn('string', 'Pax');
        data.addColumn('string', 'Total');
        data.addColumn('string', 'Booked');
        data.addColumn('string', 'Paid');
        data.addColumn('string', 'Notes');
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
			<h2 class="titlePage" style="text-align: left;"><?php echo JText::_('AIRPORT Admin Report'); ?>: <?php echo $str; ?></h2>
			<div id='table_div'></div>
		</div>
		<?php echo $config->invoice_footer; ?>		
	</div>
</div>



