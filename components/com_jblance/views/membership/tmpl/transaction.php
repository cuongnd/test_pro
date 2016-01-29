<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/membership/tmpl/transaction.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows user transactions (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 $config =& JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $currencysym = $config->currencySymbol;
 $action	= JRoute::_('index.php?option=com_jblance&view=membership&layout=transaction');
?>
<form action="<?php echo $action; ?>" method="post" name="userFormJob" enctype="multipart/form-data">	
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_TRANSACTION_HISTORY'); ?></div>
	<p class="font16"><b><?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE'); ?> : <?php echo $currencysym.' '.$this->total_amt; ?></b></p>
	<div class="border">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr class="jbl_rowhead">
				<th colspan="3"><?php echo JText::_('COM_JBLANCE_LAST_TRANSACTION'); ?></th>
			</tr>
			<tr class="jbl_row0">
				<td width="25%"><?php echo JText::_('COM_JBLANCE_DATE'); ?></td>
				<td width="10">:</td>
				<td width="75%"><?php  echo JHTML::_('date', $this->last_trans->date_trans, $dformat); ?></td>
			</tr>
			<tr class="jbl_row1">
				<td><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?></td>
				<td>:</td>
				<td><?php echo $this->last_trans->transaction; ?></td>
			</tr>
			<tr class="jbl_row0">
				<?php
					if($this->last_trans->fund_plus > 0){
						$title = JText::_('COM_JBLANCE_PLUS');
						$value = $this->last_trans->fund_plus;
					}
					else {
						$title = JText::_('COM_JBLANCE_MINUS');
						$value = $this->last_trans->fund_minus;
					}
				?>
				<td><?php echo $title; ?></td>
				<td>:</td>
				<td><?php echo $currencysym.' '.number_format($value, 2, '.', '' ); ?></td>
			</tr>
		</table>
	</div>
	<div class="sp20">&nbsp;</div>
		
	<div class="border">
		<table width="100%" cellpadding="0" cellspacing="0">
		<thead>
			<tr class="jbl_rowhead">
				<th>
					<?php echo JText::_('#'); ?>
				</th>
				<th width="12%" align="left">
					<?php echo JText::_('COM_JBLANCE_DATE'); ?>
				</th>
				<th width="68%" align="left">
					<?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>
				</th>
				<th width="10%" align="left">
					<?php echo JText::_('COM_JBLANCE_PLUS').' ('.$currencysym.')'; ?>
				</th>
				<th width="10%" align="left">
					<?php echo JText::_('COM_JBLANCE_MINUS').' ('.$currencysym.')'; ?>
				</th>				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6" class="jbl_row3">
					<?php echo $this->pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		for($i=0, $n=count($this->rows); $i < $n; $i++){
			$row = $this->rows[$i];
			?>
			<tr class="jbl_<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php  echo JHTML::_('date', $row->date_trans, $dformat); ?>				
				</td>
				<td>
					<?php echo $row->transaction; ?>
				</td>
				<td style="text-align: right;">
					<?php echo $row->fund_plus > 0  ? number_format($row->fund_plus, 2, '.', '' ) : " "; ?> 
				</td>
				<td style="text-align: right;">
					<?php echo $row->fund_minus > 0  ? number_format($row->fund_minus, 2, '.', '' ) : " "; ?> 
				</td>				
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		</table>
	</div>

	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>