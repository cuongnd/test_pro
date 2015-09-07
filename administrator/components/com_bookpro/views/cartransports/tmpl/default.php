<?php defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(2);
JToolBarHelper::title(JText::_('COM_BOOKPRO_TRANSPORT_MANAGER'), 'object');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
/*JToolBarHelper::publish();
JToolBarHelper::unpublishList();    */
JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this -> selectable ? 7 : 16;
$editSubject = $this -> escape(JText::_('Edit Flight'));
$notFound = '- ' . JText::_('not found') . ' -';
$orderDir = $this -> lists['order_Dir'];
$order = $this -> lists['order'];
$itemsCount = count($this -> items);
$pagination = &$this -> pagination;
?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft form-inline">
				<div class="filter-search fltlft">
		        <label for="From Airport"><?php echo JText::_('COM_BOOKPRO_FROM'); ?>: </label>
				
				<?php echo $this -> dfrom; ?>
				
				<label for="To Airport"><?php echo JText::_('COM_BOOKPRO_TO'); ?>: </label>
				
				<?php echo $this -> dto; ?>
				</div>
				<div class="btn-group pull-left hidden-phone fltlft">
					<button onclick="this.form.submit();" class="btn">
						<?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
					</button>
					<button onclick="this.form.reset.value=1;this.form.submit();" class="btn">
						<?php echo JText::_('COM_BOOKPRO_RESET'); ?>
					</button>
				</div>
			</div>        
			
		
		
	</fieldset>
	<div id="editcell">
		<table class="adminlist table-striped table">
			<thead>
				<tr>
					<th width="1%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
						<input type="checkbox" class="inputCheckbox"
						name="toggle" value=""
						onclick="Joomla.checkAll(this);" />
						</th>
					<?php } ?>
                    <th width="30%">
                        <?php echo JText::_('Title'); ?>
                    </th>		
                    <th class="title" width="30%">
                        <?php echo JHTML::_('grid.sort',JText::_('Code'), 'code', $orderDir, $order); ?>
                    </th>                    			
					<th width="30%">
				        <?php echo JText::_('COM_BOOKPRO_TRANSPORT_FROM'); ?>
					</th>
					<th width="30%">
				        <?php echo JText::_('COM_BOOKPRO_TRANSPORT_TO'); ?>
					</th>
                    <th width="5%">
                            <?php 
                                echo JHTML::_('grid.sort', JText::_('COM_BOOPRO_ORDER'), 'ordering', $orderDir, $order);
                                if ($this->turnOnOrdering) {
                                    echo JHTML::_('grid.order', $this->items);
                                } 
                            ?>
                    </th>                    
                    <th width="5%">
                        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_AIRPORT_STATE'), 'state', $orderDir, $order); ?>
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
						$link = JRoute::_(ARoute::edit(CONTROLLER_CAR_TRANSPORT, $subject->id));
						$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
						$isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out);
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this -> pagination -> getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject -> from . '-' . $subject -> to; ?></a>
					    		
				    		</td>
				    		<td>
						        <a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject -> code; ?></a>
				    		</td>
                            <td>
                                <?php echo $subject -> from; ?>
                            </td>                            
				    		<td>
						          <?php echo $subject -> to; ?>
				    		</td>
                            
                            <?php if (! $this->selectable) { ?>
                                <td class="order"><?php echo AHtml::orderTree($this->items,$i, $this->pagination, $this->turnOnOrdering, $itemsCount); ?></td>
                            <?php } ?>
                                                        
                            <td style="text-align: center;"><?php echo AHtml::state($subject, $i, ! $element); ?></td>                            
				    		
				    		<td style="text-align: left; white-space: nowrap;"><?php echo number_format($subject -> id, 0, '', ''); ?></td>
				    	</tr>
				    <?php
					}
					}
					?>
			</tbody>
		</table>
		
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="cid[]"	value="" /> 
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR_TRANSPORT; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>
