<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/showmybid.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/LightFace.js");
 $doc->addScript("components/com_jblance/js/light.js");
 
 $model 				= $this->getModel();
 $user					=& JFactory::getUser();
 $config 				=& JblanceHelper::getConfig();
 $projhelp 				= JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 
 $currencysym 			= $config->currencySymbol;
 $currencycode 			= $config->currencyCode;
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $checkFund 			= $config->checkFund;
 
 $curr_balance 			= JblanceHelper::getTotalFund($user->id);
 
 JText::script('COM_JBLANCE_INSUFFICIENT_FUND');
 JText::script('COM_JBLANCE_DEPOSIT_FUNDS');
 JText::script('COM_JBLANCE_CLOSE');
?>
<Script>
var link_deposit = "<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);?>";
</Script>
<form action="index.php" method="post" name="userForm" class="minheight">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_BIDS'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<thead>
			<tr class="jbl_rowhead">
				<th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BIDS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BID_STATUS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<?php if($enableEscrowPayment) { ?><th><?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS'); ?></th><?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for($i=0, $n=count($this->rows); $i < $n; $i++){
				$row 			  = $this->rows[$i];
				$link_accept_bid  = JRoute::_('index.php?option=com_jblance&task=project.acceptbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_deny_bid	  = JRoute::_('index.php?option=com_jblance&task=project.denybid&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_retract_bid = JRoute::_('index.php?option=com_jblance&task=project.retractbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_edit_bid    = JRoute::_('index.php?option=com_jblance&view=project&layout=placebid&id='.$row->proj_id);
				$link_proj_detail = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->proj_id);
				
				$projectFee = $projhelp->calculateProjectFee($user->id, $row->amount, 'freelancer');
			?>
			<tr class="jbl_<?php echo "row$k"; ?>">
				<td><a href="<?php echo $link_proj_detail;?>"> <?php echo $row->project_title; ?></a></td>
				<td><?php echo JText::_($row->proj_status); ?></td>
				<td><?php echo $currencysym.' '.$row->amount; ?></td>
				<td><?php echo JText::_($row->status); ?></td>
				<td>
				<?php
				if($row->assigned_userid == $user->id){
					if($row->status == ''){ ?>
						<!-- check if the user has enough fund and check fund is enabled to accept the bid -->
						
						<?php echo JText::_('COM_JBLANCE_BID_WON'); ?> - 
						<?php 
						if($checkFund && ($curr_balance < $projectFee)){ 
							$insuffMsg = JText::sprintf('COM_JBLANCE_INSUFFICIENT_BALANCE_TO_ACCEPT_THIS_OFFER', JblanceHelper::formatCurrency($curr_balance, $currencysym), JblanceHelper::formatCurrency($projectFee, $currencysym));
						?>
						<a href="javascript:void(0);" onclick="insufficientFund('<?php echo $insuffMsg; ?>');"><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
						<?php 
						} else { ?>
						<a href="<?php echo $link_accept_bid; ?>"><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
						<?php } ?> <!-- end of check fund -->
						 / 
						<a href="<?php echo $link_deny_bid; ?>"><?php echo JText::_('COM_JBLANCE_DENY'); ?></a>
				<?php	
					}
					elseif($row->status == 'COM_JBLANCE_ACCEPTED'){
						//get id rate first
						$rate =  $model->getRate($row->project_id, $row->publisher_userid);
						if($rate->quality_clarity == 0){
							$link_rate = JRoute::_('index.php?option=com_jblance&view=project&layout=rateuser&id='.$rate->id); ?>
							<a href="<?php echo $link_rate; ?>"><?php echo JText::_('COM_JBLANCE_RATE_BUYER'); ?></a>
				<?php			
						}
					}
				}
				else { ?>
					<a href="<?php echo $link_retract_bid; ?>"><?php echo JText::_('COM_JBLANCE_RETRACT_BID'); ?></a> / 
					<a href="<?php echo $link_edit_bid; ?>"><?php echo JText::_('COM_JBLANCE_EDIT_BID'); ?></a>
				<?php
				}
				?>
				</td>
				<?php if($enableEscrowPayment) { ?>
				<td style="text-align:center">
					<?php 
					if($row->status == 'COM_JBLANCE_ACCEPTED'){
						$perc = ($row->paid_amt/$row->amount)*100;
						echo round($perc, 2).'%';
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
			} ?>
		</tbody>
	</table>
</form>