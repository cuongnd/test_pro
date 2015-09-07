<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
//JHtml::_('behavior.tooltip');
AImporter::helper('currency','hotel');
BookProHelper::setSubmenu(1);
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::deleteList('', 'trash', 'Trash');

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);
$pagination = &$this->pagination;

?>
<div class="span10">
<h2 class="titlePage"><?php echo JText::_('COM_BOOKPRO_ORDER_LIST'); ?></h2>
<form action="index.php" method="post" name="adminForm" id="adminForm">
  <fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<div class="btn-group pull-left hidden-phone fltlft">
			<?php echo JHtml::calendar($this->lists['from'], 'filter_from', 'filter_from','%Y-%m-%d','placeholder="From date" style="width: auto"') ?>
			<?php echo JHtml::calendar($this->lists['to'], 'filter_to', 'filter_to','%Y-%m-%d','placeholder="From date" style="width: auto"') ?>
			<?php //echo $this->orderstatus ?>
			<?php echo $this->paystatus ?>
			<?php echo $this->getOrderTypeSelect($this->lists['type']) ?>
			</div>
		</div>
		<div class="btn-group pull-left hidden-phone fltlft">
					<button onclick="this.form.submit();" class="btn">
						<?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
					</button>
				</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
			</div>
	</fieldset>

<div id="editcell">

<table class="adminlist table-striped table">
	<thead>
		<tr>
			<th width="1%">#</th>
			<?php if (! $this->selectable) { ?>
			<th width="2%"><input type="checkbox" class="inputCheckbox"
				name="toggle" value=""
				onclick="Joomla.checkAll(this);" /></th>
				<?php } ?>
			<th><?php echo JText::_("COM_BOOKPRO_BOOKING_HOTEL_DESTINATION"); ?>
			</th>
			<th><?php echo JText::_("COM_BOOKPRO_BOOKING_CUSTOMER_EMAIL_MOBILE"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BOOKING_AMOUNT_DISCOUNT"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BOOKING_PAYSATUS_PAYMETHOD"); ?></th>
			<th><?php echo JText::_("COM_BOOKPRO_BOOKING_DATE_IP"); ?></th>



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
			<td colspan="13" class="emptyListInfo"><?php echo JText::_('No reservations.'); ?></td>
		</tr>
		<?php } ?>
		<?php for ($i = 0; $i < $itemsCount; $i++) { ?>
		<?php $subject = &$this->items[$i];
		$customer = $subject->customer;
		//$hotel = new BookProModelHotel();
		$hotel = HotelHelper::getObjectHotelByOrder($subject->id);
		?>
		<?php

		?>

		<tr class="row<?php echo $i % 2; ?>">
			<td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
			<?php if (! $this->selectable) { ?>
			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
			<?php } ?>
			<td><a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $hotel->title; ?></a>
				<div>
					<?php echo $hotel->city_title; ?>
				</div>
			</td>
			<td>
				<div><?php echo $customer->fullname; ?></div>
				<div><?php echo $customer->email; ?></div>
				<div><?php echo $customer->mobile; ?></div>
			</td>
			<td>
				<div><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_AMOUNT',CurrencyHelper::formatprice($subject->total)) ?></div>
				<div><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_DISCOUNT',CurrencyHelper::formatprice($subject->discount)) ?></div>
			</td>
			<td>
				<?php echo JText::sprintf('COM_BOOKPRO_BOOKING_SATUS_METHOD',$subject->pay_status,$subject->pay_method); ?>
			</td>


			<td>
			<div><?php echo JFactory::getDate($subject->created)->format('d-m-Y H:i:s') ?></div>
			<?php echo $subject->ip_address; ?></td>


		</tr>
		<?php } ?>
	</tbody>
</table>
</div>

<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
	<input type="hidden" name="controller"	value="<?php echo CONTROLLER_ORDER; ?>" />
 	<input	type="hidden" name="task" value="bookings" />
	<input type="hidden" name="reset"	value="0" />
	<input type="hidden" name="cid[]"	value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input	type="hidden" name="filter_order" value="<?php echo $order; ?>" />
	<input	type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>" />

	 <?php echo JHTML::_('form.token'); ?>
</form>

</div>
