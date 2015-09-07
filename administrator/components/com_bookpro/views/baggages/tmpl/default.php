<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(1);

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
AImporter::helper('currency','flightadministrator');

?>
<div class="span10">
<form action="index.php?option=com_bookpro&view=baggages" method="post" name="adminForm" id="adminForm">
	<div class="form-inline">
	
	<?php echo $this->airlines;?>
	
	<button onclick="this.form.submit();" class="btn btn-success"><?php echo JText::_('COM_BOOKPRO_BAGGAGE_SEARCH'); ?></button>
	
	</div>
	
		<table class="table table-stripped">
			<thead>
				<tr>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
														
							
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
					<?php } ?>
					<th width="5%">
			         <?php echo JHTML::_('grid.sort', 'State', 'state', $orderDir, $order); ?>
					</th>
					<th class="title">
				        <?php echo JHTML::_('grid.sort', 'Qty', 'qty', $orderDir, $order); ?>
					</th>
						
					
					<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_BAGGAGE_AIRLINE'); ?>
					</th>
					
					<th width="20%">
				        <?php echo JText::_('COM_BOOKPRO_FLIGHT_PRICE'); ?>
					</th>
					
					
					<th width="3%" align="right">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
    			<tr>
    				<td colspan="10">
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
				    		
				    		
				?>
				    	<tr>
				    		<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<?php if (! $this->selectable) { ?>
				    			
				    			<td>
				    			<div class="btn-group">
				    			<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'flights.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
								
								</div>
				    			
				    		<?php } ?>	
				    		<td>
					    		<a href="<?php echo JRoute::_('index.php?option=com_bookpro&task=baggage.edit&id='.$subject->id);?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->qty; ?></a>
					    		
				    		</td>
				    		
				    		<td>
				    			<?php  echo $subject->airline_name;?>
				    		</td>
				    					    	
				    		<td>
				    			                 
						        <?php  echo CurrencyHelper::displayPrice($subject->price);?>
				    		</td>
				    		
				    		
				    		
				    		<td style="text-align: left; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php 
				    	}
					} 
					?>
			</tbody>
		</table>
		
		<div class="clr"></div>
	
<input type="hidden" name="task" value="" />

<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>	
</div>
	