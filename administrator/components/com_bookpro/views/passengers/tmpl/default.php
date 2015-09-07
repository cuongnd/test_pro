<?php


defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(3);
AImporter::helper('date');

JToolBarHelper::title(JText::_('COM_BOOKPRO_PASSENGER_MANAGER'), 'user.png');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 9 : 10;

$titleEditAcount = JText::_('COM_BOOKPRO_PASSENGER_EDIT');

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$pagination = &$this->pagination;

?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	
		<table class="adminlist table-striped table" cellspacing="1">
			<thead>
				<tr>
					<th width="1%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
					<?php } ?>	
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_PASSENGER_FIRST_NAME'), 'firstname', $orderDir, $order); ?>
					</th>
					<th width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_PASSENGER_LAST_NAME'), 'lastname', $orderDir, $order); ?>
					</th>
					<th width="5%">
				        <?php echo JText::_('Age'); ?>
					</th>
					<th width="4%"><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER'); ?></th>
					<th width="15%"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT'); ?></th>
					<th width="10%%">
				       <?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY'); ?>
					</th>
					<th width="10%%">
				       <?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY'); ?>
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
				    	<?php $subject = &$this->items[$i]; ?>
				    	<?php $link = JRoute::_(ARoute::edit(CONTROLLER_PASSENGER, $subject->id)); ?>
				    	<?php $isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out); ?>     
				    	<tr class="row<?php echo ($i % 2); ?>">
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    		<?php if (! $this->selectable) { ?>
				    			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    		<?php } ?>
				    		<td><a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->firstname; ?></a></td>
				    		<td style="text-align: center;"><?php echo $subject->lastname; ?></td>
				    		<td class="emailCell">
				    		
				    		<?php echo ($subject->group_title)?>
				    		</td>
				    		<td><?php switch ($subject->gender){
				    			case 1:
				    			echo JText::_('Male');
				    			break;
				    			case 0:
				    			echo JText::_('Female');
				    			break;

				    }?>&nbsp;</td>
				    		<td><?php echo $subject->passport; ?>&nbsp;</td>
				    		
				    		<td><?php echo DateHelper::formatDate($subject->birthday,'d-m-Y'); ?>&nbsp;</td>
				    		<td><?php echo $subject->country; ?>&nbsp;</td>
				    		
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
		</table>
		
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<input type="hidden" name="cid[]"	value="" /> 
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_PASSENGER; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>