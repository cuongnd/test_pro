<?php

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('Car Destination manager'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();

/*JToolBarHelper::publish();
JToolBarHelper::unpublishList(); */


JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 7 : 10;

$titleEdit = $this->escape(JText::_('Edit Car Destination'));

$notFound = '- ' . JText::_('not found') . ' -';
$appendSubject = $this->escape(JText::_('Append object'));


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;


?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
		<div class="btn-group pull-left hidden-phone fltlft">
		        <input class="text_area" type="text" name="title" id="title" size="20" maxlength="255" value="<?php echo $this->lists['title']; ?>"  placeholder="<?php echo JText::_('Destination')?>"/>
				<?php echo $this->countries;?>
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_('COM_BOOKPRO_GO'); ?></button>
				<button onclick="this.form.reset.value=1;this.form.submit();" class="btn"><?php echo JText::_('COM_BOOKPRO_RESET'); ?></button>
		</div>
		</div>
	</fieldset>
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
					
					<th class="title" width="30%">
				        <?php echo JHTML::_('grid.sort',JText::_('Title'), 'title', $orderDir, $order); ?>
					</th>
					<th>
				        <?php echo JText::_('Code'); ?>
					</th>
                    <th width="30%">
                        <?php echo JText::_('Description'); ?>
                    </th>
                    
                    <th width="10%">
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
                    					
					<th width="10%">	
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
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_CAR_DESTINATION, $subject->id));
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
				    			                 
						        <?php  echo $subject->code;?>
						        	
				    		</td>
                            
                            <td>
                                                 
                                <?php  echo $subject->description;?>
                                    
                            </td>
                            
                            <?php if (! $this->selectable) { ?>
                                <td class="order"><?php echo AHtml::orderTree($this->items,$i, $this->pagination, $this->turnOnOrdering, $itemsCount); ?></td>
                            <?php } ?>                                                
                        
                            <td style="text-align: center;"><?php echo AHtml::state($subject, $i, !$element); ?></td>
				    		
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ''); ?></td>
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
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR_DESTINATION; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>