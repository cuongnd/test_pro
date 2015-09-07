<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(18);
JToolBarHelper::title(JText::_('Region Manager'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();

JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();

JToolBarHelper::deleteList('', 'trash', 'Trash');

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;

?>
<div class="span10>"
<form action="index.php" method="post" name="adminForm" id="adminForm">
	
	<div id="editcell">
		<table class="adminlist" >
			<thead>
				<tr>
					<th width="2%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
														
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="checkAll(<?php echo $itemsCount; ?>);" />
							
						</th>
					<?php } ?>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_REGION'), 'name', $orderDir, $order); ?>
					</th>
									
					
					<th width="5%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_STATE'), 'state', $orderDir, $order); ?>
					</th>
					<th width="5%">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
    			<tr>
    				<td colspan="6">
    				    <?php echo $pagination->getListFooter(); ?>
    				</td>
    			</tr>
			</tfoot>
			<tbody>
				<?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
					<tr><td colspan="5" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php 
				
					} else {
												
						 for ($i = 0; $i < $itemsCount; $i++) { 
				    	 	$subject = &$this->items[$i]; 
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_REGION, $subject->id));
				    		$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
				    		
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td >
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->name; ?></a>
					    		
				    		</td>
				    							    		
				    		    		
				    		
				    		<td style="text-align: center;"><?php echo AHtml::state($subject, $i, ! $element); ?></td>
				    		
				    		<td style="text-align: center; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
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
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_REGION; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>