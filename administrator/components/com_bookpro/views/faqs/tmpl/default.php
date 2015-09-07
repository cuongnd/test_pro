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


JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');


//$colspan = $this->selectable ? 9 : 10;

$editCustomer = JText::_('COM_BOOKPRO_AIRLINE_EDIT');
$titleEditAcount = JText::_('COM_BOOKPRO_AIRLINE_EDIT');


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);

$pagination = &$this->pagination;

?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	
		<table class="table" cellspacing="1">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					
					<th class="title" width="30%">
				        <?php echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'title', $orderDir, $order); ?>
					</th>
                    <th width="15%">
                        <?php echo JText::_('COM_BOOKPRO_TOUR_TITLE'); ?>
                    </th>
					<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_FAQ_TYPE'); ?>
					</th>
                                        <!--
					<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_OBJ_ID'); ?>
					</th>
                                        -->
					<th width="4%">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
										
				</tr>
			</thead>
			<tfoot>
    			<tr>
    				<!--<td colspan="5">
                        <?php echo $this->pagination->getLimitBox(); ?>
    				</td>   -->
    			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>

					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'faqs.', true, 'cb', $item->publish_up, $item->publish_down); ?>
					</td>
					<td>
						<?php if ($item->checked_out) { ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'location.', true); ?>
						<?php } ?>
						<a href="<?php echo JRoute::_('index.php?option=com_bookpro&task=faq.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->title); ?>
						</a>

					</td>
                    <td>
                    <?php echo $item->tourtitle ?>
                    </td>
					<td>
					<?php echo $item->type ?>
					</td>
                                        <!--
					<td>
					<?php echo $item->obj_id ?>
					</td>
                                        -->
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
		</table>
		
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<?php $tmpl = JRequest::getCmd('tmpl'); ?>
	<?php if ($tmpl) { ?>
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
	<?php } ?>	
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_FAQ; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>