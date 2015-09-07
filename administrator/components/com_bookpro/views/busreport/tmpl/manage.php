<?php 
defined('_JEXEC') or die('Restricted access');
AImporter::helper('report','date','currency');
JToolBarHelper::custom('exportmanage','export','','Export',false);
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(21);

$datefrom=JRequest::getVar('filter_from',null);
$dateto=JRequest::getVar('filter_to','');

if(!$datefrom){
	$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();
}
if(!$dateto){
	$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
}

$this->items=ReportHelper::buildAdminReport($datefrom, $dateto);
$itemsCount=count($this->items);
?>
<div>
<div style="width:20%;float:left;"><?php echo $this->loadTemplate('menu')?></div>
<div style="width:80%;float:right;">

<?php echo $this->loadTemplate('search')?>



<h2 class="titlePage"><?php echo JText::_('COM_BOOKPRO_BUSREPORT_BOOKING_LIST'); ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th width="3%">#</th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_BK_NO"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_REC_DATE"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_DEPART_DATE"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_FULL_NAME"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_PHONE"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_TOUR_CODE"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_PAX"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_TOTAL"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_BOOKED"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_PAID"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BUSREPORT_NOTES"); ?></th>

		</tr>
	</thead>
	
	<tbody>
	<?php if ($itemsCount == 0) { ?>
		<tr>
			<td colspan="13" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_BUSREPORT_NO_BOOKING'); ?></td>
		</tr>
		<?php } ?>
		<?php for ($i = 0; $i < $itemsCount; $i++) { ?>
		<?php $subject = &$this->items[$i]; ?>
		<?php

		?>

		<tr class="row<?php echo $i % 2; ?>">
			<td style="text-align: right; white-space: nowrap;"><?php echo $i+1?></td>
			<td><?php echo $subject->ordNo ?></td>
			<td><?php echo DateHelper::formatDate($subject->receiveDate,'d-m-Y') ?></td>
			<td><?php echo DateHelper::formatDate($subject->start,'d-m-Y') ?></td>
			<td><?php echo $subject->fullname ?></td>
			<td><?php echo $subject->telephone ?></td>
			<td><?php echo $subject->tour_code ?></td>
			<td><?php echo ($subject->adult  + $subject->child) ?></td>
			<td><?php echo CurrencyHelper::formatprice($subject->total); ?></td>
			<td><?php echo $subject->order_status; ?></td>
			<td><?php echo $subject->pay_status; ?></td>
			<td><?php echo $subject->notes;	?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
</div>