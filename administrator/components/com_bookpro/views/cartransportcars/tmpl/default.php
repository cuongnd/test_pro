<?php defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('Car Transport Car Manager'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();

/*JToolBarHelper::publish();
JToolBarHelper::unpublishList();  */

JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this -> selectable ? 7 : 10;

$titleEdit = $this -> escape(JText::_('Edit Car'));

$notFound = '- ' . JText::_('not found') . ' -';
$appendSubject = $this -> escape(JText::_('Append object'));

$orderDir = $this -> lists['order_Dir'];
$order = $this -> lists['order'];
$itemsCount = count($this -> items);
$pagination = &$this -> pagination;
?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">

	<div id="editcell" >
		<table class="adminlist table-striped table" >
			<thead>
				<tr>
					<th width="3%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
					<?php } ?>
					
					<th class="title" width="20%">
				        <?php echo JHTML::_('grid.sort', JText::_('car_id'), 'car_id', $orderDir, $order); ?>
					</th>
                    
                    <th class="title" width="20%">
                        <?php echo JText::_('Car price'); ?>
                    </th>
                    
                    <th class="title" width="20%">
                        <?php echo JText::_('car_transport_id'); ?>
                    </th>                                      
                            
					<th width="5%">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
    			<tr>
    				<td colspan="<?php echo $colspan; ?>">
    				    <?php echo $pagination -> getListFooter(); ?>
    				</td>
    			</tr>
			</tfoot>
			<tbody>
				<?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
					<tr><td colspan="<?php echo $colspan; ?>" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php } else {

						for ($i = 0; $i < $itemsCount; $i++) {
						$subject = &$this->items[$i];
						$link = JRoute::_(ARoute::edit(CONTROLLER_CAR_TRANSPORT_CAR, $subject->id));
						$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
						$isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out);
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this -> pagination -> getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject -> car_id; ?></a>
				    		</td>
                            
                            <td>
                                <?php echo $subject -> car_price; ?>
                            </td>				    		
				    	
                            <td>
                                <?php echo $subject -> car_transport_id; ?>
                            </td>                        
                        	 
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject -> id, 0, '', ''); ?></td>
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
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR_TRANSPORT_CAR; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>