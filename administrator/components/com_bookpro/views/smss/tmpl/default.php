<?php

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('SMS Manager'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();

JToolBarHelper::publish();
JToolBarHelper::unpublishList();

//JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy');

//JToolBarHelper::divider();

JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 7 : 10;

$titleEdit = $this->escape(JText::_('COM_BOOKPRO_SMS_MANAGER'));

$notFound = '- ' . JText::_('not found') . ' -';
$appendSubject = $this->escape(JText::_('Append object'));


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;


?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="filterTable">
		<tr>
			<td>
				<label for="Country"><?php echo JText::_('COM_BOOKPRO_SMS_CITY'); ?>: </label>
		        <input class="text_area" type="text" name="title" id="title" size="20" maxlength="255" value="<?php echo $this->lists['title']; ?>" />
		        <label for="Country"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>: </label>
				<?php echo $this->status;?>
				<button onclick="this.form.submit();"><?php echo JText::_('COM_BOOKPRO_GO'); ?></button>
				<button onclick="this.form.reset.value=1;this.form.submit();"><?php echo JText::_('COM_BOOKPRO_RESET'); ?></button>
			</td>
		</tr>
		
	</table>
	<div id="editcell">
		<table class="adminlist" >
			<thead>
				<tr>
					<th width="3%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
														
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="checkAll(<?php echo $itemsCount; ?>);" />
							
						</th>
					<?php } ?>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_SMS_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_FROM'); ?>
					</th>
					<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_TO'); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_STATUS'), 'status', $orderDir, $order); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_SMS_CREATED'), 'created', $orderDir, $order); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_SMS_DELIVERY_TIME'), 'sent_time', $orderDir, $order); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_SMS_SCHEDULED_TIME'), 'schedule_time', $orderDir, $order); ?>
					</th>
					<th width="5%">
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
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_SMS, $subject->id));
				    		$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
				    		$isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out); 
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
					    		
				    		</td>
				    		<td>
						        <?php  echo $subject->from;?>
				    		</td>
				    		<td>
						        <?php  echo $subject->to;?>
				    		</td>
				    		
				    		<td>
						        <?php  echo $subject->status;?>
				    		</td>
				    		<td>
						        <?php  echo JFactory::getDate($subject->created)->format('d-m-Y H:i:s');?>
				    		</td>
				    		<td>
						        <?php  echo JFactory::getDate($subject->sent_time)->format('d-m-Y H:i:s');?>
				    		</td>
				    		<td>
						        <?php  echo JFactory::getDate($subject->schedule_time)->format('d-m-Y H:i:s');?>
				    		</td>
				    		
				    		
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
	<input type="hidden" name="cid[]"	value="" /> 
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_SMS; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>
