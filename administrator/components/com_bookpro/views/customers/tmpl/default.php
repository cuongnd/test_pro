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

BookProHelper::setSubmenu(2);

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::custom('assigngroup', 'assign.png', 'assign_f2.png', '', true);
JToolBarHelper::divider();
JToolBarHelper::deleteList('', 'trash', 'Trash');


$editCustomer = JText::_('COM_BOOKPRO_CUSTOMER_EDIT');

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$pagination = &$this->pagination;

?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	
      <fieldset id="filter-bar">
			<div class="filter-search fltlft">
				<div class="btn-group pull-left hidden-phone fltlft form-inline">					
					<input type="text" name="firstname" id="firstname" class="" onchange="this.form.submit();" value="<?php echo $this->escape($this->lists['firstname']); ?>" placeholder="<?php echo JText::_('Enter keyword')?>"/>
                    <?php
                        echo $this->groups;
                    ?>
                    <?php echo $this->countries ?>
                    
				</div>
				<div class="btn-group pull-left hidden-phone fltlft">
					<button onclick="this.form.submit();" class="btn">
						<?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
					</button>
				</div>
				
			</div>
			<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</fieldset>
		<table class="table-striped table">
			<thead>
				<tr>
					<th width="1%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="2%">
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
					<?php } ?>	
					<th width="1%" style="min-width: 55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_CUSTOMER_FIRST_NAME'), 'firstname', $orderDir, $order); ?>
					</th>
					<th width="10%">
				        <?php echo JHTML::_('grid.sort',JText::_('COM_BOOKPRO_CUSTOMER_LAST_NAME'), 'lastname', $orderDir, $order); ?>
					</th>
					
					<th width="4%"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?></th>
					<th width="8%"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?></th>
					<th width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_CUSTOMER_CREATED_DATE'), 'created', $orderDir, $order); ?>
					</th>
					<th style="text-align: right" width="4%">
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
					<tr><td colspan="10"><?php echo JText::_('No items found.'); ?></td></tr>
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
				    		<td class="center">
								<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'customers.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
				                <span>
										<a href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->id)); ?>"><?php echo $subject->firstname; ?></a>
						        </span>
				    		</td>
				    		<td><?php echo $subject->lastname; ?>&nbsp;</td>				    		
				    		<td class="emailCell"><?php echo $subject->email; ?></td>
				    		<td><?php echo $subject->telephone; ?>&nbsp;</td>
				    		<td><?php echo $subject->created; ?>&nbsp;</td>
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
		</table>
		
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<?php $tmpl = JRequest::getCmd('tmpl'); ?>
	<?php if ($tmpl) { ?>
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
	<?php } ?>	
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CUSTOMER; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>
</div>