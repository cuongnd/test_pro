<?php

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(12);

JToolBarHelper::title(JText::_('COM_BOOKPRO_ITINERARY_TOUR_MANAGER'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();

JToolBarHelper::divider();


JToolBarHelper::publish();
JToolBarHelper::unpublishList();

JToolBarHelper::deleteList('', 'trash', 'Trash');
JToolBarHelper::back();

$colspan = $this->selectable ? 7 : 16;

$editSubject = $this->escape(JText::_('COM_BOOKPRO_ITINERARY_EDIT'));

$notFound = '- ' . JText::_('not found') . ' -';

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
//var_dump($itemsCount);exit();
$pagination = &$this->pagination;


?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="form-inline pull-left">
			<label for="Airline"><?php echo JText::_('COM_BOOKPRO_TOUR_SELECT') ?></label>
			<?php echo $this->tours;?>
			<button onclick="this.form.submit();" class="btn"><?php echo JText::_('COM_BOOKPRO_SEARCH'); ?></button>
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
					<th width="1%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
														
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							
						</th>
					<?php } ?>
					
					<th width="1%" style="min-width: 20px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
					</th>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_ITINERARY_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_ITINERARY_MEAL'), 'title', $orderDir, $order); ?>
					</th>
					
					<th width="5%">
				 		 <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_ITINERARY_DESTINATION'), 'dest_id', $orderDir, $order); ?>
					</th>
							
					
						<th width="5%">
				        	<?php 
				        		echo JHTML::_('grid.sort', 'Order', 'ordering', $orderDir, $order);
				        		if ($this->turnOnOrdering) {
									echo JHTML::_('grid.order', $this->items);
								} 
							?>
						</th>
					
					
					
					<th width="1%">
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
				<?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
					<tr><td colspan="<?php echo $colspan; ?>" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php 
				
					} else {
												
						 for ($i = 0; $i < $itemsCount; $i++) { 
				    	 	$subject = &$this->items[$i]; 
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_ITINERARY, $subject->id));
				    		$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
				    		$isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out); 
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td class="center">
								<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'itineraries.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
					    		
				    		</td>
				    			<td>
						        <strong><?php  echo strtoupper($subject->meal);?></strong>
				    		</td>
				    		
				    		<td>
				    			                 
						        <?php  echo $subject->dest_name;?>
				    		</td>
				    						    						    		
				    		<?php if (! $this->selectable) { ?>
				    			<td class="order"><?php echo AHtml::orderTree($this->items,$i, $this->pagination, $this->turnOnOrdering, $itemsCount); ?></td>
				    		<?php } ?>
				    		
				    		
			    			<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php 
				    	}
					} 
					?>
			</tbody>
		</table>
		
		<div class="clr"></div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="cid[]"	value="<?php echo $this->customer->id; ?>" /> 
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_ITINERARY; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>