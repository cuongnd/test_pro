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

$config = AFactory::getConfig();
?>
<?php echo $this->loadTemplate('search')?>;
<div>













<h2 class="titlePage"><?php echo JText::_('Booking List'); ?></h2>
<div class="header">
	<?php echo $config->invoice_header; ?>
</div>

<table class="adminlist">

	<thead>

		<tr>

			<th width="3%">#</th>

			<th><?php echo JText::_("Bk No."); ?></th>

			<th><?php echo JText::_("REC Date"); ?></th>

			<th><?php echo JText::_("Depart date"); ?></th>

			<th><?php echo JText::_("Full Name"); ?></th>

			<th><?php echo JText::_("Phone"); ?></th>

			<th><?php echo JText::_("Tour Code"); ?></th>

			<th><?php echo JText::_("Pax"); ?></th>

			<th><?php echo JText::_("Total"); ?></th>

			<th><?php echo JText::_("Booked"); ?></th>

			<th><?php echo JText::_("Paid"); ?></th>

			<th><?php echo JText::_("Notes"); ?></th>



		</tr>

	</thead>

	

	<tbody>

	<?php if ($itemsCount == 0) { ?>

		<tr>

			<td colspan="13" class="emptyListInfo"><?php echo JText::_('No booking.'); ?></td>

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
<div class="sale-footer">
	<?php echo $config->invoice_header; ?>
</div>
</div>

