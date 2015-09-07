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

//BookProHelper::setSubmenu(2);

//JToolBarHelper::title(JText::_('Customers Staff'), 'user.png');
//JToolBarHelper::addNew();
//JToolBarHelper::editList();
//JToolBarHelper::divider();
//JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 9 : 10;

$editCustomer = JText::_('Edit Staff');

$viewCustomer = JText::_('View staff detail');
$titleEditAcount = JText::_('Edit staff user acount');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$pagination = &$this->pagination;

?>
<div class="span12" >
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="filterTable">
		<tr>
			<td>
		        <label for="firstname"><?php echo JText::_('First name'); ?>: </label>
				<input type="text" name="firstname" id="firstname" class="filterInput" onchange="this.form.submit();" value="<?php echo $this->escape($this->lists['firstname']); ?>"/>
								
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="this.form.reset.value=1;this.form.submit();"><?php echo JText::_('Reset'); ?></button>
			</td>
		</tr>
		
	</table>
	<div id="editcell">
		<table class="adminlist table-striped table" cellspacing="1">
			<thead>
				<tr>
					<th width="3%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
					<?php } ?>	
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', 'First name', 'firstname', $orderDir, $order); ?>
					</th>
					<th width="10%">
				        <?php echo JHTML::_('grid.sort', 'Last name', 'lastname', $orderDir, $order); ?>
					</th>
					<th width="4%"><?php echo JText::_('Email'); ?></th>
					<th width="8%"><?php echo JText::_('Phone'); ?></th>
					<th width="10%">
				        <?php echo JText::_('Fax'); ?>
					</th>
					<th width="10%">
				        <?php echo JHTML::_('grid.sort', 'Created', 'created', $orderDir, $order); ?>
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
				   		<?php $name = BookProHelper::formatName($subject, true); ?> 
				    	<?php $isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out); ?>     
				    	<tr class="row<?php echo ($i % 2); ?>">
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    		<?php if (! $this->selectable) { ?>
				    			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    		<?php } ?>
				    		<td>
				    			
				                <span class="editlinktip hasTip" title="<?php echo $viewCustomer; ?>::<?php echo $name; ?>">
											<a href="<?php echo JRoute::_(ARoute::Edit(CONTROLLER_CUSTOMER, $subject->id)); ?>"><?php echo $subject->firstname; ?></a>
						         </span>
				    				
				    		</td>
				    		<td><?php echo $subject->lastname; ?>&nbsp;</td>				    		
				    		<td style="text-align: center;"><?php echo AHtmlFrontEnd::state($subject, $i, ! $element); ?></td>
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

		<div class="clr">&nbsp;</div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<?php $tmpl = JRequest::getCmd('tmpl'); ?>
	<?php if ($tmpl) { ?>
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
	<?php } ?>	
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CUSTOMER; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>
</div>