<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(2);

JToolBarHelper::title(JText::_('COM_BOOPRO_CATEGORY_MANAGER'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();

JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();

JToolBarHelper::deleteList('', 'trash', 'Trash');
$colspan = $this->selectable ? 7 : 10;
$editSubject = $this->escape(JText::_('COM_BOOPRO_CATEGORY_EDIT'));
$notFound = '- ' . JText::_('not found') . ' -';

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;

?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar" class='form-inline'>
			<div class="filter-search fltlft">
				<label for="Airline"><?php echo JText::_('COM_BOOKPRO_CATEGORIES_TYPE'); ?>: </label>
				<?php echo JHTML::_('select.genericlist', BookProHelper::getCatType(), 'type', 'onchange="this.form.submit()"', 'value', 'text', $this->lists['type']) ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="clearfix"></div>
		</fieldset>
	<div id="editcell">
		<table class="adminlist table-striped table" cellspacing="1">
			<thead>
				<tr>
					<th width="1%">#</th>
					<?php if (! $this->selectable) { ?>
					<th width="1%"><input type="checkbox" class="inputCheckbox"
						name="toggle" value=""
						onclick="Joomla.checkAll(this);" />
					</th>
					<?php } ?>
					<th width="2%">
				        <?php echo JHTML::_('grid.sort',JText::_('JSTATUS'), 'state', $orderDir, $order); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_CATEGORY_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					
					
					<th width="5%">
				        	<?php 
				        		echo JHTML::_('grid.sort', JText::_('COM_BOOPRO_ORDER'), 'ordering', $orderDir, $order);
				        		if ($this->turnOnOrdering) {
									echo JHTML::_('grid.order', $this->items);
								} 
							?>
						</th>
					
					<th style="text-align: right; white-space: nowrap;" width="5%">
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
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_CATEGORY, $subject->id));
				    		$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
				    		
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td class="center">
							<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'categories.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
					    		
				    		</td>
				    							    		
				    		
				    		<?php if (! $this->selectable) { ?>
				    			<td class="order"><?php echo AHtml::orderTree($this->items,$i, $this->pagination, $this->turnOnOrdering, $itemsCount); ?></td>
				    		<?php } ?>
				    		
				    		
				    		
				    			<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
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
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CATEGORY; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>