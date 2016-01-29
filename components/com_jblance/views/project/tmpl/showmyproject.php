<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/showmyproject.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
defined('_JEXEC') or die('Restricted access');

$model					= $this->getModel();
$config 				=& JblanceHelper::getConfig();
$enableEscrowPayment 	= $config->enableEscrowPayment;
?>
<form action="index.php" method="post" name="userForm" class="minheight">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_PROJECTS'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<thead>
			<tr class="jbl_rowhead">
				<th><?php echo JText::_('#'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BIDS'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<?php if($enableEscrowPayment) { ?><th><?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS'); ?></th><?php } ?>
			</tr>
		</thead>
	<tfoot>
		<tr >
			<td colspan="6" class="jbl_row3">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
		$k = 0;
		$n=count($this->rows);
		for ($i=0;  $i < $n; $i++) {
			$row = $this->rows[$i];
	
			$link_proj_detail = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id);
			$link_edit 		  = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject&id='.$row->id);	
			$link_pick_user	  = JRoute::_('index.php?option=com_jblance&view=project&layout=pickuser&id='.$row->id);
			$link_transfer	  = JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow');
			$link_del  		  = JRoute::_('index.php?option=com_jblance&task=project.removeproject&id='.$row->id.'&'.JSession::getFormToken().'=1');
			$link_reopen_proj = JRoute::_('index.php?option=com_jblance&task=project.reopenproject&id='.$row->id.'&'.JSession::getFormToken().'=1');
			$bidsCount 		  = $model->countBids($row->id);
			$bidInfo 		  = $model->getBidInfo($row->id, $row->assigned_userid);
			?>
			<tr class="jbl_<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<a href="<?php echo $link_proj_detail; ?>"><?php echo $row->project_title;?></a>
					<?php 
					if($row->approved == 0)
						echo '<small>('.JText::_('COM_JBLANCE_PENDING_APPROVAL').')</small>';
					?>
					<div class="fr">
			  			<?php if($row->is_featured) : ?>
			  			<img src="components/com_jblance/images/featured.png" alt="Featured" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_urgent) : ?>
			  			<img src="components/com_jblance/images/urgent.png" alt="Urgent" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_URGENT_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_private) : ?>
			  			<img src="components/com_jblance/images/private.png" alt="Private" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_sealed) : ?>
			  			<img src="components/com_jblance/images/sealed.png" alt="Sealed" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_nda) : ?>
			  			<img src="components/com_jblance/images/nda.png" alt="nda" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_NDA_PROJECT'); ?>" />
			  			<?php endif; ?>
		  			</div>
				</td>
				<td>
					<?php echo JText::_($row->status);?>
				</td>
				<td>
					<?php echo $bidsCount;?>
				</td>
				<td>
					<?php 
					if($row->status == 'COM_JBLANCE_OPEN'){ ?>
						<a href="<?php echo $link_edit; ?>"><?php echo JText::_('COM_JBLANCE_EDIT'); ?></a> /
						<a href="<?php echo $link_del; ?>"><?php echo JText::_('COM_JBLANCE_DELETE'); ?></a>
					<?php 
						if($bidsCount > 0){ ?> 
							/ <a href="<?php echo $link_pick_user;?>"><?php echo JText::_('COM_JBLANCE_PICK_USER'); ?></a>
					<?php 
						} ?>
					<?php 
					}
					elseif($row->status == 'COM_JBLANCE_CLOSED'){
						//get Rate
						$rate = $model->getRate($row->id, $row->assigned_userid);
						
						if($rate->quality_clarity == 0){
							$link_rate = JRoute::_('index.php?option=com_jblance&view=project&layout=rateuser&id='.$rate->id); ?>
						<a href="<?php echo $link_rate;?>"><?php echo JText::_('COM_JBLANCE_RATE_FREELANCER'); ?></a>
					<?php
						}
					}
					elseif($row->status == 'COM_JBLANCE_FROZEN'){
						//bid status check
						$detail_chosen = JFactory::getUser($row->assigned_userid);
						
						if($bidInfo->status == 'COM_JBLANCE_DENIED'){
							echo JText::_('COM_JBLANCE_STATUS_DENIED_BY').' - '.$detail_chosen->username.'<br/>';
						?>
							<a href="<?php echo $link_pick_user; ?>"><?php echo JText::_('COM_JBLANCE_PICK_USER'); ?></a>&nbsp;|&nbsp;
							<a href="<?php echo $link_reopen_proj; ?>"><?php echo JText::_('COM_JBLANCE_REOPEN'); ?></a>

						<?php
						}
						elseif($bidInfo->status == ''){
							echo JText::_('COM_JBLANCE_STATUS_WAITING'); ?>
							<br /><a href="<?php echo $link_reopen_proj; ?>"><?php echo JText::_('COM_JBLANCE_REOPEN'); ?></a>
						<?php
						}
					}?>
				</td>
				<?php if($enableEscrowPayment) { ?>
				<td style="text-align:center">
					<?php 
					if($row->status == 'COM_JBLANCE_CLOSED'){ 
						$perc = ($row->paid_amt/$bidInfo->bidamount)*100;
						echo round($perc, 2).'%';
						if($perc < 100){
					?>
					<a href="<?php echo $link_transfer; ?>"><?php echo JText::_('COM_JBLANCE_PAY_NOW'); ?></a>
					<?php
						}
					}
					else {
						echo JText::_('COM_JBLANCE_NA');
					}
					?>
				</td>
				<?php } ?>
			</tr>
		<?php
			$k = 1 - $k;
		}
		?>
		
	</tbody>
	</table>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="" />	
</form>