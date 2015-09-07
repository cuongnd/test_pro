<?php
defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */
$app = JFactory::getApplication();
$input = $app->input;
JHTML::_('behavior.tooltip');


BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('COM_BOOKPRO_PAYMENT_LOG_MANAGER'), 'object');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();


JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 7 : 10;
$notFound = '- ' . JText::_('not found') . ' -';
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>
<div class="span10">
	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<div class="lead">
			<?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') . ': ' . JHtml::link(JRoute::_(ARoute::detail(CONTROLLER_ORDER, $this->order->id)),$this->order->order_number);?>
			,
			<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL') . ': ' . CurrencyHelper::formatprice($this->order->total); ?>
		</div>



		<table class="table-striped table">
			<thead>
				<tr>

					<?php if (!$this->selectable) { ?>
					<th width="2%"><?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<?php } ?>
					<th width="2%"><?php echo JHTML::_('grid.sort', JText::_('JSTATUS'), 'state', $orderDir, $order); ?>
					</th>


					<th class="title" width="10%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_PAYMENT_LOG_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					<th width="15%"><?php echo JText::_('COM_BOOKPRO_FIELD_PAYMENT_LOG_GATEWAY'); ?>
					</th>

					<th width="15%"><?php echo JText::_('COM_BOOKPRO_FIELD_PAYMENT_LOG_AMOUNT'); ?>
					</th>

					<th width="15%"><?php echo JText::_('JGLOBAL_FIELD_CREATED_LABEL'); ?>
					</th>

					<th width="15%"><?php echo JText::_('JGLOBAL_FIELD_CREATED_BY_LABEL'); ?>
					</th>

				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo $colspan; ?>"><?php echo $pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
				<tr>
					<td colspan="<?php echo $colspan; ?>" class="emptyListInfo"><?php echo JText::_('No items found.'); ?>
					</td>
				</tr>
				<?php
} else {

                    for ($i = 0; $i < $itemsCount; $i++) {
                        $subject = &$this->items[$i];
                        $link = JRoute::_(ARoute::view('paylog', null, null, array('id' => $subject->id,'layout'=>'edit')));
                        ?>
				<tr>

					<?php if (!$this->selectable) { ?>
					<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
					</td>
					<?php } ?>

					<td class="center"><?php echo JHtml::_('jgrid.published', $subject->state, $i, 'paylogs.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
					</td>
					<td><a href="<?php echo $link; ?>"><?php echo $subject->title; ?> </a>
					</td>
					<td><?php echo $subject->gateway; ?>
					</td>

					<td><?php echo $subject->amount; ?>
					</td>

					<td><?php echo $subject->created; ?>
					</td>

					<td><?php echo $subject->username; ?>
					</td>

				</tr>
				<?php
                    }
                }
                ?>
			</tbody>
		</table>

		<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
			type="hidden" name="task"
			value="<?php echo JRequest::getCmd('task'); ?>" /> <input
			type="hidden" name="reset" value="0" />

		<!--        <input type="hidden" name="order_id" value="<?php echo $input->get('order_id', 0); ?>"/>-->

		<input type="hidden" name="cid[]" value="" /> <input type="hidden"
			name="boxchecked" value="0" /> <input type="hidden" name="controller"
			value="<?php echo CONTROLLER_PAYLOG; ?>" /> <input type="hidden"
			name="filter_order" value="<?php echo $order; ?>" /> <input
			type="hidden" name="filter_order_Dir"
			value="<?php echo $orderDir; ?>" /> <input type="hidden"
			name="<?php echo SESSION_TESTER; ?>" value="1" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
