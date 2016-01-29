<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 July 2012
 * @file name	:	views/admconfig/tmpl/showbudget.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows Budget Range (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">	
	<table class="<?php echo $tableClass; ?>">
	<thead>
		<tr>
			<th width="10">
				<?php echo JText::_('#'); ?>
			</th>
			<th width="10" >
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>
			<th width="" align="left">
				<?php echo JText::_('COM_JBLANCE_BUDGET_RANGE'); ?>
			</th>
			 <th width="10%">
 				<?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
				<?php echo JHTML::_('grid.order', $this->rows, 'filesave.png', 'admconfig.saveorder'); ?>
 			</th>
 			<th width="5%">
 				<?php echo JText::_('JPUBLISHED'); ?>
 			</th>
 			<th width="5%">
 				<?php echo JText::_('JGRID_HEADING_ID'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];

		$link_edit	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editbudget&cid[]='. $row->id);
		$row->checked_out = 0;
		$checked 	= JHTML::_('grid.checkedout', $row, $i);
		$published = JHTML::_('jgrid.published', $row->published, $i, 'admconfig.');
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pageNav->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link_edit?>"><?php echo $row->title.' ('.$currencysym.number_format($row->budgetmin).' - '.$currencysym.number_format($row->budgetmax).')'; ?></a>					
			</td>										
			<td class="order">
 				<span><?php echo $this->pageNav->orderUpIcon($i, true, 'admconfig.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon($i, $n, true, 'admconfig.orderdown', 'JLIB_HTML_MOVE_DOWN', true ); ?></span>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
 			</td>
 			<td align="center">
 				<?php echo $published; ?>
 			</td>
 			<td>
 				<?php echo $row->id; ?>
			</td>										
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>

	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admconfig" />
	<input type="hidden" name="layout" value="showbudget" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctype" value="budget" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>