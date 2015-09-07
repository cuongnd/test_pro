<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/* @var $this BookingViewReservations */

JHTML::_ ( 'behavior.tooltip' );

$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu ( 2 );

JToolBarHelper::title ( JText::_ ( 'COM_BOOKPRO_COUPON_MANAGER' ), 'user.png' );
JToolBarHelper::addNew ();
JToolBarHelper::editList ();
JToolBarHelper::divider ();
JToolBarHelper::deleteList ( '', 'trash', 'Trash' );
JToolBarHelper::publish();
JToolBarHelper::unpublishList();
$colspan = 12;
$editCustomer = JText::_ ( 'Edit Coupon' );
$titleEditAcount = JText::_ ( 'Edit Coupon' );
$orderDir = $this->lists ['order_Dir'];
$order = $this->lists ['order'];
$itemsCount = count ( $this->items );
$pagination = &$this->pagination;

?>

<div class="span10">
	<form action="index.php" method="post" name="adminForm" id="adminForm">

    <?php
				if ($this->hotel) {
					?>
      <h3><?php echo JText::_('COM_BOOKPRO_HOTEL').$this->hotel->title; ?></h3>
    <?php
				}
				?>

      <fieldset id="filter-bar">
			<div class="filter-search fltlft">
				<div class="btn-group pull-left hidden-phone fltlft">
					<input type="text" name="title"
						value="<?php echo $this->lists['title']?>"
						placeholder="<?php echo JText::_('COM_BOOKPRO_COUPON_TITLE')?>">

				</div>
				<div class="btn-group pull-left hidden-phone fltlft">
					<button onclick="this.form.submit();" class="btn">
						<?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
					</button>
				</div>

			</div>
			<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</fieldset>
		<div id="editcell">
			<table class="table" cellspacing="1">
				<thead>
					<tr>
						<th width="2%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
					<?php } ?>
					<th width="1%" style="min-width: 55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
					</th>
						<th class="title" width="30%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_COUPON_TITLE'), 'title', $orderDir, $order); ?>
					</th>
						<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_TYPE'); ?>
					</th>
					</th>
						<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_OBJECT_ID'); ?>
					</th>
						<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_CODE'); ?>
					</th>
						<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_AMOUNT'); ?>
					</th>

						<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_TOTAL'); ?>
					</th>
						<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_REMAIN'); ?>
					</th>
						<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_PUBLISH_DATE'); ?>
					</th>
						<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_UNPUBLISH_DATE'); ?>
					</th>

						<th width="4%">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>

					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="<?php echo $colspan; ?>">
    				    <?php echo $pagination->getListFooter(); ?>
    				</td>
					</tr>
				</tfoot>
				<tbody>
				<?php if (! is_array($this->items) || ! $itemsCount) { ?>
					<tr>
						<td colspan="<?php echo $colspan; ?>"><?php echo JText::_('COM_BOOKPRO_NO_ITEM'); ?></td>
					</tr>
				<?php } else { ?>
				    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>

				    	<?php

						$subject = &$this->items [$i];
						$link = JRoute::_ ( ARoute::edit ( CONTROLLER_COUPON, $subject->id ) );
						?>

				    	<tr class="row<?php echo ($i % 2); ?>">
						<td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    		<?php if (! $this->selectable) { ?>
				    			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    		<?php } ?>
			    		<td class="center">
							<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'coupons.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
						</td>
						<td><a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a></td>
						<td><?php echo $subject->type; ?> </td>
						<td><?php echo $subject->object_id; ?> </td>
						<td><?php echo $subject->code; ?> </td>
						<td><?php echo $subject->amount; ?> </td>
						<td><?php echo $subject->total; ?> </td>
						<td><?php echo $subject->remain; ?> </td>
						<td><?php echo $subject->publish_date; ?> </td>
						<td><?php echo $subject->unpublish_date; ?> </td>


						<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
					</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
			</table>
		</div>
		<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
			type="hidden" name="task"
			value="<?php echo JRequest::getCmd('task'); ?>" />
	<?php $tmpl = JRequest::getCmd('tmpl'); ?>
	<?php if ($tmpl) { ?>
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" />
	<?php } ?>
	<input type="hidden" name="reset" value="0" /> <input type="hidden"
			name="controller" value="<?php echo CONTROLLER_COUPON; ?>" /> <input
			type="hidden" name="boxchecked" value="0" /> <input type="hidden"
			name="filter_order" value="<?php echo $order; ?>" /> <input
			type="hidden" name="filter_order_Dir"
			value="<?php echo $orderDir; ?>" /> <input type="hidden"
			name="<?php echo SESSION_TESTER; ?>" value="1" /> <input
			type="hidden" name="hotel_id"
			value="<?php echo $this->lists['hotel_id']; ?> " />

	<?php echo JHTML::_('form.token'); ?>
</form>
</div>
