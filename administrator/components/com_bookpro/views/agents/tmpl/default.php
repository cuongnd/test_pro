<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 63 2012-07-29 10:43:08Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewReservations */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(4);

JToolBarHelper::title(JText::_('COM_BOOKPRO_AGENT_MANAGER'), 'user.png');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 9 : 10;

$editAgent = JText::_('COM_BOOKPRO_AGENT_EDIT');

$viewAgent = JText::_('COM_BOOKPRO_AGENT_VIEW_DETAIL');
$titleEditAcount = JText::_('COM_BOOKPRO_AGENT_EDIT_USER_ACOUNT');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$pagination = &$this->pagination;

?><div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="filterTable">
		<tr>
			<td>
		        <label for="firstname"><?php echo JText::_('COM_BOOKPRO_AGENT_FIRST_NAME'); ?>: </label>
				<input type="text" name="firstname" id="firstname" class="filterInput" onchange="this.form.submit();" value="<?php echo $this->escape($this->lists['firstname']); ?>"/>
								
				<button onclick="this.form.submit();"><?php echo JText::_('COM_BOOKPRO_GO'); ?></button>
				<button onclick="this.form.reset.value=1;this.form.submit();"><?php echo JText::_('COM_BOOKPRO_RESET'); ?></button>
			</td>
		</tr>

	</table>
	<div id="editcell">
		<table class="adminlist" cellspacing="1">
			<thead>
				<tr>
					<th width="3%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="checkAll(<?php echo $itemsCount; ?>);" />
						</th>
					<?php } ?>	
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_AGENT_NAME'), 'name', $orderDir, $order); ?>
					</th>
					
					<th width="5%">
				        <?php echo JText::_('Status'); ?>
					</th>
					<th width="4%"><?php echo JText::_('COM_BOOKPRO_AGENT_EMAIL'); ?></th>
					<th width="8%"><?php echo JText::_('COM_BOOKPRO_AGENT_PHONE'); ?></th>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_AGENT_FAX'); ?>
					</th>
					<th width="10%">
				        <?php echo JHTML::_('grid.sort', 'COM_BOOKPRO_AGENT_CREATED_DATE', 'created', $orderDir, $order); ?>
					</th>
					<th width="4%">
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
				<?php if (! is_array($this->items) || ! $itemsCount) { ?>
					<tr><td colspan="<?php echo $colspan; ?>"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php } else { ?>
				    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
				    	<?php $subject = &$this->items[$i]; ?>
				    	<?php /* @var $item TableCustomer */ ?>
				    	<?php $isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out); ?>     
				    	<tr class="row<?php echo ($i % 2); ?>">
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    		<?php if (! $this->selectable) { ?>
				    			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    		<?php } ?>
				    		<td>
				    			
				                <span class="editlinktip hasTip" title="<?php echo $viewAgent; ?>::<?php echo $subject->company; ?>">
											<a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_AGENT, $subject->id)); ?>"><?php echo $subject->company; ?></a>
						         </span>
				    				
				    		</td>
				    					    		
				    		<td style="text-align: center;"><?php echo AHtml::enabled($subject); ?></td>
				    		<td class="emailCell"><?php echo $subject->email; ?>&nbsp;</td>
				    		<td><?php echo $subject->telephone; ?>&nbsp;</td>
				    		<td><?php echo $subject->fax; ?>&nbsp;</td>
				    		<td><?php echo $subject->created; ?>&nbsp;</td>
				    		
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
		</table>
		<table align="center">
			<tr align="center">
				<td class="aIconLegend aIconTick"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?><span class="aIconSeparator">&nbsp;</span></td>
				<td class="aIconLegend aIconUnpublish"><?php echo JText::_('COM_BOOKPRO_BLOCK'); ?><span class="aIconSeparator">&nbsp;</span></td>

			</tr>
		</table>
		<div class="clr">&nbsp;</div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<?php $tmpl = JRequest::getCmd('tmpl'); ?>
	<?php if ($tmpl) { ?>
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
	<?php } ?>	
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_AGENT; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form></div>