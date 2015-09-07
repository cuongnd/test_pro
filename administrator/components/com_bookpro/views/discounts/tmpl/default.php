<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewReservations */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

//BookProHelper::setSubmenu(2);

JToolBarHelper::title(JText::_('COM_BOOKPRO_DISCOUNT_MANAGER'), 'user.png');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 9 : 10;

echo $this->selectable;

$editCustomer = JText::_('COM_BOOKPRO_DISCOUNT_EDIT');
$titleEditAcount = JText::_('COM_BOOKPRO_DISCOUNT_EDIT');


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$pagination = &$this->pagination;

?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	
	<div id="editcell">
		<table class="adminlist table-striped table" cellspacing="1">
			<thead>
				<tr>
					<th width="2%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
					<?php } ?>
					<th class="title" width="30%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_DISCOUNT_AMOUNT'),'amout', $orderDir, $order); ?>
					</th>
					<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_DISCOUNT_TYPE'); ?>
					</th>
					<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_DISCOUNT_APPLICATION'); ?>
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
					<tr><td colspan="<?php echo $colspan; ?>"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php } else { ?>
				    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
				    
				    	<?php $subject = &$this->items[$i];
							$link = JRoute::_(ARoute::edit(CONTROLLER_DISCOUNT, $subject->id));
				    	?>
				    	     
				    	<tr class="row<?php echo ($i % 2); ?>">
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    		<?php if (! $this->selectable) { ?>
				    			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    		<?php } ?>
				    		<td class="title"><a href="<?php echo $link; ?>"><?php echo $subject->amount; ?></a></td>
				    		<td><?php echo $subject->type?"Percent":"Total"; ?> </td>
				    		<td><?php echo $subject->app_id; ?> </td>
				    						    		
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
		</table>
		
		<div class="clr">&nbsp;</div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<?php $tmpl = JRequest::getCmd('tmpl'); ?>
	<?php if ($tmpl) { ?>
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
	<?php } ?>	
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_DISCOUNT; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>