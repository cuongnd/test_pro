<?php defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('COM_BOOKPRO_COUPON_MANAGER'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();

JToolBarHelper::publish();
JToolBarHelper::unpublishList();

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
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
		    <div class="btn-group pull-left hidden-phone fltlft inline">
		            <div class="btn-group pull-left hidden-phone fltlft">
					    <input type="text" name="name"  value="<?php echo $this->lists['name']?>" placeholder="<?php echo JText::_('Name')?>">
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
		</div>
		<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="clearfix"></div>
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
					
					<th width="5%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_AIRPORT_STATE'), 'state', $orderDir, $order); ?>
					</th>
					
					<th class="title" width="20%">
				        <?php echo JHTML::_('grid.sort', JText::_('Name'), 'name', $orderDir, $order); ?>
					</th>
                    
                    <th class="title" width="20%">
                        <?php echo JText::_('Code'); ?>
                    </th>
                    
                    <th class="title" width="20%">
                        <?php echo JText::_('Category Car'); ?>
                    </th>                                      
                    
                    <th width="10%">
                            <?php 
                                echo JHTML::_('grid.sort', JText::_('COM_BOOPRO_ORDER'), 'ordering', $orderDir, $order);
                                if ($this->turnOnOrdering) {
                                    echo JHTML::_('grid.order', $this->items);
                                } 
                            ?>
                     </th>                    
                    
					
					<th width="10%">
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
						$link = JRoute::_(ARoute::edit(CONTROLLER_CAR, $subject->id));
						$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
						$isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out);
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this -> pagination -> getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td class="center">
								<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'coupons.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject -> name; ?></a>
				    		</td>
                            
                            <td>
                                <?php echo $subject -> code; ?>
                            </td>				    		
				    	
                            <td>
                                <?php echo $subject -> car_category_id; ?>
                            </td>    
                            
                            
                            <?php if (! $this->selectable) { ?>
                                <td class="order"><?php echo AHtml::orderTree($this->items,$i, $this->pagination, $this->turnOnOrdering, $itemsCount); ?></td>
                            <?php } ?>                                                
				    		
				    			<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject -> id, 0, '', ' '); ?></td>
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
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>