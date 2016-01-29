<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	17 April 2012
 * @file name	:	views/membership/tmpl/managepay.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Manage deposits, withdrawals and Escrow payments (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.modal');
 
 $model 				= $this->getModel();
 $config 				=& JblanceHelper::getConfig();
 $dformat 				= $config->dateFormat;
 $currencysym 			= $config->currencySymbol;
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $enableWithdrawFund 	= $config->enableWithdrawFund;
 
 $action	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay');
?>
<form action="<?php echo $action; ?>" method="post" name="userFormJob" enctype="multipart/form-data" class="minheight">	
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MANAGE_PAYMENTS'); ?></div>
	<?php 
	echo JHtml::_('tabs.start', 'panel-tabs', array('useCookie'=>'1'));
	$countEscrowOut = $model->countManagePayPending('escrowout');
	$newTitle = ($countEscrowOut > 0) ? ' <span class="redfont">(<b>'.$countEscrowOut.'</b>)</span>' : '';
	
	//check if escrow is enabled
	if($enableEscrowPayment){
		echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_OUTGOING_ESCROW_PAYMENTS').$newTitle, 'escrowout'); ?>
	<?php 
	if(count($this->escrow_out)) : ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<tr class="jbl_rowhead">
			<th>#</th>
			<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_RECEIVER'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_PROJECT'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>	
			<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
		</tr>			
		<?php
		$k = 0;
		for($i=0, $n=count($this->escrow_out); $i < $n; $i++){
			$escout 		= $this->escrow_out[$i];
			$link_release 	= JRoute::_('index.php?option=com_jblance&task=membership.releaseescrow&id='.$escout->id.'&'.JSession::getFormToken().'=1');
		?>
		<tr class="jbl_row<?php echo $k; ?>">
			<td>
				<?php echo $i+1;?>
			</td>
			<td>
				<?php  echo JHTML::_('date', $escout->date_transfer, $dformat); ?>
			</td>
			<td>
				<?php 
				$receiver = JFactory::getUser($escout->to_id);
				echo $receiver->username;			
				?>
			</td>
			<td>
				<?php  echo ($escout->project_title) ? $escout->project_title : JText::_('COM_JBLANCE_NA'); ?>
			</td>
			<td style="text-align: right;">
				<?php  echo number_format($escout->amount, 2); ?>
			</td>
			<td>
				<?php if($escout->status == '') : ?>
				<a href="<?php echo  $link_release; ?>"><?php echo JText::_('COM_JBLANCE_RELEASE'); ?></a>
				<?php endif; ?>
			</td>
			<td>
				<?php
				echo ($escout->status == '') ? JText::_('COM_JBLANCE_PENDING'): JText::_($escout->status);
				?>
			</td>
		</tr>
		<?php 
			$k = 1 - $k;
		} ?>
	</table>
	<?php 
		else : 
			echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
		endif;	//end of count escrow
	}		//end of escrow enabled
	?>
	<?php
	$countEscrowIn = $model->countManagePayPending('escrowin');
	$newTitle = ($countEscrowIn > 0) ? ' <span class="redfont">(<b>'.$countEscrowIn.'</b>)</span>' : '';
	//check if escrow is enabled
	if($enableEscrowPayment){
		echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_INCOMING_ESCROW_PAYMENTS').$newTitle, 'escrowin'); ?>
	<?php
	if(count($this->escrow_in)) : ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<tr class="jbl_rowhead">
			<th>#</th>
			<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_SENDER'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_PROJECT'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>
			<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
		</tr>
	<?php
	$k = 0;
	for($i=0, $n=count($this->escrow_in); $i < $n; $i++){
	$escin 		= $this->escrow_in[$i];
	$link_accept 	= JRoute::_('index.php?option=com_jblance&task=membership.acceptescrow&id='.$escin->id.'&'.JSession::getFormToken().'=1');
	?>
		<tr class="jbl_row<?php echo $k; ?>">
			<td>
				<?php echo $i+1;?>
			</td>
			<td>
				<?php  echo JHTML::_('date', $escin->date_transfer, $dformat); ?>
			</td>
			<td>
				<?php
				$sender = JFactory::getUser($escin->from_id);
				echo $sender->username;
				?>
			</td>
			<td>
				<?php  echo ($escin->project_title) ? $escin->project_title : JText::_('COM_JBLANCE_NA'); ?>
			</td>
			<td style="text-align: right;">
				<?php  echo number_format($escin->amount, 2); ?>
			</td>
			<td>
				<?php if($escin->status == 'COM_JBLANCE_RELEASED') : ?>
				<a href="<?php echo  $link_accept; ?>"><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
				<?php endif; ?>
			</td>
			<td>
				<?php
				echo ($escin->status == '') ? JText::_('COM_JBLANCE_PENDING'): JText::_($escin->status);
				?>
			</td>
		</tr>
	<?php
	$k = 1 - $k;
	} ?>
	</table>
	<?php
		else :
			echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
		endif;	//end of escrow count
	}		//end of escrow enabled
	?>
	<?php
	$countWithdraw = $model->countManagePayPending('withdraw');
	$newTitle = ($countWithdraw > 0) ? ' <span class="redfont">(<b>'.$countWithdraw.'</b>)</span>' : '';
	
	//check if withdraw fund is enabled
	if($enableWithdrawFund){
		echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_WITHDRAWALS').$newTitle, 'withdrawals'); ?>
	<?php
	if(count($this->withdraws)) : ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<tr class="jbl_rowhead">
			<th>#</th>
			<th><?php echo JText::_('COM_JBLANCE_REQUESTED_AT'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>
			<th><?php echo JText::_('COM_JBLANCE_WITHDRAWAL_FEE').' ('.$currencysym.')'; ?></th>
			<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			<th></th>
		</tr>
		<tfoot>
			<tr>
				<td colspan="7" class="jbl_row3">
					<?php echo $this->pageNavWithdraw->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	<?php
	$k = 0;
	for($i=0, $n=count($this->withdraws); $i < $n; $i++){
		$withdraw 		= $this->withdraws[$i];
		$link_invoice =  JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$withdraw->id.'&tmpl=component&print=1&type=withdraw');
	?>
		<tr class="jbl_row<?php echo $k; ?>">
			<td>
				<?php echo $i+1;?>
			</td>
			<td>
				<?php  echo JHTML::_('date', $withdraw->date_withdraw, $dformat); ?>
			</td>
			<td style="text-align: right;">
				<?php  echo number_format($withdraw->amount, 2); ?>
			</td>
			<td style="text-align: right;">
				<?php  echo number_format($withdraw->withdrawFee, 2); ?>
			</td>
			<td>
				<?php echo JblanceHelper::getApproveStatus($withdraw->approved); ?>
			</td>
			<td class="jb-aligncenter">
				<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $link_invoice; ?>" class="modal"><img src="components/com_jblance/images/print.png" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" width="18" alt="Print"/></a>
			</td>
		</tr>
	<?php
	$k = 1 - $k;
	} ?>
	</table>
	<?php
		else :
			echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
		endif;	//end of withdraw count
	}		//end of escrow withdraw
	?>
	<?php
	$countDeposit = $model->countManagePayPending('deposit');
	$newTitle = ($countDeposit > 0) ? ' <span class="redfont">(<b>'.$countDeposit.'</b>)</span>' : '';
	echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_DEPOSITS').$newTitle, 'deposits'); ?>
	<?php
	if(count($this->deposits)) : ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<tr class="jbl_rowhead">
			<th>#</th>
			<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?></th>
			<th><?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE').' ('.$currencysym.')'; ?></th>
			<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			<th></th>
		</tr>
		<tfoot>
			<tr>
				<td colspan="7" class="jbl_row3">
					<?php echo $this->pageNavDeposit->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	<?php
	$k = 0;
	for($i=0, $n=count($this->deposits); $i < $n; $i++){
		$deposit 	  = $this->deposits[$i];
		$link_invoice =  JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$deposit->id.'&tmpl=component&print=1&type=deposit');
	?>
		<tr class="jbl_row<?php echo $k; ?>">
			<td>
				<?php echo $i+1;?>
			</td>
			<td>
				<?php  echo JHTML::_('date', $deposit->date_deposit, $dformat); ?>
			</td>
			<td class="jb-alignright">
				<?php  echo number_format($deposit->amount, 2); ?>
			</td>
			<td class="jb-alignright">
				<?php  echo number_format($deposit->total-$deposit->amount, 2); ?>
			</td>
			<td>
				<?php echo JblanceHelper::getApproveStatus($deposit->approved); ?>
			</td>
			<td class="jb-aligncenter">
				<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $link_invoice; ?>" class="modal"><img src="components/com_jblance/images/print.png" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" width="18" alt="Print"/></a>
			</td>
		</tr>
	<?php
	$k = 1 - $k;
	} ?>
	</table>
	<?php
	else :
	echo JText::_('COM_JBLANCE_NO_PENDING_PAYMENTS_FOUND');
	endif;
	?>
	<?php echo JHtml::_('tabs.end'); ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>