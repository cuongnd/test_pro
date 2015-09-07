<?php

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(6);



$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'a.lft');
$saveOrder 	= ($listOrder == 'a.lft' && $listDirn == 'asc');

$itemsCount = count($this->items);
$pagination = &$this->pagination;

$saveOrderingUrl = 'index.php?option=com_bookpro&controller=airports&task=saveOrderAjax&tmpl=component';
JHtml::_('sortablelist.sortable', 'airportList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		}
		else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<div class="span10">
<form action="index.php?option=com_bookpro&view=airports" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search fltlft form-inline">
			 <input class="text_area" type="text" name="title" id="title" size="20" maxlength="255" value="<?php echo $this->lists['title']; ?>"  placeholder="<?php echo JText::_('Destination')?>"/>
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		</div>
		
		<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		
	</div>
	
		<table class="table-striped table" id="airportList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					
						<th width="2%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
					
					<th width="2%">
				        <?php echo JHTML::_('grid.sort',JText::_('JSTATUS'), 'state', $orderDir, $order); ?>
					</th>
					
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_AIRPORT_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					
					<th width="5%">
				        <?php echo JText::_('Image'); ?>
					</th>
					<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_AIRPORT_IATA_CODE'); ?>
					</th>
					<th width="5%">
				        <?php echo JText::_('Router type'); ?>
					</th>
					<th width="5%"><?php echo JText::_('COM_BOOKPRO_FLIGHT_RATE_MANAGER'); ?>
					</th>
						
					
					<th width="5%">
				        <?php echo JText::_('Country'); ?>
					</th>
					
					<th width="1%" align="right" >
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
    			<tr>
    				<td colspan="7">
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
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_AIRPORT, $subject->id));
				    		
				    		if ($subject->level > 1)
				    		{
				    			$parentsStr = "";
				    			$_currentParentId = $subject->parent_id;
				    			$parentsStr = " " . $_currentParentId;
				    			for ($i2 = 0; $i2 < $subject->level; $i2++)
				    			{
					    			foreach ($this->ordering as $k => $v)
					    			{
						    			$v = implode("-", $v);
					    				$v = "-" . $v . "-";
					    				if (strpos($v, "-" . $_currentParentId . "-") !== false)
					    				{
						    				$parentsStr .= " " . $k;
						    				$_currentParentId = $k;
						    				break;
					    				}
				    				}
			    				}
			    			}
		    				else
		    				{
			    				$parentsStr = "";
			    			}
				    		
				    		
				?>
				    	<tr>
				    		<td class="order nowrap center hidden-phone">
								<?php
								$iconClass = '';
								if (!$saveOrder)
								{
									$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
								}
								?>
								<span class="sortable-handler<?php echo $iconClass ?>">
								<i class="icon-menu"></i>
								</span>
								<?php if ($saveOrder) : ?>
									<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey + 1; ?>" />
								<?php endif; ?>
							</td>
				    			
				    		<td class="hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $subject->id); ?>
							</td>	
				    		<td>
							<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'airports.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
				    			
				    		
				    			
				    		
					    		<?php echo str_repeat('<span class="gi">&mdash;</span>', $subject->level - 1) ?>
					    		<a href="<?php echo JRoute::_('index.php?option=com_bookpro&task=airport.edit&id='.$subject->id);?>"><?php echo $subject->title; ?></a>
					    		
				    		</td>
				    		
				    		<td>
				    			                 
						        <img style="width: 70px;" src="<?php  echo JUri::root().'/'.$subject->image;?>">
						        	
				    		</td>

				    		<td>

						        <?php  echo $subject->code;?>

				    		</td>
				    		<td>

						        <?php
                                $listRouteType=array();
                                if($subject->bus)
                                    $listRouteType[]= JText::_('Bus');
                                if($subject->air)
                                    $listRouteType[]= JText::_('Flight');
                                if($subject->tour)
                                    $listRouteType[]= JText::_('Tour');

                                echo implode(',',$listRouteType);
                                ?>

				    		</td>
				    		<td><?php $linkr = ARoute::view('airportairline',null,null,array('dest_id'=>$subject->id));?>
								<a href="<?php echo $linkr;?>" title="New"><i class="icon-pencil icon-large"></i>Add</a> 
									
								
								<?php 
									//$view_airline = AirportHelper::countAirlineByDest($subject->id);
									if ($view_airline){
								?>
								<?php $linkview = ARoute::view('flightrates',null,null,array('flight_id'=>$subject->id));?>
								<a href="<?php echo $linkview;?>" title="Edit"><i
									class="icon-calendar icon-large"></i>View</a>
								<?php } ?>		
							</td>
				    		
				    		<td>
						        <?php  echo $subject->country_name;?>
				    		</td>
				    		<td><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php 
				    	}
					} 
					?>
			</tbody>
		</table>
		
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value=""/>
		<input type="hidden" name="view" value="airports" />
	<input type="hidden" name="reset" value="0"/>
	
	<input type="hidden" name="boxchecked" value="0"/>
	
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>