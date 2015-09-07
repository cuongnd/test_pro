<?php

defined('_JEXEC') or die;
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
?>

<form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=roomlabels'); ?>" method="post" name="adminForm" id="adminForm">
	
	
	<div id="j-main-container" class="span10">

		<div id="filter-bar" class="btn-toolbar">
		
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
						
		</div>
		<div class="clearfix"> </div>
		
		
	 <table class="table-striped table">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					
					
				<th>
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'title',$orderDir, $order); ?>
				</th>
				
				
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $orderDir, $order); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'roomlabels.', true, 'cb', $item->publish_up, $item->publish_down); ?>
					</td>
					<td>
						<?php if ($item->checked_out) { ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'location.', true); ?>
						<?php } ?>
						<a href="<?php echo JRoute::_('index.php?option=com_bookpro&task=roomlabel.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					</td>
					
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $order  ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
